@extends('layouts.app')

@section('title', 'QR Attendance Generator | Smart Attendance')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <section class="card app-card border-0 shadow-lg">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h1 class="h4 mb-1">QR Attendance Generator</h1>
                            <p class="text-muted small mb-0">Generate a QR code for attendance sessions.</p>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary">Valid for 2 minutes</span>
                    </div>

                    <form method="POST" action="{{ route('qr.generate') }}">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Faculty</label>
                                <input type="text" name="faculty" class="form-control" placeholder="Dr. Patel" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" class="form-control" placeholder="Computer" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Subject</label>
                                <input type="text" name="subject" class="form-control" placeholder="DBMS" required>
                            </div>
                        </div>
                        <button class="btn btn-primary">Generate QR</button>
                    </form>

                    @if(isset($qr))
                        <div class="row align-items-center mt-5 gy-4">
                            <div class="col-md-5 text-center">
                                <img src="{{ $qr }}" class="img-fluid rounded app-card" alt="QR Code">
                            </div>
                            <div class="col-md-7">
                                <div class="alert alert-success shadow-sm">
                                    <h5 class="mb-2">Attendance Session Created</h5>
                                    <pre class="mb-0">{{ $data }}</pre>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection
