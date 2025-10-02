$(document).ready(function () {
    let seatmap = [];
    let seatCounter = 1;
    let isDragging = false;
    let dragElement = null;
    let isAddMode = false;
    let isRemoveMode = false;
    let currentLayout = "grid";

    // Initialize the seatmap system
    initializeSeatmap();

    // Event listeners
    $("#generate-seatmap").on("click", generateSeatmap);
    $("#apply-layout").on("click", applyLayout);
    $("#clear-seats").on("click", clearAllSeats);
    $("#add-seat").on("click", toggleAddMode);
    $("#remove-seat").on("click", toggleRemoveMode);
    $("#save-room").on("click", saveRoom);
    $("#layout-type").on("change", updateLayoutControls);
    $("#capacity").on("input", updateCapacity);

    // Layout controls
    $("#rows, #columns").on("input", function () {
        if ($("#capacity").val()) {
            updateCapacity();
        }
    });

    function initializeSeatmap() {
        // Initialize drag and drop functionality
        $(document).on("mousedown", ".seat", startDrag);
        $(document).on("mousemove", handleDrag);
        $(document).on("mouseup", endDrag);

        // Prevent text selection during drag
        $(document).on("selectstart", ".seat", function (e) {
            e.preventDefault();
        });
    }

    function generateSeatmap() {
        const capacity = parseInt($("#capacity").val());
        const roomNo = $("#room_no").val();

        if (!capacity || capacity < 1) {
            alert("Please enter a valid capacity");
            return;
        }

        if (!roomNo) {
            alert("Please enter a room number");
            return;
        }

        // Calculate optimal grid dimensions
        const rows =
            parseInt($("#rows").val()) || Math.ceil(Math.sqrt(capacity));
        const columns =
            parseInt($("#columns").val()) || Math.ceil(capacity / rows);

        // Adjust if capacity doesn't match
        if (rows * columns < capacity) {
            $("#columns").val(Math.ceil(capacity / rows));
        }

        createSeatmap(rows, columns, capacity);
        $("#seatmap-controls").removeClass("d-none");
        $("#save-room").removeClass("d-none");
    }

    function createSeatmap(rows, columns, capacity) {
        const container = $("#seatmap-container");
        container.empty();

        seatmap = [];
        seatCounter = 1;

        // Add teacher desk
        container.append('<div class="teacher-desk"></div>');

        // Create seatmap based on layout type
        const layoutType = $("#layout-type").val();

        switch (layoutType) {
            case "grid":
                createGridLayout(container, rows, columns, capacity);
                break;
            case "theater":
                createTheaterLayout(container, rows, columns, capacity);
                break;
            case "u-shape":
                createUShapeLayout(container, rows, columns, capacity);
                break;
            default:
                createGridLayout(container, rows, columns, capacity);
        }

        // Add legend
        addLegend(container);

        currentLayout = layoutType;
    }

    function createGridLayout(container, rows, columns, capacity) {
        const gridContainer = $('<div class="seatmap-grid"></div>');

        for (let row = 0; row < rows && seatCounter <= capacity; row++) {
            const rowDiv = $('<div class="seatmap-row"></div>');

            for (let col = 0; col < columns && seatCounter <= capacity; col++) {
                const seat = createSeat(seatCounter, row, col);
                rowDiv.append(seat);
                seatmap.push({
                    id: seatCounter,
                    row: row,
                    col: col,
                    x: col * 45,
                    y: row * 45 + 50, // Offset for teacher desk
                    status: "available",
                });
                seatCounter++;
            }

            gridContainer.append(rowDiv);
        }

        container.append(gridContainer);
    }

    function createTheaterLayout(container, rows, columns, capacity) {
        const theaterContainer = $('<div class="seatmap-theater"></div>');

        for (let row = 0; row < rows && seatCounter <= capacity; row++) {
            const rowDiv = $('<div class="seatmap-row"></div>');
            const seatsInRow = Math.min(
                columns + (row % 2),
                capacity - seatCounter + 1
            );

            for (
                let col = 0;
                col < seatsInRow && seatCounter <= capacity;
                col++
            ) {
                const seat = createSeat(seatCounter, row, col);
                rowDiv.append(seat);
                seatmap.push({
                    id: seatCounter,
                    row: row,
                    col: col,
                    x: col * 45 + (row % 2) * 20,
                    y: row * 45 + 50,
                    status: "available",
                });
                seatCounter++;
            }

            theaterContainer.append(rowDiv);
        }

        container.append(theaterContainer);
    }

    function createUShapeLayout(container, rows, columns, capacity) {
        const uContainer = $('<div class="seatmap-u-shape"></div>');

        // Top row (full width)
        const topRow = $('<div class="seatmap-row"></div>');
        const topSeats = Math.min(columns, Math.floor(capacity / 3));
        for (let col = 0; col < topSeats && seatCounter <= capacity; col++) {
            const seat = createSeat(seatCounter, 0, col);
            topRow.append(seat);
            seatmap.push({
                id: seatCounter,
                row: 0,
                col: col,
                x: col * 45,
                y: 50,
                status: "available",
            });
            seatCounter++;
        }
        uContainer.append(topRow);

        // Middle rows (centered)
        for (let row = 1; row < rows - 1 && seatCounter <= capacity; row++) {
            const rowDiv = $('<div class="seatmap-row"></div>');
            const middleSeats = Math.min(
                Math.floor(columns / 2),
                capacity - seatCounter + 1
            );

            for (
                let col = 0;
                col < middleSeats && seatCounter <= capacity;
                col++
            ) {
                const seat = createSeat(seatCounter, row, col);
                rowDiv.append(seat);
                seatmap.push({
                    id: seatCounter,
                    row: row,
                    col: col,
                    x: col * 45 + 100,
                    y: row * 45 + 50,
                    status: "available",
                });
                seatCounter++;
            }

            uContainer.append(rowDiv);
        }

        // Bottom row (full width)
        if (seatCounter <= capacity) {
            const bottomRow = $('<div class="seatmap-row"></div>');
            const bottomSeats = Math.min(columns, capacity - seatCounter + 1);
            for (
                let col = 0;
                col < bottomSeats && seatCounter <= capacity;
                col++
            ) {
                const seat = createSeat(seatCounter, rows - 1, col);
                bottomRow.append(seat);
                seatmap.push({
                    id: seatCounter,
                    row: rows - 1,
                    col: col,
                    x: col * 45,
                    y: (rows - 1) * 45 + 50,
                    status: "available",
                });
                seatCounter++;
            }
            uContainer.append(bottomRow);
        }

        container.append(uContainer);
    }

    function createSeat(id, row, col) {
        const seat = $(`
            <div class="seat available" data-seat-id="${id}" data-row="${row}" data-col="${col}">
                <span class="seat-number">${id}</span>
            </div>
        `);

        seat.on("click", function (e) {
            e.preventDefault();
            if (isAddMode) {
                addSeatAtPosition(e);
            } else if (isRemoveMode) {
                removeSeat($(this));
            } else {
                toggleSeatSelection($(this));
            }
        });

        return seat;
    }

    function addSeatAtPosition(e) {
        const container = $("#seatmap-container");
        const rect = container[0].getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const seat = $(`
            <div class="seat available" data-seat-id="${seatCounter}" style="position: absolute; left: ${x}px; top: ${y}px;">
                <span class="seat-number">${seatCounter}</span>
            </div>
        `);

        seat.on("click", function (e) {
            e.preventDefault();
            if (isRemoveMode) {
                removeSeat($(this));
            } else {
                toggleSeatSelection($(this));
            }
        });

        container.append(seat);

        seatmap.push({
            id: seatCounter,
            row: Math.floor(y / 45),
            col: Math.floor(x / 45),
            x: x,
            y: y,
            status: "available",
        });

        seatCounter++;
    }

    function removeSeat(seatElement) {
        const seatId = parseInt(seatElement.data("seat-id"));
        seatmap = seatmap.filter((seat) => seat.id !== seatId);
        seatElement.remove();
    }

    function toggleSeatSelection(seatElement) {
        seatElement.toggleClass("selected");
        const seatId = parseInt(seatElement.data("seat-id"));
        const seat = seatmap.find((s) => s.id === seatId);
        if (seat) {
            seat.status = seatElement.hasClass("selected")
                ? "selected"
                : "available";
        }
    }

    function startDrag(e) {
        if (isAddMode || isRemoveMode) return;

        isDragging = true;
        dragElement = $(e.target).closest(".seat");
        dragElement.addClass("dragging");

        e.preventDefault();
    }

    function handleDrag(e) {
        if (!isDragging || !dragElement) return;

        const container = $("#seatmap-container");
        const rect = container[0].getBoundingClientRect();
        const x = e.clientX - rect.left - 20; // Center the seat
        const y = e.clientY - rect.top - 20;

        dragElement.css({
            position: "absolute",
            left: x + "px",
            top: y + "px",
            zIndex: 1000,
        });
    }

    function endDrag(e) {
        if (!isDragging || !dragElement) return;

        isDragging = false;
        dragElement.removeClass("dragging");

        // Update seatmap data
        const seatId = parseInt(dragElement.data("seat-id"));
        const seat = seatmap.find((s) => s.id === seatId);
        if (seat) {
            seat.x = parseInt(dragElement.css("left"));
            seat.y = parseInt(dragElement.css("top"));
        }

        dragElement = null;
    }

    function toggleAddMode() {
        isAddMode = !isAddMode;
        isRemoveMode = false;

        $("#add-seat").toggleClass("btn-primary btn-outline-primary");
        $("#remove-seat")
            .removeClass("btn-danger")
            .addClass("btn-outline-danger");

        if (isAddMode) {
            $("#seatmap-container").css("cursor", "crosshair");
        } else {
            $("#seatmap-container").css("cursor", "default");
        }
    }

    function toggleRemoveMode() {
        isRemoveMode = !isRemoveMode;
        isAddMode = false;

        $("#remove-seat").toggleClass("btn-danger btn-outline-danger");
        $("#add-seat")
            .removeClass("btn-primary")
            .addClass("btn-outline-primary");

        if (isRemoveMode) {
            $("#seatmap-container").css("cursor", "not-allowed");
        } else {
            $("#seatmap-container").css("cursor", "default");
        }
    }

    function applyLayout() {
        const capacity = parseInt($("#capacity").val());
        if (!capacity) {
            alert("Please enter capacity first");
            return;
        }

        generateSeatmap();
    }

    function clearAllSeats() {
        if (confirm("Are you sure you want to clear all seats?")) {
            $("#seatmap-container").empty();
            seatmap = [];
            seatCounter = 1;
        }
    }

    function updateLayoutControls() {
        const layoutType = $("#layout-type").val();

        if (layoutType === "custom") {
            $("#rows, #columns").prop("disabled", true);
        } else {
            $("#rows, #columns").prop("disabled", false);
        }
    }

    function updateCapacity() {
        const capacity = parseInt($("#capacity").val());
        if (capacity) {
            const rows =
                parseInt($("#rows").val()) || Math.ceil(Math.sqrt(capacity));
            const columns = Math.ceil(capacity / rows);
            $("#columns").val(columns);
        }
    }

    function addLegend(container) {
        const legend = $(`
            <div class="seatmap-legend">
                <div class="legend-item">
                    <div class="legend-color available"></div>
                    <span>Available</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color occupied"></div>
                    <span>Occupied</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color selected"></div>
                    <span>Selected</span>
                </div>
            </div>
        `);
        container.append(legend);
    }

    function saveRoom() {
        const roomNo = $("#room_no").val();
        const roomName = $("#room_name").val();
        const capacity = parseInt($("#capacity").val());
        const status = $("#room_status").is(":checked") ? 1 : 0;

        if (!roomNo || !capacity) {
            alert("Please fill in all required fields");
            return;
        }

        if (seatmap.length === 0) {
            alert("Please create a seatmap first");
            return;
        }

        // Show loading modal
        $("#loadingModal").modal("show");

        const formData = {
            room_no: roomNo,
            room_name: roomName,
            capacity: capacity,
            status: status,
            seatmap: seatmap,
            _token: $('meta[name="csrf-token"]').attr("content"),
        };

        $.ajax({
            url: '{{ route("admin.rooms.store-with-seatmap") }}',
            type: "POST",
            data: formData,
            success: function (response) {
                $("#loadingModal").modal("hide");

                if (response.success) {
                    // Show success message
                    showToast("Room created successfully!", "success");

                    // Redirect to rooms index after a short delay
                    setTimeout(function () {
                        window.location.href =
                            '{{ route("admin.rooms.index") }}';
                    }, 1500);
                } else {
                    showToast(
                        "Error creating room: " + response.message,
                        "error"
                    );
                }
            },
            error: function (xhr) {
                $("#loadingModal").modal("hide");

                let errorMessage = "An error occurred while creating the room";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join(", ");
                }

                showToast(errorMessage, "error");
            },
        });
    }

    function showToast(message, type) {
        const toastClass =
            type === "success" ? "text-bg-success" : "text-bg-danger";
        const toast = $(`
            <div class="toast align-items-center ${toastClass} border-0 show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 1060;">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);

        $("body").append(toast);

        // Auto remove after 5 seconds
        setTimeout(function () {
            toast.remove();
        }, 5000);
    }

    // Initialize layout controls
    updateLayoutControls();
});
