<?php

/**
 * @package WP SmushIt
 * @subpackage Admin
 * @version 1.0
 *
 * @author Saurabh Shukla <saurabh@incsub.com>
 * @author Umesh Kumar <umesh@incsub.com>
 *
 * @copyright (c) 2015, Incsub (http://incsub.com)
 */
if ( ! class_exists( 'WpSmushitBulk' ) ) {

	/**
	 * Methods for bulk processing
	 */
	class WpSmushitBulk {

		/**
		 * Fetch all the unsmushed attachments
		 * @return array $attachments
		 */
		function get_attachments() {
			if ( ! isset( $_REQUEST['ids'] ) ) {
				$args            = array(
					'fields'         => 'ids',
					'post_type'      => 'attachment',
					'post_status'    => 'any',
					'post_mime_type' => array( 'image/jpeg', 'image/gif', 'image/png' ),
					'orderby'        => 'ID',
					'order'          => 'DESC',
					'posts_per_page' => - 1,
					'meta_query'     => array(
						array(
							'key'     => 'wp-smpro-smush-data',
							'compare' => 'NOT EXISTS'
						)
					),
					'update_post_term_cache' => false,
					'no_found_rows'  => true
				);
				$query           = new WP_Query( $args );
				$unsmushed_posts = $query->posts;
			} else {
				return explode( ',', $_REQUEST['ids'] );
			}

			return $unsmushed_posts;
		}

		/**
		 * Fetches the ids of unsmushed images for NextGen Gallery
		 * @return array
		 */
		function get_nextgen_attachments() {
			if ( ! isset( $_REQUEST['ids'] ) ) {
				$args            = array(
					'fields'         => 'ids',
					'post_type'      => 'attachment',
					'post_status'    => 'any',
					'post_mime_type' => array( 'image/jpeg', 'image/gif', 'image/png' ),
					'orderby'        => 'ID',
					'order'          => 'DESC',
					'posts_per_page' => - 1,
					'meta_query'     => array(
						array(
							'key'     => 'wp-smpro-smush-data',
							'compare' => 'NOT EXISTS'
						)
					),
					'no_found_rows'  => true
				);
				$query           = new WP_Query( $args );
				$unsmushed_posts = $query->posts;
			} else {
				return explode( ',', $_REQUEST['ids'] );
			}

			return $unsmushed_posts;
		}

	}
}
