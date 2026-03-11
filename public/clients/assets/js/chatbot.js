/**
 * TRAVELA AI CHATBOT - Client Script
 * Handles the chatbot widget interactions and API calls
 */
$(document).ready(function () {
    const $toggle = $('#chatbot-toggle');
    const $window = $('#chatbot-window');
    const $messages = $('#chatbot-messages');
    const $input = $('#chatbot-input');
    const $sendBtn = $('#chatbot-send-btn');
    const $typing = $('#chatbot-typing');
    const $close = $('#chatbot-close');

    let conversationHistory = [];
    let isProcessing = false;

    // Toggle chat window
    $toggle.on('click', function () {
        const isOpen = $window.hasClass('open');
        if (isOpen) {
            closeChat();
        } else {
            openChat();
        }
    });

    // Close button
    $close.on('click', function () {
        closeChat();
    });

    // Escape key closes chat
    $(document).on('keydown', function (e) {
        if (e.key === 'Escape' && $window.hasClass('open')) {
            closeChat();
        }
    });

    function openChat() {
        $window.addClass('open');
        $toggle.addClass('active');
        $input.focus();
        scrollToBottom();
    }

    function closeChat() {
        $window.removeClass('open');
        $toggle.removeClass('active');
    }

    // Send message on Enter
    $input.on('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Send button click
    $sendBtn.on('click', function () {
        sendMessage();
    });

    // Suggestion chip click
    $(document).on('click', '.chatbot-suggestion-chip', function () {
        const text = $(this).text();
        $input.val(text);
        sendMessage();
    });

    function sendMessage() {
        const message = $input.val().trim();
        if (!message || isProcessing) return;

        isProcessing = true;
        $input.val('');
        $sendBtn.prop('disabled', true);

        // Remove suggestion chips after first message
        $('.chatbot-suggestions').remove();

        // Display user message
        appendMessage('user', message);

        // Add to history
        conversationHistory.push({ role: 'user', text: message });

        // Show typing indicator
        $typing.addClass('show');
        scrollToBottom();

        // Send to API
        $.ajax({
            url: chatbotSendUrl,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            contentType: 'application/json',
            data: JSON.stringify({
                message: message,
                history: conversationHistory.slice(0, -1) // Send previous history, not current message
            }),
            success: function (response) {
                $typing.removeClass('show');

                const botMessage = response.message || 'Xin lỗi, tôi không thể trả lời lúc này.';
                appendMessage('bot', botMessage);

                // Add bot response to history
                conversationHistory.push({ role: 'model', text: botMessage });

                // Keep history manageable (last 20 messages)
                if (conversationHistory.length > 20) {
                    conversationHistory = conversationHistory.slice(-20);
                }

                isProcessing = false;
                $sendBtn.prop('disabled', false);
                $input.focus();
            },
            error: function (xhr) {
                $typing.removeClass('show');
                appendMessage('bot', 'Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại sau! 🙏');
                isProcessing = false;
                $sendBtn.prop('disabled', false);
                $input.focus();
            }
        });
    }

    function appendMessage(type, text) {
        const avatarContent = type === 'bot'
            ? '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>'
            : '<svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';

        const formattedText = formatMarkdown(text);

        const html = `
            <div class="chatbot-msg ${type}">
                <div class="chatbot-msg-avatar">${avatarContent}</div>
                <div class="chatbot-msg-bubble">${formattedText}</div>
            </div>
        `;

        $messages.append(html);
        scrollToBottom();
    }

    function formatMarkdown(text) {
        // Escape HTML
        let formatted = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // Bold: **text** or __text__
        formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        formatted = formatted.replace(/__(.*?)__/g, '<strong>$1</strong>');

        // Italic: *text* or _text_
        formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');

        // Unordered list items: - item or * item
        formatted = formatted.replace(/^[\-\*]\s+(.+)$/gm, '<li>$1</li>');

        // Wrap consecutive <li> items in <ul>
        formatted = formatted.replace(/((?:<li>.*?<\/li>\n?)+)/g, '<ul>$1</ul>');

        // Numbered list items: 1. item
        formatted = formatted.replace(/^\d+\.\s+(.+)$/gm, '<li>$1</li>');

        // Links: [text](url)
        formatted = formatted.replace(/\[([^\]]+)\]\(([^\)]+)\)/g, '<a href="$2" target="_blank">$1</a>');

        // Line breaks
        formatted = formatted.replace(/\n/g, '<br>');

        // Clean up multiple <br> tags
        formatted = formatted.replace(/(<br\s*\/?>\s*){3,}/g, '<br><br>');

        return formatted;
    }

    function scrollToBottom() {
        setTimeout(function () {
            $messages.scrollTop($messages[0].scrollHeight);
        }, 100);
    }
});
