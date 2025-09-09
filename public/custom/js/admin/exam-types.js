$(document).ready(function () {
    // Handle Add Exam Type
    $('#add-exam-type').on('click', function (e) {
        e.preventDefault();
        let form = $('#exam-type-form');
        let formData = form.serialize();

        $.ajax({
            url: '/admin/exam-management/exam-type/store', // Update this to your actual route
            type: 'POST',
            data: formData,
            success: function (response) {
                // Show success message, reload table, reset form, etc.
                location.reload();
            },
            error: function (xhr) {
                // Handle validation errors
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    // Handle Update Exam Type
    $('#update-exam-type').on('click', function (e) {
        e.preventDefault();
        let form = $('#exam-type-form');
        let id = $('#exam-type-id').val();
        let formData = form.serialize();

        $.ajax({
            url: '/admin/exam-types/' + id, // Update this to your actual route
            type: 'PUT',
            data: formData,
            success: function (response) {
                location.reload();
            },
            error: function (xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    // Optional: Handle Edit and Cancel buttons here
});
