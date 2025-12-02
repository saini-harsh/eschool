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
                <button type="button" class="btn btn-primary me-2" onclick="printLayout()">
                    <i class="ti ti-printer me-1"></i>Print Layout
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

                        <!-- Desk Layout Controls -->
                        <div id="deskControls" class="mb-3">
                            <div class="mb-3">
                                <label class="form-label">Number of Desks</label>
                                <div class="form-control-plaintext" id="calculatedDesks">25</div>
                                <small class="text-muted d-block">Auto-calculated based on room capacity ÷ students per
                                    desk</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Number of Rows</label>
                                <div class="form-control-plaintext" id="calculatedRows">5</div>
                                <small class="text-muted d-block">Auto-calculated based on desks ÷ columns</small>
                            </div>
                            <div class="mb-3">
                                <label for="numberOfColumns" class="form-label">Number of Columns</label>
                                <input type="number" class="form-control" id="numberOfColumns" min="1"
                                    max="20" value="2" onchange="updateDeskLayout()">
                            </div>
                            <div class="mb-3">
                                <label for="studentsPerDesk" class="form-label">Students per Desk</label>
                                <select class="form-control" id="studentsPerDesk" onchange="updateDeskLayout()">
                                    <option value="1">1 Student</option>
                                    <option value="2" selected>2 Students</option>
                                    <option value="3">3 Students</option>
                                    <option value="4">4 Students</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Auto-calculated Columns: <span
                                        id="calculatedColumns">2</span></label>
                                <small class="text-muted d-block">Recommended columns based on desks ÷ rows</small>
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
                            <span class="badge bg-primary me-2" id="deskInfo">Total Desks: <span
                                    id="totalDesks">0</span></span>
                            <span class="badge bg-info me-2" id="studentInfo">Students: <span
                                    id="totalStudents">0</span></span>
                            <span class="badge bg-success">Capacity: <?php echo e($classRoom->capacity); ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="layout-container" style="overflow: auto; max-height: 600px;">
                            <div id="roomLayout" class="room-layout">
                                <!-- Layout will be generated here -->
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


        .desk {
            width: 80%;
            height: 60px;
            border: 2px solid #000000;
            border-radius: 4px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-around;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #ffffff;
            position: relative;
            margin: 8px;
            flex-shrink: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 6px;
        }

        .desk:hover {
            border-color: #1e7e34;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .desk-number {
            position: absolute;
            top: -12px;
            left: 2px;
            background: #000000;
            color: white;
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }

        .student-circle {
            width: 60px;
            height: 40px;
            border-radius: 8px;
            background: #007bff;
            border: 2px solid #000000;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: white;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
            padding: 3px;
        }

        .student-circle.empty {
            background: #ffffff;
            border-color: #cccccc;
            color: #666666;
        }

        .student-circle:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .student-tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
            z-index: 1000;
        }

        .student-tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-top-color: #333;
        }

        .student-circle:hover .student-tooltip {
            opacity: 1;
        }

        .desk.occupied {
            background: #d4edda;
            border-color: #1e7e34;
            box-shadow: 0 3px 6px rgba(40, 167, 69, 0.3);
        }

        .desk.occupied:hover {
            background: #c3e6cb;
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
        }

        .desk.occupied .desk-number {
            color: #1e7e34;
        }

        .desk.occupied .desk-students {
            color: #1e7e34;
            font-weight: bold;
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


        .controls-panel {
            position: sticky;
            top: 20px;
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
        let roomCapacity = 30;
        let roomId = 1;
        let deskLayoutData = [];
        let numberOfDesks = 0; // Will be calculated based on room capacity
        let numberOfColumns = 2;
        let studentsPerDesk = 2;

        // Initialize layout
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== PAGE LOADED - INITIALIZING ROOM LAYOUT ===');

            // Get data from PHP variables
            try {
                roomCapacity = <?php echo e($classRoom->capacity); ?>;
                roomId = <?php echo e($classRoom->id); ?>;

                console.log('Room capacity:', roomCapacity);
                console.log('Room ID:', roomId);
            } catch (error) {
                console.error('Error getting data from PHP:', error);
                // Set defaults
                roomCapacity = 30;
                roomId = 1;
            }

            // Calculate number of desks based on room capacity
            numberOfDesks = Math.ceil(roomCapacity / studentsPerDesk);
            document.getElementById('calculatedDesks').textContent = numberOfDesks;

            // Set initial values
            document.getElementById('numberOfColumns').value = numberOfColumns;
            document.getElementById('studentsPerDesk').value = studentsPerDesk;

            // Initialize with desk layout by default
            console.log('Initializing desk layout');
            updateDeskLayout();

            console.log('=== INITIALIZATION COMPLETE ===');
        });










        function saveLayout() {
            console.log('Saving layout...');
            const formData = new FormData();

            formData.append('layout_type', 'desk');
            formData.append('layout_data', JSON.stringify(deskLayoutData));
            formData.append('number_of_desks', numberOfDesks);
            formData.append('number_of_rows', Math.ceil(numberOfDesks / numberOfColumns));
            formData.append('students_per_desk', studentsPerDesk);

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


        // Desk Layout Functions

        function updateDeskLayout() {
            numberOfColumns = parseInt(document.getElementById('numberOfColumns').value);
            studentsPerDesk = parseInt(document.getElementById('studentsPerDesk').value);

            // Calculate number of desks based on room capacity
            numberOfDesks = Math.ceil(roomCapacity / studentsPerDesk);
            document.getElementById('calculatedDesks').textContent = numberOfDesks;

            // Calculate rows automatically
            const calculatedRows = Math.ceil(numberOfDesks / numberOfColumns);
            document.getElementById('calculatedRows').textContent = calculatedRows;

            // Calculate columns automatically and update display
            const calculatedColumns = Math.ceil(numberOfDesks / calculatedRows);
            document.getElementById('calculatedColumns').textContent = calculatedColumns;

            // Validate that manual columns input is reasonable
            if (numberOfColumns > numberOfDesks) {
                numberOfColumns = numberOfDesks;
                document.getElementById('numberOfColumns').value = numberOfDesks;
            }

            generateDeskLayout();
        }

        function generateDeskLayout() {
            console.log('Generating desk layout...');
            deskLayoutData = [];

            // Calculate rows automatically
            const calculatedRows = Math.ceil(numberOfDesks / numberOfColumns);
            const columnsToUse = numberOfColumns;

            for (let i = 0; i < calculatedRows; i++) {
                deskLayoutData[i] = [];
                for (let j = 0; j < columnsToUse; j++) {
                    const deskIndex = i * columnsToUse + j;
                    if (deskIndex < numberOfDesks) {
                        deskLayoutData[i][j] = {
                            type: 'desk',
                            deskNumber: deskIndex + 1,
                            students: [],
                            maxStudents: studentsPerDesk,
                            occupied: false
                        };
                    } else {
                        deskLayoutData[i][j] = {
                            type: 'empty',
                            deskNumber: null,
                            students: [],
                            maxStudents: 0,
                            occupied: false
                        };
                    }
                }
            }

            renderDeskLayout();
            updateDeskCounts();
        }

        function renderDeskLayout() {
            console.log('Rendering desk layout...');
            console.log('Desk layout data:', deskLayoutData);
            console.log('Number of rows:', Math.ceil(numberOfDesks / numberOfColumns));
            console.log('Number of columns:', numberOfColumns);

            const container = document.getElementById('roomLayout');
            if (!container) {
                console.error('Container not found!');
                return;
            }

            container.innerHTML = '';

            // Set up CSS Grid with specified number of columns
            container.style.display = 'grid';
            container.style.gridTemplateColumns = `repeat(${numberOfColumns}, 1fr)`;
            container.style.gap = '10px';
            container.style.padding = '20px';
            container.style.background = '#f8f9fa';
            container.style.borderRadius = '8px';

            let html = '';
            let deskIndex = 0;

            // Flatten the 2D array and render as a grid
            const calculatedRows = Math.ceil(numberOfDesks / numberOfColumns);
            for (let i = 0; i < calculatedRows; i++) {
                for (let j = 0; j < numberOfColumns; j++) {
                    const desk = deskLayoutData[i] && deskLayoutData[i][j];

                    if (desk && desk.type === 'desk') {
                        const occupiedClass = desk.occupied ? 'occupied' : '';
                        const studentCount = desk.students.length;

                        // Create student circles based on studentsPerDesk setting
                        let studentCircles = '';
                        for (let k = 0; k < studentsPerDesk; k++) { // Show positions based on studentsPerDesk
                            const isAssigned = k < desk.students.length;
                            const studentName = isAssigned ? desk.students[k].name : '';
                            const circleClass = isAssigned ? 'student-circle' : 'student-circle empty';
                            let studentDisplay = '';
                            if (isAssigned && desk.students[k]) {
                                const student = desk.students[k];
                                const classInfo = student.class ? `Class ${student.class}` : '';
                                const sectionInfo = student.section ? `(${student.section})` : '';
                                const rollInfo = student.roll ? `-${student.roll}` : '';
                                studentDisplay = `${classInfo}${sectionInfo}${rollInfo}`;
                            }

                            const circleWidth = Math.max(40, Math.min(60, 320 / studentsPerDesk));
                            const circleStyle = isAssigned ?
                                `width: ${circleWidth}px; height: 35px; border-radius: 8px; background: #007bff; border: 2px solid #000000; transition: all 0.2s ease; cursor: pointer; position: relative; display: flex; align-items: center; justify-content: center; font-size: 9px; color: white; font-weight: bold; text-align: center; line-height: 1.2; padding: 2px;` :
                                `width: ${circleWidth}px; height: 35px; border-radius: 8px; background: #ffffff; border: 2px solid #cccccc; transition: all 0.2s ease; cursor: pointer; position: relative; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #666666; font-weight: bold; text-align: center; line-height: 1.2; padding: 2px;`;

                            studentCircles += `
                                <div class="${circleClass}"
                                     title="${studentName || 'Empty seat'}"
                                     style="${circleStyle}">
                                    ${studentDisplay}
                                </div>`;
                        }

                        html += `<div class="desk ${occupiedClass}"
                            data-row="${i}" data-col="${j}"
                            onclick="handleDeskClick(event)"
                            title="Desk ${desk.deskNumber} - ${studentCount} students assigned"
                            style="width: 80%; height: 60px; border: 2px solid #000000; border-radius: 4px; display: flex; flex-direction: row; align-items: center; justify-content: ${studentsPerDesk <= 2 ? 'space-around' : 'space-between'}; cursor: pointer; background: #ffffff; position: relative; margin: 8px; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); padding: 6px;">
                            <div class="desk-number" style="position: absolute; top: -12px; left: 2px; background: #000000; color: white; padding: 1px 4px; border-radius: 3px; font-size: 8px; font-weight: bold;">D${desk.deskNumber}</div>
                            ${studentCircles}
                        </div>`;
                    } else {
                        html +=
                            `<div style="width: 80%; height: 60px; margin: 8px; visibility: hidden;"></div>`;
                    }
                }
            }

            container.innerHTML = html;
            console.log('Desk layout rendered');
        }

        function handleDeskClick(event) {
            // Check if the click was on a student circle
            if (event.target.classList.contains('student-circle')) {
                event.stopPropagation();
                handleStudentCircleClick(event);
                return;
            }

            const row = parseInt(event.target.closest('.desk').dataset.row);
            const col = parseInt(event.target.closest('.desk').dataset.col);
            const desk = deskLayoutData[row][col];

            if (desk.type === 'desk') {
                openDeskStudentModal(desk, row, col);
            }
        }

        function handleStudentCircleClick(event) {
            const deskElement = event.target.closest('.desk');
            const row = parseInt(deskElement.dataset.row);
            const col = parseInt(deskElement.dataset.col);
            const desk = deskLayoutData[row][col];

            // Find which student position was clicked
            const studentCircles = deskElement.querySelectorAll('.student-circle');
            let studentIndex = -1;
            for (let i = 0; i < studentCircles.length; i++) {
                if (studentCircles[i] === event.target) {
                    studentIndex = i;
                    break;
                }
            }

            if (studentIndex >= 0) {
                openStudentAssignmentModal(desk, row, col, studentIndex);
            }
        }

        function openDeskStudentModal(desk, row, col) {
            // Create modal for student selection
            const modalHtml = `
                <div class="modal fade" id="deskStudentModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Desk ${desk.deskNumber} - Student Selection</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Number of Students (Max: ${desk.maxStudents})</label>
                                    <input type="number" class="form-control" id="studentCount"
                                           min="0" max="${desk.maxStudents}" value="${desk.students.length}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Student Names (Optional)</label>
                                    <textarea class="form-control" id="studentNames" rows="3"
                                              placeholder="Enter student names, one per line"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="saveDeskStudents(${row}, ${col})">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove existing modal if any
            const existingModal = document.getElementById('deskStudentModal');
            if (existingModal) {
                existingModal.remove();
            }

            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Populate existing data
            document.getElementById('studentCount').value = desk.students.length;
            document.getElementById('studentNames').value = desk.students.join('\n');

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('deskStudentModal'));
            modal.show();
        }

        function saveDeskStudents(row, col) {
            const studentCount = parseInt(document.getElementById('studentCount').value);
            const studentNames = document.getElementById('studentNames').value.split('\n').filter(name => name.trim());

            const desk = deskLayoutData[row][col];

            // Create student objects with names
            desk.students = [];
            for (let i = 0; i < studentCount; i++) {
                desk.students.push({
                    id: i + 1,
                    name: studentNames[i] || `Student ${i + 1}`
                });
            }

            desk.occupied = studentCount > 0;

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('deskStudentModal'));
            modal.hide();

            // Re-render layout
            renderDeskLayout();
            updateDeskCounts();
        }

        function openStudentAssignmentModal(desk, row, col, studentIndex) {
            // Create modal for individual student assignment
            const modalHtml = `
                <div class="modal fade" id="studentAssignmentModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Assign Student to Desk ${desk.deskNumber} - Position ${studentIndex + 1}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Class</label>
                                            <select class="form-control" id="studentClass" onchange="loadSections()">
                                                <option value="">Select Class</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Section</label>
                                            <select class="form-control" id="studentSection" onchange="loadStudents()">
                                                <option value="">Select Section</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Select Student</label>
                                    <select class="form-control" id="studentSelect" onchange="showStudentInfo()">
                                        <option value="">Select Student</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <div class="alert alert-info">
                                        <small>
                                            <strong>Student Information:</strong><br>
                                            <span id="studentInfo">Please select a student</span>
                                        </small>
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    <small>
                                        <strong>Current Assignment:</strong><br>
                                        ${desk.students[studentIndex] ?
                                            `Name: ${desk.students[studentIndex].name}<br>
                                                                                                                                                                                                             Class: ${desk.students[studentIndex].class || 'Not assigned'}<br>
                                                                                                                                                                                                             Section: ${desk.students[studentIndex].section || 'Not assigned'}` :
                                            'No student assigned to this position'
                                        }
                                    </small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" onclick="removeStudent(${row}, ${col}, ${studentIndex})">Remove Student</button>
                                <button type="button" class="btn btn-primary" onclick="saveStudentAssignment(${row}, ${col}, ${studentIndex})">Save Seat Assignment</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove existing modal if any
            const existingModal = document.getElementById('studentAssignmentModal');
            if (existingModal) {
                existingModal.remove();
            }

            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Load classes from database
            loadClasses();

            // Populate existing data if student is already assigned
            if (desk.students[studentIndex]) {
                const student = desk.students[studentIndex];
                // Set the class and section values if they exist
                setTimeout(() => {
                    if (student.class) {
                        document.getElementById('studentClass').value = student.class;
                        loadSections();
                        setTimeout(() => {
                            if (student.section) {
                                document.getElementById('studentSection').value = student.section;
                                loadStudents();
                            }
                        }, 500);
                    }
                }, 500);
            }

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('studentAssignmentModal'));
            modal.show();
        }

        function saveStudentAssignment(row, col, studentIndex) {
            const studentSelect = document.getElementById('studentSelect');

            if (!studentSelect.value) {
                alert('Please select a student');
                return;
            }

            const studentData = JSON.parse(studentSelect.selectedOptions[0].dataset.studentData);
            const desk = deskLayoutData[row][col];

            // Ensure students array exists and has enough elements
            while (desk.students.length <= studentIndex) {
                desk.students.push(null);
            }

            // Assign student to the specific position
            desk.students[studentIndex] = {
                id: studentData.id,
                name: studentData.name,
                roll: studentData.roll_number,
                class: studentData.class_name,
                section: studentData.section_name,
                student_id: studentData.student_id
            };

            desk.occupied = desk.students.some(s => s !== null);

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('studentAssignmentModal'));
            modal.hide();

            // Re-render layout
            renderDeskLayout();
            updateDeskCounts();
        }

        function removeStudent(row, col, studentIndex) {
            const desk = deskLayoutData[row][col];

            if (desk.students[studentIndex]) {
                desk.students[studentIndex] = null;
                desk.occupied = desk.students.some(s => s !== null);

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('studentAssignmentModal'));
                modal.hide();

                // Re-render layout
                renderDeskLayout();
                updateDeskCounts();
            }
        }

        // Dynamic student loading functions
        function loadClasses() {
            const classSelect = document.getElementById('studentClass');
            const sectionSelect = document.getElementById('studentSection');
            const studentSelect = document.getElementById('studentSelect');

            // Clear all dropdowns
            classSelect.innerHTML = '<option value="">Select Class</option>';
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            studentSelect.innerHTML = '<option value="">Select Student</option>';
            document.getElementById('studentInfo').textContent = 'Please select a student';

            // Get institution ID from the current user (you may need to pass this from PHP)
            const institutionId = <?php echo e(auth('institution')->id()); ?>;

            // Fetch classes for the institution
            fetch(`/institution/exam-management/rooms/api/classes/${institutionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.classes.forEach(classItem => {
                            const option = document.createElement('option');
                            option.value = classItem.id;
                            option.textContent = classItem.name;
                            classSelect.appendChild(option);
                        });
                    } else {
                        console.error('Error loading classes:', data.error);
                        classSelect.innerHTML = '<option value="">Error loading classes</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading classes:', error);
                    classSelect.innerHTML = '<option value="">Error loading classes</option>';
                });
        }

        function loadSections() {
            const classId = document.getElementById('studentClass').value;
            const sectionSelect = document.getElementById('studentSection');
            const studentSelect = document.getElementById('studentSelect');

            // Clear sections and students
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            studentSelect.innerHTML = '<option value="">Select Student</option>';
            document.getElementById('studentInfo').textContent = 'Please select a student';

            if (!classId) return;

            // Fetch sections for the selected class
            fetch(`/institution/exam-management/rooms/api/sections/${classId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.id;
                            option.textContent = section.name;
                            sectionSelect.appendChild(option);
                        });
                    } else {
                        console.error('Error loading sections:', data.error);
                        sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading sections:', error);
                    sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                });
        }

        function loadStudents() {
            const classId = document.getElementById('studentClass').value;
            const sectionId = document.getElementById('studentSection').value;
            const studentSelect = document.getElementById('studentSelect');

            // Clear students
            studentSelect.innerHTML = '<option value="">Select Student</option>';
            document.getElementById('studentInfo').textContent = 'Please select a student';

            if (!classId || !sectionId) return;

            // Fetch students for the selected class and section
            fetch(`/institution/exam-management/rooms/api/students/${classId}/${sectionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.students.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.textContent = `${student.name} (Roll: ${student.roll_number})`;
                            option.dataset.studentData = JSON.stringify(student);
                            studentSelect.appendChild(option);
                        });
                    } else {
                        console.error('Error loading students:', data.error);
                        studentSelect.innerHTML = '<option value="">Error loading students</option>';
                        document.getElementById('studentInfo').textContent =
                            'Error loading students. Please try again.';
                    }
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                    studentSelect.innerHTML = '<option value="">Error loading students</option>';
                    document.getElementById('studentInfo').textContent = 'Error loading students. Please try again.';
                });
        }

        function showStudentInfo() {
            const studentSelect = document.getElementById('studentSelect');
            const studentInfo = document.getElementById('studentInfo');

            if (studentSelect.value) {
                const studentData = JSON.parse(studentSelect.selectedOptions[0].dataset.studentData);
                studentInfo.innerHTML = `
                    <strong>Name:</strong> ${studentData.name}<br>
                    <strong>Roll Number:</strong> ${studentData.roll_number}<br>
                    <strong>Student ID:</strong> ${studentData.student_id}<br>
                    <strong>Class:</strong> ${studentData.class_name}<br>
                    <strong>Section:</strong> ${studentData.section_name}
                `;
            } else {
                studentInfo.textContent = 'Please select a student';
            }
        }

        function printLayout() {
            // Get the current layout HTML
            const layoutContainer = document.getElementById('roomLayout');
            const h5Element = document.querySelector('h5');
            const roomName = h5Element ? h5Element.textContent.replace('Room Layout Design - ', '') : 'Room Layout';

            // Get all CSS from the current page
            const allStyles = [];
            const styleSheets = document.styleSheets;

            // Copy all stylesheets
            for (let i = 0; i < document.styleSheets.length; i++) {
                try {
                    const styleSheet = document.styleSheets[i];
                    if (styleSheet.href) {
                        allStyles.push(`<link rel="stylesheet" href="${styleSheet.href}">`);
                    }
                } catch (e) {
                    // Skip external stylesheets that can't be accessed
                }
            }

            // Get inline styles
            const inlineStyles = document.querySelectorAll('style');
            inlineStyles.forEach(style => {
                allStyles.push(`<style>${style.innerHTML}</style>`);
            });

            // Create print content with existing styles
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${roomName} - Desk Layout</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    ${allStyles.join('\n')}
                    <style>
                        @page {
                            margin: 1cm;
                            size: A4;
                        }
                        body {
                            margin: 0;
                            padding: 20px;
                        }
                        .print-title {
                            text-align: center;
                            font-size: 24px;
                            font-weight: bold;
                            margin-bottom: 20px;
                            color: #333;
                        }
                        #roomLayout {
                            width: 100% !important;
                            display: grid !important;
                            grid-template-columns: repeat(${numberOfColumns}, 1fr) !important;
                            gap: 20px !important;
                            justify-items: stretch !important;
                            align-items: start !important;
                        }
                        .desk {
                            width: 100% !important;
                            max-width: none !important;
                            min-width: 0 !important;
                        }
                        @media print {
                            .no-print { display: none !important; }
                            body {
                                margin: 0 !important;
                                padding: 10px !important;
                            }
                            #roomLayout {
                                display: grid !important;
                                grid-template-columns: repeat(${numberOfColumns}, 1fr) !important;
                                gap: 20px !important;
                                width: 100% !important;
                                max-width: none !important;
                            }
                            .desk {
                                width: 100% !important;
                                max-width: none !important;
                                break-inside: avoid !important;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-title">${roomName}</div>
                    <div id="roomLayout" style="display: grid; grid-template-columns: repeat(${numberOfColumns}, 1fr); gap: 20px; width: 100%;">
                        ${layoutContainer.innerHTML}
                    </div>
                </body>
                </html>
            `;

            // Open print window
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();

            // Wait for content to load then print
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            };
        }


        function randomizeDeskStudents() {
            if (confirm('This will randomly assign students to desks. Continue?')) {
                deskLayoutData.forEach(row => {
                    row.forEach(desk => {
                        if (desk.type === 'desk') {
                            const randomCount = Math.floor(Math.random() * (desk.maxStudents + 1));
                            desk.students = Array.from({
                                length: randomCount
                            }, (_, i) => ({
                                id: i + 1,
                                name: `Student ${Math.floor(Math.random() * 100) + 1}`
                            }));
                            desk.occupied = randomCount > 0;
                        }
                    });
                });
                renderDeskLayout();
                updateDeskCounts();
            }
        }

        function clearDeskStudents() {
            if (confirm('This will clear all students from all desks. Continue?')) {
                deskLayoutData.forEach(row => {
                    row.forEach(desk => {
                        if (desk.type === 'desk') {
                            desk.students = [];
                            desk.occupied = false;
                        }
                    });
                });
                renderDeskLayout();
                updateDeskCounts();
            }
        }

        function updateDeskCounts() {
            let totalDesks = 0;
            let totalStudents = 0;

            deskLayoutData.forEach(row => {
                row.forEach(desk => {
                    if (desk.type === 'desk') {
                        totalDesks++;
                        totalStudents += desk.students.length;
                    }
                });
            });

            document.getElementById('totalDesks').textContent = totalDesks;
            document.getElementById('totalStudents').textContent = totalStudents;
        }


        // Make functions globally accessible
        window.saveLayout = saveLayout;
        window.updateDeskLayout = updateDeskLayout;
        window.generateDeskLayout = generateDeskLayout;
        window.handleDeskClick = handleDeskClick;
        window.saveDeskStudents = saveDeskStudents;
        window.randomizeDeskStudents = randomizeDeskStudents;
        window.clearDeskStudents = clearDeskStudents;
        window.handleStudentCircleClick = handleStudentCircleClick;
        window.openStudentAssignmentModal = openStudentAssignmentModal;
        window.saveStudentAssignment = saveStudentAssignment;
        window.removeStudent = removeStudent;
        window.loadClasses = loadClasses;
        window.loadSections = loadSections;
        window.loadStudents = loadStudents;
        window.showStudentInfo = showStudentInfo;
        window.printLayout = printLayout;
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/examination/room-layout.blade.php ENDPATH**/ ?>