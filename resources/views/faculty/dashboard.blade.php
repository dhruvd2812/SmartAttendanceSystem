@extends('layouts.app')

@section('title', 'Faculty Portal | Smart Attendance')

@section('content')

<div class="container-fluid py-3">

    {{-- ========================================================= --}}
    {{-- SUCCESS / ERROR MESSAGES --}}
    {{-- ========================================================= --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    @endif


    {{-- ========================================================= --}}
    {{-- HERO BANNER --}}
    {{-- ========================================================= --}}

    <div class="app-hero p-4 p-md-5 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="badge bg-white text-primary fw-bold px-3 py-1">
                    Faculty Portal
                </span>

                <span class="text-white-50 small">
                    • Instructor Dashboard
                </span>

            </div>

            <h1 class="display-6 fw-bold mb-2 text-white">
                Hello, {{ $user->name ?? 'Faculty Member' }} 👋
            </h1>

            <p class="mb-0 text-white-50" style="max-width: 600px;">
                Manage your class subjects, launch live QR attendance sessions,
                manually mark attendance, track lecture hours, and consult
                the AI assistant.
            </p>

        </div>


        {{-- HERO BUTTONS --}}

        <div class="d-flex flex-wrap gap-2">

            {{-- QR SESSION --}}

            <a href="{{ route('faculty.qr.index') }}"
               class="btn btn-light text-primary fw-bold px-4 py-3 d-inline-flex align-items-center gap-2 shadow-sm rounded-3">

                <i class="bi bi-qr-code-scan fs-5"></i>

                <span>
                    Launch QR Session
                </span>

            </a>


            {{-- MANUAL ATTENDANCE --}}

            <a href="{{ route('faculty.attendance.manual') }}"
               class="btn btn-success fw-bold px-4 py-3 d-inline-flex align-items-center gap-2 shadow-sm rounded-3">

                <i class="bi bi-pencil-square fs-5"></i>

                <span>
                    Manual Attendance
                </span>

            </a>


            {{-- CLASS ROSTER --}}

            <a href="{{ route('faculty.students.index') }}"
               class="btn btn-outline-light fw-semibold px-4 py-3 d-inline-flex align-items-center gap-2 rounded-3">

                <i class="bi bi-people"></i>

                <span>
                    Class Roster
                </span>

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- METRIC STAT CARDS --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">


        {{-- SUBJECTS --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card app-card border-0 h-100">

                <div class="card-body p-4 d-flex align-items-center justify-content-between">

                    <div>

                        <p class="text-muted small fw-semibold mb-1">
                            My Subjects
                        </p>

                        <h3 class="fw-bold text-dark mb-0">
                            {{ $subjectCount }}
                        </h3>

                        <small class="text-primary fw-medium">
                            Teaching Courses
                        </small>

                    </div>

                    <div class="metric-icon-wrapper metric-icon-indigo">

                        <i class="bi bi-journal-bookmark-fill"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- STUDENTS --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card app-card border-0 h-100">

                <div class="card-body p-4 d-flex align-items-center justify-content-between">

                    <div>

                        <p class="text-muted small fw-semibold mb-1">
                            Enrolled Students
                        </p>

                        <h3 class="fw-bold text-dark mb-0">
                            {{ $studentCount }}
                        </h3>

                        <small class="text-muted">
                            Across All Classes
                        </small>

                    </div>

                    <div class="metric-icon-wrapper metric-icon-cyan">

                        <i class="bi bi-mortarboard-fill"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- TODAY'S CLASSES --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card app-card border-0 h-100">

                <div class="card-body p-4 d-flex align-items-center justify-content-between">

                    <div>

                        <p class="text-muted small fw-semibold mb-1">
                            Today's Lectures
                        </p>

                        <h3 class="fw-bold text-dark mb-0">
                            {{ $todayClasses }}
                        </h3>

                        <small class="text-muted">
                            Scheduled Today
                        </small>

                    </div>

                    <div class="metric-icon-wrapper metric-icon-emerald">

                        <i class="bi bi-calendar-event-fill"></i>

                    </div>

                </div>

            </div>

        </div>


        {{-- QR ATTENDANCE SESSIONS --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card app-card border-0 h-100">

                <div class="card-body p-4 d-flex align-items-center justify-content-between">

                    <div>

                        <p class="text-muted small fw-semibold mb-1">
                            QR Sessions Conducted
                        </p>

                        <h3 class="fw-bold text-dark mb-0">
                            {{ $attendanceSessionCount }}
                        </h3>

                        <small class="text-success fw-semibold">

                            <i class="bi bi-check2-all me-1"></i>

                            Completed

                        </small>

                    </div>

                    <div class="metric-icon-wrapper metric-icon-amber"
                         style="background: rgba(245, 158, 11, 0.12); color: #d97706;">

                        <i class="bi bi-broadcast"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- QUICK ACTION SHORTCUTS --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        <div class="col-12">

            <div class="card app-card border-0 shadow-sm">


                {{-- QUICK ACTION HEADER --}}

                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">

                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">

                        <i class="bi bi-lightning-charge-fill text-primary"></i>

                        Faculty Quick Actions

                    </h5>


                    <a href="{{ route('faculty.subjects.index') }}"
                       class="btn btn-sm btn-outline-primary">

                        Manage Subjects

                    </a>

                </div>


                {{-- QUICK ACTION BODY --}}

                <div class="card-body p-4">

                    <div class="row g-3">


                        {{-- ================================================= --}}
                        {{-- GENERATE QR ATTENDANCE --}}
                        {{-- ================================================= --}}

                        <div class="col-sm-6 col-lg-3">

                            <a href="{{ route('faculty.qr.index') }}"
                               class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100">

                                <div class="rounded-3 bg-primary text-white p-3 fs-4 d-flex align-items-center justify-content-center">

                                    <i class="bi bi-qr-code-scan"></i>

                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Generate Attendance QR
                                    </h6>

                                    <small class="text-muted">
                                        Start live scanning in class
                                    </small>

                                </div>

                            </a>

                        </div>


                        {{-- ================================================= --}}
                        {{-- MANUAL ATTENDANCE --}}
                        {{-- ================================================= --}}

                        <div class="col-sm-6 col-lg-3">

                            <a href="{{ route('faculty.attendance.manual') }}"
                               class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100">

                                <div class="rounded-3 bg-success text-white p-3 fs-4 d-flex align-items-center justify-content-center">

                                    <i class="bi bi-pencil-square"></i>

                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Manual Attendance
                                    </h6>

                                    <small class="text-muted">
                                        Mark student attendance manually
                                    </small>

                                </div>

                            </a>

                        </div>


                        {{-- ================================================= --}}
                        {{-- STUDENT ATTENDANCE ROSTER --}}
                        {{-- ================================================= --}}

                        <div class="col-sm-6 col-lg-3">

                            <a href="{{ route('faculty.attendance.index') }}"
                               class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100">

                                <div class="rounded-3 bg-info text-white p-3 fs-4 d-flex align-items-center justify-content-center">

                                    <i class="bi bi-people-fill"></i>

                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        Student Attendance Roster
                                    </h6>

                                    <small class="text-muted">
                                        Review attendance percentages
                                    </small>

                                </div>

                            </a>

                        </div>


                        {{-- ================================================= --}}
                        {{-- AI ASSISTANT --}}
                        {{-- ================================================= --}}

                        <div class="col-sm-6 col-lg-3">

                            <div role="button"
                                 onclick="document.querySelector('[data-chat-toggle]')?.click()"
                                 class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100"
                                 style="cursor: pointer;">

                                <div class="rounded-3 bg-indigo text-white p-3 fs-4 d-flex align-items-center justify-content-center"
                                     style="background: #4f46e5;">

                                    <i class="bi bi-robot"></i>

                                </div>

                                <div>

                                    <h6 class="fw-bold mb-1">
                                        AI Assistant Copilot
                                    </h6>

                                    <small class="text-muted">
                                        Instant class & student stats
                                    </small>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ASSIGNED SUBJECTS TABLE --}}
    {{-- ========================================================= --}}

    <div class="card app-card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">

            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">

                <i class="bi bi-book-half text-primary"></i>

                My Assigned Courses & Subjects

            </h5>

            <span class="badge bg-primary-subtle text-primary">

                {{ $subjectCount }} subjects

            </span>

        </div>


        <div class="card-body p-0">

            @if($subjects->isNotEmpty())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    Subject Name
                                </th>

                                <th>
                                    Subject Code
                                </th>

                                <th>
                                    Semester
                                </th>

                                <th class="pe-4">
                                    Enrolled Students
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($subjects as $subject)

                                <tr>

                                    <td class="ps-4 fw-bold text-dark">

                                        {{ $subject->name }}

                                    </td>


                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $subject->code ?? '-' }}

                                        </span>

                                    </td>


                                    <td>

                                        <span class="badge bg-secondary-subtle text-secondary">

                                            Semester {{ $subject->semester ?? '-' }}

                                        </span>

                                    </td>


                                    <td class="pe-4 fw-semibold text-primary">

                                        <i class="bi bi-people me-1"></i>

                                        {{ $subject->student_classes_count }}

                                        students

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-5 text-center text-muted">

                    <i class="bi bi-journal-x fs-1 opacity-25 d-block mb-2"></i>

                    No subjects have been assigned to your profile yet.

                </div>

            @endif

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- PAGE CSS --}}
{{-- ========================================================= --}}

<style>

    .hover-lift {

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;

    }


    .hover-lift:hover {

        transform: translateY(-2px);

        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);

        border-color: #6366f1 !important;

    }


    .hover-lift:hover h6 {

        color: #4f46e5;

    }


</style>

@endsection