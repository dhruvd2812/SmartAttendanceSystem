<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5" style="max-width: 720px;">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3"><h4 class="mb-0">Edit Department</h4></div>
            <div class="card-body p-4">
                <form action="{{ route('departments.update', $department) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Department Name</label>
                        <input type="text" name="department_name" value="{{ old('department_name', $department->department_name) }}" class="form-control @error('department_name') is-invalid @enderror" required>
                        @error('department_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Department Code</label>
                        <input type="text" name="department_code" value="{{ old('department_code', $department->department_code) }}" class="form-control @error('department_code') is-invalid @enderror" required>
                        @error('department_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button class="btn btn-primary">Update Department</button>
                    <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
