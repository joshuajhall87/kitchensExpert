<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 */
class WPJMCL_Admin_Metaboxes {

    public static function init() {
        add_action( 'add_meta_boxes', array( __CLASS__, 'data_meta_box' ) );
	}

    /**
     * Meta box.
     *
     * Add an meta box with all the claim data.
     *
     * @since 1.0.0
     */
    public static function data_meta_box() {
        add_meta_box( 'claim_data', __( 'Claim Information', 'wp-job-manager-claim-listing' ), array( __CLASS__, 'data_meta_box_contents' ), 'claim', 'normal', 'high' );
    }

    /**
     * Meta box content.
     *
     * Get contents from file and put them in the meta box.
     *
     * @since 1.0.0
     */
    public static function data_meta_box_contents() {
        $statuses = wpjmcl()->claims->statuses;
        require_once( 'views/meta-box-claims-data.php' );
    }

}

WPJMCL_Admin_Metaboxes::init();
