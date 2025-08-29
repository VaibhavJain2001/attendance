<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Attendance Panel')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-3" style="width: 250px; height: 100vh;">
            <h4 class="fw-bold mb-4">Admin Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.dashboard')}}" class="nav-link text-white">🏠 Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('attendance.index') }}" class="nav-link text-white fw-bold">📋 Attendance Logs</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('employees.list') }}" class="nav-link text-white">👥 Employees</a>
                </li>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger text-decoration-none p-0">
                        🚪 Logout
                    </button>
                </form>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
