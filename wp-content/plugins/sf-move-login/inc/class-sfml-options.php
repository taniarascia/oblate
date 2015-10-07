<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

/*------------------------------------------------------------------------------------------------*/
/* !CLASS BEARING THE MECHANISM FOR OPTIONS ===================================================== */
/*------------------------------------------------------------------------------------------------*/

class SFML_Options {

	const VERSION      = '1.1';
	const OPTION_NAME  = 'sfml';
	const OPTION_GROUP = 'sfml_settings';
	const OPTION_PAGE  = 'move-login';

	protected static $options;
	protected static $options_default;
	protected static $slugs;
	protected static $labels;


	// !Init

	public static function init() {
		if ( defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			return;
		}
		// Register option and sanitization method.
		self::register_setting( array( __CLASS__, 'sanitize_options' ) );
	}


	// !Get default options

	public static function get_default_options() {

		self::maybe_clear_options_cache();

		if ( ! isset( self::$options_default ) ) {
			// Default slugs.
			self::$options_default = array(
				'slugs.postpass'     => 'postpass',
				'slugs.logout'       => 'logout',
				'slugs.lostpassword' => 'lostpassword',
				'slugs.resetpass'    => 'resetpass',
				'slugs.register'     => 'register',
				'slugs.login'        => 'login',
			);

			// Plugins can add their own actions.
			$additional_slugs = apply_filters( 'sfml_additional_slugs', array() );

			if ( is_array( $additional_slugs ) && ! empty( $additional_slugs ) ) {

				foreach ( $additional_slugs as $slug_key => $slug_label ) {
					$slug_key = sanitize_title( $slug_key, '', 'display' );

					if ( ! empty( $slug_key ) && ! isset( self::$options_default[ 'slug.' . $slug_key ] ) ) {
						self::$options_default[ 'slugs.' . $slug_key ] = $slug_key;
					}
				}

			}

			// Options
			self::$options_default = array_merge( self::$options_default, array(
				'deny_wp_login_access'   => 1,
				'deny_admin_access'      => 0,
			) );

		}

		return self::$options_default;
	}


	// !Get all options

	public static function get_options() {

		self::maybe_clear_options_cache();

		if ( ! isset( self::$options ) ) {
			self::$options = array();
			$old_options   = get_site_option( self::OPTION_NAME );
			$defaults      = self::get_default_options();

			if ( is_array( $old_options ) ) {
				$default_slugs = self::get_sub_options( 'slugs', $defaults );

				// Add and escape slugs
				foreach ( $default_slugs as $slug_key => $default_slug ) {
					self::$options[ 'slugs.' . $slug_key ] = ! empty( $old_options[ 'slugs.' . $slug_key ] ) ? sanitize_title( $old_options[ 'slugs.' . $slug_key ], $default_slug, 'display' ) : $default_slug;
				}

				// Add and escape other options
				if ( isset( $defaults['deny_wp_login_access'] ) ) {
					self::$options['deny_wp_login_access'] = isset( $old_options['deny_wp_login_access'] ) ? min( 3, max( 1, (int) $old_options['deny_wp_login_access'] ) ) : $defaults['deny_wp_login_access'];
				}

				if ( isset( $defaults['deny_admin_access'] ) ) {
					self::$options['deny_admin_access'] = isset( $old_options['deny_admin_access'] ) ? min( 3, max( 0, (int) $old_options['deny_admin_access'] ) ) : $defaults['deny_admin_access'];
				}
			}
			else {
				self::$options = $defaults;
			}

			// Generic filter, change the values.
			$options_tmp   = apply_filters( 'sfml_options', self::$options );
			self::$options = array_intersect_key( array_merge( self::$options, $options_tmp ), self::$options );	// Make sure no keys have been added or removed.
		}

		return self::$options;
	}


	// !Get the slugs

	public static function get_slugs() {

		self::maybe_clear_options_cache();

		if ( ! isset( self::$slugs ) ) {
			self::$slugs = self::get_sub_options( 'slugs', self::get_options() );
		}

		return self::$slugs;
	}


	// !Sanitize options on save

	public static function sanitize_options( $options = array() ) {

		$out           = array();
		$errors        = array( 'forbidden' => array(), 'duplicates' => array() );
		$old_options   = get_site_option( self::OPTION_NAME );
		$defaults      = self::get_default_options();

		// Add and sanitize slugs
		$default_slugs = self::get_sub_options( 'slugs', $defaults );
		$exclude       = self::get_other_actions();

		foreach ( $default_slugs as $slug_key => $default_slug ) {

			if ( isset( $exclude[ $slug_key ] ) ) {
				$out[ 'slugs.' . $slug_key ] = $exclude[ $slug_key ];
				continue;
			}

			$out[ 'slugs.' . $slug_key ] = false;

			if ( ! empty( $options[ 'slugs.' . $slug_key ] ) ) {
				$tmp_slug = sanitize_title( $options[ 'slugs.' . $slug_key ], $default_slug );

				// postpass, retrievepassword and rp are forbidden.
				if ( in_array( $tmp_slug, $exclude ) ) {
					$errors['forbidden'][] = $tmp_slug;
				}
				// Make sure the slug is not already set for another action.
				elseif ( in_array( $tmp_slug, $out ) ) {
					$errors['duplicates'][] = $tmp_slug;
				}
				// Yay!
				else {
					$out[ 'slugs.' . $slug_key ] = $tmp_slug;
				}
			}

			// Fallback to old value or default value.
			if ( ! $out[ 'slugs.' . $slug_key ] ) {
				if ( ! isset( $exclude[ $slug_key ] ) && ! empty( $old_options[ 'slugs.' . $slug_key ] ) ) {
					$out[ 'slugs.' . $slug_key ] = sanitize_title( $old_options[ 'slugs.' . $slug_key ], $default_slug );
				}
				else {
					$out[ 'slugs.' . $slug_key ] = $default_slug;
				}
			}

		}

		// Add and sanitize other options
		if ( isset( $defaults['deny_wp_login_access'] ) ) {
			if ( isset( $options['deny_wp_login_access'] ) ) {
				$out['deny_wp_login_access'] = min( 3, max( 1, (int) $options['deny_wp_login_access'] ) );
			}
			elseif ( isset( $old_options['deny_wp_login_access'] ) ) {
				$out['deny_wp_login_access'] = min( 3, max( 1, (int) $old_options['deny_wp_login_access'] ) );
			}
			else {
				$out['deny_wp_login_access'] = $defaults['deny_wp_login_access'];
			}
		}

		if ( isset( $defaults['deny_admin_access'] ) ) {
			if ( isset( $options['deny_admin_access'] ) ) {
				$out['deny_admin_access'] = min( 3, max( 0, (int) $options['deny_admin_access'] ) );
			}
			elseif ( isset( $old_options['deny_admin_access'] ) ) {
				$out['deny_admin_access'] = min( 3, max( 0, (int) $old_options['deny_admin_access'] ) );
			}
			else {
				$out['deny_admin_access'] = $defaults['deny_admin_access'];
			}
		}

		// Generic filter, change the values.
		$options_tmp = apply_filters( 'sfml_sanitize_options', $out, $options );
		$out         = array_merge( $out, $options_tmp );	// Make sure no keys have been removed.

		// Clear options cache.
		self::maybe_clear_options_cache( true );

		// Add the rewrite rules to the .htaccess/web.config file.
		$old_slugs = self::get_sub_options( 'slugs', $old_options );
		$new_slugs = self::get_sub_options( 'slugs', $out );

		if ( $old_slugs !== $new_slugs ) {
			if ( ! function_exists( 'sfml_write_rules' ) ) {
				include( SFML_PLUGIN_DIR . 'inc/rewrite.php' );
			}

			sfml_write_rules( sfml_rules( $new_slugs ) );
		}

		// Trigger errors
		if ( is_admin() ) {
			$errors['forbidden']  = array_unique( $errors['forbidden'] );
			$errors['duplicates'] = array_unique( $errors['duplicates'] );

			if ( $nbr_forbidden = count( $errors['forbidden'] ) ) {
				add_settings_error( 'sfml_settings', 'forbidden-slugs', sprintf( _n( 'The slug %s is forbidden.', 'The slugs %s are forbidden.', $nbr_forbidden, 'sf-move-login' ), wp_sprintf( '<code>%l</code>', $errors['forbidden'] ) ) );
			}
			if ( ! empty( $errors['duplicates'] ) ) {
				add_settings_error( 'sfml_settings', 'duplicates-slugs', __( 'The links can\'t have the same slugs.', 'sf-move-login' ) );
			}
		}

		return $out;
	}


	// !Get sub-options.

	public static function get_sub_options( $name = false, $options = array() ) {
		if ( empty( $options ) || ! $name ) {
			return array();
		}

		$options = (array) $options;

		if ( isset( $options[ $name ] ) ) {
			return $options[ $name ];
		}

		$group = array();
		$name  = rtrim( $name, '.' ) . '.';

		foreach ( $options as $k => $v ) {
			if ( 0 === strpos( $k, $name ) ) {
				$group[ substr( $k, strlen( $name ) ) ] = $v;
			}
		}

		return ! empty( $group ) ? $group : null;
	}


	// !Fields labels (for the slugs)

	public static function slugs_fields_labels() {

		self::maybe_clear_options_cache();

		if ( ! isset( self::$labels ) ) {
			self::$labels = array(
				'login'        => __( 'Log in' ),
				'logout'       => __( 'Log out' ),
				'register'     => __( 'Register' ),
				'lostpassword' => __( 'Lost Password' ),
				'resetpass'    => __( 'Password Reset' ),
			);

			// Plugins can add their own actions.
			$new_slugs = apply_filters( 'sfml_additional_slugs', array() );

			if ( ! empty( $new_slugs ) ) {
				$new_slugs    = array_diff_key( $new_slugs, self::$labels );
				self::$labels = array_merge( self::$labels, $new_slugs );
			}
		}

		return self::$labels;
	}


	// !Return the "other" original login actions: not the ones listed in our settings.

	public static function get_other_actions() {
		return array_diff_key( array(
			'retrievepassword' => 'retrievepassword',
			'rp'               => 'rp',
		), self::slugs_fields_labels() );
	}


	// !Clear options cache

	public static function maybe_clear_options_cache( $force = false ) {
		if ( $force || apply_filters( self::OPTION_NAME . '_clear_options_cache', false ) ) {
			self::$options         = null;
			self::$options_default = null;
			self::$slugs           = null;
			self::$labels          = null;
			remove_all_filters( self::OPTION_NAME . '_clear_options_cache' );
		}
	}


	// !register_setting() is not always defined...

	protected static function register_setting( $sanitize_callback = '' ) {
		global $new_whitelist_options;

		if ( is_admin() ) {

			if ( function_exists( 'register_setting' ) ) {
				register_setting( self::OPTION_GROUP, self::OPTION_NAME, $sanitize_callback );
				return;
			}

			$new_whitelist_options = isset( $new_whitelist_options ) && is_array( $new_whitelist_options ) ? $new_whitelist_options : array();
			$new_whitelist_options[ self::OPTION_GROUP ] = isset( $new_whitelist_options[ self::OPTION_GROUP ] ) && is_array( $new_whitelist_options[ self::OPTION_GROUP ] ) ? $new_whitelist_options[ self::OPTION_GROUP ] : array();
			$new_whitelist_options[ self::OPTION_GROUP ][] = self::OPTION_NAME;

		}

		if ( $sanitize_callback != '' ) {
			add_filter( 'sanitize_option_' . self::OPTION_NAME, $sanitize_callback );
		}
	}

}


SFML_Options::init();

/**/