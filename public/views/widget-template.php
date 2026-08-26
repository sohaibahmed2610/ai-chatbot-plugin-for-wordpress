<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$icon_url = ! empty( $this->settings['icon_url'] ) ? $this->settings['icon_url'] : '';
?>

<div class="aicb-widget-container" id="aicb-widget-container">
	<!-- Chat Window -->
	<div class="aicb-chat-window" id="aicb-chat-window" style="display: none;">
		<div class="aicb-chat-header">
			<span class="aicb-bot-name"><?php echo esc_html( $this->settings['bot_name'] ); ?></span>
			<button class="aicb-close-btn" id="aicb-close-btn" aria-label="Close Chat">&times;</button>
		</div>
		
		<div class="aicb-chat-messages" id="aicb-chat-messages">
			<!-- Messages will be injected here via JS -->
		</div>
		
		<div class="aicb-chat-input-area">
			<textarea id="aicb-chat-input" placeholder="<?php esc_attr_e( 'Type your message...', 'ai-chatbot' ); ?>" rows="1"></textarea>
			<button id="aicb-send-btn" aria-label="Send">
				<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
			</button>
		</div>
	</div>

	<!-- Floating Toggle Button -->
	<button class="aicb-toggle-btn" id="aicb-toggle-btn" aria-label="Toggle Chat">
		<?php if ( $icon_url ) : ?>
			<img src="<?php echo esc_url( $icon_url ); ?>" alt="Chat Icon" />
		<?php else : ?>
			<!-- Default Icon SVG -->
			<svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
		<?php endif; ?>
	</button>
</div>
