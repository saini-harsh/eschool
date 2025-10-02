@extends('layouts.admin')
@section('title', 'Admin | Room Details - ' . $classRoom->room_no)
@section('content')
    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Room Details - {{ $classRoom->room_no }}</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href="{{ route('admin.rooms.index') }}">
                                <i class="ti ti-home me-1"></i>Rooms
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $classRoom->room_no }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.rooms.edit', $classRoom->id) }}" class="btn btn-primary me-2">
                    <i class="ti ti-edit me-1"></i>Edit Room
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to Rooms
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <!-- Room Information -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Room Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Room Number:</label>
                            <p class="mb-0">{{ $classRoom->room_no }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Room Name:</label>
                            <p class="mb-0">{{ $classRoom->room_name ?? 'Not specified' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Capacity:</label>
                            <p class="mb-0">{{ $classRoom->capacity }} seats</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status:</label>
                            <span class="badge {{ $classRoom->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $classRoom->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Created:</label>
                            <p class="mb-0">{{ $classRoom->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if ($classRoom->updated_at != $classRoom->created_at)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Last Updated:</label>
                                <p class="mb-0">{{ $classRoom->updated_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Seat Statistics -->
                @if ($classRoom->seatmap)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="fw-bold">Seat Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-1">{{ count($classRoom->seatmap) }}</h4>
                                        <small class="text-muted">Total Seats</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <h4 class="text-success mb-1">
                                            {{ collect($classRoom->seatmap)->where('status', 'available')->count() }}</h4>
                                        <small class="text-muted">Available</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-danger mb-1">
                                        {{ collect($classRoom->seatmap)->where('status', 'occupied')->count() }}</h4>
                                    <small class="text-muted">Occupied</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Seatmap Display -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Seat Arrangement</h6>
                        @if ($classRoom->seatmap)
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="toggle-seat-numbers">
                                    <i class="ti ti-eye me-1"></i>Toggle Numbers
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="print-seatmap">
                                    <i class="ti ti-printer me-1"></i>Print
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($classRoom->seatmap && count($classRoom->seatmap) > 0)
                            <div id="seatmap-display" class="seatmap-display">
                                <!-- Teacher desk -->
                                <div class="teacher-desk"></div>

                                <!-- Seats will be rendered here by JavaScript -->
                                <div id="seats-container"></div>

                                <!-- Legend -->
                                <div class="seatmap-legend">
                                    <div class="legend-item">
                                        <div class="legend-color available"></div>
                                        <span>Available</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color occupied"></div>
                                        <span>Occupied</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color selected"></div>
                                        <span>Selected</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="ti ti-layout-grid fs-1 mb-3"></i>
                                <p>No seatmap available for this room</p>
                                <a href="{{ route('admin.rooms.edit', $classRoom->id) }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-1"></i>Create Seatmap
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection

@push('styles')
    <style>
        .seatmap-display {
            min-height: 500px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            position: relative;
            overflow: auto;
            background: #f8f9fa;
            padding: 20px;
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
            background: #fff;
            position: absolute;
            font-size: 12px;
            font-weight: bold;
            user-select: none;
        }

        .seat.available {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        .seat.occupied {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .seat.selected {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
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

        @media print {

            .btn,
            .card-header .d-flex:last-child {
                display: none !important;
            }

            .seatmap-display {
                border: 1px solid #000;
                background: white;
            }

            .seat {
                border: 1px solid #000;
                background: white !important;
                color: black !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            const seatmap = @json($classRoom->seatmap ?? []);
            let showNumbers = true;

            if (seatmap.length > 0) {
                renderSeatmap(seatmap);
            }

            $('#toggle-seat-numbers').on('click', function() {
                showNumbers = !showNumbers;
                $('.seat-number').toggle(showNumbers);
                $(this).find('i').toggleClass('ti-eye ti-eye-off');
            });

            $('#print-seatmap').on('click', function() {
                window.print();
            });

            function renderSeatmap(seats) {
                const container = $('#seats-container');
                container.empty();

                seats.forEach(function(seat) {
                    const seatElement = $(`
                <div class="seat ${seat.status}" style="left: ${seat.x}px; top: ${seat.y}px;">
                    <span class="seat-number">${seat.id}</span>
                </div>
            `);
                    container.append(seatElement);
                });
            }
        });
    </script>
@endpush
