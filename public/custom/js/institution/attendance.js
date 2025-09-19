$(document).ready(function() {
    // Initialize Flatpickr date pickers
    initializeDatePickers();
    
    // Filter form submission
    $('#attendance-filter-form').on('submit', function(e) {
        e.preventDefault();
        loadAttendanceRecords();
    });

    // Role change handler for filter form
    $('#role').on('change', function() {
        const role = $(this).val();
        if (role === 'student') {
            $('#class-field, #section-field, #teacher-field').show();
        } else {
            $('#class-field, #section-field, #teacher-field').hide();
            $('#class, #section, #teacher').val('').trigger('change');
        }
    });

    // Class change handler for filter form
    $('#class').on('change', function() {
        const classId = $(this).val();
        if (classId) {
            loadSections(classId, '#section');
            loadTeachers(classId, '#section', '#teacher');
        }
    });

    // Section change handler for filter form
    $('#section').on('change', function() {
        const classId = $('#class').val();
        const sectionId = $(this).val();
        if (classId && sectionId) {
            loadTeachers(classId, sectionId, '#teacher');
        }
    });






    // Initialize Flatpickr date pickers
    function initializeDatePickers() {
        // Initialize filter date pickers
        flatpickr("#from_date", {
            dateFormat: "d M, Y",
            placeholder: "dd/mm/yyyy",
            allowInput: true
        });
        
        flatpickr("#to_date", {
            dateFormat: "d M, Y",
            placeholder: "dd/mm/yyyy",
            allowInput: true
        });
        
    }

    // Load attendance records
    function loadAttendanceRecords() {
        const formData = {
            role: $('#role').val(),
            class: $('#class').val(),
            section: $('#section').val(),
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val()
        };

        // Update title with class-section and date range
        updateAttendanceTitle(formData);

        $.ajax({
            url: '/institution/attendance/matrix',
            type: 'GET',
            data: formData,
            success: function(response) {
                if (response.users && response.users.length > 0) {
                    buildMatrixTable(response);
                } else {
                    showNoDataMessage();
                }
            },
            error: function() {
                showAlert('Failed to fetch attendance records.', 'error');
            }
        });
    }

    // Update attendance title
    function updateAttendanceTitle(formData) {
        let title = 'Attendance Records';
        if (formData.class && formData.section) {
            // Get class and section names from the form
            const className = $('#class option:selected').text();
            const sectionName = $('#section option:selected').text();
            if (className !== 'Select Class' && sectionName !== 'Select Section') {
                title = `${className} - ${sectionName}`;
            }
        }
        
        if (formData.from_date && formData.to_date) {
            title += ` from ${formData.from_date} to ${formData.to_date}`;
        } else if (formData.from_date) {
            title += ` from ${formData.from_date}`;
        } else if (formData.to_date) {
            title += ` to ${formData.to_date}`;
        }
        
        $('#attendance-records-title').text(title);
    }

    // Build matrix table
    function buildMatrixTable(response) {
        // Use simple "Name" header for all roles
        const columnHeader = 'Name';
        
        let thead = `<thead><tr><th>${columnHeader}</th>`;
        let tbody = '';
        
        // Build header with dates
        response.dates.forEach(function(date) {
            const dateObj = moment(date);
            thead += `<th class="text-center">${dateObj.format('DD')}<br><small>${dateObj.format('MMM')}</small></th>`;
        });
        thead += '<th class="text-center">Actions</th></tr></thead>';
        
        // Build body with users and attendance
        response.users.forEach(function(userData) {
            const user = userData.user;
            const attendance = userData.attendance;
            
            tbody += `
                <tr>
                    <td>
                        <strong>${user.first_name} ${user.last_name}</strong><br>
                        <small class="text-muted">${user.roll_number || user.admission_number || user.email || user.designation}</small>
                    </td>
            `;
            
            // Add attendance status for each date
            response.dates.forEach(function(date) {
                const attendanceData = attendance[date];
                let statusIcon = '';
                let confirmButton = '';
                
                if (attendanceData) {
                    const status = attendanceData.status;
                    const isConfirmed = attendanceData.is_confirmed;
                    const markedByRole = attendanceData.marked_by_role;
                    const attendanceId = attendanceData.attendance_id;
                    
                    // Status icon
                    if (status === 'present') {
                        statusIcon = '<span class="text-success fw-bold">✓</span>';
                    } else if (status === 'absent') {
                        statusIcon = '<span class="text-danger fw-bold">✗</span>';
                    } else if (status === 'late') {
                        statusIcon = '<span class="text-warning fw-bold">⏰</span>';
                    } else if (status === 'excused') {
                        statusIcon = '<span class="text-info fw-bold">ℹ</span>';
                    }
                    
                    // Add confirmation button for teacher attendance that needs confirmation
                    if (markedByRole === 'teacher' && !isConfirmed) {
                        confirmButton = `<br><button class="btn btn-xs btn-success mt-1" onclick="confirmAttendance(${attendanceId})" title="Confirm Attendance">
                            <i class="ti ti-check"></i>
                        </button>`;
                    } else if (isConfirmed) {
                        confirmButton = `<br><small class="text-success">✓ Confirmed</small>`;
                    }
                } else {
                    statusIcon = '<span class="text-muted">-</span>';
                }
                
                tbody += `<td class="text-center">${statusIcon}${confirmButton}</td>`;
            });
            
            // Add actions column
            tbody += `
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary" onclick="viewUserAttendance(${user.id}, '${user.first_name} ${user.last_name}')">
                        View
                    </button>
                </td>
            </tr>
            `;
        });
        
        // Update table with simple styling
        $('.table').addClass('table-bordered table-striped');
        $('.table thead').replaceWith(thead);
        $('#attendance-table-body').html(tbody);
    }

    // Show no data message
    function showNoDataMessage() {
        const tbody = `
            <tr>
                <td colspan="100%" class="text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-clipboard-list text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="text-muted mb-2">No attendance records found</h6>
                    <p class="text-muted mb-0">Try adjusting your filter criteria.</p>
                </td>
            </tr>
        `;
        $('#attendance-table-body').html(tbody);
    }

    // View user attendance details
    window.viewUserAttendance = function(userId, userName) {
        // This can be expanded to show detailed attendance for a specific user
        showAlert(`Viewing attendance details for ${userName}`, 'info');
    };

    // Confirm attendance
    window.confirmAttendance = function(attendanceId) {
        if (confirm('Are you sure you want to confirm this attendance?')) {
            $.ajax({
                url: `/institution/attendance/${attendanceId}/confirm`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert(response.message, 'success');
                        // Reload the attendance records to update the UI
                        loadAttendanceRecords();
                    } else {
                        showAlert(response.message, 'error');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showAlert(response.message || 'Failed to confirm attendance.', 'error');
                }
            });
        }
    };

    // Load sections by class
    function loadSections(classId, targetSelector) {
        $.ajax({
            url: `/institution/attendance/sections/${classId}`,
            type: 'GET',
            success: function(response) {
                let options = '<option value="">Select Section</option>';
                response.forEach(function(section) {
                    options += `<option value="${section.id}">${section.name}</option>`;
                });
                $(targetSelector).html(options);
            },
            error: function() {
                showAlert('Failed to load sections.', 'error');
            }
        });
    }

    // Load teachers by class and section
    function loadTeachers(classId, sectionId, targetSelector) {
        $.ajax({
            url: '/institution/attendance/teachers',
            type: 'GET',
            data: {
                class_id: classId,
                section_id: sectionId
            },
            success: function(response) {
                let options = '<option value="">Select Teacher</option>';
                response.forEach(function(teacher) {
                    options += `<option value="${teacher.id}">${teacher.first_name} ${teacher.last_name}</option>`;
                });
                $(targetSelector).html(options);
            },
            error: function() {
                showAlert('Failed to load teachers.', 'error');
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
        
        const alert = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('body').prepend(alert);
        
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});
