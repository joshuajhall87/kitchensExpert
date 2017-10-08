<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WPJMCL_Listing_Admin.
 *
 * Admin Listing class.
 *
 * @class		WPJMCL_Listing_Admin
 * @version		1.0.0
 * @author		Jeroen Sormani
 */
class WPJMCL_Listing_Admin {

    public function __construct() {
        add_filter( 'job_manager_job_listing_data_fields', array( $this, 'add_claimed_checkbox' ) );
    }

    /**
     * Add 'claimed' checkbox.
     *
     * Add a checkbox to the listing admin area to indicate if a listing is claimed.
     *
     * @since 1.0.1
     *
     * @param 	array 	$fields List of fields.
     * @return	array			List of modified fields.
     */
    public function add_claimed_checkbox( $fields ) {
        $fields[ '_claimed' ] = array(
            'type' 			=> 'checkbox',
            'label' 		=> __( 'Claimed:', 'wp-job-manager-claim-listing' ),
            'placeholder' 	=> '',
            'description' 	=> __( 'The owner has been verified', 'wp-job-manager-claim-listing' ),
        );

        return $fields;
    }

}
