<?php
namespace AICB\Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 */
class Plugin {

	public function run() {
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_api_hooks();
	}

	private function define_admin_hooks() {
		$plugin_admin = new \AICB\Admin\Settings();
		add_action( 'admin_menu', array( $plugin_admin, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $plugin_admin, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );
	}

	private function define_public_hooks() {
		$plugin_public = new \AICB\Public_Facing\Widget();
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $plugin_public, 'render_widget' ) );
	}

	private function define_api_hooks() {
		$plugin_api = new \AICB\API\Rest_API();
		add_action( 'rest_api_init', array( $plugin_api, 'register_routes' ) );
	}
}
