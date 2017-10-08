<?php
/**
 * Install
 *
 * @since 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPJMCL_Install {

	/**
	 * Install
	 *
	 * @since 2.5.0
	 */
	public static function install() {
		WPJMCL_Post_Types::register_post_types();
	}

}
