@extends('layouts.app')

@section('title', 'Add Department | Smart Attendance')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <section class="card app-card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-start mb-4 gap-3 flex-column flex-md-row">
                        <div>
                            <h1 class="h4 mb-1">Add Department</h1>
                            <p class="text-muted small mb-0">Create a new department for your attendance system.</p>
                        </div>
                        <a href="{{ route('departments.index') }}" class="btn btn-soft-primary">Back to list</a>
                    </div>

                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Department Name</label>
                            <input type="text" name="department_name" class="form-control @error('department_name') is-invalid @enderror" value="{{ old('department_name') }}" required>
                            @error('department_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department Code</label>
                            <input type="text" name="department_code" class="form-control @error('department_code') is-invalid @enderror" value="{{ old('department_code') }}" required>
                            @error('department_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-primary">Save Department</button>
                            <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
