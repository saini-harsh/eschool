@extends('layouts.institution')
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
                    <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('institution.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Academic Calendar</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            `<a href="{{ route('institution.events.index') }}" class="btn btn-outline-primary">
                <i class="ti ti-list me-1"></i>Manage Events
            </a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#addEventModal" class="btn btn-primary">
                <i class="ti ti-circle-plus me-1"></i>New Event
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <div class="col-12">
            <div class="card mb-0">
                <div class="card-body">
                    <div id="calendar"></div>
                    
                    <!-- Test button to verify date picker -->
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="testDatePicker()">
                            Test Date Picker
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End Content -->

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
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
                                <label class="form-label">EVENT TITLE <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Enter event title" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" name="role" required>
                                    <option value="">Select</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="student">Students</option>
                                    <option value="nonworkingstaff">Non Working Staff</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" name="category" required>
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
                                <label class="form-label">Institution</label>
                                <select class="form-select" name="institution_id" readonly disabled>
                                    <option value="{{ $currentInstitution->id }}" selected>{{ $currentInstitution->name }}</option>
                                </select>
                                <input type="hidden" name="institution_id" value="{{ $currentInstitution->id }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">EVENT LOCATION <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" placeholder="Enter event location" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">FROM DATE <span class="text-danger">*</span></label>
                                    <input type="text" name="start_date" id="startDate" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">START TIME</label>                                    
                                    <input type="time" name="start_time" id="startTime" data-provider="timepickr" data-time-basic="true" class="form-control" placeholder="HH:MM">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">DESCRIPTION <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Enter event description" required></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">URL</label>
                                <input type="url" name="url" class="form-control" placeholder="Enter event URL (optional)">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">File</label>
                                <div class="input-group">
                                    <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.gif">
                                    <button class="btn btn-outline-secondary" type="button">BROWSE</button>
                                </div>
                                <small class="text-muted">(JPG, JPEG, PNG, GIF are allowed for upload)</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="status" value="0">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" id="event-status" checked>
                                    <label class="form-check-label" for="event-status">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-md btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-md btn-primary">
                        <i class="ti ti-check me-1"></i>SAVE
                    </button>
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
                <p class="d-flex align-items-center fw-medium text-black mb-3"><i class="ti ti-user text-default me-2"></i><span id="eventDetailsRole"></span></p>
                <p class="d-flex align-items-center fw-medium text-black mb-3"><i class="ti ti-tag text-default me-2"></i><span id="eventDetailsCategory"></span></p>
                <p class="d-flex align-items-center fw-medium text-black mb-3"><i class="ti ti-building text-default me-2"></i><span id="eventDetailsInstitution"></span></p>
                <p class="d-flex align-items-center fw-medium text-black mb-3"><i class="ti ti-map-pin text-default me-2"></i><span id="eventDetailsLocation"></span></p>
                <p class="d-flex align-items-center fw-medium text-black mb-0"><i class="ti ti-file-text text-default me-2"></i><span id="eventDetailsDescription"></span></p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('custom/js/institution/calender.js') }}"></script>
@endpush


