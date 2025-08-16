@extends('layouts.admin')
@section('title', 'Admin | Academic Calendar')
@section('content')

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Academic Calendar</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('admin.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Academic Calendar</li>
                </ol>
            </nav>
        </div>
        <a href="#" data-bs-toggle="modal" data-bs-target="#addEventModal" class="btn btn-primary">
            <i class="ti ti-circle-plus me-1"></i>New Event
        </a>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3 fw-bold">Drag & Drop Event Categories</h6>
                    <div class="border rounded px-3 py-2 mb-3" data-category="Exam">
                        <p class="text-dark d-inline-flex align-items-center mb-0"><i class="ti ti-point-filled text-success me-1"></i>Exam</p>
                    </div>
                    <div class="border rounded px-3 py-2 mb-3" data-category="Holiday">
                        <p class="text-dark d-inline-flex align-items-center mb-0"><i class="ti ti-point-filled text-warning me-1"></i>Holiday</p>
                    </div>
                    <div class="border rounded px-3 py-2 mb-3" data-category="Meeting">
                        <p class="text-dark d-inline-flex align-items-center mb-0"><i class="ti ti-point-filled text-danger me-1"></i>Meeting</p>
                    </div>
                    <div class="border rounded px-3 py-2 mb-3" data-category="Event">
                        <p class="text-dark d-inline-flex align-items-center mb-0"><i class="ti ti-point-filled text-info me-1"></i>Event</p>
                    </div>
                    <div class="border rounded px-3 py-2 mb-3" data-category="Deadline">
                        <p class="text-dark d-inline-flex align-items-center mb-0"><i class="ti ti-point-filled text-primary me-1"></i>Deadline</p>
                    </div>
                    <div class="border rounded px-3 py-2 mb-3" data-category="Other">
                        <p class="text-dark d-inline-flex align-items-center mb-0"><i class="ti ti-point-filled text-secondary me-1"></i>Other</p>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="removeAfterDrop" checked>
                        <label class="form-check-label fw-medium fs-14" for="removeAfterDrop">Remove after drop</label>
                    </div>
                    <a href="#" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="ti ti-circle-plus me-1"></i>New Category
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card mb-0">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End Content -->

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mb-0">Add New Event</h6>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
            </div>
            <form id="eventForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Event Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="eventTitle" name="title" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                <div class="input-group w-auto input-group-flat">
                                    <input type="text" class="form-control" id="startDate" name="start_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" required>
                                    <span class="input-group-text">
                                        <i class="ti ti-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <div class="input-group w-auto input-group-flat">
                                    <input type="text" class="form-control" id="endDate" name="end_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy">
                                    <span class="input-group-text">
                                        <i class="ti ti-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Event Category <span class="text-danger">*</span></label>
                                <select class="select" id="eventCategory" name="category" required>
                                    <option value="">Select</option>
                                    <option value="Exam">Exam</option>
                                    <option value="Holiday">Holiday</option>
                                    <option value="Meeting">Meeting</option>
                                    <option value="Event">Event</option>
                                    <option value="Deadline">Deadline</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="eventDescription" name="description" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-md btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-md btn-primary">Add Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mb-0">Add New Category</h6>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
            </div>
            <form id="categoryForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="categoryName" name="name" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Category Color <span class="text-danger">*</span></label>
                                <select class="select" id="categoryColor" name="color" required>
                                    <option value="">Select</option>
                                    <option value="#28a745">Green</option>
                                    <option value="#ffc107">Yellow</option>
                                    <option value="#dc3545">Red</option>
                                    <option value="#17a2b8">Blue</option>
                                    <option value="#6c757d">Gray</option>
                                    <option value="#007bff">Primary Blue</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-md btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-md btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mb-0">Edit Event</h6>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
            </div>
            <form id="editEventForm">
                <input type="hidden" id="editEventId" name="event_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Event Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editEventTitle" name="title" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                <div class="input-group w-auto input-group-flat">
                                    <input type="text" class="form-control" id="editStartDate" name="start_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" required>
                                    <span class="input-group-text">
                                        <i class="ti ti-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <div class="input-group w-auto input-group-flat">
                                    <input type="text" class="form-control" id="editEndDate" name="end_date" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy">
                                    <span class="input-group-text">
                                        <i class="ti ti-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Event Category <span class="text-danger">*</span></label>
                                <select class="select" id="editEventCategory" name="category" required>
                                    <option value="">Select</option>
                                    <option value="Exam">Exam</option>
                                    <option value="Holiday">Holiday</option>
                                    <option value="Meeting">Meeting</option>
                                    <option value="Event">Event</option>
                                    <option value="Deadline">Deadline</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="editEventDescription" name="description" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger me-auto" id="deleteEventBtn">Delete Event</button>
                    <button type="button" class="btn btn-md btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-md btn-primary">Update Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark modal-bg">
                <div class="modal-title text-white"><span id="eventDetailsTitle"></span></div>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                <p class="d-flex align-items-center fw-medium text-black mb-3"><i class="ti ti-calendar-check text-default me-2"></i><span id="eventDetailsDate"></span></p>
                <p class="d-flex align-items-center fw-medium text-black mb-3"><i class="ti ti-tag text-default me-2"></i><span id="eventDetailsCategory"></span></p>
                <p class="d-flex align-items-center fw-medium text-black mb-0"><i class="ti ti-file-text text-default me-2"></i><span id="eventDetailsDescription"></span></p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-event:hover {
        opacity: 0.8;
    }
    .border.rounded {
        cursor: move;
        transition: all 0.3s ease;
    }
    .border.rounded:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }
    .border.rounded.dragging {
        opacity: 0.5;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let calendar;
    let currentEventId = null;

    // Initialize date pickers
    flatpickr("[data-provider='flatpickr']", {
        dateFormat: "d M, Y"
    });

    // Initialize FullCalendar
    const calendarEl = document.getElementById('calendar');
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
        droppable: true,
        events: '{{ route("admin.academic.calendar.events") }}',
        
        select: function(arg) {
            // Clear form and set dates when clicking on calendar
            document.getElementById('eventForm').reset();
            const startDate = new Date(arg.startStr);
            document.getElementById('startDate')._flatpickr.setDate(startDate);
            if (arg.endStr) {
                const endDate = new Date(arg.endStr);
                endDate.setDate(endDate.getDate() - 1);
                document.getElementById('endDate')._flatpickr.setDate(endDate);
            }
            $('#addEventModal').modal('show');
        },
        
        eventClick: function(info) {
            currentEventId = info.event.id;
            loadEventForEdit(currentEventId);
            $('#editEventModal').modal('show');
        },
        
        eventDrop: function(info) {
            updateEventDates(info.event);
        },
        
        eventResize: function(info) {
            updateEventDates(info.event);
        },

        drop: function(info) {
            const category = info.draggedEl.getAttribute('data-category');
            const removeAfterDrop = document.getElementById('removeAfterDrop').checked;
            
            if (removeAfterDrop) {
                info.draggedEl.style.display = 'none';
            }
            
            // Show add event modal with pre-filled category
            document.getElementById('eventForm').reset();
            document.getElementById('eventCategory').value = category;
            document.getElementById('startDate')._flatpickr.setDate(info.date);
            $('#addEventModal').modal('show');
        }
    });
    
    calendar.render();

    // Handle form submission for new events
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("admin.academic.calendar.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calendar.refetchEvents();
                $('#addEventModal').modal('hide');
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

    // Handle form submission for editing events
    document.getElementById('editEventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch(`{{ route("admin.academic.calendar.update", ["id" => ":id"]) }}`.replace(':id', currentEventId), {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                calendar.refetchEvents();
                $('#editEventModal').modal('hide');
                showAlert('Event updated successfully!', 'success');
            } else {
                showAlert('Error updating event: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showAlert('Error updating event', 'error');
        });
    });

    // Handle delete event
    document.getElementById('deleteEventBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this event?')) {
            fetch(`{{ route("admin.academic.calendar.destroy", ["id" => ":id"]) }}`.replace(':id', currentEventId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    calendar.refetchEvents();
                    $('#editEventModal').modal('hide');
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

    function loadEventForEdit(eventId) {
        fetch(`{{ route("admin.academic.calendar.show", ["id" => ":id"]) }}`.replace(':id', eventId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const event = data.event;
                document.getElementById('editEventId').value = event.id;
                document.getElementById('editEventTitle').value = event.title;
                document.getElementById('editEventCategory').value = event.category;
                
                if (event.start_date) {
                    document.getElementById('editStartDate')._flatpickr.setDate(event.start_date);
                }
                if (event.end_date) {
                    document.getElementById('editEndDate')._flatpickr.setDate(event.end_date);
                }
                
                document.getElementById('editEventDescription').value = event.description;
            }
        });
    }

    function updateEventDates(event) {
        const eventData = {
            start_date: event.startStr,
            end_date: event.endStr ? new Date(event.endStr).toISOString().split('T')[0] : event.startStr
        };

        fetch(`{{ route("admin.academic.calendar.update", ["id" => ":id"]) }}`.replace(':id', event.id), {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
});
</script>
@endpush
