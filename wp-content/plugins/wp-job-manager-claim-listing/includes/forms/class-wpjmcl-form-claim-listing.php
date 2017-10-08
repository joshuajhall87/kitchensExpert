<?php

/**
 * WPJMCL_Form_Claim_Listing class.
 */
class WPJMCL_Form_Claim_Listing extends WP_Job_Manager_Form {

    public    $form_name = 'claim-listing';
    public    $steps = array();
    protected $job_id;
    protected $preview_job;
    private static $package_id      = 0;
    private static $is_user_package = false;


    /** @var WPJMCL_Form_Claim_Listing The single instance of the class */
    protected static $_instance = null;

    /**
     * Main Instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        // Posted Data
        if ( ! empty( $_POST['job_package'] ) ) {
            if ( is_numeric( $_POST['job_package'] ) ) {
                self::$package_id      = absint( $_POST['job_package'] );
                self::$is_user_package = false;
            } else {
                self::$package_id      = absint( substr( $_POST['job_package'], 5 ) );
                self::$is_user_package = true;
            }
        } elseif ( ! empty( $_COOKIE['chosen_package_id'] ) ) {
            self::$package_id      = absint( $_COOKIE['chosen_package_id'] );
            self::$is_user_package = absint( $_COOKIE['chosen_package_is_user_package'] ) === 1;
        }

        add_action( 'wp', array( $this, 'process' ) );

        $this->steps  = (array) apply_filters( 'wpjmcl_claim_listing_steps', array(
            'wc-pay' => array(
                'name'     => __( 'Choose a package', 'wp-job-manager-claim-listing' ),
                'view'     => array( __CLASS__, 'choose_package' ),
                'handler'  => array( __CLASS__, 'choose_package_handler' ),
                'priority' => 5,
            ),
            'done' => array(
                'name'     => __( 'Done', 'wp-job-manager' ),
                'view'     => array( $this, 'done' ),
                'priority' => 30
            ),
        ) );

        $this->step = isset( $_REQUEST['step'] ) ? max( absint( $_REQUEST['step'] ), 0 ) : 0;
        $this->job_id = ! empty( $_REQUEST['listing_id'] ) ? absint( $_REQUEST['listing_id'] ) : 0;
    }

    /**
     * Get the submitted job ID
     * @return int
     */
    public function get_job_id() {
        return absint( $this->job_id );
    }

    /**
     * Choose package form
     */
    public static function choose_package( $atts = array() ) {
		$form      = WPJMCL_Form_Claim_Listing::instance();

		if ( ! isset( $atts[ 'listing_id' ] ) ) {
			$job_id    = $form->get_job_id();
		} else {
			$job_id = $atts[ 'listing_id' ];
		}

		if ( ! $job_id ) {
			return _e( 'Please select a listing.', 'wp-job-manager-claim-listing' );
		}

		$step      = $form->get_step();
		$form_name = $form->form_name;

        $packages      = wpjmcl()->packages->get_packages_for_claiming();
        $user_packages = array();
        $button_text   = 'before' !== get_option( 'job_manager_paid_listings_flow' ) ? __( 'Submit &rarr;', 'wp-job-manager-claim-listing' ) : __( 'Claim listing &rarr;', 'wp-job-manager-claim-listing' );
        ?>
        <form method="post" id="job_package_selection">
            <div class="job_listing_packages_title">
                <input type="submit" name="continue" class="button" value="<?php echo apply_filters( 'submit_job_step_choose_package_submit_text', $button_text ); ?>" />
                <input type="hidden" name="job_id" value="<?php echo esc_attr( $job_id ); ?>" />
                <input type="hidden" name="step" value="<?php echo esc_attr( $step ); ?>" />
                <input type="hidden" name="job_manager_form" value="<?php echo $form_name; ?>" />
                <h2><?php _e( 'Choose a package', 'wp-job-manager-claim-listing' ); ?></h2>
            </div>
            <div class="job_listing_packages">
                <?php get_job_manager_template( 'package-selection.php', array( 'packages' => $packages, 'user_packages' => $user_packages ), 'wc-paid-listings', JOB_MANAGER_WCPL_PLUGIN_DIR . '/templates/' ); ?>
            </div>
        </form>
        <?php
    }

    /**
     * Choose package handler
     * @return bool
     */
    public static function choose_package_handler() {
        if ( ! isset( $_REQUEST['job_package'] ) ) {
            return;
        }

        if ( version_compare( JOB_MANAGER_VERSION, '1.22.0', '<' ) ) {

            // Validate Selected Package
            $validation = self::validate_package( self::$package_id, self::$is_user_package );
            if ( is_wp_error( $validation ) ) {
                WPJMCL_Form_Claim_Listing::add_error( $validation->get_error_message() );
                return false;
            }

            // Store selection in cookie
            wc_setcookie( 'chosen_package_id', self::$package_id );
            wc_setcookie( 'chosen_package_is_user_package', self::$is_user_package ? 1 : 0 );

            // Process the package unless we're doing this before a job is submitted
            if ( self::process_package( self::$package_id, self::$is_user_package, WPJMCL_Form_Claim_Listing::get_job_id() ) ) {
                WPJMCL_Form_Claim_Listing::next_step();
            }
        } else {
            $form = WPJMCL_Form_Claim_Listing::instance();

            // Validate Selected Package
            $validation = self::validate_package( self::$package_id, self::$is_user_package );
            if ( is_wp_error( $validation ) ) {
                $form->add_error( $validation->get_error_message() );
                return false;
            }

            // Store selection in cookie
            wc_setcookie( 'chosen_package_id', self::$package_id );
            wc_setcookie( 'chosen_package_is_user_package', self::$is_user_package ? 1 : 0 );
            // Process the package unless we're doing this before a job is submitted

            if ( self::process_package( self::$package_id, self::$is_user_package, $form->get_job_id() ) ) {
                $form->next_step();
            }
        }
    }

    /**
     * Validate package
     * @param  int $package_id
     * @param  bool $is_user_package
     * @return bool|WP_Error
     */
    private static function validate_package( $package_id, $is_user_package ) {
        if ( empty( $package_id ) ) {
            return new WP_Error( 'error', __( 'Invalid Package', 'wp-job-manager-claim-listing' ) );
        } elseif ( $is_user_package ) {
            if ( ! wc_paid_listings_package_is_valid( get_current_user_id(), $package_id ) ) {
                return new WP_Error( 'error', __( 'Invalid Package', 'wp-job-manager-claim-listing' ) );
            }
        } else {
            $package = wc_get_product( $package_id );

            if ( ! $package->is_type( 'job_package' ) && ! $package->is_type( 'job_package_subscription' ) ) {
                return new WP_Error( 'error', __( 'Invalid Package', 'wp-job-manager-claim-listing' ) );
            }

            // Don't let them buy the same subscription twice if the subscription is for the package
            if ( class_exists( 'WC_Subscriptions' ) && is_user_logged_in() && $package->is_type( 'job_package_subscription' ) && 'package' === $package->package_subscription_type ) {
                $user_subscriptions = wcs_get_users_subscriptions( get_current_user_id() );
                foreach ( $user_subscriptions as $user_subscription ) {
                    if ( $user_subscription->product_id == $package_id ) {
                        return new WP_Error( 'error', __( 'You already have this subscription.', 'wp-job-manager-claim-listing' ) );
                    }
                }
            }
        }
        return true;
    }

    /**
     * Purchase a job package
     * @param  int|string $package_id
     * @param  int $job_id
     * @return bool Did it work or not?
     */
    private static function process_package( $package_id, $is_user_package, $job_id ) {
        if ( ! $is_user_package ) {
            $package = wc_get_product( $package_id );

            // Don't overwrite the current package_id until the claim is approved
            update_post_meta( $job_id, '_maybe_package_id', $package_id );

            // Add package to the cart
            WC()->cart->add_to_cart( $package_id, 1, '', '', array( 'listing_id' => $job_id ) );

            woocommerce_add_to_cart_message( $package_id );

            // Clear cookie
            wc_setcookie( 'chosen_package_id', '', time() - HOUR_IN_SECONDS );
            wc_setcookie( 'chosen_package_is_user_package', '', time() - HOUR_IN_SECONDS );

            do_action( 'wpjmcl_process_package_for_job_listing', $package_id, $is_user_package, $job_id );

            // Redirect to checkout page
            wp_redirect( WC()->cart->get_checkout_url() );
            exit;
        }

        return false;
    }

    /**
     * Done Step
     */
    public function done() {
        do_action( 'job_manager_job_submitted', $this->job_id );
        get_job_manager_template( 'job-submitted.php', array( 'job' => get_post( $this->job_id ) ) );
    }
}
