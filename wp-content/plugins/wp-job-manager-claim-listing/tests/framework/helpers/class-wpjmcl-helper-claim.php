<?php
/**
 * For tests
 */
class WPJMCL_Helper_Claim {

	public static function delete( $claim_id ) {
		wp_delete_post( $claim_id, true );
	}

	public static function create( $args = array() ) {
		$claim = \wpjmcl_create_claim( $args );

		return $claim;
	}

	public static function update( $claim, $args = array() ) {
		$claim = \wpjmcl_update_claim( $claim, $args );

		return $claim;
	}

}
