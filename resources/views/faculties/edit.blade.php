@extends('layouts.app')

@section('content')

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-warning">

<h4>Edit Faculty</h4>

</div>

<div class="card-body">

<form action="{{ route('faculties.update',$faculty->id) }}" method="POST">

@csrf
@method('PUT')

<div class="mb-3">
<label>Faculty Name</label>

<input type="text"
name="faculty_name"
class="form-control"
value="{{ $faculty->faculty_name }}">
</div>

<div class="mb-3">
<label>Employee ID</label>

<input type="text"
name="employee_id"
class="form-control"
value="{{ $faculty->employee_id }}">
</div>

<div class="mb-3">
<label>Email</label>

<input type="email"
name="email"
class="form-control"
value="{{ $faculty->email }}">
</div>

<div class="mb-3">
<label>Phone</label>

<input type="text"
name="phone"
class="form-control"
value="{{ $faculty->phone }}">
</div>

<div class="mb-3">

<label>Department</label>

<select name="department_id" class="form-select">

@foreach($departments as $department)

<option value="{{ $department->id }}"
{{ $faculty->department_id==$department->id ? 'selected':'' }}>

{{ $department->department_name }}

</option>

@endforeach

</select>

</div>

<button class="btn btn-primary">
Update Faculty
</button>

<a href="{{ route('faculties.index') }}" class="btn btn-secondary">
Back
</a>

</form>

</div>

</div>

</div>

@endsection