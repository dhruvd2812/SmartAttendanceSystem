<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Dashboard | Smart Attendance</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }
        .hero {
            background: linear-gradient(135deg, #0d6efd, #5b32cf);
        }
        .metric {
            border-radius: 16px;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">Smart Attendance</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/attendance">Attendance</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attendance Dashboard</li>
            </ol>
        </nav>

        <!-- Page Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">Attendance Dashboard</h1>
        </div>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Attendance Sessions -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card metric border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="display-6 fw-bold text-primary mb-1">10</div>
                        <p class="text-muted mb-0">Total Attendance Sessions</p>
                    </div>
                </div>
            </div>

            <!-- Present Students -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card metric border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="display-6 fw-bold text-success mb-1">150</div>
                        <p class="text-muted mb-0">Present Students</p>
                    </div>
                </div>
            </div>

            <!-- Absent Students -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card metric border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="display-6 fw-bold text-danger mb-1">20</div>
                        <p class="text-muted mb-0">Absent Students</p>
                    </div>
                </div>
            </div>

            <!-- Attendance Percentage -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card metric border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="display-6 fw-bold text-info mb-1">88%</div>
                        <p class="text-muted mb-0">Attendance Percentage</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
