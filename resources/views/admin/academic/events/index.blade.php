@extends('layouts.admin')
@section('title', 'Admin | Event Management')
@section('content')
    @if (session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    <!-- Start Content -->
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Event Management</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('admin.dashboard') }}"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Event Management</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Add Event</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="event-form">
                            @csrf
                            <input type="hidden" name="id" id="event-id">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Event Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="Enter Event title" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                        <input type="date" name="start_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" name="start_time" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">End Time</label>
                                        <input type="time" name="end_time" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="form-select" name="category" required>
                                            <option value="">Select Category</option>
                                            <option value="Exam">Exam</option>
                                            <option value="Holiday">Holiday</option>
                                            <option value="Meeting">Meeting</option>
                                            <option value="Event">Event</option>
                                            <option value="Deadline">Deadline</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Color</label>
                                        <input type="color" name="color" class="form-control form-control-color" value="#3788d8">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="Enter event description"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="status" value="1"
                                                id="event-status" checked>
                                            <label class="form-check-label" for="event-status">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100" id="submit-btn">
                                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            <span class="btn-text">Add Event</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Event List</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="event-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($events) && !empty($events))
                                        @foreach($events as $key => $event)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $event->title }}</td>
                                                <td>
                                                    <span class="badge" style="background-color: {{ $event->color }}; color: white;">
                                                        {{ $event->category }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d M, Y') }}
                                                    @if($event->end_date && $event->end_date != $event->start_date)
                                                        - {{ \Carbon\Carbon::parse($event->end_date)->format('d M, Y') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($event->start_time)
                                                        {{ $event->start_time }}
                                                        @if($event->end_time)
                                                            - {{ $event->end_time }}
                                                        @endif
                                                    @else
                                                        All Day
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input status-toggle" type="checkbox" 
                                                               data-id="{{ $event->id }}" 
                                                               {{ $event->status ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $event->id }}">
                                                            <i class="ti ti-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $event->id }}">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle form submission
    $('#event-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $('#submit-btn');
        const spinner = submitBtn.find('.spinner-border');
        const btnText = submitBtn.find('.btn-text');
        
        // Show loading state
        spinner.removeClass('d-none');
        btnText.text('Processing...');
        submitBtn.prop('disabled', true);
        
        const eventId = $('#event-id').val();
        const url = eventId ? `/admin/events/${eventId}` : '/admin/events';
        const method = eventId ? 'PUT' : 'POST';
        
        $.ajax({
            url: url,
            method: method,
            data: Object.fromEntries(formData),
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
                    showAlert('Validation failed. Please check your inputs.', 'error');
                } else {
                    showAlert('An error occurred. Please try again.', 'error');
                }
            },
            complete: function() {
                // Hide loading state
                spinner.addClass('d-none');
                btnText.text(eventId ? 'Update Event' : 'Add Event');
                submitBtn.prop('disabled', false);
            }
        });
    });

    // Handle edit button click
    $(document).on('click', '.edit-btn', function() {
        const eventId = $(this).data('id');
        
        $.ajax({
            url: `/admin/events/${eventId}/edit`,
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
    });

    // Handle delete button click
    $(document).on('click', '.delete-btn', function() {
        const eventId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this event?')) {
            $.ajax({
                url: `/admin/events/${eventId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert(response.message, 'success');
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
    });

    // Handle status toggle
    $(document).on('change', '.status-toggle', function() {
        const eventId = $(this).data('id');
        const status = $(this).is(':checked') ? 1 : 0;
        
        $.ajax({
            url: `/admin/events/${eventId}/status`,
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
    });

    function populateForm(event) {
        $('#event-id').val(event.id);
        $('input[name="title"]').val(event.title);
        $('input[name="start_date"]').val(event.start_date);
        $('input[name="end_date"]').val(event.end_date);
        $('input[name="start_time"]').val(event.start_time);
        $('input[name="end_time"]').val(event.end_time);
        $('select[name="category"]').val(event.category);
        $('input[name="color"]').val(event.color);
        $('textarea[name="description"]').val(event.description);
        $('input[name="status"]').prop('checked', event.status == 1);
        
        $('#submit-btn .btn-text').text('Update Event');
    }

    function resetForm() {
        $('#event-form')[0].reset();
        $('#event-id').val('');
        $('#submit-btn .btn-text').text('Add Event');
    }

    function loadEvents() {
        $.ajax({
            url: '/admin/events/list',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    updateTable(response.data);
                }
            }
        });
    }

    function updateTable(events) {
        let tbody = '';
        events.forEach((event, index) => {
            const startDate = new Date(event.start_date).toLocaleDateString('en-US', { 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric' 
            });
            const endDate = event.end_date && event.end_date != event.start_date ? 
                ' - ' + new Date(event.end_date).toLocaleDateString('en-US', { 
                    day: '2-digit', 
                    month: 'short', 
                    year: 'numeric' 
                }) : '';
            
            const time = event.start_time ? 
                event.start_time + (event.end_time ? ' - ' + event.end_time : '') : 
                'All Day';
            
            tbody += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${event.title}</td>
                    <td><span class="badge" style="background-color: ${event.color}; color: white;">${event.category}</span></td>
                    <td>${startDate}${endDate}</td>
                    <td>${time}</td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input status-toggle" type="checkbox" 
                                   data-id="${event.id}" 
                                   ${event.status ? 'checked' : ''}>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-primary edit-btn" data-id="${event.id}">
                                <i class="ti ti-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="${event.id}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        $('#event-table tbody').html(tbody);
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
