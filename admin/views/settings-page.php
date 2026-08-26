<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$option_name = 'ai_chatbot_settings';
$settings = get_option( $option_name, array() );

$defaults = array(
	'enabled'         => false,
	'bot_name'        => 'AI Assistant',
	'welcome_message' => 'Hello! How can I help you today?',
	'provider'        => 'openai',
	'api_key'         => '',
	'model'           => 'gpt-4o-mini',
	'temperature'     => 0.7,
	'max_tokens'      => 1000,
	'system_prompt'   => 'You are a helpful assistant for this website.',
	'icon_url'        => '',
	'primary_color'   => '#007cba',
	'background_color'=> '#ffffff',
	'text_color'      => '#333333',
	'user_message_color' => '#e0e0e0',
	'assistant_message_color' => '#f0f0f0',
	'position'        => 'bottom-right',
	'horizontal_offset'=> 20,
	'vertical_offset'  => 20,
	'window_width'    => 350,
	'window_height'   => 500,
	'border_radius'   => 12,
);

$settings = wp_parse_args( $settings, $defaults );
?>

<div class="wrap ai-chatbot-settings-wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form method="post" action="options.php">
		<?php
			settings_fields( 'ai_chatbot_options_group' );
		?>

		<div class="aicb-tabs-container">
			<ul class="aicb-tabs">
				<li class="aicb-tab active" data-tab="general"><?php esc_html_e( 'General', 'ai-chatbot' ); ?></li>
				<li class="aicb-tab" data-tab="provider"><?php esc_html_e( 'AI Provider', 'ai-chatbot' ); ?></li>
				<li class="aicb-tab" data-tab="instructions"><?php esc_html_e( 'Instructions', 'ai-chatbot' ); ?></li>
				<li class="aicb-tab" data-tab="appearance"><?php esc_html_e( 'Appearance', 'ai-chatbot' ); ?></li>
				<li class="aicb-tab" data-tab="position"><?php esc_html_e( 'Position & Size', 'ai-chatbot' ); ?></li>
			</ul>

			<div class="aicb-tab-content active" id="tab-general">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable Chatbot', 'ai-chatbot' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="<?php echo esc_attr( $option_name ); ?>[enabled]" value="1" <?php checked( $settings['enabled'], true ); ?> />
								<?php esc_html_e( 'Show chatbot on the frontend', 'ai-chatbot' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Bot Name', 'ai-chatbot' ); ?></th>
						<td>
							<input type="text" class="regular-text" name="<?php echo esc_attr( $option_name ); ?>[bot_name]" value="<?php echo esc_attr( $settings['bot_name'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Welcome Message', 'ai-chatbot' ); ?></th>
						<td>
							<textarea rows="3" class="large-text" name="<?php echo esc_attr( $option_name ); ?>[welcome_message]"><?php echo esc_textarea( $settings['welcome_message'] ); ?></textarea>
						</td>
					</tr>
				</table>
			</div>

			<div class="aicb-tab-content" id="tab-provider">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'AI Provider', 'ai-chatbot' ); ?></th>
						<td>
							<select name="<?php echo esc_attr( $option_name ); ?>[provider]" id="aicb_provider">
								<option value="openai" <?php selected( $settings['provider'], 'openai' ); ?>><?php esc_html_e( 'OpenAI', 'ai-chatbot' ); ?></option>
								<option value="gemini" <?php selected( $settings['provider'], 'gemini' ); ?>><?php esc_html_e( 'Google Gemini', 'ai-chatbot' ); ?></option>
								<option value="anthropic" <?php selected( $settings['provider'], 'anthropic' ); ?>><?php esc_html_e( 'Anthropic', 'ai-chatbot' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'API Key', 'ai-chatbot' ); ?></th>
						<td>
							<input type="password" class="regular-text" name="<?php echo esc_attr( $option_name ); ?>[api_key]" value="<?php echo esc_attr( $settings['api_key'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Model', 'ai-chatbot' ); ?></th>
						<td>
							<select name="<?php echo esc_attr( $option_name ); ?>[model]" id="aicb_model" data-selected="<?php echo esc_attr( $settings['model'] ); ?>">
								<!-- Options populated via JS -->
							</select>
							<p class="description"><?php esc_html_e( 'Select the specific model for the chosen provider.', 'ai-chatbot' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Temperature', 'ai-chatbot' ); ?></th>
						<td>
							<input type="number" step="0.1" min="0" max="2" name="<?php echo esc_attr( $option_name ); ?>[temperature]" value="<?php echo esc_attr( $settings['temperature'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Max Tokens', 'ai-chatbot' ); ?></th>
						<td>
							<input type="number" step="1" name="<?php echo esc_attr( $option_name ); ?>[max_tokens]" value="<?php echo esc_attr( $settings['max_tokens'] ); ?>" />
						</td>
					</tr>
				</table>
			</div>

			<div class="aicb-tab-content" id="tab-instructions">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'System Prompt / Instructions', 'ai-chatbot' ); ?></th>
						<td>
							<textarea rows="10" class="large-text" name="<?php echo esc_attr( $option_name ); ?>[system_prompt]"><?php echo esc_textarea( $settings['system_prompt'] ); ?></textarea>
							<p class="description"><?php esc_html_e( 'These are the instructions the AI will follow. Describe its persona, knowledge, and restrictions here.', 'ai-chatbot' ); ?></p>
						</td>
					</tr>
				</table>
			</div>

			<div class="aicb-tab-content" id="tab-appearance">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Custom Icon URL', 'ai-chatbot' ); ?></th>
						<td>
							<input type="text" class="regular-text" name="<?php echo esc_attr( $option_name ); ?>[icon_url]" id="aicb_icon_url" value="<?php echo esc_attr( $settings['icon_url'] ); ?>" />
							<button type="button" class="button" id="aicb_upload_icon_button"><?php esc_html_e( 'Upload / Select Image', 'ai-chatbot' ); ?></button>
							<p class="description"><?php esc_html_e( 'Leave blank to use the default chat icon.', 'ai-chatbot' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Primary Color', 'ai-chatbot' ); ?></th>
						<td>
							<input type="text" class="aicb-color-picker" name="<?php echo esc_attr( $option_name ); ?>[primary_color]" value="<?php echo esc_attr( $settings['primary_color'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Background Color', 'ai-chatbot' ); ?></th>
						<td>
							<input type="text" class="aicb-color-picker" name="<?php echo esc_attr( $option_name ); ?>[background_color]" value="<?php echo esc_attr( $settings['background_color'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Text Color', 'ai-chatbot' ); ?></th>
						<td>
							<input type="text" class="aicb-color-picker" name="<?php echo esc_attr( $option_name ); ?>[text_color]" value="<?php echo esc_attr( $settings['text_color'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'User Message Color', 'ai-chatbot' ); ?></th>
						<td>
							<input type="text" class="aicb-color-picker" name="<?php echo esc_attr( $option_name ); ?>[user_message_color]" value="<?php echo esc_attr( $settings['user_message_color'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Assistant Message Color', 'ai-chatbot' ); ?></th>
						<td>
							<input type="text" class="aicb-color-picker" name="<?php echo esc_attr( $option_name ); ?>[assistant_message_color]" value="<?php echo esc_attr( $settings['assistant_message_color'] ); ?>" />
						</td>
					</tr>
				</table>
			</div>

			<div class="aicb-tab-content" id="tab-position">
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Position', 'ai-chatbot' ); ?></th>
						<td>
							<select name="<?php echo esc_attr( $option_name ); ?>[position]">
								<option value="bottom-right" <?php selected( $settings['position'], 'bottom-right' ); ?>><?php esc_html_e( 'Bottom Right', 'ai-chatbot' ); ?></option>
								<option value="bottom-left" <?php selected( $settings['position'], 'bottom-left' ); ?>><?php esc_html_e( 'Bottom Left', 'ai-chatbot' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Horizontal Offset (px)', 'ai-chatbot' ); ?></th>
						<td>
							<input type="number" step="1" name="<?php echo esc_attr( $option_name ); ?>[horizontal_offset]" value="<?php echo esc_attr( $settings['horizontal_offset'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Vertical Offset (px)', 'ai-chatbot' ); ?></th>
						<td>
							<input type="number" step="1" name="<?php echo esc_attr( $option_name ); ?>[vertical_offset]" value="<?php echo esc_attr( $settings['vertical_offset'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Window Width (px)', 'ai-chatbot' ); ?></th>
						<td>
							<input type="number" step="1" name="<?php echo esc_attr( $option_name ); ?>[window_width]" value="<?php echo esc_attr( $settings['window_width'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Window Height (px)', 'ai-chatbot' ); ?></th>
						<td>
							<input type="number" step="1" name="<?php echo esc_attr( $option_name ); ?>[window_height]" value="<?php echo esc_attr( $settings['window_height'] ); ?>" />
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Border Radius (px)', 'ai-chatbot' ); ?></th>
						<td>
							<input type="number" step="1" name="<?php echo esc_attr( $option_name ); ?>[border_radius]" value="<?php echo esc_attr( $settings['border_radius'] ); ?>" />
						</td>
					</tr>
				</table>
			</div>
		</div>

		<?php submit_button(); ?>
	</form>
</div>
