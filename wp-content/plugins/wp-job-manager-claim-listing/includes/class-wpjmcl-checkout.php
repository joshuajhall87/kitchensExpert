<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WPJMCL_Listing.
 */
class WPJMCL_Checkout {

    public static function init() {
        // Display meta at checkout
        add_filter( 'woocommerce_get_item_data', array( __CLASS__, 'display_listing_at_checkout' ), 10, 2 );

        // Re-add meta when getting cart items session
        add_filter( 'woocommerce_get_cart_item_from_session', array( __CLASS__, 'get_cart_item_from_session_add_item_meta' ), 10, 3 );
    }

    /**
     * Display listing (cart).
     *
     * Display the listing ID at the checkout.
     *
     * @since 1.0.0
     *
     * @param 	array 	$meta 		List of existing meta to display at checkout.
     * @param 	array	$cart_item	List of cart product values.
     * @return	array				List of modified meta to display at checkout.
     */
    public static function display_listing_at_checkout( $meta, $cart_item ) {
        if ( isset( $cart_item['listing_id'] ) ) {
            $listing = get_post( $cart_item[ 'listing_id' ] );
            $meta[] = array(
                'name' 	=> __( 'Listing', 'wp-job-manager-claim-listing' ),
                'value' => $listing->post_title,
            );
        }

        return $meta;
    }


    /**
     * Session cart item meta.
     *
     * When cart items are retrieved from the session, it will remove all item meta
     * this function makes sure it will re-add those meta.
     *
     * @since 1.0.0
     *
     * @param 	array 	$cart_item 	Cart item values.
     * @param	array 	$values		Old values.
     * @param	string	$key		Cart item key.
     * @return	array				Modified cart item values.
     */
    public static function get_cart_item_from_session_add_item_meta( $cart_item, $values, $key ) {
        if ( isset( $values[ 'listing_id' ] ) ) {
            $cart_item['listing_id'] = $values['listing_id'];
        }

        return $cart_item;
    }

}
add_action( 'plugins_loaded', array( 'WPJMCL_Checkout', 'init' ) );
