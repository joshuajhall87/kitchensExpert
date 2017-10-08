<?php
/**
 * For tests
 */
class WPJMCL_Helper_Package {

	public static function delete( $package_id ) {
		wp_delete_post( $claim_id, true );
	}

	public static function create( $args = array() ) {
		$defaults = array(
			'post_title' => 'Claim Package 1',
			'post_type' => 'product'
		);

		$args = wp_parse_args( $args, $defaults );

		$package_id = wp_insert_post( $args );
		$package = get_post( $package_id );

		return $package;
	}

	public static function create_claimable( $args = array() ) {
		$package = self::create( $args );

		wp_set_object_terms( $package->ID, 'job_listing', 'product_type' );

		update_post_meta( $package->ID, '_use_for_claims', 'yes' );
		update_post_meta( $package->ID, '_visibility', 'visible' );

		$product = wc_get_product( $package );

		return $package;
	}

	public static function create_claimable_hidden() {
		$package = self::create();

		wp_set_object_terms( $package->ID, array( 'job_listing' ), 'product_type', false );

		update_post_meta( $package->ID, '_use_for_claims', 'yes' );
		update_post_meta( $package->ID, '_visibility', 'hidden' );

		return $package;
	}

	public static function create_unclaimable() {
		$package = self::create();

		wp_set_object_terms( $package->ID, array( 'job_listing' ), 'product_type', false );
		update_post_meta( $package->ID, '_visibility', 'visible' );

		return $package;
	}

}
