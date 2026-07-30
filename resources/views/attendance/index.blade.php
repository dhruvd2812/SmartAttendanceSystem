@extends('layouts.app')

@section('title', 'Attendance Dashboard | Smart Attendance')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-muted mb-1">Attendance Dashboard</p>
            <h1 class="h2 mb-0">Attendance Summary</h1>
        </div>
        <a href="{{ route('qr.index') }}" class="btn btn-primary">Create QR Session</a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="display-6 fw-bold text-primary mb-1">10</div>
                    <p class="text-muted mb-0">Total Attendance Sessions</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="display-6 fw-bold text-success mb-1">150</div>
                    <p class="text-muted mb-0">Present Students</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="display-6 fw-bold text-danger mb-1">20</div>
                    <p class="text-muted mb-0">Absent Students</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card app-card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="display-6 fw-bold text-info mb-1">88%</div>
                    <p class="text-muted mb-0">Attendance Percentage</p>
                </div>
            </div>
        </div>
    </div>
@endsection
