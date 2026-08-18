@extends('layouts.app')

@section('title', 'Smart Attendance AI')

@section('content')
    <div class="app-card p-4 p-md-5 text-center">
        <h1 class="h3 mb-2">Smart Attendance AI</h1>
        <p class="text-muted mb-0">The assistant is ready. Type your question in the chat window.</p>
    </div>
@endsection

@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const chatbot = document.querySelector('[data-chatbot]');
            const toggle = chatbot?.querySelector('[data-chat-toggle]');

            if (chatbot && toggle && !chatbot.classList.contains('is-open')) {
                toggle.click();
            }
        });
    </script>
@endpush
