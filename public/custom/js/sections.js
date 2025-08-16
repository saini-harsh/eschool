$(document).ready(function () {
    // Add section form submission
    $("#add-section").on("click", function (e) {
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
            url: "/admin/sections/store",
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
                    form[0].reset();

                    // Re-check the status checkbox since reset() unchecks it
                    statusCheckbox.prop("checked", true);

                    // Refresh the sections list dynamically
                    refreshSectionsList();
                } else {
                    showToast(
                        "error",
                        response.message || "Something went wrong"
                    );
                }
            },
            error: function (xhr) {
                let errorMessage =
                    "An error occurred while creating the section";

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

    // Function to refresh sections list dynamically
    function refreshSectionsList() {
        $.ajax({
            url: "/admin/sections/list",
            type: "GET",
            success: function (response) {
                if (response.success) {
                    updateSectionsTable(response.data);
                } else {
                    showToast("error", "Failed to refresh sections list");
                }
            },
            error: function () {
                showToast("error", "Error refreshing sections list");
            },
        });
    }

    // Function to update the sections table
    function updateSectionsTable(sections) {
        const tbody = $(".datatable tbody");
        let html = "";

        if (sections.length === 0) {
            html =
                '<tr><td colspan="3" class="text-center">No sections found</td></tr>';
        } else {
            sections.forEach(function (section) {
                const statusText = section.status == 1 ? "Active" : "Inactive";
                const statusClass = section.status == 1 ? "active" : "";

                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${section.name}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <select class="form-select status-select" data-section-id="${
                                    section.id
                                }">
                                    <option value="1" ${
                                        section.status == 1 ? "selected" : ""
                                    }>Active</option>
                                    <option value="0" ${
                                        section.status == 0 ? "selected" : ""
                                    }>Inactive</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <a href="" class="btn btn-icon btn-sm btn-outline-white border-0">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0"
                                   data-bs-toggle="modal" data-bs-target="#delete_modal">
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
        $(".status-select").on("change", function () {
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
                    showToast("success", "Status updated successfully");
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
                refreshSectionsList();
            },
        });
    }

    // Initial load of sections (optional - if you want to load on page load)
    // refreshSectionsList();
});
