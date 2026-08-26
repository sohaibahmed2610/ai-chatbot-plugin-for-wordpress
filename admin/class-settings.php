<?php
namespace AICB\Admin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Settings {

	private $option_name = 'ai_chatbot_settings';

	public function add_plugin_admin_menu() {
		add_menu_page(
			__( 'AI Chatbot Settings', 'ai-chatbot' ),
			__( 'AI Chatbot', 'ai-chatbot' ),
			'manage_options',
			'ai-chatbot',
			array( $this, 'display_plugin_setup_page' ),
			'dashicons-format-chat',
			80
		);
	}

	public function register_settings() {
		register_setting( 'ai_chatbot_options_group', $this->option_name, array( $this, 'sanitize' ) );
	}

	public function enqueue_scripts( $hook ) {
		if ( 'toplevel_page_ai-chatbot' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_media();

		wp_enqueue_style(
			'ai-chatbot-admin-style',
			AICB_PLUGIN_URL . 'admin/css/admin.css',
			array(),
			AICB_VERSION,
			'all'
		);

		wp_enqueue_script(
			'ai-chatbot-admin-script',
			AICB_PLUGIN_URL . 'admin/js/admin.js',
			array( 'jquery', 'wp-color-picker' ),
			AICB_VERSION,
			true
		);
	}

	public function sanitize( $input ) {
		$sanitized_input = array();

		$sanitized_input['enabled']         = isset( $input['enabled'] ) ? (bool) $input['enabled'] : false;
		$sanitized_input['bot_name']        = isset( $input['bot_name'] ) ? sanitize_text_field( $input['bot_name'] ) : '';
		$sanitized_input['welcome_message'] = isset( $input['welcome_message'] ) ? sanitize_textarea_field( $input['welcome_message'] ) : '';

		$sanitized_input['provider']    = isset( $input['provider'] ) ? sanitize_text_field( $input['provider'] ) : 'openai';
		$sanitized_input['api_key']     = isset( $input['api_key'] ) ? sanitize_text_field( $input['api_key'] ) : '';
		$sanitized_input['model']       = isset( $input['model'] ) ? sanitize_text_field( $input['model'] ) : '';
		$sanitized_input['temperature'] = isset( $input['temperature'] ) ? floatval( $input['temperature'] ) : 0.7;
		$sanitized_input['max_tokens']  = isset( $input['max_tokens'] ) ? intval( $input['max_tokens'] ) : 1000;

		$sanitized_input['system_prompt'] = isset( $input['system_prompt'] ) ? sanitize_textarea_field( $input['system_prompt'] ) : '';

		$sanitized_input['icon_url']               = isset( $input['icon_url'] ) ? sanitize_url( $input['icon_url'] ) : '';
		$sanitized_input['primary_color']          = isset( $input['primary_color'] ) ? sanitize_hex_color( $input['primary_color'] ) : '#007cba';
		$sanitized_input['background_color']       = isset( $input['background_color'] ) ? sanitize_hex_color( $input['background_color'] ) : '#ffffff';
		$sanitized_input['text_color']             = isset( $input['text_color'] ) ? sanitize_hex_color( $input['text_color'] ) : '#333333';
		$sanitized_input['user_message_color']     = isset( $input['user_message_color'] ) ? sanitize_hex_color( $input['user_message_color'] ) : '#e0e0e0';
		$sanitized_input['assistant_message_color']= isset( $input['assistant_message_color'] ) ? sanitize_hex_color( $input['assistant_message_color'] ) : '#f0f0f0';

		$sanitized_input['position']        = isset( $input['position'] ) ? sanitize_text_field( $input['position'] ) : 'bottom-right';
		$sanitized_input['horizontal_offset']= isset( $input['horizontal_offset'] ) ? intval( $input['horizontal_offset'] ) : 20;
		$sanitized_input['vertical_offset']  = isset( $input['vertical_offset'] ) ? intval( $input['vertical_offset'] ) : 20;
		$sanitized_input['window_width']    = isset( $input['window_width'] ) ? intval( $input['window_width'] ) : 350;
		$sanitized_input['window_height']   = isset( $input['window_height'] ) ? intval( $input['window_height'] ) : 500;
		$sanitized_input['border_radius']   = isset( $input['border_radius'] ) ? intval( $input['border_radius'] ) : 12;

		return $sanitized_input;
	}

	public function display_plugin_setup_page() {
		require_once AICB_PLUGIN_DIR . 'admin/views/settings-page.php';
	}
}
