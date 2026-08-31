<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Faculty Portal | Smart Attendance')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --color-bg: #f8fafc;
            --color-primary: #4f46e5;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #4f46e5;
            --text-heading: #0f172a;
            --border-color: #e2e8f0;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--color-bg);
            background-image: radial-gradient(at 0% 0%, rgba(99,102,241,.07) 0px, transparent 50%), radial-gradient(at 100% 0%, rgba(6,182,212,.05) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-heading);
            -webkit-font-smoothing: antialiased;
        }
        .faculty-layout { display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .faculty-sidebar {
            position: fixed; top: 0; left: 0;
            width: 260px; height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            z-index: 1040; overflow-y: auto; overflow-x: hidden;
            transition: transform .25s ease;
        }
        .sidebar-header {
            display: flex; align-items: center; gap: 12px;
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .sidebar-logo-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, #4f46e5, #818cf8);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; color: #fff; flex-shrink: 0;
        }
        .sidebar-header h4 { margin: 0; font-size: .95rem; font-weight: 700; color: #f1f5f9; line-height: 1.2; }
        .sidebar-header small { font-size: .72rem; color: #64748b; }
        .sidebar-menu { flex: 1; padding: 12px 10px; display: flex; flex-direction: column; gap: 2px; }
        .sidebar-menu-label {
            font-size: .65rem; font-weight: 700; letter-spacing: .08em;
            text-transform: uppercase; color: #475569; padding: 10px 10px 4px; margin-top: 4px;
        }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 11px; padding: 9px 12px;
            border-radius: 9px; color: #94a3b8; text-decoration: none;
            font-size: .855rem; font-weight: 500; transition: all .15s ease;
        }
        .sidebar-menu a i { width: 18px; text-align: center; font-size: .95rem; flex-shrink: 0; }
        .sidebar-menu a:hover { background: var(--sidebar-hover); color: #e2e8f0; }
        .sidebar-menu a.active {
            background: var(--sidebar-active); color: #fff; font-weight: 600;
            box-shadow: 0 4px 12px rgba(79,70,229,.35);
        }
        .sidebar-footer { padding: 12px 10px; border-top: 1px solid rgba(255,255,255,.06); }
        .logout-btn {
            width: 100%; display: flex; align-items: center; gap: 11px; padding: 9px 12px;
            border-radius: 9px; color: #94a3b8; background: transparent; border: none;
            font-size: .855rem; font-weight: 500; cursor: pointer; transition: all .15s ease;
        }
        .logout-btn:hover { background: rgba(239,68,68,.15); color: #f87171; }

        /* MAIN */
        .faculty-main { margin-left: 260px; flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .faculty-topbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(248,250,252,.92); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 0 28px; height: 60px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .faculty-content { padding: 28px; flex: 1; }
        .faculty-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #818cf8);
            color: #fff; display: flex; align-items: center;
            justify-content: center; font-weight: 700; font-size: .9rem;
        }
        .btn-qr-gen {
            display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px;
            border-radius: 10px; background: linear-gradient(135deg, #4f46e5, #818cf8);
            color: #fff !important; font-size: .82rem; font-weight: 600;
            text-decoration: none; transition: all .2s;
            box-shadow: 0 4px 12px rgba(79,70,229,.25);
        }
        .btn-qr-gen:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(79,70,229,.4); }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,.6); backdrop-filter: blur(4px); z-index: 1030;
        }

        /* =========================================================
           SHARED UI SYSTEM (hero / cards / metrics / tables)
        ========================================================== */
        .app-hero {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            color: #fff;
            background: linear-gradient(120deg, #0f172a 0%, #1e1b4b 45%, #312e81 100%);
            border: 1px solid rgba(255,255,255,.08);
            box-shadow: 0 18px 38px -14px rgba(30,27,75,.55);
        }
        .app-hero::before {
            content: '';
            position: absolute; top: -55%; right: -8%;
            width: 340px; height: 340px; border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,.38) 0%, transparent 70%);
            pointer-events: none;
        }
        .app-hero > * { position: relative; z-index: 1; }

        .app-card {
            background: #fff;
            border: 1px solid var(--border-color) !important;
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 8px 24px -16px rgba(15,23,42,.14);
            transition: box-shadow .25s ease, transform .25s ease;
        }
        .app-card:hover { box-shadow: 0 4px 10px rgba(15,23,42,.05), 0 18px 34px -18px rgba(15,23,42,.22); }
        .app-card .card-header { border-radius: 16px 16px 0 0; border-color: var(--border-color) !important; }

        .metric-icon-wrapper {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.35rem; flex-shrink: 0;
        }
        .metric-icon-indigo  { background: rgba(79,70,229,.10);  color: #4f46e5; }
        .metric-icon-cyan    { background: rgba(8,145,178,.10);  color: #0e7490; }
        .metric-icon-emerald { background: rgba(5,150,105,.10);  color: #047857; }
        .metric-icon-amber   { background: rgba(217,119,6,.10);  color: #b45309; }

        /* soft tinted tiles used by quick actions */
        .action-tile-icon {
            width: 46px; height: 46px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; flex-shrink: 0;
        }
        .tile-indigo  { background: rgba(79,70,229,.10);  color: #4f46e5; }
        .tile-emerald { background: rgba(5,150,105,.10);  color: #047857; }
        .tile-cyan    { background: rgba(8,145,178,.10);  color: #0e7490; }
        .tile-violet  { background: rgba(124,58,237,.10); color: #6d28d9; }

        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border-color);
        }
        .table tbody td { border-color: #f1f5f9; }

        @media (max-width: 992px) {
            .faculty-sidebar { transform: translateX(-100%); }
            .faculty-sidebar.show { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .faculty-main { margin-left: 0; width: 100%; }
            .faculty-topbar { padding: 0 16px; }
            .faculty-content { padding: 16px; }
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="faculty-layout">

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="faculty-sidebar" id="facultySidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo-icon"><i class="bi bi-qr-code-scan"></i></div>
            <div>
                <h4>SmartAttendance</h4>
                <small>Faculty Portal</small>
            </div>
        </div>

        <nav class="sidebar-menu">

            <div class="sidebar-menu-label">Main</div>

            <a href="{{ route('faculty.dashboard') }}"
               class="{{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i><span>Dashboard</span>
            </a>

            <a href="{{ route('faculty.profile.edit') }}"
               class="{{ request()->routeIs('faculty.profile.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-tie"></i><span>My Profile</span>
            </a>

            <div class="sidebar-menu-label">Attendance</div>

            <a href="{{ route('faculty.attendance.index') }}"
               class="{{ request()->routeIs('faculty.attendance.index') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-column"></i><span>Attendance Sessions</span>
            </a>

            <a href="{{ route('faculty.attendance.manual') }}"
               class="{{ request()->routeIs('faculty.attendance.manual') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-list"></i><span>Manual Attendance</span>
            </a>

            <a href="{{ route('faculty.muster') }}"
               class="{{ request()->routeIs('faculty.muster') ? 'active' : '' }}">
                <i class="fa-solid fa-table-list"></i><span>Attendance Muster</span>
            </a>

            <div class="sidebar-menu-label">QR & Teaching</div>

            <a href="{{ route('faculty.qr.index') }}"
               class="{{ request()->routeIs('faculty.qr.*') ? 'active' : '' }}">
                <i class="fa-solid fa-qrcode"></i><span>QR Generator</span>
            </a>

            <a href="{{ route('faculty.subjects.index') }}"
               class="{{ request()->routeIs('faculty.subjects.*') ? 'active' : '' }}">
                <i class="fa-solid fa-book"></i><span>My Subjects</span>
            </a>

            <div class="sidebar-menu-label">Students</div>

            <a href="{{ route('faculty.students.index') }}"
               class="{{ request()->routeIs('faculty.students.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-graduate"></i><span>Students List</span>
            </a>

            <div class="sidebar-menu-label">Other</div>

            <a href="{{ route('notices.index') }}"
               class="{{ request()->routeIs('notices.*') ? 'active' : '' }}">
                <i class="fa-solid fa-bullhorn"></i><span>Notices & Updates</span>
            </a>

        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="faculty-main">
        <header class="faculty-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary btn-sm d-lg-none" id="sidebarToggle" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h5 class="mb-0 fw-bold d-none d-sm-block text-dark">
                    @yield('page-title', 'Faculty Portal')
                </h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('faculty.qr.index') }}" class="btn-qr-gen d-none d-sm-inline-flex">
                    <i class="bi bi-qr-code-scan"></i><span>Generate QR</span>
                </a>
                <div class="d-flex align-items-center gap-2">
                    <div class="faculty-avatar">
                        {{ auth()->user()->initial ?? 'F' }}
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold" style="font-size:.88rem;line-height:1.1;">
                            {{ auth()->user()->display_name ?? 'Faculty' }}
                        </span>
                        <small class="text-muted" style="font-size:.72rem;">Faculty</small>
                    </div>
                </div>
            </div>
        </header>

        <main class="faculty-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</div>

@auth
    @include('partials.chatbot-widget')
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggle  = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('facultySidebar');
    const overlay = document.getElementById('sidebarOverlay');
    function openSidebar()  { sidebar?.classList.add('show'); overlay?.classList.add('show'); }
    function closeSidebar() { sidebar?.classList.remove('show'); overlay?.classList.remove('show'); }
    toggle?.addEventListener('click', openSidebar);
    overlay?.addEventListener('click', closeSidebar);
</script>

@stack('scripts')
</body>
</html>
