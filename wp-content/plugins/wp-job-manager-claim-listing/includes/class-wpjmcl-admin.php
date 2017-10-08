<?php

class WPJMCL_Admin {

    public function __construct() {
        if ( ! is_admin() ) {
            return;
        }

        add_action( 'after_setup_theme', array( $this, 'init' ) );
    }

    public function init() {
        $this->includes();

        // Enqueue admin scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
    }

    public function includes() {
        $files = array(
			'class-wpjmcl-admin-post-types.php',
			'class-wpjmcl-admin-metaboxes.php',

            'class-wpjmcl-settings.php',

            'class-wpjmcl-claims.php',
            'class-wpjmcl-listing.php',
			'class-wpjmcl-ajax.php',
        );

        foreach ( $files as $file ) {
            require_once( wpjmcl()->plugin_dir . 'includes/admin/' . $file );
        }

        $this->claims = new WPJMCL_Claims_Admin();
        $this->listing = new WPJMCL_Listing_Admin();
        $this->ajax = new WPJMCL_Admin_AJAX();
    }

    /**
     * Enqueue scripts.
     *
     * Enqueue admin scripts.
     *
     * @since 1.0.0
     */
    public function admin_enqueue() {
        wp_enqueue_style( 'chosen', JOB_MANAGER_PLUGIN_URL . '/assets/css/chosen.css' );
        wp_enqueue_style( 'wp-job-manager-claim-listing', wpjmcl()->plugin_url . 'assets/css/wp-job-manager-claim-listing.css', array( 'dashicons' ) );

        wp_enqueue_script( 'wp-job-manager-claim-listing', wpjmcl()->plugin_url . 'assets/js/wp-job-manager-claim-listing.js',  array( 'jquery', 'chosen' ) );
    }
}
