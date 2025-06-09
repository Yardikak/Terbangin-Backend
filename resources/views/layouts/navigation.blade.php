<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backend Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    
    <style>
        /* Custom Navbar Styles */
        .navbar {
            background-color: #007bff; /* Blue color */
        }

        .navbar-nav .nav-link {
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            color: #f8f9fa !important; /* Light color on hover */
            background-color: #0056b3; /* Darker blue on hover */
        }

        .navbar-nav .nav-item.active .nav-link {
            color: #ffd700 !important; /* Gold color for active nav link */
            background-color: #0056b3; /* Darker blue on active state */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand text-white" href="#">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <!-- Debugging Route Name -->
                <li class="nav-item">
                    <span class="nav-link text-white">{{ Route::currentRouteName() }}</span>
                </li>

                <!-- Navbar items with active route checking -->
                <li class="nav-item {{ Route::currentRouteName() == 'flights.index' ? 'active' : '' }}">
                    <a class="nav-link text-white" href="{{ route('flights.index') }}">Flights</a>
                </li>
                <li class="nav-item {{ Route::currentRouteName() == 'history.index' ? 'active' : '' }}">
                    <a class="nav-link text-white" href="{{ route('history.index') }}">History</a>
                </li>
                <li class="nav-item {{ Route::currentRouteName() == 'payments.index' ? 'active' : '' }}">
                    <a class="nav-link text-white" href="{{ route('payments.index') }}">Payments</a>
                </li>
                <li class="nav-item {{ Route::currentRouteName() == 'promo.index' ? 'active' : '' }}">
                    <a class="nav-link text-white" href="{{ route('promo.index') }}">Promo</a>
                </li>
                <li class="nav-item {{ Route::currentRouteName() == 'tickets.index' ? 'active' : '' }}">
                    <a class="nav-link text-white" href="{{ route('tickets.index') }}">Tickets</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables Script -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
</body>
</html>
