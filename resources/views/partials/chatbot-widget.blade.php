<div class="attendance-chatbot" data-chatbot>

    <section
        class="attendance-chatbot__panel"
        id="attendance-chat-panel"
        aria-label="Smart Attendance AI chat"
        aria-hidden="true"
    >

        <header class="attendance-chatbot__header">

            <span class="attendance-chatbot__title">
                <span aria-hidden="true">🤖</span>
                Smart Attendance AI
            </span>

            <button
                class="attendance-chatbot__close"
                type="button"
                aria-label="Close chat"
                data-chat-close
            >
                &times;
            </button>

        </header>


        <div
            class="attendance-chatbot__messages"
            data-chat-messages
            aria-live="polite"
        >

            <div class="attendance-chatbot__message attendance-chatbot__message--bot">

                Hello! 👋 I'm your Smart Attendance Assistant.
                <br>
                How can I help you?

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
                Send
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
        🤖
    </button>

</div>


<script>

(() => {

    const chatbot = document.querySelector('[data-chatbot]');

    if (!chatbot) {
        return;
    }


    const panel = chatbot.querySelector(
        '.attendance-chatbot__panel'
    );

    const toggle = chatbot.querySelector(
        '[data-chat-toggle]'
    );

    const close = chatbot.querySelector(
        '[data-chat-close]'
    );

    const form = chatbot.querySelector(
        '[data-chat-form]'
    );

    const input = chatbot.querySelector(
        '[data-chat-input]'
    );

    const messages = chatbot.querySelector(
        '[data-chat-messages]'
    );

    const send = chatbot.querySelector(
        '[data-chat-send]'
    );


    /*
    |--------------------------------------------------------------------------
    | Chatbot URL
    |--------------------------------------------------------------------------
    */

    const chatbotUrl =
        @if(auth()->check() && auth()->user()->role === 'admin')
            @json(route('admin.chatbot.message'))
        @elseif(auth()->check() && auth()->user()->role === 'faculty')
            @json(route('faculty.chatbot.message'))
        @else
            null
        @endif;


    /*
    |--------------------------------------------------------------------------
    | Open / Close Chat
    |--------------------------------------------------------------------------
    */

    const setOpen = (open) => {

        chatbot.classList.toggle(
            'is-open',
            open
        );

        panel.setAttribute(
            'aria-hidden',
            String(!open)
        );

        toggle.setAttribute(
            'aria-expanded',
            String(open)
        );

        if (open) {

            setTimeout(() => {

                input.focus();

            }, 180);

        }

    };


    /*
    |--------------------------------------------------------------------------
    | Add Message
    |--------------------------------------------------------------------------
    */

    const appendMessage = (text, type) => {

        const item = document.createElement('div');

        item.className =
            `attendance-chatbot__message attendance-chatbot__message--${type}`;

        item.textContent = text;

        messages.appendChild(item);

        messages.scrollTop =
            messages.scrollHeight;

        return item;

    };


    /*
    |--------------------------------------------------------------------------
    | Toggle
    |--------------------------------------------------------------------------
    */

    toggle.addEventListener(
        'click',
        () => {

            setOpen(
                !chatbot.classList.contains('is-open')
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Close
    |--------------------------------------------------------------------------
    */

    close.addEventListener(
        'click',
        () => {

            setOpen(false);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        async (event) => {

            event.preventDefault();


            const message =
                input.value.trim();


            if (!message) {
                return;
            }


            if (!chatbotUrl) {

                appendMessage(
                    'Chatbot is not available for this account.',
                    'bot'
                );

                return;

            }


            /*
            |------------------------------------------------------------------
            | User Message
            |------------------------------------------------------------------
            */

            appendMessage(
                message,
                'user'
            );


            input.value = '';

            input.disabled = true;

            send.disabled = true;


            /*
            |------------------------------------------------------------------
            | Loading
            |------------------------------------------------------------------
            */

            const loading =
                appendMessage(
                    'Typing...',
                    'bot'
                );


            try {

                const csrfToken =
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute('content');


                const response =
                    await fetch(
                        chatbotUrl,
                        {
                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken

                            },

                            body:
                                JSON.stringify({
                                    message: message
                                })
                        }
                    );


                const data =
                    await response.json();


                loading.remove();


                if (
                    response.ok &&
                    data.reply
                ) {

                    appendMessage(
                        data.reply,
                        'bot'
                    );

                } else {

                    appendMessage(
                        data.message ||
                        'Sorry, I could not process that message.',
                        'bot'
                    );

                }


            } catch (error) {

                console.error(
                    'Chatbot request failed:',
                    error
                );


                loading.textContent =
                    'Something went wrong. Please try again.';

            }


            finally {

                input.disabled = false;

                send.disabled = false;

                input.focus();

                messages.scrollTop =
                    messages.scrollHeight;

            }

        }
    );

})();

</script>