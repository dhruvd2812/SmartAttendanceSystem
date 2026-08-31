@extends('layouts.app')

@section('title', 'My Subjects | Smart Attendance')

@section('content')

<div class="container py-4">

    <div class="mb-4">

        <h2 class="fw-bold mb-1">
            <i class="fas fa-book text-primary me-2"></i>
            My Subjects
        </h2>

        <p class="text-muted">
            Subjects assigned to you
        </p>

    </div>


    @if($subjects->count() > 0)

        <div class="row">

            @foreach($subjects as $subject)

                <div class="col-md-6 col-lg-4 mb-4">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <div class="mb-3">

                                <div
                                    class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:55px;height:55px;"
                                >
                                    <i class="fas fa-book"></i>
                                </div>

                            </div>


                            <h5 class="fw-bold">
                                {{ $subject->name }}
                            </h5>


                            @if($subject->code)

                                <span class="badge bg-secondary mb-3">
                                    {{ $subject->code }}
                                </span>

                            @endif


                            <p class="text-muted mb-2">

                                <i class="fas fa-layer-group me-2"></i>

                                Semester:
                                {{ $subject->semester ?? 'N/A' }}

                            </p>


                            <p class="text-muted mb-2">

                                <i class="fas fa-user-tie me-2"></i>

                                Faculty:

                                {{ $subject->faculty->display_name ?? 'Not Assigned' }}

                            </p>


                            @if($subject->description)

                                <p class="text-muted small mt-3">
                                    {{ $subject->description }}
                                </p>

                            @endif

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center py-5">

                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>

                <h4 class="fw-bold">
                    No Subjects Found
                </h4>

                <p class="text-muted">
                    No subjects are currently assigned to you.
                </p>

            </div>

        </div>

    @endif

</div>

@endsection