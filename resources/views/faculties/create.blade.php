@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Add Faculty</h4>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p class="text-muted">Create a faculty login. The faculty member can change this password later from their profile.</p>

            <form action="{{ route('faculties.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Faculty Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="email">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required autocomplete="new-password">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                </div>

                <button class="btn btn-success">
                    Save Faculty
                </button>

                <a href="{{ route('faculties.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>
@endsection
