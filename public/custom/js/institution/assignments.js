/**
 * Institution Assignment Management JavaScript
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
        initInstitutionAssignmentForm();
    }
    
    // Always initialize status updates if we're on a page with assignment status selects
    if ($('.status-toggle').length > 0) {
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

function initInstitutionAssignmentForm() {
    // Global variables
    let currentInstitutionId = $('#institution_id').val(); // Auto-set from logged-in institution
    let currentClassId = null;
    let currentSubjectId = null;
    
    // Initialize flatpickr for due date
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#due_date", {
            dateFormat: "d M, Y",
            minDate: "today",
            allowInput: true
        });
    }
    
    // Initialize form submission
    $('#assignment-form').on('submit', function(e) {
        e.preventDefault();
        handleFormSubmission();
    });
    
    // Initialize cancel button
    $('#cancel-btn').on('click', function() {
        resetForm();
    });
    
    // Initialize class change handler
    $('#class_id').on('change', function() {
        currentClassId = $(this).val();
        if (currentClassId) {
            loadSections(currentClassId);
            loadSubjects(currentInstitutionId, currentClassId);
        } else {
            clearSections();
            clearSubjects();
        }
    });
    
    // Initialize section change handler
    $('#section_id').on('change', function() {
        // Sections don't affect other dropdowns in institution view
    });
    
    // Initialize subject change handler
    $('#subject_id').on('change', function() {
        currentSubjectId = $(this).val();
    });
    
    // Initialize teacher change handler
    $('#teacher_id').on('change', function() {
        // Teachers don't affect other dropdowns
    });
}

function loadSections(classId) {
    const sectionSelect = $('#section_id');
    sectionSelect.prop('disabled', true).html('<option value="">Loading sections...</option>');
    
    $.ajax({
        url: `/institution/assignments/sections/${classId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">Select Section</option>';
                response.data.forEach(function(section) {
                    options += `<option value="${section.id}">${section.name}</option>`;
                });
                
                sectionSelect.html(options).prop('disabled', false);
                
                // Restore selected section if in edit mode
                if (window.currentAssignment && window.currentAssignment.sectionId) {
                    sectionSelect.val(window.currentAssignment.sectionId);
                }
            } else {
                sectionSelect.html('<option value="">Error loading sections</option>').prop('disabled', false);
                showToast('Error loading sections: ' + response.message, 'error');
            }
        },
        error: function(xhr) {
            sectionSelect.html('<option value="">Error loading sections</option>').prop('disabled', false);
            const errorMessage = xhr.responseJSON?.message || 'Error loading sections';
            showToast(errorMessage, 'error');
        }
    });
}

function loadSubjects(institutionId, classId) {
    const subjectSelect = $('#subject_id');
    subjectSelect.prop('disabled', true).html('<option value="">Loading subjects...</option>');
    
    $.ajax({
        url: `/institution/assignments/subjects/${institutionId}/${classId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                let options = '<option value="">Select Subject</option>';
                response.data.forEach(function(subject) {
                    const displayName = subject.code ? `${subject.name} (${subject.code})` : subject.name;
                    options += `<option value="${subject.id}">${displayName}</option>`;
                });
                
                subjectSelect.html(options).prop('disabled', false);
                
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
            } else {
                subjectSelect.html('<option value="">Error loading subjects</option>').prop('disabled', false);
                showToast('Error loading subjects: ' + response.message, 'error');
            }
        },
        error: function(xhr) {
            subjectSelect.html('<option value="">Error loading subjects</option>').prop('disabled', false);
            const errorMessage = xhr.responseJSON?.message || 'Error loading subjects';
            showToast(errorMessage, 'error');
        }
    });
}

function clearSections() {
    $('#section_id').html('<option value="">Select Section</option>');
}

function clearSubjects() {
    $('#subject_id').html('<option value="">Select Subject</option>');
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
    
    const url = assignmentId ? `/institution/assignments/${assignmentId}` : '/institution/assignments';
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
    $('#submit-btn').html('<i class="ti ti-plus me-1"></i>Add Assignment');
    $('#cancel-btn').hide();
    $('#assignment-status').prop('checked', true);
    
    // Clear global variables
    window.currentAssignment = null;
    window.editFormLoading = false;
    
    // Clear flatpickr date
    if (typeof flatpickr !== 'undefined') {
        const flatpickrInstance = flatpickr("#due_date");
        if (flatpickrInstance) {
            flatpickrInstance.clear();
        }
    }
    
    // Clear dependent dropdowns
    clearSections();
    clearSubjects();
}

function addAssignmentRow(assignment) {
    const tbody = $('#assignments-table-body');
    
    // Remove empty row if exists
    tbody.find('tr:has(td[colspan="9"])').remove();
    
    const row = createAssignmentRow(assignment);
    tbody.prepend(row);
    
    // Initialize row event handlers
    initRowEventHandlers(row);
}

function updateAssignmentRow(assignment) {
    const row = $(`tr[data-assignment-id="${assignment.id}"]`);
    if (row.length) {
        row.replaceWith(createAssignmentRow(assignment));
        initRowEventHandlers(row);
    }
}

function createAssignmentRow(assignment) {
    const dueDate = new Date(assignment.due_date);
    const isOverdue = dueDate < new Date();
    const statusBadge = isOverdue ? 'bg-danger' : 'bg-success';
    const statusText = isOverdue ? 'Overdue' : 'Active';
    
    const fileLink = assignment.assignment_file ? 
        `<small class="text-muted">
            <i class="ti ti-file-text me-1"></i>
            <a href="${assignment.assignment_file}" target="_blank" class="text-decoration-none">View File</a>
        </small>` : '';
    
    return `
        <tr data-assignment-id="${assignment.id}">
            <td>
                <div class="d-flex flex-column">
                    <span class="fw-semibold">${assignment.title}</span>
                    ${fileLink}
                </div>
            </td>
            <td>${assignment.school_class?.name || 'N/A'}</td>
            <td>${assignment.section?.name || 'N/A'}</td>
            <td>${assignment.subject?.name || 'N/A'}</td>
            <td>${assignment.teacher?.first_name || ''} ${assignment.teacher?.last_name || ''}</td>
            <td>
                <span class="badge ${statusBadge}">
                    ${dueDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                </span>
            </td>
            <td>
                <a href="javascript:void(0);" data-assignment-id="${assignment.id}"
                    class="btn btn-icon btn-sm btn-outline-primary border-0 view-submissions"
                    title="View Submissions (${assignment.student_assignments?.length || 0})">
                    <i class="ti ti-users"></i>
                </a>
            </td>
            <td>
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input status-toggle" 
                           data-assignment-id="${assignment.id}" 
                           ${assignment.status ? 'checked' : ''}>
                </div>
            </td>
            <td>
                <div class="d-flex gap-1">
                    <button class="btn btn-icon btn-sm btn-outline-primary border-0 edit-assignment" 
                            data-assignment-id="${assignment.id}" title="Edit">
                        <i class="ti ti-edit"></i>
                    </button>
                    <button class="btn btn-icon btn-sm btn-outline-danger border-0 delete-assignment" 
                            data-assignment-id="${assignment.id}" title="Delete">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;
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
        url: `/institution/assignments/${assignmentId}/status`,
        method: 'POST',
        data: {
            status: status,
            _token: csrfToken
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
        url: `/institution/assignments/${assignmentId}/edit`,
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
                $('#class_id').trigger('change');
                
                // Set teacher selection
                $('#teacher_id').val(assignment.teacher_id);
                
                // Update submit button
                $('#submit-btn').html('<i class="ti ti-edit me-1"></i>Update Assignment');
                $('#cancel-btn').show();
                
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
            url: `/institution/assignments/${assignmentId}`,
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
                    
                    // Check if table is empty
                    if ($('#assignments-table-body tr').length === 0) {
                        $('#assignments-table-body').html(`
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ti ti-clipboard-list fs-48 text-muted mb-2"></i>
                                        <span class="text-muted">No assignments found</span>
                                    </div>
                                </td>
                            </tr>
                        `);
                    }
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
                } else if (xhr.status === 404) {
                    errorMessage = 'Assignment not found.';
                }
                
                showToast(errorMessage, 'error');
            }
        });
    }
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
            url: `/institution/assignments/${assignmentId}/submissions`,
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
            <h6>Assignment: ${assignment.title}</h6>
            <p class="text-muted mb-0">Class: ${assignment.school_class?.name || 'N/A'} - Section: ${assignment.section?.name || 'N/A'}</p>
            <p class="text-muted mb-0">Subject: ${assignment.subject?.name || 'N/A'} - Teacher: ${assignment.teacher?.first_name || ''} ${assignment.teacher?.last_name || ''}</p>
        </div>
    `;
    
    if (submissions.length === 0) {
        html += '<div class="alert alert-info">No submissions found for this assignment.</div>';
    } else {
        html += `
            <div class="table-responsive">
                <table class="table table-hover">
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
        
        submissions.forEach(function(submission) {
            const submissionDate = submission.submission_date ? 
                new Date(submission.submission_date).toLocaleDateString() : 'Not submitted';
            
            const statusBadge = getStatusBadge(submission.status);
            const marks = submission.marks !== null ? submission.marks : 'Not graded';
            
            html += `
                <tr>
                    <td>${submission.student?.first_name || ''} ${submission.student?.last_name || ''}</td>
                    <td>${submissionDate}</td>
                    <td><span class="badge ${statusBadge.class}">${statusBadge.text}</span></td>
                    <td>${marks}</td>
                    <td>
                        <div class="d-flex gap-1">
            `;
            
            if (submission.submitted_file) {
                html += `
                    <a href="/institution/assignments/submission/${submission.id}/download" 
                       class="btn btn-icon btn-sm btn-outline-primary border-0" title="Download">
                        <i class="ti ti-download"></i>
                    </a>
                `;
            }
            
            if (submission.status === 'submitted' || submission.status === 'late') {
                html += `
                    <button class="btn btn-icon btn-sm btn-outline-success border-0 grade-btn" 
                            data-student-assignment-id="${submission.id}"
                            data-assignment-id="${assignment.id}"
                            data-student-name="${submission.student?.first_name || ''} ${submission.student?.last_name || ''}"
                            title="Grade">
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
    switch (status) {
        case 'pending':
            return { class: 'bg-warning', text: 'Pending' };
        case 'submitted':
            return { class: 'bg-success', text: 'Submitted' };
        case 'late':
            return { class: 'bg-danger', text: 'Late' };
        case 'graded':
            return { class: 'bg-info', text: 'Graded' };
        default:
            return { class: 'bg-secondary', text: 'Unknown' };
    }
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
            url: `/institution/assignments/${assignmentId}/grade`,
            method: 'POST',
            data: {
                student_assignment_id: studentAssignmentId,
                marks: marks,
                feedback: feedback,
                _token: csrfToken
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
                let errorMessage = 'Error grading assignment';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 419) {
                    errorMessage = 'CSRF token mismatch. Please refresh the page and try again.';
                } else if (xhr.status === 422) {
                    errorMessage = 'Validation error. Please check your input.';
                }
                
                showToast(errorMessage, 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
}

// Toast notification function
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
