$(document).ready(function () {
    // Auto-load classes and teachers for pre-selected institution
    const preSelectedInstitutionId = $('select[name="institution_id"]').val();
    if (preSelectedInstitutionId) {
        loadClassesAndTeachers(preSelectedInstitutionId);
    }

    // Fetch classes and teachers when institution changes
    $('select[name="institution_id"]').on('change', function () {
        const institutionId = $(this).val();
        if (institutionId) {
            loadClassesAndTeachers(institutionId);
        }
    });

    // Function to load classes and teachers for an institution
    function loadClassesAndTeachers(institutionId) {

        // Fetch classes
        $.ajax({
            url: '/institution/academic/classes-by-institution/' + institutionId,
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
            url: '/institution/academic/teachers-by-institution/' + institutionId,
            type: 'GET',
            success: function (data) {
                let teacherOptions = '<option value="">Select Teacher</option>';
                data.teachers.forEach(function (teacher) {
                    teacherOptions += `<option value="${teacher.id}">${teacher.first_name} ${teacher.last_name}</option>`;
                });

                $('select[name="teacher_id"]').html(teacherOptions);
            }
        });
    }

    // Fetch sections when class changes
    $('select[name="class_id"]').on('change', function () {
        const classId = $(this).val();
        $.ajax({
            url: '/institution/academic/sections-by-class/' + classId,
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
            url: '/institution/academic/assign-class-teacher/',
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
