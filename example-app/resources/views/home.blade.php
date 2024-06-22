<!-- resources/views/home.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .notice {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .navbar-nav {
            flex-direction: row;
        }

        .nav-link {
            padding-right: .5rem !important;
            padding-left: .5rem !important;
        }

        .header-buttons {
            margin-left: auto;
        }

        .header-buttons .btn {
            margin-left: 10px;
        }
    </style>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                @auth
                    @if(!Auth::user()->is_admin)
                        <h3 class="mt-4">Notices</h3>
                        <div id="notices">
                            <!-- Loop through each notice and display -->
                            @foreach($notices as $notice)
                                <div class="notice">
                                    <h5>{{ $notice->title }}</h5>
                                    <p>{{ $notice->content }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="alert alert-info" role="alert">
                        Please log in to view the notices.
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>