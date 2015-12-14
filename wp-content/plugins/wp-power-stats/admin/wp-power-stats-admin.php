<?php

class PowerStatsAdmin
{
    public static $view_url = '';
    public static $config_url = '';
    public static $current_tab = 1;
    public static $faulty_fields = array();

    protected static $admin_notice = '<b>Enjoying this plugin?</b> Your <a href="http://www.websivu.com/wp-power-stats/" target="_blank">donations</a>, <a href="https://wordpress.org/support/view/plugin-reviews/wp-power-stats" target="_blank">reviews</a> and <a href="https://github.com/artifex404/wp-power-stats" target="_blank">contributions</a> are highly appreciated and they keep me going supporting and further developing this plugin!';

    /**
     * Initialize variables and actions
     */
    public static function init()
    {

        // Current screen
        if (!empty($_GET['page'])) {
            self::$current_tab = intval(str_replace('power-stats-view-', '', $_GET['page']));
        } elseif (!empty($_POST['current_tab'])) {
            self::$current_tab = intval($_POST['current_tab']);
        }

        // Settings URL
        self::$config_url = 'admin.php?page=wp-power-stats-settings&tab=';
        self::$view_url = 'admin.php?page=wp-power-stats-view-' . self::$current_tab;

        // Load language files
        load_plugin_textdomain('wp-power-stats', POWER_STATS_DIR . '/admin/lang', '/wp-power-stats/admin/lang');

        // WPMU - New blog created
        $active_sitewide_plugins = get_site_option('active_sitewide_plugins');
        if (!empty($active_sitewide_plugins['wp-power-stats/wp-power-stats.php'])) {
            add_action('wpmu_new_blog', array(__CLASS__, 'new_blog'));
        }

        // WPMU - Blog Deleted
        add_filter('wpmu_drop_tables', array(__CLASS__, 'drop_tables'), 10, 2);

        // Display a notice if conditions are met
        if (!empty($_GET['page']) && strpos($_GET['page'], 'wp-power-stats') !== false && !empty(self::$admin_notice) && PowerStats::$options['show_admin_notice'] != PowerStats::$version && get_option('power_stats_internal', 0) > 199) {
            add_action('admin_notices', array(__CLASS__, 'show_admin_notice'));
        }

        // Remove spam hits from the database
        if (PowerStats::$options['ignore_spam_visitors'] == 'yes') {
            add_action('transition_comment_status', array(__CLASS__, 'remove_spam'), 15, 3);
        }

        if (function_exists('is_network_admin') && !is_network_admin()) {

            add_action('admin_menu', array(__CLASS__, 'wp_power_stats_add_main_menu'));
            // Add the appropriate entries to the admin menu, if this user can view/admin WP Power Statss
            add_action('admin_menu', array(__CLASS__, 'wp_power_stats_add_view_menu'));
            add_action('admin_menu', array(__CLASS__, 'wp_power_stats_add_config_menu'));

            // Update the table structure and options, if needed
            if ((!empty(PowerStats::$options['version']) && PowerStats::$options['version'] != PowerStats::$version) || get_option('wp_power_stats_plugin_version')) {
                self::update_tables_and_options();
            }
        }

        if (!isset($_POST['_nonce'])) {
            update_option('power_stats_internal', get_option('power_stats_internal', 0) + 1);
        }

        // AJAX Handlers
        if (defined('DOING_AJAX') && DOING_AJAX){
            add_action('wp_ajax_power_stats_hide_admin_notice', array(__CLASS__, 'hide_admin_notice'));
        }

    }

    private static function recursiveRemoveDirectory($directory)
    {
        foreach (glob("{$directory}/*") as $file) {
            if (is_dir($file)) self::recursiveRemoveDirectory($file);
            else unlink($file);
        }

        rmdir($directory);
    }

    /**
     * Updates the table structure, and make it backward-compatible with all the previous released versions
     */
    public static function update_tables_and_options()
    {
        $wpdb = apply_filters('power_stats_custom_wpdb', $GLOBALS['wpdb']);

        // --- Updates for versions before 2.1.3 ---
        if ((isset(PowerStats::$options['version']) && version_compare(PowerStats::$options['version'], '2.1.3', '<'))) {

            // Add new options
            PowerStats::$options['dashboard_widget'] =  'no';

        }
        // --- END: Updates for versions before 2.1.3 and after 2.0 ---

        // --- Updates for all versions before 2.0 ---
        if ((isset(PowerStats::$options['version']) && version_compare(PowerStats::$options['version'], '2.0', '<')) || get_option('wp_power_stats_plugin_version')) {

            $table_structure = $wpdb->get_results("SHOW COLUMNS FROM {$GLOBALS['wpdb']->base_prefix}power_stats_visits", ARRAY_A);
            $columns_exist = array('user' => false, 'language' => false);

            foreach ($table_structure as $row) {
                if (in_array($row['Field'], array('user', 'language'))) {
                    $columns_exist[$row['Field']] = true;
                }
            }

            // Add new table columns
            if (!$columns_exist['user']) $wpdb->query("ALTER TABLE {$GLOBALS['wpdb']->base_prefix}power_stats_visits ADD COLUMN `user` varchar(64) NOT NULL");
            if (!$columns_exist['language']) $wpdb->query("ALTER TABLE {$GLOBALS['wpdb']->base_prefix}power_stats_visits ADD COLUMN `language` varchar(128) NOT NULL");

            $view_roles = get_option('wp_power_stats_view_roles');
            $configuration_roles = get_option('wp_power_stats_configuration_roles');

            // Remove administrator value from old preferences if found
            if (!empty($view_roles)) {
                $arr_view_roles = explode(",", $view_roles);
                if (is_array($arr_view_roles)) {
                    if (($key = array_search('administrator', $arr_view_roles)) !== false) {
                        unset($arr_view_roles[$key]);
                    }
                }
                $view_roles = implode(",", $arr_view_roles);
            }

            if (!empty($configuration_roles)) {
                $arr_configuration_roles = explode(",", $configuration_roles);
                if (is_array($arr_configuration_roles)) {
                    if (($key = array_search('administrator', $arr_configuration_roles)) !== false) {
                        unset($arr_configuration_roles[$key]);
                    }
                }
                $configuration_roles = implode(",", $arr_configuration_roles);
            }

            // Migrate old style options
            PowerStats::$options['view_roles'] = $view_roles;
            PowerStats::$options['admin_roles'] = $configuration_roles;
            PowerStats::$options['ignore_ip'] =  get_option('wp_power_stats_ip_exclusion');
            PowerStats::$options['ignore_bots'] = (get_option('wp_power_stats_ignore_bots') ? "yes" : "no");
            PowerStats::$options['ignore_admin_pages'] = (get_option('wp_power_stats_ignore_admins') ? "yes" : "no");

            // Remove old style options
            delete_option('wp_power_stats_view_roles');
            delete_option('wp_power_stats_configuration_roles');
            delete_option('wp_power_stats_ip_exclusion');
            delete_option('wp_power_stats_ignore_bots');
            delete_option('wp_power_stats_ignore_admins');
            delete_option('wp_power_stats_plugin_version');

            // Clean up unused files from the previous version
            $clean_up_files = array('admin.php', 'powerStats.class.php', 'settings.php', 'trunk', 'images', 'languages', 'styles', 'vendor', 'views');

            foreach ($clean_up_files as $file) {

                $file_path = POWER_STATS_DIR .'/'. $file;

                if (file_exists($file_path) && defined('POWER_STATS_DIR')) {

                    if (is_file($file_path)) {

                        unlink($file_path);

                    } elseif (is_dir($file_path)) {

                        self::recursiveRemoveDirectory($file_path);

                    }

                }


            }

        }
        // --- END: Updates for all versions before 2.0 ---

        // Now we can update the version stored in the database
        PowerStats::$options['version'] = PowerStats::$version;

        return true;
    }

    /**
     * Creates tables, initializes options and schedules cron
     */
    public static function activate()
    {
        if (function_exists('apply_filters')) {
            $wpdb = apply_filters('power_stats_custom_wpdb', $GLOBALS['wpdb']);
        }

        // Create the tables
        self::init_tables($wpdb);

        // Schedule the auto purge hook
        if (false === wp_next_scheduled('power_stats_purge')) {
            wp_schedule_event('1111111111', 'daily', 'power_stats_purge');
        }

        return true;
    }

    /**
     * Removes a capability from a role
     * @param $role role from which to remove the capability
     * @param $capability capability to remove
     */
    public static function remove_capability($role, $capability) {
        // Add needed capabilities to the administrator
        $role = get_role($role);
        $role->remove_cap($capability);
    }

    /**
     * Adds a capability to a role
     * @param $role role to which add the capability
     * @param $capability capability to add
     */
    public static function add_capability($role, $capability) {
        // Add needed capabilities to the administrator
        $obj_role = get_role($role);
        $obj_role->add_cap($capability);
    }

    /**
     * Create tables for the plugin
     */
    public static function init_tables($wpdb = '')
    {
        // Is InnoDB engine available
        $have_innodb = $wpdb->get_results("SHOW VARIABLES LIKE 'have_innodb'", ARRAY_A);
        $use_innodb = (!empty($have_innodb[0]) && $have_innodb[0]['Value'] == 'YES') ? 'ENGINE=InnoDB' : '';

        $table_prefix = $wpdb->prefix;

        $create_browsers = "CREATE TABLE IF NOT EXISTS `{$table_prefix}power_stats_browsers` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `browser` varchar(255) NOT NULL,
          `count` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) COLLATE utf8_general_ci $use_innodb AUTO_INCREMENT=1;";

        $create_os = "CREATE TABLE IF NOT EXISTS `{$table_prefix}power_stats_os` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `os` varchar(255) NOT NULL,
          `count` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) COLLATE utf8_general_ci $use_innodb AUTO_INCREMENT=1;";

        $create_pageviews = "CREATE TABLE IF NOT EXISTS `{$table_prefix}power_stats_pageviews` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `date` date NOT NULL,
          `hits` int(10) unsigned NOT NULL,
          PRIMARY KEY  (`id`),
          UNIQUE KEY `post_id` (`date`)
        ) COLLATE utf8_general_ci $use_innodb AUTO_INCREMENT=1;";

        $create_posts = "CREATE TABLE IF NOT EXISTS `{$table_prefix}power_stats_posts` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `post_id` int(16) NOT NULL,
          `date` date NOT NULL,
          `hits` int(10) unsigned NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `post_id` (`post_id`,`date`)
        ) COLLATE utf8_general_ci $use_innodb AUTO_INCREMENT=1;";

        $create_searches = "CREATE TABLE IF NOT EXISTS `{$table_prefix}power_stats_searches` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `terms` varchar(255) DEFAULT NULL,
          `count` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) COLLATE utf8_general_ci $use_innodb AUTO_INCREMENT=1;";

        $create_visits = "CREATE TABLE IF NOT EXISTS `{$table_prefix}power_stats_visits` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `date` date NOT NULL,
          `ip` varchar(20),
          `time` time NOT NULL,
          `country` varchar(4),
          `device` varchar(16),
          `referer` text,
          `browser` varchar(255),
          `browser_version` varchar(16),
          `os` varchar(255),
          `language` varchar(128),
          `user` varchar(64),
          `is_search_engine` tinyint(4),
          `is_bot` tinyint(4),
          `user_agent` text,
          PRIMARY KEY (`id`),
          UNIQUE KEY `date` (`date`,`ip`)
        ) COLLATE utf8_general_ci $use_innodb AUTO_INCREMENT=1;";

        $create_referers = "CREATE TABLE IF NOT EXISTS `{$table_prefix}power_stats_referers` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `referer` text NOT NULL,
          `count` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) COLLATE utf8_general_ci $use_innodb AUTO_INCREMENT=1;";

        self::create_table($create_browsers, $table_prefix . 'power_stats_browsers', $wpdb);
        self::create_table($create_os, $table_prefix . 'power_stats_os', $wpdb);
        self::create_table($create_pageviews, $table_prefix . 'power_stats_pageviews', $wpdb);
        self::create_table($create_posts, $table_prefix . 'power_stats_posts', $wpdb);
        self::create_table($create_searches, $table_prefix . 'power_stats_searches', $wpdb);
        self::create_table($create_visits, $table_prefix . 'power_stats_visits', $wpdb);
        self::create_table($create_referers, $table_prefix . 'power_stats_referers', $wpdb);

        // Add capabilities to the administrator role
        $role = get_role("administrator");
        if (is_object($role) && method_exists($role, 'add_cap')) {
            $role->add_cap('wp_power_stats_view');
            $role->add_cap('wp_power_stats_configure');
        }

    }

    /**
     * Creates a table in the database
     */
    protected static function create_table($sql = '', $tablename = '', $wpdb = '')
    {
        $wpdb->query($sql);

        // Let's make sure this table was actually created
        foreach ($wpdb->get_col("SHOW TABLES LIKE '$tablename'", 0) as $table)
            if ($table == $tablename) return true;

        return false;
    }

    /**
     * Clears the purge cron job
     */
    public static function deactivate()
    {
        // Remove the scheduled cron job
        wp_clear_scheduled_hook('power_stats_purge');
    }

    /**
     * Support for WP MU network activations
     */
    public static function new_blog($blog_id)
    {
        switch_to_blog($blog_id);
        self::init_environment();
        restore_current_blog();
        PowerStats::$options = get_option('power_stats_options', array());
    }

    /**
     * Displays a message related to the current version of WP Power Stats
     */
    public static function show_admin_notice()
    {
        echo '<div class="updated power-stats-notice" style="padding:10px"><span>' . self::$admin_notice . '</span> <a id="power-stats-hide-admin-notice" class="power-stats-cancel" title="' . __('Hide this notice', 'wp-power-stats') . '" href="#">Dismiss</a></div>';
    }

    /**
     * Handles the Ajax request to hide the admin notice
     */
    public static function hide_admin_notice()
    {
        PowerStats::$options['show_admin_notice'] = PowerStats::$version;
        exit;
    }

    /**
     * Removes spam hits from the database when the corresponding comments are marked as spam
     */
    public static function remove_spam($new_status = '', $old_status = '', $comment = '')
    {
        if ($new_status == 'spam' && !empty($comment->comment_author) && !empty($comment->comment_author_IP)) {
            PowerStats::$wpdb->query(PowerStats::$wpdb->prepare("DELETE FROM {$GLOBALS['wpdb']->prefix}power_stats_visits WHERE user = %s OR INET_NTOA(ip) = %s", $comment->comment_author, $comment->comment_author_IP));
        }
    }

    /**
     * Returns the capability of the current logged in user
     */
    public static function current_role_capability()
    {
        global $current_user;
        $current_role = reset($current_user->roles);
        $role = get_role($current_role);
        foreach ($role->capabilities as $key => $capability) {
            return $key;
        }
    }

    public static function get_menu_slug() {
        if (current_user_can('wp_power_stats_admin') && !current_user_can('wp_power_stats_view')) return 'wp-power-stats-settings';
        else return 'wp-power-stats-view-1';
    }

    public static function wp_power_stats_add_main_menu($s)
    {
        if (current_user_can('wp_power_stats_view') || current_user_can('wp_power_stats_admin') || current_user_can('manage_options')) {
            $power_stats_icon = version_compare($GLOBALS['wp_version'], '3.8', '<') ? "div" : "dashicons-chart-pie";
            $page_method = (self::get_menu_slug() == 'wp-power-stats-view-1') ? 'wp_power_stats_include_view' : 'wp_power_stats_include_config';
            add_menu_page(__('Statistics', 'wp-power-stats'), __('Statistics', 'wp-power-stats'), PowerStatsAdmin::current_role_capability(), self::get_menu_slug(), array(__CLASS__, $page_method), $power_stats_icon, '3.119');
        }
        return $s;
    }

    /**
     * Adds entries in the menu, to view the statistics
     */
    public static function wp_power_stats_add_view_menu($s)
    {
        if (current_user_can('wp_power_stats_view') || current_user_can('manage_options')) {
            $new_entry = add_submenu_page('wp-power-stats-view-1', __('Overview', 'wp-power-stats'), __('Overview', 'wp-power-stats'), PowerStatsAdmin::current_role_capability(), 'wp-power-stats-view-1', array(__CLASS__, 'wp_power_stats_include_view'));
            // Load styles and Javascript needed to make the reports look nice and interactive
            add_action('load-' . $new_entry, array(__CLASS__, 'wp_power_stats_stylesheet'));
            add_action('load-' . $new_entry, array(__CLASS__, 'wp_power_stats_enqueue_scripts'));
            add_action('load-' . $new_entry, array(__CLASS__, 'contextual_help'));

            if (version_compare($GLOBALS['wp_version'], '3.8', '<'))  wp_enqueue_style('wp-power-stats-layout-fix', plugins_url('/admin/css/styles-before-3.8.css', dirname(__FILE__)));
        }
        return $s;
    }

    /**
     * Adds a new entry in the menu, to manage WP Power Stats options
     */
    public static function wp_power_stats_add_config_menu($s)
    {
        if (current_user_can('wp_power_stats_admin') || current_user_can('manage_options')) {
            $new_entry = add_submenu_page('wp-power-stats-view-1', __('Settings', 'wp-power-stats'), __('Settings', 'wp-power-stats'), PowerStatsAdmin::current_role_capability(), 'wp-power-stats-settings', array(__CLASS__, 'wp_power_stats_include_config'));
            // Load styles and Javascript needed to make the reports look nice and interactive
            add_action('load-' . $new_entry, array(__CLASS__, 'wp_power_stats_stylesheet'));

            if (version_compare($GLOBALS['wp_version'], '3.8', '<'))  wp_enqueue_style('wp-power-stats-layout-fix', plugins_url('/admin/css/styles-before-3.8.css', dirname(__FILE__)));
        }
        return $s;
    }

    /**
     * Includes the appropriate panel to view the stats
     */
    public static function wp_power_stats_include_view()
    {
        include(dirname(__FILE__) . '/view/index.php');
    }

    /**
     * Includes the appropriate panel to configure WP Power Stats
     */
    public static function wp_power_stats_include_config()
    {
        global $wp_roles;
        include(dirname(__FILE__) . '/config/index.php');
    }

    /**
     * Loads a custom stylesheet file for the administration panels
     */
    public static function wp_power_stats_stylesheet($hook = '')
    {
        if (!empty($_GET['page']) && strpos($_GET['page'], 'wp-power-stats') === false && $hook != 'edit.php') {
            return;
        }

        wp_register_style('wp-power-stats-report-grid', plugins_url('/admin/css/grid.css', dirname(__FILE__)));
        wp_register_style('wp-power-stats-report-styles', plugins_url('/admin/css/styles.css', dirname(__FILE__)));

        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
        wp_enqueue_script('google-charts', 'https://www.google.com/jsapi');
        wp_enqueue_script('helpers', plugins_url('/admin/js/scripts.js', dirname(__FILE__)));

        wp_enqueue_style('wp-power-stats-report-styles');
        wp_enqueue_style('wp-power-stats-report-grid');
    }

    /**
     *
     */
    public static function wp_power_stats_enqueue_scripts()
    {

    }

    /**
     * Replace WordPress default dashboard with WP Power Stats
     */
    public static function replace_dashboard() {

        if (PowerStats::$options['replace_dashboard'] == "yes") {

            $screen = get_current_screen();

            if ($screen->base == 'dashboard') {
                wp_redirect(admin_url('index.php?page=wp-power-stats-view-1'));
            }
        }
    }

    public static function update_options($options = array())
    {
        if (!isset($_POST['options']) || empty($options)) return true;

        foreach ($options as $option_name => $option_details) {
            // Some options require a special treatment and are updated somewhere else
            if (isset($option_details['skip_update']))
                continue;

            if (isset($_POST['options'][$option_name]))
                PowerStats::$options[$option_name] = $_POST['options'][$option_name];
        }

        if (!empty(self::$faulty_fields)) {
            self::show_message(__('There was an error saving the settings:', 'wp-power-stats') . ' ' . implode(', ', self::$faulty_fields), 'error below-h2');
        } else {
            self::show_message(__('Your changes have been saved.', 'wp-power-stats'), 'updated below-h2');
        }
    }

    /*
	 * Updates the options
	 */

    /**
     * Displays an info message
     */
    public static function show_message($message = '', $type = 'update')
    {
        echo '<div id="message" class="'. $type .'"><p>'. $message .'</p></div>';
    }

    /*
	 * Displays the options
	 */
    public static function display_options($options = array(), $current_tab = 1)
    {

        echo "<div id=\"post-body\"><br><form action=\"" . self::$config_url . $current_tab . "\" method=\"post\" id=\"form-power-stats-options-tab-{$current_tab}\"><table class=\"form-table widefat {$GLOBALS['wp_locale']->text_direction}\"><tbody>";
        $i = 0;
        foreach ($options as $option_name => $option_details) {
            $i++;
            if ($option_details['type'] != 'textarea') {
                self::settings_table_row($option_name, $option_details);
            } else {
                self::settings_textarea($option_name, $option_details);
            }
        }

        echo "</tbody></table>";
        if ($current_tab != 7) {
            echo '<p class="submit"><input type="submit" value="' . __('Save Changes', 'wp-power-stats') . '" class="button-primary" name="Submit"></p>';
        }
        echo "</form></div>";

    }

    protected static function settings_table_row($_option_name = '', $_option_details = array())
    {
        $_option_details = array_merge(array('description' => '', 'type' => '', 'long_description' => '', 'before_input_field' => '', 'after_input_field' => '', 'custom_label_yes' => '', 'custom_label_no' => ''), $_option_details);

        if (!isset(PowerStats::$options[$_option_name])) {
            PowerStats::$options[$_option_name] = '';
        }

        $is_disabled = (!empty($_option_details['disabled']) && $_option_details['disabled'] === true) ? ' disabled' : '';

        echo '<tr>';
        switch ($_option_details['type']) {
            case 'section_header':
                echo '<td colspan="2" class="power-stats-options-section-header"><h3>' . $_option_details['description'] . '</h3></td>';
                break;
            case 'yesno':
                echo '<th scope="row">'. $_option_details['description'] .'</th>';
                echo '<td><span class="block-element"><input type="radio"'. $is_disabled .' name="options['. $_option_name .']" id="'. $_option_name .'_yes" value="yes"'. ((PowerStats::$options[$_option_name] == 'yes') ? ' checked="checked"' : '') .'> <label for="'. $_option_name .'_yes">'. (!empty($_option_details['custom_label_yes']) ? $_option_details['custom_label_yes'] : __('Yes', 'wp-power-stats')) .'</label>&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                echo '<span class="block-element"><input type="radio"'. $is_disabled .'name="options['. $_option_name .']" id="'. $_option_name .'_no" value="no" '. ((PowerStats::$options[$_option_name] == 'no') ? ' checked="checked"' : '') .'> <label for="'. $_option_name .'_no">'. (!empty($_option_details['custom_label_no']) ? $_option_details['custom_label_no'] : __('No', 'wp-power-stats')) .'</label></span>';
                echo '<p class="description">'. $_option_details['long_description'] .'</p></td>';
                break;
            case 'text':
            case 'integer':
                echo '<th scope="row"><label for="'. $_option_name .'">'. $_option_details['description'] .'</label></th><td>';
                echo '<span class="block-element">'. $_option_details['before_input_field'] .' <input'. $is_disabled .' type="'. (($_option_details['type'] == 'integer') ? 'number' : 'text') .'" class="'. (($_option_details['type'] == 'integer') ? 'small-text' : 'regular-text') .'" name="options['. $_option_name .']" id="'. $_option_name .'" value="'. PowerStats::$options[$_option_name] .'"> '. $_option_details['after_input_field'] .'</span>';
                echo '<p class="description">'. $_option_details['long_description'] .'</p></td>';
                break;
            default:
        }

        echo '</tr>';
    }

    protected static function settings_textarea($option_name = '', $option_details = array('description' => '', 'type' => '', 'long_description' => ''))
    {
        if (!isset(PowerStats::$options[$option_name])) {
            PowerStats::$options[$option_name] = '';
        } ?>

    <tr>
        <th><label for="<?php echo $option_name ?>"><?php echo $option_details['description'] ?></label></th>
        <td>
            <p><textarea class="large-text code" cols="50" rows="3" name="options[<?php echo $option_name ?>]" id="<?php echo $option_name ?>"><?php echo !empty(PowerStats::$options[$option_name]) ? stripslashes(PowerStats::$options[$option_name]) : '' ?></textarea>
            </p>
            <p class="description"><?php echo $option_details['long_description'] ?></p>
        </td>
        </tr><?php
    }

    /**
     * Contextual help
     */
    public static function contextual_help()
    {
        // This contextual help is only available to those using WP 3.3 or newer
        if (empty($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '3.3', '<')) return true;

    }
}