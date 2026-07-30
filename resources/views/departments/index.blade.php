@extends('layouts.app')

@section('title', 'Departments | Smart Attendance')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-muted mb-1">Administration</p>
            <h1 class="h3 mb-1">Departments</h1>
            <p class="text-muted small mb-0">{{ $departments->count() }} department{{ $departments->count() === 1 ? '' : 's' }} found</p>
        </div>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">+ Add Department</a>
    </div>

    <section class="card app-card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table app-table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Department Name</th>
                            <th>Code</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $department)
                        <tr>
                            <td class="text-muted">{{ $department->id }}</td>
                            <td class="fw-semibold">{{ $department->department_name }}</td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $department->department_code }}</span></td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this department? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                No departments found.
                                <a href="{{ route('departments.create') }}">Add the first department</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
