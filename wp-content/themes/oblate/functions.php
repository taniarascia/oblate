<?php

/**
* Functions
*/

// Add support for featured images 
add_theme_support( 'post-thumbnails' );

// Add support for widgets
function oblate_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Widget Area', 'oblate' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'oblate' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'oblate_widgets_init' );

// Change more excerpt
function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

// Custom excerpt length
function custom_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// Support search form
add_theme_support( 'html5', array( 'search-form' ) );

// Change 'more' link
add_filter( 'the_content_more_link', 'modify_read_more_link' );
function modify_read_more_link() {
return '<p class="right-text"><a class="button" href="' . get_permalink() . '">Read More</a></p>';
}


// Add Stylesheets
function oblate_scripts() {
	wp_enqueue_style( 'style', get_template_directory_uri() . '/css/style.min.css' );
	wp_enqueue_script( 'prism', get_template_directory_uri() . '/js/prism.js', array(), '1.0.0', true );
	wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.min.js', array(), '1.0.0', true );
}

add_action( 'wp_enqueue_scripts', 'oblate_scripts' );

// Google Fonts
    function load_fonts() {
            wp_register_style('OpenSans', '//fonts.googleapis.com/css?family=Open+Sans:400,600,700,800');
			      wp_register_style('NotoSerif', '//fonts.googleapis.com/css?family=Noto+Serif:400,700');
			      wp_register_style('RobotoMono', '//fonts.googleapis.com/css?family=Roboto+Mono:400,300,500,700');
            wp_enqueue_style( 'OpenSans');
						wp_enqueue_style( 'NotoSerif');
						wp_enqueue_style( 'RobotoMono');
        }
    
    add_action('wp_print_styles', 'load_fonts');

// Disable Emoji Crap

function disable_wp_emojicons() {

  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );

function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}


// Disable XML RPC
add_filter('xmlrpc_enabled', '__return_false');

remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

// Escape HTML
function escapeHTML($arr) {
	
	// last params (double_encode) was added in 5.2.3
	if (version_compare(PHP_VERSION, '5.2.3') >= 0) {
	
		$output = htmlspecialchars($arr[2], ENT_NOQUOTES, get_bloginfo('charset'), false); 
	}
	else {
		$specialChars = array(
            '&' => '&amp;',
            '<' => '&lt;',
            '>' => '&gt;'
		);
		
		// decode already converted data
		$data = htmlspecialchars_decode($arr[2]);

		// escapse all data inside <pre>
		$output = strtr($data, $specialChars);
	}

	if (! empty($output)) {
		return  $arr[1] . $output . $arr[3];
	}	else 	{
		return  $arr[1] . $arr[2] . $arr[3];
	}	
}

function filterCode($data) {

	//$modifiedData = preg_replace_callback('@(<pre.*>)(.*)(<\/pre>)@isU', 'escapeHTML', $data);
	$modifiedData = preg_replace_callback('@(<code.*>)(.*)(<\/code>)@isU', 'escapeHTML', $data);
	$modifiedData = preg_replace_callback('@(<tt.*>)(.*)(<\/tt>)@isU', 'escapeHTML', $modifiedData);
 
	return $modifiedData;
}

add_filter( 'content_save_pre', 'filterCode', 9 );
add_filter( 'excerpt_save_pre', 'filterCode', 9 );
?>
