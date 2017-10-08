<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WP_Job_Manager_Field_Editor_Themes_Jobify {

	/**
	 * WP_Job_Manager_Field_Editor_Themes_Jobify constructor.
	 */
	function __construct() {
		add_filter( 'job_manager_field_editor_set_featured_image', array( $this, 'featured_image' ), 10 );
	}

	/**
	 * Prevent set/save featured_image meta key as featured image
	 *
	 *
	 * @since 1.5.0
	 *
	 * @param $allow
	 *
	 * @return bool
	 */
	function featured_image( $allow ){
		return false;
	}
}