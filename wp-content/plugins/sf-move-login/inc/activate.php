<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

/*------------------------------------------------------------------------------------------------*/
/* !ACTIVATION ================================================================================== */
/*------------------------------------------------------------------------------------------------*/

// Trigger wp_die() on plugin activation if the server configuration does not fit. Or, set a transient for admin notices.

function sfml_activate() {
	global $is_apache, $is_iis7;

	if ( ! function_exists( 'sfml_write_rules' ) ) {
		include( SFML_PLUGIN_DIR . 'inc/rewrite.php' );
	}

	$is_nginx = sfml_is_nginx();
	$dies     = array();

	// The plugin needs the request uri
	if ( empty( $GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI'] ) && empty( $_SERVER['REQUEST_URI'] ) ) {
		$dies[] = 'error_no_request_uri';
	}
	// IIS7
	if ( $is_iis7 && ! iis7_supports_permalinks() ) {
		$dies[] = 'error_no_mod_rewrite';
	}
	// Apache
	elseif ( $is_apache && ! got_mod_rewrite() ) {
		$dies[] = 'error_no_mod_rewrite';
	}
	// None
	elseif ( ! $is_iis7 && ! $is_apache && ! $is_nginx ) {
		$dies[] = 'error_unknown_server_conf';
	}

	// die()s: don't activate the plugin.
	if ( ! empty( $dies ) ) {

		// i18n early loading.
		sfml_lang_init();

		$dies = array_filter( array_map( 'sfml_notice_message', $dies ) );
		$dies = __( '<strong>Move Login</strong> has not been activated.', 'sf-move-login' ) . '<br/>' . implode( '<br/>', $dies );

		wp_die( $dies, __( 'Error' ), array( 'back_link' => true ) );

	}

	// Perhaps we'll need to display some notices. Add the rewrite rules to the .htaccess/web.config file.
	set_transient( 'sfml_notices-' . get_current_user_id(), '1' );	// 1 means "Update the file".
}

register_activation_hook( SFML_FILE, 'sfml_activate' );


/*------------------------------------------------------------------------------------------------*/
/* !DEACTIVATION ================================================================================ */
/*------------------------------------------------------------------------------------------------*/

// !Remove rewrite rules from the .htaccess/web.config file.

function sfml_deactivate() {
	global $is_apache, $is_iis7;

	if ( ! function_exists( 'sfml_write_rules' ) ) {
		include( SFML_PLUGIN_DIR . 'inc/rewrite.php' );
	}

	// IIS
	if ( $is_iis7 ) {
		sfml_insert_iis7_rewrite_rules( 'SF Move Login' );		// Empty content
	}
	// Apache
	elseif ( $is_apache ) {
		sfml_insert_apache_rewrite_rules( 'SF Move Login' );	// Empty content
	}
}

register_deactivation_hook( SFML_FILE, 'sfml_deactivate' );


/*------------------------------------------------------------------------------------------------*/
/* !UTILITIES =================================================================================== */
/*------------------------------------------------------------------------------------------------*/

// !Messages used for notices and die()s

function sfml_notice_message( $k ) {
	global $is_iis7;
	static $messages;

	if ( is_null( $messages ) ) {
		$file = $is_iis7 ? '<code>web.config</code>' : '<code>.htaccess</code>';
		$link = '<a href="' . ( is_multisite() ? network_admin_url( 'settings.php?page=move-login' ) : admin_url( 'options-general.php?page=move-login' ) ) . '">Move Login</a>';

		$messages = array(
			'error_no_request_uri'      => __( 'It seems your server configuration prevent the plugin to work properly. <strong>Move Login</strong> won\'t work.', 'sf-move-login' ),
			'error_no_mod_rewrite'      => __( 'It seems the url rewrite module is not activated on your server. <strong>Move Login</strong> won\'t work.', 'sf-move-login' ),
			'error_unknown_server_conf' => __( 'It seems your server does not use <i>Apache</i>, <i>Nginx</i>, nor <i>IIS7</i>. <strong>Move Login</strong> won\'t work.', 'sf-move-login' ),
			'error_file_not_writable'   => sprintf( __( '<strong>Move Login</strong> needs access to the %1$s file. Please visit the %2$s settings page and copy/paste the given code into the %1$s file.', 'sf-move-login' ), $file, $link ),
			'updated_is_nginx'          => sprintf( __( 'It seems your server uses a <i>Nginx</i> system. You have to edit the rewrite rules by yourself in the configuration file. Please visit the %s settings page and take a look at the rewrite rules. <strong>Move Login</strong> is running but won\'t work correctly until you deal with those rewrite rules.', 'sf-move-login' ), $link ),
		);
	}

	return isset( $messages[ $k ] ) ? $messages[ $k ] : '';
}

/**/