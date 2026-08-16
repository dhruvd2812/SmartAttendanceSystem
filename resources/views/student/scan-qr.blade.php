@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('content')

<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">📷 Scan QR Code</h2>
            <p class="text-muted mb-0">
                Scan your faculty's QR code to mark attendance.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('student.dashboard') }}"
               class="btn btn-outline-secondary">
                ← Back to Dashboard
            </a>
        </div>

    </div>

    {{-- QR Scanner Card --}}
    <div class="row justify-content-center">

        <div class="col-12 col-md-8 col-lg-6">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4 text-center">

                    <h5 class="fw-bold mb-3">
                        Scan Attendance QR
                    </h5>

                    <p class="text-muted mb-4">
                        Allow camera access and scan the QR code displayed
                        by your faculty.
                    </p>

                    {{-- QR Scanner Area --}}
                    <div id="reader"
                         class="mx-auto"
                         style="width: 100%; max-width: 400px;">
                    </div>

                    {{-- Scanner Result --}}
                    <div id="scan-result"
                         class="alert d-none mt-4">
                    </div>

                    {{-- Status --}}
                    <div id="scanner-status"
                         class="text-muted mt-3">
                        Starting camera...
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const readerElement = document.getElementById('reader');
    const statusElement = document.getElementById('scanner-status');
    const resultElement = document.getElementById('scan-result');

    if (!readerElement) {
        return;
    }

    function onScanSuccess(decodedText) {

        console.log('QR Code:', decodedText);

        resultElement.classList.remove('d-none');
        resultElement.className = 'alert alert-success mt-4';

        resultElement.innerHTML =
            '<strong>QR Code detected!</strong><br>' +
            'Processing attendance...';

        statusElement.textContent = 'QR code detected.';

        /*
         * For now we only detect the QR code.
         * Attendance submission can be connected here next.
         */

    }

    function onScanFailure(error) {
        // Scanner continuously checks for QR codes.
        // No action required for failed scans.
    }

    const html5QrCode = new Html5QrcodeScanner(
        "reader",
        {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        },
        false
    );

    html5QrCode.render(
        onScanSuccess,
        onScanFailure
    );

    statusElement.textContent = 'Camera ready. Scan a QR code.';

});
</script>

@endpush