$(document).ready(function () {
    // Prevent multiple initializations
    if (window.classRoomInitialized) {
        return;
    }
    window.classRoomInitialized = true;

    // Add classroom - unbind existing events first
    $("#add-class-room").off("click.classroom").on("click.classroom", function (e) {
        e.preventDefault();
        
        // Prevent multiple submissions
        if ($(this).data('submitting')) {
            return false;
        }
        
        const form = $(this).closest("form");
        const formData = new FormData(form[0]);

        // Handle checkbox status
        const statusCheckbox = form.find('input[name="status"]');
        formData.set('status', statusCheckbox.is(':checked') ? '1' : '0');

        const submitBtn = $(this);
        const originalText = submitBtn.text();
        
        // Mark as submitting
        submitBtn.data('submitting', true);
        submitBtn.prop("disabled", true).text("Saving...");

        console.log('Creating classroom...');
        $.ajax({
            url: "/admin/rooms/store",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: { "X-CSRF-TOKEN": $('input[name="_token"]').val() },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.success);
                    resetForm();
                    refreshClassRoomList();
                } else {
                    toastr.error(response.message || "Something went wrong");
                }
            },
            error: function (xhr) {
                let errorMessage = "An error occurred while creating the classroom";
                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors)[0][0];
                }
                toastr.error(errorMessage);
            },
            complete: function () {
                // Reset form state
                submitBtn.data('submitting', false);
                submitBtn.prop("disabled", false).text(originalText);
            }
        });
    });

    // Edit classroom
    $(document).on("click", ".edit-class-room", function (e) {
        e.preventDefault();
        const id = $(this).data("id");
        const roomNo = $(this).data("room-no");
        const capacity = $(this).data("capacity");
        const status = $(this).data("status");

        $("#class_room_id").val(id);
        $("#class_room_no").val(roomNo);
        $("#class_room_capacity").val(capacity);
        $("#class_room_status").prop("checked", status == 1);

        $("#add-class-room").addClass("d-none");
        $("#update-class_room").removeClass("d-none");
        $("#cancel-edit").removeClass("d-none");
        $('html, body').animate({ scrollTop: $("#class_room-form").offset().top - 100 }, 500);
    });

    // Update classroom - unbind existing events first
    $("#update-class_room").off("click.classroom").on("click.classroom", function (e) {
        e.preventDefault();
        const form = $(this).closest("form");
        const formData = new FormData(form[0]);
        const id = $("#class_room_id").val();

        // Handle checkbox status
        const statusCheckbox = form.find('input[name="status"]');
        formData.set('status', statusCheckbox.is(':checked') ? '1' : '0');

        const submitBtn = $(this);
        const originalText = submitBtn.text();
        submitBtn.prop("disabled", true).text("Updating...");

        $.ajax({
            url: `/admin/classrooms/update/${id}`,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: { "X-CSRF-TOKEN": $('input[name="_token"]').val() },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.success);
                    resetForm();
                    refreshClassRoomList();
                } else {
                    toastr.error(response.message || "Something went wrong");
                }
            },
            error: function (xhr) {
                let errorMessage = "An error occurred while updating the classroom";
                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors)[0][0];
                }
                toastr.error(errorMessage);
            },
            complete: function () {
                submitBtn.prop("disabled", false).text(originalText);
            }
        });
    });

    // Cancel edit - unbind existing events first
    $("#cancel-edit").off("click.classroom").on("click.classroom", function (e) {
        e.preventDefault();
        resetForm();
    });

    // Delete classroom
    $(document).on("click", ".delete-class-room", function (e) {
        e.preventDefault();
        const id = $(this).data("id");
        const roomNo = $(this).data("room-no");

        $("#delete_modal .modal-body h6").text("Delete Classroom");
        $("#delete_modal .modal-body p").text(`Are you sure you want to delete room "${roomNo}"?`);
        $("#deleteForm").attr("action", `/admin/classrooms/delete/${id}`);

        const deleteModal = new bootstrap.Modal(document.getElementById('delete_modal'));
        deleteModal.show();
    });

    // Handle delete form submission - unbind existing events first
    $("#deleteForm").off("submit.classroom").on("submit.classroom", function (e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.prop("disabled", true).text("Deleting...");

        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: form.serialize(),
            headers: { "X-CSRF-TOKEN": $('input[name="_token"]').val() },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.success || "Classroom deleted successfully");
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('delete_modal'));
                    deleteModal.hide();
                    refreshClassRoomList();
                } else {
                    toastr.error(response.message || "Failed to delete classroom");
                }
            },
            error: function (xhr) {
                let errorMessage = "An error occurred while deleting the classroom";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function () {
                submitBtn.prop("disabled", false).text(originalText);
            }
        });
    });

    // Status change - unbind existing events first
    $(document).off("change.classroom", ".class-room-status-select").on("change.classroom", ".class-room-status-select", function () {
        const id = $(this).data("id");
        const status = $(this).val();

        $.ajax({
            url: `/admin/classrooms/${id}/status`,
            type: "POST",
            data: {
                status: status,
                _token: $('input[name="_token"]').val(),
            },
            success: function (response) {
                if (response.success) {
                    toastr.success("Status updated successfully");
                    setTimeout(refreshClassRoomList, 1000);
                } else {
                    toastr.error(response.message || "Failed to update status");
                }
            },
            error: function () {
                toastr.error("Error updating status");
                refreshClassRoomList();
            },
        });
    });

    // Reset form to add mode
    function resetForm() {
        const form = $("#class_room_form")[0];
        form.reset();
        $("#class_room_status").prop("checked", true);
        $("#class_room_id").val("");
        $("#add-class-room").removeClass("d-none");
        $("#update-class_room").addClass("d-none");
        $("#cancel-edit").addClass("d-none");
    }

    // Refresh classroom list
    function refreshClassRoomList() {
        $.ajax({
            url: "/admin/classrooms/list",
            type: "GET",
            success: function (response) {
                if (response.success) {
                    updateClassRoomTable(response.data);
                }
            },
            error: function () {
                toastr.error("Failed to refresh classroom list");
            }
        });
    }

    // Update classroom table
    function updateClassRoomTable(classrooms) {
        const tbody = $(".datatable tbody");
        let html = "";

        if (!classrooms || classrooms.length === 0) {
            html = `<tr><td colspan="3" class="text-center">No classrooms found</td></tr>`;
        } else {
            classrooms.forEach(room => {
                html += `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0">${escapeHtml(room.room_no)}</h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <select class="form-select class-room-status-select" data-id="${room.id}">
                                    <option value="1" ${room.status == 1 ? 'selected' : ''}>Active</option>
                                    <option value="0" ${room.status == 0 ? 'selected' : ''}>Inactive</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 edit-class-room"
                                   data-id="${room.id}" data-room-no="${escapeHtml(room.room_no)}"
                                   data-capacity="${room.capacity}" data-status="${room.status}">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 delete-class-room"
                                   data-id="${room.id}" data-room-no="${escapeHtml(room.room_no)}">
                                    <i class="ti ti-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        tbody.html(html);
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
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
