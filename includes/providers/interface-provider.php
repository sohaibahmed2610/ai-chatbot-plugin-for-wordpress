<?php
namespace AICB\Providers;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

interface Provider {
	/**
	 * Send a message to the AI provider.
	 *
	 * @param string $message The user's new message.
	 * @param array  $history The history of the conversation (associative array).
	 * @return array|\WP_Error Associative array with 'reply' key, or WP_Error on failure.
	 */
	public function send_message( $message, $history = array() );
}
