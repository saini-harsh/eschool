$(document).ready(function() {
    $('#attendance-filter-form').on('submit', function(e) {
        e.preventDefault();

        let institution = $('#institution').val();
        let role = $('#role').val();

        $.ajax({
            url: '/admin/attendance/filter',
            type: 'GET',
            data: {
                institution: institution,
                role: role
            },
            success: function(response) {
                let tbody = '';
                console.log(response.length);
                if (response.length > 0) {
                    response.forEach(function(record) {
                        let name = 'N/A', email = 'N/A';
                        if (record.role === 'student' && record.student) {
                            name = record.student.first_name + ' ' + record.student.last_name;
                            email = record.student.email;
                        } else if (record.role === 'teacher' && record.teacher) {
                            name = record.teacher.first_name + ' ' + record.teacher.last_name;
                            email = record.teacher.email;
                        } else if (record.role === 'staff' && record.staff) {
                            name = record.staff.first_name + ' ' + record.staff.last_name;
                            email = record.staff.email;
                        }
                        let institutionName = record.institution ? record.institution.name : 'N/A';
                        let statusBadge = '';
                        if (record.status === 'present') {
                            statusBadge = '<span class="badge bg-success">Present</span>';
                        } else if (record.status === 'absent') {
                            statusBadge = '<span class="badge bg-danger">Absent</span>';
                        } else if (record.status === 'late') {
                            statusBadge = '<span class="badge bg-warning">Late</span>';
                        } else {
                            statusBadge = `<span class="badge bg-secondary">${record.status}</span>`;
                        }
                        tbody += `
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm rounded-circle bg-light border me-2">
                                            <i class="ti ti-user text-muted"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fs-14">${name}</h6>
                                            <small class="text-muted">${email}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-primary">${record.role.charAt(0).toUpperCase() + record.role.slice(1)}</span></td>
                                <td>${institutionName}</td>
                                <td>${moment(record.date).format('MMM DD, YYYY')}</td>
                                <td>${statusBadge}</td>
                            </tr>
                        `;
                    });
                } else {
                    tbody = `<tr><td colspan="5" class="text-center py-5">No attendance records found</td></tr>`;
                }
                console.log(tbody);
                $('#attendance-table-body').html(tbody);
            },
            error: function() {
                alert('Failed to fetch attendance records.');
            }
        });
    });

    $("#attendance-filter-form").on("change", "#role", function() {
        let role = $(this).val();
        if (role === 'student') {
            $('#student-data-fields').css('display', 'flex');
        }else {
            $('#student-data-fields').css('display', 'none');
        }
    });
});
