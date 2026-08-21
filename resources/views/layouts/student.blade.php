<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Student Dashboard | Smart Attendance')
    </title>

    {{-- Bootstrap 5 --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Font Awesome --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

</head>
<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background: #f5f7fb;
    }

    .student-layout {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar */

    .student-sidebar {
        width: 260px;
        min-height: 100vh;
        background: #111827;
        color: #ffffff;
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 25px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar-header h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
    }

    .sidebar-header small {
        color: #9ca3af;
    }

    .sidebar-menu {
        padding: 20px 12px;
        flex: 1;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 13px 15px;
        margin-bottom: 6px;
        color: #d1d5db;
        text-decoration: none;
        border-radius: 8px;
        transition: 0.2s;
    }

    .sidebar-menu a:hover {
        background: #1f2937;
        color: #ffffff;
    }

    .sidebar-menu a i {
        width: 20px;
        text-align: center;
    }

    .sidebar-footer {
        padding: 15px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .logout-btn {
        width: 100%;
        border: none;
        background: transparent;
        color: #d1d5db;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 13px 15px;
        border-radius: 8px;
        cursor: pointer;
        text-align: left;
    }

    .logout-btn:hover {
        background: #1f2937;
        color: #ffffff;
    }


    /* Main Area */

    .student-main {
        margin-left: 260px;
        width: calc(100% - 260px);
        min-height: 100vh;
    }


    /* Top Bar */

    .student-topbar {
        height: 70px;
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .student-user {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
    }

    .student-user i {
        font-size: 28px;
    }


    /* Content */

    .student-content {
        padding: 30px;
    }


    /* Responsive */

    @media (max-width: 992px) {

        .student-sidebar {
            width: 220px;
        }

        .student-main {
            margin-left: 220px;
            width: calc(100% - 220px);
        }

    }


    @media (max-width: 768px) {

        .student-sidebar {
            position: relative;
            width: 100%;
            min-height: auto;
        }

        .student-layout {
            display: block;
        }

        .student-main {
            margin-left: 0;
            width: 100%;
        }

        .student-topbar {
            padding: 0 15px;
        }

        .student-content {
            padding: 15px;
        }

    }

</style>
<body>

    {{-- Student Layout --}}
    <div class="student-layout">

        {{-- Sidebar --}}
        <aside class="student-sidebar">

            <div class="sidebar-header">

                <h4>
                    Smart Attendance
                </h4>

                <small>
                    Student Portal
                </small>

            </div>


            {{-- Sidebar Menu --}}
            <nav class="sidebar-menu">

                <a href="{{ route('student.dashboard') }}">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>

		<a href="{{ route('student.profile') }}">
  		  <i class="fa-solid fa-user"></i>
    		  <span>My Profile</span>
		</a>

                <a href="{{ route('student.attendance') }}">
                    <i class="fa-solid fa-chart-column"></i>
                    <span>My Attendance</span>
                </a>

                <a href="{{ route('student.scan-qr') }}">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>Scan QR Code</span>
                </a>

                <a href="{{ route('student.attendance.history') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Attendance History</span>
                </a>

                <a href="{{ route('student.subjects') }}">
                    <i class="fa-solid fa-book"></i>
                    <span>My Subjects</span>
                </a>

                <a href="{{ route('student.timetable') }}">
                    <i class="fa-solid fa-calendar"></i>
                    <span>Timetable</span>
                </a>

                <a href="{{ route('student.notices') }}">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span>Notices</span>
                </a>

            </nav>


            {{-- Logout --}}
            <div class="sidebar-footer">

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="logout-btn"
                    >

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

                <div>

                    <h5 class="mb-0">
                        Student Dashboard
                    </h5>

                </div>


                <div class="student-user">

                    <i class="fa-solid fa-user-circle"></i>

                    <span>
                        {{ $student->first_name ?? 'Student' }}
                    </span>

                </div>

            </header>


            {{-- Page Content --}}
            <main class="student-content">

                @yield('content')

            </main>

        </div>

    </div>


    {{-- Bootstrap JavaScript --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>