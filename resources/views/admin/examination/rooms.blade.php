@extends('layouts.admin')
@section('title', 'Admin | Exam Management | Exams')
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
                <h5 class="fw-bold">Exam Rooms Setup</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('admin.dashboard') }}"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Exam Rooms</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Room Management Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Exam Room Management</h6>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createRoomModal">
                            <i class="ti ti-plus me-1"></i>Create New Room
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="roomsTable">
                                <thead>
                                    <tr>
                                        <th>Room No</th>
                                        <th>Room Name</th>
                                        <th>Capacity</th>
                                        <th>Students per Bench</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lists as $room)
                                        <tr>
                                            <td>{{ $room->room_no }}</td>
                                            <td>{{ $room->room_name ?? 'N/A' }}</td>
                                            <td>{{ $room->capacity }}</td>
                                            <td>{{ $room->students_per_bench ?? 1 }}</td>
                                            <td>
                                                <span class="badge {{ $room->status ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $room->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        onclick="viewRoom({{ $room->id }})" title="View">
                                                        <i class="ti ti-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success"
                                                        onclick="designLayout({{ $room->id }})" title="Design Layout">
                                                        <i class="ti ti-layout-grid"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                                        onclick="editRoom({{ $room->id }})" title="Edit">
                                                        <i class="ti ti-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteRoom({{ $room->id }})" title="Delete">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No rooms found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Room Modal -->
        <div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createRoomModalLabel">Create New Room</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createRoomForm">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="room_no" class="form-label">Room Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="room_no" name="room_no" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="room_name" class="form-label">Room Name</label>
                                        <input type="text" class="form-control" id="room_name" name="room_name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="capacity" class="form-label">Capacity <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="capacity" name="capacity"
                                            min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="students_per_bench" class="form-label">Students per Bench <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="students_per_bench" name="students_per_bench"
                                            required>
                                            <option value="1">1 Student</option>
                                            <option value="2">2 Students</option>
                                            <option value="3">3 Students</option>
                                            <option value="4">4 Students</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="design_layout_now"
                                        name="design_layout_now">
                                    <label class="form-check-label" for="design_layout_now">
                                        Design layout after creating room
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection


@push('scripts')
    <script src="{{ asset('custom/js/admin/rooms.js') }}"></script>
@endpush
