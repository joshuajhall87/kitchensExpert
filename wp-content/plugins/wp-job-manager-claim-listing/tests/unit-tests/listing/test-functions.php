<?php

namespace WPJMCL\Tests\Listings;

class Functions extends \WP_UnitTestCase {

	public function test_listing_is_claimed_when_claim_is_approved() {
		$listing = \WPJMCL_Helper_Listing::create( array(
			'post_title' => 'Listing 1',
			'post_author' => 2
		) );

		$claim   = \WPJMCL_Helper_Claim::create( array(
			'listing_id' => $listing->ID,
			'user_id' => $listing->post_author
		) );

		$claim = $claim->update( array(
			'status' => 'approved',
		) );

		$is_claimed = wpjmcl()->listing->is_claimed( $listing->ID );

		$this->assertTrue( $is_claimed );
	}

	public function test_listing_owner_switches_when_claim_is_approved() {
		$listing = \WPJMCL_Helper_Listing::create( array(
			'post_title' => 'Listing 1',
			'post_author' => 2
		) );

		$claim   = \WPJMCL_Helper_Claim::create( array(
			'listing_id' => $listing->ID,
			'user_id' => 1
		) );

		$claim = $claim->update( array(
			'status' => 'approved',
		) );

		$this->assertEquals( 1, $claim->get_user_id() );
	}

}
