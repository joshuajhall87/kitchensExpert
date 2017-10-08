<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WPJMCL_Settings.
 */
class WPJMCL_Settings {

    public static function init() {
        add_action( 'job_manager_settings', array( __CLASS__, 'settings' ) );
		add_action( 'wp_job_manager_admin_field_notice', array( __CLASS__, 'admin_field_notice' ), 10, 4 );
    }

    /**
     * Settings page.
     *
     * Add an settings tab to the Listings -> settings page.
     *
     * @since 1.0.0
     *
     * @param 	array 	$settings	Array of default settings.
     * @return 	array	$settings	Array including the new settings.
     */
    public static function settings( $settings )  {
		$settings['wpjmcl_settings'] = array(
			__( 'Claim Listing', 'wp-job-manager-claim-listing' ),
			array()
		);

        if ( defined( 'JOB_MANAGER_WCPL_VERSION' ) ) {
            $settings['wpjmcl_settings'][1][] = array(
				'name'			=> 'wpjmcl_paid_claiming',
				'type'			=> 'checkbox',
				'label'			=> __( 'Paid Claims', 'wp-job-manager-claim-listing' ),
				'cb_label'		=> __( 'Require a purchase', 'wp-job-manager-claim-listing' ),
				'desc'			=> __( 'A listing is claimed by purchasing a listing package. <a href="http://docs.astoundify.com/article/902-paid-claim">Read more</a>. Please be sure you <a href="http://docs.astoundify.com/article/909-overview">create your Claim Listing page</a>.', 'wp-job-manager-claim-listing' ),
				'std'			=> 0,
            );
		} else {
            $settings['wpjmcl_settings'][1][] = array(
				'name'			=> 'wpjmcl',
				'type'			=> 'notice',
				'label'         => 'Paid Claims',
				'desc'			=> sprintf( __( 'The <a href="%s">WP Job Manager - WC Paid Listings</a> plugin must be installed and configured to charge for a claim. For more information please see the <a href="%s">documentation</a>.', 'wp-job-manager-claim-listing' ), 'https://github.com/automattic/wp-job-manager-wc-paid-listings', 'http://docs.astoundify.com/article/902-paid-claim' ),
				'std'			=> 0,
            );

		}

        // Add setting to the 'pages' tab
        $settings['job_pages'][1][] = array(
            'name' 		=> 'job_manager_claim_listing_page_id',
            'std' 		=> '',
            'label' 	=> __( 'Claim Listing Page', 'wp-job-manager' ),
            'desc'		=> __( 'Select the page where you have placed the [claim_listing] shortcode.', 'wp-job-manager' ),
            'type'      => 'page'
        );

        return $settings;

    }

	/**
	 * Create a new notice field type to dislay a message.
	 */
	public static function admin_field_notice( $option, $attributes, $value, $placeholder ) {
		echo wp_kses_post( $option[ 'desc'] );
	}

}

WPJMCL_Settings::init();
