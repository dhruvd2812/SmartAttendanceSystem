@extends('layouts.app')

@section('title', 'Notices | Smart Attendance')

@section('content')

<div class="container py-4">

    <div class="mb-4">
        <h2 class="fw-bold">
            <i class="fas fa-bullhorn text-primary"></i>
            Notices
        </h2>

        <p class="text-muted">
            College announcements and important notices
        </p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i>
                No notices available at the moment.
            </div>

        </div>
    </div>

</div>

@endsection