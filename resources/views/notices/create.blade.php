@extends('layouts.app')

@section('title', 'Add Notice | Smart Attendance')

@section('content')

<div class="container py-4">

    <h2 class="fw-bold mb-4">
        <i class="fas fa-bullhorn text-primary me-2"></i>
        Add New Notice
    </h2>

    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <form action="{{ route('notices.store') }}" method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Notice Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        placeholder="Enter notice title"
                        value="{{ old('title') }}"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Notice Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="6"
                        placeholder="Enter notice details"
                        required
                    >{{ old('description') }}</textarea>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fas fa-paper-plane me-2"></i>
                    Publish Notice
                </button>

                <a
                    href="{{ route('notices.index') }}"
                    class="btn btn-secondary"
                >
                    Back
                </a>

            </form>

        </div>

    </div>

</div>

@endsection