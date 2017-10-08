<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WPJMCL_Listing.
 */
class WPJMCL_Listing {

    public function __construct() {
        add_action( 'single_job_listing_start', array( $this, 'claim_listing_link' ) );
        add_filter( 'post_class', array( $this, 'add_post_class' ), 10, 3 );

		add_action( 'wpjmcl_claim_status_update_to_approved', array( $this, 'set_listing_as_claimed' ), 10, 2 );
		add_action( 'wpjmcl_claim_status_update_to_approved', array( $this, 'set_listing_owner' ), 10, 2 );
    }

	/**
	 * Switch the package that is associated with the listing.
	 *
	 * @since 2.5.0
	 *
	 * @param int $new_package
	 * @param int $old_package
	 * @param array $args
	 * @return void
	 */
	public function switch_package( $args, $old_package ) {
		global $wpdb;

		$current_user_package = wc_paid_listings_get_user_package( $old_package );

		$new_user_package_id = wc_paid_listings_give_user_package( $args[ 'user_id' ], $args[ 'package_id' ], $args[ 'order_id' ] );

		if ( ! $new_user_package_id ) {
			return;
		}

		$new_user_package = wc_paid_listings_get_user_package( $new_user_package_id );

		if ( isset( $current_user_package->id ) ) {
			$wpdb->delete( "{$wpdb->prefix}wcpl_user_packages", array( 'id' => $current_user_package->get_id() ) );
		}

		// use and apply the package
		wc_paid_listings_increase_package_count( $args[ 'user_id' ], $new_user_package->get_id() );
		update_post_meta( $args[ 'listing_id' ], '_user_package_id', $new_user_package->get_id() );
		update_post_meta( $args[ 'listing_id' ], '_package_id', $args[ 'package_id' ] );

		do_action( 'wpjmcl_switched_package', $args[ 'listing_id' ], $new_user_package );
	}

	/**
	 * When a claim is marked as approved the associated listing should be marked as claimed.
	 *
	 * @since 2.5.0
	 *
	 * @param int $claim_id
	 * @param array $args
	 * @return void
	 */
	public function set_listing_as_claimed( $status, $claim ) {
		$listing = get_post( $claim->get_listing_id() );

		if ( ! $listing ) {
			return;
		}

		update_post_meta( $listing->ID, '_claimed', 1 );
	}

	/**
	 * When a claim is marked as approved the associated user should become the author.
	 *
	 * @since 2.5.0
	 *
	 * @param int $claim_id
	 * @param array $args
	 * @return void
	 */
	public function set_listing_owner( $status, $claim ) {
		$listing = get_post( $claim->get_listing_id() );

		if ( ! $listing ) {
			return;
		}

        wp_update_post( array(
            'ID' => $listing->ID,
            'post_author' => $claim->get_user_id()
        ) );
	}

    /**
     * Is claimable.
     *
     * Check if the current listing is claimable.
     *
     * @since 1.0.0
     */
    public function is_claimed( $listing_id = false ) {
        if ( ! $listing_id ) {
            $listing = get_post();
		} else {
			$listing = get_post( $listing_id );
		}

        if ( 'publish' != $listing->post_status ) {
            return false;
        }

        $claimed = 1 === absint( $listing->_claimed ) ? true : false;

        return apply_filters( 'wpjmcl_is_claimed', $claimed, $listing );
    }


    /**
     * Claim listing link.
     *
     * Display 'Claim this listing' link.
     *
     * @since 1.0.0
     */
    public function claim_listing_link( $listing_id = false ) {
		if ( ! $listing_id ) {
			$listing = get_post();
		} else {
			$listing = get_post( $listing_id );
		}

        if ( ! $this->is_claimed( $listing->ID ) ) {
			$href = $this->claim_listing_url( $listing );
?>
    <a href='<?php echo $href; ?>' class='claim-listing'><?php _e( 'Claim this listing', 'wp-job-manager-claim-listing' ); ?></a>
<?php
        }

    }

	/**
	 * Create a URL to claim a listing.
	 *
	 * @since 2.5.0
	 *
	 * @param object $listing
	 * @return string $href
	 */
	public function claim_listing_url( $listing ) {
		$paid_claim_listing_page = job_manager_get_permalink( 'claim_listing' );

		$href = add_query_arg( array(
			'action' => 'claim_listing',
			'listing_id' => $listing->ID
		), $paid_claim_listing_page );

		$href = esc_url( wp_nonce_url( $href, 'claim_listing', 'claim_listing_nonce' ) );

		return $href;
	}

    /**
     * Post class.
     *
     * Add a post class 'claimed' or 'not-claimed'.
     *
     * @since 1.0.0
     *
     * @param	array $classes 	List of existing classes.
     * @return	array 			List of modified classes.
     */
    public function add_post_class( $classes, $class, $post_id ) {
        $classes[] = $this->is_claimed( $post_id ) ? 'claimed' : 'not-claimed';

        return $classes;
    }

}
