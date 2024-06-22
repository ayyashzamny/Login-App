document.addEventListener('DOMContentLoaded', function () {
    // Get the add notice form element
    const addForm = document.getElementById('addNoticeForm');

    // Add submit event listener to the add form
    addForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission behavior

        // Get values from form inputs
        const title = document.getElementById('title').value;
        const content = document.getElementById('content').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Show confirmation dialog using Swal (SweetAlert)
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to add this notice?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Add',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the add request
                fetch('/admin/notices', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        title: title,
                        content: content
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
                        // If add is successful, show success message
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Reset the form
                            addForm.reset();
                            // Redirect to manage notices page
                            window.location.href = "/admin/notices";
                        });
                    } else {
                        // If add fails, show error message
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
