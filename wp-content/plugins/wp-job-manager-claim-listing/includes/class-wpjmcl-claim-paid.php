<?php
/**
 * Paid Claim
 *
 * Handles additional functionality needed when a claim is associated
 * with an order and a listing package.
 *
 * @since 2.4.0
 */
class WPJMCL_Claim_Paid {

	public function __construct() {
		add_action( 'wpjmcl_claim_status_update_to_approved', array( $this, 'apply_package_selection' ), 10, 2 );
		add_action( 'wpjmcl_claim_status_update_to_approved', array( $this, 'apply_package_attributes' ), 10, 2 );
	}

	/**
	 * When a claim is approved and there is an associated order (a package was selected)
	 * then we need to go through the rest of the process of assigning the package to the listing.
	 *
	 * WC Paid Listings will automatically give the package when an order is completed
	 * and an associated product is a listing package. So find that and use that instead.

	 * It won't be increased because this won't fire for these orders:
	 * https://github.com/Automattic/wp-job-manager-wc-paid-listings/blob/master/includes/class-wc-paid-listings-orders.php#L136
	 *
	 * @see https://github.com/Automattic/wp-job-manager-wc-paid-listings/issues/2
	 *
	 * @param int $claim_id
	 * @param array $args
	 * @return void
	 */
    public function apply_package_selection( $status, $claim ) {
        if ( ! $claim->get_order_id() || ! $claim->get_package_id() ) {
            return;
        }

        $order = wc_get_order( $claim->get_order_id() );

        foreach ( $order->get_items() as $item_key => $item ) {
            $product = wc_get_product( $item[ 'product_id' ] );

            if ( ! ( $product->is_type( array( 'job_package', 'job_package_subscription' ) ) && $order->customer_user ) ) {
				continue;
			}

			global $wpdb;

			$package = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wcpl_user_packages WHERE user_id = %d AND order_id = %d AND ( package_count < package_limit OR package_limit = 0 );", $order->customer_user, $claim->get_order_id() ) );

			// Increase the package usage (to be 1/1) and assign the user's package
			if ( isset( $item['listing_id'] ) && $package ) {
				$listing_id = $item[ 'listing_id' ];

				// apply the user package
				wc_paid_listings_increase_package_count( $order->customer_user, $package->id );
				update_post_meta( $listing_id, '_user_package_id', $package->id );

				if ( $product->is_type( 'job_package_subscription' ) ) {
					do_action( 'wc_paid_listings_switched_subscription', $listing_id, $package );
				}
			}
        }
    }

	/**
	 * Apply the rest of the package attributes. 
	 *
	 * This needs to be done manually for now as there is no API for switching/setting
	 * packages. This means any plugins will need to hook in twice: here and core.
	 *
	 * @see https://github.com/Automattic/wp-job-manager-wc-paid-listings/issues/2
	 *
	 * @param int $claim_id
	 * @param array $args
	 * @return void
	 */
    public function apply_package_attributes( $status, $claim ) {
        if ( ! $claim->get_package_id() ) {
            return;
        }

        $package_id = $claim->get_package_id();
        $package = wc_get_product( $package_id );
		$listing_id = $claim->get_listing_id();

        update_post_meta( $listing_id, '_job_duration', $package->get_duration() );
        update_post_meta( $listing_id, '_featured', $package->is_featured() ? 1 : 0 );
        update_post_meta( $listing_id, '_package_id', $package_id );

        if ( 'listing' === $package->package_subscription_type ) {
            update_post_meta( $listing_id, '_job_expires', '' ); // Never expire automatically
		} else {
            update_post_meta( $listing_id, '_job_expires', calculate_job_expiry( $claim->get_listing_id() ) );
		}

        // remove the temporary package_id
        delete_post_meta( $listing_id, '_maybe_package_id', $package_id );
    }

}
