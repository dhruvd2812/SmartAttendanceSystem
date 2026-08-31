@extends('layouts.faculty')

@section('title', 'Edit Subject | Smart Attendance')
@section('page-title', 'Edit Subject')

@section('content')
    <div class="row justify-content-center"><div class="col-lg-7 col-xl-6"><div class="card shadow-sm border-0"><div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4"><div><h1 class="h3 mb-1">Edit Subject</h1><p class="text-muted mb-0">Update subject details.</p></div><a href="{{ route('faculty.subjects.index') }}" class="btn btn-outline-secondary">Cancel</a></div>
        <form method="POST" action="{{ route('faculty.subjects.update', $subject) }}">@csrf @method('PUT') @include('faculty.subjects._form')<button type="submit" class="btn btn-primary">Save Changes</button></form>
    </div></div></div></div>
@endsection
