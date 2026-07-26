<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Attendance Session</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3>Create Attendance Session</h3>
        </div>

        <div class="card-body">

            <form action="{{ route('attendance-sessions.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Faculty ID</label>
                    <input type="number" name="faculty_id" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Department</label>

                    <select name="department_id" class="form-select" required>
                        <option value="">Select Department</option>

                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">
                                {{ $department->department_name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject ID</label>
                    <input type="number" name="subject_id" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Semester</label>

                    <select name="semester" class="form-select">

                        @for($i=1;$i<=8;$i++)
                            <option value="{{ $i }}">
                                Semester {{ $i }}
                            </option>
                        @endfor

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lecture Name</label>
                    <input type="text"
                           name="lecture_name"
                           class="form-control"
                           placeholder="Example: DBMS Lecture"
                           required>
                </div>

                <button class="btn btn-success">
                    Generate Attendance Session
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>