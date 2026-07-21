<!DOCTYPE html>
<html>
<head>
    <title>Add Department</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header bg-success text-white">

            <h3>Add Department</h3>

        </div>

        <div class="card-body">

            <form action="{{ route('departments.store') }}" method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">Department Name</label>

                    <input
                        type="text"
                        name="department_name"
                        class="form-control @error('department_name') is-invalid @enderror"
                        value="{{ old('department_name') }}" required>
                    @error('department_name')<div class="invalid-feedback">{{ $message }}</div>@enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">Department Code</label>

                    <input
                        type="text"
                        name="department_code"
                        class="form-control @error('department_code') is-invalid @enderror"
                        value="{{ old('department_code') }}" required>
                    @error('department_code')<div class="invalid-feedback">{{ $message }}</div>@enderror

                </div>

                <button class="btn btn-success">

                    Save Department

                </button>

                <a href="{{ route('departments.index') }}" class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>
