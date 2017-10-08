<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WPJMEL_Settings.
 * @version    1.0.0
 * @author     Astoundify
 */
class WPJMEL_Settings {


	/**
	 * Constructor.
	 * @since 1.0.0
	 */
	public function __construct() {

		/* Add Settings Tab */
		add_action( 'job_manager_settings', array( $this, 'settings' ) );

		/* Register Settings */
		add_action( 'admin_init', array( $this, 'register_geo_settings' ) );

		/* Sanitize Options */
		add_filter( 'sanitize_option_wpjmel_enable_city_suggest', array( $this, 'sanitize_checkbox' ) );
		add_filter( 'sanitize_option_wpjmel_enable_map', array( $this, 'sanitize_checkbox' ) );
		add_filter( 'sanitize_option_wpjmel_google_maps_api_key', 'esc_attr' );
		add_filter( 'sanitize_option_wpjmel_start_geo_lat', 'esc_attr' );
		add_filter( 'sanitize_option_wpjmel_start_geo_long', 'esc_attr' );

		/* Scripts */
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}


	/**
	 * Register Settings.
	 * This input is added via JS.
	 * @since 2.0.0
	 */
	public function register_geo_settings() {
		register_setting( 'job_manager', 'wpjmel_start_geo_lat' );
		register_setting( 'job_manager', 'wpjmel_start_geo_long' );
	}


	/**
	 * Settings page.
	 * Add an settings tab to the Listings -> settings page.
	 *
	 * @since   1.0.0
	 * @param   array    $settings   Array of default settings.
	 * @return  array    $settings   Array including the new settings.
	 */
	public function settings( $settings )  {

		$settings['wpjmel_settings'] = array(
			__( 'Location Settings', 'wp-job-manager-extended-location' ),
			array(
				array(
					'name'          => 'wpjmel_enable_city_suggest',
					'type'          => 'checkbox',
					'label'         => __( 'Auto Location', 'wp-job-manager-extended-location' ),
					'cb_label'      => __( 'User Location Suggestion', 'wp-job-manager-extended-location' ),
					'desc'          => __( 'Attempt to automatically locate the current user&#39;s location to display location-specific results.', 'wp-job-manager-extended-location' ),
					'std'           => 1,
				),
				array(
					'name'          => 'wpjmel_enable_map',
					'type'          => 'checkbox',
					'label'         => __( 'Submission Form', 'wp-job-manager-extended-location' ),
					'cb_label'      => __( 'Display Map', 'wp-job-manager-extended-location' ),
					'desc'          => __( 'When checked there will be a small Google Map positioned beneath the location field.', 'wp-job-manager-extended-location' ),
					'std'           => 1,
				),
				array(
					'name'          => 'wpjmel_map_start_location',
					'type'          => 'text',
					'label'         => __( 'Default Location', 'wp-job-manager-extended-location' ),
					'desc'          => __( 'The start location if the map is enabled', 'wp-job-manager-extended-location' ),
					'std'           => '',
				),
				array(
					'name'          => 'wpjmel_google_maps_api_key',
					'type'          => 'text',
					'label'         => __( 'Google Maps API Key', 'wp-job-manager-extended-location' ),
					'desc'          => __( 'Enter your Google Maps API Key for more accurate results.', 'wp-job-manager-extended-location' ),
					'std'           => '',
				)
			),
		);
		return $settings;
	}


	/**
	 * Admin scripts.
	 * @since 3.0.0
	 */
	public function scripts( $hook_suffix ){

		/* Settings Page */
		if( 'job_listing_page_job-manager-settings' == $hook_suffix ){
			$wpjmel = WP_Job_Manager_Extended_Location::instance();

			wp_enqueue_style( 'wpjmel-settings', $wpjmel->url . 'assets/settings/settings.css', array( 'mapify' ), $wpjmel->version );
			wp_enqueue_script( 'wpjmel-settings', $wpjmel->url . 'assets/settings/settings.js', array( 'jquery', 'google-maps', 'mapify' ), $wpjmel->version, true );

			$options = array(
				'lat'         => esc_attr( get_option( 'wpjmel_start_geo_lat', 40.712784 ) ),
				'lng'         => esc_attr( get_option( 'wpjmel_start_geo_long', -74.005941 ) ),
				'lat_input'   => 'wpjmel_start_geo_lat',
				'lng_input'   => 'wpjmel_start_geo_long',
			);
			wp_localize_script( 'wpjmel-settings', 'wpjmel', $options );
		}
	}


	/**
	 * Sanitize Checkbox
	 * @since 3.0.0
	 */
	public function sanitize_checkbox( $input ){
		return $input ? 1 : 0;
	}

}
