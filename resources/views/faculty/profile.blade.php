@extends('layouts.faculty')

@section('title', 'My Profile | Smart Attendance')
@section('page-title', 'My Profile')

@section('content')
<div class="container py-4" style="max-width: 680px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-1">Faculty Portal</p>
            <h1 class="h3 mb-0">Edit Profile</h1>
        </div>
        <a href="{{ route('faculty.dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <div class="card app-card border-0 shadow-sm">
        <div class="card-body p-4">
            <p class="text-muted">Email: <strong>{{ $user->email }}</strong> <span class="small">(set by admin)</span></p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('faculty.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="faculty_name" class="form-label">Full Name</label>
                        <input id="faculty_name" type="text" name="faculty_name" value="{{ old('faculty_name', $faculty->faculty_name ?? '') }}" class="form-control @error('faculty_name') is-invalid @enderror" required>
                        @error('faculty_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="employee_id" class="form-label">Employee ID</label>
                        <input id="employee_id" type="text" name="employee_id" value="{{ old('employee_id', $faculty->employee_id ?? '') }}" class="form-control @error('employee_id') is-invalid @enderror" required>
                        @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', $faculty->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror" required>
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <select id="department_id" name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected(old('department_id', $faculty->department_id ?? '') == $department->id)>{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <hr class="my-4">
                <p class="text-muted small">Leave the password fields empty if you only want to update profile details.</p>

                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input id="current_password" type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                </div>
                <button class="btn btn-primary">Save Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection
