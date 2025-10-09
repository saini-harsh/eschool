@extends('layouts.teacher')
@section('title', 'Teacher | Events')
@section('content')

<!-- Start Content -->
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Events</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="{{ route('teacher.dashboard') }}"><i class="ti ti-home me-1"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Academics</li>
                    <li class="breadcrumb-item active" aria-current="page">Events</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Events Content -->
    <div class="row">
        <!-- Events List -->
        <div class="col-lg-8">
            <div class="card rounded-0 mb-0">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Events</h6>
                    <small class="text-muted">Showing only visible events</small>
                </div>
                <div class="card-body">
                    <div id="events-container">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="card rounded-0 mb-0">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm avatar-rounded bg-light-primary me-3">
                            <i class="ti ti-calendar-event text-primary"></i>
                        </div>
                        <div>
                            <h6 class="fs-14 mb-0">Total Events</h6>
                            <span class="text-muted fs-12" id="total-events-count">Loading...</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm avatar-rounded bg-light-success me-3">
                            <i class="ti ti-clock text-success"></i>
                        </div>
                        <div>
                            <h6 class="fs-14 mb-0">This Week</h6>
                            <span class="text-muted fs-12" id="this-week-events-count">Loading...</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm avatar-rounded bg-light-info me-3">
                            <i class="ti ti-school text-info"></i>
                        </div>
                        <div>
                            <h6 class="fs-14 mb-0">Institution</h6>
                            <span class="text-muted fs-12">{{ Auth::guard('teacher')->user()->institution->name ?? 'N/A' }}</span>
                        </div>
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
    // Teacher Events Management
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Teacher Events page loaded');
        loadEvents();
    });

    // Load events
    function loadEvents() {
        fetch('{{ route("teacher.events.api") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayEvents(data.data);
                    updateStats(data.data);
                } else {
                    document.getElementById('events-container').innerHTML = 
                        '<div class="text-center py-4"><p class="text-muted">No events found.</p></div>';
                }
            })
            .catch(error => {
                console.error('Error loading events:', error);
                document.getElementById('events-container').innerHTML = 
                    '<div class="text-center py-4"><p class="text-danger">Error loading events.</p></div>';
            });
    }

    // Display events
    function displayEvents(events) {
        const container = document.getElementById('events-container');
        
        if (events.length === 0) {
            container.innerHTML = '<div class="text-center py-4"><p class="text-muted">No events found.</p></div>';
            return;
        }

        let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Event</th><th>Date</th><th>Time</th><th>Role</th><th>Location</th></tr></thead><tbody>';
        
        events.forEach(event => {
            const startDate = new Date(event.start_date).toLocaleDateString('en-US', { 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric' 
            });
            const time = event.start_time || 'N/A';
            const roleBadge = event.role ? `<span class="badge bg-primary">${event.role.charAt(0).toUpperCase() + event.role.slice(1)}</span>` : '<span class="text-muted">N/A</span>';
            const location = event.location || 'N/A';
            
            html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm avatar-rounded bg-light me-2">
                                <i class="ti ti-calendar-event text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fs-14 mb-0">${event.title}</h6>
                                <small class="text-muted">${event.description ? event.description.substring(0, 50) + (event.description.length > 50 ? '...' : '') : ''}</small>
                            </div>
                        </div>
                    </td>
                    <td>${startDate}</td>
                    <td>${time}</td>
                    <td>${roleBadge}</td>
                    <td>${location}</td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        container.innerHTML = html;
    }

    // Update statistics
    function updateStats(events) {
        document.getElementById('total-events-count').textContent = events.length;
        
        const thisWeek = events.filter(event => {
            const eventDate = new Date(event.start_date);
            const now = new Date();
            const weekFromNow = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
            return eventDate >= now && eventDate <= weekFromNow;
        }).length;
        
        document.getElementById('this-week-events-count').textContent = thisWeek;
    }
</script>
@endpush
