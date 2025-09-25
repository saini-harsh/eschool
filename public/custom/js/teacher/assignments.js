/**
 * Teacher Assignment Management JavaScript
 * Handles cascading dropdowns for Classes -> Sections -> Subjects
 * and form submissions for assignment CRUD operations
 */

$(document).ready(function() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }
    
    // Check if we're on an assignment page
    if ($('#class_id').length > 0) {
        // Initialize assignment functionality
        initTeacherAssignmentForm();
    }
    
    // Always initialize status updates if we're on a page with assignment status selects
    if ($('.assignment-status-select').length > 0) {
        initAssignmentStatusUpdates();
    }
    
    // Initialize delete functionality
    if ($('.delete-assignment').length > 0) {
        initAssignmentDelete();
    }
    
    // Initialize edit functionality
    if ($('.edit-assignment').length > 0) {
        initAssignmentEdit();
    }
    
    // Initialize view submissions functionality
    if ($('.view-submissions').length > 0) {
        initViewSubmissions();
    }
    
    // Initialize grade functionality
    if ($('#submit-grade').length > 0) {
        initGradeAssignment();
    }
});

function initTeacherAssignmentForm() {
    // Global variables
    let currentInstitutionId = $('#institution_id').val(); // Auto-set from logged-in teacher
    let currentClassId = null;
    let currentSubjectId = null;

    // CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Class change handler
    $('#class_id').off('change.assignments').on('change.assignments', function() {
        const classId = $(this).val();
        currentClassId = classId;
        
        // Clear validation errors for class field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
        
        if (classId && currentInstitutionId) {
            loadSectionsByClass(classId);
            loadSubjectsByInstitutionClass(currentInstitutionId, classId);
        } else {
            resetDependentDropdowns(['section_id', 'subject_id']);
        }
    });

    // Section change handler
    $('#section_id').off('change.assignments').on('change.assignments', function() {
        // Clear validation errors for section field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });

    // Subject change handler
    $('#subject_id').off('change.assignments').on('change.assignments', function() {
        currentSubjectId = $(this).val();
        
        // Clear validation errors for subject field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });

    // Title input handler
    $('#title').off('input.assignments').on('input.assignments', function() {
        // Clear validation errors for title field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });

    // Description input handler
    $('#description').off('input.assignments').on('input.assignments', function() {
        // Clear validation errors for description field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });

    // Due date input handler
    $('#due_date').off('change.assignments').on('change.assignments', function() {
        // Clear validation errors for due date field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });

    // Form submission handlers
    $('#add-assignment').off('click.assignments').on('click.assignments', function(e) {
        e.preventDefault();
        submitAssignmentForm('create');
    });
    
    $('#update-assignment').off('click.assignments').on('click.assignments', function(e) {
        e.preventDefault();
        submitAssignmentForm('update');
    });
    
    $('#cancel-edit').off('click.assignments').on('click.assignments', function(e) {
        e.preventDefault();
        resetForm();
    });

    // Initialize form if editing
    if (window.currentAssignment) {
        setupEditForm();
    }
}

function loadSectionsByClass(classId) {
    const sectionSelect = $('#section_id');
    sectionSelect.prop('disabled', true).html('<option value="">Loading sections...</option>');
    
    $.ajax({
        url: `/teacher/assignments/sections/${classId}`,
        type: 'GET',
        success: function(response) {
            let options = '<option value="">Select Section</option>';
            response.data.forEach(function(section) {
                options += `<option value="${section.id}">${section.name}</option>`;
            });
            
            sectionSelect.html(options).prop('disabled', false);
            
            // Clear validation errors for section field
            sectionSelect.removeClass('is-invalid');
            sectionSelect.siblings('.invalid-feedback').text('');
            
            // Restore selected section if in edit mode
            if (window.currentAssignment && window.currentAssignment.sectionId) {
                sectionSelect.val(window.currentAssignment.sectionId);
            }
        },
        error: function(xhr, status, error) {
            sectionSelect.html('<option value="">Error loading sections</option>').prop('disabled', false);
            showToast('Failed to load sections', 'error');
        }
    });
}

function loadSubjectsByInstitutionClass(institutionId, classId) {
    const subjectSelect = $('#subject_id');
    subjectSelect.prop('disabled', true).html('<option value="">Loading subjects...</option>');
    
    $.ajax({
        url: `/teacher/assignments/subjects/${institutionId}/${classId}`,
        type: 'GET',
        success: function(response) {
            let options = '<option value="">Select Subject</option>';
            response.data.forEach(function(subject) {
                const displayName = subject.code ? `${subject.name} (${subject.code})` : subject.name;
                options += `<option value="${subject.id}">${displayName}</option>`;
            });
            
            subjectSelect.html(options).prop('disabled', false);
            
            // Clear validation errors for subject field
            subjectSelect.removeClass('is-invalid');
            subjectSelect.siblings('.invalid-feedback').text('');
            
            // Restore selected subject if in edit mode
            if (window.currentAssignment && window.currentAssignment.subjectId) {
                // Use setTimeout to ensure the select is fully rendered
                setTimeout(function() {
                    subjectSelect.val(window.currentAssignment.subjectId);
                    
                    // Mark edit form as fully loaded
                    window.editFormLoading = false;
                }, 100);
            } else {
                // Mark edit form as fully loaded even if no subject to select
                window.editFormLoading = false;
            }
        },
        error: function(xhr, status, error) {
            subjectSelect.html('<option value="">Error loading subjects</option>').prop('disabled', false);
            showToast('Failed to load subjects', 'error');
        }
    });
}

function resetDependentDropdowns(dropdownIds) {
    dropdownIds.forEach(function(id) {
        const select = $(`#${id}`);
        select.prop('disabled', true).html('<option value="">Select ' + id.replace('_id', '').replace('_', ' ') + '</option>');
    });
}

function setupEditForm() {
    // Set class and trigger change to load sections and subjects
    if (window.currentAssignment.classId) {
        $('#class_id').val(window.currentAssignment.classId);
        $('#class_id').trigger('change.assignments');
    }
}

function submitAssignmentForm(action) {
    const form = $('#assignment-form');
    const assignmentId = $('#assignment-id').val();
    
    // Get field values
    const title = $('#title').val();
    const description = $('#description').val();
    const institutionId = $('#institution_id').val();
    const teacherId = $('#teacher_id').val();
    const classId = $('#class_id').val();
    const sectionId = $('#section_id').val();
    const subjectId = $('#subject_id').val();
    // Convert Flatpickr date format (DD MMM, YYYY) to database format (YYYY-MM-DD)
    let dueDate = $('#due_date').val();
    if (dueDate) {
        const date = new Date(dueDate);
        if (!isNaN(date.getTime())) {
            dueDate = date.toISOString().split('T')[0]; // Convert to YYYY-MM-DD
        }
    }
    const status = $('#assignment-status').is(':checked') ? '1' : '0';
    const fileInput = $('#assignment_file')[0];
    
    // Check if edit form is still loading
    if (action === 'update' && window.editFormLoading) {
        showToast('Please wait, form is still loading...', 'error');
        return;
    }
    
    // Validate required fields before submission
    if (!title || !institutionId || !teacherId || !classId || !sectionId || !subjectId || !dueDate) {
        showToast('Please ensure all required fields are filled', 'error');
        return;
    }
    
    // Create FormData object
    const formData = new FormData();
    formData.append('title', title);
    formData.append('description', description);
    formData.append('institution_id', institutionId);
    formData.append('teacher_id', teacherId);
    formData.append('class_id', classId);
    formData.append('section_id', sectionId);
    formData.append('subject_id', subjectId);
    formData.append('due_date', dueDate);
    formData.append('status', status);
    
    // Add file if selected (for both create and update)
    if (fileInput && fileInput.files.length > 0) {
        formData.append('assignment_file', fileInput.files[0]);
    }
    
    let submitBtn, originalText;
    if (action === 'create') {
        submitBtn = $('#add-assignment');
        originalText = submitBtn.html();
    } else {
        submitBtn = $('#update-assignment');
        originalText = submitBtn.html();
    }
    
    // Disable submit button and show loading state
    submitBtn.prop('disabled', true).html('<i class="ti ti-loader me-1"></i> Saving...');
    
    // Clear previous error messages
    $('.invalid-feedback').text('');
    $('.is-invalid').removeClass('is-invalid');
    
    // Determine the URL and method
    let url, method;
    if (action === 'create') {
        url = '/teacher/assignments';
        method = 'POST';
    } else {
        url = `/teacher/assignments/${assignmentId}`;
        method = 'POST';
    }
    
    $.ajax({
        url: url,
        type: method,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (action === 'create') {
                showToast('Assignment created successfully', 'success');
                // Add new assignment to table
                addAssignmentToTable(response.data);
                resetForm();
            } else {
                showToast('Assignment updated successfully', 'success');
                // Update existing assignment in table
                updateAssignmentInTable(response.data);
                resetForm();
            }
        },
        error: function(xhr, status, error) {
            if (xhr.status === 422) {
                // Validation errors
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(function(field) {
                    const input = form.find(`[name="${field}"]`);
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(errors[field][0]);
                });
                showToast('Please fix the validation errors', 'error');
            } else {
                const errorMessage = action === 'create' ? 'Failed to create assignment' : 'Failed to update assignment';
                showToast(xhr.responseJSON?.message || errorMessage, 'error');
            }
        },
        complete: function() {
            // Re-enable submit button
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
}

function initAssignmentStatusUpdates() {
    // Initialize event handlers for existing rows
    $('.datatable tbody tr').each(function() {
        initRowEventHandlers($(this));
    });
}

function initAssignmentDelete() {
    // Initialize event handlers for existing rows
    $('.datatable tbody tr').each(function() {
        initRowEventHandlers($(this));
    });
}

function initAssignmentEdit() {
    // Initialize event handlers for existing rows
    $('.datatable tbody tr').each(function() {
        initRowEventHandlers($(this));
    });
}

function addAssignmentToTable(assignment) {
    const tableBody = $('.datatable tbody');
    
    // Create new row HTML
    const newRow = createAssignmentRow(assignment);
    
    // Add to top of table
    tableBody.prepend(newRow);
    
    // Re-initialize event handlers for the new row
    initRowEventHandlers(newRow);
    
    // Show animation
    newRow.hide().fadeIn(500);
}

function updateAssignmentInTable(assignment) {
    const row = $(`tr[data-assignment-id="${assignment.id}"]`);
    
    if (row.length) {
        // Update the row content
        const updatedRow = createAssignmentRow(assignment);
        row.replaceWith(updatedRow);
        
        // Re-initialize event handlers for the updated row
        initRowEventHandlers(updatedRow);
        
        // Show animation
        updatedRow.hide().fadeIn(500);
    }
}

function createAssignmentRow(assignment) {
    const className = assignment.school_class?.name || 'N/A';
    const sectionName = assignment.section?.name || 'N/A';
    const subjectName = assignment.subject?.name || 'N/A';
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
        <a href="/teacher/assignments/${assignment.id}/download" class="btn btn-sm btn-outline-success" title="Download File">
            <i class="ti ti-download"></i>
        </a>` : 
        '<span class="text-muted">No file</span>';
    
    const statusSelect = `
        <select class="form-select status-select assignment-status-select" data-assignment-id="${assignment.id}" data-original-value="${assignment.status}">
            <option value="1" ${assignment.status == 1 ? 'selected' : ''}>Active</option>
            <option value="0" ${assignment.status == 0 ? 'selected' : ''}>Inactive</option>
        </select>
    `;
    
    return `
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
                        <h6 class="fs-14 mb-0">${dueDate}</h6>
                    </div>
                </div>
            </td>
            <td>
                ${fileButtons}
            </td>
            <td>
                <div>
                    ${statusSelect}
                </div>
            </td>
            <td>
                <div class="d-inline-flex align-items-center">
                    <a href="javascript:void(0);" data-assignment-id="${assignment.id}"
                        class="btn btn-icon btn-sm btn-outline-white border-0 view-submissions" 
                        title="View Submissions (${assignment.student_assignments ? assignment.student_assignments.length : 0})">
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
}

function initRowEventHandlers(row) {
    // Initialize status select handler
    row.find('.assignment-status-select').off('change.assignments').on('change.assignments', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        const select = $(this);
        const assignmentId = select.data('assignment-id');
        const newStatus = select.val();
        const originalValue = select.data('original-value') || select.find('option:selected').val();
        
        // Disable select during update
        select.prop('disabled', true);
        
        $.ajax({
            url: `/teacher/assignments/${assignmentId}/status`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: newStatus
            },
            success: function(response) {
                showToast('Status updated successfully', 'success');
                
                // Update the select with new value
                select.val(newStatus);
                select.data('original-value', newStatus);
                
                // Re-enable select
                select.prop('disabled', false);
            },
            error: function(xhr, status, error) {
                showToast('Failed to update status', 'error');
                
                // Revert the select value
                select.val(originalValue);
                
                // Re-enable select
                select.prop('disabled', false);
            }
        });
        
        return false;
    });
    
    // Also prevent any form events from being triggered by status select
    row.find('.assignment-status-select').off('click.assignments').on('click.assignments', function(e) {
        e.stopPropagation();
    });
    
    // Initialize edit button handler
    row.find('.edit-assignment').off('click.assignments').on('click.assignments', function() {
        const button = $(this);
        const assignmentId = button.data('assignment-id');
        
        // Get assignment data via AJAX
        $.ajax({
            url: `/teacher/assignments/${assignmentId}/edit`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const assignment = response.data;
                    
                    // Populate form with data
                    $('#assignment-id').val(assignment.id);
                    $('#title').val(assignment.title);
                    $('#description').val(assignment.description || '');
                    
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
                    
                    // Set class and trigger change to load sections and subjects
                    $('#class_id').val(assignment.class_id);
                    $('#class_id').trigger('change.assignments');
                    
                    // Show update and cancel buttons, hide add button
                    $('#add-assignment').addClass('d-none');
                    $('#update-assignment').removeClass('d-none');
                    $('#cancel-edit').removeClass('d-none');
                    
                    // Make file field optional for editing
                    $('#assignment_file').removeAttr('required');
                    $('#file-required').text('(Optional)').removeClass('text-danger').addClass('text-muted');
                    
                    // Clear any existing validation errors
                    $('.invalid-feedback').text('');
                    $('.is-invalid').removeClass('is-invalid');
                    
                    // Scroll to form
                    $('html, body').animate({
                        scrollTop: $('#assignment-form').offset().top - 100
                    }, 500);
                    
                    showToast('Assignment loaded for editing', 'success');
                } else {
                    showToast('Failed to load assignment data', 'error');
                }
            },
            error: function(xhr, status, error) {
                showToast('Failed to load assignment for editing', 'error');
            }
        });
    });
    
    // Initialize view submissions button handler
    row.find('.view-submissions').off('click.assignments').on('click.assignments', function() {
        const button = $(this);
        const assignmentId = button.data('assignment-id');
        
        // Show loading
        $('#submissions-content').html('<div class="text-center py-4"><i class="ti ti-loader fs-24"></i><p class="mt-2">Loading submissions...</p></div>');
        $('#submissions_modal').modal('show');
        
        // Fetch submissions
        $.ajax({
            url: `/teacher/assignments/${assignmentId}/submissions`,
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
    });
    
    // Initialize delete button handler
    row.find('.delete-assignment').off('click.assignments').on('click.assignments', function() {
        const button = $(this);
        const assignmentId = button.data('assignment-id');
        const assignmentTitle = button.data('assignment-title');
        
        // Show confirmation dialog
        if (confirm(`Are you sure you want to delete "${assignmentTitle}"? This action cannot be undone.`)) {
            $.ajax({
                url: `/teacher/assignments/${assignmentId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showToast('Assignment deleted successfully', 'success');
                    
                    // Remove the row from the table
                    button.closest('tr').fadeOut(300, function() {
                        $(this).remove();
                    });
                },
                error: function(xhr, status, error) {
                    showToast(xhr.responseJSON?.message || 'Failed to delete assignment', 'error');
                }
            });
        }
    });
}

function resetForm() {
    // Clear form
    $('#assignment-form')[0].reset();
    $('#assignment-id').val('');
    
    // Reset dropdowns (teacher is auto-set, so only reset class, section and subject)
    $('#class_id').prop('disabled', false).val('');
    $('#section_id').prop('disabled', true).html('<option value="">Select Section</option>');
    $('#subject_id').prop('disabled', true).html('<option value="">Select Subject</option>');
    
    // Show add button, hide update and cancel buttons
    $('#add-assignment').removeClass('d-none');
    $('#update-assignment').addClass('d-none');
    $('#cancel-edit').addClass('d-none');
    
    // Make file field required for creating
    $('#assignment_file').attr('required', 'required');
    $('#file-required').text('*').removeClass('text-muted').addClass('text-danger');
    
    // Clear validation errors
    $('.invalid-feedback').text('');
    $('.is-invalid').removeClass('is-invalid');
    
    // Clear current assignment data
    window.currentAssignment = null;
    window.editFormLoading = false;
}

function showToast(message, type) {
    // Remove existing toasts
    $('.toast').remove();
    
    const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';
    const icon = type === 'success' ? 'ti-check' : 'ti-alert-circle';
    
    const toast = $(`
        <div class="toast align-items-center text-white ${toastClass} border-0" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ${icon} me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `);
    
    // Add toast to body
    $('body').append(toast);
    
    // Show toast
    try {
        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
            const bsToast = new bootstrap.Toast(toast[0]);
            bsToast.show();
        } else {
            // Fallback: show toast without Bootstrap
            toast.show();
        }
    } catch (error) {
        // Fallback to simple alert
        alert(message);
    }
    
    // Auto remove after 3 seconds
    setTimeout(function() {
        $('.toast').remove();
    }, 3000);
}

function initViewSubmissions() {
    $('.view-submissions').off('click.submissions').on('click.submissions', function(e) {
        e.preventDefault();
        
        const assignmentId = $(this).data('assignment-id');
        
        // Show loading
        $('#submissions-content').html('<div class="text-center py-4"><i class="ti ti-loader fs-24"></i><p class="mt-2">Loading submissions...</p></div>');
        $('#submissions_modal').modal('show');
        
        // Fetch submissions
        $.ajax({
            url: `/teacher/assignments/${assignmentId}/submissions`,
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
    });
}

function displaySubmissions(data) {
    const assignment = data.assignment;
    const submissions = data.submissions;
    
    let html = `
        <div class="mb-3">
            <h6 class="fw-bold">${assignment.title}</h6>
            <p class="text-muted mb-0">${assignment.school_class?.name || 'N/A'} - ${assignment.section?.name || 'N/A'} | ${assignment.subject?.name || 'N/A'}</p>
        </div>
    `;
    
    if (submissions.length === 0) {
        html += '<div class="alert alert-info">No submissions yet.</div>';
    } else {
        html += `
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                            <th>Marks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        submissions.forEach(submission => {
            const student = submission.student;
            const statusClass = {
                'pending': 'secondary',
                'submitted': 'success',
                'late': 'warning',
                'graded': 'primary'
            }[submission.status] || 'secondary';
            
            html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-2">
                                <img src="${student.photo ? student.photo : '/adminpanel/img/default-avatar.png'}" alt="Student" class="rounded-circle">
                            </div>
                            <div>
                                <h6 class="mb-0">${student.first_name} ${student.middle_name || ''} ${student.last_name}</h6>
                                <small class="text-muted">${student.roll_number || 'N/A'}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        ${submission.submission_date ? new Date(submission.submission_date).toLocaleDateString() : 'Not submitted'}
                    </td>
                    <td>
                        <span class="badge bg-${statusClass}">${submission.status.charAt(0).toUpperCase() + submission.status.slice(1)}</span>
                    </td>
                    <td>
                        ${submission.marks ? `<span class="fw-bold text-success">${submission.marks}/100</span>` : '<span class="text-muted">Not graded</span>'}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
            `;
            
            if (submission.submitted_file) {
                html += `
                    <a href="/teacher/assignments/submission/${submission.id}/download" class="btn btn-sm btn-outline-primary" title="Download Submission">
                        <i class="ti ti-download"></i>
                    </a>
                `;
            }
            
            if (submission.status === 'submitted' || submission.status === 'late') {
                html += `
                    <button class="btn btn-sm btn-outline-success grade-btn" 
                            data-student-assignment-id="${submission.id}"
                            data-assignment-id="${assignment.id}"
                            data-student-name="${student.first_name} ${student.middle_name || ''} ${student.last_name}"
                            title="Grade Assignment">
                        <i class="ti ti-edit"></i>
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
        
        $.ajax({
            url: `/teacher/assignments/${assignmentId}/grade`,
            method: 'POST',
            data: {
                student_assignment_id: studentAssignmentId,
                marks: marks,
                feedback: feedback
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
