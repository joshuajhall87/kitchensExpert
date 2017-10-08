<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WPJMCL_Claims.
 */
class WPJMCL_Claims {

    public function __construct() {
        $this->statuses = apply_filters( 'wpjmcl_claim_statuses', array(
            'approved' => __( 'Approved', 'wp-job-manager-claim-listing' ),
            'pending' => __( 'Pending', 'wp-job-manager-claim-listing' ),
            'declined' => __( 'Declined', 'wp-job-manager-claim-listing' ),
        ) );

        // Init so their respective actions are called
        $this->free = new WPJMCL_Claim_Free();
        $this->paid = new WPJMCL_Claim_Paid();
    }

}
