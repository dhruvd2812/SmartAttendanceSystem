@extends('layouts.app')

@section('title', 'Faculty Dashboard')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Faculty Dashboard
            </h2>

            <p class="text-muted mb-0">
                Welcome back,
                <strong>{{ $user->name ?? 'Faculty' }}</strong> 👋
            </p>

        </div>

        <div>

            <span class="badge bg-primary px-3 py-2">
                Faculty
            </span>

        </div>

    </div>


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}

    <div class="row g-4 mb-4">

        {{-- My Subjects --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                My Subjects
                            </p>

                            <h3 class="fw-bold mb-0">
                                0
                            </h3>

                        </div>

                        <div class="fs-1 text-primary">
                            📚
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Students --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Total Students
                            </p>

                            <h3 class="fw-bold mb-0">
                                0
                            </h3>

                        </div>

                        <div class="fs-1 text-success">
                            👨‍🎓
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Today's Classes --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Today's Classes
                            </p>

                            <h3 class="fw-bold mb-0">
                                0
                            </h3>

                        </div>

                        <div class="fs-1 text-warning">
                            📅
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Attendance Sessions --}}
        <div class="col-md-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Attendance Sessions
                            </p>

                            <h3 class="fw-bold mb-0">
                                0
                            </h3>

                        </div>

                        <div class="fs-1 text-danger">
                            📝
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MAIN DASHBOARD
    ========================================================== --}}

    <div class="row g-4">


        {{-- =====================================================
             ASSIGNED SUBJECTS
        ====================================================== --}}

        <div class="col-lg-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        📚 My Assigned Subjects
                    </h5>

                </div>

                <div class="card-body">

                    <div class="text-center py-5">

                        <div class="fs-1 mb-3">
                            📖
                        </div>

                        <h5 class="fw-semibold">
                            No Subjects Assigned
                        </h5>

                        <p class="text-muted mb-0">
                            Your assigned subjects will appear here.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             QUICK ACTIONS
        ====================================================== --}}

        <div class="col-lg-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        ⚡ Quick Actions
                    </h5>

                </div>

                <div class="card-body">


                    {{-- =========================================
                         START ATTENDANCE
                    ========================================== --}}

                    <div class="text-center mb-4">

                        <div class="fs-1 mb-2">
                            📝
                        </div>

                        <h5 class="fw-bold mb-2">
                            Start Attendance?
                        </h5>

                        <p class="text-muted small mb-3">
                            Do you want to start a new attendance
                            session for your class?
                        </p>


                        <div class="d-flex justify-content-center gap-2">


                            {{-- YES --}}
                            <a
                                href="{{ route('qr.index') }}"
                                class="btn btn-success px-4"
                            >

                                <i class="bi bi-check-circle me-1"></i>

                                YES

                            </a>


                            {{-- NO --}}
                            <a
                                href="{{ route('faculty.dashboard') }}"
                                class="btn btn-outline-secondary px-4"
                            >

                                <i class="bi bi-x-circle me-1"></i>

                                NO

                            </a>

                        </div>

                    </div>


                    <hr>


                    {{-- =========================================
                         OTHER ACTIONS
                    ========================================== --}}

                    <div class="d-grid gap-3 mt-3">


                        {{-- Faculty Management --}}
                        <a
                            href="{{ route('faculties.index') }}"
                            class="btn btn-outline-primary"
                        >

                            👨‍🏫 Faculty Management

                        </a>


                        {{-- Students --}}
                        <a
                            href="{{ route('students.index') }}"
                            class="btn btn-outline-success"
                        >

                            👨‍🎓 View Students

                        </a>


                        {{-- Departments --}}
                        <a
                            href="{{ route('departments.index') }}"
                            class="btn btn-outline-secondary"
                        >

                            🏢 Departments

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ATTENDANCE MANAGEMENT
    ========================================================== --}}

    <div class="row g-4 mt-1">

        <div class="col-12">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        📝 Attendance Management
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row text-center">


                        {{-- =====================================
                             QR ATTENDANCE
                        ====================================== --}}

                        <div class="col-md-4 mb-3 mb-md-0">

                            <div class="p-3 border rounded h-100">

                                <div class="fs-2 mb-2">
                                    🔲
                                </div>

                                <h6 class="fw-bold">
                                    QR Attendance
                                </h6>

                                <p class="text-muted small">
                                    Start an attendance session
                                    and generate a QR code.
                                </p>


                                {{-- Start Attendance --}}
                                <a
                                    href="{{ route('qr.index') }}"
                                    class="btn btn-sm btn-primary"
                                >

                                    <i class="bi bi-play-circle me-1"></i>

                                    Start Attendance

                                </a>

                            </div>

                        </div>


                        {{-- =====================================
                             STUDENT ATTENDANCE
                        ====================================== --}}

                        <div class="col-md-4 mb-3 mb-md-0">

                            <div class="p-3 border rounded h-100">

                                <div class="fs-2 mb-2">
                                    👥
                                </div>

                                <h6 class="fw-bold">
                                    Student Attendance
                                </h6>

                                <p class="text-muted small">
                                    View attendance records
                                    of students.
                                </p>

                                <button
                                    class="btn btn-sm btn-success"
                                    disabled
                                >

                                    Coming Soon

                                </button>

                            </div>

                        </div>


                        {{-- =====================================
                             ATTENDANCE REPORTS
                        ====================================== --}}

                        <div class="col-md-4">

                            <div class="p-3 border rounded h-100">

                                <div class="fs-2 mb-2">
                                    📊
                                </div>

                                <h6 class="fw-bold">
                                    Attendance Reports
                                </h6>

                                <p class="text-muted small">
                                    Analyze and generate
                                    attendance reports.
                                </p>

                                <button
                                    class="btn btn-sm btn-warning"
                                    disabled
                                >

                                    Coming Soon

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection