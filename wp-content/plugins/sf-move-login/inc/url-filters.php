<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

/*------------------------------------------------------------------------------------------------*/
/* !FILTER URLS ================================================================================= */
/*------------------------------------------------------------------------------------------------*/

// !Site URL

add_filter( 'site_url', 'sfml_site_url', 10, 4 );

function sfml_site_url( $url, $path, $scheme, $blog_id = null ) {
	if ( ! empty( $path ) && is_string( $path ) && false === strpos( $path, '..' ) && 0 === strpos( ltrim( $path, '/' ), 'wp-login.php' ) ) {
		$blog_id = (int) $blog_id;

		// Base url
		if ( empty( $blog_id ) || get_current_blog_id() === $blog_id || ! is_multisite() ) {
			$url = get_option( 'siteurl' );
		}
		else {
			switch_to_blog( $blog_id );
			$url = get_option( 'siteurl' );
			restore_current_blog();
		}

		$url = set_url_scheme( $url, $scheme );
		return rtrim( $url, '/' ) . '/' . ltrim( sfml_set_path( $path ), '/' );
	}

	return $url;
}


// !Network site URL: don't use network_site_url() for the login URL ffs!

add_filter( 'network_site_url', 'sfml_network_site_url', 10, 3 );

function sfml_network_site_url( $url, $path, $scheme ) {
	if ( ! empty( $path ) && is_string( $path ) && false === strpos( $path, '..' ) && 0 === strpos( ltrim( $path, '/' ), 'wp-login.php' ) ) {
		return site_url( $path, $scheme );
	}

	return $url;
}


// !Logout url: wp_logout_url() add the action param after using site_url().

add_filter( 'logout_url', 'sfml_logout_url', 1, 2 );

function sfml_logout_url( $logout_url, $redirect ) {
	return sfml_login_to_action( $logout_url, 'logout' );
}


// !Forgot password url: wp_lostpassword_url() add the action param after using network_site_url().

add_filter( 'lostpassword_url', 'sfml_lostpassword_url', 1, 2 );

function sfml_lostpassword_url( $lostpassword_url, $redirect ) {
	return sfml_login_to_action( $lostpassword_url, 'lostpassword' );
}


// !Redirections are hard-coded.

add_filter( 'wp_redirect', 'sfml_redirect', 10, 2 );

function sfml_redirect( $location, $status ) {
	if ( site_url( reset( ( explode( '?', $location ) ) ) ) === site_url( 'wp-login.php' ) ) {
		return sfml_site_url( $location, $location, 'login', get_current_blog_id() );
	}

	return $location;
}


// !Multisite: the "new site" welcome email.

add_filter( 'update_welcome_email', 'sfml_update_welcome_email', 10, 6 );

function sfml_update_welcome_email( $welcome_email, $blog_id, $user_id, $password, $title, $meta ) {
	$url = get_blogaddress_by_id( $blog_id );

	switch_to_blog( $blog_id );
	$login_url = wp_login_url();
	restore_current_blog();

	return str_replace( $url . 'wp-login.php', $login_url, $welcome_email );
}


/**/