@extends('layouts.student')

@section('title', 'Timetable | Smart Attendance')

@section('content')

<div class="container-fluid py-4">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-md-center
                gap-3 mb-4">

        <div>

            <p class="text-muted mb-1">
                Student Portal
            </p>

            <h1 class="h3 mb-1">
                My Timetable
            </h1>

            <p class="text-muted mb-0">
                View your weekly class schedule.
            </p>

        </div>


        <a href="{{ route('student.dashboard') }}"
           class="btn btn-outline-primary">

            ← Dashboard

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- STUDENT INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="card app-card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="row g-4">

                {{-- Student Name --}}
                <div class="col-12 col-md-4">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Student Name
                        </small>

                        <div class="fw-semibold">
                            {{ $student->first_name }}
                            {{ $student->last_name }}
                        </div>

                    </div>

                </div>


                {{-- Enrollment Number --}}
                <div class="col-12 col-md-4">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Enrollment Number
                        </small>

                        <div class="fw-semibold">
                            {{ $student->enrollment_no }}
                        </div>

                    </div>

                </div>


                {{-- Semester --}}
                <div class="col-12 col-md-4">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Semester
                        </small>

                        <div class="fw-semibold">
                            {{ $student->semester ?? 'Not available' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- WEEKLY TIMETABLE --}}
    {{-- ========================================================= --}}

    <div class="card app-card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <div class="mb-4">

                <h2 class="h5 mb-1">
                    Weekly Timetable
                </h2>

                <p class="text-muted small mb-0">
                    Your regular class schedule from Monday to Saturday.
                </p>

            </div>


            {{-- ================================================= --}}
            {{-- RESPONSIVE TABLE --}}
            {{-- ================================================= --}}

            <div class="table-responsive">

                <table class="table table-bordered
                              table-hover
                              align-middle
                              text-center
                              mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="min-width: 130px;">
                                Time
                            </th>

                            <th style="min-width: 170px;">
                                Monday
                            </th>

                            <th style="min-width: 170px;">
                                Tuesday
                            </th>

                            <th style="min-width: 170px;">
                                Wednesday
                            </th>

                            <th style="min-width: 170px;">
                                Thursday
                            </th>

                            <th style="min-width: 170px;">
                                Friday
                            </th>

                            <th style="min-width: 170px;">
                                Saturday
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        {{-- ================================================= --}}
                        {{-- PERIOD 1 --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="table-light">
                                09:00 - 10:00
                            </th>

                            <td>
                                <strong>Mathematics</strong>
                                <br>
                                <small class="text-muted">
                                    Room 101
                                </small>
                            </td>

                            <td>
                                <strong>DBMS</strong>
                                <br>
                                <small class="text-muted">
                                    Room 102
                                </small>
                            </td>

                            <td>
                                <strong>Java</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                            <td>
                                <strong>Computer Network</strong>
                                <br>
                                <small class="text-muted">
                                    Room 103
                                </small>
                            </td>

                            <td>
                                <strong>Web Development</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 2
                                </small>
                            </td>

                            <td>
                                <strong>Project</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- PERIOD 2 --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="table-light">
                                10:00 - 11:00
                            </th>

                            <td>
                                <strong>DBMS</strong>
                                <br>
                                <small class="text-muted">
                                    Room 102
                                </small>
                            </td>

                            <td>
                                <strong>Mathematics</strong>
                                <br>
                                <small class="text-muted">
                                    Room 101
                                </small>
                            </td>

                            <td>
                                <strong>Web Development</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 2
                                </small>
                            </td>

                            <td>
                                <strong>Java</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                            <td>
                                <strong>Computer Network</strong>
                                <br>
                                <small class="text-muted">
                                    Room 103
                                </small>
                            </td>

                            <td>
                                <strong>DBMS</strong>
                                <br>
                                <small class="text-muted">
                                    Room 102
                                </small>
                            </td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- SHORT BREAK --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="table-secondary">
                                11:00 - 11:15
                            </th>

                            <td colspan="6"
                                class="table-secondary fw-semibold">

                                ☕ Break

                            </td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- PERIOD 3 --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="table-light">
                                11:15 - 12:15
                            </th>

                            <td>
                                <strong>Computer Network</strong>
                                <br>
                                <small class="text-muted">
                                    Room 103
                                </small>
                            </td>

                            <td>
                                <strong>Java</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                            <td>
                                <strong>Mathematics</strong>
                                <br>
                                <small class="text-muted">
                                    Room 101
                                </small>
                            </td>

                            <td>
                                <strong>Web Development</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 2
                                </small>
                            </td>

                            <td>
                                <strong>DBMS</strong>
                                <br>
                                <small class="text-muted">
                                    Room 102
                                </small>
                            </td>

                            <td>
                                <strong>Project</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- PERIOD 4 --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="table-light">
                                12:15 - 01:15
                            </th>

                            <td>
                                <strong>Web Development</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 2
                                </small>
                            </td>

                            <td>
                                <strong>Computer Network</strong>
                                <br>
                                <small class="text-muted">
                                    Room 103
                                </small>
                            </td>

                            <td>
                                <strong>DBMS</strong>
                                <br>
                                <small class="text-muted">
                                    Room 102
                                </small>
                            </td>

                            <td>
                                <strong>Mathematics</strong>
                                <br>
                                <small class="text-muted">
                                    Room 101
                                </small>
                            </td>

                            <td>
                                <strong>Java</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                            <td>
                                <strong>Web Development</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 2
                                </small>
                            </td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- LUNCH BREAK --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="table-secondary">
                                01:15 - 02:00
                            </th>

                            <td colspan="6"
                                class="table-secondary fw-semibold">

                                🍴 Lunch Break

                            </td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- PERIOD 5 --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="table-light">
                                02:00 - 03:00
                            </th>

                            <td>
                                <strong>Java</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                            <td>
                                <strong>Project</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                            <td>
                                <strong>Computer Network</strong>
                                <br>
                                <small class="text-muted">
                                    Room 103
                                </small>
                            </td>

                            <td>
                                <strong>DBMS</strong>
                                <br>
                                <small class="text-muted">
                                    Room 102
                                </small>
                            </td>

                            <td>
                                <strong>Mathematics</strong>
                                <br>
                                <small class="text-muted">
                                    Room 101
                                </small>
                            </td>

                            <td>
                                <strong>Project</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                        </tr>


                        {{-- ================================================= --}}
                        {{-- PERIOD 6 --}}
                        {{-- ================================================= --}}

                        <tr>

                            <th class="table-light">
                                03:00 - 04:00
                            </th>

                            <td>
                                <strong>Project</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                            <td>
                                <strong>Web Development</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 2
                                </small>
                            </td>

                            <td>
                                <strong>Java</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                            <td>
                                <strong>Project</strong>
                                <br>
                                <small class="text-muted">
                                    Lab 1
                                </small>
                            </td>

                            <td>
                                <strong>Computer Network</strong>
                                <br>
                                <small class="text-muted">
                                    Room 103
                                </small>
                            </td>

                            <td>
                                <span class="text-muted">
                                    No Class
                                </span>
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection