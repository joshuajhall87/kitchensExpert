<?php

class WPJMCL_Forms {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'load_posted_form' ), 20 );
    }

    /**
     * If a form was posted, load its class so that it can be processed before display.
     */
    public static function load_posted_form() {
        if ( ! empty( $_POST[ 'job_manager_form' ] ) ) {
			self::load_form_class( sanitize_title( $_POST[ 'job_manager_form' ] ) );
        }
    }

    /**
     * Load a form's class
     *
     * @param  string $form_name
     * @return string class name on success, false on failure
     */
    private static function load_form_class( $form_name ) {
        if ( ! class_exists( 'WP_Job_Manager_Form' ) ) {
            include( JOB_MANAGER_PLUGIN_DIR . '/includes/abstracts/abstract-wp-job-manager-form.php' );
        }

        $wpjmcl = wpjmcl();

        // Now try to load the form_name
        $form_class  = 'WPJMCL_Form_' . str_replace( '-', '_', $form_name );
        $form_file   =  $wpjmcl->plugin_dir . '/includes/forms/class-wpjmcl-form-' . $form_name . '.php';

        if ( class_exists( $form_class ) ) {
            return call_user_func( array( $form_class, 'instance' ) );
        }

        if ( ! file_exists( $form_file ) ) {
            return false;
        }

        if ( ! class_exists( $form_class ) ) {
            include $form_file;
        }

        // Init the form
        return call_user_func( array( $form_class, 'instance' ) );
    }

    /**
     * get_form function.
     *
     * @param string $form_name
     * @param  array $atts Optional passed attributes
     * @return string
     */
    public static function get_form( $form_name, $atts = array() ) {
        if ( $form = self::load_form_class( $form_name ) ) {
            ob_start();
            $form->output( $atts );
            return ob_get_clean();
        }
    }
}

WPJMCL_Forms::init();
