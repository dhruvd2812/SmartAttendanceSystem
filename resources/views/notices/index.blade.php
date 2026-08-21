@extends('layouts.app')

@section('title', 'Manage Notices | Smart Attendance')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-bullhorn text-primary me-2"></i>
                Manage Notices
            </h2>

            <p class="text-muted mb-0">
                Create and manage college notices.
            </p>
        </div>

        <a href="{{ route('notices.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Add Notice
        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>

    @endif

    @if($notices->count() > 0)

        <div class="row">

            @foreach($notices as $notice)

                <div class="col-md-6 mb-4">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <h5 class="fw-bold">
                                <i class="fas fa-bell text-warning me-2"></i>
                                {{ $notice->title }}
                            </h5>

                            <hr>

                            <p class="text-muted">
                                {{ $notice->description }}
                            </p>

                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>
                                {{ $notice->posted_by ?? 'Admin' }}
                                <br>

                                <i class="fas fa-calendar me-1"></i>
                                {{ $notice->created_at->format('d M Y, h:i A') }}
                            </small>

                        </div>

                        <div class="card-footer bg-white">

                            <form
                                action="{{ route('notices.destroy', $notice->id) }}"
                                method="POST"
                                onsubmit="return confirm('Delete this notice?');"
                            >

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash me-1"></i>
                                    Delete
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <i class="fas fa-bullhorn fa-4x text-muted mb-3"></i>

                <h4 class="fw-bold">
                    No Notices Found
                </h4>

                <p class="text-muted">
                    No notices have been added yet.
                </p>

                <a href="{{ route('notices.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Add Notice
                </a>

            </div>

        </div>

    @endif

</div>

@endsection