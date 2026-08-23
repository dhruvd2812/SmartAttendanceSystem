@extends('layouts.app')

@section('title', 'Login | Smart Attendance')

@section('content')

<div class="d-flex align-items-center justify-content-center py-4 py-md-5">

    <div class="card auth-card border-0 shadow-lg w-100" style="max-width: 960px;">

        <div class="row g-0">

            {{-- ===================================================== --}}
            {{-- LEFT SIDE (Hero) --}}
            {{-- ===================================================== --}}
            <div class="col-lg-5 auth-hero p-4 p-md-5 d-flex flex-column justify-content-between">

                <div>
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div class="brand-badge" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <h4 class="mb-0 fw-bold text-white tracking-tight">SmartAttendance</h4>
                    </div>

                    <h2 class="fw-bold text-white mb-3" style="font-size: 1.85rem; line-height: 1.25;">
                        Next-Gen Smart Attendance Platform
                    </h2>

                    <p class="text-indigo-100 opacity-85 mb-4" style="font-size: 0.95rem;">
                        Automated QR camera attendance, AI-driven assistant queries, and instant class tracking for students and faculties.
                    </p>

                    {{-- Feature Highlights --}}
                    <div class="d-flex flex-column gap-2 mb-4">
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(8px);">
                            <i class="bi bi-qr-code text-cyan" style="color: #38bdf8;"></i>
                            <span class="small fw-semibold text-white">Live Dynamic QR Attendance</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(8px);">
                            <i class="bi bi-robot text-indigo-300" style="color: #a5b4fc;"></i>
                            <span class="small fw-semibold text-white">Interactive Smart AI Chatbot</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(8px);">
                            <i class="bi bi-bar-chart-fill text-emerald" style="color: #34d399;"></i>
                            <span class="small fw-semibold text-white">Subject-wise Analytics & Reports</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-top border-white-10">
                    <small class="text-white-50" style="font-size: 0.78rem;">
                        © {{ date('Y') }} Smart Attendance System. Secure & Verified.
                    </small>
                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- RIGHT SIDE (Form) --}}
            {{-- ===================================================== --}}
            <div class="col-lg-7 p-4 p-md-5 bg-white">

                <div class="mb-4 text-center text-lg-start">
                    <h3 class="h4 fw-bold mb-1 text-dark">
                        Account Sign In
                    </h3>
                    <p class="text-muted small mb-0">
                        Enter your university email and password to access your dashboard.
                    </p>
                </div>

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-custom alert-dismissible fade show mb-3" role="alert">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- General Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-custom mb-3">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-dark small">
                            Email address
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control border-start-0 @error('email') is-invalid @enderror"
                                placeholder="name@college.edu"
                                autocomplete="email"
                                required
                                autofocus
                            >
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="form-label fw-semibold text-dark small mb-0">
                                Password
                            </label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >
                            <button class="btn btn-outline-secondary border-start-0 text-muted" type="button" id="togglePasswordBtn">
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="form-check mb-4">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="remember"
                            value="1"
                            id="remember"
                        >
                        <label class="form-check-label text-muted small" for="remember">
                            Remember me on this browser
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold d-flex align-items-center justify-content-center gap-2">
                        <span>Sign In</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </form>

                {{-- Registration Divider & Links --}}
                <div class="mt-4 pt-3 border-top text-center">
                    <p class="text-muted small mb-3">
                        Need an account? Choose your registration portal:
                    </p>

                    <div class="row g-2">
                        <div class="col-sm-6">
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm w-100 py-2 fw-semibold">
                                <i class="bi bi-mortarboard me-1"></i> Register Student
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('faculty.register') }}" class="btn btn-outline-success btn-sm w-100 py-2 fw-semibold">
                                <i class="bi bi-person-badge me-1"></i> Register Faculty
                            </a>
                        </div>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted" style="font-size: 0.75rem;">
                            Administrator access is managed directly by institution IT staff.
                        </small>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passInput = document.getElementById('password');
        const passIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passInput && passIcon) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passInput.type === 'password';
                passInput.type = isPassword ? 'text' : 'password';
                passIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        }
    });
</script>
@endpush

@endsection