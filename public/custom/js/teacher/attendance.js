$(document).ready(function() {
    // Filter form submission
    $('#attendance-filter-form').on('submit', function(e) {
        e.preventDefault();
        loadAttendanceRecords();
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

    // Modal class change handler
    $('#modal-class').on('change', function() {
        const classId = $(this).val();
        if (classId) {
            loadSections(classId, '#modal-section');
        }
    });

    // Load students button click
    $('#load-students-btn').on('click', function() {
        loadStudentsForAttendance();
    });

    // Save attendance button click
    $('#save-attendance-btn').on('click', function() {
        saveAttendance();
    });

    // Save my attendance button click
    $('#save-my-attendance-btn').on('click', function() {
        saveMyAttendance();
    });

    // Update attendance button click
    $('#update-attendance-btn').on('click', function() {
        updateAttendance();
    });

    // Load my attendance button click
    $('#load-my-attendance-btn').on('click', function() {
        loadMyAttendance();
    });

    // Load attendance records
    function loadAttendanceRecords() {
        const formData = {
            class: $('#class').val(),
            section: $('#section').val(),
            date: $('#date').val()
        };

        $.ajax({
            url: '/teacher/attendance/filter',
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
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm rounded-circle bg-light border me-2">
                                            <i class="ti ti-user text-muted"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fs-14">${record.student.first_name} ${record.student.last_name}</h6>
                                            <small class="text-muted">${record.student.email}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>${getClassSectionText(record)}</td>
                                <td>${moment(record.date).format('MMM DD, YYYY')}</td>
                                <td>${statusBadge}</td>
                                <td>${record.marked_by ? (record.marked_by_role || 'N/A') : 'N/A'}</td>
                                <td>${confirmedBadge}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="editAttendance(${record.id}, '${record.status}', '${record.remarks || ''}')">
                                                <i class="ti ti-edit me-1"></i>Edit
                                            </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tbody = `
                        <tr>
                            <td colspan="7" class="text-center py-5">
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
    function loadStudentsForAttendance() {
        const classId = $('#modal-class').val();
        const sectionId = $('#modal-section').val();

        if (!classId || !sectionId) {
            showAlert('Please select class and section.', 'warning');
            return;
        }

        $.ajax({
            url: '/teacher/attendance/students',
            type: 'GET',
            data: {
                class_id: classId,
                section_id: sectionId
            },
            success: function(response) {
                let tbody = '';
                response.forEach(function(student) {
                    tbody += `
                        <tr>
                            <td>${student.first_name} ${student.last_name}</td>
                            <td>${student.roll_number || student.admission_number}</td>
                            <td>
                                <select class="form-select form-select-sm" name="status_${student.id}">
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="late">Late</option>
                                    <option value="excused">Excused</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="remarks_${student.id}" placeholder="Remarks">
                            </td>
                        </tr>
                    `;
                });
                $('#students-table-body').html(tbody);
                $('#students-list').show();
                $('#save-attendance-btn').show();
            },
            error: function() {
                showAlert('Failed to load students.', 'error');
            }
        });
    }

    // Save attendance
    function saveAttendance() {
        const formData = {
            class_id: $('#modal-class').val(),
            section_id: $('#modal-section').val(),
            date: $('#modal-date').val(),
            attendance_data: []
        };

        // Collect attendance data
        $('#students-table-body tr').each(function() {
            const row = $(this);
            const userId = row.find('select').attr('name').split('_')[1];
            const status = row.find('select').val();
            const remarks = row.find('input[type="text"]').val();

            formData.attendance_data.push({
                user_id: userId,
                status: status,
                remarks: remarks
            });
        });

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
                    $('#markAttendanceModal').modal('hide');
                    loadAttendanceRecords();
                    resetMarkAttendanceForm();
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
            date: $('#my-date').val(),
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
                    loadMyAttendance();
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

    // Load my attendance
    function loadMyAttendance() {
        $.ajax({
            url: '/teacher/attendance/my-attendance',
            type: 'GET',
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
                                <td>${record.marked_by ? (record.marked_by_role || 'N/A') : 'N/A'}</td>
                                <td>${confirmedBadge}</td>
                                <td>${record.remarks || 'N/A'}</td>
                            </tr>
                        `;
                    });
                } else {
                    tbody = `
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                <div class="mb-3">
                                    <i class="ti ti-clipboard-list text-muted" style="font-size: 2rem;"></i>
                                </div>
                                <h6 class="text-muted mb-2">No attendance records found</h6>
                                <p class="text-muted mb-0">Mark your attendance to see records here.</p>
                            </td>
                        </tr>
                    `;
                }
                $('#my-attendance-table-body').html(tbody);
            },
            error: function() {
                showAlert('Failed to fetch your attendance records.', 'error');
            }
        });
    }

    // Update attendance
    function updateAttendance() {
        const attendanceId = $('#edit-attendance-id').val();
        const formData = {
            status: $('#edit-status').val(),
            remarks: $('#edit-remarks').val()
        };

        $.ajax({
            url: `/teacher/attendance/${attendanceId}`,
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    $('#editAttendanceModal').modal('hide');
                    loadAttendanceRecords();
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert(response.message || 'Failed to update attendance.', 'error');
            }
        });
    }

    // Edit attendance
    window.editAttendance = function(id, status, remarks) {
        $('#edit-attendance-id').val(id);
        $('#edit-status').val(status);
        $('#edit-remarks').val(remarks);
        $('#editAttendanceModal').modal('show');
    };

    // Helper functions
    function getStatusBadge(status) {
        const badges = {
            'present': '<span class="badge bg-success">Present</span>',
            'absent': '<span class="badge bg-danger">Absent</span>',
            'late': '<span class="badge bg-warning">Late</span>',
            'excused': '<span class="badge bg-info">Excused</span>'
        };
        return badges[status] || `<span class="badge bg-secondary">${status}</span>`;
    }

    function getClassSectionText(record) {
        const className = record.schoolClass ? record.schoolClass.name : 'N/A';
        const sectionName = record.section ? record.section.name : 'N/A';
        return `${className} - ${sectionName}`;
    }

    function resetMarkAttendanceForm() {
        $('#mark-attendance-form')[0].reset();
        $('#students-list').hide();
        $('#save-attendance-btn').hide();
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
