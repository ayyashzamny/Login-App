<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Notice</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Your Application</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                @auth
                    <li class="nav-item">
                        <span class="nav-link">Welcome, {{ Auth::user()->name }}!</span>
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

    @auth
        @if(Auth::user()->is_admin)
            <div class="container mt-5">
                <div class="card">
                    <div class="card-header">{{ __('Add Notice') }}</div>
                    <div class="card-body">
                        <form id="addNoticeForm" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror"
                                    name="title" value="{{ old('title') }}" required autofocus>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea id="content" class="form-control @error('content') is-invalid @enderror"
                                    name="content" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Add Notice</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @else
        <script>
            window.location.href = "{{ url('/') }}";
        </script>
    @endauth

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/addNotice.js') }}"></script>
</body>

</html>