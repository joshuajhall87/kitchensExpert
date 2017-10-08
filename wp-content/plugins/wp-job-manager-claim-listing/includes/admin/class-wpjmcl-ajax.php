<?php
/**
 * AJAX
 *
 * @since 2.5.0
 * @package WP Job Manager - Claim Listing
 * @category AJAX
 */
class WPJMCL_Admin_AJAX {

	public function __construct() {
		$this->add_ajax_events();
	}

	public function add_ajax_events() {
		add_action( 'wp_ajax_wpjmcl_json_search_listings', array( $this, 'json_search_listings' ) );
		add_action( 'wp_ajax_wpjmcl_json_search_claimable_products', array( $this, 'json_search_claimable_products' ) );
	}

	public function json_search_listings() {
		global $wpdb;

		check_ajax_referer( 'search-products', 'security' );

		$term = (string) esc_attr( stripslashes( $_GET[ 'term' ] ) );

		if ( empty( $term ) ) {
			die();
		}

		$like_term = '%' . $wpdb->esc_like( $term ) . '%';

		if ( is_numeric( $term ) ) {
			$query = $wpdb->prepare( "
				SELECT ID FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
				WHERE posts.post_status = 'publish'
				AND (
					posts.post_parent = %s
					OR posts.ID = %s
					OR posts.post_title LIKE %s
					OR (
						postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE %s
					)
				)
			", $term, $term, $term, $like_term );
		} else {
			$query = $wpdb->prepare( "
				SELECT ID FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
				WHERE posts.post_status = 'publish'
				AND (
					posts.post_title LIKE %s
					or posts.post_content LIKE %s
				)
			", $like_term, $like_term );
		}

		$query .= " AND posts.post_type IN ('job_listing')";

		$posts = array_unique( $wpdb->get_col( $query ) );
		$found_listings = array();

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$listing = get_post( $post );
				$found_listings[ $post ] = esc_attr( $listing->post_title );
			}
		}

		wp_send_json( $found_listings );
	}

	public function json_search_claimable_products() {
		global $wpdb;

		check_ajax_referer( 'search-products', 'security' );

		$term = (string) esc_attr( stripslashes( $_GET[ 'term' ] ) );

		if ( empty( $term ) ) {
			die();
		}

		$like_term = '%' . $wpdb->esc_like( $term ) . '%';

		if ( is_numeric( $term ) ) {
			$query = $wpdb->prepare( "
				SELECT ID FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
				WHERE posts.post_status = 'publish'
				AND postmeta.meta_key = '_use_for_claims' AND postmeta.meta_value = 'yes'
				AND (
					posts.post_parent = %s
					OR posts.ID = %s
					OR posts.post_title LIKE %s
					OR (
						postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE %s
					)
				)
			", $term, $term, $term, $like_term );
		} else {
			$query = $wpdb->prepare( "
				SELECT ID FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
				WHERE posts.post_status = 'publish'
				AND postmeta.meta_key = '_use_for_claims' AND postmeta.meta_value = 'yes'
				AND (
					posts.post_title LIKE %s
					or posts.post_content LIKE %s
				)
			", $like_term, $like_term );
		}

		$query .= " AND posts.post_type IN ('product')";

		$posts = array_unique( $wpdb->get_col( $query ) );
		$found_products = array();

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $post ) {
				$package = wc_get_product( $post );

				if ( ! $package->is_type( 'job_package' ) && ! $package->is_type( 'resume_package' ) && ! $package->is_type( 'job_package_subscription' ) && ! $package->is_type( 'resume_package_subscription' ) ) {
					continue;
				}

				$found_products[ $post ] = esc_attr( $package->get_title() );
			}
		}

		wp_send_json( $found_products );
	}
}
