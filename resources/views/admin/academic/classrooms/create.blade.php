@extends('layouts.admin')
@section('title', 'Admin | Create Room with Seat Arrangement')
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
                <h5 class="fw-bold">Create Room with Seat Arrangement</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href="{{ route('admin.rooms.index') }}">
                                <i class="ti ti-home me-1"></i>Rooms
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Create Room</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <!-- Room Details Form -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Room Details</h6>
                    </div>
                    <div class="card-body">
                        <form id="room-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Room Number <span class="text-danger">*</span></label>
                                <input type="text" name="room_no" id="room_no" class="form-control"
                                    placeholder="Enter Room Number" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Room Name</label>
                                <input type="text" name="room_name" id="room_name" class="form-control"
                                    placeholder="Enter Room Name (Optional)">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Capacity <span class="text-danger">*</span></label>
                                <input type="number" name="capacity" id="capacity" class="form-control"
                                    placeholder="Enter Capacity" min="1" required>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status" value="1"
                                        id="room_status" checked>
                                    <label class="form-check-label" for="room_status">
                                        Active
                                    </label>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary" id="generate-seatmap">
                                    <i class="ti ti-layout-grid me-1"></i>Generate Seatmap
                                </button>
                                <button type="button" class="btn btn-success d-none" id="save-room">
                                    <i class="ti ti-device-floppy me-1"></i>Save Room
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Seatmap Controls -->
                <div class="card mt-3 d-none" id="seatmap-controls">
                    <div class="card-header">
                        <h6 class="fw-bold">Seatmap Controls</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Layout Type</label>
                            <select class="form-select" id="layout-type">
                                <option value="grid">Grid Layout</option>
                                <option value="theater">Theater Style</option>
                                <option value="u-shape">U-Shape</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rows</label>
                            <input type="number" id="rows" class="form-control" min="1" max="20"
                                value="5">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Columns</label>
                            <input type="number" id="columns" class="form-control" min="1" max="20"
                                value="6">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary" id="apply-layout">
                                <i class="ti ti-refresh me-1"></i>Apply Layout
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="clear-seats">
                                <i class="ti ti-trash me-1"></i>Clear All Seats
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seatmap Canvas -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Seat Arrangement</h6>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-seat">
                                <i class="ti ti-plus me-1"></i>Add Seat
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="remove-seat">
                                <i class="ti ti-minus me-1"></i>Remove Seat
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="seatmap-container" class="seatmap-container">
                            <div class="text-center text-muted py-5">
                                <i class="ti ti-layout-grid fs-1 mb-3"></i>
                                <p>Enter room capacity and click "Generate Seatmap" to start arranging seats</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0">Saving room...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .seatmap-container {
            min-height: 500px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            position: relative;
            overflow: auto;
            background: #f8f9fa;
        }

        .seat {
            width: 40px;
            height: 40px;
            border: 2px solid #6c757d;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 2px;
            cursor: pointer;
            background: #fff;
            transition: all 0.2s ease;
            position: absolute;
            font-size: 12px;
            font-weight: bold;
            user-select: none;
        }

        .seat:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .seat.selected {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        .seat.occupied {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
            cursor: not-allowed;
        }

        .seat.available {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        .seat.dragging {
            opacity: 0.7;
            z-index: 1000;
        }

        .seatmap-row {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
            justify-content: center;
        }

        .seatmap-grid {
            display: grid;
            gap: 5px;
            padding: 20px;
        }

        .seatmap-theater {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 20px;
            align-items: center;
        }

        .seatmap-theater .seatmap-row {
            display: flex;
            gap: 5px;
        }

        .seatmap-theater .seatmap-row:nth-child(odd) {
            margin-left: 20px;
        }

        .seatmap-u-shape {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 20px;
            align-items: center;
        }

        .seatmap-u-shape .seatmap-row {
            display: flex;
            gap: 5px;
        }

        .seatmap-u-shape .seatmap-row:first-child,
        .seatmap-u-shape .seatmap-row:last-child {
            width: 100%;
            justify-content: space-between;
        }

        .seatmap-u-shape .seatmap-row:not(:first-child):not(:last-child) {
            justify-content: center;
        }

        .teacher-desk {
            width: 60px;
            height: 30px;
            background: #6f42c1;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: bold;
            margin: 10px auto;
            position: relative;
        }

        .teacher-desk::before {
            content: "Teacher";
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 10px;
            color: #6c757d;
        }

        .seat-number {
            font-size: 10px;
            font-weight: bold;
        }

        .seatmap-legend {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }

        .legend-color.available {
            background: #28a745;
        }

        .legend-color.occupied {
            background: #dc3545;
        }

        .legend-color.selected {
            background: #0d6efd;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ URL::asset('custom/js/admin/room-seatmap.js') }}"></script>
@endpush
