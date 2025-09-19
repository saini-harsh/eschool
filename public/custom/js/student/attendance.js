$(document).ready(function() {
    // Initialize Flatpickr date pickers
    initializeDatePickers();
    
    // Load initial data
    loadMyAttendanceMatrix();
    loadAttendanceStats();
    
    // Filter form submission
    $('#attendance-filter-form').on('submit', function(e) {
        e.preventDefault();
        loadMyAttendanceMatrix();
        loadAttendanceStats();
    });

    // Initialize Flatpickr date pickers
    function initializeDatePickers() {
        // Get current month start and end dates
        const currentDate = moment();
        const startOfMonth = currentDate.clone().startOf('month');
        const endOfMonth = currentDate.clone().endOf('month');
        
        // Set default values to current month
        $('#from_date').val(startOfMonth.format('D MMM, YYYY'));
        $('#to_date').val(endOfMonth.format('D MMM, YYYY'));
        
        // Initialize filter date pickers
        flatpickr("#from_date", {
            dateFormat: "d M, Y",
            placeholder: "dd/mm/yyyy",
            allowInput: true,
            defaultDate: startOfMonth.format('D MMM, YYYY')
        });
        
        flatpickr("#to_date", {
            dateFormat: "d M, Y",
            placeholder: "dd/mm/yyyy",
            allowInput: true,
            defaultDate: endOfMonth.format('D MMM, YYYY')
        });
    }

    // Load my attendance matrix
    function loadMyAttendanceMatrix() {
        const formData = {
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val()
        };

        // Update attendance title
        updateAttendanceTitle(formData);

        $.ajax({
            url: '/student/attendance/my-attendance-matrix',
            type: 'GET',
            data: formData,
            success: function(response) {
                if (response.student && response.attendance) {
                    buildMyAttendanceMatrixTable(response);
                } else {
                    showNoDataMessage();
                }
            },
            error: function() {
                showAlert('Failed to fetch attendance records.', 'error');
            }
        });
    }

    // Load attendance statistics
    function loadAttendanceStats() {
        const formData = {
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val()
        };

        $.ajax({
            url: '/student/attendance/stats',
            type: 'GET',
            data: formData,
            success: function(response) {
                updateAttendanceStats(response);
            },
            error: function() {
                console.log('Failed to fetch attendance statistics.');
            }
        });
    }

    // Update attendance title
    function updateAttendanceTitle(formData) {
        let title = 'My Attendance Records';
        
        if (formData.from_date && formData.to_date) {
            title += ` from ${formData.from_date} to ${formData.to_date}`;
        } else if (formData.from_date) {
            title += ` from ${formData.from_date}`;
        } else if (formData.to_date) {
            title += ` to ${formData.to_date}`;
        }
        
        $('#attendance-title').text(title);
    }

    // Build my attendance matrix table
    function buildMyAttendanceMatrixTable(response) {
        const student = response.student;
        const attendance = response.attendance;
        
        let thead = `<thead><tr><th>Name</th>`;
        let tbody = '';
        
        // Build header with dates
        response.dates.forEach(function(date) {
            const dateObj = moment(date);
            thead += `<th class="text-center">${dateObj.format('DD')}<br><small>${dateObj.format('MMM')}</small></th>`;
        });
        thead += '<th class="text-center">Details</th></tr></thead>';
        
        // Build body with student and attendance
        tbody += `
            <tr>
                <td>
                    <strong>${student.first_name} ${student.last_name}</strong><br>
                    <small class="text-muted">${student.roll_number || student.admission_number}</small>
                </td>
        `;
        
        // Add attendance status for each date
        response.dates.forEach(function(date) {
            const attendanceData = attendance[date];
            let statusIcon = '';
            let statusText = '';
            
            if (attendanceData) {
                const status = attendanceData.status;
                const isConfirmed = attendanceData.is_confirmed;
                const markedByRole = attendanceData.marked_by_role;
                
                // Status icon only
                if (status === 'present') {
                    statusIcon = '<span class="text-success fw-bold">✓</span>';
                    statusText = 'Present';
                } else if (status === 'absent') {
                    statusIcon = '<span class="text-danger fw-bold">✗</span>';
                    statusText = 'Absent';
                } else if (status === 'late') {
                    statusIcon = '<span class="text-warning fw-bold">⏰</span>';
                    statusText = 'Late';
                } else if (status === 'excused') {
                    statusIcon = '<span class="text-info fw-bold">ℹ</span>';
                    statusText = 'Excused';
                }
            } else {
                statusIcon = '<span class="text-muted">-</span>';
                statusText = 'No Record';
            }
            
            tbody += `<td class="text-center" title="${statusText}">${statusIcon}</td>`;
        });
        
        // Add details column
        tbody += `
            <td class="text-center">
                <button class="btn btn-sm btn-outline-primary" onclick="viewAttendanceDetails()">
                    View Details
                </button>
            </td>
        </tr>
        `;
        
        // Update table with simple styling
        $('.table').addClass('table-bordered table-striped');
        $('.table thead').replaceWith(thead);
        $('#attendance-table-body').html(tbody);
    }

    // Update attendance statistics
    function updateAttendanceStats(stats) {
        $('#total-days').text(stats.totalDays || 0);
        $('#present-days').text(stats.presentDays || 0);
        $('#absent-days').text(stats.absentDays || 0);
        $('#attendance-percentage').text((stats.attendancePercentage || 0) + '%');
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
                    <p class="text-muted mb-0">Your attendance records will appear here when available.</p>
                </td>
            </tr>
        `;
        $('#attendance-table-body').html(tbody);
    }

    // View attendance details
    window.viewAttendanceDetails = function() {
        // This can be expanded to show detailed attendance information
        showAlert('Attendance details feature coming soon!', 'info');
    };

    // Show alert
    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 
                          type === 'error' ? 'alert-danger' : 'alert-info';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert
        $('.content').prepend(alertHtml);
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});