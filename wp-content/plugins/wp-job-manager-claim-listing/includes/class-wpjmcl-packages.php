<?php

class WPJMCL_Packages {

    public function __construct() {
        add_filter( 'wcpl_get_job_packages_args', array( $this, 'wcpl_get_job_packages_args' ) );
    }

    /**
     * Exclude packages used for claiming from the main submission packages
     */
    public function wcpl_get_job_packages_args( $args ) {
        $args[ 'meta_query' ][] = array(
            'key'     => '_use_for_claims',
            'value'   => 'no',
            'compare' => '=',
        );

        return $args;
    }

    public static function get_packages_for_claiming( $post__in = array() ) {
        return get_posts( array(
            'post_type'        => 'product',
            'posts_per_page'   => -1,
            'post__in'         => $post__in,
            'order'            => 'asc',
            'orderby'          => 'menu_order',
            'suppress_filters' => false,
            'tax_query'        => array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => array( 'job_package', 'job_package_subscription' )
                )
            ),
            'meta_query'     => array(
                array(
                    'key'     => '_visibility',
                    'value'   => array( 'visible', 'catalog' ),
                    'compare' => 'IN'
                ),
                array(
                    'key'     => '_use_for_claims',
                    'value'   => 'yes',
                    'compare' => '=',
                ),
            )
        ) );
    }
}
