<?php

function wpjmcl_get_claim( $claim ) {
	return new WPJMCL_Claim( $claim );
}

function wpjmcl_create_claim( $args = array() ) {
	$claim = new WPJMCL_Claim();

	return $claim->create( $args );
}

function wpjmcl_update_claim( $claim, $args = array() ) {
	$claim = wpjmcl_get_claim( $claim );

	$claim->update( $args );

	return $claim;
}

function wpjmcl_get_claims() {
	global $wpdb;

	$claims = array();

	$search = $wpdb->get_results( "
		SELECT ID FROM {$wpdb->prefix}posts
		WHERE post_type = 'claim'
		AND post_status = 'publish'
	" );

	if ( ! $search ) {
		return $claims;
	}

	foreach ( $search as $claim ) {
		$claims[] = wpjmcl_get_claim( $claim->ID );
	}

	return $claims;
}
