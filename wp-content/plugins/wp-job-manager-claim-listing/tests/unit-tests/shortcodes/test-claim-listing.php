<?php

namespace WPJMCL\Tests\Shortcodes;

class Claim_Listing extends \WP_UnitTestCase {

	public function test_shortcode_with_packages() {
		$listing_id = self::factory()->post->create( array(
			'post_title' => 'Listing 1',
			'post_type' => 'job_listing'
		) );

		// visible: claimable and visible
		$visible_package_id = self::factory()->post->create( array(
			'post_title' => 'Visible Package',
			'post_type' => 'product'
		) );
		$visible_package = get_post( $visible_package_id );

		wp_set_object_terms( $visible_package_id, array( 'job_package' ), 'product_type', false );
		update_post_meta( $visible_package_id, '_use_for_claims', 'yes' );
		update_post_meta( $visible_package_id, '_visibility', 'visible' );

		$shortcode = \WPJMCL_Shortcodes::paid_claim_listing_shortcode( array(
			'listing_id' => $listing_id
		) );

		$this->assertContains( 'id="package-' . $visible_package_id . '"', $shortcode );
	}

	public function test_shortcode_without_packages() {
		$listing_id = self::factory()->post->create( array(
			'post_title' => 'Listing 1',
			'post_type' => 'job_listing'
		) );

		$shortcode = \WPJMCL_Shortcodes::paid_claim_listing_shortcode( array(
			'listing_id' => $listing_id
		) );

		$this->assertContains( 'No packages found', $shortcode );
	}

	public function test_shortcode_without_listing() {
		$shortcode = \WPJMCL_Shortcodes::paid_claim_listing_shortcode();

		$this->assertEquals( 'Please select a listing.', $shortcode );
	}

	/**
	 * Does not add any to the standard query.
	 */
	public function test_query_does_not_add_to_submission() {
		$expected_packages = \WP_Job_Manager_WCPL_Submit_Job_Form::get_packages();

		$package_id = self::factory()->post->create( array(
			'post_title' => 'Claim Package 1',
			'post_type' => 'product'
		) );
		$package = get_post( $package_id );

		wp_set_object_terms( $package_id, array( 'job_package' ), 'product_type', false );
		update_post_meta( $package_id, '_use_for_claims', 'yes' );
		update_post_meta( $package_id, '_visibility', 'visible' );

		$actual_packages = \WP_Job_Manager_WCPL_Submit_Job_Form::get_packages();

		$this->assertEquals( $expected_packages, $actual_packages );
	}

}
