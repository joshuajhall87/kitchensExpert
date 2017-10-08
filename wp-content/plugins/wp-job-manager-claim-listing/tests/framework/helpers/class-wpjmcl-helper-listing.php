<?php
/**
 * For tests
 */
class WPJMCL_Helper_Listing {

	public static function delete( $listing_id ) {
		wp_delete_post( $listing_id, true );
	}

	public static function create( $args = array() ) {
		$defaults = array(
			'post_title' => 'Dummy Listing',
			'post_type' => 'job_listing',
			'post_status' => 'publish'
		);

		$args = wp_parse_args( $args, $defaults );

		$listing = wp_insert_post( $args );

		return get_post( $listing );
	}

	public static function create_featured() {
		$listing = self::create();

		update_post_meta( $listing->ID, '_featured', true );

		return $listing;
	}

	public static function create_with_package( $package_id ) {
		$listing = self::create();

		update_post_meta( $listing->ID, '_package_id', $package_id );

		return $listing;
	}

}
