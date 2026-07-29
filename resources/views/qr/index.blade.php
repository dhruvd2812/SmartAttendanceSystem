<!DOCTYPE html>
<html>
<head>

<title>QR Attendance Generator</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{

background:#eef2f7;

}

.card{

border:none;

border-radius:20px;

}

.qr{

width:300px;

height:300px;

}

</style>

</head>

<body>

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-8">

<div class="card shadow-lg">

<div class="card-body p-5">

<h2 class="text-center text-primary">

🎓 Smart Attendance QR Generator

</h2>

<hr>

<form method="POST" action="{{ route('qr.generate') }}">

@csrf

<div class="mb-3">

<label>Faculty</label>

<input type="text"

class="form-control"

name="faculty"

placeholder="Dr. Patel"

required>

</div>

<div class="mb-3">

<label>Department</label>

<input type="text"

class="form-control"

name="department"

placeholder="Computer"

required>

</div>

<div class="mb-3">

<label>Subject</label>

<input type="text"

class="form-control"

name="subject"

placeholder="DBMS"

required>

</div>

<button class="btn btn-primary w-100">

Generate QR

</button>

</form>

@if(isset($qr))

<hr>

<div class="text-center">

<img src="{{ $qr }}" class="qr">

</div>

<div class="alert alert-success mt-4">

<b>Attendance Session Created</b>

<pre>{{ $data }}</pre>

</div>

<div class="text-center">

<span class="badge bg-danger">

QR Valid for 2 Minutes

</span>

</div>

@endif

</div>

</div>

</div>

</div>

</div>

</body>

</html>