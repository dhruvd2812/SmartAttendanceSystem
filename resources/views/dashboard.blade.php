@extends('layouts.app')

@section('title', 'Dashboard | Smart Attendance')

@section('content')
    <section class="app-hero p-4 p-md-5 mb-4">
        <p class="mb-2 opacity-75">Administrator dashboard</p>
        <h1 class="display-6 mb-2">Hello, {{ auth()->user()->name }}</h1>
        <p class="mb-0">Manage students, departments, attendance and the QR generator from your dashboard.</p>
    </section>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card app-card app-metric border-0">
                <div class="card-body p-4">
                    <p class="text-muted small mb-2">Total Students</p>
                    <div class="display-6 fw-bold">{{ $studentCount }}</div>
                    <a href="{{ route('students.index') }}" class="small text-primary">View students →</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card app-card app-metric border-0">
                <div class="card-body p-4">
                    <p class="text-muted small mb-2">Departments</p>
                    <div class="display-6 fw-bold">{{ $departmentCount }}</div>
                    <a href="{{ route('departments.index') }}" class="small text-primary">Manage departments →</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card app-card app-metric border-0">
                <div class="card-body p-4">
                    <p class="text-muted small mb-2">Recent activity</p>
                    <div class="display-6 fw-bold">{{ $recentStudents->count() }}</div>
                    <span class="small text-muted">New students added this week</span>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-sm-row gap-2 mb-4">
        <a href="{{ route('students.create') }}" class="btn btn-primary">+ Add Student</a>
        <a href="{{ route('departments.create') }}" class="btn btn-outline-primary">+ Add Department</a>
        <a href="{{ route('qr.index') }}" class="btn btn-soft-primary">Generate QR</a>
    </div>

    <section class="card app-card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
                <div>
                    <h2 class="h5 mb-1">Recently Added Students</h2>
                    <p class="text-muted small mb-0">Track the latest student registrations.</p>
                </div>
                <a href="{{ route('students.index') }}" class="btn btn-soft-primary btn-sm">View all students</a>
            </div>
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Enrollment No.</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentStudents as $student)
                            <tr>
                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td>{{ $student->enrollment_no }}</td>
                                <td>{{ $student->department?->department_name ?? 'Not assigned' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No students added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
