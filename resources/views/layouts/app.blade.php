<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Smart Attendance')
    </title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>

        :root {
            --color-bg: #f8fafc;
            --color-surface: #ffffff;
            --color-surface-soft: #f1f5f9;
            --color-primary: #4f46e5;
            --color-primary-hover: #4338ca;
            --color-primary-soft: rgba(79, 70, 229, 0.1);
            --color-accent: #06b6d4;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --color-heading: #0f172a;
            --color-muted: #64748b;
            --border-radius-sm: 0.5rem;
            --border-radius-md: 0.875rem;
            --border-radius-lg: 1.25rem;
            --shadow-soft: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
            --shadow-card: 0 10px 30px -4px rgba(15, 23, 42, 0.06), 0 4px 10px -2px rgba(15, 23, 42, 0.03);
            --shadow-hover: 0 20px 40px -6px rgba(79, 70, 229, 0.12), 0 8px 16px -4px rgba(15, 23, 42, 0.04);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--color-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(6, 182, 212, 0.06) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(79, 70, 229, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--color-heading);
            -webkit-font-smoothing: antialiased;
        }

        /* =========================================================
           NAVBAR
        ========================================================== */

        .app-navbar {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .app-navbar .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
            font-size: 1.1rem;
        }

        .app-navbar .nav-link {
            color: #94a3b8;
            font-weight: 500;
            font-size: 0.925rem;
            padding: 0.5rem 0.85rem;
            border-radius: var(--border-radius-sm);
            transition: all 0.2s ease;
        }

        .app-navbar .nav-link:hover {
            color: #f8fafc;
            background: rgba(255, 255, 255, 0.06);
        }

        .app-navbar .nav-link.active {
            color: #ffffff;
            background: rgba(99, 102, 241, 0.2);
            border: 1px solid rgba(99, 102, 241, 0.3);
            font-weight: 600;
        }

        /* =========================================================
           CARDS & SURFACES
        ========================================================== */

        .app-card,
        .card {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: var(--border-radius-lg);
            background: #ffffff;
            box-shadow: var(--shadow-card);
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* =========================================================
           HERO SECTION
        ========================================================== */

        .app-hero {
            border-radius: var(--border-radius-lg);
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px -10px rgba(49, 46, 129, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.3) 0%, transparent 70%);
            pointer-events: none;
        }

        .app-hero::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: 10%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        /* =========================================================
           METRIC CARDS
        ========================================================== */

        .app-metric .card-body {
            min-height: 150px;
        }

        .metric-icon-wrapper {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            box-shadow: 0 8px 16px -4px rgba(0, 0, 0, 0.08);
        }

        .metric-icon-indigo { background: rgba(79, 70, 229, 0.12); color: #4f46e5; }
        .metric-icon-cyan { background: rgba(6, 182, 212, 0.12); color: #0891b2; }
        .metric-icon-emerald { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .metric-icon-amber { background: rgba(245, 158, 11 chips, 0.12); color: #d97706; }

        /* =========================================================
           TABLES
        ========================================================== */

        .app-table thead,
        .table thead {
            background: #f8fafc;
            color: #475569;
            font-size: 0.825rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
        }

        .table > :not(caption) > * > * {
            padding: 1rem 1.25rem;
            border-bottom-color: #f1f5f9;
        }

        .table tbody tr {
            transition: background-color 0.15s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.03);
        }

        /* =========================================================
           BUTTONS
        ========================================================== */

        .btn {
            font-weight: 600;
            border-radius: var(--border-radius-sm);
            padding: 0.625rem 1.25rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            border: 1px solid #4338ca;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #3730a3 100%);
            border-color: #3730a3;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        .btn-soft-primary {
            color: #4338ca;
            background: rgba(79, 70, 229, 0.1);
            border: 1px solid rgba(79, 70, 229, 0.15);
        }

        .btn-soft-primary:hover {
            background: rgba(79, 70, 229, 0.18);
            color: #312e81;
            transform: translateY(-1px);
        }

        /* =========================================================
           FORMS
        ========================================================== */

        .form-control,
        .form-select {
            border-radius: var(--border-radius-sm);
            border: 1px solid #cbd5e1;
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: #ffffff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
            outline: none;
        }

        /* =========================================================
           BADGES
        ========================================================== */

        .badge {
            font-weight: 600;
            padding: 0.45rem 0.8rem;
            border-radius: 9999px;
            letter-spacing: 0.02em;
        }

        /* =========================================================
           ALERT
        ========================================================== */

        .alert-custom {
            border-radius: var(--border-radius-md);
            border: 1px solid rgba(99, 102, 241, 0.15);
            background: rgba(238, 242, 255, 0.8);
            backdrop-filter: blur(8px);
        }

        /* =========================================================
           AUTH
        ========================================================== */

        .auth-card {
            max-width: 900px;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.15);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .auth-hero {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
            color: white;
            position: relative;
        }

        /* =========================================================
           TEXT
        ========================================================== */

        .text-muted {
            color:
                var(--color-muted) !important;
        }

        .small {
            font-size: .9rem;
        }

        /* =========================================================
           BREADCRUMB
        ========================================================== */

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
        }

        /* =========================================================
           CHATBOT
        ========================================================== */

        .attendance-chatbot {
            position: fixed;

            right: 20px;
            bottom: 20px;

            z-index: 1050;
        }

        .attendance-chatbot__toggle {
            width: 55px;
            height: 55px;

            border: 0;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #2563eb
                );

            color: #fff;

            font-size: 1.55rem;

            box-shadow:
                0 12px 28px rgba(37, 99, 235, .35);

            cursor: pointer;

            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }

        .attendance-chatbot__toggle:hover {
            transform:
                translateY(-2px);

            box-shadow:
                0 16px 32px rgba(37, 99, 235, .42);
        }

        .attendance-chatbot__panel {
            position: absolute;

            right: 0;
            bottom: 68px;

            display: flex;

            width: 350px;
            height: 450px;

            overflow: hidden;

            flex-direction: column;

            border:
                1px solid rgba(15, 23, 42, .1);

            border-radius: 18px;

            background: #fff;

            box-shadow:
                0 20px 50px rgba(15, 23, 42, .2);

            opacity: 0;

            pointer-events: none;

            transform:
                translateY(12px)
                scale(.97);

            transform-origin:
                bottom right;

            transition:
                opacity .2s ease,
                transform .2s ease;
        }

        .attendance-chatbot.is-open
        .attendance-chatbot__panel {

            opacity: 1;

            pointer-events: auto;

            transform:
                translateY(0)
                scale(1);
        }

        .attendance-chatbot__header {
            display: flex;

            align-items: center;

            justify-content: space-between;

            padding:
                15px 17px;

            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #2563eb
                );

            color: #fff;
        }

        .attendance-chatbot__title {
            font-weight: 700;
            font-size: .98rem;
        }

        .attendance-chatbot__close {
            border: 0;
            background: transparent;
            color: #fff;
            font-size: 1.7rem;
            line-height: 1;
            cursor: pointer;
        }

        .attendance-chatbot__messages {
            display: flex;
            flex: 1;
            flex-direction: column;
            gap: 10px;
            overflow-y: auto;
            padding: 17px;
            background: #f8faff;
        }

        .attendance-chatbot__message {
            width: fit-content;
            max-width: 82%;
            padding: 10px 13px;
            border-radius: 14px;
            font-size: .9rem;
            line-height: 1.4;
            white-space: pre-wrap;
        }

        .attendance-chatbot__message--bot {
            align-self: flex-start;
            border-bottom-left-radius: 4px;
            background: #e9edff;
            color: #20225e;
        }

        .attendance-chatbot__message--user {
            align-self: flex-end;
            border-bottom-right-radius: 4px;
            background: #4f46e5;
            color: #fff;
        }

        .attendance-chatbot__form {
            display: flex;
            gap: 8px;
            padding: 12px;
            border-top: 1px solid #e5e7eb;
            background: #fff;
        }

        .attendance-chatbot__form input {
            min-width: 0;
            flex: 1;
            padding: 10px 12px;
            border: 1px solid #d7dbe8;
            border-radius: 10px;
            outline: none;
            font-size: .86rem;
        }

        .attendance-chatbot__form input:focus {
            border-color: #6366f1;

            box-shadow:
                0 0 0 3px
                rgba(99, 102, 241, .14);
        }

        .attendance-chatbot__form button {
            padding: 0 14px;
            border: 0;
            border-radius: 10px;
            background: #4f46e5;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .attendance-chatbot__form button:disabled {
            opacity: .6;
            cursor: wait;
        }

        /* =========================================================
           MOBILE
        ========================================================== */

        @media (max-width: 767px) {

            .app-metric .display-6 {
                font-size: 2rem;
            }

            .attendance-chatbot {
                right: 14px;
                bottom: 14px;
            }

            .attendance-chatbot__panel {
                width:
                    min(
                        350px,
                        calc(100vw - 28px)
                    );

                height:
                    min(
                        450px,
                        calc(100vh - 100px)
                    );
            }
        }

    </style>

    @stack('styles')

</head>


<body>

{{-- =========================================================
     AUTHENTICATED NAVBAR
========================================================== --}}

@auth

<nav class="navbar navbar-expand-lg navbar-dark app-navbar py-3 shadow-sm">

    <div class="container">

        {{-- =====================================================
             BRAND
        ====================================================== --}}

        <a
            class="navbar-brand text-white"
            href="{{ auth()->user()->role === 'admin' ? route('dashboard') : (auth()->user()->role === 'faculty' ? route('faculty.dashboard') : (auth()->user()->role === 'student' ? route('student.dashboard') : route('login'))) }}"
        >
            <span class="brand-badge"><i class="bi bi-qr-code-scan"></i></span>
            <span>Smart<span style="color: #818cf8;">Attendance</span></span>
        </a>


        {{-- Mobile menu button --}}

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNav"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="mainNav"
        >

            <ul class="navbar-nav ms-auto align-items-center">


                {{-- =================================================
                     ADMIN NAVIGATION
                ================================================== --}}

                @if(auth()->user()->role === 'admin')

                    {{-- Dashboard --}}

                    <li class="nav-item">

                        <a
                            class="nav-link
                            {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}"
                        >
                            Dashboard
                        </a>

                    </li>


                    {{-- Students --}}

                    <li class="nav-item">

                        <a
                            class="nav-link
                            {{ request()->routeIs('admin.students.*') ? 'active' : '' }}"
                            href="{{ route('admin.students.index') }}"
                        >
                            Students
                        </a>

                    </li>


                    {{-- Departments --}}

                    <li class="nav-item">

                        <a
                            class="nav-link
                            {{ request()->routeIs('departments.*') ? 'active' : '' }}"
                            href="{{ route('departments.index') }}"
                        >
                            Departments
                        </a>

                    </li>


                    {{-- Faculties --}}

                    <li class="nav-item">

                        <a
                            class="nav-link
                            {{ request()->routeIs('faculties.*') ? 'active' : '' }}"
                            href="{{ route('faculties.index') }}"
                        >
                            Faculties
                        </a>

                    </li>


                    {{-- QR Generator --}}

                    <li class="nav-item">

                        <a
                            class="nav-link
                            {{ request()->routeIs('admin.qr.*') ? 'active' : '' }}"
                            href="{{ route('admin.qr.index') }}"
                        >
                            QR Generator
                        </a>

                    </li>


                    {{-- Chatbot --}}

                    <li class="nav-item">

                        <a
                            class="nav-link
                            {{ request()->routeIs('admin.chatbot.*') ? 'active' : '' }}"
                            href="{{ route('admin.chatbot.index') }}"
                        >
                            Chatbot
                        </a>

                    </li>

                @endif



                {{-- =================================================
                     FACULTY NAVIGATION
                ================================================== --}}

                @if(auth()->user()->role === 'faculty')

                    {{-- Faculty Dashboard --}}

                    <li class="nav-item">

                        <a
                            class="nav-link
                            {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}"
                            href="{{ route('faculty.dashboard') }}"
                        >
                            Dashboard
                        </a>

                    </li>


                    {{-- Students --}}

                    <li class="nav-item">

                        <a
                            class="nav-link
                            {{ request()->routeIs('faculty.students.*') ? 'active' : '' }}"
                            href="{{ route('faculty.students.index') }}"
                        >
                            Students
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            class="nav-link
                            {{ request()->routeIs('faculty.subjects.*') ? 'active' : '' }}"
                            href="{{ route('faculty.subjects.index') }}"
                        >
                            Subjects
                        </a>

                    </li>


                    {{-- QR Generator --}}

                    <li class="nav-item">

                        <a
                            class="nav-link
                            {{ request()->routeIs('faculty.qr.*') ? 'active' : '' }}"
                            href="{{ route('faculty.qr.index') }}"
                        >
                            QR Generator
                        </a>

                    </li>


                    {{-- Chatbot --}}

                    <li class="nav-item">

                        <a
                            class="nav-link
                            {{ request()->routeIs('faculty.chatbot.*') ? 'active' : '' }}"
                            href="{{ route('faculty.chatbot.index') }}"
                        >
                            Chatbot
                        </a>

                    </li>

                @endif



                {{-- =================================================
                     LOGGED-IN USER
                ================================================== --}}

                <li class="nav-item ms-lg-3">

                    <span class="text-white me-3">

                        {{ auth()->user()->name }}

                        <small class="opacity-75">
                            ({{ ucfirst(auth()->user()->role) }})
                        </small>

                    </span>

                </li>


                {{-- =================================================
                     LOGOUT
                ================================================== --}}

                <li class="nav-item">

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-outline-light btn-sm"
                        >
                            Logout
                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

</nav>

@endauth



{{-- =========================================================
     MAIN CONTENT
========================================================== --}}

<main class="container py-5">

    {{-- Success Message --}}

    @if(session('success'))

        <div class="alert alert-success alert-custom shadow-sm">

            {{ session('success') }}

        </div>

    @endif


    {{-- Error Messages --}}

    @if($errors->any())

        <div class="alert alert-danger alert-custom shadow-sm">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Page Content --}}

    @yield('content')

</main>



{{-- =========================================================
     CHATBOT
========================================================== --}}

@auth

    @if(in_array(auth()->user()->role, ['admin', 'faculty', 'student']))

        @include('partials.chatbot-widget')

    @endif

@endauth



{{-- Bootstrap JS --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


@stack('scripts')

</body>

</html>
