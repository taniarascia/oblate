<?php
/**
 * Plugin Name: Simple social share
 * Plugin URI: http://perials.com
 * Description: A simple plugin for displaying social media icons to share Post/Page content
 * Version: 2.1
 * Author: Perials
 * Author URI: http://perials.com
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) 
die("You don't have sufficient permission to access this page");

require_once('includes/admin-form.php');
require_once('includes/template-buttons-w-c.php');
require_once('includes/template-buttons-with-count.php');
require_once('includes/small-buttons.php');
require_once('includes/services-js-array.php');

class simple_social_share{

	public function __construct(){

		add_action( 'admin_menu', array( $this, 's3_register_submenu' ) );
		add_action( 'admin_init', array( $this, 's3_register_settings' ) );	

		add_action( 'wp_enqueue_scripts', array( $this,'s3_load_styles_scripts' ) );	
		add_action( 'admin_enqueue_scripts', array( $this,'s3_load_admin_styles_scripts' ) );
		add_action('wp_footer', array($this,'s3_scripts_footer'));

		add_filter( 'the_content', array( $this, 'append_s3_html' ) );

		add_shortcode('simple-social-share', array($this,'s3_html_markup' ));

		register_activation_hook( __FILE__, array( $this, 's3_load_defaults' ) );

	}

	public function s3_load_admin_styles_scripts(){

		wp_enqueue_style( 'simple-social-share-admin', plugins_url('css/admin.css',__FILE__) );
		wp_enqueue_script( 's3-admin-js', plugins_url('js/admin.js',__FILE__) );

	}

	public function s3_load_styles_scripts(){

		$s3_options = get_option('s3_options');

		wp_enqueue_style( 'simple-social-share-main', plugins_url('css/style.css',__FILE__) );
		
		if( !empty($s3_options['ss-select-animations']) && in_array('360-rotation', $s3_options['ss-select-animations']) && $s3_options['ss-select-style'] != 'horizontal-with-count' )
		wp_enqueue_style( '360-rotation', plugins_url('css/360-rotate.css',__FILE__) );
		
		if( !empty($s3_options['ss-select-animations']) && in_array('tooltip', $s3_options['ss-select-animations']) && $s3_options['ss-select-style'] != 'horizontal-with-count' ){
			wp_enqueue_style( 'tooltipster-css', plugins_url('css/tooltipster.css',__FILE__) );
			wp_enqueue_script( 'tooltipster-js', plugins_url('js/jquery.tooltipster.js',__FILE__), array('jquery') );
		}		

	}

	public function s3_scripts_footer(){

		$s3_options = get_option('s3_options');
		if( ($s3_options['ss-select-style'] == 'horizontal-with-count') || ($s3_options['ss-select-style'] == 'small-buttons')){
		
			$services_scripts_arr = get_services_js_arr($s3_options);
			if( !empty($s3_options['ss-selected-services']) ){
				foreach ($s3_options['ss-selected-services'] as $service) {
					echo $services_scripts_arr[$service];
				}
			}

		}

		if( !empty($s3_options['ss-select-animations']) && in_array('tooltip', $s3_options['ss-select-animations']) && $s3_options['ss-select-style'] != 'horizontal-with-count' && $s3_options['ss-select-style'] != 'small-buttons' ){
			?>
			<script>
				jQuery(document).ready(function($) {
	            $(".hint--top").tooltipster({animation: "grow",});
	        	});
			</script>
			<?php
		}	
	}
	
	public function s3_get_services() {
		return array('facebook', 'twitter', 'googleplus', 'digg', 'reddit', 'linkedin', 'stumbleupon', 'tumblr', 'pinterest', 'email' );
	}

	public function s3_load_defaults(){

		$s3_options['ss-select-style'] = 'horizontal-w-c-circular';
		//$s3_options['ss-available-services'] = array('facebook', 'twitter', 'googleplus', 'digg', 'reddit', 'linkedin', 'stumbleupon', 'tumblr', 'pinterest', 'email' );
		$s3_options['ss-available-services'] = $this->s3_get_services();
		$s3_options['ss-selected-services'] = $s3_options['ss-available-services'];
		$s3_options['ss-select-position'] = array('before-content');
		$s3_options['ss-show-on'] = array('pages', 'posts');
		$s3_options['ss-select-animations'] = array('tooltip');
		update_option('s3_options', $s3_options);

	}

	public function append_s3_html( $content ){

		$s3_options = get_option('s3_options');
		$s3_html_markup = $this->s3_html_markup();
		if( in_array('before-content', (array)$s3_options['ss-select-position']) )
			$content = $s3_html_markup.$content;
		if( in_array('after-content', (array)$s3_options['ss-select-position']) )
			$content .= $s3_html_markup;
		return $content;

	}

	public function s3_html_markup(){
		
		$s3_options = get_option('s3_options');

		if( is_home() && !in_array( 'home', (array)$s3_options['ss-show-on'] ) )
			return '';
		if( is_single() && !in_array( 'posts', (array)$s3_options['ss-show-on'] ) )
			return '';
		if( is_page() && !in_array( 'pages', (array)$s3_options['ss-show-on'] ) )
			return '';
		if( is_archive() && !in_array( 'archive', (array)$s3_options['ss-show-on'] ) )
			return '';

		if( $s3_options['ss-select-style'] == 'horizontal-with-count' ){
			
			$class = '';
			$service_markup_arr = get_buttons_with_c_markup_arr();			

		}
		elseif( $s3_options['ss-select-style'] == 'small-buttons' ){
			
			$class = '';
			$service_markup_arr = get_small_buttons_markup_arr();			

		}
		else{

			$class = '';
			if ( $s3_options['ss-select-style'] == 'horizontal-w-c-square' )
				$class = 'horizontal-w-c-square';
			elseif( $s3_options['ss-select-style'] == 'horizontal-w-c-r-border' )
				$class = 'horizontal-w-c-r-border';
			elseif( $s3_options['ss-select-style'] == 'horizontal-w-c-circular' )
				$class = 'horizontal-w-c-circular';	
			$class .= ' s-share-w-c';

			$service_markup_arr = get_buttons_w_c_markup_arr();			
					
		}

		$html_markup = '';
		foreach ($service_markup_arr as $key => $value) {
			if( in_array($key, (array)$s3_options['ss-selected-services']) ){
				$html_markup .= $value;
			}
		}
		return '<div id="s-share-buttons" class="'.$class.'">'.$html_markup.'</div>';
		
	}

	public function s3_register_settings(){

		register_setting( 's3_options', 's3_options' );

	}

	public function s3_register_submenu(){

		add_submenu_page( 'options-general.php', 'Simple Social Share settings', 'Simple Social Share', 'activate_plugins', 'simple-social-share-settings', array( $this, 's3_submenu_page' ) );

	}

	public function s3_submenu_page() {
		?>
		<div class="wrap">
			<h2>Settings</h2>
			<form method="POST" action="options.php">
			<?php settings_fields('s3_options'); ?>
			<?php
			$s3_options = get_option('s3_options');
			$s3_options['ss-available-services'] = $this->s3_get_services();
			?>
			<?php admin_form($s3_options); ?>
		</div>
		<?php
	}

}
new simple_social_share;
?>