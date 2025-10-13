$(document).ready(function() {
    // Prevent multiple initializations
    if (window.routinesInitialized) {
        return;
    }
    window.routinesInitialized = true;

    // Global variables
    let currentClassId = null;
    let currentSectionId = null;

    // CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Class filter change handler for report - unbind existing events first
    $('#class_filter').off('change.routines').on('change.routines', function() {
        const classId = $(this).val();
        if (classId) {
            loadSectionsForReport(classId);
            $('#section_filter').prop('disabled', false);
        } else {
            $('#section_filter').html('<option value="">Select Section</option>').prop('disabled', true);
            $('#search_routine').prop('disabled', true);
        }
    });

    // Section filter change handler for report - unbind existing events first
    $('#section_filter').off('change.routines').on('change.routines', function() {
        const sectionId = $(this).val();
        if (sectionId) {
            $('#search_routine').prop('disabled', false);
        } else {
            $('#search_routine').prop('disabled', true);
        }
    });

    // Search routine for report - unbind existing events first
    $('#search_routine').off('click.routines').on('click.routines', function() {
        const classId = $('#class_filter').val();
        const sectionId = $('#section_filter').val();
        
        if (!classId || !sectionId) {
            showAlert('Please select both class and section', 'warning');
            return;
        }
        
        loadRoutineReport(classId, sectionId);
    });

    // Print routine - unbind existing events first
    $('#print_routine').off('click.routines').on('click.routines', function() {
        window.print();
    });

    // Helper Functions
    function formatTimeTo12Hour(time24) {
        if (!time24) return '';
        
        const [hours, minutes] = time24.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        
        return `${hour12}:${minutes} ${ampm}`;
    }



    function loadRoutineReport(classId, sectionId) {
        $.ajax({
            url: '/teacher/routines/report',
            type: 'GET',
            data: {
                class_id: classId,
                section_id: sectionId
            },
            success: function(response) {
                if (response.success) {
                    displayRoutineReport(response.data);
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function() {
                showAlert('Error loading routine report', 'error');
            }
        });
    }

    function displayRoutineReport(routines) {
        const content = $('#routine_content');
        const template = $('#routine-template').html();
        
        if (Object.keys(routines).length === 0) {
            content.html(`
                <div class="text-center text-muted py-5">
                    <i class="ti ti-calendar-event fs-48 mb-3"></i>
                    <p>No routines found for the selected class and section</p>
                </div>
            `);
            return;
        }

        // Create time slots first
        const timeSlots = generateTimeSlots(routines);
        
        // Create dynamic table with proper number of columns
        let tableHtml = `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">Day</th>`;
        
        // Add time header columns dynamically
        timeSlots.forEach(function(timeSlot, index) {
            // Find the routine for this time slot to get both start and end time
            let routineForTime = null;
            ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'].forEach(function(day) {
                const dayRoutines = routines[day] || [];
                const routineAtTime = dayRoutines.find(r => r.start_time === timeSlot);
                if (routineAtTime && !routineForTime) {
                    routineForTime = routineAtTime;
                }
            });
            
            // Display both start and end time
            const timeDisplay = routineForTime ? 
                `${formatTimeTo12Hour(routineForTime.start_time)} - ${formatTimeTo12Hour(routineForTime.end_time)}` : 
                formatTimeTo12Hour(timeSlot);
            
            tableHtml += `<th class="text-center">${timeDisplay}</th>`;
        });
        
        tableHtml += `
                        </tr>
                    </thead>
                    <tbody id="routine-tbody">
                        <!-- Routine rows will be populated here -->
                    </tbody>
                </table>
            </div>`;
        
        content.html(tableHtml);
        const tbody = $('#routine-tbody');
        
        // Create rows for each day (Monday to Saturday, excluding Sunday)
        const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        const dayNames = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'];
        
        days.forEach(function(day, dayIndex) {
            const row = $('<tr></tr>');
            row.append(`<td class="text-center fw-bold">${dayNames[dayIndex]}</td>`);
            
            timeSlots.forEach(function(timeSlot) {
                const cell = $('<td class="text-center"></td>');
                const dayRoutines = routines[day] || [];
                const routineAtTime = dayRoutines.find(r => r.start_time === timeSlot);
                
                if (routineAtTime) {
                    const cellTemplate = $('#routine-cell-template').html();
                    const cellContent = cellTemplate
                        .replace('__SUBJECT_NAME__', routineAtTime.subject.name)
                        .replace('__TEACHER_NAME__', `${routineAtTime.teacher.first_name} ${routineAtTime.teacher.last_name}`)
                        .replace('__CLASS_ROOM__', routineAtTime.class_room ? routineAtTime.class_room.room_no : 'N/A');
                    cell.html(cellContent);
                }
                
                row.append(cell);
            });
            
            tbody.append(row);
        });
    }

    function generateTimeSlots(routines) {
        const timeSlots = new Set();
        Object.values(routines).forEach(function(dayRoutines) {
            dayRoutines.forEach(function(routine) {
                timeSlots.add(routine.start_time);
            });
        });
        return Array.from(timeSlots).sort();
    }

    function loadSectionsForReport(classId) {
        $.ajax({
            url: `/teacher/routines/sections/${classId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const sectionSelect = $('#section_filter');
                    sectionSelect.html('<option value="">Select Section</option>');
                    response.data.forEach(function(section) {
                        sectionSelect.append(`<option value="${section.id}">${section.name}</option>`);
                    });
                }
            },
            error: function() {
                showAlert('Error loading sections', 'error');
            }
        });
    }


    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 'alert-danger';
        
        const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
        
        $('.content').prepend(alert);
        
        setTimeout(function() {
            alert.alert('close');
        }, 5000);
    }
});
