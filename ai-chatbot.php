<?php
/**
 * Plugin Name: AI Chatbot Plugin
 * Plugin URI:  https://github.com/sohaibahmed2610/ai-chatbot-plugin-for-wordpress
 * Description: A production-ready WordPress plugin to easily integrate AI chatbots (OpenAI, Gemini, Anthropic) into your website.
 * Version:     1.0.0
 * Author:      sohaibahmed2610
 * Author URI:  https://github.com/sohaibahmed2610
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ai-chatbot
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

define('AICB_VERSION', '1.0.0');
define('AICB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AICB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AICB_PLUGIN_BASENAME', plugin_basename(__FILE__));

require_once AICB_PLUGIN_DIR . 'admin/class-settings.php';
require_once AICB_PLUGIN_DIR . 'public/class-widget.php';
require_once AICB_PLUGIN_DIR . 'includes/api/class-rest-api.php';

require_once AICB_PLUGIN_DIR . 'includes/providers/interface-provider.php';
require_once AICB_PLUGIN_DIR . 'includes/providers/class-provider-factory.php';
require_once AICB_PLUGIN_DIR . 'includes/providers/class-openai-provider.php';
require_once AICB_PLUGIN_DIR . 'includes/providers/class-gemini-provider.php';
require_once AICB_PLUGIN_DIR . 'includes/providers/class-anthropic-provider.php';

require_once AICB_PLUGIN_DIR . 'core/class-plugin.php';

function run_aicb_plugin()
{
	$plugin = new \AICB\Core\Plugin();
	$plugin->run();
}
run_aicb_plugin();
