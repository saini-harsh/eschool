$(document).ready(function() {
    // Initialize date pickers
    initializeDatePickers();
    
    // Initialize event form handlers
    initializeEventForm();
    
    // Initialize status toggle handlers
    initializeStatusToggle();
    
    // Initialize search and filter functionality
    initializeSearchAndFilter();
});

// Initialize date pickers
function initializeDatePickers() {
    $('input[data-provider="flatpickr"]').flatpickr({
        dateFormat: "d M, Y",
        allowInput: true,
        clickOpens: true,
        locale: "en"
    });
}

// Initialize event form handlers
function initializeEventForm() {
    // Handle form submission
    $('#event-form').on('submit', function(e) {
        e.preventDefault();
        submitEventForm();
    });
    
    // Handle edit button clicks
    $(document).on('click', '.edit-event-btn', function() {
        const eventId = $(this).data('id');
        editEvent(eventId);
    });
    
    // Handle delete button clicks
    $(document).on('click', '.delete-event-btn', function() {
        const eventId = $(this).data('id');
        const eventTitle = $(this).data('title') || $(this).closest('tr').find('h6 a').text();
        showDeleteModal(eventId, eventTitle);
    });
}

// Initialize status toggle handlers
function initializeStatusToggle() {
    $(document).on('change', '.status-toggle', function() {
        const eventId = $(this).data('id');
        const status = $(this).val();
        updateEventStatus(eventId, status);
    });
}

// Initialize search and filter functionality
function initializeSearchAndFilter() {
    // Search functionality
    $('.datatable-search input').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterEvents(searchTerm);
    });
    
    // Filter functionality
    $('.filter-category, .filter-status').on('change', function() {
        applyFilters();
    });
    
    // Handle delete form submission
    $("#deleteForm").on("submit", function (e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.text();
        
        // Disable submit button and show loading state
        submitBtn.prop("disabled", true).text("Deleting...");
        
        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: form.serialize(),
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            success: function (response) {
                if (response.success) {
                    showAlert(response.message || "Event deleted successfully", "success");
                    // Hide the modal
                    const deleteModal = bootstrap.Modal.getInstance(document.getElementById('delete_modal'));
                    deleteModal.hide();
                    // Refresh the event list
                    loadEvents();
                } else {
                    showAlert(response.message || "Failed to delete event", "error");
                }
            },
            error: function (xhr) {
                let errorMessage = "An error occurred while deleting the event";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert(errorMessage, "error");
            },
            complete: function () {
                // Re-enable submit button
                submitBtn.prop("disabled", false).text(originalText);
            }
        });
    });
}

// Submit event form
function submitEventForm() {
    const form = $('#event-form')[0];
    const formData = new FormData(form);
    const submitBtn = $('#submit-btn');
    const spinner = submitBtn.find('.spinner-border');
    const btnText = submitBtn.find('.btn-text');
    
    // Show loading state
    spinner.removeClass('d-none');
    btnText.text('Processing...');
    submitBtn.prop('disabled', true);
    
    const eventId = $('#event-id').val();
    const url = eventId ? `/institution/events/${eventId}` : '/institution/events';
    const method = eventId ? 'PUT' : 'POST';
    
    // Add _method field for Laravel method spoofing
    if (eventId) {
        formData.append('_method', 'PUT');
    }
    

    
    $.ajax({
        url: url,
        method: 'POST', // Always use POST for FormData with _method field
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                resetForm();
                loadEvents();
            } else {
                showAlert(response.message, 'error');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                showValidationErrors(response.errors);
            } else {
                showAlert('An error occurred. Please try again.', 'error');
            }
        },
        complete: function() {
            // Hide loading state
            spinner.addClass('d-none');
            btnText.html('<i class="ti ti-check me-1"></i>SAVE');
            submitBtn.prop('disabled', false);
        }
    });
}

// Edit event
function editEvent(eventId) {
    $.ajax({
        url: `/institution/events/${eventId}/edit`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const event = response.data;
                populateForm(event);
            } else {
                showAlert(response.message, 'error');
            }
        },
        error: function() {
            showAlert('Error fetching event details', 'error');
        }
    });
}

// Show delete modal
function showDeleteModal(eventId, eventTitle) {
    // Update modal content with event-specific information
    $("#delete_modal .modal-body h6").text("Delete Event");
    $("#delete_modal .modal-body p").text(`Are you sure you want to delete the event "${eventTitle}"?`);
    
    // Set up the delete form action
    $("#deleteForm").attr("action", `/institution/events/delete/${eventId}`);
    
    // Show the modal
    const deleteModal = new bootstrap.Modal(document.getElementById('delete_modal'));
    deleteModal.show();
}

// Delete event
function deleteEvent(eventId) {
    $.ajax({
        url: `/institution/events/delete/${eventId}`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
                // Hide the modal
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('delete_modal'));
                deleteModal.hide();
                loadEvents();
            } else {
                showAlert(response.message, 'error');
            }
        },
        error: function() {
            showAlert('Error deleting event', 'error');
        }
    });
}

// Update event status
function updateEventStatus(eventId, status) {
    $.ajax({
        url: `/institution/events/${eventId}/status`,
        method: 'POST',
        data: { status: status },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert(response.message, 'success');
            } else {
                showAlert(response.message, 'error');
            }
        },
        error: function() {
            showAlert('Error updating status', 'error');
        }
    });
}

// Load events
function loadEvents() {
    $.ajax({
        url: '/institution/events/list',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                updateTable(response.data);
            }
        },
        error: function() {
            showAlert('Error loading events', 'error');
        }
    });
}

// Update table with events data
function updateTable(events) {
    let tbody = '';
    events.forEach((event, index) => {
        const startDate = new Date(event.start_date).toLocaleDateString('en-US', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric' 
        });
        const dateTime = event.start_time ? `${startDate}<br><small class="text-muted">${event.start_time}</small>` : startDate;
        const roleBadge = event.role ? `<span class=\"badge bg-primary\">${(event.role || '').charAt(0).toUpperCase() + (event.role || '').slice(1)}</span>` : '<span class="text-muted">N/A</span>';
        const institutionText = event.institution_name || 'N/A';
        tbody += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-rounded bg-light">
                            <i class="ti ti-calendar-event text-primary"></i>
                        </div>
                        <div class="ms-2">
                            <h6 class="fs-14 mb-0"><a href="javascript:void(0);">${event.title}</a></h6>
                            ${event.description ? `<small class="text-muted">${event.description.substring(0, 50)}${event.description.length > 50 ? '...' : ''}</small>` : ''}
                        </div>
                    </div>
                </td>
                <td>${roleBadge}</td>
                <td><span class="badge" style="background-color: ${event.color}; color: white;">${event.category}</span></td>
                <td><span class="text-muted">${institutionText}</span></td>
                <td><span class="text-muted">${event.location || 'N/A'}</span></td>
                <td>${dateTime}</td>
                <td>
                    <div>
                        <select class="select status-toggle" data-id="${event.id}">
                            <option value="1" ${event.status ? 'selected' : ''}>Active</option>
                            <option value="0" ${!event.status ? 'selected' : ''}>Inactive</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div class="d-inline-flex align-items-center">
                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 edit-event-btn" data-id="${event.id}">
                            <i class="ti ti-edit"></i>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 delete-event-btn" data-id="${event.id}" data-title="${event.title}">
                            <i class="ti ti-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        `;
    });
    $('#event-table tbody').html(tbody);
}

// Populate form with event data
function populateForm(event) {
    $('#event-id').val(event.id);
    $('input[name="title"]').val(event.title);
    $('input[name="location"]').val(event.location);
    $('input[name="start_date"]').val(formatDateForPicker(event.start_date));
    $('input[name="start_time"]').val(event.start_time);
    $('select[name="category"]').val(event.category);
    $('textarea[name="description"]').val(event.description);
    $('input[name="url"]').val(event.url);
    $('input[name="status"]').prop('checked', event.status == 1);

    // Set role and institution
    if (event.role !== undefined) {
        $('select[name="role"]').val(event.role).trigger('change');
    }
    if (event.institution_id !== undefined) {
        $('select[name="institution_id"]').val(String(event.institution_id)).trigger('change');
    }

    // Update button text
    $('#submit-btn .btn-text').html('<i class="ti ti-check me-1"></i>UPDATE EVENT');
}

// Reset form
function resetForm() {
    $('#event-form')[0].reset();
    $('#event-id').val('');
    $('#submit-btn .btn-text').html('<i class="ti ti-check me-1"></i>SAVE');
    
    // Clear date picker and time field
    $('input[name="start_date"]').val('');
    $('input[name="start_time"]').val('');
}

// Format date for date picker
function formatDateForPicker(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    if (isNaN(date.getTime())) {
        console.error('Invalid date:', dateString);
        return '';
    }
    
    const day = date.getDate().toString().padStart(2, '0');
    const month = date.toLocaleDateString('en-US', { month: 'short' });
    const year = date.getFullYear();
    
    return `${day} ${month}, ${year}`;
}

// Filter events
function filterEvents(searchTerm) {
    $('#event-table tbody tr').each(function() {
        const text = $(this).text().toLowerCase();
        if (text.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

// Apply filters
function applyFilters() {
    const categoryFilter = $('.filter-category').val();
    const statusFilter = $('.filter-status').val();
    
    $('#event-table tbody tr').each(function() {
        const category = $(this).find('td:nth-child(2)').text().trim();
        const status = $(this).find('td:nth-child(6) select').val();
        
        let showRow = true;
        
        if (categoryFilter && category !== categoryFilter) {
            showRow = false;
        }
        
        if (statusFilter && status !== statusFilter) {
            showRow = false;
        }
        
        if (showRow) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

// Show alert
function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const alertContainer = document.createElement('div');
    alertContainer.style.position = 'fixed';
    alertContainer.style.top = '20px';
    alertContainer.style.right = '20px';
    alertContainer.style.zIndex = '9999';
    alertContainer.innerHTML = alertHtml;
    
    document.body.appendChild(alertContainer);
    
    setTimeout(() => {
        alertContainer.remove();
    }, 5000);
}

// Show validation errors
function showValidationErrors(errors) {
    let errorMessage = 'Validation failed:<br>';
    for (const field in errors) {
        errorMessage += `- ${errors[field][0]}<br>`;
    }
    showAlert(errorMessage, 'error');
}

// Export functions for global access
window.EventManager = {
    editEvent,
    deleteEvent,
    loadEvents,
    resetForm
};
