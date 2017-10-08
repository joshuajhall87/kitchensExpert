<?php

namespace WPJMCL\Tests\Util;

class Main_Class extends \WP_UnitTestCase {

	/** @var \WP_Job_Manager_Claim_Listing instance */
	protected $wpjmcl;

	public function setUp() {
		$this->wpjmcl = wpjmcl();

		parent::setUp();
	}

	public function test_wpjmcl_instance() {
		$this->assertClassHasStaticAttribute( 'instance', 'WP_Job_Manager_Claim_Listing' );
	}

	public function test_wpjmcl_class_instances() {
		$this->assertInstanceOf( 'WPJMCL_Claims', $this->wpjmcl->claims );
		$this->assertInstanceOf( 'WPJMCL_Listing', $this->wpjmcl->listing );
		$this->assertInstanceOf( 'WPJMCL_Packages', $this->wpjmcl->packages );
		$this->assertInstanceOf( 'WPJMCL_Orders', $this->wpjmcl->orders );
		$this->assertInstanceOf( 'WPJMCL_Admin', $this->wpjmcl->admin );
	}

}
