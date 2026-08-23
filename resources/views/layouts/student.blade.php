<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Student Portal | Smart Attendance')
    </title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>
        :root {
            --color-bg: #f8fafc;
            --color-surface: #ffffff;
            --color-primary: #4f46e5;
            --color-primary-dark: #3730a3;
            --color-primary-light: #e0e7ff;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #4f46e5;
            --text-heading: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --border-radius-lg: 1.25rem;
            --shadow-card: 0 10px 30px -4px rgba(15, 23, 42, 0.06), 0 4px 10px -2px rgba(15, 23, 42, 0.03);
            --shadow-hover: 0 20px 40px -6px rgba(79, 70, 229, 0.12), 0 8px 16px -4px rgba(15, 23, 42, 0.04);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--color-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.07) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(6, 182, 212, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-heading);
            -webkit-font-smoothing: antialiased;
        }

        .student-layout {
            display: flex;
            min-height: 100vh;
        }

        /* =========================================================
           SIDEBAR
        ========================================================== */

        .student-sidebar {
            width: 270px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            color: #ffffff;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            z-index: 1040;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
        }

        .sidebar-header h4 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #ffffff;
        }

        .sidebar-header small {
            color: #818cf8;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .sidebar-menu {
            padding: 16px 12px;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-menu-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            padding: 12px 14px 6px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            margin-bottom: 4px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }

        .sidebar-menu a:hover {
            background: var(--sidebar-hover);
            color: #ffffff;
            transform: translateX(3px);
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.35);
        }

        .sidebar-menu a i {
            width: 20px;
            text-align: center;
            font-size: 1.05rem;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.15);
        }

        .logout-btn {
            width: 100%;
            border: 1px solid rgba(239, 68, 68, 0.2);
            background: rgba(239, 68, 68, 0.08);
            color: #f87171;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            cursor: pointer;
            text-align: left;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
        }

        /* =========================================================
           MAIN CONTENT AREA
        ========================================================== */

        .student-main {
            margin-left: 270px;
            width: calc(100% - 270px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .student-topbar {
            height: 72px;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .student-user-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #ffffff;
            padding: 6px 14px 6px 8px;
            border-radius: 9999px;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .student-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .student-content {
            padding: 32px;
            flex: 1;
        }

        /* =========================================================
           COMMON REUSABLE APP CARDS & HERO
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
            background: radial-gradient(circle, rgba(99, 102, 241, 0.35) 0%, transparent 70%);
            pointer-events: none;
        }

        .app-card,
        .card {
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-lg);
            background: #ffffff;
            box-shadow: var(--shadow-card);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-hover);
        }

        .btn-scan-qr {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border: 0;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            font-weight: 600;
            border-radius: 10px;
            padding: 8px 18px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-scan-qr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(16, 185, 129, 0.45);
            color: #ffffff;
        }

        /* =========================================================
           CHATBOT STYLES
        ========================================================== */

        .attendance-chatbot {
            position: fixed;
            right: 24px;
            bottom: 24px;
            z-index: 1060;
        }

        .attendance-chatbot__toggle {
            width: 58px;
            height: 58px;
            border: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            color: #fff;
            font-size: 1.6rem;
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.45);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .attendance-chatbot__toggle:hover {
            transform: scale(1.08) translateY(-2px);
            box-shadow: 0 16px 36px rgba(79, 70, 229, 0.55);
        }

        .attendance-chatbot__panel {
            position: absolute;
            right: 0;
            bottom: 72px;
            display: flex;
            width: 380px;
            height: 520px;
            overflow: hidden;
            flex-direction: column;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 25px 60px -10px rgba(15, 23, 42, 0.25);
            opacity: 0;
            pointer-events: none;
            transform: translateY(16px) scale(0.95);
            transform-origin: bottom right;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .attendance-chatbot.is-open .attendance-chatbot__panel {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1030;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 992px) {
            .student-sidebar {
                transform: translateX(-100%);
            }

            .student-sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .student-main {
                margin-left: 0;
                width: 100%;
            }

            .student-topbar {
                padding: 0 16px;
            }

            .student-content {
                padding: 20px 16px;
            }
        }
    </style>

    @stack('styles')

</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- Student Layout --}}
    <div class="student-layout">

        {{-- Sidebar --}}
        <aside class="student-sidebar" id="studentSidebar">

            <div class="sidebar-header">
                <div class="sidebar-logo-icon">
                    <i class="bi bi-qr-code-scan"></i>
                </div>
                <div>
                    <h4>SmartAttendance</h4>
                    <small>Student Portal</small>
                </div>
            </div>

            {{-- Sidebar Menu --}}
            <nav class="sidebar-menu">

                <div class="sidebar-menu-label">Main Navigation</div>

                <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('student.scan-qr') }}" class="{{ request()->routeIs('student.scan-qr') ? 'active' : '' }}" style="background: rgba(16, 185, 129, 0.12); color: #10b981; font-weight: 600; border: 1px solid rgba(16, 185, 129, 0.2);">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>Scan QR Attendance</span>
                </a>

                <a href="{{ route('student.profile') }}" class="{{ request()->routeIs('student.profile') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i>
                    <span>My Profile</span>
                </a>

                <div class="sidebar-menu-label">Attendance & Academics</div>

                <a href="{{ route('student.attendance') }}" class="{{ request()->routeIs('student.attendance') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-column"></i>
                    <span>My Attendance</span>
                </a>

                <a href="{{ route('student.attendance.history') }}" class="{{ request()->routeIs('student.attendance.history') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Attendance History</span>
                </a>

                <a href="{{ route('student.subjects') }}" class="{{ request()->routeIs('student.subjects') ? 'active' : '' }}">
                    <i class="fa-solid fa-book"></i>
                    <span>My Subjects</span>
                </a>

                <a href="{{ route('student.timetable') }}" class="{{ request()->routeIs('student.timetable') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar"></i>
                    <span>Timetable</span>
                </a>

                <a href="{{ route('student.notices') }}" class="{{ request()->routeIs('student.notices') ? 'active' : '' }}">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span>Notices & Updates</span>
                </a>

            </nav>

            {{-- Logout --}}
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

        </aside>

        {{-- Main Area --}}
        <div class="student-main">

            {{-- Top Bar --}}
            <header class="student-topbar">

                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary btn-sm d-lg-none" id="sidebarToggle" type="button">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <h5 class="mb-0 fw-bold d-none d-sm-block text-dark">
                        @yield('page-title', 'Student Portal')
                    </h5>
                </div>

                <div class="d-flex align-items-center gap-3">

                    <a href="{{ route('student.scan-qr') }}" class="btn-scan-qr d-none d-sm-inline-flex">
                        <i class="bi bi-qr-code-scan"></i>
                        <span>Scan QR</span>
                    </a>

                    <div class="student-user-badge">
                        <div class="student-avatar">
                            {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-bold" style="font-size: 0.88rem; line-height: 1.1;">
                                {{ auth()->user()->name ?? 'Student' }}
                            </span>
                            <small class="text-muted" style="font-size: 0.72rem;">Student</small>
                        </div>
                    </div>

                </div>

            </header>

            {{-- Page Content --}}
            <main class="student-content">
                @if(session('success'))
                    <div class="alert alert-success alert-custom alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-custom alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>

    </div>

    {{-- Chatbot widget --}}
    @auth
        @include('partials.chatbot-widget')
    @endauth

    {{-- Bootstrap JavaScript --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('studentSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (toggleBtn && sidebar && overlay) {
                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
        });
    </script>

    @stack('scripts')

</body>

</html>