(function() {
	'use strict';

	document.addEventListener('DOMContentLoaded', function() {
		const toggleBtn = document.getElementById('aicb-toggle-btn');
		const closeBtn  = document.getElementById('aicb-close-btn');
		const chatWin   = document.getElementById('aicb-chat-window');
		const input     = document.getElementById('aicb-chat-input');
		const sendBtn   = document.getElementById('aicb-send-btn');
		const msgArea   = document.getElementById('aicb-chat-messages');

		if (!toggleBtn || !chatWin || !input || !sendBtn || !msgArea) return;

		let history = [];
		let isWaiting = false;

		const sessionHistory = sessionStorage.getItem('aicb_chat_history');
		if (sessionHistory) {
			try {
				history = JSON.parse(sessionHistory);
				renderHistory();
			} catch(e) {
				history = [];
			}
		}

		if (history.length === 0 && aicb_data.welcome) {
			appendMessage('assistant', aicb_data.welcome, false);
		}

		function toggleChat() {
			if (chatWin.style.display === 'none' || chatWin.style.display === '') {
				chatWin.style.display = 'flex';
				input.focus();
			} else {
				chatWin.style.display = 'none';
			}
		}

		toggleBtn.addEventListener('click', toggleChat);
		closeBtn.addEventListener('click', toggleChat);

		input.addEventListener('keydown', function(e) {
			if (e.key === 'Enter' && !e.shiftKey) {
				e.preventDefault();
				sendMessage();
			}
		});

		sendBtn.addEventListener('click', sendMessage);

		function sendMessage() {
			const text = input.value.trim();
			if (!text || isWaiting) return;

			input.value = '';
			input.style.height = 'auto'; // Reset height

			appendMessage('user', text, true);
			isWaiting = true;
			sendBtn.disabled = true;

			const typingIndicator = document.createElement('div');
			typingIndicator.className = 'aicb-typing-indicator';
			typingIndicator.innerHTML = '<div class="aicb-dot"></div><div class="aicb-dot"></div><div class="aicb-dot"></div>';
			msgArea.appendChild(typingIndicator);
			scrollToBottom();

			const data = {
				message: text,
				history: history
			};

			fetch(aicb_data.rest_url, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': aicb_data.nonce
				},
				body: JSON.stringify(data)
			})
			.then(response => response.json())
			.then(res => {
				msgArea.removeChild(typingIndicator);
				isWaiting = false;
				sendBtn.disabled = false;

				if (res.success && res.data && res.data.reply) {
					history.push({ role: 'user', content: text });
					history.push({ role: 'assistant', content: res.data.reply });
					sessionStorage.setItem('aicb_chat_history', JSON.stringify(history));
					
					appendMessage('assistant', res.data.reply, false);
				} else {
					let errorText = 'Sorry, an error occurred.';
					if (res.message) {
						errorText = res.message;
					} else if (res.data && res.data.message) {
						errorText = res.data.message;
					}
					appendMessage('error', errorText, false);
				}
				input.focus();
			})
			.catch(err => {
				msgArea.removeChild(typingIndicator);
				isWaiting = false;
				sendBtn.disabled = false;
				appendMessage('error', 'Network error. Please try again.', false);
			});
		}

		function appendMessage(role, text, isNew) {
			const msgDiv = document.createElement('div');
			msgDiv.className = 'aicb-message aicb-' + role;
			
			msgDiv.innerHTML = escapeHtml(text).replace(/\n/g, '<br>');
			
			msgArea.appendChild(msgDiv);
			scrollToBottom();
		}

		function renderHistory() {
			msgArea.innerHTML = '';
			if (history.length === 0 && aicb_data.welcome) {
				appendMessage('assistant', aicb_data.welcome, false);
			} else {
				history.forEach(item => {
					appendMessage(item.role, item.content, false);
				});
			}
		}

		function scrollToBottom() {
			msgArea.scrollTop = msgArea.scrollHeight;
		}

		function escapeHtml(unsafe) {
			return unsafe
				.replace(/&/g, "&amp;")
				.replace(/</g, "&lt;")
				.replace(/>/g, "&gt;")
				.replace(/"/g, "&quot;")
				.replace(/'/g, "&#039;");
		}

		input.addEventListener('input', function() {
			this.style.height = 'auto';
			this.style.height = (this.scrollHeight) + 'px';
		});
	});

})();
