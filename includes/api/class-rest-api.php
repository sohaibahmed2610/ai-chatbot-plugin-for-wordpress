<?php
namespace AICB\API;

use AICB\Providers\Provider_Factory;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Rest_API {

	public function register_routes() {
		register_rest_route( 'ai-chatbot/v1', '/chat', array(
			'methods'             => 'POST',
			'callback'            => array( $this, 'handle_chat_request' ),
			'permission_callback' => array( $this, 'verify_permission' ),
		) );
	}

	public function verify_permission( \WP_REST_Request $request ) {
		$nonce = $request->get_header( 'x_wp_nonce' );
		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			return new \WP_Error( 'forbidden', __( 'Invalid nonce.', 'ai-chatbot' ), array( 'status' => 403 ) );
		}
		
		return true;
	}

	public function handle_chat_request( \WP_REST_Request $request ) {
		$ip = $this->get_client_ip();
		$transient_name = 'aicb_rate_limit_' . md5( $ip );
		$requests_count = get_transient( $transient_name );

		if ( false === $requests_count ) {
			set_transient( $transient_name, 1, MINUTE_IN_SECONDS );
		} elseif ( $requests_count > 10 ) {
			return new \WP_Error( 'rate_limit_exceeded', __( 'Too many requests. Please slow down.', 'ai-chatbot' ), array( 'status' => 429 ) );
		} else {
			set_transient( $transient_name, $requests_count + 1, MINUTE_IN_SECONDS );
		}

		$message = $request->get_param( 'message' );
		$history = $request->get_param( 'history' );

		if ( empty( $message ) ) {
			return new \WP_Error( 'empty_message', __( 'Message cannot be empty.', 'ai-chatbot' ), array( 'status' => 400 ) );
		}

		$message = sanitize_text_field( $message );
		
		if ( ! is_array( $history ) ) {
			$history = array();
		}

		$provider = Provider_Factory::get_provider();

		if ( is_wp_error( $provider ) ) {
			return $provider;
		}

		$response = $provider->send_message( $message, $history );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return rest_ensure_response( array(
			'success' => true,
			'data'    => $response
		) );
	}

	private function get_client_ip() {
		$ip = '';
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}
