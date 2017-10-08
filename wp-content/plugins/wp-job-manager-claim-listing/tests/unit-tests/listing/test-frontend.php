<?php

namespace WPJMCL\Tests\Listings;

class Frontend extends \WP_UnitTestCase {

	public function test_listing_is_claimed() {
		$listing_id = self::factory()->post->create( array(
			'post_title' => 'Listing 1',
			'post_type' => 'job_listing'
		) );

		// there currently isn't a method in the plugin for this because WooCommerce does this.
		update_post_meta( $listing_id, '_claimed', true );

		$this->assertTrue( wpjmcl()->listing->is_claimed( $listing_id ) );
	}

	public function test_listing_is_claimed_and_has_claimed_post_class() {
		$listing_id = self::factory()->post->create( array(
			'post_title' => 'Listing 1',
			'post_type' => 'job_listing'
		) );

		// there currently isn't a method in the plugin for this because WooCommerce does this.
		update_post_meta( $listing_id, '_claimed', true );

		$class = get_post_class( '', $listing_id );

		$this->assertContains( 'claimed', $class );
	}

	public function test_listing_is_not_claimed_and_has_unclaimed_post_class() {
		$listing_id = self::factory()->post->create( array(
			'post_title' => 'Listing 1',
			'post_type' => 'job_listing'
		) );

		$class = get_post_class( '', $listing_id );

		$this->assertContains( 'not-claimed', $class );
	}

	public function test_listing_claim_listing_link_output() {
		$listing_id = self::factory()->post->create( array(
			'post_title' => 'Listing 1',
			'post_type' => 'job_listing'
		) );

		$this->expectOutputRegex( '/(?=<a)|(?<=\/a>)/' );

		wpjmcl()->listing->claim_listing_link( $listing_id );
	}

}
