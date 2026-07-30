@extends('layouts.app')

@section('title', 'Edit Student | Smart Attendance')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <section class="card app-card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h1 class="h4 mb-1">Edit Student</h1>
                            <p class="text-muted small mb-0">Update the student record below.</p>
                        </div>
                        <a href="{{ route('students.index') }}" class="btn btn-soft-primary">Back to list</a>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('students.update',$student->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Enrollment Number</label>
                                <input type="text" name="enrollment_no" class="form-control" value="{{ old('enrollment_no',$student->enrollment_no) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name',$student->first_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name',$student->last_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="Male" {{ old('gender',$student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender',$student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob',$student->dob) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control" value="{{ old('mobile',$student->mobile) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email',$student->email) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <select name="department_id" class="form-select" required>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id',$student->department_id) == $department->id ? 'selected' : '' }}>{{ $department->department_name }} ({{ $department->department_code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Semester</label>
                                <select name="semester" class="form-select" required>
                                    @for($i=1;$i<=8;$i++)
                                        <option value="{{ $i }}" {{ old('semester',$student->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Academic Year</label>
                                <input type="text" name="academic_year" class="form-control" value="{{ old('academic_year',$student->academic_year) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3">{{ old('address',$student->address) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Student Photo</label>
                                <input type="file" name="photo" class="form-control">
                                @if($student->photo)
                                    <img src="{{ asset('uploads/students/'.$student->photo) }}" width="80" class="mt-2 border rounded">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ old('status',$student->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status',$student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary">Update Student</button>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Back</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
