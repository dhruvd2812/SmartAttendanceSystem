<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Student Dashboard | Smart Attendance')
    </title>

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        body {
            background-color: #f5f7fb;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* ==========================================================
           Sidebar
        ========================================================== */

        .student-sidebar {
            width: 260px;
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
        }

        .student-logo {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 22px;
            border-bottom: 1px solid #e5e7eb;
        }

        .student-logo h5 {
            margin: 0;
            font-weight: 700;
        }

        .student-menu {
            padding: 20px 12px;
        }

        .student-menu-title {
            font-size: 11px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            padding: 0 12px;
            margin-bottom: 10px;
        }

        .student-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #4b5563;
            padding: 11px 13px;
            margin-bottom: 5px;
            border-radius: 10px;
            transition: 0.2s;
        }

        .student-menu a:hover {
            background: #eef4ff;
            color: #2563eb;
        }

        .student-menu a.active {
            background: #2563eb;
            color: #ffffff;
        }

        .student-menu i {
            font-size: 18px;
            width: 22px;
        }

        /* ==========================================================
           Main Area
        ========================================================== */

        .student-main {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* ==========================================================
           Top Navbar
        ========================================================== */

        .student-navbar {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
        }

        .student-navbar-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }

        .student-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #2563eb;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .student-profile-name {
            font-size: 14px;
            font-weight: 600;
        }

        /* ==========================================================
           Content
        ========================================================== */

        .student-content {
            padding: 30px;
        }

        /* ==========================================================
           Mobile
        ========================================================== */

        @media (max-width: 991px) {

            .student-sidebar {
                width: 220px;
            }

            .student-main {
                margin-left: 220px;
            }

        }

        @media (max-width: 767px) {

            .student-sidebar {
                position: static;
                width: 100%;
                min-height: auto;
            }

            .student-main {
                margin-left: 0;
            }

            .student-navbar {
                padding: 0 15px;
            }

            .student-content {
                padding: 20px 15px;
            }

        }

    </style>

    {{-- Page-specific styles --}}
    @stack('styles')

</head>


<body>

    {{-- ==========================================================
         SIDEBAR
    =========================================================== --}}

    <aside class="student-sidebar">

        {{-- Logo --}}

        <div class="student-logo">

            <div>

                <h5>
                    🎓 Smart Attendance
                </h5>

                <small class="text-muted">
                    Student Portal
                </small>

            </div>

        </div>


        {{-- Menu --}}

        <nav class="student-menu">

            <div class="student-menu-title">
                Main Menu
            </div>


            {{-- Dashboard --}}

            <a
                href="{{ route('student.dashboard') }}"
                class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
            >

                <i class="bi bi-house-door"></i>

                <span>
                    Dashboard
                </span>

            </a>


            {{-- My Profile --}}

            <a href="#">

                <i class="bi bi-person"></i>

                <span>
                    My Profile
                </span>

            </a>


            {{-- My Attendance --}}

            <a
                href="{{ route('student.attendance') }}"
                class="{{ request()->routeIs('student.attendance') ? 'active' : '' }}"
            >

                <i class="bi bi-bar-chart"></i>

                <span>
                    My Attendance
                </span>

            </a>


            {{-- Scan QR Code --}}

            <a
                href="{{ route('student.scan-qr') }}"
                class="{{ request()->routeIs('student.scan-qr') ? 'active' : '' }}"
            >

                <i class="bi bi-qr-code-scan"></i>

                <span>
                    Scan QR Code
                </span>

            </a>


            {{-- Attendance History --}}

            <a href="#">

                <i class="bi bi-calendar-check"></i>

                <span>
                    Attendance History
                </span>

            </a>


            {{-- My Subjects --}}

            <a href="#">

                <i class="bi bi-book"></i>

                <span>
                    My Subjects
                </span>

            </a>


            {{-- Timetable --}}

            <a href="#">

                <i class="bi bi-calendar3"></i>

                <span>
                    Timetable
                </span>

            </a>


            {{-- Notices --}}

            <a href="#">

                <i class="bi bi-megaphone"></i>

                <span>
                    Notices
                </span>

            </a>


            <hr>


            {{-- Logout --}}

            <form
                action="{{ route('logout') }}"
                method="POST"
            >

                @csrf

                <button
                    type="submit"
                    class="btn btn-link text-danger text-decoration-none w-100 text-start"
                >

                    <i class="bi bi-box-arrow-right me-2"></i>

                    Logout

                </button>

            </form>

        </nav>

    </aside>


    {{-- ==========================================================
         MAIN AREA
    =========================================================== --}}

    <main class="student-main">


        {{-- ======================================================
             TOP NAVBAR
        ======================================================= --}}

        <header class="student-navbar">

            <div class="student-navbar-title">

                @yield('page-title', 'Student Dashboard')

            </div>


            <div class="student-profile">

                <div class="student-avatar">

                    {{ strtoupper(substr($student->first_name ?? auth()->user()->name, 0, 1)) }}

                </div>

                <div>

                    <div class="student-profile-name">

                        {{ $student->first_name ?? auth()->user()->name }}

                        {{ $student->last_name ?? '' }}

                    </div>

                    <small class="text-muted">
                        Student
                    </small>

                </div>

            </div>

        </header>


        {{-- ======================================================
             PAGE CONTENT
        ======================================================= --}}

        <div class="student-content">

            {{-- Success Message --}}

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


            {{-- Error Message --}}

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


            {{-- Page Content --}}

            @yield('content')

        </div>

    </main>


    {{-- Bootstrap JS --}}

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


    {{-- Page-specific scripts --}}

    @stack('scripts')

</body>

</html>