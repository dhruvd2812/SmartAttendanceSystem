@extends('layouts.student')

@section('title', 'Student Dashboard | Smart Attendance')
@section('page-title', 'Student Dashboard')

@section('content')

<div class="container-fluid py-2">

    {{-- ========================================================= --}}
    {{-- WELCOME HERO BANNER --}}
    {{-- ========================================================= --}}
    <section class="app-hero p-4 p-md-5 mb-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white text-primary fw-bold px-3 py-1">Student Portal</span>
                <span class="text-white-50 small">• Semester {{ $student->semester }}</span>
            </div>
            <h1 class="display-6 fw-bold mb-2 text-white">
                Welcome back, {{ $student->first_name }}! 👋
            </h1>
            <p class="mb-0 text-white-50" style="max-width: 540px;">
                Ready for today's lectures? Scan attendance QR codes, review your subject percentages, or ask the AI assistant.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('student.scan-qr') }}" class="btn btn-light text-primary fw-bold px-4 py-3 d-inline-flex align-items-center gap-2 shadow-sm rounded-3">
                <i class="bi bi-qr-code-scan fs-5"></i>
                <span>Scan Attendance QR</span>
            </a>
            <a href="{{ route('student.attendance') }}" class="btn btn-outline-light fw-semibold px-4 py-3 d-inline-flex align-items-center gap-2 rounded-3">
                <i class="bi bi-bar-chart-line"></i>
                <span>My Attendance</span>
            </a>
        </div>
    </section>


    {{-- ========================================================= --}}
    {{-- STAT METRIC CARDS --}}
    {{-- ========================================================= --}}
    <div class="row g-4 mb-4">

        {{-- Student Full Name --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-semibold mb-1">Student Profile</p>
                        <h5 class="fw-bold text-dark mb-0">{{ $student->first_name }} {{ $student->last_name }}</h5>
                        <small class="text-primary fw-medium">{{ $student->gender ?? 'Student' }}</small>
                    </div>
                    <div class="metric-icon-wrapper metric-icon-indigo">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enrollment Number --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-semibold mb-1">Enrollment ID</p>
                        <h5 class="fw-bold text-dark mb-0">{{ $student->enrollment_no }}</h5>
                        <small class="text-muted">Academic Identifier</small>
                    </div>
                    <div class="metric-icon-wrapper metric-icon-cyan">
                        <i class="bi bi-card-text"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Department --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-semibold mb-1">Department</p>
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">
                            {{ $department?->department_name ?? 'Assigned Branch' }}
                        </h5>
                        <small class="text-muted">{{ $department?->department_code ?? 'Academic' }}</small>
                    </div>
                    <div class="metric-icon-wrapper metric-icon-emerald">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Semester --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-semibold mb-1">Current Term</p>
                        <h4 class="fw-bold text-dark mb-0">Semester {{ $student->semester }}</h4>
                        <small class="text-muted">{{ $student->academic_year ?? 'Active Term' }}</small>
                    </div>
                    <div class="metric-icon-wrapper metric-icon-amber" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- QUICK ACTION SHORTCUTS --}}
    {{-- ========================================================= --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-8">

            <div class="card app-card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-grid-fill text-primary"></i>
                        Portal Shortcuts
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <a href="{{ route('student.scan-qr') }}" class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100">
                                <div class="rounded-3 bg-success text-white p-3 fs-4 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-qr-code-scan"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Scan Attendance QR</h6>
                                    <small class="text-muted">Record lecture attendance</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-6">
                            <a href="{{ route('student.attendance') }}" class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100">
                                <div class="rounded-3 bg-primary text-white p-3 fs-4 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-pie-chart-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">My Attendance Summary</h6>
                                    <small class="text-muted">Subject-wise stats & status</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-6">
                            <a href="{{ route('student.attendance.history') }}" class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100">
                                <div class="rounded-3 bg-indigo text-white p-3 fs-4 d-flex align-items-center justify-content-center" style="background: #4f46e5;">
                                    <i class="bi bi-calendar2-check-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Attendance History</h6>
                                    <small class="text-muted">Detailed lecture logs</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-6">
                            <a href="{{ route('student.timetable') }}" class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100">
                                <div class="rounded-3 bg-warning text-dark p-3 fs-4 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-calendar3"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Class Timetable</h6>
                                    <small class="text-muted">Lecture schedule & hours</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Profile Details Grid --}}
            <div class="card app-card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-person-badge text-primary"></i>
                        Academic & Contact Details
                    </h5>
                    <a href="{{ route('student.profile') }}" class="btn btn-outline-primary btn-sm">Full Profile</a>
                </div>

                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block">Registered Email</small>
                                <span class="fw-semibold text-dark">{{ $student->email ?? 'Not Available' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block">Mobile Number</small>
                                <span class="fw-semibold text-dark">{{ $student->mobile ?? 'Not Available' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block">Department</small>
                                <span class="fw-semibold text-dark">{{ $department?->department_name ?? 'Branch Assigned' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3">
                                <small class="text-muted d-block">Academic Year</small>
                                <span class="fw-semibold text-dark">{{ $student->academic_year ?? 'Current' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Side Column AI Assistant Card --}}
        <div class="col-lg-4">

            <div class="card app-card border-0 shadow-sm p-4 mb-4 text-center text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);">
                <div class="d-inline-flex p-3 rounded-circle bg-white text-primary mb-3 mx-auto shadow-sm" style="width: 60px; height: 60px; align-items: center; justify-content: center; font-size: 1.6rem;">
                    <i class="bi bi-robot"></i>
                </div>
                <h5 class="fw-bold mb-2 text-white">Smart Attendance AI</h5>
                <p class="text-white-50 small mb-4">
                    Ask questions about your attendance percentages, who teaches your subjects, QR scanner help, or today's classes.
                </p>
                <button type="button" class="btn btn-light text-primary fw-bold w-100 py-2 shadow-sm" onclick="document.querySelector('[data-chat-toggle]')?.click()">
                    <i class="bi bi-chat-dots-fill me-1"></i> Ask AI Assistant
                </button>
            </div>

            {{-- Quick Links Card --}}
            <div class="card app-card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-3 text-dark">Important Resources</h6>
                <div class="list-group list-group-flush">
                    <a href="{{ route('student.subjects') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2 border-0">
                        <span class="small"><i class="bi bi-book me-2 text-primary"></i>Enrolled Subjects</span>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('student.notices') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2 border-0">
                        <span class="small"><i class="bi bi-megaphone me-2 text-danger"></i>Notice Board</span>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                    <a href="{{ route('student.timetable') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2 border-0">
                        <span class="small"><i class="bi bi-clock-history me-2 text-success"></i>Lecture Timetable</span>
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        border-color: #6366f1 !important;
    }
</style>

@endsection