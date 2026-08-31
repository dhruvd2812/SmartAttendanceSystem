@extends('layouts.faculty')

@section('title', 'Attendance Muster | Smart Attendance')
@section('page-title', 'Attendance Muster')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-muted mb-1">Faculty portal</p>
            <h1 class="h3 mb-1">Attendance Muster</h1>
            <p class="text-muted small mb-0">Semester-wise attendance percentage for your recorded lectures.</p>
        </div>
        <a href="{{ route('faculty.attendance.manual') }}" class="btn btn-success"><i class="bi bi-pencil-square me-1"></i> Mark Attendance</a>
    </div>

    <section class="card app-card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="fw-semibold me-1">Semester:</span>
                <a href="{{ route('faculty.muster') }}" class="btn btn-sm {{ request('semester') ? 'btn-outline-primary' : 'btn-primary' }}">All <span class="badge text-bg-light ms-1">{{ $semesterCounts->sum() }}</span></a>
                @foreach(range(1, 8) as $semester)
                    <a href="{{ route('faculty.muster', ['semester' => $semester]) }}" class="btn btn-sm {{ (int) request('semester') === $semester ? 'btn-primary' : 'btn-outline-primary' }}">Sem {{ $semester }} <span class="badge text-bg-light ms-1">{{ $semesterCounts[$semester] ?? 0 }}</span></a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="card app-card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table app-table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>#</th><th>Student</th><th>Enrollment No.</th><th>Department</th><th>Semester</th><th>Total Lectures</th><th>Present</th><th>Absent</th><th>Attendance</th></tr></thead>
                    <tbody>
                        @forelse($students as $student)
                            @php($percentage = $student->total_lectures ? round(($student->present_lectures / $student->total_lectures) * 100, 1) : 0)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td><td class="fw-semibold">{{ $student->full_name }}</td><td>{{ $student->enrollment_no }}</td><td>{{ $student->department->department_name ?? $student->department->name ?? 'N/A' }}</td><td><span class="badge bg-primary">Sem {{ $student->semester }}</span></td><td>{{ $student->total_lectures }}</td><td class="text-success fw-semibold">{{ $student->present_lectures }}</td><td class="text-danger fw-semibold">{{ $student->absent_lectures }}</td>
                                <td style="min-width: 145px;"><div class="fw-semibold mb-1">{{ $percentage }}%</div><div class="progress" style="height: 7px;"><div class="progress-bar {{ $percentage >= 75 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $percentage }}%"></div></div></td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center py-5 text-muted">No registered students found for this semester.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
