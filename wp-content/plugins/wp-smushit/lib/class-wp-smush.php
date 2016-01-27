<?php
require_once WP_SMUSH_DIR . "lib/class-wp-smush-migrate.php";
if ( ! class_exists( 'WpSmush' ) ) {

	class WpSmush {

		var $version = WP_SMUSH_VERSION;

		/**
		 * @var Stores the value of is_pro function
		 */
		private $is_pro;

		/**
		 * Api server url to check api key validity
		 *
		 */
		var $api_server = 'https://premium.wpmudev.org/wdp-un.php?action=smushit_check';

		/**
		 * Meta key to save smush result to db
		 *
		 *
		 */
		var $smushed_meta_key = 'wp-smpro-smush-data';

		/**
		 * Meta key to save migrated version
		 *
		 */
		var $migrated_version_key = "wp-smush-migrated-version";

		/**
		 * Constructor
		 */
		function __construct() {
			/**
			 * Hooks
			 */
			//Check if auto is enabled
			$auto_smush = get_option( WP_SMUSH_PREFIX . 'auto' );

			//Keep the uto smush on by default
			if ( $auto_smush === false ) {
				$auto_smush = 1;
			}

			//Auto Smush the new image
			if ( $auto_smush ) {
				add_filter( 'wp_update_attachment_metadata', array(
						$this,
						'filter_generate_attachment_metadata'
				), 12, 2 );
			}

			//Optimise WP Retina 2x images
			add_action( 'wr2x_retina_file_added', array( $this, 'smush_retina_image' ), 20, 3 );

			//Add Smush Columns
			add_filter( 'manage_media_columns', array( $this, 'columns' ) );
			add_action( 'manage_media_custom_column', array( $this, 'custom_column' ), 10, 2 );
			add_filter( 'manage_upload_sortable_columns', array( $this, 'sortable_column' ) );
			//Manage column sorting
			add_action( 'pre_get_posts', array( $this, 'smushit_orderby' ) );

			//Enqueue Scripts
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			//Old Smush stats migration
			add_action( "admin_init", array( $this, "migrate" ) );

			//Load Translation files
			add_action( 'plugins_loaded', array( $this, 'i18n' ), 12 );

			//Load NextGen Gallery, if hooked too late or early, auto smush doesn't works, also Load after settings have been saved on init action
			add_action( 'plugins_loaded', array( $this, 'load_nextgen' ), 90 );
		}

		function i18n() {
			load_plugin_textdomain( 'wp-smushit', false, WP_SMUSH_DIR . '/languages/' );
		}

		function admin_init() {
			wp_enqueue_script( 'common' );
		}

		/**
		 * Process an image with Smush.
		 *
		 * Returns an array of the $file $results.
		 *
		 * @param   string $file Full absolute path to the image file
		 * @param   string $file_url Optional full URL to the image file
		 *
		 * @returns array
		 */
		function do_smushit( $file_path = '' ) {
			$errors = new WP_Error();
			$dir_name = dirname( $file_path );
			if ( empty( $file_path ) ) {
				$errors->add( "empty_path", __( "File path is empty", 'wp-smushit' ) );
			}

			// check that the file exists
			if ( ! file_exists( $file_path ) || ! is_file( $file_path ) ) {
				$errors->add( "file_not_found", sprintf( __( "Could not find %s", 'wp-smushit' ), $file_path ) );
			}

			// check that the file is writable
			if ( ! is_writable( $dir_name ) ) {
				$errors->add( "not_writable", sprintf( __( "%s is not writable", 'wp-smushit' ), $dir_name ) );
			}

			$file_size = file_exists( $file_path ) ? filesize( $file_path ) : '';

			//Check if premium user
			$max_size = $this->is_pro() ? WP_SMUSH_PREMIUM_MAX_BYTES : WP_SMUSH_MAX_BYTES;

			//Check if file exists
			if ( $file_size == 0 ) {
				$errors->add( "image_not_found", sprintf( __( 'Skipped (%s), image not found.', 'wp-smushit' ), $this->format_bytes( $file_size ) ) );
			}

			//Check size limit
			if ( $file_size > $max_size ) {
				$errors->add( "size_limit", sprintf( __( 'Skipped (%s), size limit exceeded.', 'wp-smushit' ), $this->format_bytes( $file_size ) ) );
			}

			if ( count( $errors->get_error_messages() ) ) {
				return $errors;
			}

			/** Send image for smushing, and fetch the response */
			$response = $this->_post( $file_path, $file_size );

			if ( ! $response['success'] ) {
				$errors->add( "false_response", $response['message'] );
			}
			//If there is no data
			if ( empty( $response['data'] ) ) {
				$errors->add( "no_data", __( 'Unknown API error', 'wp-smushit' ) );
			}

			if ( count( $errors->get_error_messages() ) ) {
				return $errors;
			}

			//If there are no savings, or image returned is bigger in size
			if ( ( ! empty( $response['data']->bytes_saved ) && intval( $response['data']->bytes_saved ) <= 0 )
			     || empty( $response['data']->image )
			) {
				return $response;
			}
			$tempfile = $file_path . ".tmp";

			//Add the file as tmp
			file_put_contents( $tempfile, $response['data']->image );

			//handle backups if enabled
			$backup = get_option( WP_SMUSH_PREFIX . 'backup' );
			if ( $backup && $this->is_pro() ) {
				$path        = pathinfo( $file_path );
				$backup_name = trailingslashit( $path['dirname'] ) . $path['filename'] . ".bak." . $path['extension'];
				@copy( $file_path, $backup_name );
			}

			//replace the file
			$success = @rename( $tempfile, $file_path );

			//if tempfile still exists, unlink it
			if ( file_exists( $tempfile ) ) {
				unlink( $tempfile );
			}

			//If file renaming failed
			if ( ! $success ) {
				copy( $tempfile, $file_path );
				unlink( $tempfile );
			}

			//Some servers are having issue with file permission, this should fix it
			chmod($file_path, 0644);

			return $response;
		}

		/**
		 * Fills $placeholder array with values from $data array
		 *
		 * @param array $placeholders
		 * @param array $data
		 *
		 * @return array
		 */
		function _array_fill_placeholders( array $placeholders, array $data ) {
			$placeholders['percent']     = $data['compression'];
			$placeholders['bytes']       = $data['bytes_saved'];
			$placeholders['size_before'] = $data['before_size'];
			$placeholders['size_after']  = $data['after_size'];
			$placeholders['time']        = $data['time'];

			return $placeholders;
		}

		/**
		 * Returns signature for single size of the smush api message to be saved to db;
		 *
		 * @return array
		 */
		function _get_size_signature() {
			return array(
					'percent'     => - 1,
					'bytes'       => - 1,
					'size_before' => - 1,
					'size_after'  => - 1,
					'time'        => - 1
			);
		}

		/**
		 * Read the image paths from an attachment's meta data and process each image
		 * with wp_smushit().
		 *
		 * This method also adds a `wp_smushit` meta key for use in the media library.
		 * Called after `wp_generate_attachment_metadata` is completed.
		 *
		 * @param $meta
		 * @param null $ID
		 *
		 * @return mixed
		 */
		function resize_from_meta_data( $meta, $ID = null ) {

			//Flag to check, if original size image should be smushed or not
			$original   = get_option( WP_SMUSH_PREFIX . 'original' );
			$smush_full = ( $this->is_pro() && $original == 1 ) ? true : false;

			$errors = new WP_Error();
			$stats  = array(
					"stats" => array_merge( $this->_get_size_signature(), array(
									'api_version' => - 1,
									'lossy'       => - 1
							)
					),
					'sizes' => array()
			);

			$size_before = $size_after = $compression = $total_time = $bytes_saved = 0;

			if ( $ID && wp_attachment_is_image( $ID ) === false ) {
				return $meta;
			}

			//File path and URL for original image
			$attachment_file_path = get_attached_file( $ID );

			// If images has other registered size, smush them first
			if ( ! empty( $meta['sizes'] ) ) {

				//if smush original is set to false, otherwise smush
				//Check for large size, we will set a flag to leave the original untouched
				if ( ! $smush_full ) {
					if ( array_key_exists( 'large', $meta['sizes'] ) ) {
						$smush_full = false;
					} else {
						$smush_full = true;
					}
				}

				if( class_exists('finfo') ) {
					$finfo = new finfo( FILEINFO_MIME_TYPE );
				}else{
					$finfo = false;
				}
				$image_path = trailingslashit( dirname( $attachment_file_path ) );
				foreach ( $meta['sizes'] as $size_key => $size_data ) {

					// We take the original image. The 'sizes' will all match the same URL and
					// path. So just get the dirname and replace the filename.
					$attachment_file_path_size =  $image_path . $size_data['file'];

					if ( $finfo ) {
						$ext = file_exists( $attachment_file_path_size ) ? $finfo->file( $attachment_file_path_size ) : '';
					} elseif ( function_exists( 'mime_content_type' ) ) {
						$ext = mime_content_type( $attachment_file_path_size );
					} else {
						$ext = false;
					}
					if( $ext ) {
						$valid_mime = array_search(
								$ext,
								array(
										'jpg' => 'image/jpeg',
										'png' => 'image/png',
										'gif' => 'image/gif',
								),
								true
						);
						if ( false === $valid_mime ) {
							continue;
						}
					}
					/**
					 * Allows to skip a image from smushing
					 *
					 * @param bool , Smush image or not
					 * @$size string, Size of image being smushed
					 */
					$smush_image = apply_filters( 'wp_smush_media_image', true, $size_key );
					if ( ! $smush_image ) {
						continue;
					}

					//Store details for each size key
					$response = $this->do_smushit( $attachment_file_path_size );

					if ( is_wp_error( $response ) ) {
						return $response;
					}

					if ( ! empty( $response['data'] ) ) {
						$stats['sizes'][ $size_key ] = (object) $this->_array_fill_placeholders( $this->_get_size_signature(), (array) $response['data'] );
					}

					//Total Stats, store all data in bytes
					if ( isset( $response['data'] ) ) {
						list( $size_before, $size_after, $total_time, $compression, $bytes_saved )
								= $this->_update_stats_data( $response['data'], $size_before, $size_after, $total_time, $bytes_saved );
					} else {
						$errors->add( "image_size_error" . $size_key, sprintf( __( "Size '%s' not processed correctly", 'wp-smushit' ), $size_key ) );
					}

					if ( empty( $stats['stats']['api_version'] ) || $stats['stats']['api_version'] == - 1 ) {
						$stats['stats']['api_version'] = $response['data']->api_version;
						$stats['stats']['lossy']       = $response['data']->lossy;
					}
				}
			}else{
				$smush_full = true;
			}

			/**
			 * Allows to skip a image from smushing
			 *
			 * @param bool , Smush image or not
			 * @$size string, Size of image being smushed
			 */
			$smush_full_image = apply_filters( 'wp_smush_media_image', true, 'full' );

			//If original size is supposed to be smushed
			if ( $smush_full && $smush_full_image ) {

				$full_image_response = $this->do_smushit( $attachment_file_path );

				if ( is_wp_error( $full_image_response ) ) {
					return $full_image_response;
				}

				if ( ! empty( $full_image_response['data'] ) ) {
					$stats['sizes']['full'] = (object) $this->_array_fill_placeholders( $this->_get_size_signature(), (array) $full_image_response['data'] );
				} else {
					$errors->add( "image_size_error", __( "Size 'full' not processed correctly", 'wp-smushit' ) );
				}

				//Update stats
				if ( isset( $full_image_response['data'] ) ) {
					list( $size_before, $size_after, $total_time, $compression, $bytes_saved )
							= $this->_update_stats_data( $full_image_response['data'], $size_before, $size_after, $total_time, $bytes_saved );
				} else {
					$errors->add( "image_size_error", __( "Size 'full' not processed correctly", 'wp-smushit' ) );
				}

				//Api version and lossy, for some images, full image i skipped and for other images only full exists
				//so have to add code again
				if ( empty( $stats['stats']['api_version'] ) || $stats['stats']['api_version'] == - 1 ) {
					$stats['stats']['api_version'] = $full_image_response['data']->api_version;
					$stats['stats']['lossy']       = $full_image_response['data']->lossy;
				}


			}

			$has_errors = (bool) count( $errors->get_error_messages() );

			list( $stats['stats']['size_before'], $stats['stats']['size_after'], $stats['stats']['time'], $stats['stats']['percent'], $stats['stats']['bytes'] ) =
					array( $size_before, $size_after, $total_time, $compression, $bytes_saved );

			//Set smush status for all the images, store it in wp-smpro-smush-data
			if ( ! $has_errors ) {

				$existing_stats = get_post_meta( $ID, $this->smushed_meta_key, true );

				if ( ! empty( $existing_stats ) ) {
					//Update total bytes saved, and compression percent
					$stats['stats']['bytes']   = isset( $existing_stats['stats']['bytes'] ) ? $existing_stats['stats']['bytes'] + $stats['stats']['bytes'] : $stats['stats']['bytes'];
					$stats['stats']['percent'] = isset( $existing_stats['stats']['percent'] ) ? $existing_stats['stats']['percent'] + $stats['stats']['percent'] : $stats['stats']['percent'];

					//Update stats for each size
					if ( isset( $existing_stats['sizes'] ) && ! empty( $stats['sizes'] ) ) {

						foreach ( $existing_stats['sizes'] as $size_name => $size_stats ) {
							//if stats for a particular size doesn't exists
							if ( empty( $stats['sizes'][ $size_name ] ) ) {
								$stats['sizes'][ $size_name ] = $existing_stats['sizes'][ $size_name ];
							} else {
								//Update compression percent and bytes saved for each size
								$stats['sizes'][ $size_name ]->bytes   = $stats['sizes'][ $size_name ]->bytes + $existing_stats['sizes'][ $size_name ]->bytes;
								$stats['sizes'][ $size_name ]->percent = $stats['sizes'][ $size_name ]->percent + $existing_stats['sizes'][ $size_name ]->percent;
							}
						}
					}
				}
				update_post_meta( $ID, $this->smushed_meta_key, $stats );
			}

			return $meta;
		}

		/**
		 * Read the image paths from an attachment's meta data and process each image
		 * with wp_smushit()
		 *
		 * Filters  wp_generate_attachment_metadata
		 *
		 * @uses resize_from_meta_data
		 *
		 * @param $meta
		 * @param null $ID
		 *
		 * @return mixed
		 */
		function filter_generate_attachment_metadata( $meta, $ID = null ) {
			//Update API url for Hostgator

			//Check for use of http url, (Hostgator mostly)
			$use_http = wp_cache_get( WP_SMUSH_PREFIX.'use_http', 'smush' );
			if( !$use_http ) {
				$use_http = get_option( WP_SMUSH_PREFIX.'use_http' );
				wp_cache_add( WP_SMUSH_PREFIX.'use_http', $use_http, 'smush' );
			}
			$use_http ? define( 'WP_SMUSH_API_HTTP', 'http://smushpro.wpmudev.org/1.0/') : '';

			$this->resize_from_meta_data( $meta, $ID );

			return $meta;
		}


		/**
		 * Posts an image to Smush.
		 *
		 * @param $file_path path of file to send to Smush
		 * @param $file_size
		 *
		 * @return bool|array array containing success status, and stats
		 */
		function _post( $file_path, $file_size ) {

			$data = false;

			$file      = @fopen( $file_path, 'r' );
			$file_data = fread( $file, $file_size );
			$headers   = array(
					'accept'       => 'application/json', // The API returns JSON
					'content-type' => 'application/binary', // Set content type to binary
			);

			//Check if premium member, add API key
			$api_key = $this->_get_api_key();
			if ( ! empty( $api_key ) ) {
				$headers['apikey'] = $api_key;
			}

			//Check if lossy compression allowed and add it to headers
			$lossy = get_option( WP_SMUSH_PREFIX . 'lossy' );

			if ( $lossy && $this->is_pro() ) {
				$headers['lossy'] = 'true';
			} else {
				$headers['lossy'] = 'false';
			}

			$api_url = defined( 'WP_SMUSH_API_HTTP' ) ? WP_SMUSH_API_HTTP : WP_SMUSH_API;
			$args   = array(
					'headers'    => $headers,
					'body'       => $file_data,
					'timeout'    => WP_SMUSH_TIMEOUT,
					'user-agent' => WP_SMUSH_UA,
			);
			$result = wp_remote_post( $api_url, $args );

			//Close file connection
			fclose( $file );
			unset( $file_data );//free memory
			if ( is_wp_error( $result ) ) {

				$er_msg = $result->get_error_message();

				//Hostgator Issue
				if ( ! empty( $er_msg ) && strpos( $er_msg, 'SSL CA cert' ) !== false ) {
					//Update DB for using http protocol
					update_option( WP_SMUSH_PREFIX . 'use_http', 1 );
				}
				//Handle error
				$data['message'] = sprintf( __( 'Error posting to API: %s', 'wp-smushit' ), $result->get_error_message() );
				$data['success'] = false;
				unset( $result ); //free memory
				return $data;
			} else if ( '200' != wp_remote_retrieve_response_code( $result ) ) {
				//Handle error
				$data['message'] = sprintf( __( 'Error posting to API: %s %s', 'wp-smushit' ), wp_remote_retrieve_response_code( $result ), wp_remote_retrieve_response_message( $result ) );
				$data['success'] = false;
				unset( $result ); //free memory

				return $data;
			}

			//If there is a response and image was successfully optimised
			$response = json_decode( $result['body'] );
			if ( $response && $response->success == true ) {

				//If there is any savings
				if ( $response->data->bytes_saved > 0 ) {
					$image     = base64_decode( $response->data->image ); //base64_decode is necessary to send binary img over JSON, no security problems here!
					$image_md5 = md5( $response->data->image );
					if ( $response->data->image_md5 != $image_md5 ) {
						//Handle error
						$data['message'] = __( 'Smush data corrupted, try again.', 'wp-smushit' );
						$data['success'] = false;
						unset( $image );//free memory
					} else {
						$data['success']     = true;
						$data['data']        = $response->data;
						$data['data']->image = $image;
						unset( $image );//free memory
					}
				} else {
					//just return the data
					$data['success'] = true;
					$data['data']    = $response->data;
				}
			} else {
				//Server side error, get message from response
				$data['message'] = ! empty( $response->data ) ? $response->data : __( "Image couldn't be smushed", 'wp-smushit' );
				$data['success'] = false;
			}

			unset( $result );//free memory
			unset( $response );//free memory
			return $data;
		}


		/**
		 * Print column header for Smush results in the media library using
		 * the `manage_media_columns` hook.
		 */
		function columns( $defaults ) {
			$defaults['smushit'] = 'WP Smush';

			return $defaults;
		}

		/**
		 * Return the filesize in a humanly readable format.
		 * Taken from http://www.php.net/manual/en/function.filesize.php#91477
		 */
		function format_bytes( $bytes, $precision = 2 ) {
			$units = array( 'B', 'KB', 'MB', 'GB', 'TB' );
			$bytes = max( $bytes, 0 );
			$pow   = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
			$pow   = min( $pow, count( $units ) - 1 );
			$bytes /= pow( 1024, $pow );

			return round( $bytes, $precision ) . ' ' . $units[ $pow ];
		}

		/**
		 * Print column data for Smush results in the media library using
		 * the `manage_media_custom_column` hook.
		 */
		function custom_column( $column_name, $id ) {
			if ( 'smushit' == $column_name ) {
				$this->set_status( $id );
			}
		}

		/**
		 * Check if user is premium member, check for api key
		 *
		 * @return mixed|string
		 */
		function is_pro() {

			if ( isset( $this->is_pro ) ) {
				return $this->is_pro;
			}

			//no api key set, always false
			$api_key = $this->_get_api_key();

			if ( empty( $api_key ) ) {
				return false;
			}

			//Flag to check if we need to revalidate the key
			$revalidate = false;

			$api_auth = get_site_option('wp_smush_api_auth');

			//Check if need to revalidate
			if ( ! $api_auth || empty( $api_auth ) || empty( $api_auth[ $api_key ] ) ) {
				$revalidate = true;
			} else {
				$last_checked = $api_auth[ $api_key ]['timestamp'];
				$valid        = $api_auth[ $api_key ]['validity'];

				$diff = $last_checked - current_time( 'timestamp' );

				//Difference in hours
				$diff_h = $diff / 3600;

				//Difference in minutes
				$diff_m = $diff / 60;

				switch ( $valid ) {
					case 'valid':
						//if last checked was more than 12 hours
						if ( $diff_h > 12 ) {
							$revalidate = true;
						}
						break;
					case 'invalid':
						//if last checked was more than 24 hours
						if ( $diff_h > 24 ) {
							$revalidate = true;
						}
						break;
					case 'network_failure':
						//if last checked was more than 5 minutes
						if ( $diff_m > 5 ) {
							$revalidate = true;
						}
						break;
				}
			}
			//If we are suppose to validate api, update the results in options table
			if ( $revalidate ) {
				// call api
				$url = $this->api_server . '&key=' . urlencode( $api_key );

				$request = wp_remote_get( $url, array(
								"user-agent" => WP_SMUSH_UA,
								"timeout"    => 10
						)
				);

				if ( ! is_wp_error( $request ) && '200' == wp_remote_retrieve_response_code( $request ) ) {
					$result = json_decode( wp_remote_retrieve_body( $request ) );
					if ( $result && $result->success ) {
						$valid = 'valid';
					} else {
						$valid = 'invalid';
					}

				} else {
					$valid = 'network_failure';
				}

				//Reset Value
				$api_auth = array();

				//Update Timestamp
				$timestamp              = current_time( 'timestamp' );
				$api_auth[ $api_key ] = array( 'validity' => $valid, 'timestamp' => $timestamp );

				//Update API validity
				update_site_option( 'wp_smush_api_auth', $api_auth );

			}

			$this->is_pro = ( 'valid' == $valid );

			return $this->is_pro;
		}

		/**
		 * Returns api key
		 *
		 * @return mixed
		 */
		function _get_api_key() {

			if ( defined( 'WPMUDEV_APIKEY' ) && WPMUDEV_APIKEY ) {
				$api_key = WPMUDEV_APIKEY;
			} else {
				$api_key = get_site_option( 'wpmudev_apikey' );
			}

			return $api_key;
		}


		/**
		 * Checks if image is already smushed
		 *
		 * @param int $id
		 * @param array $data
		 *
		 * @return bool|mixed
		 */
		function is_smushed( $id, $data = null ) {

			//For new images
			$wp_is_smushed = get_post_meta( $id, 'wp-is-smushed', true );

			//Not smushed, backward compatibility, check attachment metadata
			if ( ! $wp_is_smushed && $data !== null ) {
				if ( isset( $data['wp_smushit'] ) && ! empty( $data['wp_smushit'] ) ) {
					$wp_is_smushed = true;
				}
			}

			return $wp_is_smushed;
		}

		/**
		 * Returns size saved from the api call response
		 *
		 * @param string $message
		 *
		 * @return string|bool
		 */
		function get_saved_size( $message ) {
			if ( preg_match( '/\((.*)\)/', $message, $matches ) ) {
				return isset( $matches[1] ) ? $matches[1] : false;
			}

			return false;
		}

		/**
		 * Set send button status
		 *
		 * @param $id
		 * @param bool $echo
		 * @param bool $text_only
		 *
		 * @return string|void
		 */
		function set_status( $id, $echo = true, $text_only = false ) {
			$status_txt  = $button_txt = '';
			$show_button = false;

			$wp_smush_data = get_post_meta( $id, $this->smushed_meta_key, true );
			$attachment_data = wp_get_attachment_metadata( $id );
//
			// if the image is smushed
			if ( ! empty( $wp_smush_data ) ) {

				$image_count =  count( $wp_smush_data['sizes'] );
				$bytes          = isset( $wp_smush_data['stats']['bytes'] ) ? $wp_smush_data['stats']['bytes'] : 0;
				$bytes_readable = ! empty( $bytes ) ? $this->format_bytes( $bytes ) : '';
				$percent        = isset( $wp_smush_data['stats']['percent'] ) ? $wp_smush_data['stats']['percent'] : 0;
				$percent        = $percent < 0 ? 0 : $percent;

				if ( isset( $wp_smush_data['stats']['size_before'] ) && $wp_smush_data['stats']['size_before'] == 0 && ! empty( $wp_smush_data['sizes'] ) ) {
					$status_txt  = __( 'Error processing request', 'wp-smushit' );
					$show_button = true;
				} else {
					if ( $bytes == 0 || $percent == 0 ) {
						$status_txt = __( 'Already Optimized', 'wp-smushit' );
					} elseif ( ! empty( $percent ) && ! empty( $bytes_readable ) ) {
						$status_txt = $image_count > 1 ? sprintf( __( "%d images reduced ", 'wp-smushit' ), $image_count ) : __( "Reduced ", 'wp-smushit' );
						$status_txt .= sprintf( __( "by %s (  %01.1f%% )", 'wp-smushit' ), $bytes_readable, number_format_i18n( $percent, 2, '.', '' ) );

						//Show detailed stats if available
						if ( ! empty( $wp_smush_data['sizes'] ) ) {
							//Detailed Stats Link
							$status_txt .= '<br /><a href="#" class="smush-stats-details">' . esc_html__( "Smush stats", 'wp-smushit' ) . ' [<span class="stats-toggle">+</span>]</a>';

							//Stats
							$status_txt .= $this->get_detailed_stats( $id, $wp_smush_data, $attachment_data );
						}
					}
				}

				//IF current compression is lossy
				if ( ! empty( $wp_smush_data ) && ! empty( $wp_smush_data['stats'] ) ) {
					$lossy    = ! empty( $wp_smush_data['stats']['lossy'] ) ? $wp_smush_data['stats']['lossy'] : '';
					$is_lossy = $lossy == 1 ? true : false;
				}

				//Check if Lossy enabled
				$opt_lossy     = WP_SMUSH_PREFIX . 'lossy';
				$opt_lossy_val = get_option( $opt_lossy, false );

				//Check image type
				$image_type = get_post_mime_type( $id );

				//Check if premium user, compression was lossless, and lossy compression is enabled
				if ( $this->is_pro() && ! $is_lossy && $opt_lossy_val && $image_type != 'image/gif' ) {
					// the button text
					$button_txt  = __( 'Super-Smush', 'wp-smushit' );
					$show_button = true;
				}
			} else {

				// the status
				$status_txt = __( 'Not processed', 'wp-smushit' );

				// we need to show the smush button
				$show_button = true;

				// the button text
				$button_txt = __( 'Smush Now!', 'wp-smushit' );
			}
			if ( $text_only ) {
				return $status_txt;
			}

			$text = $this->column_html( $id, $status_txt, $button_txt, $show_button, $wp_smush_data, $echo );
			if ( ! $echo ) {
				return $text;
			}
		}

		/**
		 * Print the column html
		 *
		 * @param string $id Media id
		 * @param string $status_txt Status text
		 * @param string $button_txt Button label
		 * @param boolean $show_button Whether to shoe the button
		 *
		 * @return null
		 */
		function column_html( $id, $status_txt = "", $button_txt = "", $show_button = true, $smushed = false, $echo = true ) {
			$allowed_images = array( 'image/jpeg', 'image/jpg', 'image/png', 'image/gif' );

			// don't proceed if attachment is not image, or if image is not a jpg, png or gif
			if ( ! wp_attachment_is_image( $id ) || ! in_array( get_post_mime_type( $id ), $allowed_images ) ) {
				return;
			}

			$class = $smushed ? '' : ' hidden';
			$html  = '
			<p class="smush-status' . $class . '">' . $status_txt . '</p>';
			// if we aren't showing the button
			if ( ! $show_button ) {
				if ( $echo ) {
					echo $html;

					return;
				} else {
					if ( ! $smushed ) {
						$class = ' currently-smushing';
					} else {
						$class = ' smushed';
					}

					return '<div class="smush-wrap' . $class . '">' . $html . '</div>';
				}
			}
			if ( ! $echo ) {
				$html .= '
				<button  class="button button-primary wp-smush-send" data-id="' . $id . '">
	                <span>' . $button_txt . '</span>
				</button>';
				if ( ! $smushed ) {
					$class = ' unsmushed';
				} else {
					$class = ' smushed';
				}

				return '<div class="smush-wrap' . $class . '">' . $html . '</div>';
			} else {
				$html .= '<button class="button wp-smush-send" data-id="' . $id . '">
                    <span>' . $button_txt . '</span>
				</button>';
				echo $html;
			}
		}

		/**
		 * Migrates smushit api message to the latest structure
		 *
		 *
		 * @return void
		 */
		function migrate() {

			if ( ! version_compare( $this->version, "1.7.1", "lte" ) ) {
				return;
			}

			$migrated_version = get_option( $this->migrated_version_key );

			if ( $migrated_version === $this->version ) {
				return;
			}

			global $wpdb;

			$q       = $wpdb->prepare( "SELECT * FROM `" . $wpdb->postmeta . "` WHERE `meta_key`=%s AND `meta_value` LIKE %s ", "_wp_attachment_metadata", "%wp_smushit%" );
			$results = $wpdb->get_results( $q );

			if ( count( $results ) < 1 ) {
				return;
			}

			$migrator = new WpSmushMigrate();
			foreach ( $results as $attachment_meta ) {
				$migrated_message = $migrator->migrate_api_message( maybe_unserialize( $attachment_meta->meta_value ) );
				if ( $migrated_message !== array() ) {
					update_post_meta( $attachment_meta->post_id, $this->smushed_meta_key, $migrated_message );
				}
			}

			update_option( $this->migrated_version_key, $this->version );

		}

		/**
		 * @param Object $response_data
		 * @param $size_before
		 * @param $size_after
		 * @param $total_time
		 * @param $bytes_saved
		 *
		 * @return array
		 */
		function _update_stats_data( $response_data, $size_before, $size_after, $total_time, $bytes_saved ) {
			$size_before += ! empty( $response_data->before_size ) ? (int) $response_data->before_size : 0;
			$size_after += ( ! empty( $response_data->after_size ) && $response_data->after_size > 0 ) ? (int) $response_data->after_size : (int) $response_data->before_size;
			$total_time += ! empty( $response_data->time ) ? (float) $response_data->time : 0;
			$bytes_saved += ( ! empty( $response_data->bytes_saved ) && $response_data->bytes_saved > 0 ) ? $response_data->bytes_saved : 0;
			$compression = ( $bytes_saved > 0 && $size_before > 0 ) ? ( ( $bytes_saved / $size_before ) * 100 ) : 0;

			return array( $size_before, $size_after, $total_time, $compression, $bytes_saved );
		}

		/**
		 * Updates the smush stats for a single image size
		 * @param $id
		 * @param $stats
		 * @param $image_size
		 */
		function update_smush_stats_single( $id, $smush_stats, $image_size = '' ) {
			//Return, if we don't have image id or stats for it
			if ( empty( $id ) || empty( $smush_stats ) || empty( $image_size ) ) {
				return false;
			}
			$image_size = $image_size . '@2x';
			$data       = $smush_stats['data'];
			//Get existing Stats
			$stats = get_post_meta( $id, $this->smushed_meta_key, true );
			//Update existing Stats
			if ( ! empty( $stats ) ) {
				//Update total bytes saved, and compression percent
				//Update Main Stats
				list( $stats['stats']['size_before'], $stats['stats']['size_after'], $stats['stats']['time'], $stats['stats']['percent'], $stats['stats']['bytes'] ) =
						$this->_update_stats_data( $data, $stats['stats']['size_before'], $stats['stats']['size_after'], $stats['stats']['time'], $stats['stats']['bytes'] );


				//Update stats for each size
				if ( isset( $stats['sizes'] ) ) {

					//if stats for a particular size doesn't exists
					if ( empty( $stats['sizes'][ $image_size ] ) ) {
						//Update size wise details
						$stats['sizes'][ $image_size ] = (object) $this->_array_fill_placeholders( $this->_get_size_signature(), (array) $data );
					} else {
						//Update compression percent and bytes saved for each size
						$stats['sizes'][ $image_size ]->bytes   = $stats['sizes'][ $image_size ]->bytes + $data->bytes_saved;
						$stats['sizes'][ $image_size ]->percent = $stats['sizes'][ $image_size ]->percent + $data->compression;
					}
				}
			} else {
				//Create new stats
				$stats                         = array(
						"stats" => array_merge( $this->_get_size_signature(), array(
										'api_version' => - 1,
										'lossy'       => - 1
								)
						),
						'sizes' => array()
				);
				$stats['stats']['api_version'] = $data->api_version;
				$stats['stats']['lossy']       = $data->lossy;
				//Update Main Stats
				list( $stats['stats']['size_before'], $stats['stats']['size_after'], $stats['stats']['time'], $stats['stats']['percent'], $stats['stats']['bytes'] ) =
						array( $data->before_size, $data->after_size, $data->time, $data->compression, $data->bytes_saved );
				//Update size wise details
				$stats['sizes'][ $image_size ] = (object) $this->_array_fill_placeholders( $this->_get_size_signature(), (array) $data );
			}
			//Calculate Percent
			update_post_meta( $id, $this->smushed_meta_key, $stats );

		}

		/**
		 * Smush Retina images for WP Retina 2x, Update Stats
		 *
		 * @param $id
		 * @param $retina_file
		 * @param $image_size
		 */
		function smush_retina_image( $id, $retina_file, $image_size ) {

			/**
			 * Allows to Enable/Disable WP Retina 2x Integration
			 */
			$smush_retina_images = apply_filters( 'smush_retina_images', true );

			//Check if Smush retina images is enbled
			if ( ! $smush_retina_images ) {
				return;
			}
			//Check for Empty fields
			if ( empty( $id ) || empty( $retina_file ) || empty( $image_size ) ) {
				return;
			}

			/**
			 * Allows to skip a image from smushing
			 *
			 * @param bool , Smush image or not
			 * @$size string, Size of image being smushed
			 */
			$smush_image = apply_filters( 'wp_smush_media_image', true, $image_size );
			if ( ! $smush_image ) {
				return;
			}

			$stats = $this->do_smushit( $retina_file );
			//If we squeezed out something, Update stats
			if ( !is_wp_error( $stats ) && ! empty( $stats['data'] ) && isset( $stats['data'] ) && $stats['data']->bytes_saved > 0 ) {
				$this->update_smush_stats_single( $id, $stats, $image_size );
			}
		}

		/**
		 * Return a list of images not smushed and reason
		 * @param $image_id
		 * @param $size_stats
		 * @param $attachment_metadata
		 *
		 * @return array
		 */
		function get_skipped_images( $image_id, $size_stats, $attachment_metadata ) {
			$skipped = array();

			//Get a list of all the sizes, Show skipped images
			$media_size = get_intermediate_image_sizes();

			//Full size
			$full_image = get_attached_file( $image_id );

			//If full image was not smushed, reason 1. Large Size logic, 2. Free and greater than 1Mb
			if ( ! array_key_exists( 'full', $size_stats ) ) {
				//For free version, Check the image size
				if ( ! $this->is_pro() ) {
					//For free version, check if full size is greater than 1 Mb, show the skipped status
					$file_size = file_exists( $full_image ) ? filesize( $full_image ) : '';
					if ( !empty( $file_size ) && ( $file_size / WP_SMUSH_MAX_BYTES ) > 1 ) {
						$skipped[] = array(
								'size'   => 'full',
								'reason' => 'size_limit'
						);
					}else{
						$skipped[] = array(
								'size'   => 'full',
								'reason' => 'large_size'
						);
					}
				} else {
					//Paid version, Check if we have large size
					if ( array_key_exists( 'large', $size_stats ) ) {
						$skipped[] = array(
								'size'   => 'full',
								'reason' => 'large_size'
						);
					}

				}
			}
			//For other sizes, check if the image was generated and not available in stats
			if ( is_array( $media_size ) ) {
				$dir_path = trailingslashit( dirname( $full_image ) );
				foreach ( $media_size as $size ) {
					if ( array_key_exists( $size, $attachment_metadata['sizes'] ) && ! array_key_exists( $size, $size_stats ) && ! empty( $size['file'] ) ) {
						//Image Path
						$img_path   =  $dir_path . $size['file'];
						$image_size = file_exists( $img_path ) ? filesize( $img_path ) : '';
						if ( ! empty( $image_size ) && ( $image_size / WP_SMUSH_MAX_BYTES ) > 1 ) {
							$skipped[] = array(
									'size'   => 'full',
									'reason' => 'size_limit'
							);
						}
					}
				}
			}
			return $skipped;
		}

		/**
		 * Skip messages respective to their ids
		 * @param $msg_id
		 *
		 * @return bool
		 */
		function skip_reason( $msg_id ) {
			$count = count( get_intermediate_image_sizes() );
			$smush_orgnl_txt = sprintf( esc_html__("When you upload an image to WordPress it automatically creates %s thumbnail sizes that are commonly used in your pages. WordPress also stores the original full-size image, but because these are not usually embedded on your site we don’t Smush them. Pro users can override this.", 'wp_smushit'), $count );
			$skip_msg = array(
					'large_size' => $smush_orgnl_txt,
					'size_limit' => esc_html__( "Image couldn't be smushed as it exceeded the 1Mb size limit, Pro users can smush images with size upto 32Mb.", "wp-smushit" )
			);
			$skip_rsn = !empty( $skip_msg[$msg_id ] ) ? esc_html__(" Skipped", 'wp-smushit', 'wp-smushit'): '';
			$skip_rsn = ! empty( $skip_rsn ) ? $skip_rsn . '<span class="dashicons dashicons-editor-help" title="' . $skip_msg[ $msg_id ] . '"></span>' : '';
			return $skip_rsn;
		}

		/**
		 * Shows the image size and the compression for each of them
		 * @param $image_id
		 * @param $wp_smush_data
		 *
		 * @return string
		 */
		function get_detailed_stats( $image_id, $wp_smush_data, $attachment_metadata ) {

			$stats      = '<div id="smush-stats-' . $image_id . '" class="smush-stats-wrapper hidden">
				<table class="wp-smush-stats-holder">
					<thead>
						<tr>
							<th><strong>' . esc_html__( 'Image size', 'wp-smushit' ) . '</strong></th>
							<th><strong>' . esc_html__( 'Savings', 'wp-smushit' ) . '</strong></th>
						</tr>
					</thead>
					<tbody>';
			$size_stats = $wp_smush_data['sizes'];

			//Reorder Sizes as per the maximum savings
			uasort( $size_stats, array( $this, "cmp" ) );

			if ( ! empty( $attachment_metadata['sizes'] ) ) {
				//Get skipped images
				$skipped = $this->get_skipped_images( $image_id, $size_stats, $attachment_metadata );

				if ( ! empty( $skipped ) ) {
					foreach ( $skipped as $img_data ) {
						$skip_class = $img_data['reason'] == 'size_limit' ? ' error' : '';
						$stats .= '<tr>
					<td>' . strtoupper( $img_data['size'] ) . '</td>
					<td class="smush-skipped' . $skip_class . '">' . $this->skip_reason( $img_data['reason'] ) . '</td>
				</tr>';
					}

				}
			}
			//Show Sizes and their compression
			foreach ( $size_stats as $size_key => $size_value ) {
				if ( $size_value->bytes > 0 ) {
					$stats .= '<tr>
					<td>' . strtoupper( $size_key ) . '</td>
					<td>' . $this->format_bytes( $size_value->bytes ) . ' ( ' . $size_value->percent . '% )</td>
				</tr>';
				}
			}
			$stats .= '</tbody>
				</table>
			</div>';

			return $stats;
		}

		/**
		 * Compare Values
		 *
		 * @param $a
		 * @param $b
		 *
		 * @return int
		 */
		function cmp( $a, $b ) {
			return $a->bytes < $b->bytes;
		}

		/**
		 * Check if NextGen is active or not
		 * Include and instantiate classes
		 */
		function load_nextgen() {
			if ( ! class_exists( 'C_NextGEN_Bootstrap' ) || ! $this->is_pro() ) {
				return;
			}
			//Check if integration is Enabled or not
			//Smush NextGen key
			$opt_nextgen     = WP_SMUSH_PREFIX . 'nextgen';
			$opt_nextgen_val = get_option( $opt_nextgen, 1 );
			if ( ! $opt_nextgen_val ) {
				return;
			}

			require_once( WP_SMUSH_DIR . '/lib/class-wp-smush-nextgen.php' );
			require_once( WP_SMUSH_DIR . '/lib/nextgen-integration/class-wp-smush-nextgen-admin.php' );
			require_once( WP_SMUSH_DIR . '/lib/nextgen-integration/class-wp-smush-nextgen-stats.php' );
			require_once( WP_SMUSH_DIR . '/lib/nextgen-integration/class-wp-smush-nextgen-bulk.php' );

			global $wpsmushnextgen, $wpsmushnextgenadmin, $wpsmushnextgenstats;
			//Initialize Nextgen support
			$wpsmushnextgen      = new WpSmushNextGen();
			$wpsmushnextgenadmin = new WpSmushNextGenAdmin();
			$wpsmushnextgenstats = new WpSmushNextGenStats();
			new WPSmushNextGenBulk();
		}

		/**
		 * Add the Smushit Column to sortable list
		 * @param $columns
		 *
		 * @return mixed
		 */
		function sortable_column( $columns ) {
			$columns['smushit'] = 'smushit';
			return $columns;
		}
		/**
		 * Orderby query for smush columns
		 */
		function smushit_orderby( $query ) {

			global $current_screen, $wpdb;

			//Filter only media screen
			if ( ! is_admin() || ( !empty( $current_screen) && $current_screen->base != 'upload' ) ) {
				return;
			}

			$orderby = $query->get( 'orderby' );

			if ( isset( $orderby ) && 'smushit' == $orderby ) {
				$query->set( 'meta_query', array(
						'relation' => 'OR',
						array(
								'key'     => $this->smushed_meta_key,
								'compare' => 'EXISTS'
						),
						array(
								'key'     => $this->smushed_meta_key,
								'compare' => 'NOT EXISTS'
						)
				) );
				$query->set( 'orderby', 'meta_value_num' );
			}
			return $query;

		}
	}

	global $WpSmush;
	$WpSmush = new WpSmush();

}

//Include Admin classes
require_once( WP_SMUSH_DIR . 'lib/class-wp-smush-bulk.php' );
require_once( WP_SMUSH_DIR . 'lib/class-wp-smush-admin.php' );