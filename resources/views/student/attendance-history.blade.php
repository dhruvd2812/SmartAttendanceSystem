@extends('layouts.app')

@section('title', 'Attendance History | Smart Attendance')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Attendance History
            </h1>

            <p class="text-muted mb-0">
                View your complete attendance records.
            </p>
        </div>

        <a href="{{ route('student.dashboard') }}"
           class="btn btn-outline-primary">
            ← Dashboard
        </a>

    </div>


    {{-- STUDENT INFORMATION --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 mb-3 mb-md-0">

                    <small class="text-muted">
                        Student Name
                    </small>

                    <h5 class="mb-0">
                        {{ $student->first_name }}
                        {{ $student->last_name }}
                    </h5>

                </div>


                <div class="col-md-4 mb-3 mb-md-0">

                    <small class="text-muted">
                        Enrollment Number
                    </small>

                    <h5 class="mb-0">
                        {{ $student->enrollment_no }}
                    </h5>

                </div>


                <div class="col-md-4">

                    <small class="text-muted">
                        Total Records
                    </small>

                    <h5 class="mb-0">
                        {{ $attendances->count() }}
                    </h5>

                </div>

            </div>

        </div>

    </div>


    {{-- ATTENDANCE TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                Attendance Records
            </h5>

        </div>


        <div class="card-body p-0">

            @if($attendances->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Date</th>

                                <th>Status</th>

                                <th>Created At</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($attendances as $attendance)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        @if(isset($attendance->date))
                                            {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                        @elseif(isset($attendance->created_at))
                                            {{ $attendance->created_at->format('d M Y') }}
                                        @else
                                            -
                                        @endif

                                    </td>


                                    <td>

                                        @if(
                                            isset($attendance->status)
                                            &&
                                            strtolower($attendance->status) === 'present'
                                        )

                                            <span class="badge bg-success">
                                                Present
                                            </span>

                                        @elseif(
                                            isset($attendance->status)
                                            &&
                                            strtolower($attendance->status) === 'absent'
                                        )

                                            <span class="badge bg-danger">
                                                Absent
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                {{ $attendance->status ?? 'Unknown' }}
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($attendance->created_at)
                                            {{ $attendance->created_at->format('d M Y, h:i A') }}
                                        @else
                                            -
                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="fas fa-calendar-times fa-3x text-muted"></i>

                    </div>

                    <h5>
                        No Attendance Records
                    </h5>

                    <p class="text-muted mb-0">
                        Your attendance history will appear here.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection