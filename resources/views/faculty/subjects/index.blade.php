@extends('layouts.app')

@section('title', 'Manage Subjects | Smart Attendance')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div><h1 class="h2 mb-1">Manage Subjects</h1><p class="text-muted mb-0">Add and manage subjects for your department.</p></div>
        <a href="{{ route('faculty.dashboard') }}" class="btn btn-outline-secondary">Back to dashboard</a>
    </div>
    <div class="row g-4">
        <div class="col-lg-4"><div class="card shadow-sm border-0"><div class="card-body"><h2 class="h5 mb-3">Add Subject</h2><form method="POST" action="{{ route('faculty.subjects.store') }}">@csrf @include('faculty.subjects._form')<button type="submit" class="btn btn-primary w-100">Add Subject</button></form></div></div></div>
        <div class="col-lg-8"><div class="card shadow-sm border-0"><div class="card-header bg-white d-flex justify-content-between align-items-center"><h2 class="h5 mb-0">My Subjects</h2><span class="text-muted small">{{ $subjects->count() }} total</span></div><div class="card-body p-0">
            @if($subjects->isNotEmpty())
                <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Subject</th><th>Code</th><th>Semester</th><th>Students</th><th class="text-end">Actions</th></tr></thead><tbody>
                @foreach($subjects as $subject)
                    <tr><td><div class="fw-semibold">{{ $subject->name }}</div>@if($subject->description)<small class="text-muted">{{ \Illuminate\Support\Str::limit($subject->description, 70) }}</small>@endif</td><td>{{ $subject->code }}</td><td>{{ $subject->semester ?? '-' }}</td><td>{{ $subject->student_classes_count }}</td><td class="text-end text-nowrap"><a href="{{ route('faculty.subjects.edit', $subject) }}" class="btn btn-sm btn-outline-primary">Edit</a><form method="POST" action="{{ route('faculty.subjects.destroy', $subject) }}" class="d-inline" onsubmit="return confirm('Remove this subject? This cannot be undone.');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Remove</button></form></td></tr>
                @endforeach
                </tbody></table></div>
            @else
                <p class="text-muted text-center py-5 mb-0">No subjects yet. Add your first subject using this form.</p>
            @endif
        </div></div></div>
    </div>
@endsection
