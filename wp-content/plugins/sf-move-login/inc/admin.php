<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

/*------------------------------------------------------------------------------------------------*/
/* !UTILITIES =================================================================================== */
/*------------------------------------------------------------------------------------------------*/

if ( ! function_exists( 'get_main_blog_id' ) ) :
function get_main_blog_id() {
	static $blog_id;

	if ( ! $blog_id ) {
		$blog_id = 1;

		if ( ! is_multisite() ) {
			// 1
		}
		elseif ( ! empty( $GLOBALS['current_site']->blog_id ) ) {
			$blog_id = absint( $GLOBALS['current_site']->blog_id );
		}
		elseif ( defined( 'BLOG_ID_CURRENT_SITE' ) ) {
			$blog_id = absint( BLOG_ID_CURRENT_SITE );
		}
		elseif ( defined( 'BLOGID_CURRENT_SITE' ) ) { // deprecated.
			$blog_id = absint( BLOGID_CURRENT_SITE );
		}
		$blog_id = $blog_id ? $blog_id : 1;
	}

	return $blog_id;
}
endif;


if ( ! function_exists( 'sf_maybe_flush_rewrite_rules' ) ) :
function sf_maybe_flush_rewrite_rules() {
	static $done = false;

	if ( did_action( 'init' ) ) {
		flush_rewrite_rules();
	}
	elseif ( ! $done ) {
		$done = true;
		add_action( 'init', 'flush_rewrite_rules', PHP_INT_MAX );
	}
}
endif;


// Back-compat WP < 3.6

if ( ! function_exists( 'wp_is_writable' ) ) :
function wp_is_writable( $path ) {
	if ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) ) {
		return win_is_writable( $path );
	}
	else {
		return @is_writable( $path );
	}
}
endif;


/*------------------------------------------------------------------------------------------------*/
/* !LINKS IN THE PLUGIN ROW ===================================================================== */
/*------------------------------------------------------------------------------------------------*/

add_filter( 'plugin_action_links_' . SFML_PLUGIN_BASENAME, 'sfml_settings_action_links', 10, 2 );
add_filter( 'network_admin_plugin_action_links_' . SFML_PLUGIN_BASENAME, 'sfml_settings_action_links', 10, 2 );

function sfml_settings_action_links( $links, $file ) {
	$links['settings'] = '<a href="' . ( is_multisite() ? network_admin_url( 'settings.php?page=move-login' ) : admin_url( 'options-general.php?page=move-login' ) ) . '">' . __( 'Settings' ) . '</a>';
	return $links;
}


// !List everybody, so no one will be jalous. :)

add_filter( 'plugin_row_meta', 'sfml_plugin_row_meta', 10, 2 );

function sfml_plugin_row_meta( $plugin_meta, $plugin_file ) {

	if ( SFML_PLUGIN_BASENAME === $plugin_file ) {
		$pos     = false;
		$links   = array();
		$authors = array(
			array( 'name' => 'Grégory Viguier', 'url' => 'http://www.screenfeed.fr/' ),
			array( 'name' => 'Julio Potier',    'url' => 'http://www.boiteaweb.fr' ),
			array( 'name' => 'SecuPress',       'url' => 'http://blog.secupress.fr' ),
		);

		if ( ! empty( $plugin_meta ) ) {
			$search = '"http://www.screenfeed.fr/"';
			foreach ( $plugin_meta as $i => $meta ) {
				if ( false !== strpos( $meta, $search ) ) {
					$pos = $i;
					break;
				}
			}
		}

		foreach ( $authors as $author ) {
			$links[] = sprintf( '<a href="%s">%s</a>', $author['url'], $author['name'] );
		}

		$links = sprintf( __( 'By %s' ), wp_sprintf( '%l', $links ) );

		if ( false !== $pos ) {
			$plugin_meta[ $pos ] = $links;
		}
		else {
			$plugin_meta[] = $links;
		}
	}

	return $plugin_meta;
}


/*------------------------------------------------------------------------------------------------*/
/* !MENU ITEM =================================================================================== */
/*------------------------------------------------------------------------------------------------*/

add_action( ( is_multisite() ? 'network_' : '' ) . 'admin_menu', 'sfml_admin_menu' );

function sfml_admin_menu() {
	$page = is_multisite() ? 'settings.php' : 'options-general.php';
	$cap  = is_multisite() ? 'manage_network_options' : 'manage_options';
	add_submenu_page( $page, 'Move Login', 'Move Login', $cap, SFML_Options::OPTION_PAGE, 'sfml_settings_page' );
}


/*------------------------------------------------------------------------------------------------*/
/* !SETTINGS PAGE =============================================================================== */
/*------------------------------------------------------------------------------------------------*/

// !Include the settings page file on... the settings page.

add_action( 'load-settings_page_' . SFML_Options::OPTION_PAGE, 'sfml_include_settings_page' );

function sfml_include_settings_page() {
	include( SFML_PLUGIN_DIR . 'inc/settings-page.php' );
}


/*------------------------------------------------------------------------------------------------*/
/* !SAVE SETTINGS ON FORM SUBMIT ================================================================ */
/*------------------------------------------------------------------------------------------------*/

// !options.php do not handle site options. Let's use admin-post.php for multisite installations.

if ( is_multisite() ) :

add_action( 'admin_post_update', 'sfml_update_site_option_on_submit' );

function sfml_update_site_option_on_submit() {
	$option_group = SFML_Options::OPTION_GROUP;

	if ( ! isset( $_POST['option_page'] ) || $_POST['option_page'] !== $option_group ) {
		return;
	}

	$capability = apply_filters( "option_page_capability_{$option_group}", 'manage_network_options' );

	if ( ! current_user_can( $capability ) ) {
		wp_die( __( 'Cheatin&#8217; uh?' ), 403 );
	}

	check_admin_referer( $option_group . '-options' );

	$whitelist_options = apply_filters( 'whitelist_options', array() );

	if ( ! isset( $whitelist_options[ $option_group ] ) ) {
		wp_die( __( '<strong>ERROR</strong>: options page not found.' ) );
	}

	$options = $whitelist_options[ $option_group ];

	if ( $options ) {

		foreach ( $options as $option ) {
			$option = trim( $option );
			$value  = null;

			if ( isset( $_POST[ $option ] ) ) {
				$value = $_POST[ $option ];
				if ( ! is_array( $value ) ) {
					$value = trim( $value );
				}
				$value = wp_unslash( $value );
			}

			update_site_option( $option, $value );
		}

	}

	/**
	 * Handle settings errors and return to options page
	 */
	// If no settings errors were registered add a general 'updated' message.
	if ( ! count( get_settings_errors() ) ) {
		add_settings_error( 'general', 'settings_updated', __( 'Settings saved.' ), 'updated' );
	}
	set_transient( 'settings_errors', get_settings_errors(), 30 );

	/**
	 * Redirect back to the settings page that was submitted
	 */
	$goback = add_query_arg( 'settings-updated', 'true',  wp_get_referer() );
	wp_redirect( $goback );
	exit;
}

endif;


/*------------------------------------------------------------------------------------------------*/
/* !UPGRADE ===================================================================================== */
/*------------------------------------------------------------------------------------------------*/

// !Delete previous options.

function sfml_delete_noop_options() {
	$option_name      = 'sfml';
	$page_name        = 'move-login';
	$page_parent_name = 'settings';

	// Remove the main option and the history option
	delete_option( $option_name );
	delete_option( $option_name . '_history' );

	// Remove the users metadatas, reguarding the metaboxes placement
	delete_metadata( 'user', 0, 'screen_layout_' . $page_parent_name . '_page_' . $page_name, null, true );
	delete_metadata( 'user', 0, 'metaboxhidden_' . $page_parent_name . '_page_' . $page_name, null, true );
	delete_metadata( 'user', 0, 'meta-box-order_' . $page_parent_name . '_page_' . $page_name, null, true );
	delete_metadata( 'user', 0, 'closedpostboxes_' . $page_parent_name . '_page_' . $page_name, null, true );

	if ( is_multisite() ) {
		delete_metadata( 'user', 0, 'screen_layout_' . $page_parent_name . '_page_' . $page_name . '-network', null, true );
		delete_metadata( 'user', 0, 'metaboxhidden_' . $page_parent_name . '_page_' . $page_name . '-network', null, true );
		delete_metadata( 'user', 0, 'meta-box-order_' . $page_parent_name . '_page_' . $page_name . '-network', null, true );
		delete_metadata( 'user', 0, 'closedpostboxes_' . $page_parent_name . '_page_' . $page_name . '-network', null, true );
	}
}


// !Upgrade

function sfml_upgrade() {
	$proceed            = false;
	$mono_or_multi      = 0;						// Used to tell if the site changed from monosite to multisite.
	$current_mono_multi = is_multisite() ? 2 : 1;	// 1: monosite, 2: multisite.
	$db_version         = get_site_option( 'sfml_version' );
	$db_version         = is_string( $db_version ) ? explode( '|', $db_version ) : false;
	// `$update_file`:
	// "1" means we need to update the `.htaccess`/`web.config` file.
	// "2" means "No need to update the `.htaccess`/`web.config` file": update_site_option() already did.
	$update_file        = false;

	if ( $db_version ) {
		$mono_or_multi  = isset( $db_version[1] ) ? (int) $db_version[1] : 0;
		$db_version     = $db_version[0];
	}

	// We're right on track.
	if ( $db_version && 0 === version_compare( $db_version, SFML_VERSION ) && $mono_or_multi === $current_mono_multi ) {
		return;
	}

	// Try to get a version from an older version, but only for multisite because on non-multisites get_site_option() and get_option() are the same.
	if ( ! $db_version && is_multisite() ) {
		$db_version = get_option( 'sfml_version' );
		delete_option( 'sfml_version' );
	}

	// < 2.0 (old version with Noop)
	if ( ! $mono_or_multi || version_compare( $db_version, '2.0' ) < 0 ) {

		// < 1.1
		if ( ! $db_version ) {
			sf_maybe_flush_rewrite_rules();
		}

		if ( is_multisite() && get_main_blog_id() !== get_current_blog_id() ) {
			switch_to_blog( get_main_blog_id() );
			$old_options = get_option( 'sfml' );
			sfml_delete_noop_options();
			restore_current_blog();
		}
		else {
			$old_options = get_option( 'sfml' );
			sfml_delete_noop_options();
		}

		// Noop stores the options separately, by language, whether you use a multilingual site or not.
		$old_options = is_array( $old_options ) && ! isset( $old_options['slugs.login'] ) ? reset( $old_options ) : $old_options;
		$old_options = is_array( $old_options ) ? $old_options : array();

		update_site_option( SFML_Options::OPTION_NAME, $old_options );
		$update_file = '2';

	}
	// Switched monosite ==> multisite since the last check.
	elseif ( $mono_or_multi !== $current_mono_multi && 2 === $current_mono_multi ) {

		$old_options = get_option( 'sfml' );
		$old_options = is_array( $old_options ) ? $old_options : array();
		delete_option( 'sfml' );

		update_site_option( SFML_Options::OPTION_NAME, $old_options );
		$update_file = '2';

	}
	// There are some changes in the stored options.
	elseif ( version_compare( $db_version, '2.2.1' ) < 0 ) {
		$old_options = sfml_get_options();
		update_site_option( SFML_Options::OPTION_NAME, $old_options );
		$update_file = '2';
	}

	// Maybe display a notice, but only if the `.htaccess`/`web.config` file needs to be updated.
	if ( $update_file ) {
		set_transient( 'sfml_notices-' . get_current_user_id(), $update_file );
	}

	update_site_option( 'sfml_version', SFML_VERSION . '|' . $current_mono_multi );
}

sfml_upgrade();


/*------------------------------------------------------------------------------------------------*/
/* !ADMIN NOTICES + UPDATE REWRITE RULES ======================================================== */
/*------------------------------------------------------------------------------------------------*/

// !Admin notices

add_action( 'all_admin_notices', 'sfml_notices' );

function sfml_notices() {
	global $pagenow, $is_apache, $is_iis7;

	// Get previous notices
	$user_id = get_current_user_id();
	$proceed = get_transient( 'sfml_notices-' . $user_id );	// 1 means "Update the file". 2 means "No need to update the file".

	// If the transient exists, it means it's the plugin activation or upgrade.
	if ( ! $proceed ) {
		return;
	}

	if ( ! function_exists( 'sfml_write_rules' ) ) {
		include( SFML_PLUGIN_DIR . 'inc/rewrite.php' );
	}

	delete_transient( 'sfml_notices-' . $user_id );

	$notices   = array();
	$is_nginx  = sfml_is_nginx();
	$home_path = sfml_get_home_path();

	// IIS7
	if ( $is_iis7 && iis7_supports_permalinks() && ! ( wp_is_writable( $home_path . 'web.config' ) || ( ! file_exists( $home_path . 'web.config' ) && wp_is_writable( $home_path ) ) ) ) {
		$notices[] = 'error_file_not_writable';
	}
	// Apache
	elseif ( $is_apache && got_mod_rewrite() && ! ( wp_is_writable( $home_path . '.htaccess' ) || ( ! file_exists( $home_path . '.htaccess' ) && wp_is_writable( $home_path ) ) ) ) {
		$notices[] = 'error_file_not_writable';
	}
	// Nginx
	elseif ( $is_nginx ) {
		$notices[] = 'updated_is_nginx';
	}

	// Display notices
	if ( ! empty( $notices ) ) {

		$messages = array();

		foreach ( $notices as $notice ) {
			$index = substr( $notice, 0, strpos( $notice, '_' ) );
			$messages[ $index ][] = sfml_notice_message( $notice );
		}

		$messages = array_filter( array_map( 'array_filter', $messages ) );

		foreach ( $messages as $class => $message ) {
			echo '<div class="' . $class . '"><p>' . implode( '<br/>', $message ) . '</p></div>';
		}

	}
	elseif ( '1' === $proceed ) {
		// Add the rewrite rules to the .htaccess/web.config file.
		sfml_write_rules();
	}
}


/**/