<?php
/**
 * Plugin Name: WP Job Manager - Cutom Tags Field
 * Description: Cutom tags field type for WP Job Manager Field Editor.
 * Author:      Kybarg
 * Version:     1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Wp_Job_Manager_Cutom_Tags_Field {

	/**
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Make sure only one instance is only running.
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param void
	 * @return object $instance The one true class instance.
	 */
	public static function instance() {
		if ( ! isset ( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Start things up.
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param void
	 * @return void
	 */
	public function __construct() {
		$this->setup_globals();
		$this->setup_actions();
	}

	/**
	 * Set some smart defaults to class variables.
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param void
	 * @return void
	 */
	private function setup_globals() {
		$this->file         = __FILE__;
		
		$this->basename     = plugin_basename( $this->file );
		$this->plugin_dir   = plugin_dir_path( $this->file );
		$this->plugin_url   = plugin_dir_url ( $this->file ); 
	}

	/**
	 * Hooks and filters.
	 *
	 * We need to hook into a couple of things:
	 * 1. Output fields on frontend, and save.
	 * 2. Output fields on backend, and save (done automatically).
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param void
	 * @return void
	 */
	private function setup_actions() {
		/**
		 * Filter the default fields that ship with WP Job Manager.
		 * The `form_fields` method is what we use to add our own custom fields.
		 */
		add_filter( 'submit_job_form_fields', array( $this, 'form_fields' ), 1000, 1 );
	}

	/**
	 * Add fields to the submission form.
	 *
	 * Currently the fields must fall between two sections: "job" or "company". Until
	 * WP Job Manager filters the data that passes to the registration template, these are the
	 * only two sections we can manipulate.
	 *
	 * You may use a custom field type, but you will then need to filter the `job_manager_locate_template`
	 * to search in `/templates/form-fields/$type-field.php` in your theme or plugin.
	 *
	 * @since Custom fields for WP Job Manager 1.0
	 *
	 * @param array $fields The existing fields
	 * @return array $fields The modified fields
	 */
	function form_fields( $fields ) {
		if(isset($fields[ 'job' ]) && isset($fields[ 'job' ][ 'job_tags' ])) {
			if(!isset($fields[ 'job' ][ 'job_tags' ][ 'description' ]) || empty($fields[ 'job' ][ 'job_tags' ][ 'description' ]))
				$fields[ 'job' ][ 'job_tags' ][ 'description' ] = '';
				


			$fields[ 'job' ][ 'job_tags' ][ 'description' ] .= file_get_contents( $this->plugin_dir . 'template.html' );
		}

		/**
		 * Repeat this for any additional fields.
		 */
		return $fields;
	}

}
add_action( 'init', array( 'Wp_Job_Manager_Cutom_Tags_Field', 'instance' ) );
