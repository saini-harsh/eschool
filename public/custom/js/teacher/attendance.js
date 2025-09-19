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
            $('#class-field, #section-field').show();
        } else {
            $('#class-field, #section-field').hide();
            $('#class, #section').val('').trigger('change');
        }
    });

    // Class change handler for filter form
    $('#class').on('change', function() {
        const classId = $(this).val();
        if (classId) {
            loadSections(classId, '#section');
        } else {
            $('#section-field').hide();
            $('#section').val('');
        }
    });



    // Save my attendance button click
    $('#save-my-attendance-btn').on('click', function() {
        saveMyAttendance();
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
        
        // Initialize modal date picker
        flatpickr("#modal-date", {
            dateFormat: "d M, Y",
            placeholder: "dd/mm/yyyy",
            allowInput: true
        });
        
        // Initialize my attendance date picker
        flatpickr("#my-date", {
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
            url: '/teacher/attendance/matrix',
            type: 'GET',
            data: formData,
            success: function(response) {
                if (response.error) {
                    showAlert(response.error, 'error');
                    showNoDataMessage();
                    return;
                }
                if (response.users && response.users.length > 0) {
                    buildMatrixTable(response);
                } else {
                    if (response.message) {
                        showAlert(response.message, 'info');
                    }
                    showNoDataMessage();
                }
                // Also reload my attendance with the same date range
                loadMyAttendanceMatrix();
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.error) {
                    showAlert(response.error, 'error');
                } else {
                    showAlert('Failed to fetch attendance records.', 'error');
                }
                showNoDataMessage();
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
                        <small class="text-muted">${user.roll_number || user.admission_number || user.email}</small>
                    </td>
            `;
            
            // Add attendance status for each date
            response.dates.forEach(function(date) {
                const status = attendance[date];
                let statusIcon = '';
                if (status === 'present') {
                    statusIcon = '<span class="text-success fw-bold"><i class="ti ti-check"></i></span>';
                } else if (status === 'absent') {
                    statusIcon = '<span class="text-danger fw-bold"><i class="ti ti-x"></i></span>';
                } else if (status === 'late') {
                    statusIcon = '<span class="text-warning fw-bold"><i class="ti ti-clock"></i></span>';
                } else if (status === 'excused') {
                    statusIcon = '<span class="text-info fw-bold"><i class="ti ti-info-circle"></i></span>';
                } else {
                    statusIcon = '<span class="text-muted">-</span>';
                }
                tbody += `<td class="text-center">${statusIcon}</td>`;
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

    // Load my attendance matrix
    function loadMyAttendanceMatrix() {
        const formData = {
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val()
        };

        // Update my attendance title
        updateMyAttendanceTitle(formData);

        $.ajax({
            url: '/teacher/attendance/my-attendance-matrix',
            type: 'GET',
            data: formData,
            success: function(response) {
                if (response.teacher) {
                    buildMyAttendanceMatrixTable(response);
                } else {
                    showNoMyAttendanceMessage();
                }
            },
            error: function() {
                showAlert('Failed to fetch my attendance records.', 'error');
                showNoMyAttendanceMessage();
            }
        });
    }

    // Update my attendance title
    function updateMyAttendanceTitle(formData) {
        let title = 'My Attendance Records';
        if (formData.from_date && formData.to_date) {
            title += ` from ${formData.from_date} to ${formData.to_date}`;
        } else if (formData.from_date) {
            title += ` from ${formData.from_date}`;
        } else if (formData.to_date) {
            title += ` to ${formData.to_date}`;
        }
        $('#my-attendance-records-title').text(title);
    }

    // Build my attendance matrix table
    function buildMyAttendanceMatrixTable(response) {
        const teacher = response.teacher;
        const attendance = response.attendance;
        
        let thead = `<thead><tr><th>Name</th>`;
        let tbody = '';
        
        // Build header with dates
        response.dates.forEach(function(date) {
            const dateObj = moment(date);
            thead += `<th class="text-center">${dateObj.format('DD')}<br><small>${dateObj.format('MMM')}</small></th>`;
        });
        thead += '<th class="text-center">Actions</th></tr></thead>';
        
        // Build body with teacher and attendance
        tbody += `
            <tr>
                <td>
                    <strong>${teacher.first_name} ${teacher.last_name}</strong><br>
                    <small class="text-muted">${teacher.email}</small>
                </td>
        `;
        
        // Add attendance status for each date
        response.dates.forEach(function(date) {
            const status = attendance[date];
            let statusIcon = '';
            if (status === 'present') {
                statusIcon = '<span class="text-success fw-bold"><i class="ti ti-check"></i></span>';
            } else if (status === 'absent') {
                statusIcon = '<span class="text-danger fw-bold"><i class="ti ti-x"></i></span>';
            } else if (status === 'late') {
                statusIcon = '<span class="text-warning fw-bold"><i class="ti ti-clock"></i></span>';
            } else if (status === 'excused') {
                statusIcon = '<span class="text-info fw-bold"><i class="ti ti-info-circle"></i></span>';
            } else {
                statusIcon = '<span class="text-muted">-</span>';
            }
            tbody += `<td class="text-center">${statusIcon}</td>`;
        });
        
        // Add actions column
        tbody += `
            <td class="text-center">
                <button class="btn btn-sm btn-outline-primary" onclick="viewMyAttendance()">
                    View
                </button>
            </td>
        </tr>
        `;
        
        // Update table with simple styling
        $('.table').addClass('table-bordered table-striped');
        $('.table thead').replaceWith(thead);
        $('#my-attendance-table-body').html(tbody);
    }

    // Show no my attendance message
    function showNoMyAttendanceMessage() {
        const tbody = `
            <tr>
                <td colspan="100%" class="text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-user-check text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="text-muted mb-2">No attendance records found</h6>
                    <p class="text-muted mb-0">Try adjusting your filter criteria.</p>
                </td>
            </tr>
        `;
        $('#my-attendance-table-body').html(tbody);
    }

    // View my attendance details
    window.viewMyAttendance = function() {
        showAlert('Viewing my attendance details', 'info');
    };

    // Load sections by class
    function loadSections(classId, targetSelector) {
        $.ajax({
            url: `/teacher/attendance/sections/${classId}`,
            type: 'GET',
            success: function(response) {
                let options = '<option value="">Select Section</option>';
                response.forEach(function(section) {
                    options += `<option value="${section.id}">${section.name}</option>`;
                });
                $(targetSelector).html(options);
                if (targetSelector === '#section') {
                    $('#section-field').show();
                }
            },
            error: function() {
                showAlert('Failed to load sections.', 'error');
            }
        });
    }

    // Load students for attendance marking

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
                    $('#markMyAttendanceModal').modal('hide');
                    loadMyAttendanceMatrix();
                    resetMyAttendanceForm();
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

    function getStatusBadge(status) {
        const badges = {
            'present': '<span class="badge bg-success">Present</span>',
            'absent': '<span class="badge bg-danger">Absent</span>',
            'late': '<span class="badge bg-warning">Late</span>',
            'excused': '<span class="badge bg-info">Excused</span>'
        };
        return badges[status] || `<span class="badge bg-secondary">${status}</span>`;
    }

    function getUserFromRecord(record) {
        let name = 'N/A', email = 'N/A';
        if (record.role === 'student' && record.student) {
            name = record.student.first_name + ' ' + record.student.last_name;
            email = record.student.email;
        } else if (record.role === 'teacher' && record.teacher) {
            name = record.teacher.first_name + ' ' + record.teacher.last_name;
            email = record.teacher.email;
        }
        return { name, email };
    }

    function getClassSectionText(record) {
        if (record.role === 'student') {
            const className = record.school_class ? record.school_class.name : 'N/A';
            const sectionName = record.section ? record.section.name : 'N/A';
            return `${className} - ${sectionName}`;
        } else if (record.role === 'teacher') {
            // For teacher records, show assigned classes/sections
            if (record.school_class && record.section) {
                return `${record.school_class.name} - ${record.section.name}`;
            } else if (record.school_class) {
                return record.school_class.name;
            } else {
                return 'N/A';
            }
        }
        return 'N/A';
    }


    function resetMyAttendanceForm() {
        $('#mark-my-attendance-form')[0].reset();
        $('#my-date').val(moment().format('YYYY-MM-DD'));
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
