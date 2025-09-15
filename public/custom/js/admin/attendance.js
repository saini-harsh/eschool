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
            // Load classes if institution is already selected
            const institutionId = $('#institution').val();
            if (institutionId) {
                loadClasses(institutionId, '#class');
            }
        } else {
            $('#class-field, #section-field').hide();
            $('#class, #section').val('').trigger('change');
        }
    });

    // Institution change handler for filter form
    $('#institution').on('change', function() {
        const institutionId = $(this).val();
        if (institutionId && $('#role').val() === 'student') {
            loadClasses(institutionId, '#class');
        }
    });

    // Class change handler for filter form
    $('#class').on('change', function() {
        const classId = $(this).val();
        if (classId) {
            loadSections(classId, '#section');
        }
    });

    // Modal role change handler
    $('#modal-role').on('change', function() {
        const role = $(this).val();
        if (role === 'student') {
            $('#modal-class-field, #modal-section-field').show();
            // Load classes if institution is already selected
            const institutionId = $('#modal-institution').val();
            if (institutionId) {
                loadClasses(institutionId, '#modal-class');
            }
        } else {
            $('#modal-class-field, #modal-section-field').hide();
            $('#modal-class, #modal-section').val('').trigger('change');
        }
    });

    // Modal institution change handler
    $('#modal-institution').on('change', function() {
        const institutionId = $(this).val();
        if (institutionId && $('#modal-role').val() === 'student') {
            loadClasses(institutionId, '#modal-class');
        }
    });

    // Modal class change handler
    $('#modal-class').on('change', function() {
        const classId = $(this).val();
        if (classId) {
            loadSections(classId, '#modal-section');
        }
    });

    // Load users button click
    $('#load-users-btn').on('click', function() {
        loadUsersForAttendance();
    });

    // Save attendance button click
    $('#save-attendance-btn').on('click', function() {
        saveAttendance();
    });

    // Update attendance button click
    $('#update-attendance-btn').on('click', function() {
        updateAttendance();
    });

    // Initialize Flatpickr date pickers
    function initializeDatePickers() {
        // Initialize filter date picker
        flatpickr("#date", {
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
    }

    // Load attendance records
    function loadAttendanceRecords() {
        const formData = {
            institution: $('#institution').val(),
            role: $('#role').val(),
            class: $('#class').val(),
            section: $('#section').val(),
            date: formatDateForAPI($('#date').val())
        };

        $.ajax({
            url: '/admin/attendance/filter',
            type: 'GET',
            data: formData,
            success: function(response) {
                let tbody = '';
                if (response.length > 0) {
                    response.forEach(function(record) {
                        const user = getUserFromRecord(record);
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
                                            <h6 class="mb-0 fs-14">${user.name}</h6>
                                            <small class="text-muted">${user.email}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-primary">${record.role.charAt(0).toUpperCase() + record.role.slice(1)}</span></td>
                                <td>${record.institution ? record.institution.name : 'N/A'}</td>
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
                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteAttendance(${record.id})">
                                                <i class="ti ti-trash me-1"></i>Delete
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
                            <td colspan="9" class="text-center py-5">
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

    // Load classes by institution
    function loadClasses(institutionId, targetSelector) {
        $.ajax({
            url: `/admin/attendance/classes/${institutionId}`,
            type: 'GET',
            success: function(response) {
                let options = '<option value="">Select Class</option>';
                response.forEach(function(cls) {
                    options += `<option value="${cls.id}">${cls.name}</option>`;
                });
                $(targetSelector).html(options);
            },
            error: function() {
                showAlert('Failed to load classes.', 'error');
            }
        });
    }

    // Load sections by class
    function loadSections(classId, targetSelector) {
        $.ajax({
            url: `/admin/attendance/sections/${classId}`,
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

    // Load users for attendance marking
    function loadUsersForAttendance() {
        const institutionId = $('#modal-institution').val();
        const role = $('#modal-role').val();
        const classId = $('#modal-class').val();
        const sectionId = $('#modal-section').val();

        if (!institutionId || !role) {
            showAlert('Please select institution and role.', 'warning');
            return;
        }

        if (role === 'student' && (!classId || !sectionId)) {
            showAlert('Please select class and section for students.', 'warning');
            return;
        }

        let url = '';
        let data = {};

        if (role === 'student') {
            url = '/admin/attendance/students';
            data = {
                institution_id: institutionId,
                class_id: classId,
                section_id: sectionId
            };
        } else if (role === 'teacher') {
            url = `/admin/attendance/teachers/${institutionId}`;
        } else if (role === 'nonworkingstaff') {
            url = `/admin/attendance/staff/${institutionId}`;
        }

        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            success: function(response) {
                let tbody = '';
                response.forEach(function(user) {
                    const rollId = role === 'student' ? (user.roll_number || user.admission_number) : 
                                  role === 'teacher' ? user.email : 
                                  user.designation || user.email;
                    
                    tbody += `
                        <tr>
                            <td>${user.first_name} ${user.last_name}</td>
                            <td>${rollId}</td>
                            <td>
                                <select class="form-select form-select-sm" name="status_${user.id}">
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="late">Late</option>
                                    <option value="excused">Excused</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="remarks_${user.id}" placeholder="Remarks">
                            </td>
                        </tr>
                    `;
                });
                $('#users-table-body').html(tbody);
                $('#users-list').show();
                $('#save-attendance-btn').show();
            },
            error: function() {
                showAlert('Failed to load users.', 'error');
            }
        });
    }

    // Save attendance
    function saveAttendance() {
        const formData = {
            institution_id: $('#modal-institution').val(),
            role: $('#modal-role').val(),
            class_id: $('#modal-class').val(),
            section_id: $('#modal-section').val(),
            date: formatDateForAPI($('#modal-date').val()),
            attendance_data: []
        };

        // Collect attendance data
        $('#users-table-body tr').each(function() {
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
            url: '/admin/attendance/mark',
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

    // Update attendance
    function updateAttendance() {
        const attendanceId = $('#edit-attendance-id').val();
        const formData = {
            status: $('#edit-status').val(),
            remarks: $('#edit-remarks').val()
        };

        $.ajax({
            url: `/admin/attendance/${attendanceId}`,
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

    // Delete attendance
    window.deleteAttendance = function(id) {
        if (confirm('Are you sure you want to delete this attendance record?')) {
            $.ajax({
                url: `/admin/attendance/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert(response.message, 'success');
                        loadAttendanceRecords();
                    } else {
                        showAlert(response.message, 'error');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    showAlert(response.message || 'Failed to delete attendance.', 'error');
                }
            });
        }
    };

    // Edit attendance
    window.editAttendance = function(id, status, remarks) {
        $('#edit-attendance-id').val(id);
        $('#edit-status').val(status);
        $('#edit-remarks').val(remarks);
        $('#editAttendanceModal').modal('show');
    };

    // Helper functions
    function formatDateForAPI(dateString) {
        if (!dateString) return '';
        // Convert from "d M, Y" format to "Y-m-d" format for API
        const date = moment(dateString, 'D MMM, YYYY');
        return date.isValid() ? date.format('YYYY-MM-D') : '';
    }

    function getUserFromRecord(record) {
        let name = 'N/A', email = 'N/A';
        if (record.role === 'student' && record.student) {
            name = record.student.first_name + ' ' + record.student.last_name;
            email = record.student.email;
        } else if (record.role === 'teacher' && record.teacher) {
            name = record.teacher.first_name + ' ' + record.teacher.last_name;
            email = record.teacher.email;
        } else if (record.role === 'nonworkingstaff' && record.staff) {
            name = record.staff.first_name + ' ' + record.staff.last_name;
            email = record.staff.email;
        }
        return { name, email };
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

    function getClassSectionText(record) {
        if (record.role === 'student') {
            const className = record.school_class ? record.school_class.name : 'N/A';
            const sectionName = record.section ? record.section.name : 'N/A';
            return `${className} - ${sectionName}`;
        }
        return 'N/A';
    }

    function resetMarkAttendanceForm() {
        $('#mark-attendance-form')[0].reset();
        $('#modal-class-field, #modal-section-field').hide();
        $('#users-list').hide();
        $('#save-attendance-btn').hide();
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
