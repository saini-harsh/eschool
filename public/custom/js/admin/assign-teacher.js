$(document).ready(function () {
    // Initialize cascading dropdowns
    initInstitutionClassTeacherDropdowns();
    
    // Initialize filter functionality
    initializeFilterFunctionality();
    
    console.log('Admin assign class teacher JavaScript loaded successfully');

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
            url: '/admin/academic/assign-class-teacher/list',
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
                loadClassesByInstitution(institutionId);
                loadTeachersByInstitution(institutionId);
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

    // Load classes by institution via AJAX
    function loadClassesByInstitution(institutionId) {
        const classSelect = $('#class_id');
        classSelect.prop('disabled', true).html('<option value="">Loading classes...</option>');
        
        $.ajax({
            url: `/admin/academic/assign-class-teacher/classes/${institutionId}`,
            type: 'GET',
            success: function(response) {
                let options = '<option value="">Select Class</option>';
                response.classes.forEach(function(cls) {
                    options += `<option value="${cls.id}">${cls.name}</option>`;
                });
                
                classSelect.html(options).prop('disabled', false);
                
                // If we're in edit mode, set the class value and trigger change
                if (window.currentEditData && window.currentEditData.class_id) {
                    classSelect.val(window.currentEditData.class_id);
                    classSelect.trigger('change.assignTeacher');
                }
            },
            error: function(xhr, status, error) {
                classSelect.html('<option value="">Error loading classes</option>').prop('disabled', false);
                showToast('error', 'Failed to load classes');
            }
        });
    }

    // Load teachers by institution via AJAX
    function loadTeachersByInstitution(institutionId) {
        const teacherSelect = $('#teacher_id');
        teacherSelect.prop('disabled', true).html('<option value="">Loading teachers...</option>');
        
        $.ajax({
            url: `/admin/academic/assign-class-teacher/teachers/${institutionId}`,
            type: 'GET',
            success: function(response) {
                let options = '<option value="">Select Teacher</option>';
                response.teachers.forEach(function(teacher) {
                    const fullName = `${teacher.first_name} ${teacher.last_name}`.trim();
                    options += `<option value="${teacher.id}">${fullName}</option>`;
                });
                
                teacherSelect.html(options).prop('disabled', false);
                
                // If we're in edit mode, set the teacher value
                if (window.currentEditData && window.currentEditData.teacher_id) {
                    teacherSelect.val(window.currentEditData.teacher_id);
                }
            },
            error: function(xhr, status, error) {
                teacherSelect.html('<option value="">Error loading teachers</option>').prop('disabled', false);
                showToast('error', 'Failed to load teachers');
            }
        });
    }

    // Load sections by class via AJAX
    function loadSectionsByClass(classId) {
        const sectionSelect = $('#section_id');
        sectionSelect.prop('disabled', true).html('<option value="">Loading sections...</option>');
        
        $.ajax({
            url: `/admin/academic/assign-class-teacher/sections/${classId}`,
            type: 'GET',
            success: function(response) {
                let options = '<option value="">Select Section</option>';
                response.sections.forEach(function(section) {
                    options += `<option value="${section.id}">${section.name}</option>`;
                });
                
                sectionSelect.html(options).prop('disabled', false);
                
                // If we're in edit mode, set the section value
                if (window.currentEditData && window.currentEditData.section_id) {
                    sectionSelect.val(window.currentEditData.section_id);
                }
            },
            error: function(xhr, status, error) {
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
            url: '/admin/academic/assign-class-teacher/',
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
            url: `/admin/academic/assign-class-teacher/${assignmentId}/edit`,
            type: "GET",
            success: function (response) {
                if (response.success) {
                    const data = response.data;
                    
                    // Store the data for later use
                    window.currentEditData = data;
                    
                    // Populate the form with assignment data
                    $("#assign-teacher-form [name='institution_id']").val(data.institution_id);
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
            url: `/admin/academic/assign-class-teacher/${formData.get("id")}`,
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
                url: `/admin/academic/assign-class-teacher/${assignmentId}`,
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
            url: `/admin/academic/assign-class-teacher/${assignmentId}/status`,
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

/**
 * Initialize filter functionality
 */
function initializeFilterFunctionality() {
    initializeFilterForm();
    initializeClearFilters();
}


/**
 * Initialize filter form functionality
 */
function initializeFilterForm() {
    // Handle filter form submission
    $(document).on('submit', '#filter-form', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const filters = {};
        
        // Collect selected institution IDs
        const institutionIds = [];
        $('input[name="institution_ids[]"]:checked').each(function() {
            institutionIds.push($(this).val());
        });
        if (institutionIds.length > 0) {
            filters.institution_ids = institutionIds;
        }
        
        // Collect selected class IDs
        const classIds = [];
        $('input[name="class_ids[]"]:checked').each(function() {
            classIds.push($(this).val());
        });
        if (classIds.length > 0) {
            filters.class_ids = classIds;
        }
        
        // Collect selected teacher IDs
        const teacherIds = [];
        $('input[name="teacher_ids[]"]:checked').each(function() {
            teacherIds.push($(this).val());
        });
        if (teacherIds.length > 0) {
            filters.teacher_ids = teacherIds;
        }
        
        // Collect selected status
        const status = [];
        $('input[name="status[]"]:checked').each(function() {
            status.push($(this).val());
        });
        if (status.length > 0) {
            filters.status = status;
        }
        
        console.log('Applying filters:', filters);
        applyFilters(filters);
    });
    
    // Handle close filter button
    $(document).on('click', '#close-filter', function() {
        $('#filter-dropdown').removeClass('show');
    });
}

/**
 * Initialize clear filters functionality
 */
function initializeClearFilters() {
    // Clear all filters
    $(document).on('click', '.link-danger', function() {
        $('#filter-form')[0].reset();
        applyFilters({});
    });
    
    // Clear individual filter fields
    $(document).on('click', '.filter-reset', function() {
        const field = $(this).data('field');
        $(`input[name="${field}[]"]`).prop('checked', false);
        applyFilters({});
    });
}

/**
 * Apply filters via AJAX
 */
function applyFilters(filters) {
    console.log('Applying filters:', filters);
    
    $.ajax({
        url: '/admin/academic/assign-class-teacher/filter',
        type: 'POST',
        data: {
            ...filters,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            // Show loading indicator
            $('.datatable tbody').html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');
        },
        success: function(response) {
            console.log('Filter response:', response);
            console.log('Response data type:', typeof response.data);
            console.log('Response data length:', response.data ? response.data.length : 'undefined');
            
            if (response.success) {
                console.log('Calling updateAssignmentsTable with:', response.data);
                updateAssignmentsTable(response.data);
            } else {
                showToast('error', response.message || 'Failed to apply filters');
            }
        },
        error: function(xhr, status, error) {
            console.error('Filter error:', xhr.responseText);
            showToast('error', 'Error applying filters: ' + error);
            // Reload original data on error
            refreshAssignmentsList();
        }
    });
}

/**
 * Function to refresh assignments list dynamically
 */
function refreshAssignmentsList() {
    $.ajax({
        url: '/admin/academic/assign-class-teacher/list',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                updateAssignmentsTable(response.data);
            } else {
                showToast('error', 'Failed to refresh assignments list');
            }
        },
        error: function() {
            showToast('error', 'Error refreshing assignments list');
        },
    });
}

/**
 * Function to update the assignments table
 */
function updateAssignmentsTable(assignments) {
    console.log('updateAssignmentsTable called with:', assignments);
    console.log('Assignments type:', typeof assignments);
    console.log('Assignments length:', assignments ? assignments.length : 'undefined');
    
    const tbody = $(".datatable tbody");
    console.log('Found tbody:', tbody.length);
    
    let html = "";

    if (!assignments || assignments.length === 0) {
        console.log('No assignments found, showing empty message');
        html = '<tr><td colspan="6" class="text-center">No assignments found</td></tr>';
    } else {
        console.log('Processing', assignments.length, 'assignments');
        assignments.forEach(function (assignment) {
            const statusText = assignment.status == 1 ? "Active" : "Inactive";
            const statusClass = assignment.status == 1 ? "active" : "";
            
            console.log('Processing assignment:', assignment);

            html += `
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
        });
    }

    console.log('Generated HTML length:', html.length);
    console.log('Setting tbody HTML...');
    
    tbody.html(html);
    
    console.log('HTML set successfully');
}

/**
 * Test function for manual console testing
 */
function testFilter() {
    applyFilters({ search: 'test' });
}
