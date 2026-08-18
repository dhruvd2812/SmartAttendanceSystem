@extends('layouts.app')

@section('title', 'QR Generator | Smart Attendance')

@section('content')

<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">QR Code Generator</h2>
            <p class="text-muted mb-0">
                Generate a QR code for student attendance.
            </p>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="row g-4">

        {{-- QR Generator Form --}}
        <div class="col-lg-6">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-qr-code"></i>
                        Generate Attendance QR
                    </h5>
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.qr.generate') }}"
                          method="POST">

                        @csrf


                        {{-- Subject --}}
                        <div class="mb-3">

                            <label for="subject" class="form-label fw-semibold">
                                Subject
                            </label>

                            <input
                                type="text"
                                name="subject"
                                id="subject"
                                class="form-control"
                                placeholder="Enter subject name"
                                value="{{ old('subject') }}"
                                required
                            >

                        </div>


                        {{-- Lecture Date --}}
                        <div class="mb-3">

                            <label for="date" class="form-label fw-semibold">
                                Date
                            </label>

                            <input
                                type="date"
                                name="date"
                                id="date"
                                class="form-control"
                                value="{{ old('date', date('Y-m-d')) }}"
                                required
                            >

                        </div>


                        {{-- Start Time --}}
                        <div class="mb-3">

                            <label for="start_time" class="form-label fw-semibold">
                                Start Time
                            </label>

                            <input
                                type="time"
                                name="start_time"
                                id="start_time"
                                class="form-control"
                                value="{{ old('start_time') }}"
                                required
                            >

                        </div>


                        {{-- End Time --}}
                        <div class="mb-3">

                            <label for="end_time" class="form-label fw-semibold">
                                End Time
                            </label>

                            <input
                                type="time"
                                name="end_time"
                                id="end_time"
                                class="form-control"
                                value="{{ old('end_time') }}"
                                required
                            >

                        </div>


                        {{-- Generate Button --}}
                        <div class="d-grid">

                            <button type="submit"
                                    class="btn btn-primary btn-lg">

                                <i class="bi bi-qr-code"></i>
                                Generate QR Code

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- Generated QR --}}
        <div class="col-lg-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-qr-code-scan"></i>
                        QR Code
                    </h5>
                </div>

                <div class="card-body text-center">

                    @if(isset($qr))

                        <div class="mb-3">
                            <img src="{{ $qr }}"
                                 alt="Attendance QR code"
                                 class="img-fluid"
                                 width="300"
                                 height="300">
                        </div>

                        <p class="text-muted">
                            Students can scan this QR code to mark attendance.
                        </p>

                    @else

                        <div class="py-5">

                            <i class="bi bi-qr-code"
                               style="font-size: 80px; opacity: .25;">
                            </i>

                            <h5 class="mt-3 text-muted">
                                No QR Code Generated
                            </h5>

                            <p class="text-muted">
                                Fill the form and click
                                <strong>Generate QR Code</strong>.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
