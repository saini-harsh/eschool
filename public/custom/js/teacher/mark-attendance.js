$(document).ready(function() {
    console.log('Mark attendance page JavaScript loaded');
    
    // Initialize date pickers
    initializeDatePickers();

    // Form submission handlers
    $('#mark-attendance-form').on('submit', function(e) {
        e.preventDefault();
        loadStudentsForAttendance();
    });

    $('#mark-my-attendance-form').on('submit', function(e) {
        e.preventDefault();
        saveMyAttendance();
    });

    // Class change handler using event delegation
    $(document).on('change', '#class', function() {
        console.log('Class dropdown changed');
        const classId = $(this).val();
        if (classId) {
            loadSections(classId);
        } else {
            $('#section').html('<option value="">Select Section</option>');
            $('#students-card').hide();
        }
    });

    // Save attendance button click
    $('#save-attendance-btn').on('click', function() {
        saveAttendance();
    });

    // Initialize date pickers
    function initializeDatePickers() {
        flatpickr("#date", {
            dateFormat: "d M, Y",
            placeholder: "dd/mm/yyyy",
            allowInput: true
        });
        
        flatpickr("#my-date", {
            dateFormat: "d M, Y",
            placeholder: "dd/mm/yyyy",
            allowInput: true
        });
    }

    // Load sections by class
    function loadSections(classId) {
        $.ajax({
            url: `/teacher/attendance/sections/${classId}`,
            type: 'GET',
            success: function(response) {
                let options = '<option value="">Select Section</option>';
                if (response && response.length > 0) {
                    response.forEach(function(section) {
                        options += `<option value="${section.id}">${section.name}</option>`;
                    });
                } else {
                    options += '<option value="" disabled>No sections available</option>';
                }
                $('#section').html(options);
            },
            error: function(xhr, status, error) {
                showAlert('Failed to load sections.', 'error');
            }
        });
    }

    // Load students for attendance
    function loadStudentsForAttendance() {
        const classId = $('#class').val();
        const sectionId = $('#section').val();
        const date = $('#date').val();

        if (!classId || !sectionId || !date) {
            showAlert('Please select class, section and date.', 'warning');
            return;
        }

        // Update title
        updateAttendanceTitle();

        $.ajax({
            url: '/teacher/attendance/students',
            type: 'GET',
            data: {
                class_id: classId,
                section_id: sectionId
            },
            success: function(response) {
                if (response.length > 0) {
                    buildStudentsTable(response);
                    $('#students-card').show();
                    $('#save-attendance-btn').show();
                } else {
                    showAlert('No students found for the selected class and section.', 'info');
                    $('#students-card').hide();
                }
            },
            error: function() {
                showAlert('Failed to load students.', 'error');
            }
        });
    }

    // Update attendance title
    function updateAttendanceTitle() {
        const className = $('#class option:selected').text();
        const sectionName = $('#section option:selected').text();
        const date = $('#date').val();
        
        if (className !== 'Select Class' && sectionName !== 'Select Section' && date) {
            $('#students-title').text(`${className} - ${sectionName} - ${date}`);
        }
    }

    // Build students table
    function buildStudentsTable(students) {
        let tbody = '';
        students.forEach(function(student, index) {
            tbody += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>
                        <strong>${student.first_name} ${student.last_name}</strong><br>
                        <small class="text-muted">${student.admission_number}</small>
                    </td>
                    <td class="text-center">${student.roll_number || 'N/A'}</td>
                    <td class="text-center">
                        <select class="form-select form-select-sm attendance-status" data-student-id="${student.id}">
                            <option value="">Select Status</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="excused">Excused</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm attendance-remarks" data-student-id="${student.id}" placeholder="Optional remarks">
                    </td>
                </tr>
            `;
        });
        $('#students-table-body').html(tbody);
    }

    // Save attendance
    function saveAttendance() {
        const classId = $('#class').val();
        const sectionId = $('#section').val();
        const date = formatDateForAPI($('#date').val());
        
        const attendanceData = [];
        $('.attendance-status').each(function() {
            const studentId = $(this).data('student-id');
            const status = $(this).val();
            const remarks = $(`.attendance-remarks[data-student-id="${studentId}"]`).val();
            
            if (status) {
                attendanceData.push({
                    user_id: studentId,
                    status: status,
                    remarks: remarks || ''
                });
            }
        });

        if (attendanceData.length === 0) {
            showAlert('Please mark attendance for at least one student.', 'warning');
            return;
        }

        const formData = {
            class_id: classId,
            section_id: sectionId,
            date: date,
            attendance_data: attendanceData
        };

        $.ajax({
            url: '/teacher/attendance/mark',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    // Reset form
                    $('#mark-attendance-form')[0].reset();
                    $('#students-card').hide();
                    $('#save-attendance-btn').hide();
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert(response.message || 'Failed to save attendance.', 'error');
            }
        });
    }

    // Save my attendance
    function saveMyAttendance() {
        const formData = {
            date: formatDateForAPI($('#my-date').val()),
            status: $('#my-status').val(),
            remarks: $('#my-remarks').val()
        };

        $.ajax({
            url: '/teacher/attendance/mark-my-attendance',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    $('#mark-my-attendance-form')[0].reset();
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert(response.message || 'Failed to save attendance.', 'error');
            }
        });
    }

    // Helper functions
    function formatDateForAPI(dateString) {
        if (!dateString) return '';
        // Convert from "d M, Y" format to "Y-m-d" format for API
        const date = moment(dateString, 'D MMM, YYYY');
        return date.isValid() ? date.format('YYYY-MM-D') : '';
    }

    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at the top of the content
        $('.content').prepend(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});
