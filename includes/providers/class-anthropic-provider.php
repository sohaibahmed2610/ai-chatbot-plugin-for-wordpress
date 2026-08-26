<?php
namespace AICB\Providers;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Anthropic_Provider implements Provider {

	private $api_key;
	private $model;
	private $temperature;
	private $max_tokens;
	private $system_prompt;

	public function __construct( $settings ) {
		$this->api_key       = $settings['api_key'];
		$this->model         = ! empty( $settings['model'] ) ? $settings['model'] : 'claude-3-5-sonnet-20240620';
		$this->temperature   = isset( $settings['temperature'] ) ? (float) $settings['temperature'] : 0.7;
		$this->max_tokens    = isset( $settings['max_tokens'] ) ? (int) $settings['max_tokens'] : 1000;
		$this->system_prompt = $settings['system_prompt'];
	}

	public function send_message( $message, $history = array() ) {
		$url = 'https://api.anthropic.com/v1/messages';

		$messages = array();
		
		if ( is_array( $history ) ) {
			foreach ( $history as $msg ) {
				if ( isset( $msg['role'] ) && isset( $msg['content'] ) ) {
					$messages[] = array(
						'role'    => sanitize_text_field( $msg['role'] ),
						'content' => sanitize_textarea_field( $msg['content'] ),
					);
				}
			}
		}

		$messages[] = array(
			'role'    => 'user',
			'content' => $message,
		);

		$body = array(
			'model'      => $this->model,
			'messages'   => $messages,
			'max_tokens' => $this->max_tokens,
			'temperature'=> $this->temperature,
		);

		if ( ! empty( $this->system_prompt ) ) {
			$body['system'] = $this->system_prompt;
		}

		$args = array(
			'body'        => wp_json_encode( $body ),
			'headers'     => array(
				'Content-Type'      => 'application/json',
				'x-api-key'         => $this->api_key,
				'anthropic-version' => '2023-06-01',
			),
			'timeout'     => 60,
			'data_format' => 'body',
		);

		$response = wp_remote_post( $url, $args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		$body        = wp_remote_retrieve_body( $response );
		$data        = json_decode( $body, true );

		if ( $status_code !== 200 ) {
			$error_message = isset( $data['error']['message'] ) ? $data['error']['message'] : 'Unknown Anthropic API error';
			return new \WP_Error( 'api_error', $error_message );
		}

		if ( isset( $data['content'][0]['text'] ) ) {
			return array( 'reply' => $data['content'][0]['text'] );
		}

		return new \WP_Error( 'invalid_response', __( 'Invalid response from Anthropic API.', 'ai-chatbot' ) );
	}
}
