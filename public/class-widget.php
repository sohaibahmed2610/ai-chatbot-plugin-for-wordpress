<?php
namespace AICB\Public_Facing;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Widget {

	private $settings;

	public function __construct() {
		$this->settings = get_option( 'ai_chatbot_settings', array() );
	}

	public function enqueue_scripts() {
		if ( empty( $this->settings['enabled'] ) ) {
			return;
		}

		wp_enqueue_style(
			'ai-chatbot-widget-style',
			AICB_PLUGIN_URL . 'public/css/widget.css',
			array(),
			AICB_VERSION,
			'all'
		);

		$custom_css = $this->generate_inline_css();
		wp_add_inline_style( 'ai-chatbot-widget-style', $custom_css );

		wp_enqueue_script(
			'ai-chatbot-widget-script',
			AICB_PLUGIN_URL . 'public/js/widget.js',
			array(),
			AICB_VERSION,
			true
		);

		wp_localize_script( 'ai-chatbot-widget-script', 'aicb_data', array(
			'rest_url' => esc_url_raw( rest_url( 'ai-chatbot/v1/chat' ) ),
			'nonce'    => wp_create_nonce( 'wp_rest' ),
			'bot_name' => esc_html( $this->settings['bot_name'] ?? 'AI Assistant' ),
			'welcome'  => esc_html( $this->settings['welcome_message'] ?? 'Hello!' ),
		) );
	}

	public function render_widget() {
		if ( empty( $this->settings['enabled'] ) ) {
			return;
		}
		
		require_once AICB_PLUGIN_DIR . 'public/views/widget-template.php';
	}

	private function generate_inline_css() {
		$s = $this->settings;

		$primary_color    = ! empty( $s['primary_color'] ) ? $s['primary_color'] : '#007cba';
		$bg_color         = ! empty( $s['background_color'] ) ? $s['background_color'] : '#ffffff';
		$text_color       = ! empty( $s['text_color'] ) ? $s['text_color'] : '#333333';
		$user_msg_color   = ! empty( $s['user_message_color'] ) ? $s['user_message_color'] : '#e0e0e0';
		$asst_msg_color   = ! empty( $s['assistant_message_color'] ) ? $s['assistant_message_color'] : '#f0f0f0';

		$position         = ! empty( $s['position'] ) ? $s['position'] : 'bottom-right';
		$h_offset         = isset( $s['horizontal_offset'] ) ? (int) $s['horizontal_offset'] : 20;
		$v_offset         = isset( $s['vertical_offset'] ) ? (int) $s['vertical_offset'] : 20;
		
		$width            = isset( $s['window_width'] ) ? (int) $s['window_width'] : 350;
		$height           = isset( $s['window_height'] ) ? (int) $s['window_height'] : 500;
		$border_radius    = isset( $s['border_radius'] ) ? (int) $s['border_radius'] : 12;

		$pos_css = $position === 'bottom-left' 
			? "left: {$h_offset}px; right: auto;" 
			: "right: {$h_offset}px; left: auto;";

		return "
			:root {
				--aicb-primary: {$primary_color};
				--aicb-bg: {$bg_color};
				--aicb-text: {$text_color};
				--aicb-user-msg: {$user_msg_color};
				--aicb-asst-msg: {$asst_msg_color};
				--aicb-width: {$width}px;
				--aicb-height: {$height}px;
				--aicb-radius: {$border_radius}px;
				--aicb-v-offset: {$v_offset}px;
			}
			.aicb-widget-container {
				bottom: var(--aicb-v-offset);
				{$pos_css}
			}
		";
	}
}
