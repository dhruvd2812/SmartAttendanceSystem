@extends('layouts.app')

@section('title', 'Faculty Registration | Smart Attendance')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8 col-md-10">

            <div class="card border-0 shadow-lg">

                {{-- HEADER --}}
                <div class="card-header bg-primary text-white p-4">
                    <div class="text-center">

                        <div class="fs-1 mb-2">👨‍🏫</div>

                        <h2 class="fw-bold mb-1">
                            Faculty Registration
                        </h2>

                        <p class="mb-0 opacity-75">
                            Create your Faculty account
                        </p>

                    </div>
                </div>


                {{-- FORM --}}
                <div class="card-body p-4 p-md-5">

                    {{-- Validation Errors --}}
                    @if ($errors->any())

                        <div class="alert alert-danger">

                            <strong>Please fix the following errors:</strong>

                            <ul class="mb-0 mt-2">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form method="POST"
                          action="{{ route('faculty.register.store') }}">

                        @csrf


                        {{-- FACULTY NAME --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Faculty Name
                            </label>

                            <input
                                type="text"
                                name="faculty_name"
                                value="{{ old('faculty_name') }}"
                                class="form-control @error('faculty_name') is-invalid @enderror"
                                placeholder="Enter faculty name"
                                required
                            >

                            @error('faculty_name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- EMPLOYEE ID --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Employee ID
                            </label>

                            <input
                                type="text"
                                name="employee_id"
                                value="{{ old('employee_id') }}"
                                class="form-control @error('employee_id') is-invalid @enderror"
                                placeholder="Enter employee ID"
                                required
                            >

                            @error('employee_id')

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
                                placeholder="faculty@example.com"
                                required
                            >

                            @error('email')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PHONE --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Enter phone number"
                                maxlength="15"
                            >

                            @error('phone')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- DEPARTMENT --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Department
                            </label>

                            <select
                                name="department_id"
                                class="form-select @error('department_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    -- Select Department --
                                </option>

                                @foreach ($departments as $department)

                                    <option
                                        value="{{ $department->id }}"
                                        {{ old('department_id') == $department->id ? 'selected' : '' }}
                                    >
                                        {{ $department->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('department_id')

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
                            class="btn btn-primary w-100 py-2 fw-semibold"
                        >
                            👨‍🏫 Register as Faculty
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