<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Attendance System')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark text-white sidebar vh-100 p-3">
                <h3 class="text-center mb-4">📋 Attendance</h3>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white fw-semibold" href="{{ route('user_dashboard') }}">🏠 Dashboard</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a class="nav-link text-white fw-semibold" href="{{ route('attendance.index') }}">✅ Mark
                            Attendance</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white fw-semibold" href="{{ route('attendance.reports') }}">
                            📊 Reports
                        </a>
                    </li>


                    <li class="nav-item mt-3">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger w-100">🚪 Logout</button>
                        </form>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 px-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
