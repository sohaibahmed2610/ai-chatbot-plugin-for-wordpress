<?php
namespace AICB\Providers;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Gemini_Provider implements Provider {

	private $api_key;
	private $model;
	private $temperature;
	private $max_tokens;
	private $system_prompt;

	public function __construct( $settings ) {
		$this->api_key       = $settings['api_key'];
		$this->model         = ! empty( $settings['model'] ) ? $settings['model'] : 'gemini-1.5-pro';
		$this->temperature   = isset( $settings['temperature'] ) ? (float) $settings['temperature'] : 0.7;
		$this->max_tokens    = isset( $settings['max_tokens'] ) ? (int) $settings['max_tokens'] : 1000;
		$this->system_prompt = $settings['system_prompt'];
	}

	public function send_message( $message, $history = array() ) {
		$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $this->model . ':generateContent?key=' . $this->api_key;

		$contents = array();

		if ( is_array( $history ) ) {
			foreach ( $history as $msg ) {
				if ( isset( $msg['role'] ) && isset( $msg['content'] ) ) {
					$role = sanitize_text_field( $msg['role'] );
					if ( $role === 'assistant' ) {
						$role = 'model';
					}
					$contents[] = array(
						'role'  => $role,
						'parts' => array( array( 'text' => sanitize_textarea_field( $msg['content'] ) ) ),
					);
				}
			}
		}

		$contents[] = array(
			'role'  => 'user',
			'parts' => array( array( 'text' => $message ) ),
		);

		$body = array(
			'contents' => $contents,
			'generationConfig' => array(
				'temperature'     => $this->temperature,
				'maxOutputTokens' => $this->max_tokens,
			)
		);

		if ( ! empty( $this->system_prompt ) ) {
			$body['systemInstruction'] = array(
				'parts' => array( array( 'text' => $this->system_prompt ) )
			);
		}

		$args = array(
			'body'        => wp_json_encode( $body ),
			'headers'     => array(
				'Content-Type' => 'application/json',
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
			$error_message = isset( $data['error']['message'] ) ? $data['error']['message'] : 'Unknown Gemini API error';
			return new \WP_Error( 'api_error', $error_message );
		}

		if ( isset( $data['candidates'][0]['content']['parts'][0]['text'] ) ) {
			return array( 'reply' => $data['candidates'][0]['content']['parts'][0]['text'] );
		}

		return new \WP_Error( 'invalid_response', __( 'Invalid response from Gemini API.', 'ai-chatbot' ) );
	}
}
