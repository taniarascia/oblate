<?php
/**
* Functions */

// Add support for featured images 
add_theme_support( 'post-thumbnails' );

// Change more excerpt
function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

// Add Categories for Attachments
function add_categories_for_attachments() {
    register_taxonomy_for_object_type( 'category', 'attachment' );
}
add_action( 'init' , 'add_categories_for_attachments' );

// Custom excerpt length
function custom_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// Support search form
add_theme_support( 'html5', array( 'search-form' ) );

// Change 'more' link

function modify_read_more_link() {
	return '<p class="right-text"><a class="button" href="' . get_permalink() . '">Read More</a></p>';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );

// Add Stylesheets
function oblate_scripts() {
	wp_enqueue_style( 'style', get_template_directory_uri() . '/css/main.css' );
	wp_enqueue_script( 'prism', get_template_directory_uri() . '/js/prism.js', array(), '1.0.0', true );
	wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'oblate_scripts' );

// Google Fonts
function load_fonts() {
	wp_register_style('Poppins', '//fonts.googleapis.com/css?family=Poppins:400,600,700');
	wp_register_style('Open Sans', '//fonts.googleapis.com/css?family=Open+Sans:400,600');
	wp_register_style('Heebo', '//fonts.googleapis.com/css?family=Heebo:400,700');
	wp_register_style('RobotoMono', '//fonts.googleapis.com/css?family=Roboto+Mono:400,500');
	wp_register_style('FontAwesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
	wp_enqueue_style( 'Poppins');
	wp_enqueue_style( 'Open Sans');
	wp_enqueue_style( 'Heebo');
	wp_enqueue_style( 'RobotoMono');
	wp_enqueue_style( 'FontAwesome');
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

// WordPress Titles
add_theme_support( 'title-tag' );

// Disable XML RPC
add_filter('xmlrpc_enabled', '__return_false');

remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

// OG Tags
function meta_og() {
	global $post;

	if(is_single()) {
		if(has_post_thumbnail($post->ID)) {
				$img_src = wp_get_attachment_image_src(get_post_thumbnail_id( $post->ID ), 'thumbnail');
		} else {
				$img_src = get_stylesheet_directory_uri() . '/images/taniasmall.jpg';
		}
		$excerpt = strip_tags($post->post_content);
		$excerpt_more = '';
		if (strlen($excerpt) > 155) {
			$excerpt = substr($excerpt,0,155);
			$excerpt_more = ' ...';
		}
		$excerpt = str_replace('"', '', $excerpt);
		$excerpt = str_replace("'", '', $excerpt);
		$excerptwords = preg_split('/[\n\r\t ]+/', $excerpt, -1, PREG_SPLIT_NO_EMPTY);
		array_pop($excerptwords);
		$excerpt = implode(' ', $excerptwords) . $excerpt_more;
		?>
		<meta name="author" content="Tania Rascia">
		<meta name="description" content="<?php echo $excerpt; ?>">
		<meta property="og:title" content="<?php echo the_title(); ?>">
		<meta property="og:description" content="<?php echo $excerpt; ?>">
		<meta property="og:type" content="article"/>
		<meta property="og:url" content="<?php echo the_permalink(); ?>">
		<meta property="og:site_name" content="<?php echo get_bloginfo(); ?>">
		<meta property="og:image" content="<?php echo $img_src[0]; ?>">
<?php
	} else {
			return;
	}
}
add_action('wp_head', 'meta_og', 5);

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

// Disable W3TC footer comment for all users
add_filter( 'w3tc_can_print_comment', function( $w3tc_setting ) { return false; }, 10, 1 );