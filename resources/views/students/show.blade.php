<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h3>Student Details</h3>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th>Enrollment No</th>
                    <td>{{ $student->enrollment_no }}</td>
                </tr>

                <tr>
                    <th>First Name</th>
                    <td>{{ $student->first_name }}</td>
                </tr>

                <tr>
                    <th>Last Name</th>
                    <td>{{ $student->last_name }}</td>
                </tr>

                <tr>
                    <th>Gender</th>
                    <td>{{ $student->gender }}</td>
                </tr>

                <tr>
                    <th>Date of Birth</th>
                    <td>{{ $student->dob }}</td>
                </tr>

                <tr>
                    <th>Mobile</th>
                    <td>{{ $student->mobile }}</td>
                </tr>

                <tr>
                    <th>Email</th>
                    <td>{{ $student->email }}</td>
                </tr>

                <tr>
                    <th>Address</th>
                    <td>{{ $student->address }}</td>
                </tr>

                <tr>
                    <th>Department</th>
                    <td>{{ $student->department->department_name ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>Semester</th>
                    <td>{{ $student->semester }}</td>
                </tr>

                <tr>
                    <th>Academic Year</th>
                    <td>{{ $student->academic_year }}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>{{ $student->status }}</td>
                </tr>

            </table>

            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                Back
            </a>

        </div>

    </div>

</div>

</body>
</html>