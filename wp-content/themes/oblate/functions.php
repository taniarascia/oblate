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
