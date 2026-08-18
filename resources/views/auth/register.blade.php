@extends('layouts.app')

@section('title', 'Student Registration | Smart Attendance')

@section('content')
    <div class="d-flex align-items-center justify-content-center py-5">
        <div class="card auth-card border-0 shadow-lg w-100" style="max-width: 850px;">

            <div class="row g-0">

                {{-- Left Side --}}
                <div class="col-md-4 auth-hero p-4 d-flex flex-column justify-content-center">
                    <div>
                        <div class="fs-1 mb-3">🎓</div>

                        <h2 class="fw-bold">Create Account</h2>

                        <p class="mb-0 opacity-75">
                            Register as a student and access your Smart Attendance dashboard.
                        </p>
                    </div>
                </div>

                {{-- Right Side --}}
                <div class="col-md-8 p-4 p-md-5">

                    <div class="mb-4 text-center">
                        <div class="fs-2">👨‍🎓</div>

                        <h3 class="h5 fw-bold mb-1">
                            Student Registration
                        </h3>

                        <p class="text-muted small mb-0">
                            Smart Attendance System
                        </p>
                    </div>

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf

                        {{-- Enrollment Number --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Enrollment No
                            </label>

                            <input
                                type="text"
                                name="enrollment_no"
                                value="{{ old('enrollment_no') }}"
                                class="form-control @error('enrollment_no') is-invalid @enderror"
                                placeholder="Enter enrollment number"
                                required
                            >

                            @error('enrollment_no')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- First Name + Last Name --}}
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    First Name
                                </label>

                                <input
                                    type="text"
                                    name="first_name"
                                    value="{{ old('first_name') }}"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    placeholder="Enter first name"
                                    required
                                >

                                @error('first_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Last Name
                                </label>

                                <input
                                    type="text"
                                    name="last_name"
                                    value="{{ old('last_name') }}"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    placeholder="Enter last name"
                                    required
                                >

                                @error('last_name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>


                        {{-- Gender --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Gender
                            </label>

                            <select
                                name="gender"
                                class="form-select @error('gender') is-invalid @enderror"
                                required
                            >
                                <option value="">Select Gender</option>

                                <option value="Male"
                                    {{ old('gender') === 'Male' ? 'selected' : '' }}>
                                    Male
                                </option>

                                <option value="Female"
                                    {{ old('gender') === 'Female' ? 'selected' : '' }}>
                                    Female
                                </option>
                            </select>

                            @error('gender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Enter email address"
                                required
                            >

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Department + Semester --}}
                        <div class="row">

                            <div class="col-md-7 mb-3">
                                <label class="form-label">
                                    Department
                                </label>

                                <select
                                    name="department_id"
                                    class="form-select @error('department_id') is-invalid @enderror"
                                    required
                                >
                                    <option value="">
                                        Select Department
                                    </option>

                                    @foreach(\App\Models\Department::orderBy('department_name')->get() as $department)

                                        <option
                                            value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}
                                        >
                                            {{ $department->department_name }}
                                        </option>

                                    @endforeach
                                </select>

                                @error('department_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            <div class="col-md-5 mb-3">
                                <label class="form-label">
                                    Semester
                                </label>

                                <select
                                    name="semester"
                                    class="form-select @error('semester') is-invalid @enderror"
                                    required
                                >
                                    <option value="">
                                        Select Semester
                                    </option>

                                    @for($i = 1; $i <= 8; $i++)

                                        <option
                                            value="{{ $i }}"
                                            {{ old('semester') == $i ? 'selected' : '' }}
                                        >
                                            Semester {{ $i }}
                                        </option>

                                    @endfor
                                </select>

                                @error('semester')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

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
                                placeholder="Enter password"
                                required
                            >

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label class="form-label">
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


                        {{-- Submit --}}
                        <button
                            type="submit"
                            class="btn btn-primary w-100 py-2"
                        >
                            Create Student Account
                        </button>

                    </form>


                    <p class="text-center text-muted small mt-4 mb-0">
                        Already registered?
                        <a href="{{ route('login') }}">
                            Login here
                        </a>
                    </p>

                </div>

            </div>

        </div>
    </div>
@endsection