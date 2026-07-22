<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Student</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-warning">

            <h3>Edit Student</h3>

        </div>

        <div class="card-body">

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form action="{{ route('students.update',$student->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Enrollment Number</label>

                        <input type="text"
                               name="enrollment_no"
                               class="form-control"
                               value="{{ old('enrollment_no',$student->enrollment_no) }}"
                               required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">First Name</label>

                        <input type="text"
                               name="first_name"
                               class="form-control"
                               value="{{ old('first_name',$student->first_name) }}"
                               required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Last Name</label>

                        <input type="text"
                               name="last_name"
                               class="form-control"
                               value="{{ old('last_name',$student->last_name) }}"
                               required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Gender</label>

                        <select name="gender" class="form-select" required>

                            <option value="Male" {{ $student->gender=='Male' ? 'selected' : '' }}>
                                Male
                            </option>

                            <option value="Female" {{ $student->gender=='Female' ? 'selected' : '' }}>
                                Female
                            </option>

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Date of Birth</label>

                        <input type="date"
                               name="dob"
                               class="form-control"
                               value="{{ old('dob',$student->dob) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Mobile Number</label>

                        <input type="text"
                               name="mobile"
                               class="form-control"
                               value="{{ old('mobile',$student->mobile) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Email</label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email',$student->email) }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Department</label>

                        <select name="department_id" class="form-select" required>

                            @foreach($departments as $department)

                                <option value="{{ $department->id }}"
                                    {{ $student->department_id==$department->id ? 'selected' : '' }}>

                                    {{ $department->department_name }} ({{ $department->department_code }})

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Semester</label>

                        <select name="semester" class="form-select" required>

                            @for($i=1;$i<=8;$i++)

                                <option value="{{ $i }}"
                                    {{ $student->semester==$i ? 'selected' : '' }}>

                                    Semester {{ $i }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Academic Year</label>

                        <input type="text"
                               name="academic_year"
                               class="form-control"
                               value="{{ old('academic_year',$student->academic_year) }}">

                    </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label">Address</label>

                        <textarea name="address"
                                  class="form-control"
                                  rows="3">{{ old('address',$student->address) }}</textarea>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Student Photo</label>

                        <input type="file"
                               name="photo"
                               class="form-control">

                        @if($student->photo)

                            <img src="{{ asset('uploads/students/'.$student->photo) }}"
                                 width="80"
                                 class="mt-2 border rounded">

                        @endif

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">Status</label>

                        <select name="status" class="form-select">

                            <option value="active"
                                {{ $student->status=='active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ $student->status=='inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>

                <button type="submit" class="btn btn-success">
                    Update Student
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
