@extends(auth()->user()->role === 'student' ? 'layouts.student' : 'layouts.app')

@section('title', 'Scan Attendance QR | Smart Attendance')
@section('page-title', 'QR Code Attendance Scanner')

@section('content')

<div class="container-fluid py-2">

    <div class="row justify-content-center">

        <div class="col-xl-8 col-lg-9">

            {{-- Scanner Card --}}
            <div class="card border-0 shadow-lg overflow-hidden scanner-card">

                {{-- Header --}}
                <div class="scanner-header p-4 text-white d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="scanner-icon-badge">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Live Attendance Scanner</h4>
                            <p class="mb-0 opacity-75 small">Align the classroom QR code inside the target frame</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-emerald-soft text-emerald px-3 py-2 d-inline-flex align-items-center gap-2" id="scannerLiveBadge">
                            <span class="scanner-live-dot"></span>
                            <span>Camera Active</span>
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">

                    {{-- Student Badge Info --}}
                    <div class="student-info-strip mb-4 p-3 rounded-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="student-avatar-mini">
                                {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $student->full_name ?? ($student->first_name . ' ' . $student->last_name) }}</div>
                                <div class="text-muted small">Enrollment: <span class="fw-semibold text-primary">{{ $student->enrollment_no }}</span></div>
                            </div>
                        </div>

                        {{-- Camera Selector --}}
                        <div class="d-flex align-items-center gap-2" id="cameraSelectWrapper">
                            <i class="bi bi-camera text-muted"></i>
                            <select id="cameraSelect" class="form-select form-select-sm shadow-none" style="min-width: 160px; font-size: 0.82rem;">
                                <option value="">Detecting cameras...</option>
                            </select>
                        </div>
                    </div>

                    {{-- Viewfinder Container --}}
                    <div class="scanner-viewport-wrapper mb-4">
                        <div id="reader" class="scanner-reader"></div>

                        {{-- High-tech Frame Overlay --}}
                        <div class="scanner-frame-overlay" id="scannerOverlay">
                            <div class="scanner-laser-line"></div>
                            <div class="frame-corner top-left"></div>
                            <div class="frame-corner top-right"></div>
                            <div class="frame-corner bottom-left"></div>
                            <div class="frame-corner bottom-right"></div>
                            <div class="scanner-target-hint">Align QR inside frame</div>
                        </div>
                    </div>

                    {{-- Status Message --}}
                    <div id="scanStatus" class="status-banner status-banner-info text-center">
                        <i class="bi bi-camera-fill me-1"></i> Point your camera at the faculty QR code to mark attendance.
                    </div>

                    {{-- Attendance Result Card (Celebration) --}}
                    <div id="attendanceResult" class="attendance-success-card d-none mt-4">
                        {{-- Populated dynamically via JS --}}
                    </div>

                    {{-- Controls & Actions --}}
                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mt-4">
                        {{-- File Input Fallback --}}
                        <label class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2 mb-0" style="cursor: pointer;">
                            <i class="bi bi-image"></i>
                            <span>Scan from Image File</span>
                            <input type="file" id="qrFileInput" accept="image/*" class="d-none">
                        </label>

                        <button type="button" class="btn btn-outline-primary btn-sm d-none" id="restartScannerBtn">
                            <i class="bi bi-arrow-repeat me-1"></i> Scan Another QR
                        </button>

                        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<style>
    .scanner-card {
        border-radius: 1.5rem;
        background: #ffffff;
    }

    .scanner-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
    }

    .scanner-icon-badge {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
    }

    .bg-emerald-soft {
        background: rgba(16, 185, 129, 0.15) !important;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }

    .text-emerald {
        color: #10b981 !important;
    }

    .scanner-live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 10px #10b981;
        animation: pulseLiveDot 1.5s infinite;
    }

    @keyframes pulseLiveDot {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.4); opacity: 0.6; }
    }

    .student-info-strip {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .student-avatar-mini {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }

    /* Scanner Viewport & HUD */
    .scanner-viewport-wrapper {
        position: relative;
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        border-radius: 20px;
        overflow: hidden;
        background: #0f172a;
        box-shadow: 0 15px 35px -5px rgba(15, 23, 42, 0.3);
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .scanner-reader {
        width: 100% !important;
        height: 100% !important;
        border: none !important;
    }

    .scanner-reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        border-radius: 20px;
    }

    .scanner-frame-overlay {
        position: absolute;
        top: 15%;
        left: 15%;
        right: 15%;
        bottom: 15%;
        pointer-events: none;
        box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.45);
        border-radius: 16px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 12px;
    }

    .scanner-target-hint {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        background: rgba(15, 23, 42, 0.6);
        padding: 3px 10px;
        border-radius: 9999px;
        backdrop-filter: blur(4px);
    }

    .scanner-laser-line {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent 0%, #06b6d4 50%, #4f46e5 100%);
        box-shadow: 0 0 15px #06b6d4, 0 0 8px #4f46e5;
        animation: scanLaser 2.2s ease-in-out infinite alternate;
    }

    @keyframes scanLaser {
        0% { top: 4%; opacity: 0.9; }
        100% { top: 96%; opacity: 0.9; }
    }

    .frame-corner {
        position: absolute;
        width: 24px;
        height: 24px;
        border-color: #06b6d4;
        border-style: solid;
    }

    .top-left { top: -2px; left: -2px; border-width: 3px 0 0 3px; border-top-left-radius: 8px; }
    .top-right { top: -2px; right: -2px; border-width: 3px 3px 0 0; border-top-right-radius: 8px; }
    .bottom-left { bottom: -2px; left: -2px; border-width: 0 0 3px 3px; border-bottom-left-radius: 8px; }
    .bottom-right { bottom: -2px; right: -2px; border-width: 0 3px 3px 0; border-bottom-right-radius: 8px; }

    /* Status Banners */
    .status-banner {
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .status-banner-info {
        background: rgba(99, 102, 241, 0.08);
        border: 1px solid rgba(99, 102, 241, 0.2);
        color: #3730a3;
    }

    .status-banner-warning {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.25);
        color: #b45309;
    }

    .status-banner-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.25);
        color: #b91c1c;
    }

    .status-banner-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.25);
        color: #047857;
    }

    /* Attendance Result Celebration */
    .attendance-success-card {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 2px solid #86efac;
        border-radius: 20px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.15);
        animation: cardSuccessPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes cardSuccessPop {
        from { transform: scale(0.92); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .success-celebration-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
        margin-bottom: 12px;
    }
</style>

{{-- QR Scanner Library --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const scanner = new Html5Qrcode("reader");
    const statusBox = document.getElementById('scanStatus');
    const resultBox = document.getElementById('attendanceResult');
    const overlay = document.getElementById('scannerOverlay');
    const cameraSelect = document.getElementById('cameraSelect');
    const fileInput = document.getElementById('qrFileInput');
    const restartBtn = document.getElementById('restartScannerBtn');
    const liveBadge = document.getElementById('scannerLiveBadge');

    let processing = false;
    let currentCameraId = null;

    function playSuccessChime() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const now = ctx.currentTime;
            
            // Double pleasant chime
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.frequency.setValueAtTime(523.25, now); // C5
            gain1.gain.setValueAtTime(0.12, now);
            gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.2);
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.start(now);
            osc1.stop(now + 0.2);

            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.frequency.setValueAtTime(659.25, now + 0.12); // E5
            gain2.gain.setValueAtTime(0.14, now + 0.12);
            gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.4);
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.start(now + 0.12);
            osc2.stop(now + 0.4);
        } catch(e) {}
    }

    function showStatus(message, type = 'info') {
        statusBox.className = `status-banner status-banner-${type} text-center`;
        statusBox.innerHTML = message;
    }

    function stopScanner() {
        scanner.stop().then(() => {
            if (overlay) overlay.classList.add('d-none');
            if (liveBadge) liveBadge.classList.add('d-none');
        }).catch(() => {});
    }

    function startScanner(cameraId) {
        currentCameraId = cameraId;
        processing = false;
        resultBox.classList.add('d-none');
        restartBtn.classList.add('d-none');
        if (overlay) overlay.classList.remove('d-none');
        if (liveBadge) liveBadge.classList.remove('d-none');
        showStatus('<i class="bi bi-camera-fill me-1"></i> Point camera at the attendance QR code.');

        scanner.start(
            cameraId,
            {
                fps: 15,
                qrbox: { width: 260, height: 260 }
            },
            decodedText => {
                processQr(decodedText);
            },
            errorMessage => {
                // Ignore scanning cycle ticks
            }
        ).catch(err => {
            console.error(err);
            showStatus('<i class="bi bi-exclamation-octagon-fill me-1"></i> Unable to access camera. Please allow camera permissions.', 'danger');
        });
    }

    function processQr(decodedText) {
        if (processing) return;
        processing = true;

        showStatus('<div class="spinner-border spinner-border-sm me-2"></div> QR Code detected! Verifying attendance...', 'warning');

        let qrData;
        try {
            qrData = JSON.parse(decodedText);
        } catch (error) {
            processing = false;
            showStatus('<i class="bi bi-x-circle-fill me-1"></i> Invalid attendance QR code format.', 'danger');
            return;
        }

        if (qrData.type !== 'attendance' || !qrData.session_id || !qrData.qr_token) {
            processing = false;
            showStatus('<i class="bi bi-x-circle-fill me-1"></i> This is not an active Smart Attendance QR.', 'danger');
            return;
        }

        stopScanner();

        fetch('{{ route('student.attendance.scan') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                session_id: qrData.session_id,
                qr_token: qrData.qr_token
            })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Unable to mark attendance.');
            }
            return data;
        })
        .then(data => {
            playSuccessChime();
            showStatus('<i class="bi bi-check-circle-fill me-1"></i> ' + (data.message || 'Attendance marked successfully!'), 'success');

            const att = data.attendance;
            resultBox.innerHTML = `
                <div class="success-celebration-icon">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h4 class="fw-bold text-success mb-1">Attendance Marked: Present!</h4>
                <p class="text-muted small mb-4">Your attendance has been recorded into the system.</p>

                <div class="row g-2 text-start max-w-md mx-auto p-3 bg-white rounded-3 border border-success-subtle">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Subject</small>
                        <span class="fw-bold">${att.subject} (${att.subject_code})</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Faculty</small>
                        <span class="fw-semibold">${att.faculty ?? 'Assigned Faculty'}</span>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <small class="text-muted d-block">Student</small>
                        <span>${att.student_name}</span>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge bg-success">PRESENT</span>
                    </div>
                </div>
            `;
            resultBox.classList.remove('d-none');
            restartBtn.classList.remove('d-none');
        })
        .catch(error => {
            processing = false;
            showStatus('<i class="bi bi-exclamation-triangle-fill me-1"></i> ' + error.message, 'danger');
            restartBtn.classList.remove('d-none');
        });
    }

    // Initialize Cameras
    Html5Qrcode.getCameras().then(cameras => {
        if (!cameras || cameras.length === 0) {
            showStatus('<i class="bi bi-camera-video-off-fill me-1"></i> No camera detected on this device. You can upload a QR image below.', 'warning');
            return;
        }

        cameraSelect.innerHTML = '';
        cameras.forEach((camera, index) => {
            const opt = document.createElement('option');
            opt.value = camera.id;
            opt.textContent = camera.label || `Camera ${index + 1}`;
            cameraSelect.appendChild(opt);
        });

        // Prefer back / environmental camera
        const backCamera = cameras.find(c => c.label && (c.label.toLowerCase().includes('back') || c.label.toLowerCase().includes('rear') || c.label.toLowerCase().includes('environment')));
        const chosenCameraId = backCamera ? backCamera.id : cameras[cameras.length - 1].id;
        cameraSelect.value = chosenCameraId;

        startScanner(chosenCameraId);

        cameraSelect.addEventListener('change', () => {
            stopScanner();
            setTimeout(() => startScanner(cameraSelect.value), 300);
        });
    }).catch(err => {
        console.error(err);
        showStatus('<i class="bi bi-shield-lock-fill me-1"></i> Camera permission required. Please grant permission or upload an image.', 'danger');
    });

    // File Input Scan Fallback
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length === 0) return;
        const imageFile = e.target.files[0];
        showStatus('<div class="spinner-border spinner-border-sm me-2"></div> Scanning uploaded image file...', 'warning');

        scanner.scanFile(imageFile, true)
            .then(decodedText => {
                processQr(decodedText);
            })
            .catch(err => {
                showStatus('<i class="bi bi-x-circle-fill me-1"></i> No readable QR code found in this image.', 'danger');
            });
    });

    restartBtn.addEventListener('click', () => {
        if (currentCameraId) {
            startScanner(currentCameraId);
        }
    });
});
</script>

@endsection