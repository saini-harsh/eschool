$(document).ready(function () {
    // Fetch classes and teachers when institution changes
    $('select[name="institution_id"]').on('change', function () {
        const institutionId = $(this).val();

        // Fetch classes
        $.ajax({
            url: '/admin/academic/classes-by-institution/' + institutionId,
            type: 'GET',
            success: function (data) {
                let classOptions = '<option value="">Select Class</option>';
                data.classes.forEach(function (cls) {
                    classOptions += `<option value="${cls.id}">${cls.name}</option>`;
                });
                $('select[name="class_id"]').html(classOptions);
            }
        });

        // Fetch teachers
        $.ajax({
            url: '/admin/academic/teachers-by-institution/' + institutionId,
            type: 'GET',
            success: function (data) {
                let teacherOptions = '<option value="">Select Teacher</option>';
                data.teachers.forEach(function (teacher) {
                    teacherOptions += `<option value="${teacher.id}">${teacher.first_name} ${teacher.last_name}</option>`;
                });

                $('select[name="teacher_id"]').html(teacherOptions);
            }
        });
    });

    // Fetch sections when class changes
    $('select[name="class_id"]').on('change', function () {
        const classId = $(this).val();
        $.ajax({
            url: '/admin/academic/sections-by-class/' + classId,
            type: 'GET',
            success: function (data) {
                let sectionOptions = '<option value="">Select Section</option>';
                data.sections.forEach(function (section) {
                    sectionOptions += `<option value="${section.id}">${section.name}</option>`;
                });
                $('select[name="section_id"]').html(sectionOptions);
            }
        });
    });

    // AJAX submit for assign class teacher form
    $('#assign-teacher').on('click', function (e) {
        e.preventDefault();

        let form = $('#assign-teacher-form');
        let formData = form.serialize();

        $.ajax({
            url: '/admin/academic/assign-class-teacher/',
            type: 'POST',
            data: formData,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function (response) {
                toastr.success('Class teacher assigned successfully!');
                form[0].reset();
                // Optionally, reset selects for class, section, teacher
                $('select[name="class_id"], select[name="section_id"], select[name="teacher_id"]').html('<option value="">Select</option>');
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            }
        });
    });
});
