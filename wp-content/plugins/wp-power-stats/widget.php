<?php

/**
 * Adds PowerStatsWidget widget.
 */
class PowerStatsWidget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'foo_widget', // Base ID
            __('WP Power Stats', 'wp-power-stats'), // Name
            array('description' => __('WP Power Stats Widget', 'wp-power-stats')) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {

        global $wpdb;

        $title = apply_filters('widget_title', $instance['title']);
        $show_images = isset($instance['images']) ? $instance['images'] : true;

        echo $args['before_widget'];

        if (!empty($title)) echo $args['before_title'] . $title . $args['after_title'];

        $week_start = get_option('start_of_week'); // Get Wordpress option
        $now = current_time('mysql');
        $week_mode = ($week_start == 1) ? 1 : 0;

        $total_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits`", ARRAY_N);
        $today_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE DATE(`date`) = DATE('". $now ."')", ARRAY_N);
        $this_week_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE WEEK(`date`, $week_mode) = WEEK('". $now ."', $week_mode)", ARRAY_N);
        $this_month_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE MONTH(`date`) = MONTH('". $now ."')", ARRAY_N);

        $total_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews`", ARRAY_N);
        $today_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE DATE(`date`) = DATE('". $now ."')", ARRAY_N);
        $this_week_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE WEEK(`date`, $week_mode) = WEEK('". $now ."', $week_mode)", ARRAY_N);
        $this_month_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE MONTH(`date`) = MONTH('". $now ."')", ARRAY_N);

        $table = '<style>table.wp-power-stats-widget {border: 0;} table.wp-power-stats-widget td {border: 0;padding-bottom: 4px;} table.wp-power-stats-widget td.visits div {margin: auto; width: 16px; height: 16px; background: url('.plugin_dir_url(__FILE__).'/admin/images/widget-icons.png) 16px 0;} table.wp-power-stats-widget td.pageviews div {margin: auto; width: 16px; height: 16px; background: url('.plugin_dir_url(__FILE__).'/admin/images/widget-icons.png);} table.wp-power-stats-widget td.value {text-align: center;}</style>
        <table class="wp-power-stats-widget">';

        if ($show_images) $table .='
        <thead>
            <tr>
                <td></td>
                <td class="visits"><div></div></td>
                <td class="pageviews"><div></div></td>
            </tr>
        </thead>';

        $table .='
        <tbody>
            <tr>
                <td>'. __('Total','wp-power-stats') .'</td>
                <td class="value">'. $total_visits[0] .'</td>
                <td class="value">'. $total_pageviews[0] .'</td>
            </tr>
            <tr>
                <td>'. __('Today','wp-power-stats') .'</td>
                <td class="value">'. $today_visits[0] .'</td>
                <td class="value">'. $today_pageviews[0] .'</td>
            </tr>
        
            <tr>
                <td>'. __('This Week','wp-power-stats') .'</td>
                <td class="value">'. $this_week_visits[0] .'</td>
                <td class="value">'. $this_week_pageviews[0] .'</td>
            </tr>
        
            <tr>
                <td>'. __('This Month','wp-power-stats') .'</td>
                <td class="value">'. $this_month_visits[0] .'</td>
                <td class="value">'. $this_month_pageviews[0] .'</td>
            </tr>
        </tbody>
        </table>';

        echo $table;
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     * @param array $instance Previously saved values from database.
     * @return echoes the form
     */
    public function form($instance) {

        //Defaults
        $instance = wp_parse_args((array) $instance, array('images' => true));

        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Statistics', 'wp-power-stats');
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp-power-stats'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['images'], true) ?> id="<?php echo $this->get_field_id('images'); ?>" name="<?php echo $this->get_field_name('images'); ?>" />
            <label for="<?php echo $this->get_field_id('images'); ?>"><?php _e('Show Icons', 'wp-power-stats'); ?></label>
        </p>
    <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $new_instance = (array) $new_instance;
        $instance = array('images' => 0);
        foreach ( $instance as $field => $val ) {
            if ( isset($new_instance[$field]) )
                $instance[$field] = 1;
        }
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';


        return $instance;
    }

} // class PowerStatsWidget