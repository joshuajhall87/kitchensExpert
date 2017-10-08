<?php

/**
 * Abstract WPJMS_Stat class.
 * @abstract
 */
abstract class WPJMS_Stat{

	/* Vars */
	public $post_types      = array();
	public $stat_id         = '';
	public $stat_label      = '';
	public $hook            = 'wp';
	public $cookie_id       = 'wp_job_manager_stats';
	public $cookie_name     = '';

	/**
	 * Constructor.
	 */
	public function __construct() {

		/* Register Stat */
		add_filter( 'wpjms_stats', array( $this, 'register_stat' ) );

		/* Update Stats */
		add_action( $this->hook, array( $this, 'trigger' ) );
	}

	/**
	 * Register Stat
	 */
	public function register_stat( $stats ){
		$stats[$this->stat_id] = array(
			'id'     => $this->stat_id,
			'label'  => $this->stat_label,
		);
		return $stats;
	}

	/**
	 * Trigger Stat
	 */
	public function trigger(){
		if( is_singular( $this->post_types ) ){
			$post_id         = intval( get_queried_object_id() );
			$post_type       = get_post_type( $post_id );
			$author_id       = get_post_field( 'post_author', $post_id );
			$current_user_id = get_current_user_id();
			if( in_array( $post_type, $this->post_types ) && $author_id != $current_user_id ){
				$this->update_stat_value( $post_id );
			}
		}
	}

	/**
	 * Update Stat
	 */
	public function update_stat_value( $post_id ){
		wpjms_update_stat_value( $post_id, $this->stat_id );
	}

	/**
	 * Get Cookie
	 * this will return array of post ids of set cookie.
	 * @return array
	 */
	public function get_cookie(){
		$cookie_id = $this->cookie_id;
		$cookie_name = $this->cookie_name ? $this->cookie_name : $this->stat_id;
		$cookie_value = array();
		if( isset( $_COOKIE[$cookie_id] ) && !empty( $_COOKIE[$cookie_id] ) ){
			$stats_cookie_value = json_decode( stripslashes( $_COOKIE[$cookie_id] ), true );
			if( isset( $stats_cookie_value[$cookie_name] ) && is_array( $stats_cookie_value[$cookie_name] ) ){
				$cookie_value = $stats_cookie_value[$cookie_name];
			}
		}
		return $cookie_value;
	}

	/**
	 * Add Post ID in Stat Cookie
	 */
	public function add_cookie( $post_id ){
		$post_id = intval( $post_id );
		$expiration  = intval( apply_filters( $this->stat_id . '_cookie_expiration', DAY_IN_SECONDS ) );
		$cookie_id = $this->cookie_id;
		$cookie_name = $this->cookie_name ? $this->cookie_name : $this->stat_id;
		$stats_cookie_value = array();
		if( isset( $_COOKIE[$cookie_id] ) && !empty( $_COOKIE[$cookie_id] ) ){
			$stats_cookie_value = json_decode( stripslashes( $_COOKIE[$cookie_id] ), true );
		}
		$stats_cookie_value[$cookie_name][$post_id] = $post_id;
		setcookie( $cookie_id, json_encode( $stats_cookie_value ), time() + $expiration );
	}

}
