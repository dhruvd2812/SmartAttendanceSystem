@extends(auth()->check() && auth()->user()->role === 'faculty' ? 'layouts.faculty' : 'layouts.app')

@section('title', 'Add Student | Smart Attendance')
@section('page-title', 'Add Student')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10">

            <section class="card app-card border-0 shadow-sm">

                <div class="card-body p-4 p-md-5">

                    {{-- Header --}}
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">

                        <div>
                            <h1 class="h4 mb-1">Add Student</h1>

                            <p class="text-muted small mb-0">
                                Create a student profile and login account.
                            </p>
                        </div>

                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.students.index') : route('faculty.students.index') }}"
                           class="btn btn-soft-primary">
                            Back to list
                        </a>

                    </div>


                    {{-- Validation Errors --}}
                    @if($errors->any())

                        <div class="alert alert-danger mb-4">

                            <strong>Please fix the following errors:</strong>

                            <ul class="mb-0 mt-2">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- Student Form --}}
                    <form action="{{ auth()->user()->role === 'admin' ? route('admin.students.store') : route('faculty.students.store') }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf


                        <div class="row gy-3">


                            {{-- ========================= --}}
                            {{-- STUDENT INFORMATION --}}
                            {{-- ========================= --}}

                            <div class="col-12">

                                <h5 class="fw-bold mb-1">
                                    Student Information
                                </h5>

                                <p class="text-muted small">
                                    Enter the student's personal and academic details.
                                </p>

                            </div>


                            {{-- Enrollment Number --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Enrollment No
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="enrollment_no"
                                    class="form-control"
                                    value="{{ old('enrollment_no') }}"
                                    required
                                >

                            </div>


                            {{-- First Name --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    First Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="first_name"
                                    class="form-control"
                                    value="{{ old('first_name') }}"
                                    required
                                >

                            </div>


                            {{-- Last Name --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Last Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="last_name"
                                    class="form-control"
                                    value="{{ old('last_name') }}"
                                    required
                                >

                            </div>


                            {{-- Gender --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Gender
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="gender"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Gender
                                    </option>

                                    <option
                                        value="Male"
                                        {{ old('gender') == 'Male' ? 'selected' : '' }}
                                    >
                                        Male
                                    </option>

                                    <option
                                        value="Female"
                                        {{ old('gender') == 'Female' ? 'selected' : '' }}
                                    >
                                        Female
                                    </option>

                                </select>

                            </div>


                            {{-- Date of Birth --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Date of Birth
                                </label>

                                <input
                                    type="date"
                                    name="dob"
                                    class="form-control"
                                    value="{{ old('dob') }}"
                                >

                            </div>


                            {{-- Mobile --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Mobile Number
                                </label>

                                <input
                                    type="text"
                                    name="mobile"
                                    class="form-control"
                                    value="{{ old('mobile') }}"
                                >

                            </div>


                            {{-- ========================= --}}
                            {{-- LOGIN ACCOUNT --}}
                            {{-- ========================= --}}

                            <div class="col-12 mt-4">

                                <div class="border-top pt-4">

                                    <h5 class="fw-bold mb-1">
                                        Student Login Account
                                    </h5>

                                    <p class="text-muted small mb-3">
                                        These credentials will be used by the student to login.
                                    </p>

                                </div>

                            </div>


                            {{-- Login Email --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Login Email
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email') }}"
                                    placeholder="student@example.com"
                                    required
                                >

                                <small class="text-muted">
                                    This email will be used for Student Login.
                                </small>

                            </div>


                            {{-- Login Password --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Login Password
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Enter login password"
                                    required
                                >

                                <small class="text-muted">
                                    Minimum 8 characters.
                                </small>

                            </div>


                            {{-- Confirm Password --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Confirm Login Password
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="Confirm login password"
                                    required
                                >

                            </div>


                            {{-- Department --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Department
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="department_id"
                                    class="form-select"
                                    required
                                >

                                    <option value="">
                                        Select Department
                                    </option>

                                    @foreach($departments as $department)

                                        <option
                                            value="{{ $department->id }}"
                                            {{ old('department_id') == $department->id ? 'selected' : '' }}
                                        >

                                            {{ $department->department_name }}
                                            ({{ $department->department_code }})

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Semester --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Semester
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="semester"
                                    class="form-select"
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

                            </div>


                            {{-- Academic Year --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Academic Year
                                </label>

                                <input
                                    type="text"
                                    name="academic_year"
                                    class="form-control"
                                    placeholder="2026-2027"
                                    value="{{ old('academic_year') }}"
                                >

                            </div>


                            {{-- Address --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Address
                                </label>

                                <textarea
                                    name="address"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Enter student address"
                                >{{ old('address') }}</textarea>

                            </div>


                            {{-- Photo --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Student Photo
                                </label>

                                <input
                                    type="file"
                                    name="photo"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png"
                                >

                                <small class="text-muted">
                                    JPG, JPEG or PNG. Maximum 2MB.
                                </small>

                            </div>


                            {{-- Status --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-select"
                                >

                                    <option
                                        value="active"
                                        {{ old('status', 'active') == 'active' ? 'selected' : '' }}
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="inactive"
                                        {{ old('status') == 'inactive' ? 'selected' : '' }}
                                    >
                                        Inactive
                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- Buttons --}}
                        <div class="mt-4 d-flex flex-wrap gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Save Student
                            </button>

                            <button
                                type="reset"
                                class="btn btn-outline-secondary"
                            >
                                Reset
                            </button>

                            <a
                                href="{{ auth()->user()->role === 'admin' ? route('admin.students.index') : route('faculty.students.index') }}"
                                class="btn btn-soft-primary"
                            >
                                Back
                            </a>

                        </div>

                    </form>

                </div>

            </section>

        </div>
    </div>
@endsection