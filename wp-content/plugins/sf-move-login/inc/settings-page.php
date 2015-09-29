<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin\' uh?' );
}

/*------------------------------------------------------------------------------------------------*/
/* !SETTINGS PAGE =============================================================================== */
/*------------------------------------------------------------------------------------------------*/

// !Add settings fields and sections.

function sfml_settings_fields() {
	$labels   = SFML_Options::slugs_fields_labels();
	$defaults = sfml_get_default_options();
	$options  = sfml_get_options();

	// Sections
	add_settings_section( 'slugs',  __( 'Links' ), false, SFML_Options::OPTION_PAGE );
	add_settings_section( 'access', __( 'Access', 'sf-move-login' ), false, SFML_Options::OPTION_PAGE );

	// Fields
	foreach ( $labels as $slug => $label ) {
		if ( isset( $options[ 'slugs.' . $slug ] ) ) {
			// Slugs
			add_settings_field(
				'slugs-' . $slug,
				$label,
				'sfml_text_field',
				SFML_Options::OPTION_PAGE,
				'slugs',
				array(
					'label_for'  => 'slugs-' . $slug,
					'name'       => 'slugs.' . $slug,
					'value'      => $options[ 'slugs.' . $slug ],
					'default'    => $slug,
					'attributes' => array(
						'pattern' => '[0-9a-z_-]*',
						'title'   => __( 'Only lowercase letters, digits, - and _', 'sf-move-login' ),
					),
				)
			);
		}
	}

	// Deny access to login form.
	add_settings_field(
		'deny_wp_login_access',
		'<code>wp-login.php</code>',
		'sfml_radio_field',
		SFML_Options::OPTION_PAGE,
		'access',
		array(
			'label_for' => 'deny_wp_login_access',
			'value'     => $options['deny_wp_login_access'],
			'default'   => $defaults['deny_wp_login_access'],
			'values'    => array(
				1 => __( 'Display an error message', 'sf-move-login' ),
				2 => __( 'Redirect to a &laquo;Page not found&raquo; error page', 'sf-move-login' ),
				3 => __( 'Redirect to the home page', 'sf-move-login' ),
			),
			'label'     => '<strong>' . __( 'When a not connected user attempts to access the old login page.', 'sf-move-login' ) . '</strong>',
		)
	);

	// Deny access to admin area.
	add_settings_field(
		'deny_admin_access',
		__( 'Administration area', 'sf-move-login' ),
		'sfml_radio_field',
		SFML_Options::OPTION_PAGE,
		'access',
		array(
			'label_for' => 'deny_admin_access',
			'value'     => $options['deny_admin_access'],
			'default'   => $defaults['deny_admin_access'],
			'values'    => array(
				0 => __( 'Do nothing, redirect to the new login page', 'sf-move-login' ),
				1 => __( 'Display an error message', 'sf-move-login' ),
				2 => __( 'Redirect to a &laquo;Page not found&raquo; error page', 'sf-move-login' ),
				3 => __( 'Redirect to the home page', 'sf-move-login' ),
			),
			'label'     => '<strong>' . __( 'When a not connected user attempts to access the administration area.', 'sf-move-login' ) . '</strong>',
		)
	);
}

sfml_settings_fields();


/*
 * If the parent page is 'options-general.php', WP will automaticaly use settings_errors().
 * But the alerts will be displayed before the page title, and then some JS will move it after the title.
 * Under some circumstances (large page, slow browser), the "swap" won't happen fast enough, and the user will see it.
 * For the settings in the network admin, settings_errors() is not used at all.
 * So I prefer use my own settings_errors(), displayed AFTER the title.
 */

add_action( 'all_admin_notices', 'sfml_shunt_options_settings_errors', PHP_INT_MAX );

function sfml_shunt_options_settings_errors() {
	global $parent_file;
	$parent_file .= '#sfml';	// Prevent wp-admin/options-head.php to be included.
}


// !The settings page

function sfml_settings_page() { ?>
	<div class="wrap">
		<?php
		// WordPress 4.3 uses a `<h1>` tag, not a `<h2>` anymore. In the same time, get rid of the old icon.
		if ( version_compare( $GLOBALS['wp_version'], '4.3-RC1' ) >= 0 ) {
			echo '<h1>Move Login</h1>';
		}
		else {
			screen_icon( 'tools' );
			echo '<h2>Move Login</h2>';
		}

		require( ABSPATH . 'wp-admin/options-head.php' ); ?>

		<form name="<?php echo SFML_Options::OPTION_PAGE ?>" method="post" action="<?php echo is_multisite() ? admin_url( 'admin-post.php' ) : admin_url( 'options.php' ); ?>" id="<?php echo SFML_Options::OPTION_PAGE ?>">
			<?php
			do_settings_sections( SFML_Options::OPTION_PAGE );
			settings_fields( SFML_Options::OPTION_GROUP );
			submit_button();
			?>
		</form>

		<?php sfml_rewrite_rules_textarea(); ?>
	</div>
	<?php
}


/*------------------------------------------------------------------------------------------------*/
/* !SETTINGS FIELDS ============================================================================= */
/*------------------------------------------------------------------------------------------------*/

// !Text field

function sfml_text_field( $args ) {
	$name    = ! empty( $args['name'] )       ? esc_attr( $args['name'] )              : ( ! empty( $args['label_for'] ) ? esc_attr( $args['label_for'] ) : false );
	$id      = ! empty( $args['label_for'] )  ? esc_attr( $args['label_for'] )         : false;
	$value   = isset( $args['value'] )        ? esc_attr( $args['value'] )             : '';
	$default = isset( $args['default'] )      ? esc_attr( $args['default'] )           : null;
	$atts    = ! empty( $args['attributes'] ) ? build_html_atts( $args['attributes'] ) : '';

	if ( ! $name ) {
		return;
	}

	printf(
		'<input type="text" name="%s"%s%s value="%s"/>',
		SFML_Options::OPTION_NAME . '[' . $name . ']',
		$id ? ' id="' . $id . '"' : '',
		$atts,
		$value
	);

	if ( ! is_null( $default ) ) {
		echo ' <span class="description">' . sprintf( _x( '(default: %s)', 'default value', 'sf-move-login' ), $default ) . '</span>';
	}
}


// !Radio field

function sfml_radio_field( $args ) {
	$name    = ! empty( $args['name'] )      ? esc_attr( $args['name'] )      : ( ! empty( $args['label_for'] ) ? esc_attr( $args['label_for'] ) : false );
	$id      = ! empty( $args['label_for'] ) ? esc_attr( $args['label_for'] ) : 'radio-' . $name;
	$value   = isset( $args['value'] )       ? $args['value']                 : '';
	$values  = isset( $args['values'] )      ? $args['values']                : false;
	$default = isset( $args['default'] )     ? $args['default']               : null;
	$label   = isset( $args['label'] )       ? $args['label']                 : '';

	if ( ! $name || ! $values || ! is_array( $values ) ) {
		return;
	}

	if ( ! is_null( $default ) && ! isset( $values[ $value ] ) ) {
		$value = $default;
	}

	$i = 0;
	echo $label ? '<label for="' . $id . '">' . $label . '</label><br/>' : '';

	foreach ( $values as $input_value => $input_label ) {
		printf(
			'<input type="radio" name="%s" id="%s"%s value="%s"/>',
			SFML_Options::OPTION_NAME . '[' . $name . ']',
			$id . ( $i ? '-' . $i : '' ),
			$input_value === $value ? ' checked="checked"' : '',
			esc_attr( $input_value )
		);
		echo '<label for="' . $id . ( $i ? '-' . $i : '' ) . '">' . $input_label . '</label><br/>';
		$i++;
	}

	if ( ! is_null( $default ) && isset( $values[ $default ] ) ) {
		echo '<span class="description">' . sprintf( _x( '(default: %s)', 'default value', 'sf-move-login' ), $values[ $default ] ) . '</span>';
	}
}


// !A textarea displaying the rewrite rules. Used with or without Noop.

function sfml_rewrite_rules_textarea() {
	global $is_apache, $is_iis7;

	if ( ! function_exists( 'sfml_write_rules' ) ) {
		include( SFML_PLUGIN_DIR . 'inc/rewrite.php' );
	}

	$is_nginx = sfml_is_nginx();
	$rules    = sfml_rules();

	// Message
	$base              = parse_url( trailingslashit( get_option( 'home' ) ), PHP_URL_PATH );
	$document_root_fix = str_replace( '\\', '/', realpath( $_SERVER['DOCUMENT_ROOT'] ) );
	$abspath_fix       = str_replace( '\\', '/', ABSPATH );
	$home_path         = 0 === strpos( $abspath_fix, $document_root_fix ) ? $document_root_fix . $base : sfml_get_home_path();

	// IIS
	if ( $is_iis7 ) {
		$file          = 'web.config';
		$file_content  = implode( "\n", sfml_iis7_rewrite_rules( $rules, 'SF Move Login' ) );

		$height        = 20;
		$content       = sprintf(
			__( 'If the plugin fails to add the new rewrite rules to your %1$s file, add the following to your %1$s file in %2$s, replacing other %3$s rules if they exist, <strong>above</strong> the line reading %4$s:', 'sf-move-login' ),
			'<code>' . $file . '</code>', '<code>' . $home_path . '</code>', '<strong>Move Login</strong>', '<code>&lt;rule name="WordPress Rule 1" stopProcessing="true"&gt;</code>'
		);
	}
	// nginx
	elseif ( $is_nginx ) {
		$file          = 'nginx.conf';
		$file_content  = implode( "\n", sfml_nginx_rewrite_rules( $rules ) );

		$height        = substr_count( $file_content, "\n" );
		$content       = sprintf(
			'<span style="color:red">' . __( 'The plugin can\'t add the new rewrite rules to your %s file by itself, you will need to add them manually.', 'sf-move-login' ) . '</span>',
			'<code>' . $file . '</code>'
		);
		$file          = false;	// Don't check id the file is writable.
	}
	// Apache
	elseif ( $is_apache ) {
		$file          = '.htaccess';
		$file_content  = "\n# BEGIN SF Move Login\n";
		$file_content .= implode( "\n", sfml_apache_rewrite_rules( $rules ) );
		$file_content .= "\n# END SF Move Login\n";

		$height        = substr_count( $file_content, "\n" );
		$content       = sprintf(
			__( 'If the plugin fails to add the new rewrite rules to your %1$s file, add the following to your %1$s file in %2$s, replacing other %3$s rules if they exist, <strong>above</strong> the line reading %4$s:', 'sf-move-login' ),
			'<code>' . $file . '</code>', '<code>' . $home_path . '</code>', '<strong>Move Login</strong>', '<code># BEGIN WordPress</code>'
		);
	}
	else {
		return;
	}

	// Add a warning if the file is not writable.
	if ( $home_path && $file && ! wp_is_writable( $home_path . $file ) ) {
		$content .= '</p><p style="color:red">' . sprintf(
			__( 'Your %s file is not writable.', 'sf-move-login' ),
			'<code>' . $file . '</code>'
		);
	}

	// Add a warning if the plugin is bypassed.
	if ( defined( 'SFML_ALLOW_LOGIN_ACCESS' ) && SFML_ALLOW_LOGIN_ACCESS ) {
		$content .= '</p><p class="description">' . __( 'The constant <code>SFML_ALLOW_LOGIN_ACCESS</code> is defined to <code>true</code>, the settings below won\'t take effect.', 'sf-move-login' );
	}

	$content  = '<p>' . $content . ' <button type="button" id="sfml-file-button" class="button-secondary hide-if-no-js" style="vertical-align:baseline">' . __( 'Show' ) . "</button></p>\n";
	$content .= '<textarea id="sfml-file-content" class="code readonly hide-if-js" readonly="readonly" cols="120" rows="' . $height . '">' . esc_textarea( $file_content ) . "</textarea>\n";
	$content .= '<script type="text/javascript">jQuery( "#sfml-file-button" ).on( "click", function() { jQuery( this ).remove(); jQuery( "#sfml-file-content" ).removeClass( "hide-if-js" ); } );</script>' . "\n";

	echo $content;
}


/*------------------------------------------------------------------------------------------------*/
/* !TOOLS ======================================================================================= */
/*------------------------------------------------------------------------------------------------*/

// !Build a string for html attributes (means: separated by a space) : array( 'width' => '200', 'height' => '150', 'yolo' => 'foo' ) ==> ' width="200" height="150" yolo="foo"'

if ( ! function_exists( 'build_html_atts' ) ) :
function build_html_atts( $attributes, $quote = '"' ) {
	$out = '';

	if ( ! is_array( $attributes ) || empty( $attributes ) ) {
		return '';
	}

	foreach ( $attributes as $att_name => $att_value ) {
		$out .= ' ' . esc_attr( $att_name ) . '=' . $quote . $att_value . $quote;
	}

	return $out;
}
endif;


/**/