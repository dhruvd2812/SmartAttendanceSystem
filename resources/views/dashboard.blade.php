@extends('layouts.app')

@section('title', 'Admin Dashboard | Smart Attendance')

@section('content')

<div class="container-fluid py-4">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="app-hero p-4 p-md-5 mb-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">

            <div>
                <p class="mb-2 opacity-75">
                    Administrator Dashboard
                </p>

                <h1 class="display-6 mb-2">
                    Hello, {{ auth()->user()->name }}
                </h1>

                <p class="mb-0">
                    Manage students, departments, faculty, attendance and QR generation.
                </p>
            </div>

            <div class="mt-3 mt-md-0">
                <span class="badge bg-dark fs-6 px-3 py-2">
                    Administrator (Admin)
                </span>
            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STATISTICS --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        {{-- Students --}}
        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <p class="text-muted mb-1">
                                Total Students
                            </p>

                            <h2 class="mb-0">
                                {{ $studentCount ?? 0 }}
                            </h2>
                        </div>

                        <div class="fs-1">
                            👨‍🎓
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Departments --}}
        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <p class="text-muted mb-1">
                                Departments
                            </p>

                            <h2 class="mb-0">
                                {{ $departmentCount ?? 0 }}
                            </h2>
                        </div>

                        <div class="fs-1">
                            🏢
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Faculty --}}
        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <p class="text-muted mb-1">
                                Faculty
                            </p>

                            <h2 class="mb-0">
                                {{ $facultyCount ?? 0 }}
                            </h2>
                        </div>

                        <div class="fs-1">
                            👨‍🏫
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Attendance --}}
        <div class="col-md-6 col-xl-3">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <p class="text-muted mb-1">
                                Attendance
                            </p>

                            <h2 class="mb-0">
                                {{ number_format($attendancePercentage ?? 0, 1) }}%
                            </h2>
                        </div>

                        <div class="fs-1">
                            📊
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- QUICK ACTIONS --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <h4 class="mb-4">
                Quick Actions
            </h4>

            <div class="row g-3">

                {{-- Students --}}
                <div class="col-md-6 col-lg-3">

                    <a href="{{ route('admin.students.index') }}"
                       class="btn btn-primary w-100 py-3">

                        👨‍🎓
                        <br>

                        Manage Students

                    </a>

                </div>


                {{-- Departments --}}
                <div class="col-md-6 col-lg-3">

                    <a href="{{ route('departments.index') }}"
                       class="btn btn-success w-100 py-3">

                        🏢
                        <br>

                        Departments

                    </a>

                </div>


                {{-- Faculty --}}
                <div class="col-md-6 col-lg-3">

                    <a href="{{ route('faculties.index') }}"
                       class="btn btn-warning w-100 py-3">

                        👨‍🏫
                        <br>

                        Faculty

                    </a>

                </div>


                {{-- QR GENERATOR --}}
                <div class="col-md-6 col-lg-3">

                    <a href="{{ route('admin.qr.index') }}"
                       class="btn btn-dark w-100 py-3">

                        📱
                        <br>

                        QR Generator

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- RECENT STUDENTS --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Recent Students
                </h5>

                <a href="{{ route('admin.students.index') }}"
                   class="btn btn-sm btn-outline-primary">

                    View All

                </a>

            </div>

        </div>


        <div class="card-body p-0">

            @if(isset($recentStudents) && $recentStudents->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Semester</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($recentStudents as $student)

                                <tr>

                                    <td>
                                        {{ $student->id }}
                                    </td>

                                    <td>
                                        {{ $student->name }}
                                    </td>

                                    <td>
                                        {{ $student->email }}
                                    </td>

                                    <td>
                                        {{ $student->department->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $student->semester ?? '-' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="p-4 text-center text-muted">

                    No students found.

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DEPARTMENTS --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Departments
            </h5>

        </div>

        <div class="card-body">

            @if(isset($departments) && $departments->count())

                <div class="row g-3">

                    @foreach($departments as $department)

                        <div class="col-md-6 col-lg-4">

                            <div class="border rounded p-3 h-100">

                                <h6 class="mb-1">
                                    {{ $department->name ?? '-' }}
                                </h6>

                                <small class="text-muted">
                                    {{ $department->code ?? '' }}
                                </small>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <p class="text-muted mb-0">
                    No departments found.
                </p>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ADMIN CHATBOT --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1">
                        🤖 Smart Attendance AI
                    </h5>

                    <p class="text-muted mb-0">
                        Ask questions about students, faculty, departments and attendance.
                    </p>

                </div>

                <a href="{{ route('admin.chatbot.index') }}"
                   class="btn btn-primary">

                    Open Chatbot

                </a>

            </div>

        </div>

    </div>

</div>

@endsection