<?php

namespace WPJMCL\Tests\Claim;

class Free_Claim extends \WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function test_create_free_claim_when_guest() {
		$listing = \WPJMCL_Helper_Listing::create();
		$href = wpjmcl()->listing->claim_listing_url( $listing );

		self::go_to( get_permalink( $href ) );

		$this->assertEquals( 0, count( wpjmcl_get_claims() ) );
	}

	public function test_create_free_claim_when_user() {
		$listing = \WPJMCL_Helper_Listing::create();

		$user_id = self::factory()->user->create( array(
			'role' => 'employer'
		) );

		wp_set_current_user( $user_id );

		$href = wpjmcl()->listing->claim_listing_url( $listing );

		self::go_to( home_url( $href ) );

		// this needs to be made real
		$this->assertEquals( 0, count( wpjmcl_get_claims() ) );
	}

}
