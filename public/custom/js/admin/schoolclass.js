$(document).ready(function () {
    // Prevent multiple initializations
    if (window.schoolClassInitialized) {
        return;
    }
    window.schoolClassInitialized = true;

    // Check if we're on the classes page by looking for class-specific elements
    if (
        $(".class-status-select").length > 0 ||
        window.location.pathname.includes("/admin/classes")
    ) {
        // Initialize Select2 for existing dropdowns
        initializeSelect2();

        // Add event listeners for status changes on page load
        addStatusChangeListeners();
    }

    // Add class - unbind existing events first
    $("#add-class")
        .off("click.schoolclass")
        .on("click.schoolclass", function (e) {
            e.preventDefault();

            // Prevent multiple submissions
            if ($(this).data("submitting")) {
                return false;
            }

            const form = $(this).closest("form");
            const formData = new FormData(form[0]);

            // Handle checkbox status properly
            const statusCheckbox = form.find('input[name="status"]');
            if (statusCheckbox.is(":checked")) {
                formData.set("status", "1");
            } else {
                formData.set("status", "0");
            }

            // Disable submit button and show loading state
            const submitBtn = $(this);
            const originalText = submitBtn.text();

            // Mark as submitting
            submitBtn.data("submitting", true);
            submitBtn.prop("disabled", true).text("Creating...");

            $.ajax({
                url: "/admin/classes/store",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        resetForm();
                        refreshClassList();
                    } else {
                        toastr.error(
                            response.message || "Something went wrong"
                        );
                    }
                },
                error: function (xhr) {
                    let errorMessage =
                        "An error occurred while creating the class";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                },
                complete: function () {
                    // Re-enable submit button and clear submitting flag
                    submitBtn
                        .prop("disabled", false)
                        .text(originalText)
                        .data("submitting", false);
                },
            });
        });

    // Edit class functionality
    $(document).on("click", ".edit-class", function (e) {
        e.preventDefault();
        const classId = $(this).data("class-id");
        const className = $(this).data("class-name");
        const institutionId = $(this).data("institution-id");
        const sectionIds = $(this).data("section-ids");
        const status = $(this).data("status");

        // Populate the form with class data
        $("#class_id").val(classId);
        $("#class_name").val(className);
        $("#institution_id").val(institutionId);
        $("#class_status").prop("checked", status == 1);

        // Handle section checkboxes
        $(".section-checkbox").prop("checked", false); // Uncheck all first
        if (sectionIds && sectionIds.length > 0) {
            sectionIds.forEach(function (sectionId) {
                $(`#section_${sectionId}`).prop("checked", true);
            });
        }

        // Switch buttons
        $("#add-class").addClass("d-none");
        $("#update-class").removeClass("d-none");
        $("#cancel-edit").removeClass("d-none");

        // Scroll to form
        $("html, body").animate(
            {
                scrollTop: $("#class-form").offset().top - 100,
            },
            500
        );
    });

    // Update class - unbind existing events first
    $("#update-class")
        .off("click.schoolclass")
        .on("click.schoolclass", function (e) {
            e.preventDefault();

            // Prevent multiple submissions
            if ($(this).data("submitting")) {
                return false;
            }

            const form = $(this).closest("form");
            const formData = new FormData(form[0]);
            const classId = $("#class_id").val();

            // Handle checkbox status properly
            const statusCheckbox = form.find('input[name="status"]');
            if (statusCheckbox.is(":checked")) {
                formData.set("status", "1");
            } else {
                formData.set("status", "0");
            }

            // Disable submit button and show loading state
            const submitBtn = $(this);
            const originalText = submitBtn.text();

            // Mark as submitting
            submitBtn.data("submitting", true);
            submitBtn.prop("disabled", true).text("Updating...");

            $.ajax({
                url: `/admin/classes/update/${classId}`,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        resetForm();
                        refreshClassList();
                    } else {
                        toastr.error(
                            response.message || "Something went wrong"
                        );
                    }
                },
                error: function (xhr) {
                    let errorMessage =
                        "An error occurred while updating the class";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                },
                complete: function () {
                    // Re-enable submit button and clear submitting flag
                    submitBtn
                        .prop("disabled", false)
                        .text(originalText)
                        .data("submitting", false);
                },
            });
        });

    // Cancel edit - unbind existing events first
    $("#cancel-edit")
        .off("click.schoolclass")
        .on("click.schoolclass", function (e) {
            e.preventDefault();
            resetForm();
        });

    // Delete class functionality
    $(document).on("click", ".delete-class", function (e) {
        e.preventDefault();
        const classId = $(this).data("class-id");
        const className = $(this).data("class-name");

        // Update modal content with class-specific information
        $("#delete_modal .modal-body h6").text("Delete Class");
        $("#delete_modal .modal-body p").text(
            `Are you sure you want to delete the class "${className}"?`
        );

        // Set up the delete form action
        $("#deleteForm").attr("action", `/admin/classes/delete/${classId}`);

        // Show the modal
        const deleteModal = new bootstrap.Modal(
            document.getElementById("delete_modal")
        );
        deleteModal.show();
    });

    // Handle delete form submission for classes - unbind existing events first
    $("#deleteForm")
        .off("submit.schoolclass")
        .on("submit.schoolclass", function (e) {
            e.preventDefault();

            // Only handle if we're on the classes page
            if (!window.location.pathname.includes("/admin/classes")) {
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
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success(
                            response.message || "Class deleted successfully"
                        );
                        // Hide the modal
                        const deleteModal = bootstrap.Modal.getInstance(
                            document.getElementById("delete_modal")
                        );
                        deleteModal.hide();
                        // Refresh the class list
                        refreshClassList();
                    } else {
                        toastr.error(
                            response.message || "Failed to delete class"
                        );
                    }
                },
                error: function (xhr) {
                    let errorMessage =
                        "An error occurred while deleting the class";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                },
                complete: function () {
                    // Re-enable submit button and clear submitting flag
                    submitBtn
                        .prop("disabled", false)
                        .text(originalText)
                        .data("submitting", false);
                },
            });
        });

    // Function to reset form to add mode
    function resetForm() {
        const form = $("#class-form")[0];
        form.reset();

        // Re-check the status checkbox since reset() unchecks it
        $("#class_status").prop("checked", true);

        // Clear hidden field
        $("#class_id").val("");

        // Switch buttons back to add mode
        $("#add-class").removeClass("d-none");
        $("#update-class").addClass("d-none");
        $("#cancel-edit").addClass("d-none");
    }

    function refreshClassList() {
        $.ajax({
            url: "/admin/classes/list",
            type: "GET",
            success: function (response) {
                if (response.success) {
                    updateClassTable(response.data);
                }
            },
            error: function (xhr, status, error) {
                toastr.error("Failed to refresh class list");
            },
        });
    }

    function updateClassTable(classes) {
        const tbody = $(".datatable tbody");
        let html = "";

        if (classes.length === 0) {
            html = `<tr><td colspan="5" class="text-center">No classes found</td></tr>`;
        } else {
            classes.forEach((cls) => {
                // Convert section_ids array to comma-separated section names
                let sectionNames = "-";
                let sectionIds = [];
                if (cls.section_ids && cls.section_ids.length > 0) {
                    // If sections are already loaded with the class data
                    if (cls.sections && cls.sections.length > 0) {
                        sectionNames = cls.sections
                            .map((s) => s.name)
                            .join(", ");
                        sectionIds = cls.sections.map((s) => s.id);
                    } else {
                        // Fallback to section IDs if names are not available
                        sectionNames = cls.section_ids.join(", ");
                        sectionIds = cls.section_ids;
                    }
                }

                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(
                                        cls.name
                                    )}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(
                                        sectionNames
                                    )}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${
                                        cls.student_count || 0
                                    }</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <select class="form-select class-status-select" data-class-id="${
                                    cls.id
                                }">
                                    <option value="1" ${
                                        cls.status == 1 ? "selected" : ""
                                    }>Active</option>
                                    <option value="0" ${
                                        cls.status == 0 ? "selected" : ""
                                    }>Inactive</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 edit-class"
                                   data-class-id="${
                                       cls.id
                                   }" data-class-name="${escapeHtml(cls.name)}"
                                   data-institution-id="${cls.institution_id}"
                                   data-section-ids='${JSON.stringify(
                                       sectionIds
                                   )}'
                                   data-status="${cls.status}">
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
            $(".class-status-select").select2({
                minimumResultsForSearch: Infinity, // Disable search
                width: "100%", // Changed from "auto" to "100%"
            });
        }
    }

    // Function to add event listeners for status changes
    function addStatusChangeListeners() {
        // Remove any existing listeners to prevent conflicts
        $(".class-status-select").off("change.schoolclass");

        $(".class-status-select").on("change.schoolclass", function () {
            const classId = $(this).data("class-id");
            const newStatus = $(this).val();

            // Update status via AJAX
            updateClassStatus(classId, newStatus);
        });
    }

    // Function to update class status
    function updateClassStatus(classId, status) {
        $.ajax({
            url: `/admin/classes/${classId}/status`,
            type: "POST",
            data: {
                status: status,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    toastr.success("Status updated successfully");
                    // Refresh the class list to show updated data
                    setTimeout(function () {
                        refreshClassList();
                    }, 1000);
                } else {
                    toastr.error(response.message || "Failed to update status");
                }
            },
            error: function (xhr, status, error) {
                toastr.error("Error updating status");
                // Revert the select to previous value
                refreshClassList();
            },
        });
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
        const map = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#039;",
        };
        return text.replace(/[&<>"']/g, function (m) {
            return map[m];
        });
    }
});
