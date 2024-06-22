document.addEventListener('DOMContentLoaded', function () {
    // DataTable initialization
    $('#notices-table').DataTable();

    // Get the edit form element
    const editForm = document.getElementById('editNoticeForm');

    // Get references to the modal and its components
    const editModal = new bootstrap.Modal(document.getElementById('editNoticeModal'));

    // Add submit event listener to the edit form
    editForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission behavior

        // Get values from form inputs
        const noticeId = document.getElementById('edit-id').value;
        const editTitle = document.getElementById('edit-title').value;
        const editContent = document.getElementById('edit-content').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Show confirmation dialog using Swal (SweetAlert)
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to update this notice?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the update request
                fetch(`/admin/notices/${noticeId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        title: editTitle,
                        content: editContent
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // If update is successful, show success message
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            editModal.hide(); // Close the modal
                            // Update the table row with new data
                            const row = document.getElementById('notice-' + noticeId);
                            row.children[1].textContent = editTitle;
                            row.children[2].textContent = editContent;
                        });
                    } else {
                        // If update fails, show error message
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    // Catch any network or server errors
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    });

    // Add click event listeners to all elements with class '.edit-btn'
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default link behavior

            // Get data attributes from clicked edit button
            const noticeId = this.getAttribute('data-id');
            const noticeTitle = this.getAttribute('data-title');
            const noticeContent = this.getAttribute('data-content');

            // Set values in edit form fields
            document.getElementById('edit-id').value = noticeId;
            document.getElementById('edit-title').value = noticeTitle;
            document.getElementById('edit-content').value = noticeContent;

            // Show the modal
            editModal.show();
        });
    });

    // Add click event listeners to all elements with class '.delete-btn'
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default form submission behavior

            // Get notice id
            const noticeId = this.getAttribute('data-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Show confirmation dialog using Swal (SweetAlert)
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this notice?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the delete request
                    fetch(`/admin/notices/${noticeId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // If delete is successful, show success message
                            Swal.fire({
                                title: 'Deleted!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Remove the table row
                                const row = document.getElementById('notice-' + noticeId);
                                row.remove();
                            });
                        } else {
                            // If delete fails, show error message
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        // Catch any network or server errors
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });
    });
});
