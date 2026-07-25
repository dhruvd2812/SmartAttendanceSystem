<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments | Smart Attendance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .page-header {
            background: linear-gradient(135deg, #0d6efd, #563dce);
        }

        .table > :not(caption) > * > * {
            padding: 1rem;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-white shadow-sm border-bottom">
        <div class="container">
            <span class="navbar-brand text-primary fw-bold">
                Smart Attendance System
            </span>
        </div>
    </nav>

    <main class="container py-5">

        <!-- Header -->
        <section class="page-header text-white rounded-4 shadow-sm p-4 p-md-5 mb-4
                        d-flex flex-column flex-md-row align-items-md-center
                        justify-content-between gap-3">

            <div>
                <p class="text-white-50 mb-1">
                    Administration
                </p>

                <h1 class="h2 mb-0">
                    Department Management
                </h1>
            </div>

            <a href="{{ route('departments.create') }}"
               class="btn btn-light text-primary fw-semibold">
                + Add Department
            </a>

        </section>


        <!-- Success Message -->
        @if (session('success'))

            <div class="alert alert-success alert-dismissible fade show shadow-sm"
                 role="alert">

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        <!-- Department Card -->
        <section class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">

                <!-- Title + Search -->
                <div class="d-flex flex-column flex-md-row
                            justify-content-between
                            align-items-md-center
                            gap-3 mb-4">

                    <div>

                        <h2 class="h5 mb-1">
                            All Departments
                        </h2>

                        <!-- FIXED: count() instead of total() -->
                        <p class="text-muted mb-0 small">

                            {{ $departments->count() }}

                            department{{ $departments->count() === 1 ? '' : 's' }}

                            found

                        </p>

                    </div>


                    <!-- Search -->
                    <form method="GET"
                          action="{{ route('departments.index') }}"
                          class="d-flex gap-2"
                          role="search">

                        <input type="search"
                               name="search"
                               value="{{ $search ?? '' }}"
                               class="form-control"
                               placeholder="Search name or code"
                               aria-label="Search departments">

                        <button class="btn btn-outline-primary"
                                type="submit">

                            Search

                        </button>


                        @if (!empty($search))

                            <a href="{{ route('departments.index') }}"
                               class="btn btn-outline-secondary">

                                Clear

                            </a>

                        @endif

                    </form>

                </div>


                <!-- Department Table -->
                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th scope="col">
                                    #
                                </th>

                                <th scope="col">
                                    Department Name
                                </th>

                                <th scope="col">
                                    Code
                                </th>

                                <th scope="col" class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($departments as $department)

                                <tr>

                                    <td class="text-muted">
                                        {{ $department->id }}
                                    </td>


                                    <td class="fw-semibold">
                                        {{ $department->department_name }}
                                    </td>


                                    <td>

                                        <span class="badge text-bg-primary">
                                            {{ $department->department_code }}
                                        </span>

                                    </td>


                                    <td class="text-end text-nowrap">

                                        <!-- Edit -->
                                        <a href="{{ route('departments.edit', $department) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            Edit

                                        </a>


                                        <!-- Delete -->
                                        <form action="{{ route('departments.destroy', $department) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete this department? This cannot be undone.');">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">

                                                Delete

                                            </button>

                                        </form>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="4"
                                        class="text-center py-5 text-muted">

                                        No departments found.

                                        <a href="{{ route('departments.create') }}">
                                            Add the first department
                                        </a>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
    </script>

</body>
</html>