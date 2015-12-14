<?php

// Avoid accidental invocation of this script
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

$wpdb = $GLOBALS['wpdb'];

if (function_exists('is_multisite') && is_multisite()) {

    $blog = $GLOBALS['wpdb']->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs WHERE site_id = %d AND deleted = 0 AND spam = 0", $wpdb->siteid));

    foreach ($blog as $blog_id) {

        switch_to_blog($blog_id);
        power_stats_uninstall($wpdb);

    }

    restore_current_blog();

} else {

    power_stats_uninstall($wpdb);

}

function power_stats_uninstall($wpdb = '')
{
    // Delete tables
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}power_stats_browsers");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}power_stats_os");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}power_stats_pageviews");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}power_stats_posts");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}power_stats_referers");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}power_stats_searches");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}power_stats_visits");

    // Delete options
    delete_option('power_stats_options');
    delete_option('power_stats_internal');

    // Remove scheduled auto purge event
    wp_clear_scheduled_hook('power_stats_purge');
}