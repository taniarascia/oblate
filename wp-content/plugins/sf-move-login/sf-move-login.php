<?php
/*
 * Plugin Name: SF Move Login
 * Plugin URI: http://www.screenfeed.fr/plugin-wp/move-login/
 * Description: Change your login url
 * Version: 2.2
 * Author: Grégory Viguier
 * Author URI: http://www.screenfeed.fr/
 * License: GPLv3
 * License URI: http://www.screenfeed.fr/gpl-v3.txt
 * Network: true
 * Text Domain: sf-move-login
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

if ( version_compare( $GLOBALS['wp_version'], '3.1', '<' ) ) {
	return;
}

/*------------------------------------------------------------------------------------------------*/
/* !CONSTANTS =================================================================================== */
/*------------------------------------------------------------------------------------------------*/

define( 'SFML_VERSION',         '2.2' );
define( 'SFML_FILE',            __FILE__ );
define( 'SFML_PLUGIN_BASENAME', plugin_basename( SFML_FILE ) );
define( 'SFML_PLUGIN_DIR',      plugin_dir_path( SFML_FILE ) );


/*------------------------------------------------------------------------------------------------*/
/* !INCLUDES ==================================================================================== */
/*------------------------------------------------------------------------------------------------*/

add_action( 'after_setup_theme', 'sfml_init', 11 );

function sfml_init() {
	global $pagenow;

	include( SFML_PLUGIN_DIR . 'inc/utilities.php' );

	if ( ! class_exists( 'SFML_Options' ) ) {
		include( SFML_PLUGIN_DIR . 'inc/class-sfml-options.php' );
	}

	// Administration
	if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		include( SFML_PLUGIN_DIR . 'inc/admin.php' );
	}

	// !EMERGENCY BYPASS
	if ( ! defined( 'SFML_ALLOW_LOGIN_ACCESS' ) || ! SFML_ALLOW_LOGIN_ACCESS ) {
		include( SFML_PLUGIN_DIR . 'inc/url-filters.php' );
		include( SFML_PLUGIN_DIR . 'inc/redirections-and-dies.php' );
	}

}


/*------------------------------------------------------------------------------------------------*/
/* !I18N SUPPORT ================================================================================ */
/*------------------------------------------------------------------------------------------------*/

add_action( 'init', 'sfml_lang_init' );

function sfml_lang_init() {
	load_plugin_textdomain( 'sf-move-login', false, basename( dirname( SFML_FILE ) ) . '/languages/' );
}


/*------------------------------------------------------------------------------------------------*/
/* !PLUGIN ACTIVATION AND DEACTIVATION ========================================================== */
/*------------------------------------------------------------------------------------------------*/

if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
	include( SFML_PLUGIN_DIR . 'inc/activate.php' );
}

/**/