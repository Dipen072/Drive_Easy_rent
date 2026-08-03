/**
 * DriveEase AI Chatbot Widget Logic
 */
document.addEventListener('DOMContentLoaded', function () {
    const launcherBtn = document.getElementById('deChatLauncher');
    const chatWidget = document.getElementById('deChatWidget');
    const closeBtn = document.getElementById('deChatClose');
    const minimizeBtn = document.getElementById('deChatMinimize');
    const chatBody = document.getElementById('deChatBody');
    const chatInput = document.getElementById('deChatInput');
    const sendBtn = document.getElementById('deChatSendBtn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!launcherBtn || !chatWidget) return;

    // Toggle Chat visibility
    launcherBtn.addEventListener('click', function () {
        chatWidget.classList.toggle('active');
        if (chatWidget.classList.contains('active')) {
            chatInput.focus();
            scrollToBottom();
            // Hide badge dot on first open
            const badge = launcherBtn.querySelector('.de-chat-badge');
            if (badge) badge.style.display = 'none';
        }
    });

    closeBtn?.addEventListener('click', function () {
        chatWidget.classList.remove('active');
    });

    minimizeBtn?.addEventListener('click', function () {
        chatWidget.classList.remove('active');
    });

    // Send Message Event
    sendBtn?.addEventListener('click', sendMessage);
    chatInput?.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Delegate Quick Action Clicks
    document.addEventListener('click', function (e) {
        const pill = e.target.closest('.de-chat-pill');
        if (pill) {
            const url = pill.getAttribute('data-url');
            const text = pill.getAttribute('data-text');

            if (url) {
                window.location.href = url;
            } else if (text) {
                chatInput.value = text;
                sendMessage();
            }
        }
    });

    function sendMessage() {
        const text = chatInput.value.trim();
        if (!text) return;

        // Render User Message
        appendUserMessage(text);
        chatInput.value = '';
        sendBtn.disabled = true;

        // Show Typing Indicator
        showTypingIndicator();
        scrollToBottom();

        // Send AJAX request
        fetch('/chatbot/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response error');
            return res.json();
        })
        .then(data => {
            removeTypingIndicator();
            if (data.status === 'success') {
                appendBotMessage(data.reply, data.quick_actions, data.cars);
            } else {
                appendBotMessage("I'm sorry, I couldn't process that right now. Please try again or contact support at 1800-123-4567.");
            }
        })
        .catch(err => {
            console.error('Chatbot error:', err);
            removeTypingIndicator();
            appendBotMessage("Sorry! Connection issue. Please check your internet connection or browse our cars directly.", [
                { label: '🚗 Browse Cars', url: '/cars' },
                { label: '📞 Contact Support', url: '/contact' }
            ]);
        })
        .finally(() => {
            sendBtn.disabled = false;
            scrollToBottom();
        });
    }

    function appendUserMessage(text) {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'de-message user';
        msgDiv.innerHTML = `
            <div class="de-msg-avatar"><i class="fas fa-user"></i></div>
            <div class="de-msg-content">${escapeHtml(text)}</div>
        `;
        chatBody.appendChild(msgDiv);
    }

    function appendBotMessage(replyText, quickActions = [], cars = []) {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'de-message bot';

        let formattedText = formatMarkdown(replyText);

        // Generate Quick Actions HTML
        let actionsHtml = '';
        if (quickActions && quickActions.length > 0) {
            actionsHtml += '<div class="de-quick-actions">';
            quickActions.forEach(qa => {
                if (qa.url) {
                    actionsHtml += `<a href="${qa.url}" class="de-chat-pill" data-url="${qa.url}">${qa.label}</a>`;
                } else if (qa.text) {
                    actionsHtml += `<button type="button" class="de-chat-pill" data-text="${escapeHtml(qa.text)}">${qa.label}</button>`;
                }
            });
            actionsHtml += '</div>';
        }

        // Generate Recommended Cars HTML
        let carsHtml = '';
        if (cars && cars.length > 0) {
            carsHtml += '<div class="de-chat-cars">';
            cars.forEach(car => {
                carsHtml += `
                    <a href="${car.url}" class="de-chat-car-card">
                        <img src="${car.image}" alt="${escapeHtml(car.name)}" class="de-chat-car-img" onerror="this.src='/website/images/car-placeholder.jpg'">
                        <div class="de-chat-car-info">
                            <h7>${escapeHtml(car.name)}</h7>
                            <span>${car.seats} • ${car.fuel} • ${car.location}</span>
                        </div>
                        <div class="de-chat-car-price">${car.price}</div>
                    </a>
                `;
            });
            carsHtml += '</div>';
        }

        msgDiv.innerHTML = `
            <div class="de-msg-avatar"><i class="fas fa-robot"></i></div>
            <div class="de-msg-content">
                <div>${formattedText}</div>
                ${carsHtml}
                ${actionsHtml}
            </div>
        `;
        chatBody.appendChild(msgDiv);
        scrollToBottom();
    }

    function showTypingIndicator() {
        removeTypingIndicator();
        const typingDiv = document.createElement('div');
        typingDiv.className = 'de-message bot de-typing-wrapper';
        typingDiv.id = 'deTypingWrapper';
        typingDiv.innerHTML = `
            <div class="de-msg-avatar"><i class="fas fa-robot"></i></div>
            <div class="de-typing-indicator">
                <div class="de-typing-dot"></div>
                <div class="de-typing-dot"></div>
                <div class="de-typing-dot"></div>
            </div>
        `;
        chatBody.appendChild(typingDiv);
    }

    function removeTypingIndicator() {
        const typingWrapper = document.getElementById('deTypingWrapper');
        if (typingWrapper) typingWrapper.remove();
    }

    function scrollToBottom() {
        setTimeout(() => {
            chatBody.scrollTop = chatBody.scrollHeight;
        }, 50);
    }

    function escapeHtml(str) {
        return str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatMarkdown(text) {
        if (!text) return '';
        let html = escapeHtml(text);

        // Bold **text**
        html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Inline code `code`
        html = html.replace(/`(.*?)`/g, '<code>$1</code>');
        // Line breaks
        html = html.replace(/\n/g, '<br>');

        return html;
    }
});
