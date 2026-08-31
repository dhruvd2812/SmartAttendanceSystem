@extends('layouts.app')

@section('title', 'My Attendance')

@section('content')

<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">My Attendance</h2>
            <p class="text-muted mb-0">
                Welcome, {{ $student->first_name }} {{ $student->last_name }}
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <span class="badge
                @if($attendanceStatus === 'Good')
                    bg-success
                @elseif($attendanceStatus === 'Warning')
                    bg-warning text-dark
                @else
                    bg-danger
                @endif
                px-3 py-2">
                {{ $attendanceStatus }} Attendance
            </span>
        </div>
    </div>


    {{-- Summary Cards --}}
    <div class="row g-4 mb-4">

        {{-- Overall Attendance --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Overall Attendance</p>

                    <h2 class="fw-bold mb-2">
                        {{ $attendancePercentage }}%
                    </h2>

                    <div class="progress" style="height: 8px;">
                        <div
                            class="progress-bar
                                @if($attendancePercentage >= 75)
                                    bg-success
                                @elseif($attendancePercentage >= 60)
                                    bg-warning
                                @else
                                    bg-danger
                                @endif"
                            role="progressbar"
                            style="width: {{ min($attendancePercentage, 100) }}%;">
                        </div>
                    </div>

                    <small class="text-muted">
                        {{ $totalClasses }} total classes
                    </small>
                </div>
            </div>
        </div>


        {{-- Present --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Present</p>

                    <h2 class="fw-bold text-success mb-0">
                        {{ $presentCount }}
                    </h2>

                    <small class="text-muted">
                        Classes attended
                    </small>
                </div>
            </div>
        </div>


        {{-- Absent --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Absent</p>

                    <h2 class="fw-bold text-danger mb-0">
                        {{ $absentCount }}
                    </h2>

                    <small class="text-muted">
                        Classes missed
                    </small>
                </div>
            </div>
        </div>


        {{-- Late --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Late</p>

                    <h2 class="fw-bold text-warning mb-0">
                        {{ $lateCount }}
                    </h2>

                    <small class="text-muted">
                        Late attendance
                    </small>
                </div>
            </div>
        </div>

    </div>


    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Attendance Filters
            </h5>

            <form method="GET" action="{{ url('/student/attendance') }}">

                <div class="row g-3">

                    {{-- From Date --}}
                    <div class="col-md-4">

                        <label for="from_date" class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            id="from_date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}"
                        >

                    </div>


                    {{-- To Date --}}
                    <div class="col-md-4">

                        <label for="to_date" class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            id="to_date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}"
                        >

                    </div>


                    {{-- Subject --}}
                    <div class="col-md-4">

                        <label for="subject_id" class="form-label">
                            Subject
                        </label>

                        <select
                            id="subject_id"
                            name="subject_id"
                            class="form-select"
                        >

                            <option value="">
                                All Subjects
                            </option>

                            @foreach($subjects as $subject)

                                <option
                                    value="{{ $subject->id }}"
                                    {{ request('subject_id') == $subject->id ? 'selected' : '' }}
                                >
                                    {{ $subject->name }}
                                    ({{ $subject->code }})
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                <div class="mt-3">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Apply Filters
                    </button>

                    <a
                        href="{{ url('/student/attendance') }}"
                        class="btn btn-outline-secondary"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- Subject-wise Attendance --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="fw-bold mb-0">
                    Subject-wise Attendance
                </h5>

            </div>


            @if($subjectAttendance->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>Subject</th>
                                <th>Total</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Late</th>
                                <th>Attendance</th>
                                <th>Status</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($subjectAttendance as $subject)

                                <tr>

                                    <td>
                                        <strong>
                                            {{ $subject['subject_name'] }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            {{ $subject['subject_code'] }}
                                        </small>
                                    </td>

                                    <td>
                                        {{ $subject['total'] }}
                                    </td>

                                    <td class="text-success fw-semibold">
                                        {{ $subject['present'] }}
                                    </td>

                                    <td class="text-danger fw-semibold">
                                        {{ $subject['absent'] }}
                                    </td>

                                    <td class="text-warning fw-semibold">
                                        {{ $subject['late'] }}
                                    </td>

                                    <td style="min-width: 150px;">

                                        <div class="fw-semibold mb-1">
                                            {{ $subject['percentage'] }}%
                                        </div>

                                        <div
                                            class="progress"
                                            style="height: 7px;"
                                        >

                                            <div
                                                class="progress-bar
                                                    @if($subject['percentage'] >= 75)
                                                        bg-success
                                                    @elseif($subject['percentage'] >= 60)
                                                        bg-warning
                                                    @else
                                                        bg-danger
                                                    @endif"
                                                style="width: {{ min($subject['percentage'], 100) }}%;"
                                            ></div>

                                        </div>

                                    </td>

                                    <td>

                                        <span
                                            class="badge
                                                @if($subject['status'] === 'Good')
                                                    bg-success
                                                @elseif($subject['status'] === 'Warning')
                                                    bg-warning text-dark
                                                @else
                                                    bg-danger
                                                @endif"
                                        >
                                            {{ $subject['status'] }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">
                        <span style="font-size: 45px;">📊</span>
                    </div>

                    <h5 class="fw-bold">
                        No Attendance Records
                    </h5>

                    <p class="text-muted mb-0">
                        Your attendance records will appear here
                        once attendance is marked.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- Attendance History --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h5 class="fw-bold mb-3">
                Attendance History
            </h5>


            @if($attendances->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>Date</th>
                                <th>Lecture</th>
                                <th>Subject</th>
                                <th>Faculty</th>
                                <th>Status</th>
                                <th>Marked At</th>
                                <th>Remarks</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($attendances as $attendance)

                                <tr>

                                    <td>
                                        {{ optional($attendance->attendanceSession?->lecture_date)->format('d M Y') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $attendance->attendanceSession?->lecture_name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $attendance->attendanceSession?->subject?->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $attendance->attendanceSession?->faculty?->display_name ?? '-' }}
                                    </td>

                                    <td>

                                        @if($attendance->status === 'present')

                                            <span class="badge bg-success">
                                                Present
                                            </span>

                                        @elseif($attendance->status === 'late')

                                            <span class="badge bg-warning text-dark">
                                                Late
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Absent
                                            </span>

                                        @endif

                                    </td>

                                    <td>
                                        {{ $attendance->marked_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $attendance->remarks ?? '-' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <h5 class="fw-bold">
                        No Attendance History
                    </h5>

                    <p class="text-muted mb-0">
                        There are currently no attendance records
                        for your account.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection