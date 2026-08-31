@extends(auth()->check() && auth()->user()->role === 'faculty' ? 'layouts.faculty' : 'layouts.app')

@section('title', 'Student Details | Smart Attendance')
@section('page-title', 'Student Details')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <section class="card app-card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h1 class="h4 mb-1">Student Details</h1>
                            <p class="text-muted small mb-0">Review the selected student information.</p>
                        </div>
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.students.index') : route('faculty.students.index') }}" class="btn btn-soft-primary">Back to students</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr><th class="px-0 py-2">Enrollment No</th><td class="py-2">{{ $student->enrollment_no }}</td></tr>
                                <tr><th class="px-0 py-2">First Name</th><td class="py-2">{{ $student->first_name }}</td></tr>
                                <tr><th class="px-0 py-2">Last Name</th><td class="py-2">{{ $student->last_name }}</td></tr>
                                <tr><th class="px-0 py-2">Gender</th><td class="py-2">{{ $student->gender }}</td></tr>
                                <tr><th class="px-0 py-2">Date of Birth</th><td class="py-2">{{ $student->dob }}</td></tr>
                                <tr><th class="px-0 py-2">Mobile</th><td class="py-2">{{ $student->mobile }}</td></tr>
                                <tr><th class="px-0 py-2">Email</th><td class="py-2">{{ $student->email }}</td></tr>
                                <tr><th class="px-0 py-2">Address</th><td class="py-2">{{ $student->address }}</td></tr>
                                <tr><th class="px-0 py-2">Department</th><td class="py-2">{{ $student->department->department_name ?? 'N/A' }}</td></tr>
                                <tr><th class="px-0 py-2">Semester</th><td class="py-2">{{ $student->semester }}</td></tr>
                                <tr><th class="px-0 py-2">Academic Year</th><td class="py-2">{{ $student->academic_year }}</td></tr>
                                <tr><th class="px-0 py-2">Status</th><td class="py-2">{{ ucfirst($student->status) }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
