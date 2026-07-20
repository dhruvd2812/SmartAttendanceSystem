<!DOCTYPE html>
<html>
<head>

<title>Students</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">


<h2>Student List</h2>


<a href="{{route('students.create')}}" 
class="btn btn-primary mb-3">

Add Student

</a>


@if(session('success'))

<div class="alert alert-success">

{{session('success')}}

</div>

@endif


<table class="table table-bordered">


<tr>

<th>ID</th>
<th>Enrollment</th>
<th>Name</th>
<th>Department</th>
<th>Semester</th>
<th>Status</th>
<th>Action</th>

</tr>


@foreach($students as $student)


<tr>

<td>{{$student->id}}</td>

<td>{{$student->enrollment_no}}</td>

<td>
{{$student->first_name}}
{{$student->last_name}}
</td>


<td>

{{$student->department->department_name ?? 'N/A'}}

</td>


<td>

{{$student->semester}}

</td>


<td>


<a href="{{route('students.edit',$student->id)}}"
class="btn btn-warning btn-sm">

Edit

</a>



<form action="{{route('students.destroy',$student->id)}}"
method="POST"
style="display:inline;">


@csrf

@method('DELETE')


<button type="submit"
class="btn btn-danger btn-sm"
onclick="return confirm('Are you sure?')">

Delete

</button>


</form>



</td>


</tr>


@endforeach


</table>


</div>

</body>
</html>