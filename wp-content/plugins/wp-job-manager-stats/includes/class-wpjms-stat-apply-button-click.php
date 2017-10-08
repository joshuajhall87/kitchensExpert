<?php

/* Load Class */
WPJMS_Stat_Apply_Button_Click::get_instance();

/**
 * Stat: Unique Visits
 */
class WPJMS_Stat_Apply_Button_Click extends WPJMS_Stat{

	/**
	 * Returns the instance.
	 */
	public static function get_instance(){
		static $instance = null;
		if ( is_null( $instance ) ) $instance = new self;
		return $instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct(){

		/* Var */
		$this->post_types = array( 'job_listing' );
		$this->stat_id    = 'apply_button_click';
		$this->stat_label = __( 'Contact Clicks', 'wp-job-manager-stats' );
		$this->hook       = 'wp_enqueue_scripts';

		/* Load Parent Constructor */
		parent::__construct();

		/* Ajax Callback to update count */
		add_action( 'wp_ajax_wpjms_stat_apply_button_click', array( $this, 'update_stat_ajax' ) );
		add_action( 'wp_ajax_nopriv_wpjms_stat_apply_button_click', array( $this, 'update_stat_ajax' ) );
	}

	/**
	 * Update Stats on Apply Button CLick
	 */
	public function update_stat_value( $post_id ){

		/* Add script to count button click */
		wp_enqueue_script( 'wpjms-stat-apply-button-click', WPJMS_URL . 'assets/stats/stat-apply-button-click.js', array( 'jquery' ), WPJMS_VERSION, true );
		$ajax_data = array(
			'ajax_url'         => admin_url( 'admin-ajax.php' ),
			'ajax_nonce'       => wp_create_nonce( 'wpjms-stat_abc' ),
			'post_id'          => intval( $post_id ),
		);
		wp_localize_script( 'wpjms-stat-apply-button-click', 'wpjms_stat_abc', $ajax_data );
	}

	/**
	 * Update Stat Ajax
	 */
	public function update_stat_ajax(){

		/* Strip Slash */
		$request = stripslashes_deep( $_POST );

		/* Check Nonce */
		check_ajax_referer( 'wpjms-stat_abc', 'nonce' );

		/* Update Count */
		$post_ids = $this->get_cookie();
		$post_id = $request['post_id'];

		/* Cookie exist, bail */
		if ( in_array( $post_id, $post_ids ) ){
			echo json_encode( array( 'stat' => $this->stat_id, 'result' => 'cookie_already_set', 'cookie' => $post_ids ) );
			wp_die();
		}
		/* Update Stat */
		else{
			$update_stat = wpjms_update_stat_value( intval( $post_id ), $this->stat_id );
			if( $update_stat ){
				$this->add_cookie( $post_id );
				echo json_encode( array( 'stat' => $this->stat_id, 'result' => 'stat_updated', 'cookie' => $post_ids ) );
				wp_die();
			}
			else{
				echo json_encode( array( 'stat' => $this->stat_id, 'result' => 'stat_update_fail', 'cookie' => $post_ids ) );
				wp_die();
			}
		}
	}

}
