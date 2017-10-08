<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

class WP_Job_Manager_Job_Tags_Shortcodes {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( 'jobs_by_tag', array( $this, 'jobs_by_tag' ) );
		add_shortcode( 'job_tag_cloud', array( $this, 'job_tag_cloud' ) );

		// Change core output jobs shortcode
		add_action( 'job_manager_job_filters_search_jobs_end', array( $this, 'show_tag_filter' ) );
		add_filter( 'job_manager_get_listings', array( $this, 'apply_tag_filter' ) );
		add_filter( 'job_manager_output_jobs_defaults', array( $this, 'output_jobs_defaults' ) );
	}

	/**
	 * Change default args
	 */
	public function output_jobs_defaults( $atts ) {
		$atts['show_tags'] = true;
		return $atts;
	}

	/**
	 * Show the tag cloud
	 */
	public function show_tag_filter( $shortcode_atts ) {

		if ( isset( $shortcode_atts['show_tags'] ) && ( $shortcode_atts['show_tags'] === false || (string) $shortcode_atts['show_tags'] == 'false' ) )
			return;

		if ( wp_count_terms( 'job_listing_tag' ) == 0 )
			return;

		wp_enqueue_script( 'wp-job-manager-ajax-tag-filters', JOB_MANAGER_TAGS_PLUGIN_URL . '/assets/js/tag-filter.js', array( 'jquery' ), '1.0', true );

		$atts = array(
			'smallest'                  => 1,
		    'largest'                   => 2,
		    'unit'                      => 'em',
		    'number'                    => 25,
		    'format'                    => 'flat',
		    'separator'                 => "\n",
		    'orderby'                   => 'count',
		    'order'                     => 'DESC',
		    'exclude'                   => null,
		    'include'                   => null,
		    'link'                      => 'view',
		    'taxonomy'                  => 'job_listing_tag',
		    'echo'                      => false,
		    'topic_count_text_callback' => array( $this, 'tag_cloud_text_callback' )
		);

		/**$html = '<div class="filter_wide filter_by_tag"><span class="filter-label">Pin point the right company for you!<span><br><br /><a href="#" class="tag-link-24 tag-link-position-1" title="6 listings" style="font-size: 2em;">Accepts Credit Cards</a><a href="https://www.kitchensexpert.co.uk/listing-tag/street-parking/" class="tag-link-33 tag-link-position-2" title="4 listings" style="font-size: 1.7272727272727em;">Street Parking</a><a href="https://www.kitchensexpert.co.uk/listing-tag/reservations/" class="tag-link-32 tag-link-position-3" title="4 listings" style="font-size: 1.7272727272727em;">Reservations</a><a href="https://www.kitchensexpert.co.uk/listing-tag/alcohol/" class="tag-link-25 tag-link-position-4" title="4 listings" style="font-size: 1.7272727272727em;">Alcohol</a><a href="https://www.kitchensexpert.co.uk/listing-tag/flatpack-cabinets/" class="tag-link-80 tag-link-position-5" title="3 listings" style="font-size: 1.5454545454545em;">flatpack cabinets</a><a href="https://www.kitchensexpert.co.uk/listing-tag/pet-friendly/" class="tag-link-31 tag-link-position-6" title="3 listings" style="font-size: 1.5454545454545em;">Pet Friendly</a><a href="https://www.kitchensexpert.co.uk/listing-tag/6-cabinet-widths/" class="tag-link-86 tag-link-position-7" title="2 listings" style="font-size: 1.3272727272727em;">6 cabinet widths</a><a href="https://www.kitchensexpert.co.uk/listing-tag/16mm-cabinets/" class="tag-link-82 tag-link-position-8" title="2 listings" style="font-size: 1.3272727272727em;">16mm cabinets</a><a href="https://www.kitchensexpert.co.uk/listing-tag/7-cabinet-widths/" class="tag-link-81 tag-link-position-9" title="2 listings" style="font-size: 1.3272727272727em;">7 cabinet widths</a><a href="https://www.kitchensexpert.co.uk/listing-tag/public-wi-fi/" class="tag-link-79 tag-link-position-10" title="2 listings" style="font-size: 1.3272727272727em;">public wi-fi</a><a href="https://www.kitchensexpert.co.uk/listing-tag/15mm-cabinets/" class="tag-link-87 tag-link-position-11" title="1 listing" style="font-size: 1em;">15mm cabinets</a><a href="https://www.kitchensexpert.co.uk/listing-tag/german-kitchens/" class="tag-link-85 tag-link-position-12" title="1 listing" style="font-size: 1em;">german kitchens</a><a href="https://www.kitchensexpert.co.uk/listing-tag/solid-back-cabinets/" class="tag-link-84 tag-link-position-13" title="1 listing" style="font-size: 1em;">solid back cabinets</a><a class="tag-link-83 tag-link-position-14" title="1 listing" style="font-size: 1em;">bespoke cabinets</a></div>';
**/
		$html = preg_replace( "/<a(.*)href='([^'']*)'(.*)>/", '<a href="#"$1$3>', $html );

		echo $html;
	}

	/**
	 * Filter by tag
	 */
	public function apply_tag_filter( $args ) {
		$params = array();
		if ( isset( $_POST['form_data'] ) ) {

			parse_str( $_POST['form_data'], $params );

			if ( isset( $params['job_tag'] ) ) {
				$tags      = array_filter( $params['job_tag'] );
				$tag_array = array();

				foreach ( $tags as $tag ) {
					$tag = get_term_by( 'name', $tag, 'job_listing_tag' );
					$tag_array[] = $tag->slug;
				}

				$args['tax_query'][] = array(
					'taxonomy' => 'job_listing_tag',
					'field'    => 'slug',
					'terms'    => $tag_array,
					'operator' => "IN"
				);

				add_filter( 'job_manager_get_listings_custom_filter', '__return_true' );
				add_filter( 'job_manager_get_listings_custom_filter_text', array( $this, 'apply_tag_filter_text' ) );
				add_filter( 'job_manager_get_listings_custom_filter_rss_args', array( $this, 'apply_tag_filter_rss' ) );
			}

		}

		return $args;
	}

	/**
	 * Append 'showing' text
	 * @return string
	 */
	public function apply_tag_filter_text( $text ) {
		$params = array();
		parse_str( $_POST['form_data'], $params );

		$text .= ' ' . __( 'tagged', 'wp-job-manager-tags' ) . ' ' . implode( ', ', array_filter( $params['job_tag'] ) );

		return $text;
	}

	/**
	 * apply_tag_filter_rss
	 * @return array
	 */
	public function apply_tag_filter_rss( $args ) {
		$params = array();
		parse_str( $_POST['form_data'], $params );

		$args['job_tags'] = implode( ',', array_filter( $params['job_tag'] ) );

		return $args;
	}

	/**
	 * Jobs by tag shortcode
	 *
	 * @return string
	 */
	public function jobs_by_tag( $atts ) {
		global $job_manager;

		ob_start();

		extract( shortcode_atts( array(
			'per_page'        => '-1',
			'orderby'         => 'date',
			'order'           => 'desc',
			'tag'             => '',
			'tags'            => ''
		), $atts ) );

		$tags   = array_filter( array_map( 'sanitize_title', explode( ',', $tags ) ) );

		if ( $tag )
			$tags[] = sanitize_title( $tag );

		if ( ! $tags )
			return;

		$args = array(
			'post_type'           => 'job_listing',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $per_page,
			'orderby'             => $orderby,
			'order'               => $order,
		);

		$args['tax_query'] = array(
			array(
				'taxonomy' => 'job_listing_tag',
				'field'    => 'slug',
				'terms'    => $tags
			)
		);

		if ( get_option( 'job_manager_hide_filled_positions' ) == 1 )
			$args['meta_query'] = array(
				array(
					'key'     => '_filled',
					'value'   => '1',
					'compare' => '!='
				)
			);

		$jobs = new WP_Query( apply_filters( 'job_manager_output_jobs_args', $args ) );

		if ( $jobs->have_posts() ) : ?>

			<ul class="job_listings">

				<?php while ( $jobs->have_posts() ) : $jobs->the_post(); ?>

					<?php get_job_manager_template_part( 'content', 'job_listing' ); ?>

				<?php endwhile; ?>

			</ul>

		<?php else :

			echo '<p>' . sprintf( __( 'No jobs found tagged with %s.', 'wp-job-manager-tags' ), implode( ', ', $tags ) ) . '</p>';

		endif;

		wp_reset_postdata();

		return '<div class="job_listings">' . ob_get_clean() . '</div>';
	}

	/**
	 * Job Tag cloud shortcode
	 */
	public function job_tag_cloud( $atts ) {
		ob_start();

		$atts = shortcode_atts( array(
			'smallest'                  => 8,
		    'largest'                   => 22,
		    'unit'                      => 'pt',
		    'number'                    => 45,
		    'format'                    => 'flat',
		    'separator'                 => "\n",
		    'orderby'                   => 'count',
		    'order'                     => 'DESC',
		    'exclude'                   => null,
		    'include'                   => null,
		    'link'                      => 'view',
		    'taxonomy'                  => 'job_listing_tag',
		    'echo'                      => false,
		    'topic_count_text_callback' => array( $this, 'tag_cloud_text_callback' )
		), $atts );

		$html = wp_tag_cloud( apply_filters( 'job_tag_cloud', $atts ) );

		if ( ! apply_filters( 'enable_job_tag_archives', get_option( 'job_manager_enable_tag_archive' ) ) )
			$html = str_replace( '</a>', '</span>', preg_replace( "/<a(.*)href='([^'']*)'(.*)>/", '<span$1$3>', $html ) );

		return $html;
	}

	/**
	 * tag_cloud_text_callback
	 */
	public function tag_cloud_text_callback( $count ) {
		return sprintf( _n( '%s job', '%s jobs', $count, 'wp-job-manager-tags' ), number_format_i18n( $count ) );
	}
}

new WP_Job_Manager_Job_Tags_Shortcodes();