@extends('layouts.institution')
@section('title', 'Institution | Invigilator Assignments')
@section('content')

    @if (session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">{{ session('success') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="content">
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Invigilator Assignments</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('institution.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('institution.exam-management.invigilator.index') }}">Invigilator</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Assignments</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filters</h6>
                <form class="row g-3 align-items-end" method="GET" action="{{ route('institution.exam-management.invigilator.assignments') }}">
                    <div class="col-md-2">
                        <label for="institution" class="form-label">Institution</label>
                        <select class="form-select" id="institution" name="institution">
                            <option value="">Select Institution</option>
                            @if (isset($institutions) && count($institutions) > 0)
                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution->id }}" {{ auth()->user()->id == $institution->id ? 'selected' : '' }}>
                                        {{ $institution->name }}</option>
                                @endforeach
                            @else
                                <option value="">No institutions found</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="exam-type" class="form-label">Exam Type</label>
                        <select class="form-select" id="exam-type" name="exam_type"></select>
                    </div>
                    <div class="col-md-2">
                        <label for="exam-month" class="form-label">Month</label>
                        <select class="form-select" id="exam-month" name="month">
                            <option value="">Select Month</option>
                            <option value="1" {{ request('month') == '1' ? 'selected' : '' }}>January</option>
                            <option value="2" {{ request('month') == '2' ? 'selected' : '' }}>February</option>
                            <option value="3" {{ request('month') == '3' ? 'selected' : '' }}>March</option>
                            <option value="4" {{ request('month') == '4' ? 'selected' : '' }}>April</option>
                            <option value="5" {{ request('month') == '5' ? 'selected' : '' }}>May</option>
                            <option value="6" {{ request('month') == '6' ? 'selected' : '' }}>June</option>
                            <option value="7" {{ request('month') == '7' ? 'selected' : '' }}>July</option>
                            <option value="8" {{ request('month') == '8' ? 'selected' : '' }}>August</option>
                            <option value="9" {{ request('month') == '9' ? 'selected' : '' }}>September</option>
                            <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>October</option>
                            <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                            <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>December</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}" />
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="ti ti-filter me-1"></i>Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('institution.exam-management.invigilator.assignments') }}" class="btn btn-outline-secondary w-100"><i class="ti ti-x me-1"></i>Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 15%">Date</th>
                                <th style="width: 25%">Exam</th>
                                <th style="width: 20%">Room</th>
                                <th style="width: 20%">Teacher</th>
                                <th style="width: 20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $item)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d M, Y') }}</td>
                                    <td>{{ $item->exam ? $item->exam->title : 'N/A' }}</td>
                                    <td>{{ $item->classRoom ? ($item->classRoom->room_no . ($item->classRoom->room_name ? ' - '.$item->classRoom->room_name : '')) : 'N/A' }}</td>
                                    <td>{{ $item->teacher ? ($item->teacher->first_name . ' ' . $item->teacher->last_name) : 'Unassigned' }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('institution.exam-management.invigilator.index', ['exam_type' => $item->exam ? $item->exam->exam_type_id : null, 'month' => $item->exam ? $item->exam->month : null]) }}">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No assignments found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('custom/js/institution/exams.js') }}"></script>
@endpush

