@extends('layouts.app')

@section('title', 'Register | Smart Attendance')

@section('content')
    <div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 100px);">
        <div class="card auth-card border-0 shadow-lg">
            <div class="row g-0">
                <div class="col-md-5 auth-hero p-4 d-flex flex-column justify-content-center">
                    <div>
                        <h2 class="fw-bold">Create account</h2>
                        <p class="mb-0 opacity-75">Register your administrator account and start managing attendance.</p>
                    </div>
                </div>
                <div class="col-md-7 p-4 p-md-5">
                    <div class="mb-4 text-center">
                        <div class="fs-2">🎓</div>
                        <h3 class="h5 fw-bold mb-1">Register</h3>
                        <p class="text-muted small mb-0">Smart Attendance System</p>
                    </div>
                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Full name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Confirm password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button class="btn btn-primary w-100 py-2">Create Account</button>
                    </form>
                    <p class="text-center text-muted small mt-4 mb-0">Already registered? <a href="{{ route('login') }}">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection
