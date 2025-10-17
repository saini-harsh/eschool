$(document).ready(function () {
    // Check if we're on the assign subject page
    if (window.location.pathname.includes('/admin/assign-subject')) {
        // Initialize cascading dropdowns
        initAssignSubjectDropdowns();
        
        // Initialize Select2 for existing dropdowns
        initializeSelect2();
        
        // Add event listeners for status changes on page load
        addStatusChangeListeners();
    }

    // Global variables
    let currentInstitutionId = null;
    let currentClassId = null;

    // CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Add assign subject form submission
    $("#add-assign-subject").on("click", function (e) {
        e.preventDefault();

        // Get form data
        const form = $(this).closest("form");
        const formData = new FormData(form[0]);

        // Handle checkbox status properly
        const statusCheckbox = form.find('input[name="status"]');
        if (statusCheckbox.is(':checked')) {
            formData.set('status', '1');
        } else {
            formData.set('status', '0');
        }

        // Disable submit button and show loading state
        const submitBtn = $(this);
        const originalText = submitBtn.text();
        submitBtn.prop("disabled", true).text("Creating...");

        // Clear previous error messages
        $(".error-message").remove();
        $(".is-invalid").removeClass("is-invalid");

        // Make AJAX request
        $.ajax({
            url: "/admin/assign-subject/",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    // Show success message
                    showToast("success", response.message);

                    // Reset form
                    resetForm();

                    // Refresh the assignments list dynamically
                    refreshAssignmentsList();
                } else {
                    showToast(
                        "error",
                        response.message || "Something went wrong"
                    );
                }
            },
            error: function (xhr) {
                let errorMessage =
                    "An error occurred while creating the assignment";

                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    displayValidationErrors(errors);
                    errorMessage = "Please fix the validation errors";
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showToast("error", errorMessage);
            },
            complete: function () {
                // Re-enable submit button
                submitBtn.prop("disabled", false).text(originalText);
            },
        });
    });

    // Function to display validation errors
    function displayValidationErrors(errors) {
        $.each(errors, function (field, messages) {
            const input = $(`[name="${field}"]`);
            input.addClass("is-invalid");

            // Add error message below the input
            const errorDiv = $(
                '<div class="error-message text-danger small mt-1"></div>'
            );
            errorDiv.text(messages[0]);
            input.closest(".mb-3").append(errorDiv);
        });
    }

    // Function to show toast notifications
    function showToast(type, message) {
        // Check if toastr is available
        if (typeof toastr !== "undefined") {
            toastr[type](message);
        } else {
            // Fallback to alert if toastr is not available
            alert(message);
        }
    }

    // Function to reset form to add mode
    function resetForm() {
        const form = $("#assign-subject-form")[0];
        form.reset();

        // Re-check the status checkbox since reset() unchecks it
        $("#assign-subject-status").prop("checked", true);

        // Clear hidden field
        $("#assign-subject-id").val("");

        // Clear edit data
        window.currentEditData = null;

        // Switch buttons back to add mode
        $("#add-assign-subject").removeClass("d-none");
        $("#update-assign-subject").addClass("d-none");
        $("#cancel-edit").addClass("d-none");

        // Reset dropdowns
        resetDependentDropdowns(['class_id', 'section_id', 'subject_id', 'teacher_id']);
    }

    // Function to reset dependent dropdowns
    function resetDependentDropdowns(dropdowns) {
        dropdowns.forEach(function(dropdownId) {
            const dropdown = $(`#${dropdownId}`);
            dropdown.html(`<option value="">Select ${dropdownId.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</option>`).prop('disabled', true);
        });
    }

    // Function to refresh assignments list dynamically
    function refreshAssignmentsList() {
        $.ajax({
            url: "/admin/assign-subject/list",
            type: "GET",
            success: function (response) {
                if (response.success) {
                    updateAssignmentsTable(response.data);
                } else {
                    showToast("error", "Failed to refresh assignment list");
                }
            },
            error: function () {
                showToast("error", "Error refreshing assignment list");
            },
        });
    }

    // Function to update the assignment table
    function updateAssignmentsTable(assignments) {
        const tbody = $(".datatable tbody");
        let html = "";

        if (assignments.length === 0) {
            html =
                '<tr><td colspan="7" class="text-center">No assignments found</td></tr>';
        } else {
            assignments.forEach(function (assignment) {
                const teacherName = `${assignment.teacher.first_name || ''} ${assignment.teacher.middle_name || ''} ${assignment.teacher.last_name || ''}`.trim();
                const statusText = assignment.status == 1 ? "Active" : "Inactive";

                html += `
                    <tr data-assign-subject-id="${assignment.id}">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(teacherName)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(assignment.institution.name)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(assignment.school_class.name)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(assignment.section.name)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(assignment.subject.name)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <select class="form-select status-select assign-subject-status-select" data-assign-subject-id="${assignment.id}" data-original-value="${assignment.status}">
                                    <option value="1" ${assignment.status == 1 ? "selected" : ""}>Active</option>
                                    <option value="0" ${assignment.status == 0 ? "selected" : ""}>Inactive</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <a href="javascript:void(0);" data-assign-subject-id="${assignment.id}" class="btn btn-icon btn-sm btn-outline-white border-0 edit-assign-subject">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <a href="javascript:void(0);" data-assign-subject-id="${assignment.id}" 
                                   data-teacher-name="${escapeHtml(teacherName)}" 
                                   data-subject-name="${escapeHtml(assignment.subject.name)}" 
                                   class="btn btn-icon btn-sm btn-outline-white border-0 delete-assign-subject">
                                    <i class="ti ti-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        tbody.html(html);

        // Reinitialize Select2 for the new dropdowns
        initializeSelect2();

        // Add event listeners for status changes
        addStatusChangeListeners();
    }

    // Function to initialize Select2 dropdowns
    function initializeSelect2() {
        // Check if Select2 is available
        if (typeof $.fn.select2 !== "undefined") {
            // Only initialize Select2 for status selects
            $(".status-select[data-assign-subject-id]").select2({
                minimumResultsForSearch: Infinity, // Disable search
                width: "100%", // Changed from "auto" to "100%"
            });
        }
    }

    // Function to add event listeners for status changes
    function addStatusChangeListeners() {
        // Remove any existing listeners to prevent conflicts
        $(".status-select").off("change.assignSubject");
        
        // Only listen for status selects that have assign-subject-id data attribute
        $(".status-select[data-assign-subject-id]").on("change.assignSubject", function () {
            const assignSubjectId = $(this).data("assign-subject-id");
            const newStatus = $(this).val();

            // Update status via AJAX
            updateAssignSubjectStatus(assignSubjectId, newStatus);
        });
    }

    // Function to update assign subject status
    function updateAssignSubjectStatus(assignSubjectId, status) {
        $.ajax({
            url: `/admin/assign-subject/${assignSubjectId}/status`,
            type: "POST",
            data: {
                status: status,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    showToast("success", "Status updated successfully");
                    // Update the specific row status without full refresh
                    const statusSelect = $(`.status-select[data-assign-subject-id="${assignSubjectId}"]`);
                    statusSelect.data('original-value', status);
                } else {
                    showToast(
                        "error",
                        response.message || "Failed to update status"
                    );
                    // Revert the select to previous value
                    const statusSelect = $(`.status-select[data-assign-subject-id="${assignSubjectId}"]`);
                    const originalValue = statusSelect.data('original-value');
                    statusSelect.val(originalValue);
                }
            },
            error: function () {
                showToast("error", "Error updating status");
                // Revert the select to previous value
                const statusSelect = $(`.status-select[data-assign-subject-id="${assignSubjectId}"]`);
                const originalValue = statusSelect.data('original-value');
                statusSelect.val(originalValue);
            },
        });
    }

    // Edit assign subject
    $(document).on("click", ".edit-assign-subject", function (e) {
        e.preventDefault();
        const assignSubjectId = $(this).data("assign-subject-id");

        // Fetch assignment details via AJAX
        $.ajax({
            url: `/admin/assign-subject/${assignSubjectId}/edit`,
            type: "GET",
            success: function (response) {
                if (response.success) {
                    const data = response.data;
                    
                    // Store the data for later use
                    window.currentEditData = data;
                    
                    // Populate the form with assignment data
                    $("#assign-subject-form [name='institution_id']").val(data.institution_id);
                    $("#assign-subject-form [name='status']").prop("checked", data.status);
                    $("#assign-subject-form [name='id']").val(data.id);
                    
                    // Trigger institution change to load classes and teachers
                    $("#institution_id").trigger('change.assignSubject');
                    
                    // Show the edit mode
                    $("#add-assign-subject").addClass("d-none");
                    $("#update-assign-subject").removeClass("d-none");
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

    // Update assign subject
    $(document).on("click", "#update-assign-subject", function (e) {
        e.preventDefault();

        // Get form data
        const form = $(this).closest("form");
        const formData = new FormData(form[0]);

        // Disable submit button and show loading state
        const submitBtn = $(this);
        const originalText = submitBtn.text();
        submitBtn.prop("disabled", true).text("Updating...");

        // Clear previous error messages
        $(".error-message").remove();
        $(".is-invalid").removeClass("is-invalid");

        // Make AJAX request to update assignment
        $.ajax({
            url: `/admin/assign-subject/${formData.get("id")}`,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    showToast("success", response.message);

                    // Reset form
                    resetForm();

                    // Refresh the assignments list dynamically
                    refreshAssignmentsList();
                } else {
                    showToast(
                        "error",
                        response.message || "Something went wrong"
                    );
                }
            },
            error: function (xhr) {
                let errorMessage =
                    "An error occurred while updating the assignment";

                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    displayValidationErrors(errors);
                    errorMessage = "Please fix the validation errors";
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showToast("error", errorMessage);
            },
            complete: function () {
                // Re-enable submit button
                submitBtn.prop("disabled", false).text(originalText);
            },
        });
    });

    // Cancel edit
    $(document).on("click", "#cancel-edit", function (e) {
        e.preventDefault();
        resetForm();
    });

    // Delete assign subject functionality
    $(document).on("click", ".delete-assign-subject", function (e) {
        e.preventDefault();
        const assignSubjectId = $(this).data("assign-subject-id");
        const teacherName = $(this).data("teacher-name");
        const subjectName = $(this).data("subject-name");
        
        // Update modal content with assignment-specific information
        $("#delete_modal .modal-body h6").text("Delete Subject Assignment");
        $("#delete_modal .modal-body p").text(`Are you sure you want to delete the assignment of "${subjectName}" to "${teacherName}"?`);
        
        // Set up the delete form action
        $("#deleteForm").attr("action", `/admin/assign-subject/${assignSubjectId}`);
        
        // Show the modal
        const deleteModal = new bootstrap.Modal(document.getElementById('delete_modal'));
        deleteModal.show();
    });

    // Handle delete form submission for assignments
    $("#deleteForm").on("submit", function (e) {
        e.preventDefault();
        
        // Only handle if we're on the assign subject page
        if (!window.location.pathname.includes('/admin/assign-subject')) {
            return;
        }
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.text();
        
        // Disable submit button and show loading state
        submitBtn.prop("disabled", true).text("Deleting...");
        
        $.ajax({
            url: form.attr("action"),
            type: "DELETE",
            data: form.serialize(),
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                if (response.success) {
                    showToast("success", response.message || "Assignment deleted successfully");
                    // Hide the modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('delete_modal'));
                    deleteModal.hide();
                    // Refresh the assignment list via AJAX
                    refreshAssignmentsList();
                } else {
                    showToast("error", response.message || "Failed to delete assignment");
                }
            },
            error: function (xhr) {
                let errorMessage = "An error occurred while deleting the assignment";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showToast("error", errorMessage);
            },
            complete: function () {
                // Re-enable submit button
                submitBtn.prop("disabled", false).text(originalText);
            }
        });
    });

    // Helper function to escape HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Initialize Select2 for existing dropdowns
    initializeSelect2();
    addStatusChangeListeners();
    
    // Initialize filter functionality
    initializeFilterFunctionality();
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
        
        // Collect selected subject IDs
        const subjectIds = [];
        $('input[name="subject_ids[]"]:checked').each(function() {
            subjectIds.push($(this).val());
        });
        if (subjectIds.length > 0) {
            filters.subject_ids = subjectIds;
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
        url: '/admin/assign-subject/filter',
        type: 'POST',
        data: {
            ...filters,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            // Show loading indicator
            $('.datatable tbody').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
        },
        success: function(response) {
            console.log('Filter response:', response);
            if (response.success) {
                updateAssignmentsTable(response.data);
            } else {
                showToast('error', 'Failed to filter assignments');
                // Reload original data
                refreshAssignmentsList();
            }
        },
        error: function(xhr, status, error) {
            console.error('Filter error:', error);
            showToast('error', 'Error filtering assignments');
            // Reload original data
            refreshAssignmentsList();
        }
    });
}

/**
 * Initialize assign subject cascading dropdowns
 */
function initAssignSubjectDropdowns() {
    // Institution change handler
    $('#institution_id').off('change.assignSubject').on('change.assignSubject', function() {
        const institutionId = $(this).val();
        currentInstitutionId = institutionId;
        
        // Clear validation errors for institution field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
        
        if (institutionId) {
            loadClassesByInstitution(institutionId);
            loadTeachersByInstitution(institutionId);
        } else {
            resetDependentDropdowns(['class_id', 'section_id', 'subject_id', 'teacher_id']);
        }
    });

    // Class change handler
    $('#class_id').off('change.assignSubject').on('change.assignSubject', function() {
        const classId = $(this).val();
        currentClassId = classId;
        
        // Clear validation errors for class field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
        
        if (classId) {
            loadSectionsByClass(classId);
            if (currentInstitutionId) {
                loadSubjectsByInstitutionClass(currentInstitutionId, classId);
            }
        } else {
            resetDependentDropdowns(['section_id', 'subject_id']);
        }
    });
}

/**
 * Load classes by institution via AJAX
 */
function loadClassesByInstitution(institutionId) {
    const classSelect = $('#class_id');
    classSelect.prop('disabled', true).html('<option value="">Loading classes...</option>');
    
    $.ajax({
        url: `/admin/assign-subject/classes/${institutionId}`,
        type: 'GET',
        success: function(response) {
            let options = '<option value="">Select Class</option>';
            response.classes.forEach(function(cls) {
                options += `<option value="${cls.id}">${cls.name}</option>`;
            });
            
            classSelect.html(options).prop('disabled', false);
            
            // Clear validation errors for class field
            classSelect.removeClass('is-invalid');
            classSelect.siblings('.invalid-feedback').text('');
            
            // If we're in edit mode, set the class value and trigger change
            if (window.currentEditData && window.currentEditData.class_id) {
                classSelect.val(window.currentEditData.class_id);
                classSelect.trigger('change.assignSubject');
            }
        },
        error: function(xhr, status, error) {
            classSelect.html('<option value="">Error loading classes</option>').prop('disabled', false);
            showToast('error', 'Failed to load classes');
        }
    });
}

/**
 * Load teachers by institution via AJAX
 */
function loadTeachersByInstitution(institutionId) {
    const teacherSelect = $('#teacher_id');
    teacherSelect.prop('disabled', true).html('<option value="">Loading teachers...</option>');
    
    $.ajax({
        url: `/admin/assign-subject/teachers/${institutionId}`,
        type: 'GET',
        success: function(response) {
            let options = '<option value="">Select Teacher</option>';
            response.teachers.forEach(function(teacher) {
                const fullName = `${teacher.first_name} ${teacher.middle_name || ''} ${teacher.last_name}`.trim();
                options += `<option value="${teacher.id}">${fullName}</option>`;
            });
            
            teacherSelect.html(options).prop('disabled', false);
            
            // Clear validation errors for teacher field
            teacherSelect.removeClass('is-invalid');
            teacherSelect.siblings('.invalid-feedback').text('');
            
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

/**
 * Load subjects by institution and class via AJAX
 */
function loadSubjectsByInstitutionClass(institutionId, classId) {
    const subjectSelect = $('#subject_id');
    subjectSelect.prop('disabled', true).html('<option value="">Loading subjects...</option>');
    
    $.ajax({
        url: `/admin/assign-subject/subjects/${institutionId}/${classId}`,
        type: 'GET',
        success: function(response) {
            let options = '<option value="">Select Subject</option>';
            response.subjects.forEach(function(subject) {
                options += `<option value="${subject.id}">${subject.name} (${subject.code})</option>`;
            });
            
            subjectSelect.html(options).prop('disabled', false);
            
            // Clear validation errors for subject field
            subjectSelect.removeClass('is-invalid');
            subjectSelect.siblings('.invalid-feedback').text('');
            
            // If we're in edit mode, set the subject value
            if (window.currentEditData && window.currentEditData.subject_id) {
                subjectSelect.val(window.currentEditData.subject_id);
            }
        },
        error: function(xhr, status, error) {
            subjectSelect.html('<option value="">Error loading subjects</option>').prop('disabled', false);
            showToast('error', 'Failed to load subjects');
        }
    });
}

/**
 * Load sections by class via AJAX
 */
function loadSectionsByClass(classId) {
    const sectionSelect = $('#section_id');
    sectionSelect.prop('disabled', true).html('<option value="">Loading sections...</option>');
    
    $.ajax({
        url: `/admin/assign-subject/sections/${classId}`,
        type: 'GET',
        success: function(response) {
            let options = '<option value="">Select Section</option>';
            response.sections.forEach(function(section) {
                options += `<option value="${section.id}">${section.name}</option>`;
            });
            
            sectionSelect.html(options).prop('disabled', false);
            
            // Clear validation errors for section field
            sectionSelect.removeClass('is-invalid');
            sectionSelect.siblings('.invalid-feedback').text('');
            
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
