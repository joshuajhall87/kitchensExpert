<?php
/**
 * Register shortcodes.
 *
 * @since 2.4.0
 */
class WPJMCL_Shortcodes {

    public static function init() {
        add_shortcode( 'claim_listing', array( __CLASS__, 'paid_claim_listing_shortcode' ) );
    }

    public static function paid_claim_listing_shortcode( $atts = array() ) {
		$atts = shortcode_atts( array(
			'listing_id' => isset( $_REQUEST[ 'listing_id' ] ) ? $_REQUEST[ 'listing_id' ] : false
		), $atts );

        return WPJMCL_Forms::get_form( 'claim-listing', $atts );
    }

}

WPJMCL_Shortcodes::init();
