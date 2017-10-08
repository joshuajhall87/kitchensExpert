<?php
/**
 * Post Types
 */
class WPJMCL_Post_Types {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ) );
	}

    public static function register_post_types() {
        $labels = array(
            'name'               => __( 'Claims ', 'wp-job-manager-claim-listing' ),
            'singular_name'      => __( 'Claim', 'wp-job-manager-claim-listing' ),
            'menu_name'          => __( 'Claims', 'wp-job-manager-claim-listing' ),
            'name_admin_bar'     => __( 'Claims', 'wp-job-manager-claim-listing' ),
            'add_new'            => __( 'Add New', 'wp-job-manager-claim-listing' ),
            'add_new_item'       => __( 'Add New Claim', 'wp-job-manager-claim-listing' ),
            'new_item'           => __( 'New Claim', 'wp-job-manager-claim-listing' ),
            'edit_item'          => __( 'Edit Claim', 'wp-job-manager-claim-listing' ),
            'view_item'          => __( 'View Claim', 'wp-job-manager-claim-listing' ),
            'all_items'          => __( 'All Claims', 'wp-job-manager-claim-listing' ),
            'search_items'       => __( 'Search Claims', 'wp-job-manager-claim-listing' ),
            'parent_item_colon'  => __( 'Parent Claims:', 'wp-job-manager-claim-listing' ),
            'not_found'          => __( 'No Claims found.', 'wp-job-manager-claim-listing' ),
            'not_found_in_trash' => __( 'No Claims found in Trash.', 'wp-job-manager-claim-listing' )
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'claim' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array(),
        );

        register_post_type( 'claim', $args );
    }

}

WPJMCL_Post_Types::init();
