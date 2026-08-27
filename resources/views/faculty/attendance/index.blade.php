@extends('layouts.app')

@section('title', 'Lecture Attendance | Smart Attendance')

@section('content')

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">

        <div>
            <p class="text-muted mb-1">Faculty portal</p>

            <h1 class="h3 mb-1">
                Lecture Attendance
            </h1>

            <p class="text-muted small mb-0">
                Review attendance recorded for your lectures.
            </p>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="d-flex flex-wrap gap-2">

            {{-- Manual Attendance --}}
            <a href="{{ route('faculty.attendance.manual') }}"
               class="btn btn-success">
                <i class="bi bi-pencil-square me-1"></i>
                + Manual Attendance
            </a>

            {{-- Generate QR --}}
            <a href="{{ route('faculty.qr.index') }}"
               class="btn btn-primary">
                <i class="bi bi-qr-code me-1"></i>
                + Generate QR
            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

            <strong>Success!</strong>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

            <strong>Error!</strong>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <section class="card app-card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>
                    <h5 class="mb-1">
                        Attendance Filters
                    </h5>

                    <p class="text-muted small mb-0">
                        Filter your lecture attendance records.
                    </p>
                </div>

            </div>


            <form method="GET"
                  action="{{ route('faculty.attendance.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- SUBJECT --}}
                    <div class="col-md-5">

                        <label class="form-label small fw-semibold">
                            Subject
                        </label>

                        <select name="subject_id"
                                class="form-select">

                            <option value="">
                                All subjects
                            </option>

                            @foreach($subjects as $subject)

                                <option value="{{ $subject->id }}"
                                    @selected(request('subject_id') == $subject->id)>

                                    {{ $subject->name }}

                                    @if($subject->code)
                                        ({{ $subject->code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- DATE --}}
                    <div class="col-md-4">

                        <label class="form-label small fw-semibold">
                            Lecture date
                        </label>

                        <input type="date"
                               name="date"
                               value="{{ request('date') }}"
                               class="form-control">

                    </div>


                    {{-- BUTTONS --}}
                    <div class="col-md-3">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-funnel me-1"></i>
                                Apply Filters

                            </button>

                            <a href="{{ route('faculty.attendance.index') }}"
                               class="btn btn-outline-secondary">

                                Reset

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </section>


    {{-- =========================================================
        ATTENDANCE TABLE
    ========================================================== --}}
    <section class="card app-card border-0 shadow-sm">

        <div class="card-body p-4">

            {{-- TABLE HEADER --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">

                <div>

                    <h5 class="mb-1">
                        Attendance Records
                    </h5>

                    <p class="text-muted small mb-0">
                        Attendance for lectures conducted by you.
                    </p>

                </div>


                <a href="{{ route('faculty.attendance.manual') }}"
                   class="btn btn-outline-success btn-sm">

                    <i class="bi bi-person-check me-1"></i>
                    Mark Attendance Manually

                </a>

            </div>


            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table app-table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Date
                            </th>

                            <th>
                                Subject / Lecture
                            </th>

                            <th>
                                Time
                            </th>

                            <th>
                                Enrolled
                            </th>

                            <th>
                                Present Students
                            </th>

                            <th>
                                Late
                            </th>

                            <th>
                                Attendance
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($sessions as $session)

                            {{-- =================================================
                                CALCULATIONS
                            ================================================== --}}

                            @php

                                $scannedStudents = $session->attendances
                                    ->pluck('student')
                                    ->filter()
                                    ->unique('id');

                                $enrolled = max(
                                    $session->subject?->student_classes_count ?? 0,
                                    $scannedStudents->count()
                                );

                                $marked =
                                    $session->present_count +
                                    $session->late_count;

                                $percentage = $enrolled
                                    ? round(($marked / $enrolled) * 100)
                                    : 0;

                            @endphp


                            <tr>

                                {{-- DATE --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $session->lecture_date?->format('d M Y') ?? '-' }}

                                    </div>

                                </td>


                                {{-- SUBJECT --}}
                                <td>

                                    <div class="fw-semibold">

                                        {{ $session->subject?->name ?? 'Unknown subject' }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $session->lecture_name ?: 'General lecture' }}

                                    </small>

                                </td>


                                {{-- TIME --}}
                                <td>

                                    {{ $session->start_time ?? '-' }}

                                    @if($session->end_time)
                                        – {{ $session->end_time }}
                                    @endif

                                </td>


                                {{-- ENROLLED --}}
                                <td>

                                    <span class="badge bg-primary">

                                        {{ $enrolled }}

                                    </span>

                                </td>


                                {{-- PRESENT --}}
                                <td>

                                    <span class="text-success fw-semibold">

                                        {{ $session->present_count }}

                                    </span>


                                    {{-- STUDENT NAMES --}}
                                    @if($scannedStudents->isNotEmpty())

                                        <small class="d-block text-muted mt-1">

                                            {{ $scannedStudents->pluck('full_name')->join(', ') }}

                                        </small>

                                    @endif

                                </td>


                                {{-- LATE --}}
                                <td>

                                    <span class="text-warning fw-semibold">

                                        {{ $session->late_count }}

                                    </span>

                                </td>


                                {{-- ATTENDANCE PERCENTAGE --}}
                                <td style="min-width: 130px;">

                                    <div class="fw-semibold mb-1">

                                        {{ $percentage }}%

                                    </div>


                                    <div class="progress"
                                         style="height: 6px;">

                                        <div
                                            class="progress-bar
                                            {{
                                                $percentage >= 75
                                                    ? 'bg-success'
                                                    : (
                                                        $percentage >= 50
                                                            ? 'bg-warning'
                                                            : 'bg-danger'
                                                    )
                                            }}"
                                            role="progressbar"
                                            style="width: {{ min($percentage, 100) }}%;"
                                            aria-valuenow="{{ $percentage }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>

                                    </div>

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if($session->status === 'active')

                                        <span class="badge bg-success">

                                            Active

                                        </span>

                                    @elseif($session->status === 'completed')

                                        <span class="badge bg-primary">

                                            Completed

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ ucfirst($session->status ?? 'Unknown') }}

                                        </span>

                                    @endif

                                </td>

                            </tr>


                        @empty

                            {{-- NO RECORDS --}}
                            <tr>

                                <td colspan="8"
                                    class="text-center py-5">

                                    <div class="mb-3">

                                        <i class="bi bi-calendar-x fs-1 text-muted"></i>

                                    </div>

                                    <h6 class="mb-1">
                                        No attendance lectures found
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        There are no attendance records for the selected filters.
                                    </p>


                                    <a href="{{ route('faculty.attendance.manual') }}"
                                       class="btn btn-success">

                                        <i class="bi bi-pencil-square me-1"></i>

                                        Add Manual Attendance

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </section>

@endsection