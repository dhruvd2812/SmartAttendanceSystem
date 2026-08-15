<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Attendance')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --color-bg: #f4f6ff;
            --color-surface: #ffffff;
            --color-surface-soft: #eff2ff;
            --color-primary: #4f46e5;
            --color-primary-soft: rgba(79, 70, 229, 0.12);
            --color-heading: #111827;
            --color-muted: #6b7280;
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
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top left, rgba(79,70,229,.15), transparent 32%),
                        linear-gradient(180deg, #f8fbff 0%, #f4f6ff 60%, #ffffff 100%);
            color: var(--color-heading);
        }

        .app-navbar {
            background: #312e81;
            box-shadow: 0 18px 45px rgba(49, 46, 129, 0.16);
        }

        .app-navbar .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .app-navbar .nav-link {
            color: rgba(255,255,255,0.85);
            transition: color .2s ease;
        }

        .app-navbar .nav-link:hover,
        .app-navbar .nav-link.active {
            color: #ffffff;
        }

        .app-card {
            border: 1px solid rgba(15, 23, 42, .08);
            border-radius: 1.5rem;
            background: var(--color-surface);
            box-shadow: 0 24px 50px rgba(15, 23, 42, .08);
        }

        .app-hero {
            border-radius: 1.75rem;
            background: linear-gradient(135deg, #4f46e5 0%, #2563eb 100%);
            color: #fff;
            box-shadow: 0 24px 60px rgba(79, 70, 229, .18);
        }

        .app-metric .card-body {
            min-height: 175px;
        }

        .app-metric .display-6 {
            font-size: 2.5rem;
        }

        .app-table thead {
            background: #eff6ff;
        }

        .app-table tbody tr:hover {
            background: rgba(79, 70, 229, .05);
        }

        .btn-soft-primary {
            color: #312e81;
            background: rgba(79, 70, 229, .12);
            border: 1px solid rgba(79, 70, 229, .16);
        }

        .btn-soft-primary:hover {
            background: rgba(79, 70, 229, .18);
        }

        .form-control,
        .form-select {
            border-radius: 1rem;
            border-color: rgba(15,23,42,.12);
        }

        .alert-custom {
            border-radius: 1rem;
            border: 1px solid rgba(34,170,250,.16);
            background: rgba(224, 242, 254, .82);
        }

        .auth-card {
            max-width: 460px;
            border-radius: 1.75rem;
            overflow: hidden;
            background: #ffffff;
        }

        .auth-hero {
            background: linear-gradient(135deg, #4f46e5 0%, #2563eb 100%);
            color: white;
        }

        .text-muted {
            color: var(--color-muted) !important;
        }

        .small {
            font-size: .9rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "›";
        }

        .attendance-chatbot { position: fixed; right: 20px; bottom: 20px; z-index: 1050; }
        .attendance-chatbot__toggle { width: 55px; height: 55px; border: 0; border-radius: 50%; background: linear-gradient(135deg, #4f46e5, #2563eb); color: #fff; font-size: 1.55rem; box-shadow: 0 12px 28px rgba(37, 99, 235, .35); cursor: pointer; transition: transform .2s ease, box-shadow .2s ease; }
        .attendance-chatbot__toggle:hover { transform: translateY(-2px); box-shadow: 0 16px 32px rgba(37, 99, 235, .42); }
        .attendance-chatbot__panel { position: absolute; right: 0; bottom: 68px; display: flex; width: 350px; height: 450px; overflow: hidden; flex-direction: column; border: 1px solid rgba(15, 23, 42, .1); border-radius: 18px; background: #fff; box-shadow: 0 20px 50px rgba(15, 23, 42, .2); opacity: 0; pointer-events: none; transform: translateY(12px) scale(.97); transform-origin: bottom right; transition: opacity .2s ease, transform .2s ease; }
        .attendance-chatbot.is-open .attendance-chatbot__panel { opacity: 1; pointer-events: auto; transform: translateY(0) scale(1); }
        .attendance-chatbot__header { display: flex; align-items: center; justify-content: space-between; padding: 15px 17px; background: linear-gradient(135deg, #4f46e5, #2563eb); color: #fff; }
        .attendance-chatbot__title { font-weight: 700; font-size: .98rem; }.attendance-chatbot__close { border: 0; background: transparent; color: #fff; font-size: 1.7rem; line-height: 1; cursor: pointer; }
        .attendance-chatbot__messages { display: flex; flex: 1; flex-direction: column; gap: 10px; overflow-y: auto; padding: 17px; background: #f8faff; }.attendance-chatbot__message { width: fit-content; max-width: 82%; padding: 10px 13px; border-radius: 14px; font-size: .9rem; line-height: 1.4; white-space: pre-wrap; }.attendance-chatbot__message--bot { align-self: flex-start; border-bottom-left-radius: 4px; background: #e9edff; color: #20225e; }.attendance-chatbot__message--user { align-self: flex-end; border-bottom-right-radius: 4px; background: #4f46e5; color: #fff; }
        .attendance-chatbot__form { display: flex; gap: 8px; padding: 12px; border-top: 1px solid #e5e7eb; background: #fff; }.attendance-chatbot__form input { min-width: 0; flex: 1; padding: 10px 12px; border: 1px solid #d7dbe8; border-radius: 10px; outline: none; font-size: .86rem; }.attendance-chatbot__form input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, .14); }.attendance-chatbot__form button { padding: 0 14px; border: 0; border-radius: 10px; background: #4f46e5; color: #fff; font-weight: 600; cursor: pointer; }.attendance-chatbot__form button:disabled { opacity: .6; cursor: wait; }

        @media (max-width: 767px) {
            .app-metric .display-6 {
                font-size: 2rem;
            }
            .attendance-chatbot { right: 14px; bottom: 14px; }
            .attendance-chatbot__panel { width: min(350px, calc(100vw - 28px)); height: min(450px, calc(100vh - 100px)); }
        }
    </style>
</head>
<body>
    @auth
    <nav class="navbar navbar-expand-lg navbar-dark app-navbar py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Smart Attendance</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">Departments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('faculties.*') ? 'active' : '' }}" href="{{ route('faculties.index') }}">Faculties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('qr.*') ? 'active' : '' }}" href="{{ route('qr.index') }}">QR Generator</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth

    <main class="container py-5">
        @if(session('success'))
            <div class="alert alert-success alert-custom shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    @auth
        @include('partials.chatbot-widget')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
