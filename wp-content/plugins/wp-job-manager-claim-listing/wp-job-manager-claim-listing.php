<?php
/*
 * Plugin Name: WP Job Manager - Claim Listing
 * Plugin URI: https://astoundify.com/downloads/wp-job-manager-claim-listing/
 * Description: Allow listings to be "claimed" to indicate verified ownership. A fee can be charged using WooCommerce.
 * Version: 2.5.0
 * Author: Astoundify
 * Author URI: http://astoundify.com
 * Text Domain: wp-job-manager-claim-listing
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WP_Job_Manager_Claim_Listing {

    /**
     * Plugin version.
     *
     * @since 1.0.0
     * @var string $version Plugin version number.
     */
    public $version = '2.5.0';

    /**
     * Instace of WP_Job_Manager_Claim_Listing.
     *
     * @since 1.0.0
     * @access private
     * @var object $instance The instance of WPJMCL.
     */
    private static $instance;

    public $claims;
    public $listing;
    public $packages;
    public $orders;
    public $wcpl;
    public $admin;

    /**
     * Construct.
     *
     * Initialize the class and plugin.
     *
     * @since 1.0.0
     */
    public function __construct() {
        // License updater
        add_action( 'admin_init', array( $this, 'license_updater' ), 9 );

        $this->init();
    }

    /**
     * Instance.
     *
     * An global instance of the class. Used to retrieve the instance
     * to use on other files/plugins/themes.
     *
     * @since 1.0.0
     * @return object Instance of the class.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * init.
     *
     * Initialize plugin parts.
     *
     * @since 1.0.0
     */
    public function init() {
        $this->plugin_dir = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );

        $this->load_textdomain();
        $this->includes();
    }

    public function includes() {
        $files = array(
			'class-wpjmcl-install.php',
			'class-wpjmcl-post-types.php',
            'class-wpjmcl-shortcode.php',
            'class-wpjmcl-checkout.php',
            'class-wpjmcl-forms.php',
            'class-wpjmcl-claim.php',
            'class-wpjmcl-packages.php',
            'class-wpjmcl-orders.php',
            'class-wpjmcl-wc-paid-listings.php',

            'class-wpjmcl-claims.php',
            'class-wpjmcl-claim-paid.php',
            'class-wpjmcl-claim-free.php',
            'class-wpjmcl-listing.php',
			'class-wpjmcl-admin.php',

			'functions.php'
        );

        foreach ( $files as $file ) {
            require_once( $this->plugin_dir . 'includes/' . $file );
        }

        $this->claims = new WPJMCL_Claims();
        $this->listing = new WPJMCL_Listing();
        $this->packages = new WPJMCL_Packages();
        $this->admin = new WPJMCL_Admin();
    }

    /**
     * Textdomain.
     *
     * Load the textdomain based on WP language.
     *
     * @since 1.0.0
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'wp-job-manager-claim-listing', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    /**
     * License updater.
     *
     * Initialise the automatic license updater.
     *
     * @since 1.1.0
     */
    public function license_updater() {
        include_once( 'includes/updater/class-astoundify-updater.php' );
        new Astoundify_Updater_Claims( __FILE__ );
    }

}


/**
 * The main function responsible for returning the WP_Job_Manager_Claim_Listing object.
 *
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * @since 1.0.0
 *
 * @return object WP_Job_Manager_Claim_Listing class object.
 */
function WP_Job_Manager_Claim_Listing() {
    // _deprecated_function( 'WP_Job_Manager_Claim_Listing', '2.0.0', 'wpjmcl' );

    return WP_Job_Manager_Claim_Listing::instance();
}

/** Shortcut */
function wpjmcl() {
    return WP_Job_Manager_Claim_Listing::instance();
}

wpjmcl();
