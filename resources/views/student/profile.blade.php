@extends('layouts.student')

@section('title', 'My Profile | Smart Attendance')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="mb-4">

        <h1 class="h3 mb-1">
            My Profile
        </h1>

        <p class="text-muted mb-0">
            View your personal, contact and academic information.
        </p>

    </div>


    {{-- Profile Information --}}
    <div class="row g-4">

        {{-- Personal Details --}}
        <div class="col-12 col-lg-6">

            <div class="card app-card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h2 class="h5 mb-4">
                        Personal Details
                    </h2>

                    <div class="mb-3">

                        <small class="text-muted">
                            First Name
                        </small>

                        <div class="fw-semibold">
                            {{ $student->first_name }}
                        </div>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted">
                            Last Name
                        </small>

                        <div class="fw-semibold">
                            {{ $student->last_name }}
                        </div>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted">
                            Gender
                        </small>

                        <div class="fw-semibold">
                            {{ $student->gender ?? 'Not available' }}
                        </div>

                    </div>

                    <div>

                        <small class="text-muted">
                            Date of Birth
                        </small>

                        <div class="fw-semibold">
                            {{ $student->dob ?? 'Not available' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Contact Details --}}
        <div class="col-12 col-lg-6">

            <div class="card app-card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h2 class="h5 mb-4">
                        Contact Details
                    </h2>

                    <div class="mb-3">

                        <small class="text-muted">
                            Email
                        </small>

                        <div class="fw-semibold">
                            {{ $student->email ?? 'Not available' }}
                        </div>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted">
                            Mobile
                        </small>

                        <div class="fw-semibold">
                            {{ $student->mobile ?? 'Not available' }}
                        </div>

                    </div>

                    <div>

                        <small class="text-muted">
                            Address
                        </small>

                        <div class="fw-semibold">
                            {{ $student->address ?? 'Not available' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Academic Details --}}
        <div class="col-12">

            <div class="card app-card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h2 class="h5 mb-4">
                        Academic Details
                    </h2>

                    <div class="row g-4">

                        <div class="col-12 col-md-6 col-xl-3">

                            <small class="text-muted">
                                Enrollment Number
                            </small>

                            <div class="fw-semibold">
                                {{ $student->enrollment_no }}
                            </div>

                        </div>


                        <div class="col-12 col-md-6 col-xl-3">

                            <small class="text-muted">
                                Department
                            </small>

                            <div class="fw-semibold">
                                {{ $department?->department_name ?? 'Not assigned' }}
                            </div>

                        </div>


                        <div class="col-12 col-md-6 col-xl-3">

                            <small class="text-muted">
                                Semester
                            </small>

                            <div class="fw-semibold">
                                {{ $student->semester }}
                            </div>

                        </div>


                        <div class="col-12 col-md-6 col-xl-3">

                            <small class="text-muted">
                                Academic Year
                            </small>

                            <div class="fw-semibold">
                                {{ $student->academic_year ?? 'Not available' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection