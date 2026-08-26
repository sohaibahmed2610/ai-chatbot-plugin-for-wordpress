(function($) {
	'use strict';

	$(document).ready(function() {
		$('.aicb-color-picker').wpColorPicker();

		$('.aicb-tab').on('click', function() {
			var tab_id = $(this).attr('data-tab');

			$('.aicb-tab').removeClass('active');
			$('.aicb-tab-content').removeClass('active');

			$(this).addClass('active');
			$('#tab-' + tab_id).addClass('active');
		});

		var providerSelect = $('#aicb_provider');
		var modelSelect = $('#aicb_model');
		var selectedModel = modelSelect.data('selected');

		var aiModels = {
			'openai': [
				{ value: 'gpt-4o', label: 'GPT-4o' },
				{ value: 'gpt-4o-mini', label: 'GPT-4o Mini' },
				{ value: 'gpt-4-turbo', label: 'GPT-4 Turbo' },
				{ value: 'gpt-3.5-turbo', label: 'GPT-3.5 Turbo' }
			],
			'gemini': [
				{ value: 'gemini-3.1-flash', label: 'Gemini 3.1 Flash' },
				{ value: 'gemini-3.1-flash-lite', label: 'Gemini 3.1 Flash Lite' },
				{ value: 'gemini-3.5-flash', label: 'Gemini 3.5 Flash' },
				{ value: 'gemini-3.5-flash-lite', label: 'Gemini 3.5 Flash Lite' },
				{ value: 'gemini-3.6-flash', label: 'Gemini 3.6 Flash' },
				{ value: 'gemini-3.7-flash', label: 'Gemini 3.7 Flash' }
			],
			'anthropic': [
				{ value: 'claude-3-5-sonnet-20240620', label: 'Claude 3.5 Sonnet' },
				{ value: 'claude-3-opus-20240229', label: 'Claude 3 Opus' },
				{ value: 'claude-3-sonnet-20240229', label: 'Claude 3 Sonnet' },
				{ value: 'claude-3-haiku-20240307', label: 'Claude 3 Haiku' }
			]
		};

		function updateModelDropdown() {
			var provider = providerSelect.val();
			var models = aiModels[provider] || [];
			
			modelSelect.empty();
			
			$.each(models, function(index, model) {
				var option = $('<option></option>').attr('value', model.value).text(model.label);
				if (model.value === selectedModel) {
					option.prop('selected', true);
				}
				modelSelect.append(option);
			});
		}

		providerSelect.on('change', function() {
			selectedModel = ''; // Reset selected model when provider changes so it defaults to first
			updateModelDropdown();
		});

		// Initialize on load
		if (providerSelect.length && modelSelect.length) {
			updateModelDropdown();
		}

		var mediaUploader;
		$('#aicb_upload_icon_button').on('click', function(e) {
			e.preventDefault();
			
			if (mediaUploader) {
				mediaUploader.open();
				return;
			}

			mediaUploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Chatbot Icon',
				button: {
					text: 'Choose Icon'
				},
				multiple: false
			});

			mediaUploader.on('select', function() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				$('#aicb_icon_url').val(attachment.url);
			});

			mediaUploader.open();
		});
	});

})(jQuery);
