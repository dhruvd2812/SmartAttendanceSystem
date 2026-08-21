@extends('layouts.student')

@section('title', 'My Timetable | Smart Attendance')

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
                🗓️ My Timetable
            </h1>

            <p class="text-muted mb-0">
                View your weekly class schedule.
            </p>
        </div>

        <a href="{{ route('student.dashboard') }}"
           class="btn btn-outline-primary">
            ← Back to Dashboard
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- STUDENT INFORMATION --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Student
                    </small>

                    <strong>
                        {{ $student->name ?? $student->student_name ?? 'Student' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Department
                    </small>

                    <strong>
                        {{ $student->department->name ?? 'N/A' }}
                    </strong>

                </div>


                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Semester
                    </small>

                    <strong>
                        {{ $student->semester ?? 'N/A' }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TIMETABLE --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between
                        align-items-center">

                <div>
                    <h5 class="mb-1">
                        Weekly Schedule
                    </h5>

                    <small class="text-muted">
                        Your scheduled classes
                    </small>
                </div>

                <span class="badge bg-primary">
                    {{ $timetables->count() }} Classes
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($timetables->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover
                                  table-bordered
                                  align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Day
                                </th>

                                <th>
                                    Time
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th>
                                    Faculty
                                </th>

                                <th>
                                    Department
                                </th>

                                <th>
                                    Room
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($timetables as $timetable)

                                <tr>

                                    {{-- DAY --}}

                                    <td>

                                        <strong>
                                            {{ $timetable->day }}
                                        </strong>

                                    </td>


                                    {{-- TIME --}}

                                    <td>

                                        @php
                                            $startTime = $timetable->start_time
                                                ? \Carbon\Carbon::parse($timetable->start_time)->format('h:i A')
                                                : 'N/A';

                                            $endTime = $timetable->end_time
                                                ? \Carbon\Carbon::parse($timetable->end_time)->format('h:i A')
                                                : 'N/A';
                                        @endphp

                                        <span>
                                            {{ $startTime }}
                                        </span>

                                        <span class="text-muted">
                                            -
                                        </span>

                                        <span>
                                            {{ $endTime }}
                                        </span>

                                    </td>


                                    {{-- SUBJECT --}}

                                    <td>

                                        @if($timetable->subject)

                                            <strong>
                                                {{ $timetable->subject->name }}
                                            </strong>

                                            @if($timetable->subject->code)

                                                <br>

                                                <small class="text-muted">
                                                    {{ $timetable->subject->code }}
                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                No subject
                                            </span>

                                        @endif

                                    </td>


                                    {{-- FACULTY --}}

                                    <td>

                                        @if($timetable->faculty)

                                            {{ $timetable->faculty->faculty_name
                                                ?? $timetable->faculty->name
                                                ?? 'Faculty'
                                            }}

                                        @else

                                            <span class="text-muted">
                                                Not assigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- DEPARTMENT --}}

                                    <td>

                                        @if($timetable->department)

                                            {{ $timetable->department->name }}

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ROOM --}}

                                    <td>

                                        @if($timetable->room)

                                            <span class="badge bg-light text-dark">

                                                {{ $timetable->room }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                Not assigned
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- ================================================= --}}
                {{-- NO TIMETABLE --}}
                {{-- ================================================= --}}

                <div class="text-center py-5 px-3">

                    <div class="display-4 mb-3">
                        🗓️
                    </div>

                    <h5 class="mb-2">
                        No Timetable Available
                    </h5>

                    <p class="text-muted mb-0">
                        There are currently no timetable entries
                        for your department and semester.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FOOTER NOTE --}}
    {{-- ========================================================= --}}

    <div class="text-center mt-4">

        <small class="text-muted">
            Smart Attendance System
        </small>

    </div>

</div>

@endsection