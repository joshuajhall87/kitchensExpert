<?php

namespace WPJMCL\Tests\Claims;

class Post_Type extends \WP_UnitTestCase {

	/** @var \WP_Job_Manager_Claim_Listing instance */
	protected $wpjmcl;

	public function setUp() {
		$this->wpjmcl = wpjmcl();

		parent::setUp();
	}

	/**
	 */
	public function test_post_type_exists() {
		$this->assertTrue( post_type_exists( 'claim' ) );
	}

}
