<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die();
}


include( plugin_dir_path( __FILE__ ) . 'inc/class-sfml-options.php' );

delete_site_option( 'sfml_version' );
delete_site_option( SFML_Options::OPTION_NAME );

/**/