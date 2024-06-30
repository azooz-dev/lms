$(document).on('click', '#delete', function(e) {
    e.preventDefault();
    var form = $(this).closest('form'); // Assuming the delete button is inside a form
    var url = form.attr('action'); // Get the form's action URL

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // If the user confirmed the action, submit the form or send an AJAX request
            form.submit(); // This submits the form, which should trigger the delete action on the server
        }
    });
});


$(document).on('click', '#confirm', function(e) {
    e.preventDefault();
    var form = $(this).closest('form'); // Assuming the delete button is inside a form
    var url = form.attr('action'); // Get the form's action URL

    Swal.fire({
        title: 'Are you sure?',
        text: "Confirm this order ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, confirm it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // If the user confirmed the action, submit the form or send an AJAX request
            form.submit(); // This submits the form, which should trigger the delete action on the server
        }
    });
});
