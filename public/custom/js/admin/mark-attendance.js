$(document).ready(function() {
    // Initialize Flatpickr date picker
    flatpickr("#date", {
        dateFormat: "d M, Y",
        defaultDate: "today",
        placeholder: "dd/mm/yyyy",
        allowInput: true
    });

    // Institution change handler
    $('#institution').on('change', function() {
        const institutionId = $(this).val();
        if (institutionId) {
            loadClasses(institutionId);
        } else {
            $('#class-field, #section-field').hide();
            $('#class, #section').val('').trigger('change');
        }
    });

    // Role change handler
    $('#role').on('change', function() {
        const role = $(this).val();
        if (role === 'student') {
            $('#class-field, #section-field').show();
        } else {
            $('#class-field, #section-field').hide();
            $('#class, #section').val('').trigger('change');
        }
    });

    // Class change handler
    $(document).on('change', '#class', function() {
        const classId = $(this).val();
        if (classId) {
            loadSections(classId);
        } else {
            $('#section-field').hide();
            $('#section').val('');
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

    // Load classes by institution
    function loadClasses(institutionId) {
        $.ajax({
            url: `/admin/attendance/classes/${institutionId}`,
            type: 'GET',
            success: function(response) {
                let options = '<option value="">Select Class</option>';
                response.forEach(function(classItem) {
                    options += `<option value="${classItem.id}">${classItem.name}</option>`;
                });
                $('#class').html(options);
                $('#class-field').show();
            },
            error: function() {
                showAlert('Failed to load classes.', 'error');
            }
        });
    }

    // Load sections by class
    function loadSections(classId) {
        $.ajax({
            url: `/admin/attendance/sections/${classId}`,
            type: 'GET',
            success: function(response) {
                let options = '<option value="">Select Section</option>';
                response.forEach(function(section) {
                    options += `<option value="${section.id}">${section.name}</option>`;
                });
                $('#section').html(options);
                $('#section-field').show();
            },
            error: function() {
                showAlert('Failed to load sections.', 'error');
            }
        });
    }

    // Load users for attendance marking
    function loadUsersForAttendance() {
        const role = $('#role').val();
        const institutionId = $('#institution').val();
        const classId = $('#class').val();
        const sectionId = $('#section').val();
        const date = $('#date').val();

        if (!role) {
            showAlert('Please select role.', 'warning');
            return;
        }

        if (!institutionId) {
            showAlert('Please select institution.', 'warning');
            return;
        }

        if (role === 'student' && (!classId || !sectionId)) {
            showAlert('Please select class and section for students.', 'warning');
            return;
        }

        if (!date) {
            showAlert('Please select date.', 'warning');
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
                buildUsersTable(response, role);
                updateUsersTitle(role, institutionId, classId, sectionId, date);
            },
            error: function() {
                showAlert('Failed to load users.', 'error');
            }
        });
    }

    // Build users table
    function buildUsersTable(users, role) {
        let tbody = '';
        users.forEach(function(user) {
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
    }

    // Update users title
    function updateUsersTitle(role, institutionId, classId, sectionId, date) {
        let title = `${role.charAt(0).toUpperCase() + role.slice(1)}s List`;
        
        const institutionName = $('#institution option:selected').text();
        title = `${institutionName} - ${title}`;
        
        if (role === 'student' && classId && sectionId) {
            const className = $('#class option:selected').text();
            const sectionName = $('#section option:selected').text();
            title = `${className} - ${sectionName} Students`;
        }
        title += ` - ${date}`;
        $('#users-title').text(title);
    }

    // Save attendance
    function saveAttendance() {
        const formData = {
            role: $('#role').val(),
            institution_id: $('#institution').val(),
            class_id: $('#class').val(),
            section_id: $('#section').val(),
            date: formatDateForAPI($('#date').val()),
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
                    resetForm();
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

    // Reset form
    function resetForm() {
        $('#mark-attendance-form')[0].reset();
        $('#users-list').hide();
        $('#save-attendance-btn').hide();
        $('#class-field, #section-field').hide();
    }

    // Helper functions
    function formatDateForAPI(dateString) {
        if (!dateString) return '';
        // Convert from "d M, Y" format to "Y-m-d" format for API
        const date = moment(dateString, 'D MMM, YYYY');
        return date.isValid() ? date.format('Y-MM-DD') : '';
    }

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
