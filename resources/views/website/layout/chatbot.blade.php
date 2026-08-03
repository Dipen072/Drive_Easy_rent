<!-- DriveEase AI Chatbot Widget -->
<button class="de-chat-launcher" id="deChatLauncher" aria-label="Open DriveEase AI Chatbot">
    <i class="fas fa-robot"></i>
    <span class="de-chat-badge"></span>
</button>

<div class="de-chat-widget" id="deChatWidget">
    <!-- Header -->
    <div class="de-chat-header">
        <div class="de-chat-header-info">
            <div class="de-bot-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <div class="de-chat-header-text">
                <h6>DriveEase AI</h6>
                <span><i class="de-status-dot"></i> Online • Instant Support</span>
            </div>
        </div>
        <div class="de-header-actions">
            <button type="button" class="de-btn-icon" id="deChatMinimize" title="Minimize">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="de-btn-icon" id="deChatClose" title="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Messages Body -->
    <div class="de-chat-body" id="deChatBody">
        <!-- Welcome Message -->
        <div class="de-message bot">
            <div class="de-msg-avatar"><i class="fas fa-robot"></i></div>
            <div class="de-msg-content">
                <p>Hello! 👋 Welcome to <strong>DriveEase Car Rental</strong>!</p>
                <p>I'm your AI assistant. How can I help you today?</p>
                
                <div class="de-quick-actions">
                    <button type="button" class="de-chat-pill" data-text="Show me available cars">🚗 Browse Cars</button>
                    <button type="button" class="de-chat-pill" data-text="What are your rental rates?">💰 Check Prices</button>
                    <button type="button" class="de-chat-pill" data-text="Show me active discount offers">🎉 Offers & Coupons</button>
                    <button type="button" class="de-chat-pill" data-text="Which cities do you operate in?">📍 Cities / Locations</button>
                    <button type="button" class="de-chat-pill" data-text="How do I book a car?">📋 How to Book?</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Input Form -->
    <div class="de-chat-footer">
        <div class="de-chat-input-wrapper">
            <input type="text" class="de-chat-input" id="deChatInput" placeholder="Ask DriveEase AI anything..." maxlength="1000" autocomplete="off">
        </div>
        <button type="button" class="de-chat-send-btn" id="deChatSendBtn" aria-label="Send Message">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>
