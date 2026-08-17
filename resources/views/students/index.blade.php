@extends('layouts.app')

@section('title', 'Students | Smart Attendance')

@section('content')

<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Students</h2>
            <p class="text-muted mb-0">
                Manage all students
            </p>
        </div>

        {{-- IMPORTANT: Admin route --}}
        <a href="{{ route('admin.students.create') }}"
           class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            Add Student
        </a>
    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Students Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold">
                Student List
            </h5>
        </div>

        <div class="card-body p-0">

            @if($students->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Gender</th>
                                <th>Phone</th>
                                <th class="text-center">
                                    Actions
                                </th>
                            </tr>

                        </thead>

                        <tbody>

                        @foreach($students as $student)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $student->name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $student->email }}
                                </td>

                                <td>
                                    {{ $student->department->name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ ucfirst($student->gender ?? 'N/A') }}
                                </td>

                                <td>
                                    {{ $student->phone ?? 'N/A' }}
                                </td>

                                <td class="text-center">

                                    {{-- View --}}
                                    <a href="{{ route('admin.students.show', $student->id) }}"
                                       class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-eye"></i>
                                    </a>


                                    {{-- Edit --}}
                                    <a href="{{ route('admin.students.edit', $student->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>


                                    {{-- Delete --}}
                                    <form action="{{ route('admin.students.destroy', $student->id) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this student?')">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>

                    <h5>No Students Found</h5>

                    <p class="text-muted">
                        No students have been added yet.
                    </p>

                    <a href="{{ route('admin.students.create') }}"
                       class="btn btn-primary">

                        <i class="fas fa-plus me-1"></i>
                        Add First Student

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection