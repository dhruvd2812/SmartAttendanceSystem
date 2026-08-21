@extends('layouts.student')

@section('title', 'Student Dashboard | Smart Attendance')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- WELCOME --}}
    {{-- ========================================================= --}}

    <section class="app-hero p-4 p-md-5 mb-4">

        <p class="mb-2 opacity-75">
            Student Dashboard
        </p>

        <h1 class="display-6 mb-2">
            Welcome, {{ $student->first_name }}!
        </h1>

        <p class="mb-0">
            View your profile, attendance, subjects, timetable and notices.
        </p>

    </section>


    {{-- ========================================================= --}}
    {{-- STUDENT SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row g-4 mb-4">

        {{-- Student Name --}}
        <div class="col-12 col-md-6 col-xl-3">

            <div class="card app-card app-metric border-0">

                <div class="card-body p-4">

                    <p class="text-muted small mb-2">
                        Student Name
                    </p>

                    <div class="fw-bold fs-5">
                        {{ $student->first_name }}
                        {{ $student->last_name }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Enrollment Number --}}
        <div class="col-12 col-md-6 col-xl-3">

            <div class="card app-card app-metric border-0">

                <div class="card-body p-4">

                    <p class="text-muted small mb-2">
                        Enrollment Number
                    </p>

                    <div class="fw-bold fs-5">
                        {{ $student->enrollment_no }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Department --}}
        <div class="col-12 col-md-6 col-xl-3">

            <div class="card app-card app-metric border-0">

                <div class="card-body p-4">

                    <p class="text-muted small mb-2">
                        Department
                    </p>

                    <div class="fw-bold fs-5">
                        {{ $department?->department_name ?? 'Not assigned' }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Semester --}}
        <div class="col-12 col-md-6 col-xl-3">

            <div class="card app-card app-metric border-0">

                <div class="card-body p-4">

                    <p class="text-muted small mb-2">
                        Semester
                    </p>

                    <div class="fw-bold fs-5">
                        {{ $student->semester }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MY PROFILE --}}
    {{-- ========================================================= --}}

    <section class="card app-card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="mb-4">

                <h2 class="h5 mb-1">
                    My Profile
                </h2>

                <p class="text-muted small mb-0">
                    Your personal and academic information.
                </p>

            </div>


            <div class="row g-3">

                {{-- First Name --}}
                <div class="col-12 col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted">
                            First Name
                        </small>

                        <div class="fw-semibold">
                            {{ $student->first_name }}
                        </div>

                    </div>

                </div>


                {{-- Last Name --}}
                <div class="col-12 col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted">
                            Last Name
                        </small>

                        <div class="fw-semibold">
                            {{ $student->last_name }}
                        </div>

                    </div>

                </div>


                {{-- Enrollment Number --}}
                <div class="col-12 col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted">
                            Enrollment Number
                        </small>

                        <div class="fw-semibold">
                            {{ $student->enrollment_no }}
                        </div>

                    </div>

                </div>


                {{-- Gender --}}
                <div class="col-12 col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted">
                            Gender
                        </small>

                        <div class="fw-semibold">
                            {{ $student->gender }}
                        </div>

                    </div>

                </div>


                {{-- Email --}}
                <div class="col-12 col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted">
                            Email
                        </small>

                        <div class="fw-semibold">
                            {{ $student->email ?? 'Not available' }}
                        </div>

                    </div>

                </div>


                {{-- Mobile --}}
                <div class="col-12 col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted">
                            Mobile
                        </small>

                        <div class="fw-semibold">
                            {{ $student->mobile ?? 'Not available' }}
                        </div>

                    </div>

                </div>


                {{-- Department --}}
                <div class="col-12 col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted">
                            Department
                        </small>

                        <div class="fw-semibold">
                            {{ $department?->department_name ?? 'Not assigned' }}
                        </div>

                    </div>

                </div>


                {{-- Academic Year --}}
                <div class="col-12 col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted">
                            Academic Year
                        </small>

                        <div class="fw-semibold">
                            {{ $student->academic_year ?? 'Not available' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ========================================================= --}}
    {{-- QUICK ACTIONS --}}
    {{-- ========================================================= --}}

    <section class="card app-card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <h2 class="h5 mb-3">
                Quick Actions
            </h2>


            <div class="d-flex flex-column flex-sm-row gap-2">

                {{-- My Attendance --}}
                <a href="{{ route('student.attendance') }}"
                   class="btn btn-primary">

                    📊 My Attendance

                </a>


                {{-- Scan QR Code --}}
                <a href="{{ route('student.scan-qr') }}"
                   class="btn btn-outline-primary">

                    📷 Scan QR Code

                </a>


                {{-- Attendance History --}}
                <a href="{{ route('student.attendance.history') }}"
                   class="btn btn-outline-primary">

                    📅 Attendance History

                </a>


                {{-- Timetable --}}
                <a href="{{ route('student.timetable') }}"
                   class="btn btn-outline-primary">

                    🗓️ Timetable

                </a>

            </div>

        </div>

    </section>

</div>

@endsection