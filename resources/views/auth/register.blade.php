@extends('layouts.app')

@section('title', 'Student Registration | Smart Attendance')

@section('content')

<div class="d-flex align-items-center justify-content-center py-4 py-md-5">

    <div class="card auth-card border-0 shadow-lg w-100" style="max-width: 960px;">

        <div class="row g-0">

            {{-- ===================================================== --}}
            {{-- LEFT SIDE (Hero) --}}
            {{-- ===================================================== --}}
            <div class="col-lg-4 auth-hero p-4 p-md-5 d-flex flex-column justify-content-between">

                <div>
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div class="brand-badge" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <h4 class="mb-0 fw-bold text-white">SmartAttendance</h4>
                    </div>

                    <h2 class="fw-bold text-white mb-3" style="font-size: 1.75rem;">
                        Join as a Student
                    </h2>

                    <p class="text-indigo-100 opacity-85 mb-4" style="font-size: 0.92rem;">
                        Create your student account to scan attendance QR codes, track your subject percentages, and get timetable alerts.
                    </p>

                    <div class="d-flex flex-column gap-2 mb-4">
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255, 255, 255, 0.08);">
                            <i class="bi bi-check2-circle text-emerald" style="color: #34d399;"></i>
                            <span class="small fw-semibold text-white">Instant QR Code Scanning</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255, 255, 255, 0.08);">
                            <i class="bi bi-check2-circle text-emerald" style="color: #34d399;"></i>
                            <span class="small fw-semibold text-white">Real-time Attendance Status</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255, 255, 255, 0.08);">
                            <i class="bi bi-check2-circle text-emerald" style="color: #34d399;"></i>
                            <span class="small fw-semibold text-white">Personal AI Chat Assistant</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-top border-white-10">
                    <small class="text-white-50" style="font-size: 0.78rem;">
                        Already registered? <a href="{{ route('login') }}" class="text-white fw-bold text-decoration-underline">Sign in here</a>
                    </small>
                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- RIGHT SIDE (Form) --}}
            {{-- ===================================================== --}}
            <div class="col-lg-8 p-4 p-md-5 bg-white">

                <div class="mb-4 text-center text-lg-start">
                    <h3 class="h4 fw-bold mb-1 text-dark">
                        Student Registration
                    </h3>
                    <p class="text-muted small mb-0">
                        Fill in your academic and personal information below.
                    </p>
                </div>

                {{-- General Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-custom mb-4">
                        <ul class="mb-0 ps-3 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div class="row g-3">

                        {{-- Enrollment Number --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">
                                Enrollment Number <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-card-text"></i>
                                </span>
                                <input
                                    type="text"
                                    name="enrollment_no"
                                    value="{{ old('enrollment_no') }}"
                                    class="form-control border-start-0 @error('enrollment_no') is-invalid @enderror"
                                    placeholder="e.g. 21012011001"
                                    required
                                >
                            </div>
                            @error('enrollment_no')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">
                                Gender <span class="text-danger">*</span>
                            </label>
                            <select
                                name="gender"
                                class="form-select @error('gender') is-invalid @enderror"
                                required
                            >
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- First Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">
                                First Name <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                class="form-control @error('first_name') is-invalid @enderror"
                                placeholder="First Name"
                                required
                            >
                            @error('first_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Last Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">
                                Last Name <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                class="form-control @error('last_name') is-invalid @enderror"
                                placeholder="Last Name"
                                required
                            >
                            @error('last_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark small">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="form-control border-start-0 @error('email') is-invalid @enderror"
                                    placeholder="student@college.edu"
                                    required
                                >
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Department --}}
                        <div class="col-md-7">
                            <label class="form-label fw-semibold text-dark small">
                                Department <span class="text-danger">*</span>
                            </label>
                            <select
                                name="department_id"
                                class="form-select @error('department_id') is-invalid @enderror"
                                required
                            >
                                <option value="">Select Department</option>
                                @foreach(\App\Models\Department::orderBy('department_name')->get() as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->department_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Semester --}}
                        <div class="col-md-5">
                            <label class="form-label fw-semibold text-dark small">
                                Semester <span class="text-danger">*</span>
                            </label>
                            <select
                                name="semester"
                                class="form-select @error('semester') is-invalid @enderror"
                                required
                            >
                                <option value="">Semester</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
                                        Semester {{ $i }}
                                    </option>
                                @endfor
                            </select>
                            @error('semester')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">
                                Password <span class="text-danger">*</span>
                            </label>
                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="At least 8 characters"
                                required
                            >
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">
                                Confirm Password <span class="text-danger">*</span>
                            </label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Re-enter password"
                                required
                            >
                        </div>

                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold d-flex align-items-center justify-content-center gap-2">
                            <span>Create Student Account</span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>

                </form>

                <p class="text-center text-muted small mt-4 mb-0">
                    Already registered?
                    <a href="{{ route('login') }}" class="text-primary fw-semibold">
                        Sign in to account
                    </a>
                </p>

            </div>

        </div>

    </div>

</div>

@endsection