@extends('layouts.app')

@section('content')

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-dark text-white d-flex justify-content-between">

            <h4>Faculty List</h4>

            <a href="{{ route('faculties.create') }}" class="btn btn-success">
                Add Faculty
            </a>

        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">

                <tr>

                    <th>ID</th>
                    <th>Name</th>
                    <th>Employee ID</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th width="180">Action</th>

                </tr>

                </thead>

                <tbody>

                @forelse($faculties as $faculty)

                <tr>

                    <td>{{ $faculty->id }}</td>

                    <td>{{ $faculty->faculty_name }}</td>

                    <td>{{ $faculty->employee_id }}</td>

                    <td>{{ $faculty->email }}</td>

                    <td>{{ $faculty->phone }}</td>

                    <td>{{ $faculty->department->department_name }}</td>

                    <td>

                        <a href="{{ route('faculties.edit',$faculty->id) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('faculties.destroy',$faculty->id) }}" method="POST" style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this Faculty?')">
                                Delete
                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7" class="text-center">
                        No Faculty Found
                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection