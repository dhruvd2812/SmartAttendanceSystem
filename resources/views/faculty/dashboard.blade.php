@extends('layouts.faculty')

@section('title', 'Faculty Dashboard | Smart Attendance')
@section('page-title', 'Faculty Dashboard')

@section('content')

<div class="container-fluid p-0">

    {{-- Flash messages are rendered by layouts.faculty --}}


    {{-- ========================================================= --}}
    {{-- HERO BANNER --}}
    {{-- ========================================================= --}}

    <div class="app-hero p-4 p-md-5 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="badge hero-badge fw-semibold px-3 py-2">
                    Faculty Portal
                </span>

                <span class="small" style="color:rgba(226,232,240,.65);">
                    • Instructor Dashboard
                </span>

            </div>

            <h1 class="fw-bold mb-2 text-white" style="font-size: clamp(1.6rem, 2.6vw, 2.1rem); letter-spacing: -.02em;">
                Hello, {{ $user->display_name ?? 'Faculty Member' }} 👋
            </h1>

            <p class="mb-0" style="max-width: 600px; color: rgba(226,232,240,.72);">
                Manage your class subjects, launch live QR attendance sessions,
                manually mark attendance, track lecture hours, and consult
                the AI assistant.
            </p>

        </div>


        {{-- HERO BUTTONS --}}

        <div class="d-flex flex-wrap gap-2">

            {{-- QR SESSION --}}

            <a href="{{ route('faculty.qr.index') }}"
               class="btn btn-light fw-semibold px-4 py-3 d-inline-flex align-items-center gap-2 rounded-3"
               style="color:#312e81;">

                <i class="bi bi-qr-code-scan fs-5"></i>

                <span>
                    Launch QR Session
                </span>

            </a>


            {{-- MANUAL ATTENDANCE --}}

            <a href="{{ route('faculty.attendance.manual') }}"
               class="btn hero-btn-glass fw-semibold px-4 py-3 d-inline-flex align-items-center gap-2 rounded-3">

                <i class="bi bi-pencil-square fs-5"></i>

                <span>
                    Manual Attendance
                </span>

            </a>


            {{-- CLASS ROSTER --}}

            <a href="{{ route('faculty.students.index') }}"
               class="btn hero-btn-glass fw-semibold px-4 py-3 d-inline-flex align-items-center gap-2 rounded-3">

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

                        <small class="text-muted">
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

                        <small class="fw-semibold" style="color:#047857;">

                            <i class="bi bi-check2-all me-1"></i>

                            Completed

                        </small>

                    </div>

                    <div class="metric-icon-wrapper metric-icon-amber">

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
                               class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-white hover-lift h-100">

                                <div class="action-tile-icon tile-indigo">

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
                               class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-white hover-lift h-100">

                                <div class="action-tile-icon tile-emerald">

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
                               class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-white hover-lift h-100">

                                <div class="action-tile-icon tile-cyan">

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
                                 class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-white hover-lift h-100"
                                 style="cursor: pointer;">

                                <div class="action-tile-icon tile-violet">

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

            <span class="badge rounded-pill fw-semibold px-3 py-2"
                  style="background: rgba(79,70,229,.10); color:#4f46e5;">

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

                                        <span class="badge rounded-pill fw-semibold"
                                              style="background:#f1f5f9; color:#334155; border:1px solid #e2e8f0;">

                                            {{ $subject->code ?? '-' }}

                                        </span>

                                    </td>


                                    <td>

                                        <span class="badge rounded-pill fw-semibold"
                                              style="background: rgba(8,145,178,.10); color:#0e7490;">

                                            Semester {{ $subject->semester ?? '-' }}

                                        </span>

                                    </td>


                                    <td class="pe-4 fw-semibold" style="color:#4f46e5;">

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

    /* ---- HERO ELEMENTS ---- */

    .hero-badge {
        background: rgba(255, 255, 255, 0.12);
        color: #e0e7ff;
        border: 1px solid rgba(255, 255, 255, 0.18);
        letter-spacing: .03em;
        font-size: .72rem;
        text-transform: uppercase;
    }

    .app-hero .btn-light {
        border: 0;
        box-shadow: 0 6px 18px -6px rgba(0, 0, 0, 0.45);
    }

    .hero-btn-glass {
        background: rgba(255, 255, 255, 0.10);
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #fff;
        transition: background .2s ease, border-color .2s ease;
    }

    .hero-btn-glass:hover,
    .hero-btn-glass:focus {
        background: rgba(255, 255, 255, 0.18);
        border-color: rgba(255, 255, 255, 0.35);
        color: #fff;
    }


    /* ---- QUICK ACTION TILES ---- */

    .hover-lift {
        border-color: #e2e8f0 !important;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px -12px rgba(15, 23, 42, 0.22);
        border-color: #c7d2fe !important;
    }

    .hover-lift:hover h6 {
        color: #4f46e5;
    }

</style>

@endsection