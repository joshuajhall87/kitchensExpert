<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WPJMCL_Claims.
 *
 * Class to handle all claim business in the admin
 *
 * @class		WPJMCL_Claims
 * @version		1.1.0
 * @author		Spencer Finnell
 */
class WPJMCL_Claims_Admin {

    public function __construct() {
        add_action( 'save_post', array( $this, 'save_data_meta_box' ), 10, 2 );
    }

    /**
     * Save Meta box.
     *
     * Save the given contents from the meta box.
     *
     * @since 1.0.0
     *
     * @param int $post_id ID of the current post.
     */
    public function save_data_meta_box( $post_id, $post ) {
        if ( ! isset( $_POST['data_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['data_meta_box_nonce'], 'data_meta_box' ) ) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if ( 'claim' != $post->post_type ) {
            return;
        }

		$args = array(
			'listing_id' => isset( $_POST[ 'listing_id' ] ) ? absint( $_POST['listing_id'] ) : false,
			'user_id' => isset( $_POST[ 'user_id' ] ) ? absint( $_POST[ 'user_id' ] ) : false,
			'order_id' => isset( $_POST[ 'order_id' ] ) ? absint( $_POST[ 'order_id' ] ) : false,
			'package_id' => isset( $_POST[ 'package_id' ] ) ? absint( $_POST[ 'package_id' ] ) : false,
			'status' =>  isset( $_POST[ 'status' ] ) && in_array( $_POST['status'], array_keys( wpjmcl()->claims->statuses ) ) ? $_POST['status'] : false
		);

		$claim = wpjmcl_get_claim( $post_id );

        remove_action( 'save_post', array( $this, __FUNCTION__ ), 10, 2 );

		$claim->update( $args );

        add_action( 'save_post', array( $this, __FUNCTION__ ), 10, 2 );
	}

}
