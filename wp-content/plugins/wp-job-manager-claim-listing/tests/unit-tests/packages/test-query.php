<?php

namespace WPJMCL\Tests\Packages;

class Query extends \WP_UnitTestCase {

	/**
	 * Visible and claimable job_package
	 */
	public function test_query_can_find_visible_claimable_job_package() {
		// hidden: not a job package
		$hidden_package_id = self::factory()->post->create( array(
			'post_title' => 'Claim Package 1',
			'post_type' => 'product'
		) );
		$hidden_package = get_post( $hidden_package_id );

		wp_set_object_terms( $hidden_package_id, array( 'simple' ), 'product_type', false );
		update_post_meta( $hidden_package_id, '_use_for_claims', 'yes' );
		update_post_meta( $hidden_package_id, '_visibility', 'visible' );

		// visible: job package used for claims
		$visible_package_id = self::factory()->post->create( array(
			'post_title' => 'Claim Package 1',
			'post_type' => 'product'
		) );
		$visible_package = get_post( $visible_package_id );

		wp_set_object_terms( $visible_package_id, array( 'job_package' ), 'product_type', false );
		update_post_meta( $visible_package_id, '_use_for_claims', 'yes' );
		update_post_meta( $visible_package_id, '_visibility', 'visible' );

		$actual = wpjmcl()->packages->get_packages_for_claiming();

		$this->assertEquals( array( $visible_package ), $actual );
	}

	/**
	 * Visible and claimable job_package_subscription
	 */
	public function test_query_can_find_visible_claimable_job_package_subscription() {
		// hidden: not a job package
		$hidden_package_id = self::factory()->post->create( array(
			'post_title' => 'Claim Package 1',
			'post_type' => 'product'
		) );
		$hidden_package = get_post( $hidden_package_id );

		wp_set_object_terms( $hidden_package_id, array( 'simple' ), 'product_type', false );
		update_post_meta( $hidden_package_id, '_use_for_claims', 'yes' );
		update_post_meta( $hidden_package_id, '_visibility', 'visible' );

		// visible: job package used for claims
		$visible_package_id = self::factory()->post->create( array(
			'post_title' => 'Claim Package 1',
			'post_type' => 'product'
		) );
		$visible_package = get_post( $visible_package_id );

		wp_set_object_terms( $visible_package_id, array( 'job_package_subscription' ), 'product_type', false );
		update_post_meta( $visible_package_id, '_use_for_claims', 'yes' );
		update_post_meta( $visible_package_id, '_visibility', 'visible' );

		$actual = wpjmcl()->packages->get_packages_for_claiming();

		$this->assertEquals( array( $visible_package ), $actual );
	}

	/**
	 * Hidden and claimable job_package (should not be output)
	 */
	public function test_query_cannot_find_hidden_claimable_job_package() {
		// hidden: claimable and hidden
		$hidden_package_id = self::factory()->post->create( array(
			'post_title' => 'Hidden Package',
			'post_type' => 'product'
		) );
		$hidden_package = get_post( $hidden_package_id );

		wp_set_object_terms( $hidden_package_id, array( 'job_package' ), 'product_type', false );
		update_post_meta( $hidden_package_id, '_use_for_claims', 'yes' );
		update_post_meta( $hidden_package_id, '_visibility', 'hidden' );

		// visible: visible and claimable
		$visible_package_id = self::factory()->post->create( array(
			'post_title' => 'Visible Package',
			'post_type' => 'product'
		) );
		$visible_package = get_post( $visible_package_id );

		wp_set_object_terms( $visible_package_id, array( 'job_package' ), 'product_type', false );
		update_post_meta( $visible_package_id, '_use_for_claims', 'yes' );
		update_post_meta( $visible_package_id, '_visibility', 'visible' );

        $actual = wpjmcl()->packages->get_packages_for_claiming();

		$this->assertEquals( array( $visible_package ), $actual );
	}

	/**
	 * Hidden and claimable job_package_subscription (should not be output)
	 */
	public function test_query_cannot_find_hidden_claimable_job_package_subscription() {
		// hidden: claimable and hidden
		$hidden_package_id = self::factory()->post->create( array(
			'post_title' => 'Hidden Package',
			'post_type' => 'product'
		) );
		$hidden_package = get_post( $hidden_package_id );

		wp_set_object_terms( $hidden_package_id, array( 'job_package_subscription' ), 'product_type', false );
		update_post_meta( $hidden_package_id, '_use_for_claims', 'yes' );
		update_post_meta( $hidden_package_id, '_visibility', 'hidden' );

		// visible: visible and claimable
		$visible_package_id = self::factory()->post->create( array(
			'post_title' => 'Visible Package',
			'post_type' => 'product'
		) );
		$visible_package = get_post( $visible_package_id );

		wp_set_object_terms( $visible_package_id, array( 'job_package_subscription' ), 'product_type', false );
		update_post_meta( $visible_package_id, '_use_for_claims', 'yes' );
		update_post_meta( $visible_package_id, '_visibility', 'visible' );

        $actual = wpjmcl()->packages->get_packages_for_claiming();

		$this->assertEquals( array( $visible_package ), $actual );
	}

	/**
	 * Visible and not claimable job_package (should not be output)
	 */
	public function test_query_cannot_find_visible_nonclaimable_job_package() {
		// hidden: not claimable and visible
		$hidden_package_id = self::factory()->post->create( array(
			'post_title' => 'Hidden Package',
			'post_type' => 'product'
		) );
		$hidden_package = get_post( $hidden_package_id );

		wp_set_object_terms( $hidden_package_id, array( 'job_package' ), 'product_type', false );
		update_post_meta( $hidden_package_id, '_use_for_claims', 'no' );
		update_post_meta( $hidden_package_id, '_visibility', 'visible' );

		// visible: claimable and visible
		$visible_package_id = self::factory()->post->create( array(
			'post_title' => 'Visible Package',
			'post_type' => 'product'
		) );
		$visible_package = get_post( $visible_package_id );

		wp_set_object_terms( $visible_package_id, array( 'job_package' ), 'product_type', false );
		update_post_meta( $visible_package_id, '_use_for_claims', 'yes' );
		update_post_meta( $visible_package_id, '_visibility', 'visible' );

        $actual = wpjmcl()->packages->get_packages_for_claiming();

		$this->assertEquals( array( $visible_package ), $actual );
	}

	/**
	 * Visible and not claimable job_package_subscription (should not be output)
	 */
	public function test_query_cannot_find_visible_nonclaimable_job_package_subscription() {
		// hidden: not claimable and visible
		$hidden_package_id = self::factory()->post->create( array(
			'post_title' => 'Hidden Package',
			'post_type' => 'product'
		) );
		$hidden_package = get_post( $hidden_package_id );

		wp_set_object_terms( $hidden_package_id, array( 'job_package_subscription' ), 'product_type', false );
		update_post_meta( $hidden_package_id, '_use_for_claims', 'no' );
		update_post_meta( $hidden_package_id, '_visibility', 'visible' );

		// visible: claimable and visible
		$visible_package_id = self::factory()->post->create( array(
			'post_title' => 'Visible Package',
			'post_type' => 'product'
		) );
		$visible_package = get_post( $visible_package_id );

		wp_set_object_terms( $visible_package_id, array( 'job_package_subscription' ), 'product_type', false );
		update_post_meta( $visible_package_id, '_use_for_claims', 'yes' );
		update_post_meta( $visible_package_id, '_visibility', 'visible' );

        $actual = wpjmcl()->packages->get_packages_for_claiming();

		$this->assertEquals( array( $visible_package ), $actual );
	}

}
