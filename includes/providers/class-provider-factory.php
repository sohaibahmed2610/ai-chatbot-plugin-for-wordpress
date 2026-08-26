<?php
namespace AICB\Providers;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Provider_Factory {

	/**
	 * Get the instantiated provider based on settings.
	 *
	 * @return Provider|\WP_Error
	 */
	public static function get_provider() {
		$settings = get_option( 'ai_chatbot_settings', array() );
		
		if ( empty( $settings['provider'] ) || empty( $settings['api_key'] ) ) {
			return new \WP_Error( 'missing_config', __( 'AI Provider or API Key is not configured.', 'ai-chatbot' ) );
		}

		$provider_name = $settings['provider'];

		switch ( $provider_name ) {
			case 'openai':
				return new OpenAI_Provider( $settings );
			case 'gemini':
				return new Gemini_Provider( $settings );
			case 'anthropic':
				return new Anthropic_Provider( $settings );
			default:
				return new \WP_Error( 'invalid_provider', __( 'Invalid AI Provider configured.', 'ai-chatbot' ) );
		}
	}
}
