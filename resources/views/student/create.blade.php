<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="card">

        <div class="card-header bg-primary text-white">
            <h3>Add Student</h3>
        </div>

        <div class="card-body">

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Enrollment No</label>
                        <input type="text" name="enrollment_no" class="form-control" value="{{ old('enrollment_no') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Gender</label>

                        <select name="gender" class="form-select" required>

                            <option value="">Select Gender</option>

                            <option value="Male">Male</option>

                            <option value="Female">Female</option>

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Department</label>

                        <select name="department_id" class="form-select" required>

                            <option value="">Select Department</option>

                            @foreach($departments as $department)

                                <option value="{{ $department->id }}">

                                    {{ $department->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Semester</label>

                        <select name="semester" class="form-select" required>

                            <option value="">Select Semester</option>

                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                            <option value="5">Semester 5</option>
                            <option value="6">Semester 6</option>
                            <option value="7">Semester 7</option>
                            <option value="8">Semester 8</option>

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Academic Year</label>
                        <input type="text" name="academic_year" class="form-control" placeholder="2026-2027" value="{{ old('academic_year') }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Student Photo</label>
                        <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>

                        <select name="status" class="form-select">

                            <option value="active">Active</option>

                            <option value="inactive">Inactive</option>

                        </select>

                    </div>

                </div>

                <button type="submit" class="btn btn-success">
                    Save Student
                </button>

                <button type="reset" class="btn btn-warning">
                    Reset
                </button>

                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                    Back
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>