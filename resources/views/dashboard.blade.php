<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Smart Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#f5f7fb}.hero{background:linear-gradient(135deg,#0d6efd,#5b32cf)}.metric{border-radius:16px}</style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm"><div class="container"><a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">Smart Attendance</a><button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse" id="nav"><ul class="navbar-nav ms-auto align-items-lg-center"><li class="nav-item"><a class="nav-link" href="{{ route('students.index') }}">Students</a></li><li class="nav-item"><a class="nav-link" href="{{ route('departments.index') }}">Departments</a></li><li class="nav-item ms-lg-3"><form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline-light btn-sm">Logout</button></form></li></ul></div></div></nav>
    <main class="container py-5">
        <section class="hero rounded-4 text-white p-4 p-md-5 mb-4"><p class="mb-2 text-white-50">Administrator dashboard</p><h1 class="h2 mb-1">Hello, {{ auth()->user()->name }}</h1><p class="mb-0">Manage students and departments from one place.</p></section>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="row g-4 mb-4"><div class="col-md-6"><div class="card metric border-0 shadow-sm"><div class="card-body p-4"><p class="text-muted mb-1">Total Students</p><div class="display-6 fw-bold">{{ $studentCount }}</div><a href="{{ route('students.index') }}" class="small">View students →</a></div></div></div><div class="col-md-6"><div class="card metric border-0 shadow-sm"><div class="card-body p-4"><p class="text-muted mb-1">Departments</p><div class="display-6 fw-bold">{{ $departmentCount }}</div><a href="{{ route('departments.index') }}" class="small">Manage departments →</a></div></div></div></div>
        <div class="d-flex gap-2 mb-4"><a href="{{ route('students.create') }}" class="btn btn-primary">+ Add Student</a><a href="{{ route('departments.create') }}" class="btn btn-outline-primary">+ Add Department</a></div>
        <section class="card border-0 shadow-sm rounded-4"><div class="card-body p-4"><h2 class="h5 mb-3">Recently Added Students</h2><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Name</th><th>Enrollment No.</th><th>Department</th></tr></thead><tbody>@forelse($recentStudents as $student)<tr><td>{{ $student->first_name }} {{ $student->last_name }}</td><td>{{ $student->enrollment_no }}</td><td>{{ $student->department?->department_name ?? 'Not assigned' }}</td></tr>@empty<tr><td colspan="3" class="text-muted text-center py-4">No students added yet.</td></tr>@endforelse</tbody></table></div></div></section>
    </main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
