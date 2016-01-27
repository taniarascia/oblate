<?php
/////////////////////////////////////////////////////////////////////////
/* -------- WPMU DEV Dashboard Notice - Aaron Edwards (Incsub) ------- */
if ( !class_exists('WPMUDEV_Dashboard_Notice3') ) {
	class WPMUDEV_Dashboard_Notice3 {
		
		var $version = '3.1';
		var $screen_id = false;
		var $product_name = false;
		var $product_update = false;
		var $theme_pack = 128;
		var $server_url = 'http://premium.wpmudev.org/wdp-un.php';
		var $update_count = 0;
		
		function __construct() {
			add_action( 'init', array( &$this, 'init' ) );
		}
		
		function init() {
			global $wpmudev_un;
			
			if ( class_exists( 'WPMUDEV_Dashboard' ) || ( isset($wpmudev_un->version) && version_compare($wpmudev_un->version, '3.4', '<') ) )
				return;
			
			// Schedule update cron on main site only
			if ( is_main_site() ) {
				if ( ! wp_next_scheduled( 'wpmudev_scheduled_jobs' ) ) {
					wp_schedule_event( time(), 'twicedaily', 'wpmudev_scheduled_jobs' );
				}

				add_action( 'wpmudev_scheduled_jobs', array( $this, 'updates_check') );
			}
			add_action( 'delete_site_transient_update_plugins', array( &$this, 'updates_check' ) ); //refresh after upgrade/install
			add_action( 'delete_site_transient_update_themes', array( &$this, 'updates_check' ) ); //refresh after upgrade/install
			
			if ( is_admin() && current_user_can( 'install_plugins' ) ) {
				
				add_action( 'site_transient_update_plugins', array( &$this, 'filter_plugin_count' ) );
				add_action( 'site_transient_update_themes', array( &$this, 'filter_theme_count' ) );
				add_filter( 'plugins_api', array( &$this, 'filter_plugin_info' ), 101, 3 ); //run later to work with bad autoupdate plugins
				add_filter( 'themes_api', array( &$this, 'filter_plugin_info' ), 101, 3 ); //run later to work with bad autoupdate plugins
				add_action( 'admin_init', array( &$this, 'filter_plugin_rows' ), 15 ); //make sure it runs after WP's
				add_action( 'core_upgrade_preamble', array( &$this, 'disable_checkboxes' ) );
				add_action( 'activated_plugin', array( &$this, 'set_activate_flag' ) );
				
				//remove version 1.0
				remove_action( 'admin_notices', 'wdp_un_check', 5 );
				remove_action( 'network_admin_notices', 'wdp_un_check', 5 );
				//remove version 2.0, a bit nasty but only way
				remove_all_actions( 'all_admin_notices', 5 );
				
				//if dashboard is installed but not activated
				if ( file_exists(WP_PLUGIN_DIR . '/wpmudev-updates/update-notifications.php') ) {
					if ( !get_site_option('wdp_un_autoactivated') ) {
						//include plugin API if necessary
						if ( !function_exists('activate_plugin') ) require_once(ABSPATH . 'wp-admin/includes/plugin.php');
						$result = activate_plugin( '/wpmudev-updates/update-notifications.php', network_admin_url('admin.php?page=wpmudev'), is_multisite() );
						if ( !is_wp_error($result) ) { //if autoactivate successful don't show notices
							update_site_option('wdp_un_autoactivated', 1);
							return;
						}
					}
					
					add_action( 'admin_print_styles', array( &$this, 'notice_styles' ) );
					add_action( 'all_admin_notices', array( &$this, 'activate_notice' ), 5 );
				} else { //dashboard not installed at all
					if ( get_site_option('wdp_un_autoactivated') ) {
						update_site_option('wdp_un_autoactivated', 0);//reset flag when dashboard is deleted
					}
					add_action( 'admin_print_styles', array( &$this, 'notice_styles' ) );
					add_action( 'all_admin_notices', array( &$this, 'install_notice' ), 5 );
				}
			}
		}
		
		function is_allowed_screen() {
			global $wpmudev_notices;
			$screen = get_current_screen();
			$this->screen_id = $screen->id;
			
			//Show special message right after plugin activation
			if ( in_array( $this->screen_id, array('plugins', 'plugins-network') ) && ( isset($_GET['activate']) || isset($_GET['activate-multi']) ) ) {	
				$activated = get_site_option('wdp_un_activated_flag');
				if ($activated === false) $activated = 1; //on first encounter of new installed notice show
				if ($activated) {
					if ($activated >= 2)
						update_site_option('wdp_un_activated_flag', 0);
					else
						update_site_option('wdp_un_activated_flag', 2);
					return true;
				}
			}
			
			//always show on certain core pages if updates are available
			$updates = get_site_option('wdp_un_updates_available');
			if (is_array($updates) && count($updates)) {
				$this->update_count = count($updates);
				if ( in_array( $this->screen_id, array('plugins', 'update-core', /*'plugin-install', 'theme-install',*/ 'plugins-network', 'themes-network', /*'theme-install-network', 'plugin-install-network',*/ 'update-core-network') ) )
					return true;
			}
			
			//check our registered plugins for hooks
			if ( isset($wpmudev_notices) && is_array($wpmudev_notices) ) {
				foreach ( $wpmudev_notices as $product ) {
					if ( isset($product['screens']) && is_array($product['screens']) && in_array( $this->screen_id, $product['screens'] ) ) {
						$this->product_name = $product['name'];
						//if this plugin needs updating flag it
						if ( isset($product['id']) && isset($updates[$product['id']]) )
							$this->product_update = true;
						return true;
					}
				}
			}
			
			if ( defined('WPMUDEV_SCREEN_ID') ) var_dump($this->screen_id); //for internal debugging
			
			return false;
		}
		
		function auto_install_url() {
			$function = is_multisite() ? 'network_admin_url' : 'admin_url';
			return wp_nonce_url($function("update.php?action=install-plugin&plugin=install_wpmudev_dash"), "install-plugin_install_wpmudev_dash");
		}
		
		function activate_url() {
			$function = is_multisite() ? 'network_admin_url' : 'admin_url';
			return wp_nonce_url($function('plugins.php?action=activate&plugin=wpmudev-updates%2Fupdate-notifications.php'), 'activate-plugin_wpmudev-updates/update-notifications.php');
		}
		
		function install_notice() {
			if ( !$this->is_allowed_screen() ) return;

			echo '<div class="updated" id="wpmu-install-dashboard"><div class="wpmu-install-wrap"><p class="wpmu-message">';
			
			if ($this->product_name) {
				if ($this->product_update)
					echo 'Important updates are available for <strong>' . esc_html($this->product_name) . '</strong>. Install the free WPMU DEV Dashboard plugin now for updates and support!';
				else
					echo '<strong>' . esc_html($this->product_name) . '</strong> is almost ready - install the free WPMU DEV Dashboard plugin for updates and support!';
			} else if ($this->update_count) {
				echo "Important updates are available for your WPMU DEV plugins/themes. Install the free WPMU DEV Dashboard plugin now for updates and support!";
			} else {
				echo 'Almost ready - install the free WPMU DEV Dashboard plugin for updates and support!';
			}
			echo '</p><a class="wpmu-button" href="' . $this->auto_install_url() . '">Install WPMU DEV Dashboard</a>';
			echo '</div>';
			echo '<div class="wpmu-more-wrap"><a href="http://premium.wpmudev.org/update-notifications-plugin-information/" class="wpmu-more-info">More Info&raquo;</a></div>';
			echo '</div>';
		}
		
		function activate_notice() {
			if ( !$this->is_allowed_screen() ) return;
			
			echo '<div class="updated" id="wpmu-install-dashboard"><div class="wpmu-install-wrap"><p class="wpmu-message">';
			if ($this->product_name) {
				if ($this->product_update)
					echo 'Important updates are available for <strong>' . esc_html($this->product_name) . '</strong>. Activate the WPMU DEV Dashboard to update now!';
				else
					echo "Just one more step to enable updates and support for <strong>" . esc_html($this->product_name) . '</strong>!';
			} else if ($this->update_count) {
				echo "Important updates are available for your WPMU DEV plugins/themes. Activate the WPMU DEV Dashboard to update now!";
			} else {
				echo "Just one more step - activate the WPMU DEV Dashboard plugin and you're all done!";
			}
			echo '</p><a class="wpmu-button" href="' . $this->activate_url() . '">Activate WPMU DEV Dashboard</a></div></div>';
		}
		
		function notice_styles() {
			if ( !$this->is_allowed_screen() ) return;
			?>
<!-- WPMU DEV Dashboard notice -->
<style type="text/css" media="all">
#wpmu-install-dashboard{background-color:#031f34;font-family:sans-serif;display:block;border-radius:5px;position:relative;border:none;padding:14px 0.6em;font-size:1.3em;line-height:1.4em}
#wpmu-install-dashboard .wpmu-button{width:auto;position:absolute;top:0;bottom:0;right:0;padding:10px;margin:auto 14px auto 10px;line-height:1em;max-height:1em;background-color:#06385e;color:#3bb2df;text-decoration:none;border:5px solid rgba(53,131,191,0.4);vertical-align:middle;text-shadow:0 1px 0 rgba(0,0,0,0.6)}
#wpmu-install-dashboard .wpmu-button:hover{color:#e8da42;border-color:rgba(53,131,191,0.9);-webkit-transition:0.2s}
#wpmu-install-dashboard .wpmu-message{color:#c0d7eb;margin:0 280px 0 15px;padding:12px 0;text-shadow:0 1px 0 rgba(0,0,0,1);font-size:1em}
#wpmu-install-dashboard .wpmu-button,#wpmu-install-dashboard .wpmu-button:hover{-webkit-transition:0.2s}
#wpmu-install-dashboard .wpmu-btn-wrap{min-width:10%;background-color:#fff}
#wpmu-install-dashboard .wpmu-install-wrap{max-width:1050px;margin:0 auto;position:relative}#wpmu-install-dashboard .wpmu-more-wrap{max-width:1050px;margin:0 auto -9px;padding:5px 0 0;text-align:right}
#wpmu-install-dashboard .wpmu-more-info{color:#e8da42;font-size:0.9em;margin:0 14px}
#wpmu-install-dashboard, .wpmu-update-row{background-image:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZAAAAGQCAYAAACAvzbMAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpCMDBDRENDMjJEOUUxMUUzQjQ0MThGOUZCNkQzMTIwNCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpCMDBDRENDMzJEOUUxMUUzQjQ0MThGOUZCNkQzMTIwNCI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkIwMENEQ0MwMkQ5RTExRTNCNDQxOEY5RkI2RDMxMjA0IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkIwMENEQ0MxMkQ5RTExRTNCNDQxOEY5RkI2RDMxMjA0Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+4bBa5AAAFnZJREFUeNrs3el2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);background-repeat:no-repeat;background-position:95% 64%}
#the-list .wpmu-update-row{background-color:#031f34;border:none;color:#c0d7eb;background-size:100px}
#the-list .wpmu-update-row a{color:#3bb2df}
#the-list .wpmu-update-row a:hover{color:#e8da42;-webkit-transition:0.2s}
</style>
			<?php
		}
		
		function get_id_plugin($plugin_file) {
			return get_file_data( $plugin_file, array('name' => 'Plugin Name', 'id' => 'WDP ID', 'version' => 'Version') );
		}
		
		//simple check for updates
		function updates_check() {
			global $wp_version;
			$local_projects = array();
	
			//----------------------------------------------------------------------------------//
			//plugins directory
			//----------------------------------------------------------------------------------//
			$plugins_root = WP_PLUGIN_DIR;
			if( empty($plugins_root) ) {
				$plugins_root = ABSPATH . 'wp-content/plugins';
			}
	
			$plugins_dir = @opendir($plugins_root);
			$plugin_files = array();
			if ( $plugins_dir ) {
				while (($file = readdir( $plugins_dir ) ) !== false ) {
					if ( substr($file, 0, 1) == '.' )
						continue;
					if ( is_dir( $plugins_root.'/'.$file ) ) {
						$plugins_subdir = @ opendir( $plugins_root.'/'.$file );
						if ( $plugins_subdir ) {
							while (($subfile = readdir( $plugins_subdir ) ) !== false ) {
								if ( substr($subfile, 0, 1) == '.' )
									continue;
								if ( substr($subfile, -4) == '.php' )
									$plugin_files[] = "$file/$subfile";
							}
						}
					} else {
						if ( substr($file, -4) == '.php' )
							$plugin_files[] = $file;
					}
				}
			}
			@closedir( $plugins_dir );
			@closedir( $plugins_subdir );
	
			if ( $plugins_dir && !empty($plugin_files) ) {
				foreach ( $plugin_files as $plugin_file ) {
					if ( is_readable( "$plugins_root/$plugin_file" ) ) {
	
						unset($data);
						$data = $this->get_id_plugin( "$plugins_root/$plugin_file" );
	
						if ( isset($data['id']) && !empty($data['id']) ) {
							$local_projects[$data['id']]['type'] = 'plugin';
							$local_projects[$data['id']]['version'] = $data['version'];
							$local_projects[$data['id']]['filename'] = $plugin_file;
						}
					}
				}
			}
	
			//----------------------------------------------------------------------------------//
			// mu-plugins directory
			//----------------------------------------------------------------------------------//
			$mu_plugins_root = WPMU_PLUGIN_DIR;
			if( empty($mu_plugins_root) ) {
				$mu_plugins_root = ABSPATH . 'wp-content/mu-plugins';
			}
	
			if ( is_dir($mu_plugins_root) && $mu_plugins_dir = @opendir($mu_plugins_root) ) {
				while (($file = readdir( $mu_plugins_dir ) ) !== false ) {
					if ( substr($file, -4) == '.php' ) {
						if ( is_readable( "$mu_plugins_root/$file" ) ) {
	
							unset($data);
							$data = $this->get_id_plugin( "$mu_plugins_root/$file" );
	
							if ( isset($data['id']) && !empty($data['id']) ) {
								$local_projects[$data['id']]['type'] = 'mu-plugin';
								$local_projects[$data['id']]['version'] = $data['version'];
								$local_projects[$data['id']]['filename'] = $file;
							}
						}
					}
				}
				@closedir( $mu_plugins_dir );	
			}
	
			//----------------------------------------------------------------------------------//
			// wp-content directory
			//----------------------------------------------------------------------------------//
			$content_plugins_root = WP_CONTENT_DIR;
			if( empty($content_plugins_root) ) {
				$content_plugins_root = ABSPATH . 'wp-content';
			}
	
			$content_plugins_dir = @opendir($content_plugins_root);
			$content_plugin_files = array();
			if ( $content_plugins_dir ) {
				while (($file = readdir( $content_plugins_dir ) ) !== false ) {
					if ( substr($file, 0, 1) == '.' )
						continue;
					if ( !is_dir( $content_plugins_root.'/'.$file ) ) {
						if ( substr($file, -4) == '.php' )
							$content_plugin_files[] = $file;
					}
				}
			}
			@closedir( $content_plugins_dir );
	
			if ( $content_plugins_dir && !empty($content_plugin_files) ) {
				foreach ( $content_plugin_files as $content_plugin_file ) {
					if ( is_readable( "$content_plugins_root/$content_plugin_file" ) ) {
						unset($data);
						$data = $this->get_id_plugin( "$content_plugins_root/$content_plugin_file" );
	
						if ( isset($data['id']) && !empty($data['id']) ) {
							$local_projects[$data['id']]['type'] = 'drop-in';
							$local_projects[$data['id']]['version'] = $data['version'];
							$local_projects[$data['id']]['filename'] = $content_plugin_file;
						}
					}
				}
			}
			
			//----------------------------------------------------------------------------------//
			//themes directory
			//----------------------------------------------------------------------------------//
			$themes_root = WP_CONTENT_DIR . '/themes';
			if ( empty($themes_root) ) {
				$themes_root = ABSPATH . 'wp-content/themes';
			}
	
			$themes_dir = @opendir($themes_root);
			$themes_files = array();
			$local_themes = array();
			if ( $themes_dir ) {
				while (($file = readdir( $themes_dir ) ) !== false ) {
					if ( substr($file, 0, 1) == '.' )
						continue;
					if ( is_dir( $themes_root.'/'.$file ) ) {
						$themes_subdir = @ opendir( $themes_root.'/'.$file );
						if ( $themes_subdir ) {
							while (($subfile = readdir( $themes_subdir ) ) !== false ) {
								if ( substr($subfile, 0, 1) == '.' )
									continue;
								if ( substr($subfile, -4) == '.css' )
									$themes_files[] = "$file/$subfile";
							}
						}
					} else {
						if ( substr($file, -4) == '.css' )
							$themes_files[] = $file;
					}
				}
			}
			@closedir( $themes_dir );
			@closedir( $themes_subdir );
	
			if ( $themes_dir && !empty($themes_files) ) {
				foreach ( $themes_files as $themes_file ) {
	
					//skip child themes
					if ( strpos( $themes_file, '-child' ) !== false )
						continue;
	
					if ( is_readable( "$themes_root/$themes_file" ) ) {
	
						unset($data);
						$data = $this->get_id_plugin( "$themes_root/$themes_file" );
	
						if ( isset($data['id']) && !empty($data['id']) ) {
							$local_projects[$data['id']]['type'] = 'theme';
							$local_projects[$data['id']]['filename'] = substr( $themes_file, 0, strpos( $themes_file, '/' ) );
							
							//keep record of all themes for 133 themepack
							if ($data['id'] == $this->theme_pack) {
								$local_themes[$themes_file]['id'] = $data['id'];
								$local_themes[$themes_file]['filename'] = substr( $themes_file, 0, strpos( $themes_file, '/' ) );
								$local_themes[$themes_file]['version'] = $data['version'];
								//increment 133 theme pack version to lowest in all of them
								if ( isset($local_projects[$data['id']]['version']) && version_compare($data['version'], $local_projects[$data['id']]['version'], '<') ) {
									$local_projects[$data['id']]['version'] = $data['version'];
								} else if ( !isset($local_projects[$data['id']]['version']) ) {
									$local_projects[$data['id']]['version'] = $data['version'];
								}
							} else {
								$local_projects[$data['id']]['version'] = $data['version'];
							}
						}
					}
				}
			}
			update_site_option('wdp_un_local_themes', $local_themes);
			
			update_site_option('wdp_un_local_projects', $local_projects);
			
			//now check the API
			$projects = '';
			foreach ($local_projects as $pid => $project)
				$projects .= "&p[$pid]=" . $project['version'];
			
			//get WP/BP version string to help with support
			$wp = is_multisite() ? "WordPress Multisite $wp_version" : "WordPress $wp_version";
			if ( defined( 'BP_VERSION' ) )
				$wp .= ', BuddyPress ' . BP_VERSION;
			
			//add blog count if multisite
			$blog_count = is_multisite() ? get_blog_count() : 1;
			
			$url = $this->server_url . '?action=check&un-version=3.3.3&wp=' . urlencode($wp) . '&bcount=' . $blog_count . '&domain=' . urlencode(network_site_url()) . $projects;
	
			$options = array(
				'timeout' => 15,
				'user-agent' => 'Dashboard Notification/' . $this->version
			);
	
			$response = wp_remote_get($url, $options);
			if ( wp_remote_retrieve_response_code($response) == 200 ) {
				$data = $response['body'];
				if ( $data != 'error' ) {
					$data = unserialize($data);
					if ( is_array($data) ) {
						
						//we've made it here with no errors, now check for available updates
						$remote_projects = isset($data['projects']) ? $data['projects'] : array();
						$updates = array();
				
						//check for updates
						if ( is_array($remote_projects) ) {
							foreach ( $remote_projects as $id => $remote_project ) {
								if ( isset($local_projects[$id]) && is_array($local_projects[$id]) ) {
									//match
									$local_version = $local_projects[$id]['version'];
									$remote_version = $remote_project['version'];
									
									if ( version_compare($remote_version, $local_version, '>') ) {
										//add to array
										$updates[$id] = $local_projects[$id];
										$updates[$id]['url'] = $remote_project['url'];
										$updates[$id]['instructions_url'] = $remote_project['instructions_url'];
										$updates[$id]['support_url'] = $remote_project['support_url'];
										$updates[$id]['name'] = $remote_project['name'];
										$updates[$id]['thumbnail'] = $remote_project['thumbnail'];
										$updates[$id]['version'] = $local_version;
										$updates[$id]['new_version'] = $remote_version;
										$updates[$id]['changelog'] = $remote_project['changelog'];
										$updates[$id]['autoupdate'] = $remote_project['autoupdate'];
									}
								}
							}
				
							//record results
							update_site_option('wdp_un_updates_available', $updates);
						} else {
							return false;
						}
					}
				}
			}
		}
		
		function filter_plugin_info($res, $action, $args) {
			global $wp_version;
			$cur_wp_version = preg_replace('/-.*$/', '', $wp_version);
			
			//if in details iframe on update core page short-curcuit it
			if ( ($action == 'plugin_information' || $action == 'theme_information') && strpos($args->slug, 'wpmudev_install') !== false ) {
				$string = explode('-', $args->slug);
				$id = intval($string[1]);
				$updates = get_site_option('wdp_un_updates_available');
				if ( did_action( 'install_plugins_pre_plugin-information' ) && is_array( $updates ) && isset($updates[$id]) ) {
					echo '<iframe width="100%" height="100%" border="0" style="border:none;" src="' . $this->server_url . '?action=details&id=' . $id . '"></iframe>';
					exit;
				}
				
				$res = new stdClass;
				$res->name = $updates[$id]['name'];
				$res->slug = sanitize_title($updates[$id]['name']);
				$res->version = $updates[$id]['version'];
				$res->rating = 100;
				$res->homepage = $updates[$id]['url'];
				$res->download_link = '';
				$res->tested = $cur_wp_version;
				
				return $res;
			}
			
			if ( $action == 'plugin_information' && strpos($args->slug, 'install_wpmudev_dash') !== false ) {
				$res = new stdClass;
				$res->name = 'WPMU DEV Dashboard';
				$res->slug = 'wpmu-dev-dashboard';
				$res->version = '';
				$res->rating = 100;
				$res->homepage = 'http://premium.wpmudev.org/project/wpmu-dev-dashboard/';
				$res->download_link = $this->server_url . "?action=install_wpmudev_dash";
				$res->tested = $cur_wp_version;
				
				return $res;
			}
	
			return $res;
		}
		
		function filter_plugin_rows() {
			if ( !current_user_can( 'update_plugins' ) )
				return;
			
			$updates = get_site_option('wdp_un_updates_available');
			if ( is_array($updates) && count($updates) ) {
				foreach ( $updates as $id => $plugin ) {
					if ( $plugin['autoupdate'] != '2' ) {
						if ( $plugin['type'] == 'theme' ) {
							remove_all_actions( 'after_theme_row_' . $plugin['filename'] );
							add_action('after_theme_row_' . $plugin['filename'], array( &$this, 'plugin_row'), 9, 2 );
						} else {
							remove_all_actions( 'after_plugin_row_' . $plugin['filename'] );
							add_action('after_plugin_row_' . $plugin['filename'], array( &$this, 'plugin_row'), 9, 2 );
						}
					}
				}
			}
			
			$local_themes = get_site_option('wdp_un_local_themes');
			if ( is_array($local_themes) && count($local_themes) ) {
				foreach ( $local_themes as $id => $plugin ) {
					remove_all_actions( 'after_theme_row_' . $plugin['filename'] );
					//only add the notice if specific version is wrong
					if ( isset($updates[$this->theme_pack]) && version_compare($plugin['version'], $updates[$this->theme_pack]['new_version'], '<') ) {
						add_action('after_theme_row_' . $plugin['filename'], array( &$this, 'themepack_row'), 9, 2 );
					}
				}
			}
		}
	
		function filter_plugin_count( $value ) {
			
			//remove any conflicting slug local WPMU DEV plugins from WP update notifications
			$local_projects = get_site_option('wdp_un_local_projects');
			if ( is_array($local_projects) && count($local_projects) ) {
				foreach ( $local_projects as $id => $plugin ) {
					if (isset($value->response[$plugin['filename']]))
						unset($value->response[$plugin['filename']]);
				}
			}
			
			$updates = get_site_option('wdp_un_updates_available');
			if ( is_array($updates) && count($updates) ) {
				foreach ( $updates as $id => $plugin ) {
					if ( $plugin['type'] != 'theme' && $plugin['autoupdate'] != '2' ) {
						
						//build plugin class
						$object = new stdClass;
						$object->url = $plugin['url'];
						$object->slug = "wpmudev_install-$id";
						$object->upgrade_notice = $plugin['changelog'];
						$object->new_version = $plugin['new_version'];
						$object->package = '';
							
						//add to class
						$value->response[$plugin['filename']] = $object;
					}
				}
			}
				
			return $value;
		}
	
		function filter_theme_count( $value ) {
			
			$updates = get_site_option('wdp_un_updates_available');
			if ( is_array($updates) && count($updates) ) {
				foreach ( $updates as $id => $theme ) {
					if ( $theme['type'] == 'theme' && $theme['autoupdate'] != '2' ) {
						//build theme listing
						$value->response[$theme['filename']]['url'] = $this->server_url . '?action=details&id=' . $id;
						$value->response[$theme['filename']]['new_version'] = $theme['new_version'];
						$value->response[$theme['filename']]['package'] = '';
					}
				}
			}
			
			//filter 133 theme pack themes from the list unless update is available
			$local_themes = get_site_option('wdp_un_local_themes');
			if ( is_array($local_themes) && count($local_themes) ) {
				foreach ( $local_themes as $id => $theme ) {
					//add to count only if new version exists, otherwise remove
					if (isset($updates[$theme['id']]) && isset($updates[$theme['id']]['new_version']) && version_compare($theme['version'], $updates[$theme['id']]['new_version'], '<')) {
						$value->response[$theme['filename']]['new_version'] = $updates[$theme['id']]['new_version'];
						$value->response[$theme['filename']]['package'] = '';
					} else if (isset($value) && isset($value->response) && isset($theme['filename']) && isset($value->response[$theme['filename']])) {
						unset($value->response[$theme['filename']]);
					}
				}
			}
			
			return $value;
		}
	
		function plugin_row( $file, $plugin_data ) {
	
			//get new version and update url
			$updates = get_site_option('wdp_un_updates_available');
			if ( is_array($updates) && count($updates) ) {
				foreach ( $updates as $id => $plugin ) {
					if ($plugin['filename'] == $file) {
						$project_id = $id;
						$version = $plugin['new_version'];
						$plugin_url = $plugin['url'];
						$autoupdate = $plugin['autoupdate'];
						$filename = $plugin['filename'];
						$type = $plugin['type'];
						break;
					}
				}
			} else {
				return false;
			}
	
			$plugins_allowedtags = array('a' => array('href' => array(),'title' => array()),'abbr' => array('title' => array()),'acronym' => array('title' => array()),'code' => array(),'em' => array(),'strong' => array());
			$plugin_name = wp_kses( $plugin_data['Name'], $plugins_allowedtags );
	
			$info_url = $this->server_url . '?action=details&id=' . $project_id . '&TB_iframe=true&width=640&height=800';
			if ( file_exists(WP_PLUGIN_DIR . '/wpmudev-updates/update-notifications.php') ) {
				$message = "Activate WPMU DEV Dashboard";
				$action_url = $this->activate_url();
			} else { //dashboard not installed at all
				$message = "Install WPMU DEV Dashboard";
				$action_url = $this->auto_install_url();
			}
			
			if ( current_user_can('update_plugins') ) {
				echo '<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange"><div class="update-message wpmu-update-row">';
				printf( 'There is a new version of %1$s available on WPMU DEV. <a href="%2$s" class="thickbox" title="%3$s">View version %4$s details</a> or <a href="%5$s">%6$s</a> to update.', $plugin_name, esc_url($info_url), esc_attr($plugin_name), $version, esc_url($action_url), $message );
				echo '</div></td></tr>';
			}
		}
		
		function themepack_row( $file, $plugin_data ) {
	
			//get new version and update url
			$updates = get_site_option('wdp_un_updates_available');
			if ( isset($updates[$this->theme_pack]) ) {
				$plugin = $updates[$this->theme_pack];
				$project_id = $this->theme_pack;
				$version = $plugin['new_version'];
				$plugin_url = $plugin['url'];
			} else {
				return false;
			}
	
			$plugins_allowedtags = array('a' => array('href' => array(),'title' => array()),'abbr' => array('title' => array()),'acronym' => array('title' => array()),'code' => array(),'em' => array(),'strong' => array());
			$plugin_name = wp_kses( $plugin_data['Name'], $plugins_allowedtags );
	
			$info_url = $this->server_url . '?action=details&id=' . $project_id . '&TB_iframe=true&width=640&height=800';
			if ( file_exists(WP_PLUGIN_DIR . '/wpmudev-updates/update-notifications.php') ) {
				$message = "Activate WPMU DEV Dashboard";
				$action_url = $this->activate_url();
			} else { //dashboard not installed at all
				$message = "Install WPMU DEV Dashboard";
				$action_url = $this->auto_install_url();
			}
			
			if ( current_user_can('update_themes') ) {
				echo '<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange"><div class="update-message">';
				printf( 'There is a new version of %1$s available on WPMU DEV. <a href="%2$s" class="thickbox" title="%3$s">View version %4$s details</a> or <a href="%5$s">%6$s</a> to update.', $plugin_name, esc_url($info_url), esc_attr($plugin_name), $version, esc_url($action_url), $message );
				echo '</div></td></tr>';
			}
		}
		
		function disable_checkboxes() {
	
			$updates = get_site_option('wdp_un_updates_available');
			if ( !is_array( $updates ) || ( is_array( $updates ) && !count( $updates ) ) ) {
				return;
			}
		
			$jquery = '';
			foreach ( (array) $updates as $id => $plugin) {
				$jquery .= "<script type='text/javascript'>jQuery(\"input:checkbox[value='".esc_attr($plugin['filename'])."']\").remove();</script>\n";
			}
	
			//disable checkboxes for 133 theme pack themes
			$local_themes = get_site_option('wdp_un_local_themes');
			if ( is_array($local_themes) && count($local_themes) ) {
				foreach ( $local_themes as $id => $theme ) {
					$jquery .= "<script type='text/javascript'>jQuery(\"input:checkbox[value='".esc_attr($theme['filename'])."']\").remove();</script>\n";
				}
			}
			echo $jquery;
		}
		
		function set_activate_flag($plugin) {
			$data = $this->get_id_plugin( WP_PLUGIN_DIR . '/' . $plugin );
			if ( isset($data['id']) && !empty($data['id']) ) {
				update_site_option('wdp_un_activated_flag', 1);
			}
		}
		
	}
	$WPMUDEV_Dashboard_Notice3 = new WPMUDEV_Dashboard_Notice3();
}

//disable older version
if ( !class_exists('WPMUDEV_Dashboard_Notice') ) {
	class WPMUDEV_Dashboard_Notice {}
}