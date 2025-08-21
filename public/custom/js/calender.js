document.addEventListener('DOMContentLoaded', function() {
    let calendar;
    let currentEventId = null;

    // Get CSRF token function
    function getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            return token.getAttribute('content');
        }
        const inputToken = document.querySelector('input[name="_token"]');
        if (inputToken) {
            return inputToken.value;
        }
        return null;
    }

    // Check if Bootstrap is available
    function showBootstrapModal(modalId) {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
            
            // Reinitialize date pickers when modal is shown
            setTimeout(() => {
                initializeDatePickers();
            }, 100);
        } else if (typeof $ !== 'undefined') {
            $('#' + modalId).modal('show');
            
            // Reinitialize date pickers when modal is shown
            setTimeout(() => {
                initializeDatePickers();
            }, 100);
        }
    }

    function hideBootstrapModal(modalId) {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
            if (modal) modal.hide();
        } else if (typeof $ !== 'undefined') {
            $('#' + modalId).modal('hide');
        }
    }

    // Initialize date pickers
    function initializeDatePickers() {
        if (typeof flatpickr !== 'undefined') {
            // Initialize date picker
            const dateInput = document.getElementById('startDate');
            if (dateInput && !dateInput._flatpickr) {
                flatpickr(dateInput, {
                    dateFormat: "d M, Y"
                });
            }
            
            // Initialize time picker specifically
            const timeInput = document.getElementById('startTime');
            if (timeInput && !timeInput._flatpickr) {
                flatpickr(timeInput, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true
                });
            }
        }
    }
    
    // Initialize pickers when page loads
    initializeDatePickers();
    
    // Initialize FullCalendar
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        if (typeof FullCalendar === 'undefined') {
            return;
        }
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            editable: true,
            selectable: true,
            selectMirror: true,
            dayMaxEvents: true,
            weekends: true,
            events: function(fetchInfo, successCallback, failureCallback) {
                const baseUrl = window.location.origin + '/admin/calendar';
                const eventsUrl = baseUrl + '/events';
                
                fetch(eventsUrl)
                    .then(response => response.json())
                    .then(data => {
                        successCallback(data);
                    })
                    .catch(error => {
                        failureCallback(error);
                    });
            },
            
            select: function(arg) {
                // Clear form and set dates when clicking on calendar
                const eventForm = document.getElementById('eventForm');
                if (eventForm) {
                    eventForm.reset();
                    const startDate = new Date(arg.startStr);
                    const startDateInput = document.getElementById('startDate');
                    
                    // Set the date using flatpickr if available
                    if (startDateInput && startDateInput._flatpickr) {
                        startDateInput._flatpickr.setDate(startDate);
                    } else if (startDateInput) {
                        // Fallback: set the value directly
                        startDateInput.value = startDate.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });
                    }
                    
                    // Set the status checkbox to checked by default
                    const statusCheckbox = document.getElementById('event-status');
                    if (statusCheckbox) {
                        statusCheckbox.checked = true;
                    }
                    showBootstrapModal('addEventModal');
                }
            },
            
            eventClick: function(info) {
                showEventDetails(info.event);
            },
            
            eventDrop: function(info) {
                updateEventDates(info.event);
            },
            
            eventResize: function(info) {
                updateEventDates(info.event);
            }
        });
        
        calendar.render();
    }

    // Handle form submission for new events
    const eventForm = document.getElementById('eventForm');
    if (eventForm) {
        eventForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            const baseUrl = window.location.origin + '/admin/calendar';
            
            fetch(baseUrl + '/events', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCSRFToken(),
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    calendar.refetchEvents();
                    hideBootstrapModal('addEventModal');
                    this.reset();
                    showAlert('Event created successfully!', 'success');
                } else {
                    showAlert('Error creating event: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showAlert('Error creating event', 'error');
            });
        });
    }

    // Handle form submission for editing events
    const editEventForm = document.getElementById('editEventForm');
    if (editEventForm) {
        editEventForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            const baseUrl = window.location.origin + '/admin/calendar';
            
            fetch(baseUrl + '/events/' + currentEventId, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': getCSRFToken(),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    calendar.refetchEvents();
                    hideBootstrapModal('editEventModal');
                    showAlert('Event updated successfully!', 'success');
                } else {
                    showAlert('Error updating event: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showAlert('Error updating event', 'error');
            });
        });
    }

    // Handle delete event
    const deleteEventBtn = document.getElementById('deleteEventBtn');
    if (deleteEventBtn) {
        deleteEventBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this event?')) {
                const baseUrl = window.location.origin + '/admin/calendar';
                
                fetch(baseUrl + '/events/' + currentEventId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': getCSRFToken(),
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        calendar.refetchEvents();
                        hideBootstrapModal('editEventModal');
                        showAlert('Event deleted successfully!', 'success');
                    } else {
                        showAlert('Error deleting event: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Error deleting event', 'error');
                });
            }
        });
    }

    function showEventDetails(event) {
        const titleElement = document.getElementById('eventDetailsTitle');
        const dateElement = document.getElementById('eventDetailsDate');
        const roleElement = document.getElementById('eventDetailsRole');
        const categoryElement = document.getElementById('eventDetailsCategory');
        const institutionElement = document.getElementById('eventDetailsInstitution');
        const locationElement = document.getElementById('eventDetailsLocation');
        const descriptionElement = document.getElementById('eventDetailsDescription');
        
        if (titleElement) titleElement.textContent = event.title;
        
        const startDate = new Date(event.start);
        const startDateStr = startDate.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
        
        let dateText = startDateStr;
        if (event.end) {
            const endDate = new Date(event.end);
            const endDateStr = endDate.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            dateText = `${startDateStr} - ${endDateStr}`;
        }
        
        if (dateElement) dateElement.textContent = dateText;
        if (roleElement) roleElement.textContent = event.extendedProps?.role ? event.extendedProps.role.charAt(0).toUpperCase() + event.extendedProps.role.slice(1) : 'No role specified';
        if (categoryElement) categoryElement.textContent = event.extendedProps?.category || 'No Category';
        if (institutionElement) institutionElement.textContent = event.extendedProps?.institution_name || 'No institution specified';
        if (locationElement) locationElement.textContent = event.extendedProps?.location || 'No location available';
        if (descriptionElement) descriptionElement.textContent = event.extendedProps?.description || 'No description available';
        
        showBootstrapModal('eventDetailsModal');
    }

    function updateEventDates(event) {
        const eventData = {
            start_date: event.startStr,
            end_date: event.endStr ? new Date(event.endStr).toISOString().split('T')[0] : event.startStr
        };

        const baseUrl = window.location.origin + '/admin/calendar';
        
        fetch(baseUrl + '/events/' + event.id, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken(),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(eventData)
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                calendar.refetchEvents();
                showAlert('Error updating event dates', 'error');
            }
        });
    }

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
    
    // Test function for date picker
    window.testDatePicker = function() {
        const dateInput = document.getElementById('startDate');
        const timeInput = document.getElementById('startTime');
        
        if (dateInput && dateInput._flatpickr) {
            alert('Date picker is working! Instance: ' + dateInput._flatpickr);
        } else {
            alert('Date picker is NOT working. Element: ' + dateInput);
        }
        
        if (timeInput && timeInput._flatpickr) {
            alert('Time picker is working! Instance: ' + timeInput._flatpickr);
        } else {
            alert('Time picker is NOT working. Element: ' + timeInput);
        }
    };
});