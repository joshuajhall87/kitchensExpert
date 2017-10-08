<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 */
class WPJMCL_Admin_Post_Types {

    public static function init() {
        // Add to menu
        add_action( 'admin_menu', array( __CLASS__, 'add_claim_to_menu' ) );

        // Menu highlight
        add_action( 'admin_head', array( __CLASS__, 'menu_highlight' ) );

        // Update post type messages
        add_filter( 'post_updated_messages', array( __CLASS__, 'custom_messages' ) );

        // Add custom columns
        add_filter( 'manage_edit-claim_columns', array( __CLASS__, 'custom_columns' ), 11 );

        // Add contents to custom columns
        add_action( 'manage_claim_posts_custom_column', array( __CLASS__, 'custom_column_contents' ), 10, 2 );

        // Add Claim filter
        add_action( 'restrict_manage_posts', array( __CLASS__, 'post_type_claim_filters' ) );
        add_filter( 'request', array( __CLASS__, 'post_type_claim_filters_request' ) );
	}

    /**
     * Menu item.
     *
     * Add 'Claims' to the sub-menu of 'Job Listings'.
     *
     * @since 1.0.0
     */
    public static function add_claim_to_menu() {
        add_submenu_page( 'edit.php?post_type=job_listing', 'Claims', 'Claims', 'manage_options', '/edit.php?post_type=claim' );
    }

    /**
     * Menu highlight.
     *
     * Hightlight the Listings menu when on any of the claim listing pages.
     *
     * @since 1.1.0
     */
    public static function menu_highlight() {
        global $parent_file, $submenu_file, $post_type;

        if ( 'claim' == $post_type ) {
            $parent_file = 'edit.php?post_type=job_listing';
            $submenu_file = 'edit.php?post_type=claim';
		}
    }

    /**
     * Admin messages.
     *
     * Custom admin messages when using claim post type.
     *
     * @since 1.0.0
     *
     * @param 	array $messages List of existing post messages.
     * @return 	array 			Full list of all messages.
     */
    public static function custom_messages( $messages ) {
        $post             = get_post();
        $post_type        = 'claim';
        $post_type_object = get_post_type_object( $post_type );

        $messages[ $post_type ] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __( 'Claim updated.', 'wp-job-manager-claim-listing' ),
            2  => __( 'Custom field updated.', 'wp-job-manager-claim-listing' ),
            3  => __( 'Custom field deleted.', 'wp-job-manager-claim-listing' ),
            4  => __( 'Claim updated.', 'wp-job-manager-claim-listing' ),
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Claim restored to revision from %s', 'wp-job-manager-claim-listing' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => __( 'Claim published.', 'wp-job-manager-claim-listing' ),
            7  => __( 'Claim saved.', 'wp-job-manager-claim-listing' ),
            8  => __( 'Claim submitted.', 'wp-job-manager-claim-listing' ),
            9  => sprintf(
                __( 'Claim scheduled for: <strong>%1$s</strong>.', 'wp-job-manager-claim-listing' ),
                date_i18n( __( 'M j, Y @ G:i', 'wp-job-manager-claim-listing' ), strtotime( $post->post_date ) )
            ),
            10 => __( 'Claim draft updated.', 'wp-job-manager-claim-listing' )
        );

        $permalink 					= admin_url( 'edit.php?post_type=claim' );
        $return_to_claims_link 		= sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'Return to claims', 'wp-job-manager-claim-listing' ) );
        $messages[ $post_type ][1] 	.= $return_to_claims_link;
        $messages[ $post_type ][6] 	.= $return_to_claims_link;
        $messages[ $post_type ][9] 	.= $return_to_claims_link;
        $messages[ $post_type ][8]  .= $return_to_claims_link;
        $messages[ $post_type ][10] .= $return_to_claims_link;

        return $messages;
    }

    /**
     * Post columns.
     *
     * Set custom columns for the new post type.
     *
     * @since 1.0.0
     *
     * @param 	array $columns 	List of existing post columns.
     * @return 	array 			List of edited columns.
     */
    public static function custom_columns( $existing_columns ) {
        $columns['cb']    		= '<input type="checkbox" />';
        $columns['status']		= __( 'Status', 'wp-job-manager-claim-listing' );
        $columns['title']		= __( 'Title', 'wp-job-manager-claim-listing' );
        $columns['listing']		= __( 'Listing', 'wp-job-manager-claim-listing' );
        $columns['order_id']	= __( 'Order', 'wp-job-manager-claim-listing' );
        $columns['claimer']		= __( 'Claimer', 'wp-job-manager-claim-listing' );
        $columns['date']		= __( 'Date', 'wp-job-manager-claim-listing' );

        $merged_columns = array_merge( $columns, $existing_columns );

        unset( $merged_columns['title'] );

        return $merged_columns;

    }

    /**
     * Column contents.
     *
     * Ouput the custom columns contents.
     *
     * @since 1.0.0
     *
     * @param string 	$columns Slug of the current columns to ouput data for.
     * @param int 		$post_id ID of the current post.
     */
    public static function custom_column_contents( $column, $post_id ) {
        global $post;

        switch( $column ) :

            case 'status' :
                $status 	= get_post_meta( $post_id, '_status', true );
                $statuses 	= wpjmcl()->claims->statuses;

                ?><span class='status status-<?php echo sanitize_html_class( strtolower( $status ) ); ?>'>
                    <?php echo isset( $statuses[ $status ] ) ? $statuses[ $status ] : __( 'Unknown', 'wp-job-manager-claim-listing' ); ?>
                </span><?php
            break;

            case 'listing' :
                $listing 	= get_post( get_post_meta( $post_id, '_listing_id', true ) );
                $href 		= admin_url( sprintf( 'post.php?post=%s&action=edit', $post->ID ) );
                ?>
                    <strong><a href='<?php echo esc_url( $href ); ?>'><?php echo esc_html( $post->post_title ); ?></a></strong>
                    <div class="row-actions">
                        <span class="edit"><a href='<?php echo esc_url( $href ); ?>'><?php _e( 'Edit Claim',
                        'wp-job-manager-claim-listing' ); ?></a></span> &nbsp;|&nbsp;
                        <span class="view"><a href="<?php echo esc_url( get_permalink( $listing ) ); ?>"><?php _e( 'View Listing',
                        'wp-job-manager-claim-listing' ); ?></span></span>
                    </div>
                <?php
            break;

            case 'order_id' :
                $order_id 	= get_post_meta( $post_id, '_order_id', true );

                if ( $order_id ) {
                    $status     = get_post( $order_id )->post_status;
                    $href 		= admin_url( sprintf( 'post.php?post=%s&action=edit', $order_id ) );
                ?>
                    <a href='<?php echo esc_url( $href ); ?>'>#<?php echo absint( $order_id ); ?></a><br /><?php echo esc_attr( wc_get_order_status_name( $status ) ); ?>
                <?php } else { ?>
                    &mdash;
                <?php
                }
            break;

            case 'claimer' :
                $post = get_post( $post_id );
                $user = get_userdata( $post->post_author );
                ?><a href='user-edit.php?user_id=<?php echo absint( $user->ID ); ?>'><?php echo esc_html( $user->display_name ); ?></a><?php
            break;

        endswitch;
    }

    /**
     * Status filter dropdown.
     *
     * Display the claim status filter dropdown at the
     * claims post overview page.
     *
     * @since 1.1.0
     *
     * @global string 	$typenow 	The current post type of the page loading.
     */
    public static function post_type_claim_filters() {
        global $typenow;

        if ( $typenow != 'claim' ) :
            return;
        endif;

        ?><select name='claim_status' id='dropdown_claim_status'>
            <option value=''><?php _e( 'All claim statuses', 'wp-job-manager-claim-listing' ); ?></option><?php

            foreach ( wpjmcl()->claims->statuses as $key => $status ) :
                ?><option value='<?php echo esc_attr( $key ); ?>' <?php selected( isset( $_GET['claim_status'] ) ? $_GET['claim_status']: '', $key ); ?>><?php echo esc_html( $status ); ?></option><?php
            endforeach;

        ?></select><?php

    }

    /**
     * Filter request.
     *
     * Filter the posts request when the claim status is set.
     *
     * @since 1.1.0
     *
     * @param 	array $vars Variables set for the Query request.
     * @return	array		Modified variables with the status meta query.
     */
    public static function post_type_claim_filters_request( $vars ) {
        // Bail if claim status GET is not set.
        if ( ! isset( $_GET['claim_status'] ) || empty( $_GET['claim_status'] ) ) :
            return $vars;
        endif;

        $vars['meta_query'][] = array(
            'key'		=> '_status',
            'comare'	=> '=',
            'value'		=> $_GET['claim_status'],
        );

        return $vars;

    }

}

WPJMCL_Admin_Post_Types::init();
