<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        @auth
            @if(Auth::user()->is_admin)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1>Notices</h1>
                    <a href="{{ route('admin.notices.create') }}" class="btn btn-success">Add Notice</a>
                    <a href="#" class="btn btn-primary" id="download-pdf">Download PDF</a>
                </div>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table class="table" id="notices-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notices as $notice)
                            <tr id="notice-{{ $notice->id }}">
                                <td>{{ $notice->id }}</td>
                                <td>{{ $notice->title }}</td>
                                <td>{{ $notice->content }}</td>
                                <td>
                                    <button class="btn btn-primary edit-btn" data-id="{{ $notice->id }}"
                                        data-title="{{ $notice->title }}" data-content="{{ $notice->content }}">Edit</button>
                                    <button class="btn btn-danger delete-btn" data-id="{{ $notice->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">
                    You do not have access to view this content.
                </div>
            @endif
        @endauth
        @guest
            <div class="alert alert-info">
                Please log in to view the notices.
            </div>
        @endguest
    </div>

    <!-- Edit Notice Modal -->
    <div class="modal fade" id="editNoticeModal" tabindex="-1" role="dialog" aria-labelledby="editNoticeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNoticeModalLabel">Edit Notice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editNoticeForm">
                    <div class="modal-body">
                        <input type="hidden" id="edit-id" name="id">
                        <div class="form-group">
                            <label for="edit-title">Title</label>
                            <input type="text" class="form-control" id="edit-title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-content">Content</label>
                            <textarea class="form-control" id="edit-content" name="content" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- jsPDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <!-- jsPDF AutoTable Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/editNotice.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const { jsPDF } = window.jspdf;

            $('#notices-table').DataTable();

            document.getElementById('download-pdf').addEventListener('click', function () {
                // Create a new jsPDF instance
                const doc = new jsPDF();

                // Get the table data without the Actions column
                const data = [];
                const headers = [];
                $('#notices-table thead tr th').each(function (index, element) {
                    if (index !== 3) { // Exclude Actions column
                        headers.push($(element).text());
                    }
                });

                data.push(headers);

                $('#notices-table tbody tr').each(function () {
                    const rowData = [];
                    $(this).find('td').each(function (index, element) {
                        if (index !== 3) { // Exclude Actions column
                            rowData.push($(element).text());
                        }
                    });
                    data.push(rowData);
                });

                // Add the data to the PDF
                doc.autoTable({
                    head: [headers],
                    body: data.slice(1),
                });

                // Save the PDF
                doc.save('notices.pdf');
            });
        });
    </script>
</body>

</html>