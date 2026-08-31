@extends(auth()->check() && auth()->user()->role === 'faculty' ? 'layouts.faculty' : 'layouts.app')

@section('title', 'Students | Smart Attendance')
@section('page-title', 'Students List')

@section('content')
    @php($isAdmin = auth()->user()->role === 'admin')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-muted mb-1">{{ $isAdmin ? 'Administration' : 'Academic management' }}</p>
            <h1 class="h3 mb-1">Students</h1>
            <p class="text-muted small mb-0">{{ $students->count() }} student{{ $students->count() === 1 ? '' : 's' }} found{{ request('semester') ? ' in Semester ' . request('semester') : '' }}</p>
        </div>
        <a href="{{ $isAdmin ? route('admin.students.create') : route('faculty.students.create') }}" class="btn btn-primary">+ Add Student</a>
    </div>

    @if(session('success') || session('error'))
        <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
            {{ session('success') ?? session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="card app-card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="fw-semibold me-1">Semester:</span>
                <a href="{{ request()->url() }}" class="btn btn-sm {{ request('semester') ? 'btn-outline-primary' : 'btn-primary' }}">All <span class="badge text-bg-light ms-1">{{ $semesterCounts->sum() }}</span></a>
                @foreach(range(1, 8) as $semester)
                    <a href="{{ request()->url() }}?semester={{ $semester }}" class="btn btn-sm {{ (int) request('semester') === $semester ? 'btn-primary' : 'btn-outline-primary' }}">Sem {{ $semester }} <span class="badge text-bg-light ms-1">{{ $semesterCounts[$semester] ?? 0 }}</span></a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="card app-card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table app-table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>#</th><th>Student Name</th><th>Email</th><th>Department</th><th>Semester</th><th>Gender</th><th>Phone</th><th class="text-end">Actions</th></tr></thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $student->full_name ?: 'N/A' }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->department->department_name ?? $student->department->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-primary">Sem {{ $student->semester }}</span></td>
                                <td>{{ ucfirst($student->gender ?? 'N/A') }}</td>
                                <td>{{ $student->mobile ?? $student->phone ?? 'N/A' }}</td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ $isAdmin ? route('admin.students.show', $student) : route('faculty.students.show', $student) }}" class="btn btn-sm btn-outline-secondary me-1">View</a>
                                    @if($isAdmin || (auth()->user()->faculty && auth()->user()->faculty->department_id == $student->department_id))
                                        <a href="{{ $isAdmin ? route('admin.students.edit', $student) : route('faculty.students.edit', $student) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                        <form action="{{ $isAdmin ? route('admin.students.destroy', $student) : route('faculty.students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this student? This cannot be undone.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-5 text-muted">No students found for this semester. <a href="{{ $isAdmin ? route('admin.students.create') : route('faculty.students.create') }}">Add the first student</a>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
