<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WPJMCL_WC_Paid_Listings.
 *
 * Integration class for WP Job Manager WC Paid Listings plugin.
 *
 * @class		WPJMCL_WC_Paid_Listings
 * @version		1.0.4
 * @author		Jeroen Sormani
 */
class WPJMCL_WC_Paid_Listings {

    /**
     * Construct.
     *
     * Initialize this class including hooks.
     *
     * @since 1.0.4
     */
    public static function init() {
        if ( ! defined( 'JOB_MANAGER_WCPL_VERSION' ) ) {
            return;
        }

        // Add WC Paid Listings 'claimed' checkbox
        add_action( 'woocommerce_product_options_general_product_data', array( __CLASS__, 'add_claimed_checkbox' ), 20 );

        // Save claimed checkbox
        add_action( 'woocommerce_process_product_meta_job_package', array( __CLASS__, 'save_claimed_checkbox' ) );
        add_action( 'woocommerce_process_product_meta_job_package_subscription', array( __CLASS__, 'save_claimed_checkbox' ) );

        /**
         * Paid listing checkout process
         */
        add_filter( 'product_type_options', array( __CLASS__, 'add_claimable_listing_package_checkbox' ) );
        add_action( 'woocommerce_process_product_meta_job_package', array( __CLASS__, 'save_product_type_fields' ) );
        add_action( 'woocommerce_process_product_meta_job_package_subscription', array( __CLASS__, 'save_product_type_fields' ) );
    }


    /**
     * Add claimed checkbox.
     *
     * Add a 'claimed' checkbox to the listing products. When checked
     * the created listing will be set to claimed automatically.
     *
     * @since 1.0.4
     */
    public static function add_claimed_checkbox() {
		$post = get_post();

        woocommerce_wp_checkbox( array(
            'id' 			=> '_default_to_claimed',
            'label' 		=> __( 'Claimed Listing?', 'wp-job-manager-claim-listings' ),
            'description' 	=> __( 'This listing will automatically be marked as claimed/verified upon submission.', 'wp-job-manager-claim-listings' ),
            'value' 		=> get_post_meta( $post->ID, '_default_to_claimed', true ),
            'wrapper_class'	=> 'show_if_job_package show_if_job_package_subscription',
        ) );
    }


    /**
     * Save checkbox.
     *
     * Save the 'claimed' checkbox.
     *
     * @since 1.0.4
     *
     * @param	int	$post_id	ID of the post that is being saved.
     */
    public static function save_claimed_checkbox( $post_id ) {
        $value = ! empty( $_POST['_default_to_claimed'] ) ? $_POST['_default_to_claimed'] : '';
        $value = $value == 'yes' ? 'yes' : 'no';

        update_post_meta( $post_id, '_default_to_claimed', $value );
    }

    /**
     * Add product option.
     *
     * Add a product type option to allow job_listings to also be claim
     * listing packages.
     *
     * @since 1.1.0
     *
     * @param	array	$product_type_options	List of existing product type options.
     * @return	array							List of modified product type options.
     */
    public static function add_claimable_listing_package_checkbox( $product_type_options ) {
        $product_type_options['use_for_claims'] = array(
            'id'            => '_use_for_claims',
            'wrapper_class' => 'show_if_job_package show_if_job_package_subscription',
            'label'         => __( 'Use for Claiming a Listing', 'wp-job-manager-claim-listing' ),
            'description'   => __( 'Allow this package to be a option for claiming a listing. These packages will not appear on the standard listing submission form.', 'wp-job-manager-claim-listing' ),
            'default'       => 'no'
        );

        return $product_type_options;
    }

    /**
     * Save product option.
     *
     * Save the new 'use for claims' product type option.
     *
     * @since 1.0.0
     *
     * @param	int	$post_id Post ID being saved.
     */
    public static function save_product_type_fields( $post_id ) {
        $use_for_claims = isset( $_POST['_use_for_claims'] ) ? 'yes' : 'no';

        update_post_meta( $post_id, '_use_for_claims', $use_for_claims );
    }

}

add_action( 'plugins_loaded', array( 'WPJMCL_WC_Paid_Listings', 'init' ) );
