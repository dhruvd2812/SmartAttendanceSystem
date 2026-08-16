<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Student Dashboard | Smart Attendance')
    </title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>

        body {
            margin: 0;
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        /* ==============================
           TOP NAVBAR
        ============================== */

        .student-navbar {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .brand {
            font-size: 21px;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
        }

        .student-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* ==============================
           SIDEBAR
        ============================== */

        .student-sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            bottom: 0;
            width: 250px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            padding: 20px 15px;
            overflow-y: auto;
        }

        .sidebar-title {
            font-size: 12px;
            color: #9ca3af;
            text-transform: uppercase;
            font-weight: 700;
            margin: 10px 12px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            margin-bottom: 5px;
            border-radius: 10px;
            color: #374151;
            text-decoration: none;
            transition: 0.2s;
        }

        .sidebar-link:hover {
            background: #eff6ff;
            color: #2563eb;
        }

        .sidebar-link.active {
            background: #2563eb;
            color: #ffffff;
        }

        .sidebar-icon {
            width: 22px;
            text-align: center;
        }

        /* ==============================
           MAIN CONTENT
        ============================== */

        .student-main {
            margin-left: 250px;
            padding: 95px 30px 30px;
            min-height: 100vh;
        }

        /* ==============================
           CARDS
        ============================== */

        .app-card {
            background: #ffffff;
            border-radius: 16px;
        }

        .app-hero {
            background: linear-gradient(
                135deg,
                #2563eb,
                #4f46e5
            );
            color: white;
            border-radius: 18px;
        }

        .app-metric {
            transition: 0.2s;
        }

        .app-metric:hover {
            transform: translateY(-3px);
        }

        /* ==============================
           MOBILE
        ============================== */

        .mobile-menu-btn {
            display: none;
        }

        @media (max-width: 991px) {

            .student-sidebar {
                transform: translateX(-100%);
                transition: 0.3s;
                z-index: 1100;
            }

            .student-sidebar.show {
                transform: translateX(0);
            }

            .student-main {
                margin-left: 0;
                padding: 90px 15px 25px;
            }

            .mobile-menu-btn {
                display: inline-block;
                margin-right: 12px;
                border: none;
                background: transparent;
                font-size: 22px;
            }

            .brand {
                font-size: 18px;
            }

            .student-navbar {
                padding: 0 15px;
            }

        }

    </style>

    @stack('styles')

</head>

<body>

    {{-- =====================================================
         TOP NAVBAR
    ====================================================== --}}

    <nav class="student-navbar">

        <div class="d-flex align-items-center">

            <button
                type="button"
                class="mobile-menu-btn"
                onclick="toggleStudentSidebar()"
            >
                <i class="fas fa-bars"></i>
            </button>

            <a href="{{ route('student.dashboard') }}" class="brand">

                <i class="fas fa-graduation-cap"></i>

                Smart Attendance

            </a>

        </div>


        {{-- Student User --}}

        <div class="student-user">

            <div class="text-end d-none d-sm-block">

                <div class="fw-semibold">

                    {{ $student->first_name ?? auth()->user()->name }}

                    {{ $student->last_name ?? '' }}

                </div>

                <small class="text-muted">

                    Student

                </small>

            </div>


            <div class="student-avatar">

                {{ strtoupper(substr(
                    $student->first_name ?? auth()->user()->name,
                    0,
                    1
                )) }}

            </div>


            <form
                method="POST"
                action="{{ route('logout') }}"
                class="ms-2"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-danger btn-sm"
                >

                    <i class="fas fa-sign-out-alt"></i>

                    <span class="d-none d-md-inline">
                        Logout
                    </span>

                </button>

            </form>

        </div>

    </nav>


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    <aside id="studentSidebar" class="student-sidebar">

        <div class="sidebar-title">
            Student Menu
        </div>


        {{-- Dashboard --}}

        <a
            href="{{ route('student.dashboard') }}"
            class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
        >

            <span class="sidebar-icon">
                <i class="fas fa-home"></i>
            </span>

            Dashboard

        </a>


        {{-- Profile --}}

        <a
            href="#"
            class="sidebar-link"
        >

            <span class="sidebar-icon">
                <i class="fas fa-user"></i>
            </span>

            My Profile

        </a>


        {{-- Attendance --}}

        <a
            href="#"
            class="sidebar-link"
        >

            <span class="sidebar-icon">
                <i class="fas fa-chart-bar"></i>
            </span>

            My Attendance

        </a>


        {{-- Scan QR --}}

        <a
            href="#"
            class="sidebar-link"
        >

            <span class="sidebar-icon">
                <i class="fas fa-qrcode"></i>
            </span>

            Scan QR Code

        </a>


        {{-- Attendance History --}}

        <a
            href="#"
            class="sidebar-link"
        >

            <span class="sidebar-icon">
                <i class="fas fa-calendar-check"></i>
            </span>

            Attendance History

        </a>


        {{-- Subjects --}}

        <a
            href="#"
            class="sidebar-link"
        >

            <span class="sidebar-icon">
                <i class="fas fa-book"></i>
            </span>

            My Subjects

        </a>


        {{-- Timetable --}}

        <a
            href="#"
            class="sidebar-link"
        >

            <span class="sidebar-icon">
                <i class="fas fa-calendar-days"></i>
            </span>

            Timetable

        </a>


        {{-- Notices --}}

        <a
            href="#"
            class="sidebar-link"
        >

            <span class="sidebar-icon">
                <i class="fas fa-bullhorn"></i>
            </span>

            Notices

        </a>


        <hr>


        {{-- Logout --}}

        <form
            method="POST"
            action="{{ route('logout') }}"
        >

            @csrf

            <button
                type="submit"
                class="sidebar-link border-0 bg-transparent w-100 text-start"
            >

                <span class="sidebar-icon">
                    <i class="fas fa-right-from-bracket"></i>
                </span>

                Logout

            </button>

        </form>

    </aside>


    {{-- =====================================================
         MAIN CONTENT
    ====================================================== --}}

    <main class="student-main">

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        @yield('content')

    </main>


    {{-- Bootstrap JS --}}

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>


    {{-- Sidebar JS --}}

    <script>

        function toggleStudentSidebar()
        {
            const sidebar =
                document.getElementById('studentSidebar');

            sidebar.classList.toggle('show');
        }

    </script>


    @stack('scripts')

</body>

</html>