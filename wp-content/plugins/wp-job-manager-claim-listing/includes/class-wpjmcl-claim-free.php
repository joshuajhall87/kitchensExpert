<?php
/**
 * Free Claim
 *
 * Handles additional functionality needed when a claim is submitted
 * for free. Such as requiring an account. 
 *
 * @since 2.4.0
 */
class WPJMCL_Claim_Free {

    public function __construct() {
        add_action( 'template_redirect', array( $this, 'create_claim' ) );
        add_action( 'template_redirect', array( $this, 'display_guest_notice' ) );
    }

	/**
	 * Create a claim via a URL action.
	 *
	 * @since 2.4.0
	 *
	 * @return void
	 */
    public function create_claim() {
        if ( get_option( 'wpjmcl_paid_claiming' ) && defined( 'JOB_MANAGER_WCPL_VERSION' ) ) {
           return;
        }

        if ( ! isset( $_GET[ 'action' ] ) || 'claim_listing' != $_GET[ 'action' ] ) {
            return;
        }

        if ( ! isset( $_GET[ 'listing_id' ] ) ) {
            return;
        }

        $listing_id = absint( $_GET[ 'listing_id' ] );

        if ( ! is_user_logged_in() ) {
            wp_safe_redirect( esc_url_raw( add_query_arg( array( 'action' => 'claim_as_guest', 'listing_id' => $listing_id ), get_permalink( $listing_id ) ) ) );
            exit();
        } else {
			$claim = wpjmcl_create_claim( array(
				'listing_id' => $listing_id,
				'user_id' => get_current_user_id() 
			) );

            if ( $claim ) {
                // Add a notice if the theme is using WC
                if ( defined( 'WC_VERSION' ) ) {
                    wc_add_notice( __( 'Your claim has been submitted.', 'wp-job-manager-claim-listing' ) );
                }

                do_action( 'wpjmcl_free_claim_created', $claim, $listing_id );

                wp_safe_redirect( esc_url_raw( get_permalink( $listing_id ) ) );
                exit();
            }
        }
    }

	/**
	 * Display a message to guests who try to claim. We want the feature to show but
	 * no action to be able to be taken.
	 *
	 * @since 2.4.0
	 *
	 * @return void
	 */
    public function display_guest_notice() {
        if ( ! isset( $_GET[ 'action' ] ) || 'claim_as_guest' != $_GET[ 'action' ] ) {
            return;
        }

        if ( ! isset( $_GET[ 'listing_id' ] ) ) {
            return;
        }

        $listing_id = absint( $_GET[ 'listing_id' ] );

        // Add a notice if the theme is using WC
        if ( defined( 'WC_VERSION' ) ) {
            wc_add_notice( sprintf( __( 'Please <a href="%s">log in</a> to claim this listing.', 'wp-job-manager-claim-listing' ), wp_login_url( get_permalink( $listing_id ) ) ) );
        }

        do_action( 'wpjmcl_guest_claim_redirect', $listing_id );
    }

}
