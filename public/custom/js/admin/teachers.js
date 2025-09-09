/**
 * Teachers Management JavaScript
 * Handles teacher status updates and delete confirmations
 */

$(document).ready(function() {
    console.log('Teachers.js loaded successfully');
    
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }
    
    // Initialize teacher functionality
    initTeachersForm();
});

function initTeachersForm() {
    console.log('Initializing teacher form...');
    
    // Handle status changes
    $(document).on('change', '.status-select', function() {
        const teacherId = $(this).data('teacher-id');
        const newStatus = $(this).val();
        const selectElement = $(this);
        const originalValue = selectElement.find('option[selected]').val() || selectElement.val();
        
        console.log('Teacher status changed:', teacherId, newStatus);
        updateTeacherStatus(teacherId, newStatus, selectElement, originalValue);
    });
    
    // Handle delete confirmations
    $(document).on('click', '.delete-teacher', function(e) {
        e.preventDefault();
        const deleteUrl = $(this).data('delete-url');
        const teacherName = $(this).data('teacher-name');
        
        showDeleteConfirmation(deleteUrl, teacherName);
    });
}

function updateTeacherStatus(teacherId, status, selectElement, originalValue) {
    $.ajax({
        url: `/admin/teachers/status/${teacherId}`,
        type: 'POST',
        data: {
            status: status,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                if (typeof toastr !== 'undefined') {
                    toastr.success('Teacher status updated successfully');
                } else {
                    showToast('Teacher status updated successfully', 'success');
                }
                // Update the selected attribute
                selectElement.find('option').removeAttr('selected');
                selectElement.find('option[value="' + status + '"]').attr('selected', 'selected');
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(response.message || 'Failed to update status');
                } else {
                    showToast(response.message || 'Failed to update status', 'error');
                }
                // Revert the selection on error
                selectElement.val(originalValue);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error updating teacher status:', xhr.responseText);
            if (typeof toastr !== 'undefined') {
                toastr.error('Error updating teacher status');
            } else {
                showToast('Error updating teacher status', 'error');
            }
            // Revert the selection on error
            selectElement.val(originalValue);
        }
    });
}

function showDeleteConfirmation(deleteUrl, teacherName) {
    // Create a simple confirmation dialog
    if (confirm(`Are you sure you want to delete the teacher "${teacherName}"?`)) {
        // Create a form and submit it
        const form = $('<form>', {
            'method': 'POST',
            'action': deleteUrl
        });
        
        form.append($('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': $('meta[name="csrf-token"]').attr('content')
        }));
        
        $('body').append(form);
        form.submit();
    }
}

/**
 * Show toast notification
 */
function showToast(message, type) {
    // Use toastr if available, otherwise fallback to custom toast
    if (typeof toastr !== 'undefined') {
        if (type === 'success') {
            toastr.success(message);
        } else {
            toastr.error(message);
        }
    } else {
        // Fallback to custom toast
        var toastHtml = `
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                <div class="toast align-items-center text-bg-${type === 'success' ? 'success' : 'danger'} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        `;
        
        // Add toast to page
        $('body').append(toastHtml);
        
        // Auto-hide after 3 seconds
        setTimeout(function() {
            $('.toast').remove();
        }, 3000);
    }
}

// Handle form submissions for create/edit
$(document).ready(function() {
    // Handle teacher form submission
    $('#teacher-form').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        
        // Show loading state
        submitBtn.prop('disabled', true).text('Saving...');
        
        // Form will submit normally, re-enable button on page load
        setTimeout(function() {
            submitBtn.prop('disabled', false).text(originalText);
        }, 2000);
    });
    
    // Handle institution change for cascading dropdowns
    $('#institution_id').on('change', function() {
        const institutionId = $(this).val();
        console.log('Institution changed to:', institutionId);
        
        // You can add cascading dropdown logic here if needed
        // For example, loading subjects or other institution-specific data
    });
});
