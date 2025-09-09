/**
 * Institutions Management JavaScript
 * Handles institution status updates and delete confirmations
 */

$(document).ready(function() {
    console.log('Institutions.js loaded successfully');
    
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }
    
    // Initialize institution functionality
    initInstitutionsForm();
});

function initInstitutionsForm() {
    console.log('Initializing institution form...');
    
    // Handle status changes
    $(document).on('change', '.status-select', function() {
        const institutionId = $(this).data('institution-id');
        const newStatus = $(this).val();
        const selectElement = $(this);
        const originalValue = selectElement.find('option[selected]').val() || selectElement.val();
        
        console.log('Institution status changed:', institutionId, newStatus);
        updateInstitutionStatus(institutionId, newStatus, selectElement, originalValue);
    });
    
    // Handle delete confirmations
    $(document).on('click', '.delete-institution', function(e) {
        e.preventDefault();
        const deleteUrl = $(this).data('delete-url');
        const institutionName = $(this).data('institution-name');
        
        showDeleteConfirmation(deleteUrl, institutionName);
    });
}

function updateInstitutionStatus(institutionId, status, selectElement, originalValue) {
    $.ajax({
        url: `/admin/institutions/status/${institutionId}`,
        type: 'POST',
        data: {
            status: status,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                if (typeof toastr !== 'undefined') {
                    toastr.success('Institution status updated successfully');
                } else {
                    showToast('Institution status updated successfully', 'success');
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
            console.error('Error updating institution status:', xhr.responseText);
            if (typeof toastr !== 'undefined') {
                toastr.error('Error updating institution status');
            } else {
                showToast('Error updating institution status', 'error');
            }
            // Revert the selection on error
            selectElement.val(originalValue);
        }
    });
}

function showDeleteConfirmation(deleteUrl, institutionName) {
    // Create a simple confirmation dialog
    if (confirm(`Are you sure you want to delete the institution "${institutionName}"?`)) {
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
    // Handle institution form submission
    $('#institution-form').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        
        // Show loading state
        submitBtn.prop('disabled', true).text('Saving...');
        
        // Form will submit normally, re-enable button on page load
        setTimeout(function() {
            submitBtn.prop('disabled', false).text(originalText);
        }, 2000);
    });
    
    // Auto-generate institution code
    $('#institution_name').on('input', function() {
        const name = $(this).val();
        if (name) {
            // Generate a simple code from the name
            const code = name.replace(/[^A-Za-z0-9]/g, '').toUpperCase().substring(0, 6);
            $('#institution_code').val(code);
        }
    });
});
