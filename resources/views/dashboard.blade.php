@extends('layouts.app')

@section('title', 'Admin Command Center | Smart Attendance')

@section('content')

<div class="container-fluid py-3">

    {{-- ========================================================= --}}
    {{-- HERO BANNER --}}
    {{-- ========================================================= --}}
    <div class="app-hero p-4 p-md-5 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white text-primary fw-bold px-3 py-1">Administrator Hub</span>
                <span class="text-white-50 small">• System Command Center</span>
            </div>
            <h1 class="display-6 fw-bold mb-2 text-white">
                Welcome back, {{ auth()->user()->display_name }} 👋
            </h1>
            <p class="mb-0 text-white-50" style="max-width: 600px;">
                Manage university students, faculty members, academic departments, and live QR attendance sessions with AI insights.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.qr.index') }}" class="btn btn-light text-primary fw-bold px-4 py-3 d-inline-flex align-items-center gap-2 shadow-sm rounded-3">
                <i class="bi bi-qr-code-scan fs-5"></i>
                <span>Generate QR Session</span>
            </a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-light fw-semibold px-4 py-3 d-inline-flex align-items-center gap-2 rounded-3">
                <i class="bi bi-people"></i>
                <span>Manage Students</span>
            </a>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- METRIC STAT CARDS --}}
    {{-- ========================================================= --}}
    <div class="row g-4 mb-4">

        {{-- Students --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-semibold mb-1">Total Enrolled</p>
                        <h3 class="fw-bold text-dark mb-0">{{ $studentCount ?? 0 }}</h3>
                        <small class="text-primary fw-medium">Active Students</small>
                    </div>
                    <div class="metric-icon-wrapper metric-icon-indigo">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Departments --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-semibold mb-1">Departments</p>
                        <h3 class="fw-bold text-dark mb-0">{{ $departmentCount ?? 0 }}</h3>
                        <small class="text-muted">Academic Wings</small>
                    </div>
                    <div class="metric-icon-wrapper metric-icon-cyan">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Faculty --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-semibold mb-1">Faculty Members</p>
                        <h3 class="fw-bold text-dark mb-0">{{ $facultyCount ?? 0 }}</h3>
                        <small class="text-muted">Professors & Instructors</small>
                    </div>
                    <div class="metric-icon-wrapper metric-icon-emerald">
                        <i class="bi bi-person-video3"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 h-100">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted small fw-semibold mb-1">Average Attendance</p>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($attendancePercentage ?? 0, 1) }}%</h3>
                        <small class="text-success fw-semibold"><i class="bi bi-graph-up-arrow me-1"></i>System Total</small>
                    </div>
                    <div class="metric-icon-wrapper metric-icon-amber" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                        <i class="bi bi-pie-chart-fill"></i>
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
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-lightning-charge-fill text-primary"></i>
                        Administrative Quick Actions
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-3">
                            <a href="{{ route('admin.students.index') }}" class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100">
                                <div class="rounded-3 bg-primary text-white p-3 fs-4 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Students Directory</h6>
                                    <small class="text-muted">Manage all students</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <a href="{{ route('departments.index') }}" class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100">
                                <div class="rounded-3 bg-info text-white p-3 fs-4 d-flex align-items-center justify-content-center" style="background: #06b6d4;">
                                    <i class="bi bi-diagram-3-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Departments</h6>
                                    <small class="text-muted">Branches & courses</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <a href="{{ route('faculties.index') }}" class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100">
                                <div class="rounded-3 bg-success text-white p-3 fs-4 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-lines-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Faculty Staff</h6>
                                    <small class="text-muted">Teachers & instructors</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <a href="{{ route('admin.qr.index') }}" class="p-3 border rounded-4 d-flex align-items-center gap-3 text-decoration-none text-dark bg-light hover-lift h-100">
                                <div class="rounded-3 bg-dark text-white p-3 fs-4 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-qr-code"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">QR Generator</h6>
                                    <small class="text-muted">Create live scan session</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-4 mb-4">
        {{-- ========================================================= --}}
        {{-- RECENT STUDENTS --}}
        {{-- ========================================================= --}}
        <div class="col-lg-8">
            <div class="card app-card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="bi bi-person-check-fill text-primary"></i>
                        Recently Registered Students
                    </h5>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-primary">
                        View All Students
                    </a>
                </div>

                <div class="card-body p-0">
                    @if(isset($recentStudents) && $recentStudents->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Student Name</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th class="pe-4">Semester</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentStudents as $student)
                                        <tr>
                                            <td class="ps-4 fw-semibold text-muted">{{ $student->id }}</td>
                                            <td class="fw-bold text-dark">{{ $student->name }}</td>
                                            <td class="text-muted small">{{ $student->email }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    {{ $student->department->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="pe-4">
                                                <span class="badge bg-primary-subtle text-primary">
                                                    Sem {{ $student->semester ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-5 text-center text-muted">
                            <i class="bi bi-people fs-1 opacity-25 d-block mb-2"></i>
                            No students registered yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- AI ASSISTANT PROMO & DEPARTMENTS --}}
        {{-- ========================================================= --}}
        <div class="col-lg-4">
            {{-- AI Widget Hero --}}
            <div class="card app-card border-0 shadow-sm p-4 mb-4 text-center text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);">
                <div class="d-inline-flex p-3 rounded-circle bg-white text-primary mb-3 mx-auto shadow-sm" style="width: 60px; height: 60px; align-items: center; justify-content: center; font-size: 1.6rem;">
                    <i class="bi bi-robot"></i>
                </div>
                <h5 class="fw-bold mb-2 text-white">Administrator AI Copilot</h5>
                <p class="text-white-50 small mb-4">
                    Query instant statistics on overall student attendance, faculty schedules, low-attendance alerts, and department records.
                </p>
                <button type="button" class="btn btn-light text-primary fw-bold w-100 py-2 shadow-sm" onclick="document.querySelector('[data-chat-toggle]')?.click()">
                    <i class="bi bi-chat-dots-fill me-1"></i> Open AI Assistant
                </button>
            </div>

            {{-- Departments Card --}}
            <div class="card app-card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-dark">Active Departments</h6>
                    <a href="{{ route('departments.index') }}" class="small text-primary fw-semibold text-decoration-none">Manage</a>
                </div>

                @if(isset($departments) && $departments->count())
                    <div class="d-flex flex-column gap-2">
                        @foreach($departments->take(5) as $dept)
                            <div class="p-2 px-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-semibold small text-dark d-block">{{ $dept->name ?? '-' }}</span>
                                    <small class="text-muted">{{ $dept->code ?? 'Dept' }}</small>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary small">Active</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small mb-0">No departments configured yet.</p>
                @endif
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