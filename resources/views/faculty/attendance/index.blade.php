@extends('layouts.app')

@section('title', 'Lecture Attendance | Smart Attendance')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-muted mb-1">Faculty portal</p>
            <h1 class="h3 mb-1">Lecture Attendance</h1>
            <p class="text-muted small mb-0">Review attendance recorded for your QR lectures.</p>
        </div>
        <a href="{{ route('faculty.qr.index') }}" class="btn btn-primary">+ Generate QR</a>
    </div>

    <section class="card app-card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5"><label class="form-label small fw-semibold">Subject</label><select name="subject_id" class="form-select"><option value="">All subjects</option>@foreach($subjects as $subject)<option value="{{ $subject->id }}" @selected(request('subject_id') == $subject->id)>{{ $subject->name }}@if($subject->code) ({{ $subject->code }}) @endif</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label small fw-semibold">Lecture date</label><input type="date" name="date" value="{{ request('date') }}" class="form-control"></div>
                <div class="col-md-3 d-flex gap-2"><button class="btn btn-primary">Apply filters</button><a href="{{ route('faculty.attendance.index') }}" class="btn btn-outline-secondary">Reset</a></div>
            </form>
        </div>
    </section>

    <section class="card app-card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive"><table class="table app-table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Date</th><th>Subject / Lecture</th><th>Time</th><th>Enrolled</th><th>Present Students</th><th>Late</th><th>Attendance</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($sessions as $session)
                        @php($scannedStudents = $session->attendances->pluck('student')->filter()->unique('id'))
                        @php($enrolled = max($session->subject?->student_classes_count ?? 0, $scannedStudents->count()))
                        @php($marked = $session->present_count + $session->late_count)
                        @php($percentage = $enrolled ? round(($marked / $enrolled) * 100) : 0)
                        <tr>
                            <td>{{ $session->lecture_date?->format('d M Y') ?? '-' }}</td>
                            <td><div class="fw-semibold">{{ $session->subject?->name ?? 'Unknown subject' }}</div><small class="text-muted">{{ $session->lecture_name ?: 'General lecture' }}</small></td>
                            <td>{{ $session->start_time }} – {{ $session->end_time }}</td><td>{{ $enrolled }}</td>
                            <td class="text-success fw-semibold">{{ $session->present_count }}@if($scannedStudents->isNotEmpty())<small class="d-block text-muted fw-normal">{{ $scannedStudents->pluck('full_name')->join(', ') }}</small>@endif</td><td class="text-warning fw-semibold">{{ $session->late_count }}</td>
                            <td><div class="fw-semibold">{{ $percentage }}%</div><div class="progress" style="height: 6px;"><div class="progress-bar {{ $percentage >= 75 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $percentage }}%"></div></div></td>
                            <td><span class="badge {{ $session->status === 'active' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($session->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-5 text-muted">No attendance lectures found for these filters.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
        </div>
    </section>
@endsection
