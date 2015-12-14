<?php

class PowerStatsDashboardWidget {

    /**
     * The id of this widget.
     */
    const wid = 'wp_power_stats_widget';

    /**
     * Hook to wp_dashboard_setup to add the widget.
     */
    public static function init() {

        if (isset(PowerStats::$options['dashboard_widget']) && PowerStats::$options['dashboard_widget'] == "yes")

        // Register widget settings
        self::update_dashboard_widget_options(
            self::wid,                                     // The  widget id
            array(                                         // Associative array of options & default values
                'example_number' => 42,
            ),
            true                                           // Add only (will not update existing options)
        );

        // Register the widget
        wp_add_dashboard_widget(
            self::wid,                                     // A unique slug/ID
            __( 'WP Power Stats', 'nouveau' ),   // Visible name for the widget
            array( 'PowerStatsDashboardWidget', 'widget' )      // Callback for the main widget content
        );
    }

    /**
     * Saves an array of options for a single dashboard widget to the database.
     * Can also be used to define default values for a widget.
     *
     * @param string $widget_id The name of the widget being updated
     * @param array $args An associative array of options being saved.
     * @param bool $add_only If true, options will not be added if widget options already exist
     */
    public static function update_dashboard_widget_options( $widget_id, $args = array(), $add_only = false ) {

        // Fetch ALL dashboard widget options from the db...
        $opts = get_option( 'dashboard_widget_options' );

        // Get just our widget's options, or set empty array
        $w_opts = ( isset( $opts[ $widget_id ] ) ) ? $opts[ $widget_id ] : array();

        if ( $add_only ) {
        // Flesh out any missing options (existing ones overwrite new ones)
            $opts[ $widget_id ] = array_merge( $args, $w_opts );
        } else {
        // Merge new options with existing ones, and add it back to the widgets array
            $opts[ $widget_id ] = array_merge( $w_opts, $args );
        }

        // Save the entire widgets array back to the db
        return update_option( 'dashboard_widget_options', $opts );
    }

    /**
     * Load the widget code
     */
    public static function widget() {

        $week_start = get_option('start_of_week'); // Get Wordpress option
        $now = current_time('mysql');
        $week_mode = ($week_start == 1) ? 1 : 0;

        $wpdb = $GLOBALS['wpdb'];

        $today_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE DATE(`date`) = DATE('". $now ."')", ARRAY_N);
        $this_week_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE WEEK(`date`, $week_mode) = WEEK('". $now ."', $week_mode)", ARRAY_N);
        $this_month_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE MONTH(`date`) = MONTH('". $now ."')", ARRAY_N);

        $today_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE DATE(`date`) = DATE('". $now ."')", ARRAY_N);
        $this_week_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE WEEK(`date`, $week_mode) = WEEK('". $now ."', $week_mode)", ARRAY_N);
        $this_month_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE MONTH(`date`) = MONTH('". $now ."')", ARRAY_N);

        ?>

        <div class="inside">

            <table style="margin: auto;">
                <thead>
                <tr>
                    <td style="width: 80px;"></td>
                    <td style="width: 60px; text-align: right; padding-right: 20px;"><?php _e('Visitors','wp-power-stats') ?></td>
                    <td style="width: 60px; text-align: right;"><?php _e('Pageviews','wp-power-stats') ?> </td>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td><?php _e('Today','wp-power-stats') ?></td>
                    <td style="text-align: right; padding-right: 20px;"><?php echo $today_visits[0] ?></td>
                    <td style="text-align: right;"><?php echo empty($today_pageviews[0]) ? 0 : $today_pageviews[0] ?></td>
                </tr>

                <tr>
                    <td><?php _e('This Week','wp-power-stats') ?></td>
                    <td style="text-align: right; padding-right: 20px;"><?php echo $this_week_visits[0] ?></td>
                    <td style="text-align: right;"><?php echo empty($this_week_pageviews[0]) ? 0 : $this_week_pageviews[0] ?></td>
                </tr>

                <tr>
                    <td><?php _e('This Month','wp-power-stats') ?></td>
                    <td style="text-align: right; padding-right: 20px;"><?php echo $this_month_visits[0] ?></td>
                    <td style="text-align: right;"><?php echo empty($this_month_pageviews[0]) ? 0 : $this_month_pageviews[0] ?></td>
                </tr>
                </tbody>
            </table>

        </div><!-- inside -->

        <?php

    }

    /**
     * Load widget config code.
     *
     * This is what will display when an admin clicks
     */
    public static function config() {
        //require_once( 'widget-config.php' );
    }

    /**
     * Gets one specific option for the specified widget.
     *
     * @param $widget_id
     * @param $option
     * @param null $default
     *
     * @return string
     */
    public static function get_dashboard_widget_option( $widget_id, $option, $default = null ) {

        $opts = self::get_dashboard_widget_options( $widget_id );

        // If widget opts dont exist, return false
        if ( ! $opts ) {
            return false;
        }

        // Otherwise fetch the option or use default
        if ( isset( $opts[ $option ] ) && ! empty( $opts[ $option ] ) ) {
            return $opts[ $option ];
        } else {
            return ( isset( $default ) ) ? $default : false;
        }

    }

    /**
     * Gets the options for a widget of the specified name.
     *
     * @param string $widget_id Optional. If provided, will only get options for the specified widget.
     *
     * @return array An associative array containing the widget's options and values. False if no opts.
     */
    public static function get_dashboard_widget_options( $widget_id = '' ) {

        // Fetch ALL dashboard widget options from the db...
        $opts = get_option( 'dashboard_widget_options' );

        // If no widget is specified, return everything
        if ( empty( $widget_id ) ) {
            return $opts;
        }

        // If we request a widget and it exists, return it
        if ( isset( $opts[ $widget_id ] ) ) {
            return $opts[ $widget_id ];
        }

        // Something went wrong...
        return false;
    }

}