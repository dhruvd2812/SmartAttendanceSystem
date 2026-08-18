@extends('layouts.app')

@section('title', 'Faculty Dashboard | Admin')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="app-hero p-4 p-md-5 mb-4">

        <p class="mb-2 opacity-75">
            Administrator Access
        </p>

        <h1 class="display-6 mb-2">
            Faculty Dashboard
        </h1>

        <p class="mb-0">
            Admin can view Faculty information.
        </p>

    </div>


    {{-- Faculty List --}}
    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                Faculty Members
            </h5>

        </div>


        <div class="card-body">

            @if($faculties->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead>

                            <tr>
                                <th>#</th>
                                <th>Faculty Name</th>
                                <th>Employee ID</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Department</th>
                            </tr>

                        </thead>


                        <tbody>

                            @foreach($faculties as $faculty)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $faculty->faculty_name }}
                                    </td>

                                    <td>
                                        {{ $faculty->employee_id }}
                                    </td>

                                    <td>
                                        {{ $faculty->email }}
                                    </td>

                                    <td>
                                        {{ $faculty->phone }}
                                    </td>

                                    <td>
                                        {{ $faculty->department->name ?? 'N/A' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="alert alert-info mb-0">
                    No faculty records found.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection