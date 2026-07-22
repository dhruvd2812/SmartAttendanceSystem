<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student List</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h3 class="mb-0">Student List</h3>

            <a href="{{ route('students.create') }}" class="btn btn-light">
                Add Student
            </a>

        </div>

        <div class="card-body">

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            <table class="table table-bordered table-striped table-hover">

                <thead class="table-dark">

                <tr>

                    <th>ID</th>

                    <th>Photo</th>

                    <th>Enrollment No</th>

                    <th>Student Name</th>

                    <th>Department</th>

                    <th>Semester</th>

                    <th>Status</th>

                    <th width="220">Action</th>

                </tr>

                </thead>

                <tbody>

                @forelse($students as $student)

                    <tr>

                        <td>{{ $student->id }}</td>

                        <td>

                            @if($student->photo)

                                <img src="{{ asset('uploads/students/'.$student->photo) }}"
                                     width="60"
                                     height="60"
                                     class="rounded">

                            @else

                                No Photo

                            @endif

                        </td>

                        <td>{{ $student->enrollment_no }}</td>

                        <td>

                            {{ $student->first_name }}

                            {{ $student->last_name }}

                        </td>

                        <td>

                            {{ $student->department->name ?? 'N/A' }}

                        </td>

                        <td>

                            Semester {{ $student->semester }}

                        </td>

                        <td>

                            @if($student->status == 'active')

                                <span class="badge bg-success">Active</span>

                            @else

                                <span class="badge bg-danger">Inactive</span>

                            @endif

                        </td>

                        <td>

                            <a href="{{ route('students.show',$student->id) }}"
                               class="btn btn-info btn-sm">

                                View

                            </a>

                            <a href="{{ route('students.edit',$student->id) }}"
                               class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <form action="{{ route('students.destroy',$student->id) }}"
                                  method="POST"
                                  style="display:inline;">

                                @csrf

                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this student?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" class="text-center">

                            No Students Found

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>