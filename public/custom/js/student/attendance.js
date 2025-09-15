$(document).ready(function() {
    // Initialize Flatpickr date pickers
    initializeDatePickers();
    
    // Filter form submission
    $('#attendance-filter-form').on('submit', function(e) {
        e.preventDefault();
        loadAttendanceRecords();
        loadAttendanceStats();
    });

    // Initialize Flatpickr date pickers
    function initializeDatePickers() {
        // Initialize start date picker
        flatpickr("#start_date", {
            dateFormat: "d M, Y",
            placeholder: "dd/mm/yyyy",
            allowInput: true
        });
        
        // Initialize end date picker
        flatpickr("#end_date", {
            dateFormat: "d M, Y",
            placeholder: "dd/mm/yyyy",
            allowInput: true
        });
    }

    // Load attendance records
    function loadAttendanceRecords() {
        const formData = {
            start_date: formatDateForAPI($('#start_date').val()),
            end_date: formatDateForAPI($('#end_date').val())
        };

        $.ajax({
            url: '/student/attendance/filter',
            type: 'GET',
            data: formData,
            success: function(response) {
                let tbody = '';
                if (response.length > 0) {
                    response.forEach(function(record) {
                        const statusBadge = getStatusBadge(record.status);
                        const confirmedBadge = record.is_confirmed 
                            ? '<span class="badge bg-success">Confirmed</span>' 
                            : '<span class="badge bg-warning">Pending</span>';
                        
                        tbody += `
                            <tr>
                                <td>${moment(record.date).format('MMM DD, YYYY')}</td>
                                <td>${statusBadge}</td>
                                <td>${record.remarks || 'N/A'}</td>
                                <td>${record.marked_by_role ? record.marked_by_role.charAt(0).toUpperCase() + record.marked_by_role.slice(1) : 'N/A'}</td>
                                <td>${confirmedBadge}</td>
                            </tr>
                        `;
                    });
                } else {
                    tbody = `
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="ti ti-clipboard-list text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <h6 class="text-muted mb-2">No attendance records found</h6>
                                <p class="text-muted mb-0">Try adjusting your filter criteria.</p>
                            </td>
                        </tr>
                    `;
                }
                $('#attendance-table-body').html(tbody);
            },
            error: function() {
                showAlert('Failed to fetch attendance records.', 'error');
            }
        });
    }

    // Load attendance statistics
    function loadAttendanceStats() {
        const formData = {
            start_date: formatDateForAPI($('#start_date').val()),
            end_date: formatDateForAPI($('#end_date').val())
        };

        $.ajax({
            url: '/student/attendance/stats',
            type: 'GET',
            data: formData,
            success: function(response) {
                // Update the statistics cards
                $('.card.bg-primary h3').text(response.totalDays);
                $('.card.bg-success h3').text(response.presentDays);
                $('.card.bg-danger h3').text(response.absentDays);
                $('.card.bg-info h3').text(response.attendancePercentage + '%');
            },
            error: function() {
                showAlert('Failed to fetch attendance statistics.', 'error');
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
        return badges[status] || `<span class="badge bg-secondary">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
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

    // Set default date range to current month
    const now = new Date();
    const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
    const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    
    // Set default dates in Flatpickr format
    $('#start_date').val(moment(firstDay).format('D MMM, YYYY'));
    $('#end_date').val(moment(lastDay).format('D MMM, YYYY'));
});
