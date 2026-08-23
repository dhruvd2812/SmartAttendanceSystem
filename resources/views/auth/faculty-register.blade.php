@extends('layouts.app')

@section('title', 'Faculty Registration | Smart Attendance')

@section('content')

<div class="d-flex align-items-center justify-content-center py-4 py-md-5">
    <div class="card auth-card border-0 shadow-lg w-100" style="max-width: 960px;">
        <div class="row g-0">
            <div class="col-lg-4 auth-hero p-4 p-md-5 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <div class="brand-badge" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <h4 class="mb-0 fw-bold text-white">SmartAttendance</h4>
                    </div>

                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-3" style="background: rgba(255, 255, 255, 0.1);">
                        <i class="bi bi-person-badge" style="color: #67e8f9;"></i>
                        <span class="small fw-semibold text-white">Faculty portal</span>
                    </div>
                    <h2 class="fw-bold text-white mb-3" style="font-size: 1.75rem; line-height: 1.25;">
                        Make every class count
                    </h2>
                    <p class="text-indigo-100 opacity-85 mb-4" style="font-size: 0.92rem;">
                        Set up your faculty account to manage classes, launch secure attendance sessions, and keep students on track.
                    </p>

                    <div class="d-flex flex-column gap-2 mb-4">
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255, 255, 255, 0.08);">
                            <i class="bi bi-qr-code text-emerald" style="color: #34d399;"></i>
                            <span class="small fw-semibold text-white">Start live QR attendance</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255, 255, 255, 0.08);">
                            <i class="bi bi-graph-up-arrow" style="color: #67e8f9;"></i>
                            <span class="small fw-semibold text-white">Review class performance</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255, 255, 255, 0.08);">
                            <i class="bi bi-calendar3" style="color: #a5b4fc;"></i>
                            <span class="small fw-semibold text-white">Stay aligned with your timetable</span>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-top border-white-10">
                    <small class="text-white-50" style="font-size: 0.78rem;">
                        Already registered? <a href="{{ route('login') }}" class="text-white fw-bold text-decoration-underline">Sign in here</a>
                    </small>
                </div>
            </div>

            <div class="col-lg-8 p-4 p-md-5 bg-white">
                <div class="mb-4 text-center text-lg-start">
                    <h3 class="h4 fw-bold mb-1 text-dark">Create your faculty account</h3>
                    <p class="text-muted small mb-0">Tell us a little about you and your teaching department.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-custom mb-4">
                        <ul class="mb-0 ps-3 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('faculty.register.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label for="faculty_name" class="form-label fw-semibold text-dark small">Full name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input id="faculty_name" type="text" name="faculty_name" value="{{ old('faculty_name') }}" class="form-control border-start-0 @error('faculty_name') is-invalid @enderror" placeholder="Dr. Priya Shah" autocomplete="name" required autofocus>
                            </div>
                            @error('faculty_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-5">
                            <label for="employee_id" class="form-label fw-semibold text-dark small">Employee ID <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person-vcard"></i></span>
                                <input id="employee_id" type="text" name="employee_id" value="{{ old('employee_id') }}" class="form-control border-start-0 @error('employee_id') is-invalid @enderror" placeholder="FAC-1042" required>
                            </div>
                            @error('employee_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-7">
                            <label for="email" class="form-label fw-semibold text-dark small">Work email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control border-start-0 @error('email') is-invalid @enderror" placeholder="name@college.edu" autocomplete="email" required>
                            </div>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-5">
                            <label for="phone" class="form-label fw-semibold text-dark small">Phone number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control border-start-0 @error('phone') is-invalid @enderror" placeholder="Optional" maxlength="15" autocomplete="tel">
                            </div>
                            @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label for="department_id" class="form-label fw-semibold text-dark small">Department <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-building"></i></span>
                                <select id="department_id" name="department_id" class="form-select border-start-0 @error('department_id') is-invalid @enderror" required>
                                    <option value="">Select your department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('department_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold text-dark small">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                <input id="password" type="password" name="password" class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror" placeholder="At least 8 characters" autocomplete="new-password" required>
                                <button class="btn btn-outline-secondary border-start-0 text-muted" type="button" id="togglePasswordBtn" aria-label="Show password"><i class="bi bi-eye" id="togglePasswordIcon"></i></button>
                            </div>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold text-dark small">Confirm password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-shield-check"></i></span>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control border-start-0" placeholder="Re-enter password" autocomplete="new-password" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mt-4 fw-bold d-flex align-items-center justify-content-center gap-2">
                        <span>Create faculty account</span><i class="bi bi-arrow-right"></i>
                    </button>
                </form>

                <p class="text-center text-muted small mt-4 mb-0">Already registered? <a href="{{ route('login') }}" class="text-primary fw-semibold">Sign in to account</a></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && passwordIcon) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                passwordIcon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
                toggleBtn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            });
        }
    });
</script>
@endpush

@endsection