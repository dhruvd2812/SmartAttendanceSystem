@extends('layouts.app')

@section('title', 'Admin Registration | Smart Attendance')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">

            <div class="card border-0 shadow-lg">

                {{-- HEADER --}}
                <div class="card-header bg-dark text-white p-4">

                    <div class="text-center">

                        <div class="fs-1 mb-2">
                            👨‍💼
                        </div>

                        <h2 class="fw-bold mb-1">
                            Admin Registration
                        </h2>

                        <p class="mb-0 opacity-75">
                            Create Admin Account
                        </p>

                    </div>

                </div>


                {{-- BODY --}}
                <div class="card-body p-4 p-md-5">

                    {{-- VALIDATION ERRORS --}}
                    @if ($errors->any())

                        <div class="alert alert-danger">

                            <strong>
                                Please fix the following errors:
                            </strong>

                            <ul class="mb-0 mt-2">

                                @foreach ($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- SUCCESS MESSAGE --}}
                    @if (session('success'))

                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>

                    @endif


                    {{-- ADMIN REGISTRATION FORM --}}
                    <form
                        method="POST"
                        action="{{ route('admin.register.store') }}"
                    >

                        @csrf


                        {{-- NAME --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Admin Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter admin name"
                                required
                            >

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- EMAIL --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="admin@example.com"
                                required
                            >

                            @error('email')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PASSWORD --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimum 8 characters"
                                required
                            >

                            @error('password')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- CONFIRM PASSWORD --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Confirm password"
                                required
                            >

                        </div>


                        {{-- REGISTER BUTTON --}}
                        <button
                            type="submit"
                            class="btn btn-dark w-100 py-2 fw-semibold"
                        >
                            👨‍💼 Create Admin Account
                        </button>

                    </form>


                    {{-- LOGIN LINK --}}
                    <div class="text-center mt-4">

                        <p class="text-muted mb-2">
                            Already have an account?
                        </p>

                        <a
                            href="{{ route('login') }}"
                            class="btn btn-outline-secondary"
                        >
                            ← Back to Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection