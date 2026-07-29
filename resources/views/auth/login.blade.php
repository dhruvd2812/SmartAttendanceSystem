<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Smart Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{min-height:100vh;background:linear-gradient(135deg,#0d6efd,#5b32cf)}.auth-card{max-width:430px;border-radius:20px}</style>
</head>
<body class="d-flex align-items-center py-5">
    <main class="container"><div class="card auth-card border-0 shadow-lg mx-auto"><div class="card-body p-4 p-md-5">
        <div class="text-center mb-4"><div class="fs-2">🎓</div><h1 class="h3 fw-bold mb-1">Welcome back</h1><p class="text-muted mb-0">Smart Attendance System</p></div>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form method="POST" action="{{ route('login.store') }}">@csrf
            <div class="mb-3"><label class="form-label">Email address</label><input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="form-check mb-4"><input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"><label class="form-check-label" for="remember">Remember me</label></div>
            <button class="btn btn-primary w-100 py-2">Login</button>
        </form>
        <p class="text-center text-muted small mt-4 mb-0">New administrator? <a href="{{ route('register') }}">Create an account</a></p>
    </div></div></main>
</body></html>
