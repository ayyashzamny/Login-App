<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">


        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                @auth
                    <li class="nav-item">
                        <a class="navbar-brand" href="#">Welcome, {{ Auth::user()->name }}!</a>

                    </li>
                @else
                    <li class="nav-item">
                        <span class="nav-link">You are not logged in.</span>
                    </li>
                @endauth
            </ul>
            <div class="header-buttons">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                @else
                    @if(Auth::user()->is_admin)
                        <a class="btn btn-primary" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                        <a class="btn btn-primary" href="{{ route('admin.notices.index') }}">Manage Notices</a>
                    @endif
                    <a class="btn btn-secondary" href="{{ route('logout') }}" role="button"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endguest
            </div>
        </div>
    </nav>


    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Notices</h5>
                        <p class="card-text">{{ $totalNotices }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6">
                <canvas id="progressChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Progress Chart
            var progressCtx = document.getElementById('progressChart').getContext('2d');
            var progressChart = new Chart(progressCtx, {
                type: 'bar',
                data: {
                    labels: ['Users', 'Notices'],
                    datasets: [{
                        label: 'Total',
                        data: [{{ $totalUsers }}, {{ $totalNotices }}],
                        backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)'],
                        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Pie Chart
            var pieCtx = document.getElementById('pieChart').getContext('2d');
            var pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['Users', 'Notices'],
                    datasets: [{
                        data: [{{ $totalUsers }}, {{ $totalNotices }}],
                        backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)'],
                        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
</body>

</html>