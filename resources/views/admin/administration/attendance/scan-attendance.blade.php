@extends('layouts.admin')
@section('title', 'Admin Dashboard | Scan Attendance')

@section('content')
    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Scan Attendance</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href="{{ route('admin.attendance') }}"><i class="ti ti-arrow-left me-1"></i>Attendance
                                Management</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Scan Attendance</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Scan Attendance Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Select Scanning Method</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <button type="button" class="btn btn-outline-primary btn-lg w-100" id="scan-barcode-btn">
                                    <i class="ti ti-scan me-2"></i>Scan Barcode
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <button type="button" class="btn btn-outline-success btn-lg w-100" id="scan-qr-btn">
                                    <i class="ti ti-qrcode me-2"></i>Scan QR Code
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <button type="button" class="btn btn-outline-info btn-lg w-100" id="scan-biometric-btn">
                                    <i class="ti ti-fingerprint me-2"></i>Biometric Scan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scanning Form -->
                <form id="scan-attendance-form" class="row g-3 align-items-end" style="display:none;">
                    <!-- Institution Dropdown -->
                    <div class="col-md-3">
                        <label for="scan-institution" class="form-label">Institution *</label>
                        <select class="form-select" id="scan-institution" name="institution" required>
                            <option value="">Select Institution</option>
                            @foreach ($institutions as $institution)
                                <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Role Dropdown -->
                    <div class="col-md-3">
                        <label for="scan-role" class="form-label">Role *</label>
                        <select class="form-select" id="scan-role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="nonworkingstaff">Non-working Staff</option>
                        </select>
                    </div>

                    <!-- Class Dropdown (for students) -->
                    <div class="col-md-3" id="scan-class-field" style="display:none;">
                        <label for="scan-class" class="form-label">Class *</label>
                        <select class="form-select" id="scan-class" name="class">
                            <option value="">Select Class</option>
                        </select>
                    </div>

                    <!-- Section Dropdown (for students) -->
                    <div class="col-md-3" id="scan-section-field" style="display:none;">
                        <label for="scan-section" class="form-label">Section *</label>
                        <select class="form-select" id="scan-section" name="section">
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="col-md-3">
                        <label for="scan-date" class="form-label">Date *</label>
                        <input type="text" class="form-control" id="scan-date" name="date" data-provider="flatpickr"
                            data-date-format="d M, Y" placeholder="dd/mm/yyyy" value="{{ date('d M, Y') }}" required>
                    </div>
                </form>

                <!-- Scanner Container -->
                <div id="scanner-container" class="mt-4" style="display:none;">
                    <div class="card border-primary">
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <h6 id="scanner-title">Scanner Ready</h6>
                                <p class="text-muted" id="scanner-instruction">Position the code within the frame</p>
                            </div>
                            <div id="scanner-view" class="text-center">
                                <video id="scanner-video" width="100%"
                                    style="max-width: 500px; border: 2px solid #0d6efd; border-radius: 8px;" autoplay
                                    playsinline></video>
                                <canvas id="scanner-canvas" style="display:none;"></canvas>
                            </div>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-danger" id="stop-scanner-btn">
                                    <i class="ti ti-x me-1"></i>Stop Scanner
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manual Input (for barcode/biometric) -->
                <div id="manual-input-container" class="mt-4" style="display:none;">
                    <div class="card border-info">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Manual Input</h6>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <input type="text" class="form-control form-control-lg" id="manual-input"
                                        placeholder="Enter Barcode/Biometric ID">
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary btn-lg w-100" id="submit-manual-input">
                                        <i class="ti ti-check me-1"></i>Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scan Results -->
                <div id="scan-results" class="mt-4" style="display:none;">
                    <div class="alert alert-success" role="alert">
                        <h6 class="alert-heading"><i class="ti ti-check-circle me-1"></i>Attendance Marked Successfully!
                        </h6>
                        <p class="mb-0" id="scan-result-message"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="{{ asset('custom/js/admin/scan-attendance.js') }}"></script>
@endpush
