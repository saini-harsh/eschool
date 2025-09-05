$(document).ready(function () {
    // Check if we're on the subjects page by looking for subject-specific elements
    if ($(".subject-status-select").length > 0 || window.location.pathname.includes('/admin/subjects')) {
        // Initialize Select2 for existing dropdowns
        initializeSelect2();
        
        // Add event listeners for status changes on page load
        addStatusChangeListeners();
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

    // Function to refresh subjects list dynamically
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

    // Function to update the subject table
    function updateSubjectsTable(subjects) {
        const tbody = $(".datatable tbody");
        let html = "";

        if (subjects.length === 0) {
            html =
                '<tr><td colspan="7" class="text-center">No subjects found</td></tr>';
        } else {
            subjects.forEach(function (subject) {
                const statusText = subject.status == 1 ? "Active" : "Inactive";
                const statusClass = subject.status == 1 ? "active" : "";
                const className = subject.school_class ? subject.school_class.name : 'N/A';

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
                                <select class="form-select status-select" data-subject-id="${
                                    subject.id
                                }">
                                    <option value="1" ${
                                        subject.status == 1 ? "selected" : ""
                                    }>Active</option>
                                    <option value="0" ${
                                        subject.status == 0 ? "selected" : ""
                                    }>Inactive</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <a href="javascript:void(0);" data-subject-id="${
                                    subject.id
                                }" class="btn btn-icon btn-sm btn-outline-white border-0 edit-subject">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <a href="javascript:void(0);" data-subject-id="${
                                    subject.id
                                }" data-subject-name="${escapeHtml(subject.name)}" 
                                   class="btn btn-icon btn-sm btn-outline-white border-0 delete-subject">
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
            $(".status-select").select2({
                minimumResultsForSearch: Infinity, // Disable search
                width: "100%", // Changed from "auto" to "100%"
            });
        }
    }

    // Function to add event listeners for status changes
    function addStatusChangeListeners() {
        // Remove any existing listeners to prevent conflicts
        $(".status-select").off("change");
        
        $(".status-select").on("change", function () {
            const subjectId = $(this).data("subject-id");
            const newStatus = $(this).val();

            // Update status via AJAX
            updateSubjectStatus(subjectId, newStatus);
        });
    }

    // Function to update subject status
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
});
