// Room Management JavaScript

document.addEventListener("DOMContentLoaded", function () {
    initializeRoomManagement();
});

function initializeRoomManagement() {
    // Handle create room form submission
    const createRoomForm = document.getElementById("createRoomForm");
    if (createRoomForm) {
        createRoomForm.addEventListener("submit", handleCreateRoom);
    }

    // Initialize DataTable with error handling
    initializeDataTable();
}

function initializeDataTable() {
    // Check if jQuery and DataTables are available
    if (typeof $ === "undefined" || typeof $.fn.DataTable === "undefined") {
        console.log(
            "jQuery or DataTables not available - using basic table styling"
        );
        return;
    }

    const table = document.getElementById("roomsTable");
    if (!table) {
        console.log("Table not found");
        return;
    }

    // Check if DataTable is already initialized
    if ($.fn.DataTable.isDataTable("#roomsTable")) {
        $("#roomsTable").DataTable().destroy();
    }

    // Check if table has proper structure
    const headerCells = table.querySelectorAll("thead th");
    const tbody = table.querySelector("tbody");
    const rows = tbody ? tbody.querySelectorAll("tr") : [];

    // Only initialize if we have a proper table structure
    if (headerCells.length === 0) {
        console.log("No header cells found");
        return;
    }

    // Check if we have data rows (not just empty state)
    const hasDataRows =
        rows.length > 0 && !rows[0].querySelector("td[colspan]");

    try {
        if (hasDataRows) {
            // Initialize DataTable with proper configuration
            $("#roomsTable").DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, "asc"]],
                columnDefs: [
                    { orderable: false, targets: -1 }, // Disable sorting on Actions column
                ],
                language: {
                    emptyTable: "No rooms found",
                    zeroRecords: "No matching rooms found",
                },
                drawCallback: function (settings) {
                    // Re-initialize any event handlers after table redraw
                    initializeTableEventHandlers();
                },
            });
            console.log("DataTable initialized successfully");
        } else {
            console.log(
                "No data rows found - skipping DataTable initialization"
            );
        }
    } catch (error) {
        console.error("Error initializing DataTable:", error);
        // Fallback: just make the table responsive without DataTables
        table.classList.add("table-responsive");
    }
}

function initializeTableEventHandlers() {
    // Re-initialize any event handlers that might have been lost during DataTable redraw
    // This is called after each table redraw
}

function refreshDataTable() {
    if ($.fn.DataTable.isDataTable("#roomsTable")) {
        $("#roomsTable").DataTable().ajax.reload();
    } else {
        initializeDataTable();
    }
}

function handleCreateRoom(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const designLayoutNow = formData.get("design_layout_now") === "on";

    // Remove the checkbox from form data as it's not needed for the API
    formData.delete("design_layout_now");

    // Show loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="ti ti-loader me-1"></i>Creating...';
    submitBtn.disabled = true;

    fetch("/institution/exam-management/rooms/store", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast("Room created successfully!", "success");

                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("createRoomModal")
                );
                modal.hide();

                // Reset form
                event.target.reset();

                // Redirect to layout design if requested
                if (designLayoutNow && data.data && data.data.id) {
                    setTimeout(() => {
                        window.location.href = `/institution/exam-management/rooms/${data.data.id}/design-layout`;
                    }, 1000);
                } else {
                    // Reload page to show new room
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            } else {
                showToast(data.message || "Error creating room", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Error creating room", "error");
        })
        .finally(() => {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
}

function viewRoom(roomId) {
    // Open room details in a modal or new page
    window.open(`/institution/exam-management/rooms/${roomId}`, "_blank");
}

function designLayout(roomId) {
    // Navigate to layout design page
    window.location.href = `/institution/exam-management/rooms/${roomId}/design-layout`;
}

function editRoom(roomId) {
    // Open edit modal or navigate to edit page
    fetch(`/institution/exam-management/rooms/${roomId}/edit`)
        .then((response) => response.text())
        .then((html) => {
            // Create modal with edit form
            const modalHtml = `
            <div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editRoomModalLabel">Edit Room</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ${html}
                        </div>
                    </div>
                </div>
            </div>
        `;

            // Remove existing modal if any
            const existingModal = document.getElementById("editRoomModal");
            if (existingModal) {
                existingModal.remove();
            }

            // Add new modal to body
            document.body.insertAdjacentHTML("beforeend", modalHtml);

            // Show modal
            const modal = new bootstrap.Modal(
                document.getElementById("editRoomModal")
            );
            modal.show();

            // Handle form submission
            const editForm = document
                .getElementById("editRoomModal")
                .querySelector("form");
            if (editForm) {
                editForm.addEventListener("submit", function (e) {
                    e.preventDefault();
                    handleEditRoom(roomId, e);
                });
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Error loading edit form", "error");
        });
}

function handleEditRoom(roomId, event) {
    const formData = new FormData(event.target);

    // Show loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="ti ti-loader me-1"></i>Updating...';
    submitBtn.disabled = true;

    fetch(`/institution/exam-management/rooms/${roomId}/update`, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast("Room updated successfully!", "success");

                // Close modal
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById("editRoomModal")
                );
                modal.hide();

                // Reload page
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showToast(data.message || "Error updating room", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Error updating room", "error");
        })
        .finally(() => {
            // Reset button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
}

function deleteRoom(roomId) {
    if (
        confirm(
            "Are you sure you want to delete this room? This action cannot be undone."
        )
    ) {
        fetch(`/institution/exam-management/rooms/${roomId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    showToast("Room deleted successfully!", "success");
                    // Reload page
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || "Error deleting room", "error");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                showToast("Error deleting room", "error");
            });
    }
}

function showToast(message, type) {
    // Create toast container if it doesn't exist
    let container = document.querySelector(".toast-container");
    if (!container) {
        container = document.createElement("div");
        container.className = "toast-container position-fixed top-0 end-0 p-3";
        container.style.zIndex = "1050";
        document.body.appendChild(container);
    }

    // Create toast
    const toastId = "toast-" + Date.now();
    const toast = document.createElement("div");
    toast.id = toastId;
    toast.className = `toast align-items-center text-bg-${
        type === "success" ? "success" : "danger"
    } border-0`;
    toast.setAttribute("role", "alert");
    toast.setAttribute("aria-live", "assertive");
    toast.setAttribute("aria-atomic", "true");

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    container.appendChild(toast);

    // Initialize and show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    // Remove toast element after it's hidden
    toast.addEventListener("hidden.bs.toast", function () {
        toast.remove();
    });
}

// Utility functions for room management
function formatRoomCapacity(capacity) {
    return capacity + " students";
}

function getRoomStatusBadge(status) {
    return status
        ? '<span class="badge bg-success">Active</span>'
        : '<span class="badge bg-danger">Inactive</span>';
}

// Export functions for global access
window.viewRoom = viewRoom;
window.designLayout = designLayout;
window.editRoom = editRoom;
window.deleteRoom = deleteRoom;
