{{-- AI Chatbot Widget --}}

{{-- Chatbot CSS --}}
<link rel="stylesheet" href="{{ asset('clients/assets/css/chatbot.css') }}">

{{-- Toggle Button --}}
<button class="chatbot-toggle" id="chatbot-toggle" aria-label="Mở chatbot">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/>
        <circle cx="8" cy="10" r="1.2"/>
        <circle cx="12" cy="10" r="1.2"/>
        <circle cx="16" cy="10" r="1.2"/>
    </svg>
</button>

{{-- Chat Window --}}
<div class="chatbot-window" id="chatbot-window">
    {{-- Header --}}
    <div class="chatbot-header">
        <div class="chatbot-avatar">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
        </div>
        <div class="chatbot-header-info">
            <h4>Trợ lý Travela AI</h4>
            <span><span class="status-dot"></span> Trực tuyến</span>
        </div>
        <button class="chatbot-close" id="chatbot-close" aria-label="Đóng chatbot">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    {{-- Messages --}}
    <div class="chatbot-messages" id="chatbot-messages">
        {{-- Welcome Message --}}
        <div class="chatbot-msg bot">
            <div class="chatbot-msg-avatar">
                <svg viewBox="0 0 24 24"><path fill="#fff" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
            </div>
            <div class="chatbot-msg-bubble">
                Xin chào! 👋 Tôi là <strong>Trợ lý Travela AI</strong>. Tôi có thể giúp bạn tìm kiếm tour du lịch, tư vấn điểm đến và trả lời các câu hỏi về du lịch Việt Nam. Bạn cần hỗ trợ gì ạ?
                <div class="chatbot-suggestions">
                    <span class="chatbot-suggestion-chip">🏖️ Gợi ý tour hot</span>
                    <span class="chatbot-suggestion-chip">💰 Tour giá rẻ</span>
                    <span class="chatbot-suggestion-chip">🗺️ Tour miền Trung</span>
                    <span class="chatbot-suggestion-chip">📋 Cách đặt tour</span>
                </div>
            </div>
        </div>

        {{-- Typing Indicator --}}
        <div class="chatbot-typing" id="chatbot-typing">
            <div class="chatbot-typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

    {{-- Input Area --}}
    <div class="chatbot-input-area">
        <input type="text" class="chatbot-input" id="chatbot-input" placeholder="Nhập tin nhắn..." autocomplete="off" maxlength="1000">
        <button class="chatbot-send-btn" id="chatbot-send-btn" aria-label="Gửi tin nhắn">
            <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </div>
</div>

{{-- Chatbot Script Variables --}}
<script>
    var chatbotSendUrl = "{{ route('chatbot.send') }}";
    var csrfToken = "{{ csrf_token() }}";
</script>
<script src="{{ asset('clients/assets/js/chatbot.js') }}"></script>
