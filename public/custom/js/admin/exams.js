$(document).ready(function() {
    $('#institution').on('change', function() {
        var institutionId = $(this).val();
        $('#class').html('<option value="">Select Class</option>');
        $('#section').html('<option value="">Select Section</option>');
        if (institutionId) {
            $.ajax({
                url: '/admin/exam-management/exams/get-classes-sections/' + institutionId,
                type: 'GET',
                success: function(response) {
                    if (response.classes && response.classes.length > 0) {
                        $.each(response.classes, function(index, cls) {
                            $('#class').append('<option value="' + cls.id + '">' + cls.name + '</option>');
                        });
                    }
                    if (response.sections && response.sections.length > 0) {
                        $.each(response.sections, function(index, section) {
                            $('#section').append('<option value="' + section.id + '">' + section.name + '</option>');
                        });
                    }
                }
            });
        }
    });
});
