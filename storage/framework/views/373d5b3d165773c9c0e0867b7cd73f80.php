<?php $__env->startSection('title', 'Institution | Room Layout Design'); ?>

<?php $__env->startPush('head'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(session('success')): ?>
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo e(session('success')); ?>

                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Room Layout Design - <?php echo e($classRoom->room_no); ?></h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="<?php echo e(route('institution.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('institution.exam-management.rooms.index')); ?>">Class
                                Rooms</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Layout Design</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </button>
                <button type="button" class="btn btn-success" onclick="saveLayout()">
                    <i class="ti ti-device-floppy me-1"></i>Save Layout
                </button>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Layout Design Section -->
        <div class="row">
            <!-- Controls Panel -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Layout Controls</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Room Capacity: <?php echo e($classRoom->capacity); ?></label>
                        </div>
                        <div class="mb-3">
                            <label for="studentsPerBench" class="form-label">Students per Bench</label>
                            <select class="form-control" id="studentsPerBench" onchange="updateStudentsPerBench()">
                                <option value="1" <?php echo e(($classRoom->students_per_bench ?? 1) == 1 ? 'selected' : ''); ?>>1
                                    Student</option>
                                <option value="2" <?php echo e(($classRoom->students_per_bench ?? 1) == 2 ? 'selected' : ''); ?>>2
                                    Students</option>
                                <option value="3" <?php echo e(($classRoom->students_per_bench ?? 1) == 3 ? 'selected' : ''); ?>>3
                                    Students</option>
                                <option value="4" <?php echo e(($classRoom->students_per_bench ?? 1) == 4 ? 'selected' : ''); ?>>4
                                    Students</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Layout Tools</label>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addRow()">
                                    <i class="ti ti-plus me-1"></i>Add Row
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addColumn()">
                                    <i class="ti ti-plus me-1"></i>Add Column
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="removeRow()">
                                    <i class="ti ti-minus me-1"></i>Remove Row
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" onclick="removeColumn()">
                                    <i class="ti ti-minus me-1"></i>Remove Column
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Seat Actions</label>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="setSeatMode('add')">
                                    <i class="ti ti-plus me-1"></i>Add Seats
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    onclick="setSeatMode('remove')">
                                    <i class="ti ti-minus me-1"></i>Remove Seats
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    onclick="setSeatMode('move')">
                                    <i class="ti ti-arrows-move me-1"></i>Move Seats
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Aisle Actions</label>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="setSeatMode('aisle')">
                                    <i class="ti ti-separator me-1"></i>Add Aisle
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm"
                                    onclick="setSeatMode('clear')">
                                    <i class="ti ti-x me-1"></i>Clear Cell
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-info btn-sm w-100" onclick="clearLayout()">
                                <i class="ti ti-trash me-1"></i>Clear All
                            </button>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="testLayout()">
                                <i class="ti ti-test me-1"></i>Test Layout
                            </button>
                        </div>
                        <div class="mb-3">
                            <div class="alert alert-info">
                                <small>
                                    <strong>Instructions:</strong><br>
                                    • Click on grid to add/remove seats<br>
                                    • Use "Add Aisle" to create walkways<br>
                                    • Use "Clear Cell" to remove any element<br>
                                    • Drag to move seats<br>
                                    • Use controls to adjust layout
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Layout Canvas -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Room Layout Canvas</h6>
                        <div>
                            <span class="badge bg-primary me-2">Total Seats: <span id="totalSeats">0</span></span>
                            <span class="badge bg-success">Capacity: <?php echo e($classRoom->capacity); ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="layout-container" style="overflow: auto; max-height: 600px;">
                            <div id="roomLayout" class="room-layout">
                                <!-- Layout will be generated here -->
                            </div>

                            <!-- Fallback static layout for testing -->
                            <div id="fallbackLayout" class="room-layout" style="display: none;">
                                <div class="layout-row">
                                    <div class="seat" data-row="0" data-col="0"><span class="seat-number">1</span>
                                    </div>
                                    <div class="seat" data-row="0" data-col="1"><span class="seat-number">2</span>
                                    </div>
                                    <div class="seat" data-row="0" data-col="2"><span class="seat-number">3</span>
                                    </div>
                                    <div class="seat" data-row="0" data-col="3"><span class="seat-number">4</span>
                                    </div>
                                    <div class="seat" data-row="0" data-col="4"><span class="seat-number">5</span>
                                    </div>
                                </div>
                                <div class="layout-row">
                                    <div class="seat" data-row="1" data-col="0"><span class="seat-number">6</span>
                                    </div>
                                    <div class="seat" data-row="1" data-col="1"><span class="seat-number">7</span>
                                    </div>
                                    <div class="seat" data-row="1" data-col="2"><span class="seat-number">8</span>
                                    </div>
                                    <div class="seat" data-row="1" data-col="3"><span class="seat-number">9</span>
                                    </div>
                                    <div class="seat" data-row="1" data-col="4"><span
                                            class="seat-number">10</span></div>
                                </div>
                                <div class="layout-row">
                                    <div class="seat" data-row="2" data-col="0"><span
                                            class="seat-number">11</span></div>
                                    <div class="seat" data-row="2" data-col="1"><span
                                            class="seat-number">12</span></div>
                                    <div class="seat" data-row="2" data-col="2"><span
                                            class="seat-number">13</span></div>
                                    <div class="seat" data-row="2" data-col="3"><span
                                            class="seat-number">14</span></div>
                                    <div class="seat" data-row="2" data-col="4"><span
                                            class="seat-number">15</span></div>
                                </div>
                            </div>

                            <!-- Emergency show button -->
                            <div class="mt-3">
                                <button type="button" class="btn btn-warning btn-sm" onclick="showFallbackLayout()">
                                    <i class="ti ti-eye me-1"></i>Show Sample Layout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .room-layout {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            min-height: 400px;
            border: 2px solid #dee2e6;
        }

        .seat {
            width: 50px;
            height: 50px;
            border: 3px solid #007bff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #ffffff;
            position: relative;
            margin: 3px;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }

        .seat.occupied {
            background: #28a745;
            border-color: #1e7e34;
            color: white;
        }

        .seat.occupied:hover {
            background: #218838;
        }

        .seat:hover {
            border-color: #007bff;
            transform: scale(1.05);
        }

        .seat.dragging {
            opacity: 0.5;
            transform: scale(1.1);
        }

        .seat-number {
            font-size: 10px;
            font-weight: bold;
        }

        .teacher-desk {
            background: #6c757d;
            border-color: #495057;
            color: white;
        }

        .teacher-desk::after {
            content: "T";
            font-weight: bold;
        }

        .layout-row {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: nowrap;
            padding: 5px;
            background: rgba(0, 123, 255, 0.05);
            border-radius: 5px;
        }

        .aisle {
            width: 20px;
            height: 40px;
            background: transparent;
        }

        .controls-panel {
            position: sticky;
            top: 20px;
        }

        .seat-mode-active {
            background: #007bff !important;
            color: white !important;
        }

        /* Debug styles to ensure visibility */
        .room-layout * {
            box-sizing: border-box;
        }

        .seat-number {
            font-size: 12px;
            font-weight: bold;
            color: inherit;
        }

        /* Force visibility */
        .seat {
            visibility: visible !important;
            opacity: 1 !important;
            display: flex !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        let currentSeatMode = 'add';
        let layoutData = [];
        let studentsPerBench = 1;
        let roomCapacity = 30;
        let roomId = 1;

        // Initialize layout
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== PAGE LOADED - INITIALIZING ROOM LAYOUT ===');

            // Get data from PHP variables
            try {
                layoutData = <?php echo json_encode($classRoom->seatmap ?? [], 15, 512) ?>;
                studentsPerBench = <?php echo e($classRoom->students_per_bench ?? 1); ?>;
                roomCapacity = <?php echo e($classRoom->capacity); ?>;
                roomId = <?php echo e($classRoom->id); ?>;

                console.log('Layout data:', layoutData);
                console.log('Room capacity:', roomCapacity);
                console.log('Students per bench:', studentsPerBench);
                console.log('Room ID:', roomId);
            } catch (error) {
                console.error('Error getting data from PHP:', error);
                // Set defaults
                layoutData = [];
                studentsPerBench = 1;
                roomCapacity = 30;
                roomId = 1;
            }

            // Always create a default layout for now
            console.log('Creating default layout');
            createDefaultLayout();
            updateSeatCount();

            // Set default seat mode
            setSeatMode('add');

            console.log('=== INITIALIZATION COMPLETE ===');
        });

        function createDefaultLayout() {
            console.log('Creating default layout for capacity:', roomCapacity);

            // Create a simple 5x6 grid for testing
            const rows = 5;
            const cols = 6;

            layoutData = [];
            for (let i = 0; i < rows; i++) {
                layoutData[i] = [];
                for (let j = 0; j < cols; j++) {
                    layoutData[i][j] = {
                        type: 'seat',
                        occupied: false,
                        seatNumber: null
                    };
                }
            }
            console.log('Default layout created:', layoutData);
            renderLayout();
        }

        function renderLayout() {
            console.log('=== RENDER LAYOUT START ===');
            console.log('Layout data:', layoutData);
            console.log('Layout data length:', layoutData ? layoutData.length : 'null');

            const container = document.getElementById('roomLayout');
            console.log('Container found:', !!container);

            if (!container) {
                console.error('Room layout container not found!');
                return;
            }

            container.innerHTML = '';
            console.log('Container cleared');

            if (!layoutData || layoutData.length === 0) {
                console.log('No layout data to render');
                return;
            }

            console.log('Starting to render rows...');

            // Use simple HTML approach instead of DOM manipulation
            let html = '';
            layoutData.forEach((row, rowIndex) => {
                console.log(`Rendering row ${rowIndex}:`, row);
                html +=
                    `<div style="display: flex; gap: 10px; margin-bottom: 10px; padding: 5px; background: #f0f8ff; border-radius: 5px;">`;

                row.forEach((seat, colIndex) => {
                    if (seat && seat.type === 'aisle') {
                        html += `<div style="width: 30px; height: 50px; background: #6c757d; border: 2px solid #495057; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; margin: 2px; color: white; font-weight: bold; font-size: 10px;"
                            data-row="${rowIndex}" data-col="${colIndex}" onclick="handleSeatClick(event)" title="Aisle">
                            A
                        </div>`;
                        console.log(`Added aisle at row ${rowIndex}, col ${colIndex}`);
                    } else {
                        const occupiedStyle = seat && seat.occupied ? 'background: #28a745; color: white;' :
                            'background: white; color: #333;';
                        const seatNumber = seat && seat.seatNumber ? seat.seatNumber : '';
                        html += `<div style="width: 50px; height: 50px; border: 3px solid #007bff; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; margin: 2px; font-weight: bold; font-size: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); ${occupiedStyle}"
                            data-row="${rowIndex}" data-col="${colIndex}" onclick="handleSeatClick(event)">
                            ${seatNumber}
                        </div>`;
                        console.log(`Added seat at row ${rowIndex}, col ${colIndex}`);
                    }
                });

                html += '</div>';
            });

            container.innerHTML = html;

            // Debug: Check what was actually added to the DOM
            console.log('Container children count:', container.children.length);
            console.log('Container HTML:', container.innerHTML.substring(0, 200) + '...');

            console.log('=== RENDER LAYOUT COMPLETE ===');
        }

        function handleSeatClick(event) {
            console.log('Seat clicked, mode:', currentSeatMode);
            const row = parseInt(event.target.dataset.row);
            const col = parseInt(event.target.dataset.col);

            console.log('Clicked seat at row:', row, 'col:', col);

            if (currentSeatMode === 'add') {
                if (!layoutData[row][col].occupied) {
                    layoutData[row][col].occupied = true;
                    layoutData[row][col].seatNumber = getNextSeatNumber();
                    layoutData[row][col].type = 'seat';
                    console.log('Added seat at', row, col);
                }
            } else if (currentSeatMode === 'remove') {
                layoutData[row][col].occupied = false;
                layoutData[row][col].seatNumber = null;
                layoutData[row][col].type = 'seat';
                console.log('Removed seat at', row, col);
            } else if (currentSeatMode === 'aisle') {
                layoutData[row][col].type = 'aisle';
                layoutData[row][col].occupied = false;
                layoutData[row][col].seatNumber = null;
                console.log('Added aisle at', row, col);
            } else if (currentSeatMode === 'clear') {
                layoutData[row][col].type = 'seat';
                layoutData[row][col].occupied = false;
                layoutData[row][col].seatNumber = null;
                console.log('Cleared cell at', row, col);
            }

            renderLayout();
            updateSeatCount();
        }

        function handleSeatDragStart(event) {
            if (currentSeatMode === 'move') {
                event.target.classList.add('dragging');
                event.dataTransfer.setData('text/plain', JSON.stringify({
                    row: event.target.dataset.row,
                    col: event.target.dataset.col
                }));
            }
        }

        function handleSeatDragOver(event) {
            event.preventDefault();
        }

        function handleSeatDrop(event) {
            event.preventDefault();
            if (currentSeatMode === 'move') {
                const sourceData = JSON.parse(event.dataTransfer.getData('text/plain'));
                const targetRow = parseInt(event.target.dataset.row);
                const targetCol = parseInt(event.target.dataset.col);

                // Swap seats
                const temp = layoutData[sourceData.row][sourceData.col];
                layoutData[sourceData.row][sourceData.col] = layoutData[targetRow][targetCol];
                layoutData[targetRow][targetCol] = temp;

                renderLayout();
                updateSeatCount();
            }
        }

        function setSeatMode(mode) {
            console.log('Setting seat mode to:', mode);
            currentSeatMode = mode;

            // Update button styles - remove active class from all buttons
            document.querySelectorAll('button').forEach(btn => {
                if (btn.onclick && btn.onclick.toString().includes('setSeatMode')) {
                    btn.classList.remove('seat-mode-active');
                }
            });

            // Find the clicked button and add active class
            const buttons = document.querySelectorAll('button');
            buttons.forEach(btn => {
                if (btn.onclick && btn.onclick.toString().includes(`setSeatMode('${mode}')`)) {
                    btn.classList.add('seat-mode-active');
                    console.log('Activated button for mode:', mode);
                }
            });
        }

        function addRow() {
            console.log('Adding row');
            if (layoutData.length === 0) {
                // If no data, create a basic row
                layoutData = [
                    [{
                        type: 'seat',
                        occupied: false,
                        seatNumber: null
                    }]
                ];
            } else {
                const cols = layoutData[0].length;
                const newRow = [];
                for (let i = 0; i < cols; i++) {
                    newRow.push({
                        type: 'seat',
                        occupied: false,
                        seatNumber: null
                    });
                }
                layoutData.push(newRow);
            }
            renderLayout();
        }

        function addColumn() {
            console.log('Adding column');
            if (layoutData.length === 0) {
                // If no data, create a basic column
                layoutData = [
                    [{
                        type: 'seat',
                        occupied: false,
                        seatNumber: null
                    }]
                ];
            } else {
                layoutData.forEach(row => {
                    row.push({
                        type: 'seat',
                        occupied: false,
                        seatNumber: null
                    });
                });
            }
            renderLayout();
        }

        function removeRow() {
            console.log('Removing row');
            if (layoutData.length > 1) {
                layoutData.pop();
                renderLayout();
                updateSeatCount();
            } else {
                console.log('Cannot remove row - only one row left');
            }
        }

        function removeColumn() {
            console.log('Removing column');
            if (layoutData.length > 0 && layoutData[0] && layoutData[0].length > 1) {
                layoutData.forEach(row => {
                    row.pop();
                });
                renderLayout();
                updateSeatCount();
            } else {
                console.log('Cannot remove column - only one column left or no data');
            }
        }

        function clearLayout() {
            if (confirm('Are you sure you want to clear all seats?')) {
                console.log('Clearing layout');
                if (layoutData && layoutData.length > 0) {
                    layoutData.forEach(row => {
                        if (row && row.length > 0) {
                            row.forEach(seat => {
                                if (seat) {
                                    seat.occupied = false;
                                    seat.seatNumber = null;
                                }
                            });
                        }
                    });
                }
                renderLayout();
                updateSeatCount();
            }
        }

        function updateStudentsPerBench() {
            const selectElement = document.getElementById('studentsPerBench');
            if (selectElement) {
                studentsPerBench = parseInt(selectElement.value);
                console.log('Updated students per bench to:', studentsPerBench);
            } else {
                console.error('Students per bench select element not found');
            }
        }

        function getNextSeatNumber() {
            let maxNumber = 0;
            if (layoutData && layoutData.length > 0) {
                layoutData.forEach(row => {
                    if (row && row.length > 0) {
                        row.forEach(seat => {
                            if (seat && seat.seatNumber && seat.seatNumber > maxNumber) {
                                maxNumber = seat.seatNumber;
                            }
                        });
                    }
                });
            }
            return maxNumber + 1;
        }

        function updateSeatCount() {
            let count = 0;
            if (layoutData && layoutData.length > 0) {
                layoutData.forEach(row => {
                    if (row && row.length > 0) {
                        row.forEach(seat => {
                            if (seat && seat.occupied) {
                                count++;
                            }
                        });
                    }
                });
            }
            const totalSeatsElement = document.getElementById('totalSeats');
            if (totalSeatsElement) {
                totalSeatsElement.textContent = count;
            }
            console.log('Updated seat count:', count);
        }

        function saveLayout() {
            console.log('Saving layout...');
            const formData = new FormData();
            formData.append('layout_data', JSON.stringify(layoutData));
            formData.append('students_per_bench', studentsPerBench);

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                formData.append('_token', csrfToken.getAttribute('content'));
            }

            fetch(`/institution/exam-management/rooms/${roomId}/update-layout`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Save response:', data);
                    if (data.success) {
                        showToast('Layout saved successfully!', 'success');
                    } else {
                        showToast('Error saving layout: ' + (data.message || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Error saving layout:', error);
                    showToast('Error saving layout', 'error');
                });
        }

        function testLayout() {
            console.log('=== TEST LAYOUT FUNCTION ===');
            console.log('Current layout data:', layoutData);
            console.log('Container element:', document.getElementById('roomLayout'));

            // Force create a simple test layout
            layoutData = [
                [{
                        type: 'seat',
                        occupied: false,
                        seatNumber: null
                    },
                    {
                        type: 'seat',
                        occupied: true,
                        seatNumber: 1
                    },
                    {
                        type: 'seat',
                        occupied: false,
                        seatNumber: null
                    }
                ],
                [{
                        type: 'seat',
                        occupied: true,
                        seatNumber: 2
                    },
                    {
                        type: 'seat',
                        occupied: false,
                        seatNumber: null
                    },
                    {
                        type: 'seat',
                        occupied: true,
                        seatNumber: 3
                    }
                ]
            ];

            console.log('Test layout data created:', layoutData);
            renderLayoutSimple();
            updateSeatCount();
        }

        function renderLayoutSimple() {
            console.log('=== SIMPLE RENDER LAYOUT START ===');
            const container = document.getElementById('roomLayout');
            if (!container) {
                console.error('Container not found!');
                return;
            }

            let html = '';
            layoutData.forEach((row, rowIndex) => {
                html +=
                    `<div style="display: flex; gap: 10px; margin-bottom: 10px; padding: 5px; background: #f0f8ff; border-radius: 5px;">`;
                row.forEach((seat, colIndex) => {
                    if (seat && seat.type === 'aisle') {
                        html += `<div style="width: 30px; height: 50px; background: #6c757d; border: 2px solid #495057; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; margin: 2px; color: white; font-weight: bold; font-size: 10px;"
                            data-row="${rowIndex}" data-col="${colIndex}" onclick="handleSeatClick(event)" title="Aisle">
                            A
                        </div>`;
                    } else {
                        const occupiedStyle = seat && seat.occupied ? 'background: #28a745; color: white;' :
                            'background: white; color: #333;';
                        const seatNumber = seat && seat.seatNumber ? seat.seatNumber : '';
                        html += `<div style="width: 50px; height: 50px; border: 3px solid #007bff; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; margin: 2px; font-weight: bold; font-size: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); ${occupiedStyle}"
                            data-row="${rowIndex}" data-col="${colIndex}" onclick="handleSeatClick(event)">
                            ${seatNumber}
                        </div>`;
                    }
                });
                html += '</div>';
            });

            container.innerHTML = html;
            console.log('Simple layout rendered with inline styles, HTML length:', html.length);
            console.log('=== SIMPLE RENDER LAYOUT COMPLETE ===');
        }

        function showToast(message, type) {
            // Create and show toast notification
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type === 'success' ? 'success' : 'danger'} border-0 show`;
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;

            const container = document.querySelector('.position-fixed.top-0.end-0.p-3') || document.body;
            container.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function showFallbackLayout() {
            console.log('Showing fallback layout');
            const mainLayout = document.getElementById('roomLayout');
            const fallbackLayout = document.getElementById('fallbackLayout');

            if (mainLayout && fallbackLayout) {
                mainLayout.style.display = 'none';
                fallbackLayout.style.display = 'flex';
                document.getElementById('totalSeats').textContent = '15';
                console.log('Fallback layout displayed');
            }
        }

        // Make functions globally accessible
        window.updateStudentsPerBench = updateStudentsPerBench;
        window.addRow = addRow;
        window.addColumn = addColumn;
        window.removeRow = removeRow;
        window.removeColumn = removeColumn;
        window.setSeatMode = setSeatMode;
        window.clearLayout = clearLayout;
        window.testLayout = testLayout;
        window.saveLayout = saveLayout;
        window.handleSeatClick = handleSeatClick;
        window.showFallbackLayout = showFallbackLayout;
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/institution/examination/room-layout.blade.php ENDPATH**/ ?>