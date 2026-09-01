@extends('layouts.student')

@section('title', 'Notices | Smart Attendance')

@section('content')

<div class="container-fluid py-4">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-md-center
                gap-3 mb-4">

        <div>
            <p class="text-muted mb-1">
                Student Portal
            </p>

            <h1 class="h3 mb-1">
                📢 Notices
            </h1>

            <p class="text-muted mb-0">
                Announcements posted by faculty and admin.
            </p>
        </div>

        <a href="{{ route('student.dashboard') }}"
           class="btn btn-outline-primary">
            ← Back to Dashboard
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- NOTICES --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between
                        align-items-center">

                <div>
                    <h5 class="mb-1">
                        Latest Announcements
                    </h5>

                    <small class="text-muted">
                        Newest notices appear first
                    </small>
                </div>

                <span class="badge bg-primary">
                    {{ $notices->count() }} Notices
                </span>

            </div>

        </div>


        <div class="card-body">

            @if($notices->count() > 0)

                <div class="row g-3">

                    @foreach($notices as $notice)

                        <div class="col-md-6">

                            <div class="card border-0 shadow-sm h-100">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between
                                                align-items-start gap-2 mb-2">

                                        <h5 class="mb-0">
                                            🔔 {{ $notice->title }}
                                        </h5>

                                        @if($notice->role)

                                            <span class="badge bg-light text-dark
                                                         text-capitalize flex-shrink-0">
                                                {{ $notice->role }}
                                            </span>

                                        @endif

                                    </div>

                                    <hr>

                                    <p class="text-muted">
                                        {{ $notice->description }}
                                    </p>

                                    <small class="text-muted">

                                        <i class="fas fa-user me-1"></i>
                                        {{ \App\Support\PersonName::human($notice->posted_by, 'Admin') }}

                                        <br>

                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $notice->created_at->format('d M Y, h:i A') }}

                                    </small>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                {{-- ================================================= --}}
                {{-- NO NOTICES --}}
                {{-- ================================================= --}}

                <div class="text-center py-5 px-3">

                    <div class="display-4 mb-3">
                        📢
                    </div>

                    <h5 class="mb-2">
                        No Notices Available
                    </h5>

                    <p class="text-muted mb-0">
                        There are currently no active notices
                        from your faculty or admin.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FOOTER NOTE --}}
    {{-- ========================================================= --}}

    <div class="text-center mt-4">

        <small class="text-muted">
            Smart Attendance System
        </small>

    </div>

</div>

@endsection
