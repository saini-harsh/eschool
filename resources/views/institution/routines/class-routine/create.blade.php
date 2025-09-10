@extends('layouts.institution')
@section('title', 'Institution | Add Class Routine')
@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif 

<div class="content">
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div>
                <h6 class="mb-3 fs-14">
                    <a href="{{ route('institution.routines.index') }}">
                        <i class="ti ti-arrow-left me-1"></i> Class Routine
                    </a>
                </h6>

                <!-- Select Criteria Card -->
                <div class="card rounded-0 mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0">Select Criteria</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Institution <span class="text-danger">*</span></label>
                                <select name="institution_id" id="institution_id" class="form-select">
                                    <option value="">Select Institution</option>
                                    @foreach ($institutions as $institution)
                                        <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Class <span class="text-danger">*</span></label>
                                <select name="class_id" id="class_id" class="form-select">
                                    <option value="">Select Class</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Section <span class="text-danger">*</span></label>
                                <select name="section_id" id="section_id" class="form-select">
                                    <option value="">Select Section</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <button type="button" id="search_existing" class="btn btn-primary">
                                    <i class="ti ti-search me-1"></i> SEARCH
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Class Routine Create Card -->
                <div class="card rounded-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Class Routine Create</h6>
                        <div class="d-flex gap-2">
                            <button type="button" id="print_routine" class="btn btn-primary btn-sm">
                                <i class="ti ti-printer me-1"></i> PRINT
                            </button>
                            <button type="button" id="add_routine" class="btn btn-primary btn-sm">
                                <i class="ti ti-plus me-1"></i> ADD
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Day Tabs -->
                        <ul class="nav nav-tabs mb-4" id="dayTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="saturday-tab" data-bs-toggle="tab" data-bs-target="#saturday" type="button" role="tab" aria-controls="saturday" aria-selected="false">SATURDAY</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="sunday-tab" data-bs-toggle="tab" data-bs-target="#sunday" type="button" role="tab" aria-controls="sunday" aria-selected="true">SUNDAY</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="monday-tab" data-bs-toggle="tab" data-bs-target="#monday" type="button" role="tab" aria-controls="monday" aria-selected="false">MONDAY</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tuesday-tab" data-bs-toggle="tab" data-bs-target="#tuesday" type="button" role="tab" aria-controls="tuesday" aria-selected="false">TUESDAY</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="wednesday-tab" data-bs-toggle="tab" data-bs-target="#wednesday" type="button" role="tab" aria-controls="wednesday" aria-selected="false">WEDNESDAY</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="thursday-tab" data-bs-toggle="tab" data-bs-target="#thursday" type="button" role="tab" aria-controls="thursday" aria-selected="false">THURSDAY</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="friday-tab" data-bs-toggle="tab" data-bs-target="#friday" type="button" role="tab" aria-controls="friday" aria-selected="false">FRIDAY</button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="dayTabsContent">
                            @foreach(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'] as $day)
                            <div class="tab-pane fade {{ $day == 'sunday' ? 'show active' : '' }}" id="{{ $day }}" role="tabpanel" aria-labelledby="{{ $day }}-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>SUBJECT</th>
                                                <th>TEACHER</th>
                                                <th>START TIME</th>
                                                <th>END TIME</th>
                                                <th>IS BREAK</th>
                                                <th>OTHER DAY</th>
                                                <th>CLASS ROOM</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody id="{{ $day }}-routine-tbody">
                                            <!-- Existing routines will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Add New Routine Form -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="fw-bold mb-0">Add New Routine for {{ strtoupper($day) }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <form id="{{ $day }}-routine-form" class="routine-form" data-day="{{ $day }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Subject <span class="text-danger">*</span></label>
                                                    <select name="subject_id" class="form-select subject-select" required>
                                                        <option value="">Select Subject</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Teacher <span class="text-danger">*</span></label>
                                                    <select name="teacher_id" class="form-select teacher-select" required>
                                                        <option value="">Select Teacher</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                                    <input type="time" name="start_time" data-provider="timepickr" data-time-basic="true" class="form-control" placeholder="HH:MM" required>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label class="form-label">End Time <span class="text-danger">*</span></label>
                                                    <input type="time" name="end_time" data-provider="timepickr" data-time-basic="true" class="form-control" placeholder="HH:MM" required>
                                                </div>
                                                <div class="col-md-1 mb-3">
                                                    <label class="form-label">Break</label>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input type="checkbox" name="is_break" class="form-check-input" value="1">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 mb-3">
                                                    <label class="form-label">Other Day</label>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input type="checkbox" name="is_other_day" class="form-check-input" value="1">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label class="form-label">Class Room</label>
                                                    <select name="class_room_id" class="form-select">
                                                        <option value="">Select Room</option>
                                                        @foreach ($classRooms as $room)
                                                            <option value="{{ $room->id }}">{{ $room->room_no }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-3 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="ti ti-plus me-1"></i> Add
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Routine Row Template -->
<template id="routine-row-template">
    <tr>
        <td>
            <div class="fw-bold text-primary">__SUBJECT_NAME__</div>
        </td>
        <td>
            <div class="text-muted">__TEACHER_NAME__</div>
        </td>
        <td>
            <div class="text-muted">__START_TIME__</div>
        </td>
        <td>
            <div class="text-muted">__END_TIME__</div>
        </td>
        <td class="text-center">
            <span class="badge __IS_BREAK_BADGE__">
                __IS_BREAK_TEXT__
            </span>
        </td>
        <td class="text-center">
            <span class="badge __IS_OTHER_DAY_BADGE__">
                __IS_OTHER_DAY_TEXT__
            </span>
        </td>
        <td>
            <div class="text-muted">__CLASS_ROOM__</div>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger delete-routine" data-routine-id="__ROUTINE_ID__">
                <i class="ti ti-trash"></i>
            </button>
        </td>
    </tr>
</template>
@endsection

@push('scripts')
<script src="{{ asset('custom/js/institution/routines.js') }}"></script>
@endpush
