@extends('layouts.app')

@section('title', 'Faculties | Smart Attendance')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-muted mb-1">Administration</p>
            <h1 class="h3 mb-1">Faculties</h1>
            <p class="text-muted small mb-0">{{ $faculties->count() }} faculty {{ $faculties->count() === 1 ? 'member' : 'members' }} found</p>
        </div>
        <a href="{{ route('faculties.create') }}" class="btn btn-primary">+ Add Faculty Login</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="card app-card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table app-table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>#</th><th>Faculty Email</th><th class="text-end">Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($faculties as $faculty)
                            <tr>
                                <td class="text-muted">{{ $faculty->id }}</td>
                                <td class="fw-semibold">{{ $faculty->user->email ?? $faculty->email }}</td>
                                <td class="text-end text-nowrap">
                                    <form action="{{ route('faculties.destroy', $faculty) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this faculty member? This cannot be undone.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-5 text-muted">No faculty members found. <a href="{{ route('faculties.create') }}">Add the first faculty login</a>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
