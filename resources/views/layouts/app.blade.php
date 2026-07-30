<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        @media (max-width: 767px) {
            .app-metric .display-6 {
                font-size: 2rem;
            }
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
