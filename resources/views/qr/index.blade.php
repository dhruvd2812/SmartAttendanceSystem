@extends(auth()->check() && auth()->user()->role === 'faculty' ? 'layouts.faculty' : 'layouts.app')

@section('title', 'Attendance QR Studio | Smart Attendance')
@section('page-title', 'QR Attendance Generator')

@section('content')

<div class="container-fluid py-3">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-1">Faculty & Lecture Studio</span>
                <span class="text-muted small">• Active Session Engine</span>
            </div>
            <h2 class="fw-bold mb-1 text-dark">
                QR Attendance Generator
            </h2>
            <p class="text-muted mb-0">
                Generate secure, timed, dynamic QR codes for live student classroom attendance.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ auth()->user()->role === 'faculty' ? route('faculty.dashboard') : route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>

    {{-- Error --}}
    @if(session('error'))
        <div class="alert alert-danger alert-custom alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-octagon-fill text-danger fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-custom mb-4">
            <div class="fw-bold mb-1"><i class="bi bi-shield-x me-1"></i> Please fix the following errors:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        {{-- ===================================================== --}}
        {{-- GENERATOR FORM --}}
        {{-- ===================================================== --}}
        <div class="col-lg-5">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold d-flex align-items-center gap-2 text-dark">
                        <i class="bi bi-sliders text-primary"></i>
                        Session Setup
                    </h5>
                </div>

                <div class="card-body p-4">

                    {{-- Faculty Information --}}
                    <div class="p-3 bg-light rounded-3 mb-3 d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;">
                            <i class="bi bi-person-video3"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Conducting Faculty</small>
                            <span class="fw-bold text-dark">{{ $faculty->faculty_name ?? $faculty->name ?? 'Faculty Member' }}</span>
                        </div>
                    </div>

                    <form action="{{ auth()->user()->role === 'admin' ? route('admin.qr.generate') : route('faculty.qr.generate') }}" method="POST">
                        @csrf

                        @if(auth()->user()->role === 'admin')
                            <div class="mb-3">
                                <label for="faculty_id" class="form-label fw-semibold text-dark">
                                    Faculty <span class="text-danger">*</span>
                                </label>
                                <select name="faculty_id" id="faculty_id" class="form-select" required>
                                    <option value="">Select Faculty</option>
                                    @foreach($faculties as $item)
                                        <option value="{{ $item->id }}" {{ old('faculty_id', $faculty->id ?? '') == $item->id ? 'selected' : '' }}>
                                            {{ $item->faculty_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- Subject Selection --}}
                        <div class="mb-3">
                            <label for="subject_id" class="form-label fw-semibold text-dark">
                                Subject <span class="text-danger">*</span>
                            </label>

                            <select name="subject_id" id="subject_id" class="form-select" required>
                                <option value="">Select Class Subject</option>
                                @foreach($subjects as $item)
                                    <option value="{{ $item->id }}" {{ old('subject_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} @if(!empty($item->code)) ({{ $item->code }}) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Lecture Name --}}
                        <div class="mb-3">
                            <label for="lecture_name" class="form-label fw-semibold text-dark">
                                Lecture / Topic Name
                            </label>

                            <input type="text" name="lecture_name" id="lecture_name" class="form-control" placeholder="e.g. Unit 3: Normalization & Keys" value="{{ old('lecture_name') }}">
                            <small class="text-muted">Optional topic description</small>
                        </div>

                        {{-- Lecture Date --}}
                        <div class="mb-3">
                            <label for="lecture_date" class="form-label fw-semibold text-dark">
                                Lecture Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="lecture_date" id="lecture_date" class="form-control" value="{{ old('lecture_date', date('Y-m-d')) }}" required>
                        </div>

                        {{-- Timings --}}
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <label for="start_time" class="form-label fw-semibold text-dark">
                                    Start Time <span class="text-danger">*</span>
                                </label>
                                <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', date('H:i')) }}" required>
                            </div>
                            <div class="col-6">
                                <label for="end_time" class="form-label fw-semibold text-dark">
                                    End Time <span class="text-danger">*</span>
                                </label>
                                <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', date('H:i', strtotime('+1 hour'))) }}" required>
                            </div>
                        </div>

                        {{-- Generate Button --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                <i class="bi bi-qr-code-scan fs-5"></i>
                                <span>Generate Live QR Code</span>
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- QR DISPLAY --}}
        {{-- ===================================================== --}}
        <div class="col-lg-7">

            <div class="card shadow-sm border-0 h-100 {{ isset($qr) ? 'border-primary' : '' }}">

                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold d-flex align-items-center gap-2 text-dark">
                        <i class="bi bi-display text-primary"></i>
                        Classroom QR Display
                    </h5>

                    @if(isset($qr) && isset($session))
                        <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1" id="btnProjectorMode">
                            <i class="bi bi-arrows-fullscreen"></i>
                            <span>Projector View</span>
                        </button>
                    @endif
                </div>

                <div class="card-body p-4 text-center d-flex flex-column align-items-center justify-content-center">

                    @if(isset($qr) && isset($session))

                        {{-- Active Status & Badge --}}
                        <div class="mb-3 d-flex align-items-center gap-2">
                            <span id="statusBadge" class="badge bg-success px-3 py-2 fs-6 d-inline-flex align-items-center gap-2 shadow-sm">
                                <span class="spinner-grow spinner-grow-sm text-light"></span>
                                <span>LIVE SESSION ACTIVE</span>
                            </span>
                        </div>

                        {{-- QR Code Image Box --}}
                        <div class="qr-code-presenter-box p-3 bg-white rounded-4 shadow-sm border mb-3" id="qrPresenterBox">
                            <img src="{{ $qr }}" id="attendanceQrImage" alt="Attendance QR Code" width="300" height="300" class="img-fluid rounded">
                        </div>

                        {{-- Countdown Progress Section --}}
                        <div class="w-100 max-w-md mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted small fw-semibold">QR Code Validity</span>
                                <span id="countdown" class="fw-bold fs-5 text-success">02:00</span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 9999px; background: #e2e8f0;">
                                <div id="countdownProgressBar" class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%;"></div>
                            </div>
                        </div>

                        {{-- Session Details Pill Card --}}
                        <div class="row g-2 text-start w-100 max-w-md p-3 bg-light rounded-4 border mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Subject</small>
                                <span class="fw-bold text-dark">{{ $subject->name }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Lecture</small>
                                <span class="fw-semibold text-dark">{{ $session->lecture_name ?: 'General Lecture' }}</span>
                            </div>
                            <div class="col-6 mt-2">
                                <small class="text-muted d-block">Date</small>
                                <span>{{ $session->lecture_date->format('d M Y') }}</span>
                            </div>
                            <div class="col-6 mt-2">
                                <small class="text-muted d-block">Time Window</small>
                                <span>{{ $session->start_time }} - {{ $session->end_time }}</span>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> Print QR
                            </button>

                            <a href="{{ $qr }}" download="Attendance_QR_{{ $session->id }}.png" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-download me-1"></i> Download Image
                            </a>
                        </div>

                    @else

                        {{-- Empty State --}}
                        <div class="py-5">
                            <div class="mb-3">
                                <div class="d-inline-flex p-4 rounded-circle bg-light text-muted">
                                    <i class="bi bi-qr-code" style="font-size: 4rem; opacity: 0.35;"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">No Active QR Session</h5>
                            <p class="text-muted max-w-sm mx-auto mb-0">
                                Fill out the session details on the left and click <strong>Generate Live QR Code</strong> to project it in class.
                            </p>
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

{{-- Projector Modal --}}
@if(isset($qr) && isset($session))
<div class="modal fade" id="projectorModal" tabindex="-1" aria-labelledby="projectorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-success px-3 py-2">CLASSROOM PROJECTOR VIEW</span>
                    <h5 class="modal-title mb-0" id="projectorModalLabel">{{ $subject->name }} — {{ $session->lecture_name ?: 'Attendance QR' }}</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center justify-content-center text-center p-4">
                <div class="p-4 bg-white rounded-5 shadow-lg mb-4">
                    <img src="{{ $qr }}" alt="Large Projector QR" style="width: min(460px, 80vw); height: auto;" class="img-fluid">
                </div>
                <h2 class="display-6 fw-bold mb-2 text-white">Scan with Smart Attendance App</h2>
                <div class="fs-4 text-warning fw-bold mb-3" id="modalCountdown">Time Remaining: 02:00</div>
                <p class="text-secondary mb-0">Session ID: #{{ $session->id }} • Date: {{ $session->lecture_date->format('d-m-Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<style>
    .max-w-md { max-width: 460px; }
    .max-w-sm { max-width: 340px; }
    .qr-code-presenter-box {
        transition: transform 0.2s ease;
    }
    .qr-code-presenter-box:hover {
        transform: scale(1.02);
    }
</style>

@if(isset($session))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const countdown = document.getElementById('countdown');
    const modalCountdown = document.getElementById('modalCountdown');
    const progressBar = document.getElementById('countdownProgressBar');
    const statusBadge = document.getElementById('statusBadge');
    const projectorBtn = document.getElementById('btnProjectorMode');
    const expiresAt = new Date("{{ $session->qr_expires_at->toIso8601String() }}").getTime();
    const totalDurationMs = 120000; // 2 minutes window

    if (projectorBtn) {
        projectorBtn.addEventListener('click', () => {
            const modal = new bootstrap.Modal(document.getElementById('projectorModal'));
            modal.show();
        });
    }

    function updateCountdown() {
        const now = new Date().getTime();
        const remaining = expiresAt - now;

        if (remaining <= 0) {
            if (countdown) {
                countdown.innerHTML = 'EXPIRED';
                countdown.className = 'fw-bold fs-5 text-danger';
            }
            if (modalCountdown) {
                modalCountdown.innerHTML = 'QR HAS EXPIRED';
                modalCountdown.className = 'fs-4 text-danger fw-bold mb-3';
            }
            if (progressBar) {
                progressBar.style.width = '0%';
                progressBar.className = 'progress-bar bg-danger';
            }
            if (statusBadge) {
                statusBadge.innerHTML = '<i class="bi bi-x-circle me-1"></i> SESSION EXPIRED';
                statusBadge.className = 'badge bg-danger px-3 py-2 fs-6 shadow-sm';
            }
            return;
        }

        const totalSeconds = Math.floor(remaining / 1000);
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = totalSeconds % 60;
        const timeString = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

        if (countdown) countdown.innerHTML = timeString;
        if (modalCountdown) modalCountdown.innerHTML = 'Time Remaining: ' + timeString;

        const percent = Math.max(0, Math.min(100, (remaining / totalDurationMs) * 100));
        if (progressBar) {
            progressBar.style.width = percent + '%';
            if (percent < 25) {
                progressBar.className = 'progress-bar bg-danger progress-bar-striped progress-bar-animated';
                if (countdown) countdown.className = 'fw-bold fs-5 text-danger';
            } else if (percent < 50) {
                progressBar.className = 'progress-bar bg-warning progress-bar-striped progress-bar-animated';
                if (countdown) countdown.className = 'fw-bold fs-5 text-warning';
            } else {
                progressBar.className = 'progress-bar bg-success progress-bar-striped progress-bar-animated';
                if (countdown) countdown.className = 'fw-bold fs-5 text-success';
            }
        }

        setTimeout(updateCountdown, 1000);
    }

    updateCountdown();
});
</script>
@endif

@endsection