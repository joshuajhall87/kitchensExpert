<?php

namespace WPJMCL\Tests\Claim;

class Claim extends \WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function test_claim_create() {
		$listing = \WPJMCL_Helper_Listing::create();
		$claim   = \WPJMCL_Helper_Claim::create( array(
			'listing_id' => $listing->ID
		) );

		$this->assertNotFalse( $claim->ID );
	}

	/**
	 * Post status should always be `publish` with a meta status of pending
	 */
	public function test_claim_create_status() {
		$listing = \WPJMCL_Helper_Listing::create();
		$claim   = \WPJMCL_Helper_Claim::create( array(
			'listing_id' => $listing->ID
		) );

		$this->assertEquals( 'publish', $claim->data->post_status );
		$this->assertEquals( 'pending', $claim->get_status() );
	}

	/**
	 * Free orders are created without an associated order, so the current user is assumed the claimer
	 */
	public function test_user_id_when_create_claim_with_no_order() {
		$listing = \WPJMCL_Helper_Listing::create();

		$user_id = self::factory()->user->create( array(
			'role' => 'employer'
		) );

		$claim   = \WPJMCL_Helper_Claim::create( array(
			'listing_id' => $listing->ID,
			'user_id' => $user_id
		) );

		$this->assertEquals( $user_id, $claim->get_user_id() );
	}

	/**
	 * The claim author should be set to the order customer account ID, and the order associated with
	 * the listing.
	 */
	public function test_user_id_when_create_claim_with_order() {
		$listing = \WPJMCL_Helper_Listing::create();
		$order   = \WPJMCL_Helper_Order::create();
		$claim   = \WPJMCL_Helper_Claim::create( array(
			'listing_id' => $listing->ID, 
			'order_id' => $order->id, 
			'user_id' => $order->customer_user 
		) );

		// user should be order user
		$this->assertEquals( $order->customer_user, $claim->get_user_id() );

		// order id should be set
		$this->assertEquals( $order->id, $claim->get_order_id() );
	}

	/**
	 * Test updating a claim status
	 */
	public function test_update_claim_status() {
		$listing = \WPJMCL_Helper_Listing::create();
		$claim   = \WPJMCL_Helper_Claim::create( array(
			'listing_id' => $listing->ID
		) );

		$claim->update( array(
			'status' => 'declined'
		) );

		$this->assertEquals( 'declined', $claim->status );
	}

	/**
	 * Test updating a claim to an invalid status
	 */
	public function test_update_claim_status_with_invalid_status() {
		$listing = \wpjmcl_helper_listing::create();
		$claim   = \WPJMCL_Helper_Claim::create( array(
			'listing_id' => $listing->ID
		) );

		$claim = $claim->update( array(
			'status' => 'invalid'
		) );

		// claims are created with a pending status
		$this->assertEquals( 'pending', $claim->get_status() );
	}

	/**
	 * Test updating an existing claim's associated listing
	 */
	public function test_listing_id_update_when_claim_is_updated() {
		$listing = \WPJMCL_Helper_Listing::create();
		$claim   = \WPJMCL_Helper_Claim::create( array(
			'listing_id' => $listing->ID
		) );

		$new_listing = \WPJMCL_Helper_Listing::create( array(
			'post_title' => 'New Listing'
		) );

		$claim = $claim->update( array(
			'listing_id' => $new_listing->ID
		) );

		// claim has title of new listing
		$this->assertEquals( $new_listing->ID, $claim->get_listing_id() );
	}

	/**
	 * Test updating an existing claim's associated listing
	 */
	public function test_update_claim_title_when_listing_updates() {
		$listing = \WPJMCL_Helper_Listing::create();
		$claim   = \WPJMCL_Helper_Claim::create( array(
			'listing_id' => $listing->ID
		) );

		$new_listing = \WPJMCL_Helper_Listing::create( array(
			'post_title' => 'New Listing'
		) );

		$claim = $claim->update( array(
			'listing_id' => $new_listing->ID
		) );

		// claim has title of new listing
		$this->assertEquals( 'New Listing', $claim->data->post_title );
	}

	/**
	 * Test updating an existing claim's associated package
	 */
	public function test_update_claim_package() {
		$package = \WPJMCL_Helper_Package::create_claimable();
		$listing = \WPJMCL_Helper_Listing::create_with_package( $package->ID );
		$claim   = \WPJMCL_Helper_Claim::create( array(
			'package_id' => 1,
			'user_id' => 1
		) );

		$claim->update( array(
			'package_id' => $package->ID
		) );

		$listing_package_id = get_post_meta( $listing->ID, '_package_id', true );
		$user_package_id = get_post_meta( $listing->ID, '_user_package_id', true );

		$this->assertEquals( $package->ID, $listing_package_id );
		$this->assertNotNull( $user_package_id );
	}

}
