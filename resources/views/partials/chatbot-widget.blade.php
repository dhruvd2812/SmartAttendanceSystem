<div class="attendance-chatbot" data-chatbot>
    <section class="attendance-chatbot__panel" id="attendance-chat-panel" aria-label="Smart Attendance AI chat" aria-hidden="true">
        <header class="attendance-chatbot__header">
            <span class="attendance-chatbot__title"><span aria-hidden="true">&#129302;</span> Smart Attendance AI</span>
            <button class="attendance-chatbot__close" type="button" aria-label="Close chat" data-chat-close>&times;</button>
        </header>
        <div class="attendance-chatbot__messages" data-chat-messages aria-live="polite">
            <div class="attendance-chatbot__message attendance-chatbot__message--bot">Hello! &#128075; I'm your Smart Attendance Assistant.<br>How can I help you?</div>
        </div>
        <form class="attendance-chatbot__form" data-chat-form>
            <input type="text" data-chat-input maxlength="1000" autocomplete="off" placeholder="Ask about attendance, QR, faculty..." aria-label="Chat message">
            <button type="submit" aria-label="Send message" data-chat-send>Send</button>
        </form>
    </section>
    <button class="attendance-chatbot__toggle" type="button" aria-label="Open Smart Attendance AI chat" aria-expanded="false" aria-controls="attendance-chat-panel" data-chat-toggle>&#129302;</button>
</div>
<script>
(() => {
    const chatbot = document.querySelector('[data-chatbot]'); if (!chatbot) return;
    const panel = chatbot.querySelector('.attendance-chatbot__panel'), toggle = chatbot.querySelector('[data-chat-toggle]'), close = chatbot.querySelector('[data-chat-close]'), form = chatbot.querySelector('[data-chat-form]'), input = chatbot.querySelector('[data-chat-input]'), messages = chatbot.querySelector('[data-chat-messages]'), send = chatbot.querySelector('[data-chat-send]');
    const setOpen = open => { chatbot.classList.toggle('is-open', open); panel.setAttribute('aria-hidden', String(!open)); toggle.setAttribute('aria-expanded', String(open)); if (open) setTimeout(() => input.focus(), 180); };
    const appendMessage = (text, type) => { const item = document.createElement('div'); item.className = `attendance-chatbot__message attendance-chatbot__message--${type}`; item.textContent = text; messages.appendChild(item); messages.scrollTop = messages.scrollHeight; return item; };
    toggle.addEventListener('click', () => setOpen(!chatbot.classList.contains('is-open'))); close.addEventListener('click', () => setOpen(false));
    form.addEventListener('submit', async event => { event.preventDefault(); const message = input.value.trim(); if (!message) return; appendMessage(message, 'user'); input.value = ''; input.disabled = true; send.disabled = true; const loading = appendMessage('Typing...', 'bot');
        try { const response = await fetch('{{ route('chatbot.message') }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ message }) }); const data = await response.json(); loading.remove(); appendMessage(response.ok && data.reply ? data.reply : 'Sorry, I could not process that message. Please try again.', 'bot'); }
        catch (error) { loading.textContent = 'Something went wrong. Please try again.'; console.error('Chatbot request failed:', error); }
        finally { input.disabled = false; send.disabled = false; input.focus(); messages.scrollTop = messages.scrollHeight; }
    });
})();
</script>
