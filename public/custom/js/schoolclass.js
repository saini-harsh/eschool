$(document).ready(function () {
    // Add class
    $("#add-class").on("click", function (e) {
        e.preventDefault();
        const form = $(this).closest("form");
        const formData = new FormData(form[0]);

        $.ajax({
            url: "/admin/classes/store",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    form[0].reset();
                    refreshClassList();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                toastr.error("Error occurred");
            }
        });
    });

    function refreshClassList() {
        $.ajax({
            url: "/admin/classes/list",
            type: "GET",
            success: function (response) {
                if (response.success) {
                    updateClassTable(response.data);
                }
            }
        });
    }

    function updateClassTable(classes) {
        const tbody = $(".datatable tbody");
        let html = "";
        if (classes.length === 0) {
            html = `<tr><td colspan="4" class="text-center">No classes found</td></tr>`;
        } else {
            classes.forEach(cls => {
                // Convert section_ids array to comma-separated section names
                let sectionNames = "-";
                if (cls.section_ids && cls.section_ids.length > 0) {
                    sectionNames = cls.sections ? cls.sections.map(s => s.name).join(", ") : cls.section_ids.join(", ");
                }

                html += `
                    <tr>
                        <td>${cls.name}</td>
                        <td>${sectionNames}</td>
                        <td>
                            <select class="form-select status-select" data-class-id="${cls.id}">
                                <option value="1" ${cls.status == 1 ? 'selected' : ''}>Active</option>
                                <option value="0" ${cls.status == 0 ? 'selected' : ''}>Inactive</option>
                            </select>
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-secondary"><i class="ti ti-edit"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></a>
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
            url: `/admin/classes/${sectionId}/status`,
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
});
