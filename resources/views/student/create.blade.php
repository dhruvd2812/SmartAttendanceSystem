<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2>Add Student</h2>

    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label>Enrollment No</label>
            <input type="text" name="enrollment_no" class="form-control">
        </div>

        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Gender</label>
            <select name="gender" class="form-control">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control">
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">
                        {{ $department->department_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Semester</label>
            <input type="number" name="semester" class="form-control">
        </div>

        <div class="mb-3">
            <label>Academic Year</label>
            <input type="text" name="academic_year" class="form-control">
        </div>

        <!-- Photo Field -->
        <div class="mb-3">
            <label>Student Photo</label>
            <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png">
        </div>

        <button type="submit" class="btn btn-primary">
            Save Student
        </button>

    </form>

</div>

</body>
</html>