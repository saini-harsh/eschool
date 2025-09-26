$(document).ready(function() {
    // Initialize admin assignment functionality
    initAdminAssignmentForm();
    initAssignmentStatusUpdates();
    initAssignmentEdit();
    initAssignmentDelete();
    initViewSubmissions();
    initGradeAssignment();
});

function initAdminAssignmentForm() {
    // Initialize flatpickr for due date
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#due_date", {
            dateFormat: "d M, Y",
            minDate: "today",
            allowInput: true
        });
    }

    // Show/hide form
    $('#add-assignment-btn').on('click', function() {
        $('#assignment-form-container').slideDown();
        resetForm();
        $('html, body').animate({
            scrollTop: $('#assignment-form').offset().top - 100
        }, 500);
    });

    $('#cancel-btn').on('click', function() {
        $('#assignment-form-container').slideUp();
        resetForm();
    });

    // Handle form submission
    $('#assignment-form').on('submit', function(e) {
        e.preventDefault();
        handleFormSubmission();
    });

    // Handle institution change
    $('#institution_id').on('change', function() {
        const institutionId = $(this).val();
        if (institutionId) {
            loadClassesByInstitution(institutionId);
            loadTeachersByInstitution(institutionId);
        } else {
            $('#class_id').html('<option value="">Select Class</option>');
            $('#teacher_id').html('<option value="">Select Teacher</option>');
        }
        $('#section_id').html('<option value="">Select Section</option>');
        $('#subject_id').html('<option value="">Select Subject</option>');
    });

    // Handle class change
    $('#class_id').on('change', function() {
        const classId = $(this).val();
        const institutionId = $('#institution_id').val();
        
        if (classId) {
            loadSectionsByClass(classId);
            if (institutionId) {
                loadSubjectsByInstitutionClass(institutionId, classId);
            }
        } else {
            $('#section_id').html('<option value="">Select Section</option>');
            $('#subject_id').html('<option value="">Select Subject</option>');
        }
    });
}

function loadClassesByInstitution(institutionId) {
    $.ajax({
        url: `/admin/assignments/classes/${institutionId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">Select Class</option>';
                response.data.forEach(function(cls) {
                    options += `<option value="${cls.id}">${cls.name}</option>`;
                });
                $('#class_id').html(options);
                
                // Restore class value if in edit mode
                if (window.currentAssignment && window.currentAssignment.classId && !window.editFormLoading) {
                    $('#class_id').val(window.currentAssignment.classId);
                }
            }
        },
        error: function(xhr) {
            showToast('Error loading classes', 'error');
        }
    });
}

function loadSectionsByClass(classId) {
    $.ajax({
        url: `/admin/assignments/sections/${classId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">Select Section</option>';
                response.data.forEach(function(section) {
                    options += `<option value="${section.id}">${section.name}</option>`;
                });
                $('#section_id').html(options);
                
                // Restore section value if in edit mode
                if (window.currentAssignment && window.currentAssignment.sectionId && !window.editFormLoading) {
                    $('#section_id').val(window.currentAssignment.sectionId);
                }
            }
        },
        error: function(xhr) {
            showToast('Error loading sections', 'error');
        }
    });
}

function loadSubjectsByInstitutionClass(institutionId, classId) {
    $.ajax({
        url: `/admin/assignments/subjects/${institutionId}/${classId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">Select Subject</option>';
                response.data.forEach(function(subject) {
                    options += `<option value="${subject.id}">${subject.name}</option>`;
                });
                $('#subject_id').html(options);
                
                // Restore subject value if in edit mode
                if (window.currentAssignment && window.currentAssignment.subjectId && !window.editFormLoading) {
                    $('#subject_id').val(window.currentAssignment.subjectId);
                }
            }
        },
        error: function(xhr) {
            showToast('Error loading subjects', 'error');
        }
    });
}

function loadTeachersByInstitution(institutionId) {
    $.ajax({
        url: `/admin/assignments/teachers/${institutionId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">Select Teacher</option>';
                response.data.forEach(function(teacher) {
                    options += `<option value="${teacher.id}">${teacher.first_name} ${teacher.last_name}</option>`;
                });
                $('#teacher_id').html(options);
                
                // Restore teacher value if in edit mode
                if (window.currentAssignment && window.currentAssignment.teacherId && !window.editFormLoading) {
                    $('#teacher_id').val(window.currentAssignment.teacherId);
                }
            }
        },
        error: function(xhr) {
            showToast('Error loading teachers', 'error');
        }
    });
}

function handleFormSubmission() {
    const form = $('#assignment-form')[0];
    const formData = new FormData(form);
    const assignmentId = $('#assignment-id').val();
    
    // Convert Flatpickr date format (DD MMM, YYYY) to database format (YYYY-MM-DD)
    let dueDate = $('#due_date').val();
    if (dueDate) {
        const date = new Date(dueDate);
        if (!isNaN(date.getTime())) {
            dueDate = date.toISOString().split('T')[0]; // Convert to YYYY-MM-DD
        }
    }
    
    // Update the form data with the converted date
    formData.set('due_date', dueDate);
    
    // Ensure CSRF token is included
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    formData.set('_token', csrfToken);
    
    const submitBtn = $('#submit-btn');
    const originalText = submitBtn.html();
    
    // Disable submit button
    submitBtn.prop('disabled', true).html('<i class="ti ti-loader"></i> Processing...');
    
    const url = assignmentId ? `/admin/assignments/${assignmentId}` : '/admin/assignments';
    const method = assignmentId ? 'POST' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                resetForm();
                if (assignmentId) {
                    updateAssignmentRow(response.data);
                } else {
                    addAssignmentRow(response.data);
                }
            } else {
                showToast(response.message, 'error');
            }
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred';
            
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON.errors) {
                    // Handle validation errors
                    const errors = xhr.responseJSON.errors;
                    const firstError = Object.values(errors)[0];
                    errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                }
            } else if (xhr.status === 419) {
                errorMessage = 'CSRF token mismatch. Please refresh the page and try again.';
            } else if (xhr.status === 422) {
                errorMessage = 'Validation error. Please check your input.';
            } else if (xhr.status === 500) {
                errorMessage = 'Server error. Please try again later.';
            }
            
            showToast(errorMessage, 'error');
        },
        complete: function() {
            // Re-enable submit button
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
}

function resetForm() {
    $('#assignment-form')[0].reset();
    $('#assignment-id').val('');
    $('#form-title').text('Add New Assignment');
    $('#submit-btn').html('<i class="ti ti-plus me-1"></i>Add Assignment');
    $('#cancel-btn').hide();
    $('#assignment-form-container').hide();
    
    // Clear dependent dropdowns
    $('#class_id').html('<option value="">Select Class</option>');
    $('#section_id').html('<option value="">Select Section</option>');
    $('#subject_id').html('<option value="">Select Subject</option>');
    $('#teacher_id').html('<option value="">Select Teacher</option>');
    
    // Clear flatpickr
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#due_date").clear();
    }
    
    // Clear global variables
    window.currentAssignment = null;
    window.editFormLoading = false;
}

function addAssignmentRow(assignment) {
    const className = assignment.school_class?.name || 'N/A';
    const sectionName = assignment.section?.name || 'N/A';
    const subjectName = assignment.subject?.name || 'N/A';
    const teacherName = assignment.teacher ? `${assignment.teacher.first_name} ${assignment.teacher.last_name}` : 'N/A';
    const institutionName = assignment.institution?.name || 'N/A';
    const dueDate = assignment.due_date ? new Date(assignment.due_date).toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    }) : 'N/A';
    const description = assignment.description ? assignment.description.substring(0, 30) + (assignment.description.length > 30 ? '...' : '') : '';
    
    const fileButtons = assignment.assignment_file ? 
        `<a href="/${assignment.assignment_file}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="View File">
            <i class="ti ti-eye"></i>
        </a>
        <a href="/admin/assignments/${assignment.id}/download" class="btn btn-sm btn-outline-success" title="Download File">
            <i class="ti ti-download"></i>
        </a>` : 
        '<span class="text-muted">No file</span>';

    const newRow = `
        <tr data-assignment-id="${assignment.id}">
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-2">
                        <h6 class="fs-14 mb-0">${assignment.title}</h6>
                        ${description ? `<small class="text-muted">${description}</small>` : ''}
                    </div>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-2">
                        <h6 class="fs-14 mb-0">${institutionName}</h6>
                    </div>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-2">
                        <h6 class="fs-14 mb-0">${className}</h6>
                    </div>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-2">
                        <h6 class="fs-14 mb-0">${sectionName}</h6>
                    </div>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-2">
                        <h6 class="fs-14 mb-0">${subjectName}</h6>
                    </div>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-2">
                        <h6 class="fs-14 mb-0">${teacherName}</h6>
                    </div>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-2">
                        <h6 class="fs-14 mb-0">${dueDate}</h6>
                    </div>
                </div>
            </td>
            <td>
                ${fileButtons}
            </td>
            <td>
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input status-toggle" 
                           data-assignment-id="${assignment.id}" 
                           ${assignment.status ? 'checked' : ''}>
                </div>
            </td>
            <td>
                <div class="d-inline-flex align-items-center">
                    <a href="javascript:void(0);" data-assignment-id="${assignment.id}"
                        class="btn btn-icon btn-sm btn-outline-white border-0 view-submissions" 
                        title="View Submissions (${assignment.student_assignments?.length || 0})">
                        <i class="ti ti-users"></i>
                    </a>
                    <a href="javascript:void(0);" data-assignment-id="${assignment.id}"
                        class="btn btn-icon btn-sm btn-outline-white border-0 edit-assignment">
                        <i class="ti ti-edit"></i>
                    </a>
                    <a href="javascript:void(0);" data-assignment-id="${assignment.id}"
                        data-assignment-title="${assignment.title}"
                        class="btn btn-icon btn-sm btn-outline-white border-0 delete-assignment">
                        <i class="ti ti-trash"></i>
                    </a>
                </div>
            </td>
        </tr>
    `;
    
    // Add to table (prepend to show newest first)
    $('.datatable tbody').prepend(newRow);
    
    // Initialize event handlers for the new row
    const row = $(`tr[data-assignment-id="${assignment.id}"]`);
    initRowEventHandlers(row);
}

function updateAssignmentRow(assignment) {
    const row = $(`tr[data-assignment-id="${assignment.id}"]`);
    if (row.length) {
        // Update the row content
        const className = assignment.school_class?.name || 'N/A';
        const sectionName = assignment.section?.name || 'N/A';
        const subjectName = assignment.subject?.name || 'N/A';
        const teacherName = assignment.teacher ? `${assignment.teacher.first_name} ${assignment.teacher.last_name}` : 'N/A';
        const institutionName = assignment.institution?.name || 'N/A';
        const dueDate = assignment.due_date ? new Date(assignment.due_date).toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        }) : 'N/A';
        const description = assignment.description ? assignment.description.substring(0, 30) + (assignment.description.length > 30 ? '...' : '') : '';
        
        const fileButtons = assignment.assignment_file ? 
            `<a href="/${assignment.assignment_file}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="View File">
                <i class="ti ti-eye"></i>
            </a>
            <a href="/admin/assignments/${assignment.id}/download" class="btn btn-sm btn-outline-success" title="Download File">
                <i class="ti ti-download"></i>
            </a>` : 
            '<span class="text-muted">No file</span>';

        // Update each cell
        row.find('td:eq(0)').html(`
            <div class="d-flex align-items-center">
                <div class="ms-2">
                    <h6 class="fs-14 mb-0">${assignment.title}</h6>
                    ${description ? `<small class="text-muted">${description}</small>` : ''}
                </div>
            </div>
        `);
        row.find('td:eq(1)').html(`
            <div class="d-flex align-items-center">
                <div class="ms-2">
                    <h6 class="fs-14 mb-0">${institutionName}</h6>
                </div>
            </div>
        `);
        row.find('td:eq(2)').html(`
            <div class="d-flex align-items-center">
                <div class="ms-2">
                    <h6 class="fs-14 mb-0">${className}</h6>
                </div>
            </div>
        `);
        row.find('td:eq(3)').html(`
            <div class="d-flex align-items-center">
                <div class="ms-2">
                    <h6 class="fs-14 mb-0">${sectionName}</h6>
                </div>
            </div>
        `);
        row.find('td:eq(4)').html(`
            <div class="d-flex align-items-center">
                <div class="ms-2">
                    <h6 class="fs-14 mb-0">${subjectName}</h6>
                </div>
            </div>
        `);
        row.find('td:eq(5)').html(`
            <div class="d-flex align-items-center">
                <div class="ms-2">
                    <h6 class="fs-14 mb-0">${teacherName}</h6>
                </div>
            </div>
        `);
        row.find('td:eq(6)').html(`
            <div class="d-flex align-items-center">
                <div class="ms-2">
                    <h6 class="fs-14 mb-0">${dueDate}</h6>
                </div>
            </div>
        `);
        row.find('td:eq(7)').html(fileButtons);
        row.find('td:eq(8)').html(`
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input status-toggle" 
                       data-assignment-id="${assignment.id}" 
                       ${assignment.status ? 'checked' : ''}>
            </div>
        `);
        row.find('td:eq(9)').html(`
            <div class="d-inline-flex align-items-center">
                <a href="javascript:void(0);" data-assignment-id="${assignment.id}"
                    class="btn btn-icon btn-sm btn-outline-white border-0 view-submissions" 
                    title="View Submissions (${assignment.student_assignments?.length || 0})">
                    <i class="ti ti-users"></i>
                </a>
                <a href="javascript:void(0);" data-assignment-id="${assignment.id}"
                    class="btn btn-icon btn-sm btn-outline-white border-0 edit-assignment">
                    <i class="ti ti-edit"></i>
                </a>
                <a href="javascript:void(0);" data-assignment-id="${assignment.id}"
                    data-assignment-title="${assignment.title}"
                    class="btn btn-icon btn-sm btn-outline-white border-0 delete-assignment">
                    <i class="ti ti-trash"></i>
                </a>
            </div>
        `);
        
        // Re-initialize event handlers
        initRowEventHandlers(row);
    }
}

function initRowEventHandlers(row) {
    // Initialize status toggle
    row.find('.status-toggle').off('change').on('change', function() {
        const assignmentId = $(this).data('assignment-id');
        const status = $(this).is(':checked') ? 1 : 0; // Convert boolean to integer
        updateAssignmentStatus(assignmentId, status);
    });
    
    // Initialize edit button
    row.find('.edit-assignment').off('click').on('click', function() {
        const assignmentId = $(this).data('assignment-id');
        editAssignment(assignmentId);
    });
    
    // Initialize delete button
    row.find('.delete-assignment').off('click').on('click', function() {
        const assignmentId = $(this).data('assignment-id');
        deleteAssignment(assignmentId);
    });
    
    // Initialize view submissions button
    row.find('.view-submissions').off('click').on('click', function() {
        const assignmentId = $(this).data('assignment-id');
        viewSubmissions(assignmentId);
    });
}

function initAssignmentStatusUpdates() {
    $('.status-toggle').off('change').on('change', function() {
        const assignmentId = $(this).data('assignment-id');
        const status = $(this).is(':checked') ? 1 : 0; // Convert boolean to integer
        updateAssignmentStatus(assignmentId, status);
    });
}

function updateAssignmentStatus(assignmentId, status) {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    $.ajax({
        url: `/admin/assignments/${assignmentId}/status`,
        method: 'POST',
        data: {
            _token: csrfToken,
            status: status
        },
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            if (response.success) {
                showToast(response.message, 'success');
            } else {
                showToast(response.message, 'error');
                // Revert the toggle (status is now 1 or 0, so !status means opposite)
                $(`.status-toggle[data-assignment-id="${assignmentId}"]`).prop('checked', status === 0);
            }
        },
        error: function(xhr) {
            let errorMessage = 'Error updating status';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 419) {
                errorMessage = 'CSRF token mismatch. Please refresh the page and try again.';
            }
            
            showToast(errorMessage, 'error');
            // Revert the toggle (status is now 1 or 0, so !status means opposite)
            $(`.status-toggle[data-assignment-id="${assignmentId}"]`).prop('checked', status === 0);
        }
    });
}

function initAssignmentEdit() {
    $('.edit-assignment').off('click').on('click', function() {
        const assignmentId = $(this).data('assignment-id');
        editAssignment(assignmentId);
    });
}

function editAssignment(assignmentId) {
    // Get assignment data via AJAX
    $.ajax({
        url: `/admin/assignments/${assignmentId}/edit`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const assignment = response.data;
                
                // Populate form with data
                $('#assignment-id').val(assignment.id);
                $('#title').val(assignment.title);
                $('#description').val(assignment.description || '');
                $('#institution_id').val(assignment.institution_id);
                
                // Format date for Flatpickr (convert from YYYY-MM-DD to DD MMM, YYYY)
                if (assignment.due_date) {
                    const date = new Date(assignment.due_date);
                    const formattedDate = date.toLocaleDateString('en-US', { 
                        day: 'numeric', 
                        month: 'short', 
                        year: 'numeric' 
                    });
                    $('#due_date').val(formattedDate);
                }
                
                $('#assignment-status').prop('checked', assignment.status == 1);
                
                // Set current values for dependent dropdowns
                window.currentAssignment = {
                    institutionId: assignment.institution_id,
                    teacherId: assignment.teacher_id,
                    classId: assignment.class_id,
                    sectionId: assignment.section_id,
                    subjectId: assignment.subject_id
                };
                
                // Set flag to track edit form loading
                window.editFormLoading = true;
                
                // Load classes and teachers first
                loadClassesByInstitution(assignment.institution_id);
                loadTeachersByInstitution(assignment.institution_id);
                
                // Wait for classes to load, then set class and load sections/subjects
                setTimeout(() => {
                    $('#class_id').val(assignment.class_id);
                    $('#class_id').trigger('change');
                    
                    // Wait for sections and subjects to load, then set their values
                    setTimeout(() => {
                        $('#section_id').val(assignment.section_id);
                        $('#subject_id').val(assignment.subject_id);
                        $('#teacher_id').val(assignment.teacher_id);
                        
                        // Clear the edit form loading flag
                        window.editFormLoading = false;
                    }, 800);
                }, 800);
                
                // Update form title and button
                $('#form-title').text('Edit Assignment');
                $('#submit-btn').html('<i class="ti ti-edit me-1"></i>Update Assignment');
                $('#cancel-btn').show();
                
                // Show form
                $('#assignment-form-container').slideDown();
                
                // Scroll to form
                $('html, body').animate({
                    scrollTop: $('#assignment-form').offset().top - 100
                }, 500);
                
            } else {
                showToast('Error loading assignment: ' + response.message, 'error');
            }
        },
        error: function(xhr) {
            const errorMessage = xhr.responseJSON?.message || 'Error loading assignment';
            showToast(errorMessage, 'error');
        }
    });
}

function initAssignmentDelete() {
    $('.delete-assignment').off('click').on('click', function() {
        const assignmentId = $(this).data('assignment-id');
        deleteAssignment(assignmentId);
    });
}

function deleteAssignment(assignmentId) {
    if (confirm('Are you sure you want to delete this assignment? This action cannot be undone.')) {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: `/admin/assignments/${assignmentId}`,
            method: 'DELETE',
            data: {
                _token: csrfToken
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    $(`tr[data-assignment-id="${assignmentId}"]`).remove();
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Error deleting assignment';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 419) {
                    errorMessage = 'CSRF token mismatch. Please refresh the page and try again.';
                }
                
                showToast(errorMessage, 'error');
            }
        });
    }
}

function initViewSubmissions() {
    $('.view-submissions').off('click').on('click', function() {
        const assignmentId = $(this).data('assignment-id');
        viewSubmissions(assignmentId);
    });
}

function viewSubmissions(assignmentId) {
    // Show loading
    $('#submissions-content').html('<div class="text-center py-4"><i class="ti ti-loader fs-24"></i><p class="mt-2">Loading submissions...</p></div>');
    $('#submissions_modal').modal('show');

    // Fetch submissions
    $.ajax({
        url: `/admin/assignments/${assignmentId}/submissions`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                displaySubmissions(response.data);
            } else {
                $('#submissions-content').html('<div class="alert alert-danger">Error loading submissions: ' + response.message + '</div>');
            }
        },
        error: function(xhr) {
            const errorMessage = xhr.responseJSON?.message || 'Error loading submissions';
            $('#submissions-content').html('<div class="alert alert-danger">' + errorMessage + '</div>');
        }
    });
}

function displaySubmissions(data) {
    const assignment = data.assignment;
    const submissions = data.submissions;

    let html = `
        <div class="mb-3">
            <h6>Assignment: ${assignment.title}</h6>
            <p class="text-muted mb-0">${assignment.institution?.name} - ${assignment.school_class?.name} ${assignment.section?.name}</p>
        </div>
    `;

    if (submissions.length === 0) {
        html += '<div class="alert alert-info">No submissions found for this assignment.</div>';
    } else {
        html += `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                            <th>Marks</th>
                            <th>Feedback</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        submissions.forEach(function(submission) {
            const studentName = submission.student ? `${submission.student.first_name} ${submission.student.last_name}` : 'N/A';
            const submissionDate = submission.submission_date ? new Date(submission.submission_date).toLocaleDateString() : 'Not submitted';
            const statusBadge = getStatusBadge(submission.status);
            const marks = submission.marks !== null ? submission.marks : '-';
            const feedback = submission.feedback || '-';

            html += `
                <tr>
                    <td>${studentName}</td>
                    <td>${submissionDate}</td>
                    <td>${statusBadge}</td>
                    <td>${marks}</td>
                    <td>${feedback}</td>
                    <td>
                        <div class="d-flex gap-1">
            `;

            if (submission.submitted_file) {
                html += `
                    <a href="/admin/assignments/submission/${submission.id}/download" 
                       class="btn btn-sm btn-outline-primary" title="Download Submission">
                        <i class="ti ti-download"></i>
                    </a>
                `;
            }

            if (submission.status === 'submitted' || submission.status === 'late') {
                html += `
                    <button class="btn btn-sm btn-outline-success grade-btn" 
                            data-student-assignment-id="${submission.id}"
                            data-assignment-id="${assignment.id}"
                            data-student-name="${studentName}"
                            title="Grade Assignment">
                        <i class="ti ti-check"></i>
                    </button>
                `;
            }

            html += `
                        </div>
                    </td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;
    }

    $('#submissions-content').html(html);

    // Initialize grade buttons
    $('.grade-btn').off('click.grade').on('click.grade', function() {
        const studentAssignmentId = $(this).data('student-assignment-id');
        const assignmentId = $(this).data('assignment-id');
        const studentName = $(this).data('student-name');

        $('#grade-student-assignment-id').val(studentAssignmentId);
        $('#grade-assignment-id').val(assignmentId);
        $('#grade-student-name').val(studentName);
        $('#grade-marks').val('');
        $('#grade-feedback').val('');

        $('#grade_modal').modal('show');
    });
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning">Pending</span>',
        'submitted': '<span class="badge bg-success">Submitted</span>',
        'late': '<span class="badge bg-danger">Late</span>',
        'graded': '<span class="badge bg-info">Graded</span>'
    };
    return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
}

function initGradeAssignment() {
    $('#submit-grade').off('click.grade').on('click.grade', function() {
        const studentAssignmentId = $('#grade-student-assignment-id').val();
        const assignmentId = $('#grade-assignment-id').val();
        const marks = $('#grade-marks').val();
        const feedback = $('#grade-feedback').val();

        if (!marks || marks < 0 || marks > 100) {
            showToast('Please enter valid marks between 0 and 100', 'error');
            return;
        }

        const submitBtn = $(this);
        const originalText = submitBtn.html();

        submitBtn.prop('disabled', true).html('<i class="ti ti-loader"></i> Grading...');

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: `/admin/assignments/${assignmentId}/grade`,
            method: 'POST',
            data: {
                _token: csrfToken,
                student_assignment_id: studentAssignmentId,
                marks: marks,
                feedback: feedback
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    $('#grade_modal').modal('hide');
                    // Refresh submissions
                    $('.view-submissions').first().click();
                } else {
                    showToast(response.message, 'error');
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Error grading assignment';
                showToast(errorMessage, 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
}

function showToast(message, type = 'info') {
    if (typeof toastr !== 'undefined') {
        // Configure toastr options
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        
        switch (type) {
            case 'success':
                toastr.success(message);
                break;
            case 'error':
                toastr.error(message);
                break;
            case 'warning':
                toastr.warning(message);
                break;
            case 'info':
            default:
                toastr.info(message);
                break;
        }
    } else {
        // Fallback to alert if toastr is not available
        if (type === 'error') {
            alert('Error: ' + message);
        } else if (type === 'success') {
            alert('Success: ' + message);
        } else {
            alert(message);
        }
    }
}
