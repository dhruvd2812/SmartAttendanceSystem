@extends('layouts.app')

@section('title', 'Login | Smart Attendance')

@section('content')

<div class="d-flex align-items-center justify-content-center"
     style="min-height: calc(100vh - 100px);">

    <div class="card auth-card border-0 shadow-lg">

        <div class="row g-0">

            {{-- ===================================================== --}}
            {{-- LEFT SIDE --}}
            {{-- ===================================================== --}}

            <div class="col-md-5 auth-hero p-4 d-flex flex-column justify-content-center">

                <div>

                    <h2 class="fw-bold">
                        Welcome back
                    </h2>

                    <p class="mb-0 opacity-75">
                        Sign in and continue managing your attendance system.
                    </p>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- RIGHT SIDE --}}
            {{-- ===================================================== --}}

            <div class="col-md-7 p-4 p-md-5">

                {{-- Header --}}

                <div class="mb-4 text-center">

                    <div class="fs-2">
                        🎓
                    </div>

                    <h3 class="h5 fw-bold mb-1">
                        Smart Attendance Login
                    </h3>

                    <p class="text-muted small mb-0">
                        Smart Attendance System
                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- LOGIN FORM --}}
                {{-- ================================================= --}}

                <form method="POST" action="{{ route('login.store') }}">

                    @csrf


                    {{-- Email --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Email address
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Enter your email"
                            required
                            autofocus
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Password --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter your password"
                            required
                        >

                        @error('password')
                            <div class="invalid-feedback">
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

                        <label
                            class="form-check-label"
                            for="remember"
                        >
                            Remember me
                        </label>

                    </div>


                    {{-- Login Button --}}

                    <button
                        type="submit"
                        class="btn btn-primary w-100 py-2"
                    >
                        Login
                    </button>

                </form>


                {{-- ================================================= --}}
                {{-- REGISTRATION SECTION --}}
                {{-- ================================================= --}}

                <div class="text-center mt-4">

                    <p class="text-muted small mb-3">
                        Don't have an account?
                    </p>


                    {{-- ============================================= --}}
                    {{-- STUDENT REGISTRATION --}}
                    {{-- ============================================= --}}

                    <a
                        href="{{ route('register') }}"
                        class="btn btn-outline-primary w-100 mb-2"
                    >
                        🎓 Register as Student
                    </a>


                    {{-- ============================================= --}}
                    {{-- FACULTY REGISTRATION --}}
                    {{-- ============================================= --}}

                    <a
                        href="{{ route('faculty.register') }}"
                        class="btn btn-outline-success w-100 mb-2"
                    >
                        👨‍🏫 Register as Faculty
                    </a>


                    {{-- ============================================= --}}
                    {{-- ADMIN REGISTRATION --}}
                    {{-- ============================================= --}}

                    <a
                        href="{{ route('admin.register') }}"
                        class="btn btn-outline-danger w-100"
                    >
                        🛡️ Register as Admin
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection