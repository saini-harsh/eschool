/**
 * Non-Working Staff Management JavaScript
 * Handles staff status updates and delete confirmations
 */

$(document).ready(function() {
    console.log('NonWorkingStaff.js loaded successfully');
    
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }
    
    // Initialize non-working staff functionality
    initNonWorkingStaffForm();
});

function initNonWorkingStaffForm() {
    console.log('Initializing non-working staff form...');
    
    // Handle status changes
    $(document).on('change', '.status-select', function() {
        const staffId = $(this).data('staff-id');
        const newStatus = $(this).val();
        const selectElement = $(this);
        const originalValue = selectElement.find('option[selected]').val() || selectElement.val();
        
        console.log('Staff status changed:', staffId, newStatus);
        updateStaffStatus(staffId, newStatus, selectElement, originalValue);
    });
    
    // Handle delete confirmations
    $(document).on('click', '.delete-staff', function(e) {
        e.preventDefault();
        const deleteUrl = $(this).data('delete-url');
        const staffName = $(this).data('staff-name');
        
        showDeleteConfirmation(deleteUrl, staffName);
    });
}

function updateStaffStatus(staffId, status, selectElement, originalValue) {
    $.ajax({
        url: `/admin/nonworkingstaff/status/${staffId}`,
        type: 'POST',
        data: {
            status: status,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                if (typeof toastr !== 'undefined') {
                    toastr.success('Staff status updated successfully');
                } else {
                    showToast('Staff status updated successfully', 'success');
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
            console.error('Error updating staff status:', xhr.responseText);
            if (typeof toastr !== 'undefined') {
                toastr.error('Error updating staff status');
            } else {
                showToast('Error updating staff status', 'error');
            }
            // Revert the selection on error
            selectElement.val(originalValue);
        }
    });
}

function showDeleteConfirmation(deleteUrl, staffName) {
    // Create a simple confirmation dialog
    if (confirm(`Are you sure you want to delete the staff member "${staffName}"?`)) {
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
    // Handle staff form submission
    $('#staff-form').on('submit', function(e) {
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
    });
});
