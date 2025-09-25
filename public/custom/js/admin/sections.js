$(document).ready(function () {
    $('#filter_institution_id').on('change', function() {
        var institutionId = $(this).val();
        if (institutionId) {
            $.ajax({
                url: '/admin/sections/by-institution/' + institutionId,
                type: 'GET',
                success: function(response) {
                    var rows = '';
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(section) {
                            rows += `<tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <h6 class="fs-14 mb-0">${section.institution.name}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-2">
                                            <h6 class="fs-14 mb-0">${section.name}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <select class="form-select section-status-select" data-section-id="${section.id}">
                                            <option value="1" ${section.status == 1 ? 'selected' : ''}>Active</option>
                                            <option value="0" ${section.status == 0 ? 'selected' : ''}>Inactive</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-inline-flex align-items-center">
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 edit-section"
                                        data-section-id="${section.id}" data-section-name="${section.name}"
                                        data-status="${section.status}">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 delete-section"
                                        data-section-id="${section.id}" data-section-name="${section.name}">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>`;
                        });
                    } else {
                        rows = `<tr><td colspan="3" class="text-center">No sections found.</td></tr>`;
                    }
                    $('#sections-table-body').html(rows);
                }
            });
        } else {
            $('#sections-table-body').html('<tr><td colspan="3" class="text-center">Please select an institution.</td></tr>');
        }
    });

    // Optionally, trigger change on page load to show empty state
    $('#filter_institution_id').trigger('change');

    // Check if we're on the sections page by looking for section-specific elements
    if ($(".section-status-select").length > 0 || window.location.pathname.includes('/admin/sections')) {
        // Initialize Select2 for existing dropdowns
        initializeSelect2();

        // Add event listeners for status changes on page load
        addStatusChangeListeners();
    }

    // Add section
    $("#add-section").on("click", function (e) {
        e.preventDefault();
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

        $.ajax({
            url: "/admin/sections/store",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    resetForm();
                    refreshSectionList();
                } else {
                    toastr.error(response.message || "Something went wrong");
                }
            },
            error: function (xhr) {
                let errorMessage = "An error occurred while creating the section";
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    if (errors && Object.keys(errors).length > 0) {
                        const firstError = Object.values(errors)[0][0];
                        errorMessage = firstError;
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function () {
                // Re-enable submit button
                submitBtn.prop("disabled", false).text(originalText);
            }
        });
    });

    // Edit section functionality
    $(document).on("click", ".edit-section", function (e) {
        e.preventDefault();
        const sectionId = $(this).data("section-id");
        const sectionName = $(this).data("section-name");
        const status = $(this).data("status");

        // Populate the form with section data
        $("#section_id").val(sectionId);
        $("#section_name").val(sectionName);
        $("#section_status").prop("checked", status == 1);

        // Switch buttons
        $("#add-section").addClass("d-none");
        $("#update-section").removeClass("d-none");
        $("#cancel-edit").removeClass("d-none");

        // Scroll to form
        $('html, body').animate({
            scrollTop: $("#section-form").offset().top - 100
        }, 500);
    });

    // Update section
    $("#update-section").on("click", function (e) {
        e.preventDefault();
        const form = $(this).closest("form");
        const formData = new FormData(form[0]);
        const sectionId = $("#section_id").val();

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
        submitBtn.prop("disabled", true).text("Updating...");

        $.ajax({
            url: `/admin/sections/update/${sectionId}`,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    resetForm();
                    refreshSectionList();
                } else {
                    toastr.error(response.message || "Something went wrong");
                }
            },
            error: function (xhr) {
                let errorMessage = "An error occurred while updating the section";
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    if (errors && Object.keys(errors).length > 0) {
                        const firstError = Object.values(errors)[0][0];
                        errorMessage = firstError;
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function () {
                // Re-enable submit button
                submitBtn.prop("disabled", false).text(originalText);
            }
        });
    });

    // Cancel edit
    $("#cancel-edit").on("click", function (e) {
        e.preventDefault();
        resetForm();
    });

    // Delete section functionality
    $(document).on("click", ".delete-section", function (e) {
        e.preventDefault();
        const sectionId = $(this).data("section-id");
        const sectionName = $(this).data("section-name");

        // Update modal content with section-specific information
        $("#delete_modal .modal-body h6").text("Delete Section");
        $("#delete_modal .modal-body p").text(`Are you sure you want to delete the section "${sectionName}"?`);

        // Set up the delete form action
        $("#deleteForm").attr("action", `/admin/sections/delete/${sectionId}`);

        // Show the modal
        const deleteModal = new bootstrap.Modal(document.getElementById('delete_modal'));
        deleteModal.show();
    });

    // Handle delete form submission for sections
    $("#deleteForm").on("submit", function (e) {
        e.preventDefault();

        // Only handle if we're on the sections page
        if (!window.location.pathname.includes('/admin/sections')) {
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
                    toastr.success(response.message || "Section deleted successfully");
                    // Hide the modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('delete_modal'));
                    deleteModal.hide();
                    // Refresh the section list
                    refreshSectionList();
                } else {
                    toastr.error(response.message || "Failed to delete section");
                }
            },
            error: function (xhr) {
                let errorMessage = "An error occurred while deleting the section";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function () {
                // Re-enable submit button
                submitBtn.prop("disabled", false).text(originalText);
            }
        });
    });

    // Function to reset form to add mode
    function resetForm() {
        const form = $("#section-form")[0];
        form.reset();

        // Re-check the status checkbox since reset() unchecks it
        $("#section_status").prop("checked", true);

        // Clear hidden field
        $("#section_id").val("");

        // Switch buttons back to add mode
        $("#add-section").removeClass("d-none");
        $("#update-section").addClass("d-none");
        $("#cancel-edit").addClass("d-none");
    }

    function refreshSectionList() {
        $.ajax({
            url: "/admin/sections/list",
            type: "GET",
            success: function (response) {
                if (response.success) {
                    updateSectionTable(response.data);
                }
            },
            error: function (xhr, status, error) {
                toastr.error("Failed to refresh section list");
            }
        });
    }

    function updateSectionTable(sections) {
        const tbody = $(".datatable tbody");
        let html = "";

        if (sections.length === 0) {
            html = `<tr><td colspan="3" class="text-center">No sections found</td></tr>`;
        } else {
            sections.forEach(section => {
                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(section.name)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <select class="form-select section-status-select" data-section-id="${section.id}">
                                    <option value="1" ${section.status == 1 ? 'selected' : ''}>Active</option>
                                    <option value="0" ${section.status == 0 ? 'selected' : ''}>Inactive</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 edit-section"
                                   data-section-id="${section.id}" data-section-name="${escapeHtml(section.name)}"
                                   data-status="${section.status}">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 delete-section"
                                   data-section-id="${section.id}" data-section-name="${escapeHtml(section.name)}">
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
            $(".section-status-select").select2({
                minimumResultsForSearch: Infinity, // Disable search
                width: "100%", // Changed from "auto" to "100%"
            });
        }
    }

    // Function to add event listeners for status changes
    function addStatusChangeListeners() {
        // Remove any existing listeners to prevent conflicts
        $(".section-status-select").off("change");

        $(".section-status-select").on("change", function () {
            const sectionId = $(this).data("section-id");
            const newStatus = $(this).val();

            // Update status via AJAX
            updateSectionStatus(sectionId, newStatus);
        });
    }

    // Function to update section status
    function updateSectionStatus(sectionId, status) {
        $.ajax({
            url: `/admin/sections/${sectionId}/status`,
            type: "POST",
            data: {
                status: status,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    toastr.success("Status updated successfully");
                    // Refresh the section list to show updated data
                    setTimeout(function() {
                        refreshSectionList();
                    }, 1000);
                } else {
                    toastr.error(response.message || "Failed to update status");
                }
            },
            error: function (xhr, status, error) {
                toastr.error("Error updating status");
                // Revert the select to previous value
                refreshSectionList();
            },
        });
    }

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
});
