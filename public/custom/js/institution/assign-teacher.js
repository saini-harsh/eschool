$(document).ready(function () {
    // Initialize cascading dropdowns
    initInstitutionClassTeacherDropdowns();

    // Auto-load classes and teachers for pre-selected institution
    const preSelectedInstitutionId = $('select[name="institution_id"]').val();
    if (preSelectedInstitutionId) {
        loadClassesAndTeachers(preSelectedInstitutionId);
    }

    // Function to show toast notifications
    function showToast(type, message) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            // Fallback to alert if toastr is not available
            alert(message);
        }
    }

    // Function to reset form to add mode
    function resetForm() {
        const form = $("#assign-teacher-form")[0];
        form.reset();

        // Re-check the status checkbox since reset() unchecks it
        $("#subject-status").prop("checked", true);

        // Clear hidden field
        $("#assign-teacher-id").val("");

        // Clear edit data
        window.currentEditData = null;

        // Switch buttons back to add mode
        $("#assign-teacher").removeClass("d-none");
        $("#update-assign-teacher").addClass("d-none");
        $("#cancel-edit").addClass("d-none");

        // Reset dropdowns
        resetDependentDropdowns(['class_id', 'section_id', 'teacher_id']);
    }

    // Function to reset dependent dropdowns
    function resetDependentDropdowns(dropdowns) {
        dropdowns.forEach(function(dropdownId) {
            const dropdown = $(`#${dropdownId}`);
            dropdown.html('<option value="">Select</option>').prop('disabled', true);
        });
    }

    // Function to refresh assignments list
    function refreshAssignmentsList() {
        $.ajax({
            url: '/institution/academic/assign-class-teacher/list',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    updateAssignmentsTable(response.data);
                }
            },
            error: function() {
                showToast('error', 'Error refreshing assignments list');
            }
        });
    }

    // Function to update assignments table
    function updateAssignmentsTable(assignments) {
        const tbody = $('.datatable tbody');
        tbody.empty();

        assignments.forEach(function(assignment) {
            const statusText = assignment.status ? 'Active' : 'Inactive';
            const statusClass = assignment.status ? 'text-success' : 'text-danger';
            
            const row = `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="ms-2">
                                <h6 class="fs-14 mb-0">${assignment.teacher.first_name} ${assignment.teacher.last_name}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="ms-2">
                                <h6 class="fs-14 mb-0">${assignment.institution.name}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="ms-2">
                                <h6 class="fs-14 mb-0">${assignment.class.name}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="ms-2">
                                <h6 class="fs-14 mb-0">${assignment.section.name}</h6>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <select class="select status-toggle" data-assignment-id="${assignment.id}">
                                <option value="1" ${assignment.status ? 'selected' : ''}>Active</option>
                                <option value="0" ${!assignment.status ? 'selected' : ''}>Inactive</option>
                            </select>
                        </div>
                    </td>
                    <td>
                        <div class="d-inline-flex align-items-center">
                            <a href="javascript:void(0);" data-assignment-id="${assignment.id}"
                                class="btn btn-icon btn-sm btn-outline-white border-0 edit-assign-teacher">
                                <i class="ti ti-edit"></i>
                            </a>
                            <a href="javascript:void(0);" data-assignment-id="${assignment.id}"
                                class="btn btn-icon btn-sm btn-outline-white border-0 delete-assign-teacher">
                                <i class="ti ti-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Initialize cascading dropdowns
    function initInstitutionClassTeacherDropdowns() {
        // Institution change handler
        $('#institution_id').on('change.assignTeacher', function() {
            const institutionId = $(this).val();
            if (institutionId) {
                loadClassesAndTeachers(institutionId);
            } else {
                resetDependentDropdowns(['class_id', 'section_id', 'teacher_id']);
            }
        });

        // Class change handler
        $('#class_id').on('change.assignTeacher', function() {
            const classId = $(this).val();
            if (classId) {
                loadSectionsByClass(classId);
            } else {
                resetDependentDropdowns(['section_id']);
            }
        });
    }

    // Function to load classes and teachers for an institution
    function loadClassesAndTeachers(institutionId) {
        // Fetch classes
        $.ajax({
            url: `/institution/academic/assign-class-teacher/classes/${institutionId}`,
            type: 'GET',
            success: function (data) {
                let classOptions = '<option value="">Select Class</option>';
                data.classes.forEach(function (cls) {
                    classOptions += `<option value="${cls.id}">${cls.name}</option>`;
                });
                $('#class_id').html(classOptions).prop('disabled', false);
                
                // If we're in edit mode, set the class value and trigger change
                if (window.currentEditData && window.currentEditData.class_id) {
                    $('#class_id').val(window.currentEditData.class_id);
                    $('#class_id').trigger('change.assignTeacher');
                }
            },
            error: function() {
                $('#class_id').html('<option value="">Error loading classes</option>').prop('disabled', false);
                showToast('error', 'Failed to load classes');
            }
        });

        // Fetch teachers
        $.ajax({
            url: `/institution/academic/assign-class-teacher/teachers/${institutionId}`,
            type: 'GET',
            success: function (data) {
                let teacherOptions = '<option value="">Select Teacher</option>';
                data.teachers.forEach(function (teacher) {
                    teacherOptions += `<option value="${teacher.id}">${teacher.first_name} ${teacher.last_name}</option>`;
                });
                $('#teacher_id').html(teacherOptions).prop('disabled', false);
                
                // If we're in edit mode, set the teacher value
                if (window.currentEditData && window.currentEditData.teacher_id) {
                    $('#teacher_id').val(window.currentEditData.teacher_id);
                }
            },
            error: function() {
                $('#teacher_id').html('<option value="">Error loading teachers</option>').prop('disabled', false);
                showToast('error', 'Failed to load teachers');
            }
        });
    }

    // Load sections by class via AJAX
    function loadSectionsByClass(classId) {
        const sectionSelect = $('#section_id');
        sectionSelect.prop('disabled', true).html('<option value="">Loading sections...</option>');
        
        $.ajax({
            url: `/institution/academic/assign-class-teacher/sections/${classId}`,
            type: 'GET',
            success: function (data) {
                let sectionOptions = '<option value="">Select Section</option>';
                data.sections.forEach(function (section) {
                    sectionOptions += `<option value="${section.id}">${section.name}</option>`;
                });
                sectionSelect.html(sectionOptions).prop('disabled', false);
                
                // If we're in edit mode, set the section value
                if (window.currentEditData && window.currentEditData.section_id) {
                    sectionSelect.val(window.currentEditData.section_id);
                }
            },
            error: function() {
                sectionSelect.html('<option value="">Error loading sections</option>').prop('disabled', false);
                showToast('error', 'Failed to load sections');
            }
        });
    }

    // Store new assignment
    $('#assign-teacher').on('click', function (e) {
        e.preventDefault();

        const form = $('#assign-teacher-form');
        const formData = new FormData(form[0]);

        $.ajax({
            url: '/institution/academic/assign-class-teacher/',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function (response) {
                if (response.success) {
                    showToast('success', response.message);
                    form[0].reset();
                    $("#subject-status").prop("checked", true);
                    resetDependentDropdowns(['class_id', 'section_id', 'teacher_id']);
                    refreshAssignmentsList();
                } else {
                    showToast('error', response.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        showToast('error', value[0]);
                    });
                } else {
                    showToast('error', 'Something went wrong. Please try again.');
                }
            }
        });
    });

    // Edit assignment
    $(document).on("click", ".edit-assign-teacher", function (e) {
        e.preventDefault();
        const assignmentId = $(this).data("assignment-id");

        // Fetch assignment details via AJAX
        $.ajax({
            url: `/institution/academic/assign-class-teacher/${assignmentId}/edit`,
            type: "GET",
            success: function (response) {
                if (response.success) {
                    const data = response.data;
                    
                    // Store the data for later use
                    window.currentEditData = data;
                    
                    // Populate the form with assignment data
                    $("#assign-teacher-form [name='status']").prop("checked", data.status);
                    $("#assign-teacher-form [name='id']").val(data.id);
                    
                    // Trigger institution change to load classes and teachers
                    $("#institution_id").trigger('change.assignTeacher');
                    
                    // Show the edit mode
                    $("#assign-teacher").addClass("d-none");
                    $("#update-assign-teacher").removeClass("d-none");
                    $("#cancel-edit").removeClass("d-none");
                } else {
                    showToast("error", "Failed to fetch assignment details");
                }
            },
            error: function () {
                showToast("error", "Error fetching assignment details");
            },
        });
    });

    // Update assignment
    $('#update-assign-teacher').on('click', function (e) {
        e.preventDefault();

        const form = $('#assign-teacher-form');
        const formData = new FormData(form[0]);

        $.ajax({
            url: `/institution/academic/assign-class-teacher/${formData.get("id")}`,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function (response) {
                if (response.success) {
                    showToast("success", response.message);
                    resetForm();
                    refreshAssignmentsList();
                } else {
                    showToast("error", response.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        showToast("error", value[0]);
                    });
                } else {
                    showToast("error", "Error updating assignment");
                }
            },
        });
    });

    // Cancel edit
    $('#cancel-edit').on('click', function (e) {
        e.preventDefault();
        resetForm();
    });

    // Delete assignment
    $(document).on("click", ".delete-assign-teacher", function (e) {
        e.preventDefault();
        const assignmentId = $(this).data("assignment-id");

        if (confirm("Are you sure you want to delete this assignment?")) {
            $.ajax({
                url: `/institution/academic/assign-class-teacher/${assignmentId}`,
                type: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function (response) {
                    if (response.success) {
                        showToast("success", response.message);
                        refreshAssignmentsList();
                    } else {
                        showToast("error", response.message);
                    }
                },
                error: function () {
                    showToast("error", "Error deleting assignment");
                },
            });
        }
    });

    // Update status
    $(document).on("change", ".status-toggle", function () {
        const assignmentId = $(this).data("assignment-id");
        const status = $(this).val() == 1;

        $.ajax({
            url: `/institution/academic/assign-class-teacher/${assignmentId}/status`,
            type: "POST",
            data: {
                status: status,
                _token: $('input[name="_token"]').val()
            },
            success: function (response) {
                if (response.success) {
                    showToast("success", response.message);
                } else {
                    showToast("error", response.message);
                    // Revert the select value
                    $(this).val(status ? 0 : 1);
                }
            },
            error: function () {
                showToast("error", "Error updating status");
                // Revert the select value
                $(this).val(status ? 0 : 1);
            },
        });
    });
});
