/**
 * Students Management JavaScript
 * Handles cascading dropdowns for Institution -> Classes -> Sections -> Teachers
 */

$(document).ready(function() {
    // Initialize students functionality
    initStudentsForm();
});

function initStudentsForm() {
    // Load classes and teachers when institution is selected
    $('#institution_id').on('change', function() {
        var institutionId = $(this).val();
        loadClassesByInstitution(institutionId);
        loadTeachersByInstitution(institutionId);
        generateInstitutionCode(institutionId);
    });

    // Load sections when class is selected
    $('#class_id').on('change', function() {
        var classId = $(this).val();
        loadSectionsByClass(classId);
    });

    // Trigger institution change on page load if institution is pre-selected
    if ($('#institution_id').val()) {
        $('#institution_id').trigger('change');
    }
}

/**
 * Generate institution code based on selected institution
 */
function generateInstitutionCode(institutionId) {
    if (institutionId) {
        var code = 'INS' + institutionId.toString().padStart(3, '0');
        $('input[name="institution_code"]').val(code);
    } else {
        $('input[name="institution_code"]').val('');
    }
}

/**
 * Load classes for selected institution
 */
function loadClassesByInstitution(institutionId) {
    var classSelect = $('#class_id');
    var sectionSelect = $('#section_id');

    // Reset class and section selections
    classSelect.html('<option value="">-- None --</option>');
    sectionSelect.html('<option value="">-- None --</option>');

    if (institutionId) {
        // Show loading state
        classSelect.html('<option value="">Loading classes...</option>');

        // Fetch classes for selected institution via AJAX
        $.ajax({
            url: '/admin/students/classes/' + institutionId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                classSelect.html('<option value="">-- None --</option>');

                if (response.classes && response.classes.length > 0) {
                    response.classes.forEach(function(classItem) {
                        classSelect.append('<option value="' + classItem.id + '">' + classItem.name + '</option>');
                    });
                } else {
                    classSelect.append('<option value="">No classes available</option>');
                }
            },
            error: function() {
                classSelect.append('<option value="">Error loading classes</option>');
            }
        });
    }
}

/**
 * Load teachers for selected institution
 */
function loadTeachersByInstitution(institutionId) {
    var teacherSelect = $('#teacher_id');

    // Reset teacher selection
    teacherSelect.html('<option value="">-- None --</option>');

    if (institutionId) {
        // Show loading state
        teacherSelect.html('<option value="">Loading teachers...</option>');

        // Fetch teachers for selected institution via AJAX
        $.ajax({
            url: '/admin/students/teachers/' + institutionId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                teacherSelect.html('<option value="">-- None --</option>');

                if (response.teachers && response.teachers.length > 0) {
                    response.teachers.forEach(function(teacher) {
                        teacherSelect.append('<option value="' + teacher.id + '">' + teacher.first_name + ' ' + teacher.last_name + '</option>');
                    });
                } else {
                    teacherSelect.append('<option value="">No teachers available</option>');
                }
            },
            error: function() {
                teacherSelect.append('<option value="">Error loading teachers</option>');
            }
        });
    }
}

/**
 * Load sections for selected class
 */
function loadSectionsByClass(classId) {
    var sectionSelect = $('#section_id');

    // Reset section selection
    sectionSelect.html('<option value="">-- None --</option>');

    if (classId) {
        // Show loading state
        sectionSelect.html('<option value="">Loading sections...</option>');

        // Fetch sections for selected class via AJAX
        $.ajax({
            url: '/admin/students/sections/' + classId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                sectionSelect.html('<option value="">-- None --</option>');

                if (response.sections && response.sections.length > 0) {
                    response.sections.forEach(function(section) {
                        sectionSelect.append('<option value="' + section.id + '">' + section.name + '</option>');
                    });
                } else {
                    sectionSelect.append('<option value="">No sections available</option>');
                }
            },
            error: function() {
                sectionSelect.append('<option value="">Error loading sections</option>');
            }
        });
    }
}

/**
 * Reset all form fields
 */
function resetStudentForm() {
    $('#institution_id').val('');
    $('#class_id').html('<option value="">-- None --</option>');
    $('#section_id').html('<option value="">-- None --</option>');
    $('#teacher_id').html('<option value="">-- None --</option>');
    $('input[name="institution_code"]').val('');
}

/**
 * Pre-populate form with existing values (for edit mode)
 */
function populateStudentForm(studentData) {
    if (studentData.institution_id) {
        $('#institution_id').val(studentData.institution_id);
        // Trigger change to load classes and teachers
        $('#institution_id').trigger('change');

        // Wait for classes to load, then set class
        setTimeout(function() {
            if (studentData.class_id) {
                $('#class_id').val(studentData.class_id);
                // Trigger change to load sections
                $('#class_id').trigger('change');

                // Wait for sections to load, then set section
                setTimeout(function() {
                    if (studentData.section_id) {
                        $('#section_id').val(studentData.section_id);
                    }
                }, 500);
            }
        }, 500);
    }

    if (studentData.teacher_id) {
        // Wait for teachers to load, then set teacher
        setTimeout(function() {
            $('#teacher_id').val(studentData.teacher_id);
        }, 500);
    }
}
