<!DOCTYPE html>
<html>

<head>

<title>Edit Student</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body>


<div class="container mt-5">


<h2>Edit Student</h2>


<form action="{{ route('students.update',$student->id) }}" method="POST">


@csrf

@method('PUT')



<div class="mb-3">

<label>Enrollment No</label>

<input type="text" 
class="form-control"
value="{{ $student->enrollment_no }}"
readonly>

</div>



<div class="mb-3">

<label>First Name</label>

<input type="text"
name="first_name"
class="form-control"
value="{{ $student->first_name }}">

</div>



<div class="mb-3">

<label>Last Name</label>

<input type="text"
name="last_name"
class="form-control"
value="{{ $student->last_name }}">

</div>




<div class="mb-3">

<label>Gender</label>

<select name="gender" class="form-control">


<option value="Male"
@if($student->gender=="Male") selected @endif>
Male
</option>


<option value="Female"
@if($student->gender=="Female") selected @endif>
Female
</option>


</select>

</div>




<div class="mb-3">

<label>Department</label>


<select name="department_id" class="form-control">


@foreach($departments as $department)


<option value="{{ $department->id }}"

@if($student->department_id == $department->id)

selected

@endif

>

{{ $department->department_name }}

</option>


@endforeach


</select>


</div>




<div class="mb-3">

<label>Semester</label>


<input type="number"
name="semester"
class="form-control"
value="{{ $student->semester }}">


</div>





<div class="mb-3">

<label>Academic Year</label>


<input type="text"
name="academic_year"
class="form-control"
value="{{ $student->academic_year }}">


</div>





<button class="btn btn-success">

Update Student

</button>



<a href="{{route('students.index')}}"
class="btn btn-secondary">

Back

</a>



</form>



</div>


</body>

</html>