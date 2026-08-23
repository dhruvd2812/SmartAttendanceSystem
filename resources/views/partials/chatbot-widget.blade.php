<div class="attendance-chatbot" data-chatbot>

    <section
        class="attendance-chatbot__panel"
        id="attendance-chat-panel"
        aria-label="Smart Attendance AI Assistant"
        aria-hidden="true"
    >

        <header class="attendance-chatbot__header">
            <div class="d-flex align-items-center gap-2">
                <div class="chatbot-header-avatar">
                    <i class="bi bi-robot"></i>
                </div>
                <div>
                    <div class="attendance-chatbot__title">Smart Attendance AI</div>
                    <div class="chatbot-status-indicator">
                        <span class="status-dot"></span>
                        <span>Online Assistant</span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-1">
                <button
                    class="chatbot-header-btn"
                    type="button"
                    title="Clear Conversation"
                    data-chat-clear
                >
                    <i class="bi bi-trash3"></i>
                </button>

                <button
                    class="attendance-chatbot__close"
                    type="button"
                    aria-label="Close chat"
                    data-chat-close
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </header>

        <div
            class="attendance-chatbot__messages"
            data-chat-messages
            aria-live="polite"
        >
            <div class="attendance-chatbot__message attendance-chatbot__message--bot">
                👋 Hello <strong>{{ auth()->user()->name ?? 'there' }}</strong>! I am your <strong>Smart Attendance Assistant</strong>.<br><br>
                How can I assist you today? You can tap any suggested topic below or type your question.
            </div>

            {{-- Suggestions chips --}}
            <div class="chatbot-chips" data-chat-chips>
                @if(auth()->check() && auth()->user()->role === 'student')
                    <button type="button" class="chat-chip" data-chip="My attendance">📊 My Attendance</button>
                    <button type="button" class="chat-chip" data-chip="Today attendance">📅 Today's Attendance</button>
                    <button type="button" class="chat-chip" data-chip="How to scan QR">📱 How to Scan QR</button>
                    <button type="button" class="chat-chip" data-chip="List subjects">📚 My Subjects</button>
                @elseif(auth()->check() && auth()->user()->role === 'faculty')
                    <button type="button" class="chat-chip" data-chip="My subjects">📚 My Subjects</button>
                    <button type="button" class="chat-chip" data-chip="Today attendance">🗓️ Today's Classes</button>
                    <button type="button" class="chat-chip" data-chip="Total students">👨‍🎓 Total Students</button>
                    <button type="button" class="chat-chip" data-chip="Generate QR">📱 Generate QR Help</button>
                @else
                    <button type="button" class="chat-chip" data-chip="Total students">👨‍🎓 Total Students</button>
                    <button type="button" class="chat-chip" data-chip="Total faculty">👨‍🏫 Total Faculty</button>
                    <button type="button" class="chat-chip" data-chip="List departments">🏢 Departments</button>
                    <button type="button" class="chat-chip" data-chip="Help">❓ System Help</button>
                @endif
            </div>
        </div>

        <form
            class="attendance-chatbot__form"
            data-chat-form
        >
            <input
                type="text"
                data-chat-input
                maxlength="1000"
                autocomplete="off"
                placeholder="Ask about attendance, QR, faculty..."
                aria-label="Chat message"
            >

            <button
                type="submit"
                aria-label="Send message"
                data-chat-send
            >
                <i class="bi bi-send-fill"></i>
            </button>
        </form>

    </section>

    <button
        class="attendance-chatbot__toggle"
        type="button"
        aria-label="Open Smart Attendance AI chat"
        aria-expanded="false"
        aria-controls="attendance-chat-panel"
        data-chat-toggle
    >
        <i class="bi bi-chat-dots-fill"></i>
        <span class="chatbot-pulse-ring"></span>
    </button>

</div>

<style>
    .attendance-chatbot__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
        color: #ffffff;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .chatbot-header-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.4);
    }

    .attendance-chatbot__title {
        font-weight: 700;
        font-size: 0.95rem;
        line-height: 1.2;
    }

    .chatbot-status-indicator {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.72rem;
        color: #a5b4fc;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 8px #10b981;
    }

    .chatbot-header-btn {
        background: transparent;
        border: 0;
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.95rem;
        padding: 6px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .chatbot-header-btn:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);
    }

    .attendance-chatbot__close {
        background: transparent;
        border: 0;
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
        padding: 4px 6px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .attendance-chatbot__close:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);
    }

    .attendance-chatbot__messages {
        display: flex;
        flex: 1;
        flex-direction: column;
        gap: 12px;
        overflow-y: auto;
        padding: 16px;
        background: #f8fafc;
    }

    .attendance-chatbot__message {
        width: fit-content;
        max-width: 85%;
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 0.88rem;
        line-height: 1.45;
        word-wrap: break-word;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        animation: chatFadeIn 0.2s ease forwards;
    }

    @keyframes chatFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .attendance-chatbot__message--bot {
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        background: #ffffff;
        color: #1e293b;
        border: 1px solid rgba(226, 232, 240, 0.9);
    }

    .attendance-chatbot__message--user {
        align-self: flex-end;
        border-bottom-right-radius: 4px;
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }

    .chatbot-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 4px;
        margin-bottom: 4px;
    }

    .chat-chip {
        background: #ffffff;
        border: 1px solid rgba(99, 102, 241, 0.25);
        color: #4f46e5;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 5px 11px;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .chat-chip:hover {
        background: #4f46e5;
        color: #ffffff;
        border-color: #4f46e5;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
    }

    .typing-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 6px;
    }

    .typing-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #6366f1;
        animation: typingDot 1.4s infinite ease-in-out both;
    }

    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typingDot {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
        40% { transform: scale(1); opacity: 1; }
    }

    .attendance-chatbot__form {
        display: flex;
        gap: 8px;
        padding: 12px 14px;
        border-top: 1px solid #e2e8f0;
        background: #ffffff;
    }

    .attendance-chatbot__form input {
        min-width: 0;
        flex: 1;
        padding: 9px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        outline: none;
        font-size: 0.88rem;
        transition: all 0.2s ease;
    }

    .attendance-chatbot__form input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .attendance-chatbot__form button {
        width: 40px;
        height: 40px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: #ffffff;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        transition: all 0.2s ease;
    }

    .attendance-chatbot__form button:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(79, 70, 229, 0.4);
    }

    .attendance-chatbot__form button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .chatbot-pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px solid rgba(99, 102, 241, 0.6);
        animation: pulseRing 2.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        pointer-events: none;
    }

    @keyframes pulseRing {
        0% { transform: scale(0.95); opacity: 0.8; }
        50% { transform: scale(1.3); opacity: 0; }
        100% { transform: scale(1.3); opacity: 0; }
    }
</style>

<script>
(() => {
    const chatbot = document.querySelector('[data-chatbot]');
    if (!chatbot) return;

    const panel = chatbot.querySelector('.attendance-chatbot__panel');
    const toggle = chatbot.querySelector('[data-chat-toggle]');
    const close = chatbot.querySelector('[data-chat-close]');
    const clearBtn = chatbot.querySelector('[data-chat-clear]');
    const form = chatbot.querySelector('[data-chat-form]');
    const input = chatbot.querySelector('[data-chat-input]');
    const messages = chatbot.querySelector('[data-chat-messages]');
    const send = chatbot.querySelector('[data-chat-send]');

    const chatbotUrl =
        @if(auth()->check() && auth()->user()->role === 'admin')
            @json(route('admin.chatbot.message'))
        @elseif(auth()->check() && auth()->user()->role === 'faculty')
            @json(route('faculty.chatbot.message'))
        @elseif(auth()->check() && auth()->user()->role === 'student')
            @json(route('student.chatbot.message'))
        @else
            null
        @endif;

    const playNotificationSound = () => {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.1);
            gain.gain.setValueAtTime(0.08, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.15);
        } catch (e) {}
    };

    const setOpen = (open) => {
        chatbot.classList.toggle('is-open', open);
        panel.setAttribute('aria-hidden', String(!open));
        toggle.setAttribute('aria-expanded', String(open));
        if (open) {
            setTimeout(() => { input.focus(); }, 180);
        }
    };

    const appendMessage = (text, type, isHtml = false) => {
        const item = document.createElement('div');
        item.className = `attendance-chatbot__message attendance-chatbot__message--${type}`;
        if (isHtml) {
            item.innerHTML = text;
        } else {
            item.textContent = text;
        }
        messages.appendChild(item);
        messages.scrollTop = messages.scrollHeight;
        return item;
    };

    const showTyping = () => {
        const item = document.createElement('div');
        item.className = 'attendance-chatbot__message attendance-chatbot__message--bot typing-wrapper';
        item.innerHTML = '<div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>';
        messages.appendChild(item);
        messages.scrollTop = messages.scrollHeight;
        return item;
    };

    const sendMessage = async (messageText) => {
        const message = messageText.trim();
        if (!message) return;

        if (!chatbotUrl) {
            appendMessage('Chatbot is not available for this account.', 'bot');
            return;
        }

        appendMessage(message, 'user');
        input.value = '';
        input.disabled = true;
        send.disabled = true;

        const loading = showTyping();

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const response = await fetch(chatbotUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            loading.remove();

            if (response.ok && data.reply) {
                appendMessage(data.reply, 'bot');
                playNotificationSound();
            } else {
                appendMessage(data.message || 'Sorry, I could not process that message.', 'bot');
            }
        } catch (error) {
            console.error('Chatbot error:', error);
            loading.remove();
            appendMessage('Something went wrong. Please try again.', 'bot');
        } finally {
            input.disabled = false;
            send.disabled = false;
            input.focus();
            messages.scrollTop = messages.scrollHeight;
        }
    };

    toggle.addEventListener('click', () => setOpen(!chatbot.classList.contains('is-open')));
    close.addEventListener('click', () => setOpen(false));

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            messages.innerHTML = `
                <div class="attendance-chatbot__message attendance-chatbot__message--bot">
                    Chat history cleared. How can I assist you now?
                </div>
            `;
        });
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        sendMessage(input.value);
    });

    document.addEventListener('click', (e) => {
        const chip = e.target.closest('[data-chip]');
        if (chip) {
            const query = chip.getAttribute('data-chip');
            if (query) {
                sendMessage(query);
            }
        }
    });
})();
</script>