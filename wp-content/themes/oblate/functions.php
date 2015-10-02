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

function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

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
	}
	else
	{
		return  $arr[1] . $arr[2] . $arr[3];
	}
	
}


function filterCode($data) {

	//$modifiedData = preg_replace_callback('@(<pre.*>)(.*)(<\/pre>)@isU', 'escapeHTML', $data);
	$modifiedData = preg_replace_callback('@(<code.*>)(.*)(<\/code>)@isU', 'escapeHTML', $modifiedData);
	$modifiedData = preg_replace_callback('@(<tt.*>)(.*)(<\/tt>)@isU', 'escapeHTML', $modifiedData);
 
	return $modifiedData;
}


add_filter( 'content_save_pre', 'filterCode', 9 );
add_filter( 'excerpt_save_pre', 'filterCode', 9 );


?>