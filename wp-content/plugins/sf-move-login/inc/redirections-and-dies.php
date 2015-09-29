<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

/*------------------------------------------------------------------------------------------------*/
/* !REMOVE DEFAULT WORDPRESS REDIRECTIONS TO LOGIN AND ADMIN AREAS ============================== */
/*------------------------------------------------------------------------------------------------*/

remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );


/*------------------------------------------------------------------------------------------------*/
/* !IF THE CURRENT URI IS NOT LISTED IN OUR SLUGS, DENY ACCESS TO THE FORM ====================== */
/*------------------------------------------------------------------------------------------------*/

add_action( 'login_init', 'sfml_login_init', 0 );

function sfml_login_init() {
	// If the user is logged in, do nothing, lets WP redirect this user to the administration area.
	if ( is_user_logged_in() ) {
		return;
	}

	$uri       = sf_get_current_url( 'uri' );
	$subdir    = sfml_wp_directory();
	$slugs     = sfml_get_slugs();
	if ( $subdir ) {
		foreach ( $slugs as $action => $slug ) {
			$slugs[ $action ] = $subdir . $slug;
		}
	}
	// If you want to display the login form somewhere outside wp-login.php, add your URIs here.
	$new_slugs = apply_filters( 'sfml_slugs_not_to_kill', array(), $uri, $subdir, $slugs );
	$slugs     = is_array( $new_slugs ) && ! empty( $new_slugs ) ? array_merge( $new_slugs, $slugs ) : $slugs;

	if ( ! in_array( $uri, $slugs ) ) {
		do_action( 'sfml_wp_login_error' );

		// To make sure something happen.
		if ( false === has_action( 'sfml_wp_login_error' ) ) {
			sfml_wp_login_error();
		}
	}
}


add_action( 'sfml_wp_login_error', 'sfml_wp_login_error' );

function sfml_wp_login_error() {
	$do = sfml_deny_wp_login_access();

	switch ( $do ) {
		case 2:
			$redirect = $GLOBALS['wp_rewrite']->using_permalinks() ? home_url( '404' ) : add_query_arg( 'p', '404', home_url() );
			wp_safe_redirect( esc_url( user_trailingslashit( apply_filters( 'sfml_404_error_page', $redirect ) ) ) );
			exit;
		case 3:
			wp_safe_redirect( esc_url( user_trailingslashit( home_url() ) ) );
			exit;
		default:
			wp_die( __( 'No no no, the login form is not here.', 'sf-move-login' ) );
	}
}


/*------------------------------------------------------------------------------------------------*/
/* !IF NOT CONNECTED, DO NOT REDIRECT FROM ADMIN AREA TO WP-LOGIN.PHP =========================== */
/*------------------------------------------------------------------------------------------------*/

add_action( 'after_setup_theme', 'sfml_maybe_die_before_admin_redirect', 12 );

function sfml_maybe_die_before_admin_redirect() {
	global $pagenow;
	// If it's not the administration area, or if it's an ajax call, no need to go further.
	if ( ! ( is_admin() && ! ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( 'admin-post.php' === $pagenow && ! empty( $_REQUEST['action'] ) ) ) ) ) {
		return;
	}

	$scheme = is_user_admin() ? 'logged_in' : apply_filters( 'auth_redirect_scheme', '' );

	if ( ! wp_validate_auth_cookie( '', $scheme ) && sfml_deny_admin_access() ) {
		do_action( 'sfml_wp_admin_error' );

		// To make sure something happen.
		if ( false === has_action( 'sfml_wp_admin_error' ) ) {
			sfml_wp_admin_error();
		}
	}
}


add_action( 'sfml_wp_admin_error', 'sfml_wp_admin_error' );

function sfml_wp_admin_error() {
	$do = sfml_deny_admin_access();

	switch ( $do ) {
		case 1:
			wp_die( __( 'Cheatin&#8217; uh?' ) );
		case 2:
			$redirect = $GLOBALS['wp_rewrite']->using_permalinks() ? home_url( '404' ) : add_query_arg( 'p', '404', home_url() );
			wp_safe_redirect( esc_url( user_trailingslashit( apply_filters( 'sfml_404_error_page', $redirect ) ) ) );
			exit;
		case 3:
			wp_safe_redirect( esc_url( user_trailingslashit( home_url() ) ) );
			exit;
	}
}

/**/