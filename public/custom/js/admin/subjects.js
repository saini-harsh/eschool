$(document).ready(function () {
    // Check if we're on the subjects page by looking for subject-specific elements
    if ($(".subject-status-select").length > 0 || window.location.pathname.includes('/admin/subjects')) {
        // Initialize Select2 for existing dropdowns
        initializeSelect2();
        
        // Add event listeners for status changes on page load
        addStatusChangeListeners();
        
        // Initialize institution-class cascading dropdowns
        initInstitutionClassDropdowns();
    }

    // Add subject form submission
    $("#add-subject").on("click", function (e) {
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
            url: "/admin/subjects/store",
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

                    // Refresh the subjects list dynamically
                    refreshSubjectsList();
                } else {
                    showToast(
                        "error",
                        response.message || "Something went wrong"
                    );
                }
            },
            error: function (xhr) {
                let errorMessage =
                    "An error occurred while creating the subject";

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
        const form = $("#subject-form")[0];
        form.reset();

        // Re-check the status checkbox since reset() unchecks it
        $("#subject-status").prop("checked", true);

        // Clear hidden field
        $("#subject-id").val("");

        // Switch buttons back to add mode
        $("#add-subject").removeClass("d-none");
        $("#update-subject").addClass("d-none");
        $("#cancel-edit").addClass("d-none");
    }

});

/**
 * Function to refresh subjects list dynamically
 */
    function refreshSubjectsList() {
        $.ajax({
            url: "/admin/subjects/list",
            type: "GET",
            success: function (response) {
                if (response.success) {
                    updateSubjectsTable(response.data);
                } else {
                    showToast("error", "Failed to refresh subject list");
                }
            },
            error: function () {
                showToast("error", "Error refreshing subject list");
            },
        });
    }

/**
 * Function to update the subject table
 */
    function updateSubjectsTable(subjects) {
    console.log('updateSubjectsTable called with:', subjects);
    console.log('Subjects type:', typeof subjects);
    console.log('Subjects length:', subjects ? subjects.length : 'undefined');
    
        const tbody = $(".datatable tbody");
    console.log('Found tbody:', tbody.length);
    
        let html = "";

    if (!subjects || subjects.length === 0) {
        console.log('No subjects found, showing empty message');
            html =
                '<tr><td colspan="7" class="text-center">No subjects found</td></tr>';
        } else {
        console.log('Processing', subjects.length, 'subjects');
            subjects.forEach(function (subject) {
                const statusText = subject.status == 1 ? "Active" : "Inactive";
                const statusClass = subject.status == 1 ? "active" : "";
                const className = subject.school_class ? subject.school_class.name : 'N/A';
            
            console.log('Processing subject:', subject);

                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(subject.name)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(subject.code)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(subject.type)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(subject.institution.name)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(className)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                            <select class="form-select status-select" data-subject-id="${subject.id}">
                                <option value="1" ${subject.status == 1 ? "selected" : ""}>Active</option>
                                <option value="0" ${subject.status == 0 ? "selected" : ""}>Inactive</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                            <a href="javascript:void(0);" data-subject-id="${subject.id}" class="btn btn-icon btn-sm btn-outline-white border-0 edit-subject">
                                    <i class="ti ti-edit"></i>
                                </a>
                            <a href="javascript:void(0);" data-subject-id="${subject.id}" data-subject-name="${escapeHtml(subject.name)}" 
                                   class="btn btn-icon btn-sm btn-outline-white border-0 delete-subject">
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

        // Reinitialize Select2 for the new dropdowns
        initializeSelect2();

        // Add event listeners for status changes
        addStatusChangeListeners();
    }

/**
 * Function to initialize Select2 dropdowns
 */
    function initializeSelect2() {
        // Check if Select2 is available
        if (typeof $.fn.select2 !== "undefined") {
            // Only initialize Select2 for subject status selects
            $(".status-select[data-subject-id]").select2({
                minimumResultsForSearch: Infinity, // Disable search
                width: "100%", // Changed from "auto" to "100%"
            });
        }
    }

/**
 * Function to add event listeners for status changes
 */
    function addStatusChangeListeners() {
        // Remove any existing listeners to prevent conflicts
        $(".status-select").off("change.subjects");
        
        // Only listen for status selects that have subject-id data attribute
        $(".status-select[data-subject-id]").on("change.subjects", function () {
            const subjectId = $(this).data("subject-id");
            const newStatus = $(this).val();

            // Update status via AJAX
            updateSubjectStatus(subjectId, newStatus);
        });
    }

/**
 * Function to update subject status
 */
function updateSubjectStatus(subjectId, status) {
    $.ajax({
        url: `/admin/subjects/${subjectId}/status`,
        type: "POST",
        data: {
            status: status,
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.success) {
                showToast("success", "Status updated successfully");
                // Refresh the subject list to show updated data
                setTimeout(function() {
                    refreshSubjectsList();
                }, 1000);
            } else {
                showToast(
                    "error",
                    response.message || "Failed to update status"
                );
            }
        },
        error: function () {
            showToast("error", "Error updating status");
            // Revert the select to previous value
            refreshSubjectsList();
        },
    });
}

    // Edit subject
    $(document).on("click", ".edit-subject", function (e) {
        e.preventDefault();
        const subjectId = $(this).data("subject-id");

        // Fetch subject details via AJAX
        $.ajax({
            url: `/admin/subjects/edit/${subjectId}`,
            type: "GET",
            success: function (response) {
                if (response.success) {
                    // Populate the form with subject data
                    $("#subject-form [name='name']").val(response.data.name);
                    $("#subject-form [name='code']").val(response.data.code);
                    $("#subject-form [name='type']").val(response.data.type);
                    $("#subject-form [name='status']").prop(
                        "checked",
                        response.data.status
                    );
                    $("#subject-form [name='institution_id']").val(
                        response.data.institution_id
                    );
                    $("#subject-form [name='class_id']").val(
                        response.data.class_id
                    );
                    $("#subject-form [name='id']").val(response.data.id);
                    
                    // Show the edit modal
                    $("#add-subject").addClass("d-none");
                    $("#update-subject").removeClass("d-none");
                    $("#cancel-edit").removeClass("d-none");
                } else {
                    showToast("error", "Failed to fetch subject details");
                }
            },
            error: function () {
                showToast("error", "Error fetching subject details");
            },
        });
    });

    // Update subject
    $(document).on("click", "#update-subject", function (e) {
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

        // Make AJAX request to update subject
        $.ajax({
            url: `/admin/subjects/update/${formData.get("id")}`,
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

                    // Refresh the subjects list dynamically
                    refreshSubjectsList();
                } else {
                    showToast(
                        "error",
                        response.message || "Something went wrong"
                    );
                }
            },
            error: function (xhr) {
                let errorMessage =
                    "An error occurred while updating the subject";

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

    // Delete subject functionality
    $(document).on("click", ".delete-subject", function (e) {
        e.preventDefault();
        const subjectId = $(this).data("subject-id");
        const subjectName = $(this).data("subject-name");
        
        // Update modal content with subject-specific information
        $("#delete_modal .modal-body h6").text("Delete Subject");
        $("#delete_modal .modal-body p").text(`Are you sure you want to delete the subject "${subjectName}"?`);
        
        // Set up the delete form action
        $("#deleteForm").attr("action", `/admin/subjects/delete/${subjectId}`);
        
        // Show the modal
        const deleteModal = new bootstrap.Modal(document.getElementById('delete_modal'));
        deleteModal.show();
    });

    // Handle delete form submission for subjects
    $("#deleteForm").on("submit", function (e) {
        e.preventDefault();
        
        // Only handle if we're on the subjects page
        if (!window.location.pathname.includes('/admin/subjects')) {
            return;
        }
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.text();
        
        // Disable submit button and show loading state
        submitBtn.prop("disabled", true).text("Deleting...");
        
        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: form.serialize(),
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                if (response.success) {
                    showToast("success", response.message || "Subject deleted successfully");
                    // Hide the modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('delete_modal'));
                    deleteModal.hide();
                    // Refresh the subject list
                    refreshSubjectsList();
                } else {
                    showToast("error", response.message || "Failed to delete subject");
                }
            },
            error: function (xhr) {
                let errorMessage = "An error occurred while deleting the subject";
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

    // Initial load of subjects (optional - if you want to load on page load)
    // refreshSubjectsList();
    initializeSelect2(); // Initialize Select2 for existing dropdowns
    addStatusChangeListeners(); // Add event listeners for status changes
    
    // Initialize filter functionality
    initializeFilterFunctionality();
    
    // Test function to verify JavaScript is working
    console.log('Admin subjects JavaScript loaded successfully');
});

/**
 * Initialize filter functionality
 */
function initializeFilterFunctionality() {
    // Search functionality
    initializeSearch();
    
    // Filter form functionality
    initializeFilterForm();
    
    // Clear filters functionality
    initializeClearFilters();
}

/**
 * Initialize search functionality
 */
function initializeSearch() {
    // Create search input if it doesn't exist
    if ($('.datatable-search input').length === 0) {
        $('.datatable-search').html(`
            <div class="position-relative">
                <input type="text" class="form-control" placeholder="Search subjects..." id="subject-search">
                <i class="ti ti-search position-absolute top-50 end-0 translate-middle-y me-3"></i>
            </div>
        `);
    }
    
    // Add search event listener with debouncing
    let searchTimeout;
    $(document).on('input', '#subject-search', function() {
        const searchTerm = $(this).val();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Set new timeout for debounced search
        searchTimeout = setTimeout(function() {
            applyFilters({ search: searchTerm });
        }, 300);
    });
}

/**
 * Initialize filter form functionality
 */
function initializeFilterForm() {
    // Filter form submission
    $(document).on('submit', '#filter-dropdown form', function(e) {
        e.preventDefault();
        console.log('Filter form submitted');
        
        const formData = new FormData(this);
        const filters = {};
        
        // Get institution filters
        const institutionIds = [];
        $(this).find('input[name="institution_ids[]"]:checked').each(function() {
            institutionIds.push($(this).val());
        });
        if (institutionIds.length > 0) {
            filters.institution_ids = institutionIds;
        }
        
        // Get status filters
        const status = [];
        $(this).find('input[name="status[]"]:checked').each(function() {
            status.push($(this).val());
        });
        if (status.length > 0) {
            filters.status = status;
        }
        
        // Get class filters
        const classIds = [];
        $(this).find('input[name="class_ids[]"]:checked').each(function() {
            classIds.push($(this).val());
        });
        if (classIds.length > 0) {
            filters.class_ids = classIds;
        }
        
        // Get type filter
        const type = $(this).find('select[name="type"]').val();
        if (type) {
            filters.type = type;
        }
        
        console.log('Applied filters:', filters);
        
        // Apply filters
        applyFilters(filters);
        
        // Close filter dropdown
        $('#filter-dropdown').removeClass('show');
    });
    
    // Close filter button
    $(document).on('click', '#close-filter', function() {
        $('#filter-dropdown').removeClass('show');
    });
}

/**
 * Initialize clear filters functionality
 */
function initializeClearFilters() {
    // Clear all filters
    $('a[href="javascript:void(0);"]:contains("Clear All")').on('click', function() {
        // Clear search
        $('#subject-search').val('');
        
        // Clear filter form
        $('#filter-dropdown form')[0].reset();
        
        // Clear all checkboxes
        $('#filter-dropdown input[type="checkbox"]').prop('checked', false);
        
        // Apply empty filters (show all)
        applyFilters({});
    });
    
    // Reset individual filter sections
    $('a[href="javascript:void(0);"]:contains("Reset")').on('click', function() {
        const section = $(this).closest('.mb-3');
        section.find('input[type="checkbox"]').prop('checked', false);
        section.find('select').val('');
    });
}

/**
 * Apply filters to subjects
 */
function applyFilters(filters) {
    console.log('Applying filters:', filters);
    
    $.ajax({
        url: '/admin/subjects/filter',
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
            console.log('Response data type:', typeof response.data);
            console.log('Response data length:', response.data ? response.data.length : 'undefined');
            
            if (response.success) {
                console.log('Calling updateSubjectsTable with:', response.data);
                updateSubjectsTable(response.data);
            } else {
                showToast('error', response.message || 'Failed to apply filters');
            }
        },
        error: function(xhr, status, error) {
            console.error('Filter error:', xhr.responseText);
            showToast('error', 'Error applying filters: ' + error);
            // Reload original data on error
            refreshSubjectsList();
        }
    });
}

/**
 * Test function to verify filter functionality
 * Call this from browser console: testFilter()
 */
function testFilter() {
    console.log('Testing filter functionality...');
    applyFilters({ search: 'test' });
}

/**
 * Initialize institution-class cascading dropdowns
 */
function initInstitutionClassDropdowns() {
    // Institution change handler
    $('#institution_id').off('change.subjects').on('change.subjects', function() {
        const institutionId = $(this).val();
        const classSelect = $('#class_id');
        
        // Clear validation errors for institution field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
        
        if (institutionId) {
            loadClassesByInstitution(institutionId);
        } else {
            // Reset class dropdown
            classSelect.html('<option value="">Select Class</option>').prop('disabled', true);
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
        url: `/admin/subjects/classes/${institutionId}`,
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
        },
        error: function(xhr, status, error) {
            classSelect.html('<option value="">Error loading classes</option>').prop('disabled', false);
            showToast('error', 'Failed to load classes');
        }
    });
}
