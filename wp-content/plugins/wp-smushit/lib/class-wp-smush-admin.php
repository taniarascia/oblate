<?php
/**
 * @package WP Smush
 * @subpackage Admin
 * @version 1.0
 *
 * @author Saurabh Shukla <saurabh@incsub.com>
 * @author Umesh Kumar <umesh@incsub.com>
 *
 * @copyright (c) 2015, Incsub (http://incsub.com)
 */
if ( ! class_exists( 'WpSmushitAdmin' ) ) {
	/**
	 * Show settings in Media settings and add column to media library
	 *
	 */

	/**
	 * Class WpSmushitAdmin
	 *
	 * @property int $remaining_count
	 * @property int $total_count
	 * @property int $smushed_count
	 * @property int $exceeding_items_count
	 */
	class WpSmushitAdmin extends WpSmush {

		/**
		 * @var array Settings
		 */
		public $settings;

		public $bulk;

		/**
		 * @var Total count of Attachments for Smushing
		 */
		public $total_count;

		/**
		 * @var Smushed attachments out of total attachments
		 */
		public $smushed_count;

		/**
		 * @array Stores the stats for all the images
		 */
		public $stats;

		/**
		 * @var int Limit for allowed number of images per bulk request
		 */
		public $max_free_bulk = 50; //this is enforced at api level too

		public $upgrade_url = 'https://premium.wpmudev.org/project/wp-smush-pro/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=WP%20Smush%20Upgrade';

		//Stores unsmushed ids
		private $ids = '';

		//Stores all lossless smushed ids
		private $lossless_ids = '';

		/**
		 * @var int Number of attachments exceeding free limit
		 */
		public $exceeding_items_count = 0;

		/**
		 * @var If the plugin is a pro version or not
		 */
		private $is_pro_user;

		/**
		 * Constructor
		 */
		public function __construct() {

			// Save Settings, Process Option, Need to process it early, so the pages are loaded accordingly, nextgen gallery integration is loaded at same action
			add_action( 'plugins_loaded', array( $this, 'process_options' ), 20 );

			// hook scripts and styles
			add_action( 'admin_init', array( $this, 'register' ) );

			// hook custom screen
			add_action( 'admin_menu', array( $this, 'screen' ) );

			//Handle Smush Bulk Ajax
			add_action( 'wp_ajax_wp_smushit_bulk', array( $this, 'process_smush_request' ) );

			//Handle Smush Single Ajax
			add_action( 'wp_ajax_wp_smushit_manual', array( $this, 'smush_single' ) );

			add_filter( 'plugin_action_links_' . WP_SMUSH_BASENAME, array(
				$this,
				'settings_link'
			) );
			add_filter( 'network_admin_plugin_action_links_' . WP_SMUSH_BASENAME, array(
				$this,
				'settings_link'
			) );
			//Attachment status, Grid view
			add_filter( 'attachment_fields_to_edit', array( $this, 'filter_attachment_fields_to_edit' ), 10, 2 );

			// hook into admin footer to load a hidden html/css spinner
			add_action( 'admin_footer-upload.php', array( $this, 'print_loader' ) );

			/// Smush Upgrade
			add_action( 'admin_notices', array( $this, 'smush_upgrade' ) );
		}

		/**
		 * Adds smush button and status to attachment modal and edit page if it's an image
		 *
		 *
		 * @param array $form_fields
		 * @param WP_Post $post
		 *
		 * @return array $form_fields
		 */
		function filter_attachment_fields_to_edit( $form_fields, $post ) {
			if ( ! wp_attachment_is_image( $post->ID ) ) {
				return $form_fields;
			}
			$form_fields['wp_smush'] = array(
				'label'         => __( 'WP Smush', 'wp-smushit' ),
				'input'         => 'html',
				'html'          => $this->smush_status( $post->ID ),
				'show_in_edit'  => true,
				'show_in_modal' => true,
			);

			return $form_fields;
		}

		function __get( $prop ) {

			if ( method_exists( "WpSmushitAdmin", $prop ) ) {
				return $this->$prop();
			}

			$method_name = "get_" . $prop;
			if ( method_exists( "WpSmushitAdmin", $method_name ) ) {
				return $this->$method_name();
			}
		}

		/**
		 * Add Bulk option settings page
		 */
		function screen() {
			global $hook_suffix;
			$admin_page_suffix = add_media_page( 'Bulk WP Smush', 'WP Smush', 'edit_others_posts', 'wp-smush-bulk', array(
				$this,
				'ui'
			) );
			// enqueue js only on this screen
			add_action( 'admin_print_scripts-' . $admin_page_suffix, array( $this, 'enqueue' ) );

			// Enqueue js on media screen
			add_action( 'admin_print_scripts-upload.php', array( $this, 'enqueue' ) );

			// Enqueue js on Post screen (Edit screen for media )
			add_action( 'admin_print_scripts-post.php', array( $this, 'enqueue' ) );

			// Enqueue js on Post screen (Edit screen for media )
			add_action( 'admin_print_scripts-post-new.php', array( $this, 'enqueue' ) );

			//For Nextgen gallery Pages, check later in enqueue function
			add_action( 'admin_print_scripts', array( $this, 'enqueue' ) );
		}

		/**
		 * Register js and css
		 */
		function register() {

			global $WpSmush;

			// Register js for smush utton in grid view
			$current_blog_id       = get_current_blog_id();
			$meta_key              = $current_blog_id == 1 ? 'wp_media_library_mode' : 'wp_' . $current_blog_id . '_media_library_mode';
			$wp_media_library_mode = get_user_meta( get_current_user_id(), $meta_key, true );

			//Either request variable is not empty and grid mode is set, or if request empty then view is as per user choice, or no view is set
			if ( ( ! empty( $_REQUEST['mode'] ) && $_REQUEST['mode'] == 'grid' ) ||
			     ( empty( $_REQUEST['mode'] ) && $wp_media_library_mode != 'list' )
			) {
				wp_register_script( 'wp-smushit-admin-js', WP_SMUSH_URL . 'assets/js/wp-smushit-admin.js', array(
					'jquery',
					'media-views'
				), WP_SMUSH_VERSION );
			} else {
				wp_register_script( 'wp-smushit-admin-js', WP_SMUSH_URL . 'assets/js/wp-smushit-admin.js', array(
					'jquery',
					'underscore'
				), WP_SMUSH_VERSION );
			}
			wp_register_script( 'wp-smushit-admin-media-js', WP_SMUSH_URL . 'assets/js/wp-smushit-admin-media.js', array( 'jquery' ), $WpSmush->version );


			/* Register Style. */
			wp_register_style( 'wp-smushit-admin-css', WP_SMUSH_URL . 'assets/css/wp-smushit-admin.css', array(), $WpSmush->version );
			wp_register_style( 'jquery-ui', WP_SMUSH_URL . 'assets/css/jquery-ui.css', array() );

		}

		/**
		 * enqueue js and css
		 */
		function enqueue() {
			global $pagenow;
			$current_screen = get_current_screen();
			$current_page   = $current_screen->base;

			//Do not enqueue, unless it is one of the required screen
			if ( $current_page != 'nggallery-manage-images' && $current_page != 'gallery_page_wp-smush-nextgen-bulk' && $pagenow != 'post.php' && $pagenow != 'upload.php' ) {
				return;
			}

			if ( $pagenow == 'post.php' ) {
				//Do not load any style or js on post types other than attachment
				$post_type = get_post_type();
				if ( empty( $post_type ) || $post_type !== 'attachment' ) {
					return;
				}
			}

			wp_enqueue_script( 'wp-smushit-admin-js' );
			wp_enqueue_script( 'jquery-ui-tooltip' );
			if ( $pagenow == 'post.php' || $pagenow == 'upload.php' ) {
				//For grid view, Need not load it anywhere else
				wp_enqueue_script( 'wp-smushit-admin-media-js' );
			}

			//Style
			wp_enqueue_style( 'wp-smushit-admin-css' );
			wp_enqueue_style( 'jquery-ui' );

			// localize translatable strings for js
			$this->localize();
		}

		/**
		 * Localize Translations
		 */
		function localize() {
			global $pagenow;
			if ( ! isset( $pagenow ) || ! in_array( $pagenow, array( "post.php", "upload.php", "post-new.php" ) ) ) {
				return;
			}

			$bulk   = new WpSmushitBulk();
			$handle = 'wp-smushit-admin-js';

			if ( $this->is_pro_user || $this->remaining_count <= $this->max_free_bulk ) {
				$bulk_now = __( 'Bulk Smush Now', 'wp-smushit' );
			} else {
				$bulk_now = sprintf( __( 'Bulk Smush %d Attachments', 'wp-smushit' ), $this->max_free_bulk );
			}

			$wp_smush_msgs = array(
				'progress'             => __( 'Smushing in Progress', 'wp-smushit' ),
				'done'                 => __( 'All Done!', 'wp-smushit' ),
				'bulk_now'             => $bulk_now,
				'something_went_wrong' => __( 'Ops!... something went wrong', 'wp-smushit' ),
				'resmush'              => __( 'Super-Smush', 'wp-smushit' ),
				'smush_it'             => __( 'Smush it', 'wp-smushit' ),
				'smush_now'            => __( 'Smush Now', 'wp-smushit' ),
				'sending'              => __( 'Sending ...', 'wp-smushit' ),
				"error_in_bulk"        => __( '{{errors}} image(s) were skipped due to an error.', 'wp-smushit' ),
				"all_supersmushed"     => __( 'All images are Super-Smushed.', 'wp-smushit' )
			);

			wp_localize_script( $handle, 'wp_smush_msgs', $wp_smush_msgs );

			//Localize smushit_ids variable, if there are fix number of ids
			$this->ids = ! empty( $_REQUEST['ids'] ) ? explode( ',', $_REQUEST['ids'] ) : $bulk->get_attachments();

			//If premium, Super smush allowed, all images are smushed, localize lossless smushed ids for bulk compression
			if ( $this->is_pro_user &&
			     ( $this->total_count == $this->smushed_count && empty( $this->ids ) )
			) {

				//Check if Super smush enabled
				$super_smush = get_option( WP_SMUSH_PREFIX . 'lossy', false );

				if ( $super_smush ) {
					//get the attachments, and get lossless count
					$this->lossless_ids = $this->get_lossless_attachments();
				}
			}

			//Array of all smushed, unsmushed and lossless ids
			$data = array(
				'smushed'   => $this->get_smushed_image_ids(),
				'unsmushed' => $this->ids,
				'lossless'  => $this->lossless_ids,
				'timeout'   => WP_SMUSH_TIMEOUT * 1000 //Convert it into ms
			);

			wp_localize_script( 'wp-smushit-admin-js', 'wp_smushit_data', $data );

		}

		/**
		 * Translation ready settings
		 */
		function init_settings() {
			if ( $this->is_pro_user ) {
				$smush_orgnl_txt = esc_html__( 'Smush all images, including originals.', 'wp_smushit' );
			} else {
				$count           = count( get_intermediate_image_sizes() );
				$smush_orgnl_txt = sprintf( esc_html__( "When you upload an image to WordPress it automatically creates %s thumbnail sizes that are commonly used in your pages. WordPress also stores the original full-size image, but because these are not usually embedded on your site we don’t Smush them. Pro users can override this.", 'wp_smushit' ), $count );
			}
			$this->settings = array(
				'auto'     => __( 'Smush images on upload', 'wp-smushit' ),
				'original' => __( 'Smush Original Image', 'wp-smushit' ) . '<span class="dashicons dashicons-info smush-original" title="' . $smush_orgnl_txt . '"></span>',
				'lossy'    => __( 'Super-Smush images', 'wp-smushit' ) . ' <small>(' . __( 'Lossy Image Compression', 'wp-smushit' ) . ')</small>',
				'backup'   => __( 'Backup Original Images', 'wp-smushit' ) . ' <small>(' . __( 'Will nearly double the size of your Uploads Directory', 'wp-smushit' ) . ')</small>',
				'nextgen'  => __( 'Enable NextGen Gallery integration', 'wp-smushit' )
			);
		}

		/**
		 * Runs the expensive queries to get our global smush stats
		 */
		function setup_global_stats() {
			$this->total_count   = $this->total_count();
			$this->smushed_count = $this->smushed_count();
			$this->stats         = $this->global_stats();
		}

		/**
		 * Display the ui
		 */
		function ui() {
			$this->setup_global_stats();
			?>
			<div class="wrap">

				<h2>
					<?php
					if ( $this->is_pro_user ) {
						_e( 'WP Smush Pro', 'wp-smushit' );
					} else {
						_e( 'WP Smush', 'wp-smushit' );
					} ?>
				</h2><?php
				$this->smush_pro_features();
				?>

				<div class="wp-smpushit-container">
					<h3>
						<?php _e( 'Settings', 'wp-smushit' ) ?>
					</h3>
					<?php
					// display the options
					$this->options_ui();

					//Bulk Smushing
					$this->bulk_preview();
					?>
				</div>
			</div>
			<?php
			$this->print_loader();
		}

		/**
		 * Process and display the options form
		 */
		function options_ui() {
			?>
			<form action="" method="post">

				<div id="wp-smush-options-wrap">
					<?php
					//Smush auto key
					$opt_auto = WP_SMUSH_PREFIX . 'auto';
					//Auto value
					$opt_auto_val = get_option( $opt_auto, false );

					//If value is not set for auto smushing set it to 1
					if ( $opt_auto_val === false ) {
						//default to checked
						$opt_auto_val = 1;
					}

					//Smush auto key
					$opt_original = WP_SMUSH_PREFIX . 'original';
					//Auto value
					$opt_original_val = get_option( $opt_original, false );

					//Smush auto key
					$opt_lossy = WP_SMUSH_PREFIX . 'lossy';
					//Auto value
					$opt_lossy_val = get_option( $opt_lossy, false );

					//Smush auto key
					$opt_backup = WP_SMUSH_PREFIX . 'backup';
					//Auto value
					$opt_backup_val = get_option( $opt_backup, false );

					//Smush NextGen key
					$opt_nextgen = WP_SMUSH_PREFIX . 'nextgen';
					//Auto value
					$opt_nextgen_val = get_option( $opt_nextgen, 1 );

					//disable lossy for non-premium members
					$disabled = $feature_class = '';

					//For free version, show unchecked
					if ( ! $this->is_pro_user ) {
						$opt_original_val = 0;
						$opt_lossy_val    = 0;
						$opt_backup_val   = 0;
						$opt_nextgen_val  = 0;

						$disabled      = ' disabled';
					} ?>
					<div class='wp-smush-setting-row'><?php
						// return html
						printf( "<label><input type='checkbox' name='%1\$s' id='%1\$s' value='1' %2\$s %3\$s>%4\$s</label>", esc_attr( $opt_auto ), checked( $opt_auto_val, 1, false ), '', $this->settings['auto'] );
						?>
					</div>
					<fieldset class="wp-smush-pro-features">
						<legend><strong><?php esc_html_e( "PRO FEATURES", "wp-smushit" ); ?></strong></legend><?php

						if ( ! $this->is_pro_user ) {
							?>
							<div class="pro-note">
								<div style="padding:14px 0 14px;">
									<span class="dashicons dashicons-info"></span><?php esc_html_e( "These features are available in Pro Version of the plugin.", "wp-smushit" ); ?>
									<a href="<?php echo $this->upgrade_url; ?>" target="_blank" class="button find-out-link">Find
										out more »</a>
								</div>
							</div>
							<?php
							$feature_class = ' disabled';
						}

						//Smush Original
						printf( "<div class='wp-smush-setting-row%5\$s'><label><input type='checkbox' name='%1\$s' id='%1\$s' value='1' %2\$s %3\$s>%4\$s</label></div>", esc_attr( $opt_original ), checked( $opt_original_val, 1, false ), $disabled, $this->settings['original'], $feature_class );

						//Lossy
						printf( "<div class='wp-smush-setting-row%5\$s'><label><input type='checkbox' name='%1\$s' id='%1\$s' value='1' %2\$s %3\$s>%4\$s</label></div>", esc_attr( $opt_lossy ), checked( $opt_lossy_val, 1, false ), $disabled, $this->settings['lossy'], $feature_class );

						//NextGen Gallery
						printf( "<div class='wp-smush-setting-row%5\$s'><label><input type='checkbox' name='%1\$s' id='%1\$s' value='1' %2\$s %3\$s>%4\$s</label></div>", esc_attr( $opt_nextgen ), checked( $opt_nextgen_val, 1, false ), $disabled, $this->settings['nextgen'], $feature_class );

						//Backup
						printf( "<div class='wp-smush-setting-row%5\$s'><label><input type='checkbox' name='%1\$s' id='%1\$s' value='1' %2\$s %3\$s>%4\$s</label></div>", esc_attr( $opt_backup ), checked( $opt_backup_val, 1, false ), $disabled, $this->settings['backup'], $feature_class ); ?>
						<!-- End of pro-only -->
					</fieldset>
				</div>
				<!-- End of wrap --><?php
				// nonce
				wp_nonce_field( 'save_wp_smush_options', 'wp_smush_options_nonce' );
				?>
				<input type="submit" id="wp-smush-save-settings" class="button button-primary" value="<?php _e( 'Save Changes', 'wp-smushit' ); ?>">
			</form>
			<?php
		}

		/**
		 * Check if form is submitted and process it
		 *
		 * @return null
		 */
		function process_options() {

			$this->is_pro_user = $this->is_pro();

			$this->init_settings();

			//If refresh is set in URL
			if ( isset( $_GET['refresh'] ) && $_GET['refresh'] ) {
				$this->refresh_status();
			}

			// we aren't saving options
			if ( ! isset( $_POST['wp_smush_options_nonce'] ) ) {
				return;
			}
			// the nonce doesn't pan out
			if ( ! wp_verify_nonce( $_POST['wp_smush_options_nonce'], 'save_wp_smush_options' ) ) {
				return;
			}
			// var to temporarily assign the option value
			$setting = null;

			// process each setting and update options
			foreach ( $this->settings as $name => $text ) {
				// formulate the index of option
				$opt_name = WP_SMUSH_PREFIX . $name;

				// get the value to be saved
				$setting = isset( $_POST[ $opt_name ] ) ? 1 : 0;

				// update the new value
				update_option( $opt_name, $setting );

				// unset the var for next loop
				unset( $setting );
			}

		}

		/**
		 * Returns number of images of larger than 1Mb size
		 *
		 * @return int
		 */
		function get_exceeding_items_count( $force_update = false ) {
			$count = wp_cache_get( 'exceeding_items', 'wp_smush' );
			if ( ! $count || $force_update ) {
				$count       = 0;
				$bulk        = new WpSmushitBulk();
				$attachments = $bulk->get_attachments();
				//Check images bigger than 1Mb, used to display the count of images that can't be smushed
				foreach ( $attachments as $attachment ) {
					if ( file_exists( get_attached_file( $attachment ) ) ) {
						$size = filesize( get_attached_file( $attachment ) );
					}
					if ( empty( $size ) || ! ( ( $size / WP_SMUSH_MAX_BYTES ) > 1 ) ) {
						continue;
					}
					$count ++;
				}
				wp_cache_set( 'exceeding_items', $count, 'wp_smush', 3000 );
			}

			return $count;
		}

		/**
		 * Bulk Smushing UI
		 */
		function bulk_preview() {

			$exceed_mb = '';
			if ( ! $this->is_pro_user ) {

				//Initialize exceeding item Count
				$this->exceeding_items_count = $this->get_exceeding_items_count();

				if ( $this->exceeding_items_count && $this->exceeding_items_count !== 0 ) {
					$exceed_mb = sprintf(
						_n( "%d image is over 1MB so will be skipped using the free version of the plugin.",
							"%d images are over 1MB so will be skipped using the free version of the plugin.", $this->exceeding_items_count, 'wp-smushit' ),
						$this->exceeding_items_count
					);
				}
			}
			?>
			<hr>
			<div class="bulk-smush">
				<h3><?php _e( 'Smush in Bulk', 'wp-smushit' ) ?></h3>
				<?php

				if ( $this->remaining_count == 0 ) {
					?>
					<p><?php _e( "Congratulations, all your images are currently Smushed!", 'wp-smushit' ); ?></p>
					<?php
					$this->progress_ui();

					//Display Super smush bulk progress bar
					$this->super_smush_bulk_ui();
				} else {
					?>
					<div class="smush-instructions">
						<h4 class="smush-remaining-images-notice"><?php printf( _n( "%d attachment in your media library has not been smushed.", "%d image attachments in your media library have not been smushed yet.", $this->remaining_count, 'wp-smushit' ), $this->remaining_count ); ?></h4>
						<?php if ( $exceed_mb ) { ?>
							<p class="error">
								<?php echo $exceed_mb; ?>
								<a href="<?php echo $this->upgrade_url; ?>"><?php _e( 'Remove size limit &raquo;', 'wp-smushit' ); ?></a>
							</p>

						<?php } ?>

						<p><?php _e( "Please be aware, smushing a large number of images can take a while depending on your server and network speed.
						<strong>You must keep this page open while the bulk smush is processing</strong>, but you can leave at any time and come back to continue where it left off.", 'wp-smushit' ); ?></p>

						<?php if ( ! $this->is_pro_user ) { ?>
							<p class="error">
								<?php printf( __( "Free accounts are limited to bulk smushing %d attachments per request. You will need to click to start a new bulk job after each %d attachments.", 'wp-smushit' ), $this->max_free_bulk, $this->max_free_bulk ); ?>
								<a href="<?php echo $this->upgrade_url; ?>"><?php _e( 'Remove limits &raquo;', 'wp-smushit' ); ?></a>
							</p>
						<?php } ?>


					</div>

					<!-- Bulk Smushing -->
					<?php wp_nonce_field( 'wp-smush-bulk', '_wpnonce' ); ?>
					<br/><?php
					$this->progress_ui();
					?>
					<p class="smush-final-log"></p>
					<?php
					$this->setup_button();
				}

				$auto_smush = get_site_option( WP_SMUSH_PREFIX . 'auto' );
				if ( ! $auto_smush && $this->remaining_count == 0 ) {
					?>
					<p><?php printf( __( 'When you <a href="%s">upload some images</a> they will be available to smush here.', 'wp-smushit' ), admin_url( 'media-new.php' ) ); ?></p>
					<?php
				} else { ?>
					<p>
					<?php
					// let the user know that there's an alternative
					printf( __( 'You can also smush images individually from your <a href="%s">Media Library</a>.', 'wp-smushit' ), admin_url( 'upload.php' ) );
					?>
					</p><?php
				}
				?>
			</div>
			<?php
		}

		/**
		 * Loading Image
		 */
		function print_loader() {
			?>
			<div class="wp-smush-loader-wrap hidden">
				<div class="floatingCirclesG">
					<div class="f_circleG" id="frotateG_01">
					</div>
					<div class="f_circleG" id="frotateG_02">
					</div>
					<div class="f_circleG" id="frotateG_03">
					</div>
					<div class="f_circleG" id="frotateG_04">
					</div>
					<div class="f_circleG" id="frotateG_05">
					</div>
					<div class="f_circleG" id="frotateG_06">
					</div>
					<div class="f_circleG" id="frotateG_07">
					</div>
					<div class="f_circleG" id="frotateG_08">
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Print out the progress bar
		 */
		function progress_ui() {

			// calculate %ages, avoid divide by zero error with no attachments
			if ( $this->total_count > 0 ) {
				$smushed_pc = $this->smushed_count / $this->total_count * 100;
			} else {
				$smushed_pc = 0;
			}

			$progress_ui = '<div id="progress-ui">';

			// display the progress bars
			$progress_ui .= '<div id="wp-smush-progress-wrap">
                                                <div id="wp-smush-fetched-progress" class="wp-smush-progressbar"><div style="width:' . $smushed_pc . '%"></div></div>
                                                <p id="wp-smush-compression">'
			                . __( "Reduced by ", 'wp-smushit' )
			                . '<span id="human">' . $this->stats['human'] . '</span> ( <span id="percent">' . number_format_i18n( $this->stats['percent'], 2, '.', '' ) . '</span>% )
                                                </p>
                                        </div>';

			// status divs to show completed count/ total count
			$progress_ui .= '<div id="wp-smush-progress-status">

                            <p id="fetched-status">' .
			                sprintf(
				                __(
					                '<span class="done-count">%d</span> of <span class="total-count">%d</span> total attachments have been smushed', 'wp-smushit'
				                ), $this->smushed_count, $this->total_count
			                ) .
			                '</p>
                                        </div>
				</div>';
			// print it out
			echo $progress_ui;
		}

		/**
		 * Processes the Smush request and sends back the next id for smushing
		 */
		function process_smush_request() {

			global $WpSmush;

			$should_continue = true;

			if ( empty( $_REQUEST['attachment_id'] ) ) {
				wp_send_json_error( 'missing id' );
			}

			if ( ! $this->is_pro_user ) {
				//Free version bulk smush, check the transient counter value
				$should_continue = $this->check_bulk_limit();
			}

			//If the bulk smush needs to be stopped
			if ( ! $should_continue ) {
				wp_send_json_error(
					array(
						'error'    => 'bulk_request_image_limit_exceeded',
						'continue' => false
					)
				);
			}

			$attachment_id = sanitize_key( $_REQUEST['attachment_id'] );

			$original_meta = wp_get_attachment_metadata( $attachment_id, true );

			$smush = $WpSmush->resize_from_meta_data( $original_meta, $attachment_id );

			$this->setup_global_stats();

			$stats = $this->stats;

			$stats['smushed'] = $this->smushed_count;
			$stats['total']   = $this->total_count;

			if ( is_wp_error( $smush ) ) {
				$error = $smush->get_error_message();
				//Check for timeout error and suggest to filter timeout
				if ( strpos( $error, 'timed out' ) ) {
					$msg = esc_html__( "Smush request timed out, You can try setting a higher value for `WP_SMUSH_API_TIMEOUT`.", "wp-smushit" );
				}
				wp_send_json_error( array( 'stats' => $stats, 'error_msg' => $msg ) );
			} else {
				wp_send_json_success( array( 'stats' => $stats ) );
			}
		}

		/**
		 * Smush single images
		 *
		 * @return mixed
		 */
		function smush_single() {
			if ( ! current_user_can( 'upload_files' ) ) {
				wp_die( __( "You don't have permission to work with uploaded files.", 'wp-smushit' ) );
			}

			if ( ! isset( $_GET['attachment_id'] ) ) {
				wp_die( __( 'No attachment ID was provided.', 'wp-smushit' ) );
			}

			global $WpSmush;

			$attachment_id = intval( $_GET['attachment_id'] );

			$original_meta = wp_get_attachment_metadata( $attachment_id );

			$smush = $WpSmush->resize_from_meta_data( $original_meta, $attachment_id );

			$status = $WpSmush->set_status( $attachment_id, false, true );

			/** Send stats **/
			if ( is_wp_error( $smush ) ) {
				/**
				 * @param WP_Error $smush
				 */
				wp_send_json_error( $smush->get_error_message() );
			} else {
				wp_send_json_success( $status );
			}

		}

		/**
		 * Check bulk sent count, whether to allow further smushing or not
		 *
		 * @return bool
		 */
		function check_bulk_limit() {

			$transient_name  = WP_SMUSH_PREFIX . 'bulk_sent_count';
			$bulk_sent_count = get_transient( $transient_name );

			//If bulk sent count is not set
			if ( false === $bulk_sent_count ) {

				//start transient at 0
				set_transient( $transient_name, 1, 60 );

				return true;

			} else if ( $bulk_sent_count < $this->max_free_bulk ) {

				//If lte $this->max_free_bulk images are sent, increment
				set_transient( $transient_name, $bulk_sent_count + 1, 60 );

				return true;

			} else { //Bulk sent count is set and greater than $this->max_free_bulk

				//clear it and return false to stop the process
				set_transient( $transient_name, 0, 60 );

				return false;

			}
		}

		/**
		 * The UI for bulk smushing
		 *
		 * @return null
		 */
		function all_ui( $send_ids ) {

			// if there are no images in the media library
			if ( $this->total_count < 1 ) {
				printf(
					__(
						'<p>Please <a href="%s">upload some images</a>.</p>', 'wp-smushit'
					), admin_url( 'media-new.php' )
				);

				// no need to print out the rest of the UI
				return;
			}

			// otherwise, start displaying the UI
			?>
			<div id="all-bulk" class="wp-smush-bulk-wrap">
				<?php
				// everything has been smushed, display a notice
				if ( $this->smushed_count === $this->total_count ) {
					?>
					<p>
						<?php
						_e( 'All your images are already smushed!', 'wp-smushit' );
						?>
					</p>
					<?php
				} else {
					$this->selected_ui( $send_ids, '' );
					// we have some smushing to do! :)
					// first some warnings
					?>
					<p>
						<?php
						// let the user know that there's an alternative
						printf( __( 'You can also smush images individually from your <a href="%s">Media Library</a>.', 'wp-smushit' ), admin_url( 'upload.php' ) );
						?>
					</p>
					<?php
				}

				// display the progress bar
				$this->progress_ui();

				// display the appropriate button
				$this->setup_button();

				?>
			</div>
			<?php
		}

		/**
		 * Total Image count
		 * @return int
		 */
		function total_count() {
			$query   = array(
				'fields'         => 'ids',
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'post_mime_type' => array( 'image/jpeg', 'image/gif', 'image/png' ),
				'order'          => 'ASC',
				'posts_per_page' => - 1,
				'no_found_rows'  => true
			);
			$results = new WP_Query( $query );
			$count   = ! empty( $results->post_count ) ? $results->post_count : 0;

			// send the count
			return $count;
		}

		/**
		 * Optimised images count
		 *
		 * @param bool $return_ids
		 *
		 * @return array|int
		 */
		function smushed_count( $return_ids = false ) {
			$query = array(
				'fields'         => 'ids',
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'post_mime_type' => array( 'image/jpeg', 'image/gif', 'image/png' ),
				'order'          => 'ASC',
				'posts_per_page' => - 1,
				'meta_key'       => 'wp-smpro-smush-data',
				'no_found_rows'  => true
			);

			$results = new WP_Query( $query );
			if ( ! $return_ids ) {
				$count = ! empty( $results->post_count ) ? $results->post_count : 0;
			} else {
				return $results->posts;
			}

			// send the count
			return $count;
		}

		/**
		 * Returns remaining count
		 *
		 * @return int
		 */
		function remaining_count() {
			return $this->total_count - $this->smushed_count;
		}

		/**
		 * Display Thumbnails, if bulk action is choosen
		 */
		function selected_ui( $send_ids, $received_ids ) {
			if ( empty( $received_ids ) ) {
				return;
			}

			?>
			<div id="select-bulk" class="wp-smush-bulk-wrap">
				<p>
					<?php
					printf(
						__(
							'<strong>%d of %d images</strong> were sent for smushing:',
							'wp-smushit'
						),
						count( $send_ids ), count( $received_ids )
					);
					?>
				</p>
				<ul id="wp-smush-selected-images">
					<?php
					foreach ( $received_ids as $attachment_id ) {
						$this->attachment_ui( $attachment_id );
					}
					?>
				</ul>
			</div>
			<?php
		}

		/**
		 * Display the bulk smushing button
		 */
		function setup_button( $super_smush = false ) {
			$button   = $this->button_state( $super_smush );
			$disabled = ! empty( $button['disabled'] ) ? ' disabled="disabled"' : '';
			?>
			<button class="button button-primary<?php echo ' ' . $button['class']; ?>" name="smush-all" <?php echo $disabled; ?>>
				<span><?php echo $button['text'] ?></span>
			</button>
			<?php
		}

		function global_stats() {

			global $wpdb, $WpSmush;

			$smush_data = array(
				'size_before' => 0,
				'size_after'  => 0,
				'percent'     => 0,
				'human'       => 0
			);

			/**
			 * Allows to set a limit of mysql query
			 * Default value is 2000
			 */
			$limit  = apply_filters( 'wp_smush_media_query_limit', 2000 );
			$limit  = intval( $limit );
			$offset = 0;

			while ( $global_data = $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key=%s LIMIT $offset, $limit", "wp-smpro-smush-data" ) ) ) {

				if ( ! empty( $global_data ) ) {
					foreach ( $global_data as $data ) {
						$data = maybe_unserialize( $data );
						if ( ! empty( $data['stats'] ) ) {
							$smush_data['size_before'] += ! empty( $data['stats']['size_before'] ) ? (int) $data['stats']['size_before'] : 0;
							$smush_data['size_after'] += ! empty( $data['stats']['size_after'] ) ? (int) $data['stats']['size_after'] : 0;
						}
					}
				}

				$smush_data['bytes'] = $smush_data['size_before'] - $smush_data['size_after'];
				$offset += $limit;
			}

			if ( ! isset( $smush_data['bytes'] ) || $smush_data['bytes'] < 0 ) {
				$smush_data['bytes'] = 0;
			}

			if ( $smush_data['size_before'] > 0 ) {
				$smush_data['percent'] = ( $smush_data['bytes'] / $smush_data['size_before'] ) * 100;
			}

			//Round off precentage
			$smush_data['percent'] = round( $smush_data['percent'], 2 );

			$smush_data['human'] = $WpSmush->format_bytes( $smush_data['bytes'] );

			return $smush_data;
		}

		/**
		 * Returns Bulk smush button id and other details, as per if bulk request is already sent or not
		 *
		 * @return array
		 */

		private function button_state( $super_smush ) {
			$button = array(
				'cancel' => false,
			);
			if ( $super_smush ) {
				if ( $this->is_pro_user || $this->remaining_count <= $this->max_free_bulk ) { //if premium or under limit

					$button['text']  = __( 'Bulk Smush Now', 'wp-smushit' );
					$button['class'] = 'wp-smush-button wp-smush-send';
				} else {
					return array();
				}

			} else {

				// if we have nothing left to smush, disable the buttons
				if ( $this->smushed_count === $this->total_count ) {
					$button['text']     = __( 'All Done!', 'wp-smushit' );
					$button['class']    = 'wp-smush-finished disabled wp-smush-finished';
					$button['disabled'] = 'disabled';

				} else if ( $this->is_pro_user || $this->remaining_count <= $this->max_free_bulk ) { //if premium or under limit

					$button['text']  = __( 'Bulk Smush Now', 'wp-smushit' );
					$button['class'] = 'wp-smush-button wp-smush-send';

				} else { //if not premium and over limit
					$button['text']  = sprintf( __( 'Bulk Smush %d Attachments', 'wp-smushit' ), $this->max_free_bulk );
					$button['class'] = 'wp-smush-button wp-smush-send';

				}
			}

			return $button;
		}

		/**
		 * Queries all the attachments which have smushe meta
		 *
		 * @return array
		 */
		function get_smushed_image_ids() {
			$args  = array(
				'fields'         => 'ids',
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'post_mime_type' => array( 'image/jpeg', 'image/gif', 'image/png' ),
				'order'          => 'ASC',
				'posts_per_page' => - 1,
				'meta_query'     => array(
					array(
						'key'   => 'wp-is-smushed',
						'value' => '1',
					)
				),
				'no_found_rows'  => true
			);
			$query = new WP_Query( $args );

			return $query->posts;
		}

		/**
		 * Get the smush button text for attachment
		 */
		function smush_status( $id ) {
			$response = trim( $this->set_status( $id, false ) );

			return $response;
		}


		/**
		 * Adds a smushit pro settings link on plugin page
		 *
		 * @param $links
		 *
		 * @return array
		 */
		function settings_link( $links ) {

			$settings_page = admin_url( 'upload.php?page=wp-smush-bulk' );
			$settings      = '<a href="' . $settings_page . '">' . __( 'Settings', 'wp-smushit' ) . '</a>';

			array_unshift( $links, $settings );

			return $links;
		}

		/**
		 * Shows Notice for free users, displays a discount coupon
		 */
		function smush_upgrade() {

			if ( ! current_user_can( 'edit_others_posts' ) || ! is_super_admin() ) {
				return;
			}

			if ( isset( $_GET['page'] ) && 'wp-smush-bulk' == $_GET['page'] ) {
				return;
			}

			if ( isset( $_GET['dismiss_smush_upgrade'] ) ) {
				update_option( 'dismiss_smush_upgrade', 1 );
			}

			if ( get_option( 'dismiss_smush_upgrade' ) || $this->is_pro_user ) {
				return;
			}
			?>
			<div class="updated">
				<a href="<?php echo admin_url( 'index.php' ); ?>?dismiss_smush_upgrade=1" style="float:right;margin-top: 10px;text-decoration: none;"><span class="dashicons dashicons-dismiss" style="color:gray;"></span>Dismiss</a>

				<h3><span class="dashicons dashicons-megaphone" style="color:red"></span> Happy Smushing!</h3>

				<p>Welcome to the all new WP Smush, now running on the WPMU DEV Smush infrastructure!</p>

				<p>That means that you can continue smushing your images for free, now with added https support, speed,
					and reliability... enjoy!</p>

				<p>And now, if you'd like to upgrade to the WP Smush Pro plugin you can smush images up to 32MB in size,
					get 'Super Smushing' of, on average, 2&times; more reduction than lossless, backup all non smushed
					images and bulk smush an
					unlimited number of images at once.
					<a href="https://premium.wpmudev.org/?coupon=SMUSH50OFF#pricing"> Click here to upgrade with a 50%
						discount</a>.</p>
			</div>
			<?php
		}

		/**
		 * Get the smushed attachments from the database, except gif
		 *
		 * @global object $wpdb
		 *
		 * @param int|bool|array $attachment_id
		 *
		 * @return object query results
		 */
		function get_attachments() {

			global $wpdb;

			$allowed_images = "( 'image/jpeg', 'image/jpg', 'image/png' )";

			// get the attachment id, smush data
			$sql     = "SELECT p.ID as attachment_id, p.post_mime_type as type, ms.meta_value as smush_data"
			           . " FROM $wpdb->posts as p"
			           . " LEFT JOIN $wpdb->postmeta as ms"
			           . " ON (p.ID= ms.post_id AND ms.meta_key='wp-smpro-smush-data')"
			           . " WHERE"
			           . " p.post_type='attachment'"
			           . " AND p.post_mime_type IN " . $allowed_images
			           . " ORDER BY p . ID DESC"
			           // add a limit
			           . " LIMIT " . $this->total_count();
			$results = $wpdb->get_results( $sql );
			unset( $sql );

			return $results;
		}

		/**
		 * Returns the ids and meta which are losslessly compressed
		 *
		 * @return array
		 */
		function get_lossless_attachments() {

			$lossless_attachments = array();

			//Fetch all the smushed attachment ids
			$attachments = $this->get_attachments();

			//Check if image is lossless or lossy
			foreach ( $attachments as $attachment ) {

				//Check meta for lossy value
				$smush_data = ! empty( $attachment->smush_data ) ? maybe_unserialize( $attachment->smush_data ) : '';

				//Return if not smushed
				if ( empty( $smush_data ) ) {
					continue;
				}

				//if stats not set or lossy is not set for attachment, return
				if ( empty( $smush_data['stats'] ) || ! isset( $smush_data['stats']['lossy'] ) ) {
					continue;
				}
				//Add to array if lossy is not 1
				if ( $smush_data['stats']['lossy'] != 1 ) {
					$lossless_attachments[] = $attachment->attachment_id;
				}

			}
			unset( $attachments );

			return $lossless_attachments;
		}

		/**
		 * Adds progress bar for Super Smush bulk, if there are any lossless smushed images
		 */
		function super_smush_bulk_ui() {

			//Check if Super smush enabled
			$super_smush = get_option( WP_SMUSH_PREFIX . 'lossy', false );

			//Need to check, if there are any lossless ids
			if ( ! $super_smush || empty( $this->lossless_ids ) || count( $this->lossless_ids ) <= 0 ) {
				return;
			}

			$ss_progress_ui = '<h4>' . __( 'Super-Smush Images', 'wp-smushit' ) . '</h4>';
			$ss_progress_ui .= '<p>' . __( 'We found attachments that were previously smushed losslessly. If desired you can Super-Smush them now for more savings with almost no noticeable quality loss.', 'wp-smushit' ) . '</p>';
			$ss_progress_ui .= '<div id="progress-ui" class="super-smush">';

			// display the progress bars
			$ss_progress_ui .= '<div id="wp-smush-ss-progress-wrap">
			<div id="wp-smush-ss-progress" class="wp-smush-progressbar"><div style="width:0%"></div></div>
			<p id="wp-smush-compression">'
			                   . sprintf(
				                   _n( '<span class="remaining-count">%d</span> attachment left to Super-Smush',
					                   '<span class="remaining-count">%d</span> attachments left to Super-Smush',
					                   count( $this->lossless_ids ),
					                   'wp-smushit' ), count( $this->lossless_ids ), count( $this->lossless_ids ) )
			                   . '</p>
            </div>
			</div><!-- End of progress ui -->';
			echo $ss_progress_ui;
			$this->setup_button( true );
		}

		/**
		 * Displays the features available in Smush pro
		 */
		function smush_pro_features() {
			/**
			 * Allows to filter whether to display or not, features divison
			 */
			$show_features = apply_filters( 'smush_pro_features', true );

			if ( ! is_super_admin() || ! $show_features ) {
				return;
			}
			if ( $this->is_pro_user ) { ?>
				<div class="wp-smpushit-features updated">
					<h3><?php _e( 'Thanks for using WP Smush Pro! You now can:', 'wp-smushit' ) ?></h3>
					<ol>
						<li><?php _e( 'Smush the original Full image sizes that are normally skipped by default.', 'wp-smushit' ); ?></li>
						<li><?php _e( 'NextGen Gallery integration', 'wp-smushit' ); ?></li>
						<li><?php _e( '"Super-Smush" your images with our intelligent multi-pass lossy compression. Get 2&times; more compression than lossless with almost no noticeable quality loss!', 'wp-smushit' ); ?></li>
						<li><?php _e( 'Get the best lossless compression. We try multiple methods to squeeze every last byte out of your images.', 'wp-smushit' ); ?></li>
						<li><?php _e( 'Smush images up to 32MB.', 'wp-smushit' ); ?></li>
						<li><?php _e( 'Bulk smush ALL your images with one click!', 'wp-smushit' ); ?></li>
						<li><?php _e( 'Keep a backup of your original un-smushed images in case you want to restore later.', 'wp-smushit' ); ?></li>
					</ol>
				</div>
			<?php } else {
				if( $this->_get_api_key() ) {
					$refresh_url = add_query_arg( array( 'refresh' => 1 ) );
				}else{
					$refresh_url = '';
				}?>
				<div class="wp-smpushit-features error">
					<h3><?php _e( 'Upgrade to WP Smush Pro to:', 'wp-smushit' ) ?></h3>
					<ol>
						<li><?php _e( 'Smush the original Full image sizes that are normally skipped by default.', 'wp-smushit' ); ?></li>
						<li><?php _e( 'NextGen Gallery integration', 'wp-smushit' ); ?></li>
						<li><?php _e( '"Super-Smush" your images with our intelligent multi-pass lossy compression. Get 2&times; more compression than lossless with almost no noticeable quality loss!', 'wp-smushit' ); ?></li>
						<li><?php _e( 'Get the best lossless compression. We try multiple methods to squeeze every last byte out of your images.', 'wp-smushit' ); ?></li>
						<li><?php _e( 'Smush images greater than 1MB.', 'wp-smushit' ); ?></li>
						<li><?php _e( 'Bulk smush ALL your images with one click! No more rate limiting.', 'wp-smushit' ); ?></li>
						<li><?php _e( 'Keep a backup of your original un-smushed images in case you want to restore later.', 'wp-smushit' ); ?></li>
						<li><?php _e( 'Access 24/7/365 support from <a href="https://premium.wpmudev.org/support/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=WP%20Smush%20Upgrade">the best WordPress support team on the planet</a>.', 'wp-smushit' ); ?></li>
						<li><?php _e( 'Download <a href="https://premium.wpmudev.org/?utm_source=wordpress.org&utm_medium=plugin&utm_campaign=WP%20Smush%20Upgrade">350+ other premium plugins and themes</a> included in your membership.', 'wp-smushit' ); ?></li>
					</ol>
					<p>
						<a class="button-primary" href="<?php echo $this->upgrade_url; ?>"><?php _e( 'Upgrade Now &raquo;', 'wp-smushit' ); ?></a>
					</p>

					<p><?php _e( 'Already upgraded to a WPMU DEV membership? Install and Login to our Dashboard plugin to enable Smush Pro features.', 'wp-smushit' ); ?></p><?php
					if ( ! empty( $refresh_url ) ) {?>
						<p><?php echo sprintf( __( 'Unable to access Pro Features? <a href="%s">Refresh Status</a>', 'wp-smushit' ), $refresh_url ); ?></p><?php }
					?>

					<p>
						<?php
						if ( ! class_exists( 'WPMUDEV_Dashboard' ) ) {
							if ( file_exists( WP_PLUGIN_DIR . '/wpmudev-updates/update-notifications.php' ) ) {
								$function = is_multisite() ? 'network_admin_url' : 'admin_url';
								$url      = wp_nonce_url( $function( 'plugins.php?action=activate&plugin=wpmudev-updates%2Fupdate-notifications.php' ), 'activate-plugin_wpmudev-updates/update-notifications.php' );
								?>
								<a class="button-secondary" href="<?php echo $url; ?>"><?php _e( 'Activate WPMU DEV Dashboard', 'wp-smushit' ); ?></a><?php
							} else { //dashboard not installed at all
								?>
								<a class="button-secondary" target="_blank" href="https://premium.wpmudev.org/project/wpmu-dev-dashboard/"><?php _e( 'Install WPMU DEV Dashboard', 'wp-smushit' ); ?></a><?php
							}
						}
						?>
					</p>
				</div>
			<?php }
		}

		/**
		 * Delete Site Option, stored for api status
		 */
		function refresh_status() {

			delete_site_option('wp_smush_api_auth');
		}
	}

	global $wpsmushit_admin;
	$wpsmushit_admin = new WpSmushitAdmin();
}
