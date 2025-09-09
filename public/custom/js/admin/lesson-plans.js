/**
 * Lesson Plans Management JavaScript
 * Handles cascading dropdowns for Institution -> Teachers -> Classes -> Subjects
 * and form submissions for lesson plan CRUD operations
 */

$(document).ready(function() {
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }
    
    // Check if we're on a lesson plan page
    if ($('#institution_id').length > 0) {
        // Initialize lesson plan functionality
        initLessonPlanForm();
    }
    
    // Always initialize status updates if we're on a page with lesson plan status selects
    if ($('.lesson-plan-status-select').length > 0) {
        initLessonPlanStatusUpdates();
    }
    
    // Initialize delete functionality
    if ($('.delete-lesson-plan').length > 0) {
        initLessonPlanDelete();
    }
    
    // Initialize edit functionality
    if ($('.edit-lesson-plan').length > 0) {
        initLessonPlanEdit();
    }
});

function initLessonPlanForm() {
    // Global variables
    let currentInstitutionId = null;
    let currentClassId = null;
    let currentTeacherId = null;
    let currentSubjectId = null;

    // CSRF token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Institution change handler
    $('#institution_id').off('change.lessonPlans').on('change.lessonPlans', function(e) {
        // Only handle if it's not a status select
        if ($(e.target).hasClass('status-select') || $(e.target).hasClass('lesson-plan-status-select')) {
            return;
        }
        
        const institutionId = $(this).val();
        currentInstitutionId = institutionId;
        
        // Clear validation errors for institution field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
        
        if (institutionId) {
            loadTeachersByInstitution(institutionId);
            // Don't load classes here - they will be loaded when teacher is selected
            resetDependentDropdowns(['class_id', 'subject_id']);
        } else {
            resetDependentDropdowns(['teacher_id', 'class_id', 'subject_id']);
        }
    });

    // Teacher change handler
    $('#teacher_id').off('change.lessonPlans').on('change.lessonPlans', function() {
        currentTeacherId = $(this).val();
        
        // Clear validation errors for teacher field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
        
        // Load classes based on selected teacher
        if (currentTeacherId && currentInstitutionId) {
            loadClassesByTeacher(currentInstitutionId, currentTeacherId);
        } else {
            resetDependentDropdowns(['class_id', 'subject_id']);
        }
    });

    // Class change handler
    $('#class_id').off('change.lessonPlans').on('change.lessonPlans', function() {
        const classId = $(this).val();
        currentClassId = classId;
        
        // Clear validation errors for class field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
        
        if (classId && currentInstitutionId) {
            loadSubjectsByInstitutionClass(currentInstitutionId, classId);
        } else {
            resetDependentDropdowns(['subject_id']);
        }
    });

    // Subject change handler
    $('#subject_id').off('change.lessonPlans').on('change.lessonPlans', function() {
        currentSubjectId = $(this).val();
        
        // Clear validation errors for subject field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });

    // Title input handler
    $('#title').off('input.lessonPlans').on('input.lessonPlans', function() {
        // Clear validation errors for title field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });

    // Description input handler
    $('#description').off('input.lessonPlans').on('input.lessonPlans', function() {
        // Clear validation errors for description field
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').text('');
    });

    // Form submission handlers
    $('#add-lesson-plan').off('click.lessonPlans').on('click.lessonPlans', function(e) {
        e.preventDefault();
        submitLessonPlanForm('create');
    });
    
    $('#update-lesson-plan').off('click.lessonPlans').on('click.lessonPlans', function(e) {
        e.preventDefault();
        submitLessonPlanForm('update');
    });
    
    $('#cancel-edit').off('click.lessonPlans').on('click.lessonPlans', function(e) {
        e.preventDefault();
        resetForm();
    });

    // Initialize form if editing
    if (window.currentLessonPlan) {
        setupEditForm();
    }
}

function loadTeachersByInstitution(institutionId) {
    const teacherSelect = $('#teacher_id');
    teacherSelect.prop('disabled', true).html('<option value="">Loading teachers...</option>');
    
    $.ajax({
        url: `/admin/lesson-plans/teachers/${institutionId}`,
        type: 'GET',
        success: function(response) {
            let options = '<option value="">Select Teacher</option>';
            response.teachers.forEach(function(teacher) {
                const fullName = `${teacher.first_name} ${teacher.middle_name || ''} ${teacher.last_name}`.trim();
                options += `<option value="${teacher.id}">${fullName}</option>`;
            });
            
            teacherSelect.html(options).prop('disabled', false);
            
            // Clear validation errors for teacher field
            teacherSelect.removeClass('is-invalid');
            teacherSelect.siblings('.invalid-feedback').text('');
            
            // Restore selected teacher if in edit mode
            if (window.currentLessonPlan && window.currentLessonPlan.teacherId) {
                teacherSelect.val(window.currentLessonPlan.teacherId);
                // Trigger teacher change to load classes
                teacherSelect.trigger('change.lessonPlans');
            }
        },
        error: function(xhr, status, error) {
            teacherSelect.html('<option value="">Error loading teachers</option>').prop('disabled', false);
            showToast('Failed to load teachers', 'error');
        }
    });
}

function loadClassesByTeacher(institutionId, teacherId) {
    const classSelect = $('#class_id');
    classSelect.prop('disabled', true).html('<option value="">Loading classes...</option>');
    
    $.ajax({
        url: `/admin/lesson-plans/classes-by-teacher/${institutionId}/${teacherId}`,
        type: 'GET',
        success: function(response) {
            let options = '<option value="">Select Class</option>';
            response.classes.forEach(function(cls) {
                options += `<option value="${cls.id}">${cls.name}</option>`;
            });
            
            classSelect.html(options).prop('disabled', false);
            
            // Clear validation errors for class field
            classSelect.removeClass('is-invalid');
            classSelect.siblings('.invalid-feedback').text('');
            
            // Restore selected class if in edit mode
            if (window.currentLessonPlan && window.currentLessonPlan.classId) {
                classSelect.val(window.currentLessonPlan.classId);
                // Trigger class change to load subjects
                classSelect.trigger('change.lessonPlans');
            }
        },
        error: function(xhr, status, error) {
            classSelect.html('<option value="">Error loading classes</option>').prop('disabled', false);
            showToast('Failed to load classes', 'error');
        }
    });
}

function loadClassesByInstitution(institutionId) {
    const classSelect = $('#class_id');
    classSelect.prop('disabled', true).html('<option value="">Loading classes...</option>');
    
    $.ajax({
        url: `/admin/lesson-plans/classes/${institutionId}`,
        type: 'GET',
        success: function(response) {
            let options = '<option value="">Select Class</option>';
            response.classes.forEach(function(cls) {
                options += `<option value="${cls.id}">${cls.name}</option>`;
            });
            
            classSelect.html(options).prop('disabled', false);
            
            // Clear validation errors for class field
            classSelect.removeClass('is-invalid');
            classSelect.siblings('.invalid-feedback').text('');
            
            // Restore selected class if in edit mode
            if (window.currentLessonPlan && window.currentLessonPlan.classId) {
                classSelect.val(window.currentLessonPlan.classId);
                // Trigger class change to load subjects
                classSelect.trigger('change.lessonPlans');
            }
        },
        error: function(xhr, status, error) {
            classSelect.html('<option value="">Error loading classes</option>').prop('disabled', false);
            showToast('Failed to load classes', 'error');
        }
    });
}

function loadSubjectsByInstitutionClass(institutionId, classId) {
    const subjectSelect = $('#subject_id');
    subjectSelect.prop('disabled', true).html('<option value="">Loading subjects...</option>');
    
    $.ajax({
        url: `/admin/lesson-plans/subjects/${institutionId}/${classId}`,
        type: 'GET',
        success: function(response) {
            let options = '<option value="">Select Subject</option>';
            response.subjects.forEach(function(subject) {
                const displayName = subject.code ? `${subject.name} (${subject.code})` : subject.name;
                options += `<option value="${subject.id}">${displayName}</option>`;
            });
            
            subjectSelect.html(options).prop('disabled', false);
            
            // Clear validation errors for subject field
            subjectSelect.removeClass('is-invalid');
            subjectSelect.siblings('.invalid-feedback').text('');
            
            // Restore selected subject if in edit mode
            if (window.currentLessonPlan && window.currentLessonPlan.subjectId) {
                // Use setTimeout to ensure the select is fully rendered
                setTimeout(function() {
                    subjectSelect.val(window.currentLessonPlan.subjectId);
                    
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
    // Trigger change events to load dependent data
    if (window.currentLessonPlan.institutionId) {
        $('#institution_id').trigger('change.lessonPlans');
    }
}

function submitLessonPlanForm(action) {
    const form = $('#lesson-plan-form');
    const lessonPlanId = $('#lesson-plan-id').val();
    
    // Get field values
    const title = $('#title').val();
    const description = $('#description').val();
    const institutionId = $('#institution_id').val();
    const teacherId = $('#teacher_id').val();
    const classId = $('#class_id').val();
    const subjectId = $('#subject_id').val();
    const status = $('#lesson-plan-status').is(':checked') ? '1' : '0';
    const fileInput = $('#lesson_plan_file')[0];
    
    
    // Check if edit form is still loading
    if (action === 'update' && window.editFormLoading) {
        showToast('Please wait, form is still loading...', 'error');
        return;
    }
    
    // Validate required fields before submission
    if (!title || !institutionId || !teacherId || !classId || !subjectId) {
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
    formData.append('subject_id', subjectId);
    formData.append('status', status);
    
    // Add file if selected (for both create and update)
    if (fileInput && fileInput.files.length > 0) {
        formData.append('lesson_plan_file', fileInput.files[0]);
    }
    
    
    let submitBtn, originalText;
    if (action === 'create') {
        submitBtn = $('#add-lesson-plan');
        originalText = submitBtn.html();
    } else {
        submitBtn = $('#update-lesson-plan');
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
        url = '/admin/lesson-plans';
        method = 'POST';
    } else {
        url = `/admin/lesson-plans/${lessonPlanId}`;
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
                showToast('Lesson plan created successfully', 'success');
                // Add new lesson plan to table
                addLessonPlanToTable(response.data);
                resetForm();
            } else {
                showToast('Lesson plan updated successfully', 'success');
                // Update existing lesson plan in table
                updateLessonPlanInTable(response.data);
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
                const errorMessage = action === 'create' ? 'Failed to create lesson plan' : 'Failed to update lesson plan';
                showToast(xhr.responseJSON?.message || errorMessage, 'error');
            }
        },
        complete: function() {
            // Re-enable submit button
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
}

function initLessonPlanStatusUpdates() {
    // Initialize event handlers for existing rows
    $('.datatable tbody tr').each(function() {
        initRowEventHandlers($(this));
    });
}

function initLessonPlanDelete() {
    // Initialize event handlers for existing rows
    $('.datatable tbody tr').each(function() {
        initRowEventHandlers($(this));
    });
}

function initLessonPlanEdit() {
    // Initialize event handlers for existing rows
    $('.datatable tbody tr').each(function() {
        initRowEventHandlers($(this));
    });
}

function addLessonPlanToTable(lessonPlan) {
    const tableBody = $('.datatable tbody');
    
    // Create new row HTML
    const newRow = createLessonPlanRow(lessonPlan);
    
    // Add to top of table
    tableBody.prepend(newRow);
    
    // Re-initialize event handlers for the new row
    initRowEventHandlers(newRow);
    
    // Show animation
    newRow.hide().fadeIn(500);
}

function updateLessonPlanInTable(lessonPlan) {
    const row = $(`tr[data-lesson-plan-id="${lessonPlan.id}"]`);
    
    if (row.length) {
        // Update the row content
        const updatedRow = createLessonPlanRow(lessonPlan);
        row.replaceWith(updatedRow);
        
        // Re-initialize event handlers for the updated row
        initRowEventHandlers(updatedRow);
        
        // Show animation
        updatedRow.hide().fadeIn(500);
    }
}

function createLessonPlanRow(lessonPlan) {
    const teacherName = `${lessonPlan.teacher?.first_name || ''} ${lessonPlan.teacher?.middle_name || ''} ${lessonPlan.teacher?.last_name || ''}`.trim();
    const institutionName = lessonPlan.institution?.name || 'N/A';
    const className = lessonPlan.school_class?.name || 'N/A';
    const subjectName = lessonPlan.subject?.name || 'N/A';
    const description = lessonPlan.description ? lessonPlan.description.substring(0, 30) + (lessonPlan.description.length > 30 ? '...' : '') : '';
    
    const fileButtons = lessonPlan.lesson_plan_file ? 
        `<a href="/${lessonPlan.lesson_plan_file}" target="_blank" class="btn btn-sm btn-outline-primary me-1" title="View PDF">
            <i class="ti ti-eye"></i>
        </a>
        <a href="/admin/lesson-plans/${lessonPlan.id}/download" class="btn btn-sm btn-outline-success" title="Download PDF">
            <i class="ti ti-download"></i>
        </a>` : 
        '<span class="text-muted">No file</span>';
    
    const statusSelect = `
        <select class="form-select status-select lesson-plan-status-select" data-lesson-plan-id="${lessonPlan.id}" data-original-value="${lessonPlan.status}">
            <option value="1" ${lessonPlan.status == 1 ? 'selected' : ''}>Active</option>
            <option value="0" ${lessonPlan.status == 0 ? 'selected' : ''}>Inactive</option>
        </select>
    `;
    
    return `
        <tr data-lesson-plan-id="${lessonPlan.id}">
            <td>
                <div class="d-flex align-items-center">
                    <div class="ms-2">
                        <h6 class="fs-14 mb-0">${lessonPlan.title}</h6>
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
                        <h6 class="fs-14 mb-0">${teacherName}</h6>
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
                        <h6 class="fs-14 mb-0">${subjectName}</h6>
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
                    <a href="javascript:void(0);" data-lesson-plan-id="${lessonPlan.id}"
                        class="btn btn-icon btn-sm btn-outline-white border-0 edit-lesson-plan">
                        <i class="ti ti-edit"></i>
                    </a>
                    <a href="javascript:void(0);" data-lesson-plan-id="${lessonPlan.id}"
                        data-lesson-plan-title="${lessonPlan.title}"
                        class="btn btn-icon btn-sm btn-outline-white border-0 delete-lesson-plan">
                        <i class="ti ti-trash"></i>
                    </a>
                </div>
            </td>
        </tr>
    `;
}

function initRowEventHandlers(row) {
    // Initialize status select handler
    row.find('.lesson-plan-status-select').off('change.lessonPlans').on('change.lessonPlans', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        const select = $(this);
        const lessonPlanId = select.data('lesson-plan-id');
        const newStatus = select.val();
        const originalValue = select.data('original-value') || select.find('option:selected').val();
        
        // Disable select during update
        select.prop('disabled', true);
        
        $.ajax({
            url: `/admin/lesson-plans/${lessonPlanId}/status`,
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
    row.find('.lesson-plan-status-select').off('click.lessonPlans').on('click.lessonPlans', function(e) {
        e.stopPropagation();
    });
    
    // Initialize edit button handler
    row.find('.edit-lesson-plan').off('click.lessonPlans').on('click.lessonPlans', function() {
        const button = $(this);
        const lessonPlanId = button.data('lesson-plan-id');
        
        // Get lesson plan data via AJAX
        $.ajax({
            url: `/admin/lesson-plans/${lessonPlanId}/edit`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const lessonPlan = response.data;
                    
                    // Populate form with data
                    $('#lesson-plan-id').val(lessonPlan.id);
                    $('#title').val(lessonPlan.title);
                    $('#description').val(lessonPlan.description || '');
                    $('#institution_id').val(lessonPlan.institution_id);
                    $('#lesson-plan-status').prop('checked', lessonPlan.status == 1);
                    
                    // Set current values for dependent dropdowns
                    window.currentLessonPlan = {
                        institutionId: lessonPlan.institution_id,
                        teacherId: lessonPlan.teacher_id,
                        classId: lessonPlan.class_id,
                        subjectId: lessonPlan.subject_id
                    };
                    
                    // Set flag to track edit form loading
                    window.editFormLoading = true;
                    
                    // Trigger institution change to load teachers first
                    $('#institution_id').trigger('change.lessonPlans');
                    
                    // Show update and cancel buttons, hide add button
                    $('#add-lesson-plan').addClass('d-none');
                    $('#update-lesson-plan').removeClass('d-none');
                    $('#cancel-edit').removeClass('d-none');
                    
                    // Make file field optional for editing
                    $('#lesson_plan_file').removeAttr('required');
                    $('#file-required').text('(Optional)').removeClass('text-danger').addClass('text-muted');
                    
                    // Clear any existing validation errors
                    $('.invalid-feedback').text('');
                    $('.is-invalid').removeClass('is-invalid');
                    
                    // Scroll to form
                    $('html, body').animate({
                        scrollTop: $('#lesson-plan-form').offset().top - 100
                    }, 500);
                    
                    showToast('Lesson plan loaded for editing', 'success');
                } else {
                    showToast('Failed to load lesson plan data', 'error');
                }
            },
            error: function(xhr, status, error) {
                showToast('Failed to load lesson plan for editing', 'error');
            }
        });
    });
    
    // Initialize delete button handler
    row.find('.delete-lesson-plan').off('click.lessonPlans').on('click.lessonPlans', function() {
        const button = $(this);
        const lessonPlanId = button.data('lesson-plan-id');
        const lessonPlanTitle = button.data('lesson-plan-title');
        
        // Show confirmation dialog
        if (confirm(`Are you sure you want to delete "${lessonPlanTitle}"? This action cannot be undone.`)) {
            $.ajax({
                url: `/admin/lesson-plans/${lessonPlanId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showToast('Lesson plan deleted successfully', 'success');
                    
                    // Remove the row from the table
                    button.closest('tr').fadeOut(300, function() {
                        $(this).remove();
                    });
                },
                error: function(xhr, status, error) {
                    showToast(xhr.responseJSON?.message || 'Failed to delete lesson plan', 'error');
                }
            });
        }
    });
}

function resetForm() {
    // Clear form
    $('#lesson-plan-form')[0].reset();
    $('#lesson-plan-id').val('');
    
    // Reset dropdowns
    $('#teacher_id').prop('disabled', true).html('<option value="">Select Teacher</option>');
    $('#class_id').prop('disabled', true).html('<option value="">Select Class</option>');
    $('#subject_id').prop('disabled', true).html('<option value="">Select Subject</option>');
    
    // Show add button, hide update and cancel buttons
    $('#add-lesson-plan').removeClass('d-none');
    $('#update-lesson-plan').addClass('d-none');
    $('#cancel-edit').addClass('d-none');
    
    // Make file field required for creating
    $('#lesson_plan_file').attr('required', 'required');
    $('#file-required').text('*').removeClass('text-muted').addClass('text-danger');
    
    // Clear validation errors
    $('.invalid-feedback').text('');
    $('.is-invalid').removeClass('is-invalid');
    
    // Clear current lesson plan data
    window.currentLessonPlan = null;
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
