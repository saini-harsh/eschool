@extends('layouts.institution')
@section('title', 'Institution | Students Management')

@push('styles')
    <style>
        #search-results {
            top: 100%;
            left: 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        #search-results .list-group-item {
            border-left: none;
            border-right: none;
            transition: background-color 0.2s;
        }
        
        #search-results .list-group-item:first-child {
            border-top: none;
        }
        
        #search-results .list-group-item:last-child {
            border-bottom: none;
        }
        
        #search-results .list-group-item:hover {
            background-color: #f8f9fa;
        }
        
        #student-search-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }
        
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
        }
        
        #siblings-cards-container .card {
            border: 1px solid #e9ecef;
        }
        
        #siblings-cards-container .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

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
                <h5 class="fw-bold">Students</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="{{ route('institution.students.index') }}"><i class="ti ti-home me-1"></i>Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Students</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button type="button" class="btn btn-info" id="viewSiblingsBtn">
                    <i class="ti ti-users me-1"></i>View Students with Siblings
                </button>
                <a href="{{ route('institution.admission.admission-form') }}" class="btn btn-outline-secondary"
                    id="openAdmissionForm">
                    <i class="ti ti-form me-1"></i>Open Admission Form
                </a>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportStudentsModal">
                    <i class="ti ti-download me-1"></i>Export Students
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importStudentsModal">
                    <i class="ti ti-upload me-1"></i>Import Students
                </button>
                <a href="{{ route('institution.students.create') }}" class="btn btn-primary"><i
                        class="ti ti-circle-plus me-1"></i>New Student</a>

            </div>
        </div>
        <!-- End Page Header -->

        <!-- Student Search Section -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="position-relative">
                    <label for="student-search-input" class="form-label fw-semibold mb-2">
                        <i class="ti ti-search me-1"></i>Search Students
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0" 
                               id="student-search-input" 
                               placeholder="Search by name, student ID, admission number, roll number, or email..."
                               autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" id="clear-search" style="display: none;">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                    <!-- Search Results Dropdown -->
                    <div id="search-results" class="position-absolute w-100 bg-white border rounded shadow-lg mt-1" 
                         style="display: none; z-index: 1000; max-height: 400px; overflow-y: auto;">
                        <div id="search-results-list" class="list-group list-group-flush">
                            <!-- Results will be populated here -->
                        </div>
                        <div id="search-loading" class="text-center p-3" style="display: none;">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="ms-2 text-muted">Searching...</span>
                        </div>
                        <div id="search-empty" class="text-center p-3 text-muted" style="display: none;">
                            <i class="ti ti-search-off fs-24 mb-2 d-block"></i>
                            <p class="mb-0">No students found</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students with Siblings Section (Hidden by default) -->
        <div id="siblings-section" style="display: none;">
            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="backToClassesBtn">
                        <i class="ti ti-arrow-left me-1"></i>Back to Classes
                    </button>
                    <h5 class="mb-0 fw-bold">
                        <i class="ti ti-users me-2"></i>Students with Siblings
                    </h5>
                </div>
            </div>
            
            <div id="siblings-loading" class="text-center py-5" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted mt-2">Loading students with siblings...</p>
            </div>
            
            <div id="siblings-empty" class="text-center py-5" style="display: none;">
                <div class="avatar avatar-lg bg-light text-muted rounded-circle mx-auto mb-3">
                    <i class="ti ti-users-off fs-24"></i>
                </div>
                <h5 class="text-muted">No Students with Siblings</h5>
                <p class="text-muted">No students with siblings found in your institution.</p>
            </div>
            
            <div id="siblings-cards-container" class="row">
                <!-- Cards will be populated here -->
            </div>
        </div>

        <!-- Classes Cards Section -->
        <div id="classes-section">
            <div class="row" id="classes-grid">

                @if (isset($classes) && count($classes) > 0)
                    @foreach ($classes as $class)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card class-card h-100" data-class-id="{{ $class->id }}"
                                style="cursor: pointer; transition: all 0.3s ease;">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div class="avatar avatar-lg bg-primary text-white rounded-circle mx-auto">
                                            <i class="ti ti-school fs-24"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-2">Class - {{ $class->name }}</h5>
                                    <p class="text-muted mb-3">
                                        <i class="ti ti-users me-1"></i>
                                        {{ $class->students_count ?? 0 }} Students
                                    </p>
                                    <div class="d-flex justify-content-center">
                                        <span class="badge badge-soft-primary">Click to view students</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="avatar avatar-lg bg-light text-muted rounded-circle mx-auto mb-3">
                                <i class="ti ti-school fs-24"></i>
                            </div>
                            <h5 class="text-muted">Add Classes</h5>
                            <p class="text-muted">Create classes first to manage students.</p>
                            <a href="{{ route('institution.classes.index') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>Create Class
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Students List Section (Hidden by default) -->
        <div id="students-section" style="display: none;">
            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-outline-secondary me-3" id="back-to-classes">
                        <i class="ti ti-arrow-left me-1"></i>Back to Classes
                    </button>
                    <h6 class="mb-0" id="selected-class-name">Students</h6>
                </div>
            </div>

            <!-- Table Filters -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="section-filter" class="form-label">Filter by Section</label>
                            <select class="form-select" id="section-filter">
                                <option value="">All Sections</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status-filter" class="form-label">Filter by Status</label>
                            <select class="form-select" id="status-filter">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="student-search" class="form-label">Search Students</label>
                            <input type="text" class="form-control" id="student-search"
                                placeholder="Search by name or email...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-nowrap" id="students-table">
                    <thead class="thead-ight">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Section</th>
                            <th>Teacher</th>
                            <th>Status</th>
                            <th class="no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody id="students-tbody">
                        <!-- Students will be loaded here via AJAX -->
                    </tbody>
                </table>
            </div>

            <!-- Loading and Empty States -->
            <div id="students-loading" class="text-center py-5" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading students...</p>
            </div>

            <div id="students-empty" class="text-center py-5" style="display: none;">
                <div class="avatar avatar-lg bg-light text-muted rounded-circle mx-auto mb-3">
                    <i class="ti ti-users fs-24"></i>
                </div>
                <h5 class="text-muted">No Students Found</h5>
                <p class="text-muted">This class doesn't have any students yet.</p>
            </div>
        </div>

        <!-- Import Students Modal -->
        <div class="modal fade" id="importStudentsModal" tabindex="-1" aria-labelledby="importStudentsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importStudentsModalLabel">
                            <i class="ti ti-upload me-2"></i>Import Students from CSV
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="importStudentsForm" action="{{ route('institution.students.import') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="class_id" class="form-label">Select Class <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="class_id" name="class_id" required>
                                        <option value="">Choose Class</option>
                                        @if (isset($classes) && !empty($classes))
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('class_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="section_id" class="form-label">Select Section</label>
                                    <select class="form-select" id="section_id" name="section_id">
                                        <option value="">Choose Section (Optional)</option>
                                    </select>
                                    @error('section_id')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <small class="text-muted">Section is optional. Leave blank if not applicable.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="csv_file" class="form-label">Upload CSV/Excel File <span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="csv_file" name="csv_file"
                                    accept=".csv,.xlsx,.xls" required>
                                <div class="form-text">
                                    <small class="text-muted">
                                        <i class="ti ti-info-circle me-1"></i>
                                        Please upload a CSV or Excel file (.csv, .xlsx, .xls) with student data.
                                        <a href="#" id="downloadTemplate" class="text-primary">Download
                                            template</a>
                                    </small>
                                </div>
                                @error('csv_file')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- CSV/Excel Template Information -->
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="ti ti-info-circle me-1"></i>CSV/Excel Format Requirements
                                </h6>
                                <p class="mb-2">Your file should contain the following columns (Excel format supported):</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Required Fields:</strong><br>
                                            • <strong>Name</strong> (First Middle Last or First Last)<br>
                                            • <strong>Gender</strong> (Male/Female/Other)<br>
                                            • <strong>DOB</strong> (Date of Birth - YYYY-MM-DD, DD/MM/YYYY, or DD-MM-YYYY)<br>
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Optional Fields:</strong><br>
                                            • Roll No.<br>
                                            • DOB Status<br>
                                            • PEN No.<br>
                                            • Aadhaar No.<br>
                                            • Mother's Name<br>
                                            • Father's Name<br>
                                            • WhatsApp No. (used as phone if phone not provided)<br>
                                            • Admission Date<br>
                                            • Address Verification<br>
                                            • Admission Amount<br>
                                            • KSO ID<br>
                                            • Total Payment<br>
                                            • Admission Status<br>
                                            • Sibling Name<br>
                                            • Name of the School<br>
                                            • Class<br>
                                            • Result<br>
                                            • Email<br>
                                            • Phone<br>
                                            • Address<br>
                                            • Pincode<br>
                                            • District
                                        </small>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <strong>Note:</strong> The system will automatically map Excel column headers to database fields. 
                                        Section assignment is optional and can be set during import or left blank.
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ti ti-x me-1"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="importBtn">
                                <i class="ti ti-upload me-1"></i>Import Students
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Export Students Modal -->
        <div class="modal fade" id="exportStudentsModal" tabindex="-1" aria-labelledby="exportStudentsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportStudentsModalLabel">
                            <i class="ti ti-download me-2"></i>Export Students to CSV
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="exportStudentsForm">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="export_class_id" class="form-label">Select Class <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="export_class_id" name="class_id" required>
                                        <option value="">Choose Class</option>
                                        @if (isset($classes) && !empty($classes))
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="export_section_id" class="form-label">Select Section</label>
                                    <select class="form-select" id="export_section_id" name="section_id">
                                        <option value="">All Sections</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Export Options -->
                            <div class="mb-3">
                                <label class="form-label">Export Options</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="include_inactive"
                                        name="include_inactive">
                                    <label class="form-check-label" for="include_inactive">
                                        Include inactive students
                                    </label>
                                </div>
                            </div>

                            <!-- Export Information -->
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="ti ti-info-circle me-1"></i>Export Information
                                </h6>
                                <p class="mb-2">The exported CSV file will contain the following student information:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Student Details:</strong><br>
                                            • Student ID, Name, Email, Phone<br>
                                            • Date of Birth, Address, Gender<br>
                                            • Admission Details, Roll Number<br>
                                            • Class, Section, Teacher
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Additional Information:</strong><br>
                                            • Parent Names, Religion, Blood Group<br>
                                            • Caste/Tribe, District, Pincode<br>
                                            • Status, Institution Code<br>
                                            • Permanent Address
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ti ti-x me-1"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-success" id="exportBtn">
                                <i class="ti ti-download me-1"></i>Export Students
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>



    <style>
        .class-card {
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .class-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #007bff;
        }

        .class-card .avatar {
            transition: all 0.3s ease;
        }

        .class-card:hover .avatar {
            transform: scale(1.1);
        }

        .students-section-hidden {
            display: none !important;
        }

        .classes-section-hidden {
            display: none !important;
        }
    </style>

    <script>
        // Auto-hide existing toast notifications
        setTimeout(() => {
            const toastEl = document.querySelector('.toast');
            if (toastEl) {
                const bsToast = bootstrap.Toast.getOrCreateInstance(toastEl);
                bsToast.hide();
            }
        }, 3000); // Hide after 3 seconds

        // Import Students Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class_id');
            const sectionSelect = document.getElementById('section_id');
            const importForm = document.getElementById('importStudentsForm');
            const importBtn = document.getElementById('importBtn');
            const downloadTemplate = document.getElementById('downloadTemplate');

            // Export Students Modal functionality
            const exportClassSelect = document.getElementById('export_class_id');
            const exportSectionSelect = document.getElementById('export_section_id');
            const exportForm = document.getElementById('exportStudentsForm');
            const exportBtn = document.getElementById('exportBtn');

            // Handle class selection change
            classSelect.addEventListener('change', function() {
                const classId = this.value;
                sectionSelect.innerHTML = '<option value="">Choose Section</option>';

                if (classId) {
                    fetch(`/institution/students/sections/${classId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.sections && data.sections.length > 0) {
                                data.sections.forEach(section => {
                                    const option = document.createElement('option');
                                    option.value = section.id;
                                    option.textContent = section.name;
                                    sectionSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching sections:', error);
                        });
                }
            });

            // Handle form submission
            importForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = importBtn;
                const originalText = submitBtn.innerHTML;

                // Show loading state
                submitBtn.innerHTML = '<i class="ti ti-loader-2 me-1"></i>Importing...';
                submitBtn.disabled = true;

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            showToast('success', data.message || 'Students imported successfully!');

                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'importStudentsModal'));
                            modal.hide();

                            // Reset form
                            importForm.reset();
                            sectionSelect.innerHTML = '<option value="">Choose Section</option>';

                            // Reload page to show updated data
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showToast('error', data.message ||
                                'Import failed. Please check your CSV file.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'An error occurred during import. Please try again.');
                    })
                    .finally(() => {
                        // Reset button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });

            // Handle template download
            downloadTemplate.addEventListener('click', function(e) {
                e.preventDefault();
                // Redirect to server-side template download
                window.location.href = '{{ route("institution.students.download-template") }}';
            });

            // Handle export class selection change
            exportClassSelect.addEventListener('change', function() {
                const classId = this.value;
                exportSectionSelect.innerHTML = '<option value="">All Sections</option>';

                if (classId) {
                    fetch(`/institution/students/sections/${classId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.sections && data.sections.length > 0) {
                                data.sections.forEach(section => {
                                    const option = document.createElement('option');
                                    option.value = section.id;
                                    option.textContent = section.name;
                                    exportSectionSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching sections:', error);
                        });
                }
            });

            // Handle export form submission
            exportForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const classId = exportClassSelect.value;
                const sectionId = exportSectionSelect.value;
                const includeInactive = document.getElementById('include_inactive').checked;

                if (!classId) {
                    showToast('error', 'Please select a class');
                    return;
                }

                // Show loading state
                const submitBtn = exportBtn;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="ti ti-loader-2 me-1"></i>Exporting...';
                submitBtn.disabled = true;

                // Build export URL
                let exportUrl = `/institution/students/export/class/${classId}`;
                const params = new URLSearchParams();

                if (sectionId) {
                    params.append('section_id', sectionId);
                }
                if (includeInactive) {
                    params.append('include_inactive', '1');
                }

                if (params.toString()) {
                    exportUrl += '?' + params.toString();
                }

                // Create a temporary form to trigger download
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = exportUrl;
                form.target = '_blank';
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('exportStudentsModal'));
                modal.hide();

                // Reset form
                exportForm.reset();
                exportSectionSelect.innerHTML = '<option value="">All Sections</option>';

                // Reset button state
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 1000);

                showToast('success', 'Export started successfully!');
            });

            // Toast notification function
            function showToast(type, message) {
                const toastContainer = document.querySelector('.position-fixed.top-0.end-0.p-3') ||
                    createToastContainer();

                const toastEl = document.createElement('div');
                toastEl.className =
                    `toast align-items-center text-bg-${type === 'success' ? 'success' : 'danger'} border-0 show`;
                toastEl.setAttribute('role', 'alert');
                toastEl.setAttribute('aria-live', 'assertive');
                toastEl.setAttribute('aria-atomic', 'true');

                toastEl.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                `;

                toastContainer.appendChild(toastEl);

                const bsToast = new bootstrap.Toast(toastEl);
                bsToast.show();

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    bsToast.hide();
                }, 5000);
            }

            function createToastContainer() {
                const container = document.createElement('div');
                container.className = 'position-fixed top-0 end-0 p-3';
                container.style.zIndex = '1050';
                document.body.appendChild(container);
                return container;
            }
        });
    </script>
    <!-- End Content -->
@endsection

@push('scripts')
    <script>
        // Escape HTML function (shared)
        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('student-search-input');
            const searchResults = document.getElementById('search-results');
            const searchResultsList = document.getElementById('search-results-list');
            const searchLoading = document.getElementById('search-loading');
            const searchEmpty = document.getElementById('search-empty');
            const clearSearchBtn = document.getElementById('clear-search');
            let searchTimeout;

            // Show/hide clear button
            searchInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                    hideSearchResults();
                }
            });

            // Clear search
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                this.style.display = 'none';
                hideSearchResults();
                searchInput.focus();
            });

            // Search on keypress
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                // Clear previous timeout
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    hideSearchResults();
                    return;
                }

                // Show loading state
                showSearchLoading();

                // Debounce search
                searchTimeout = setTimeout(function() {
                    performSearch(query);
                }, 300);
            });

            // Hide results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    hideSearchResults();
                }
            });

            // Perform search
            function performSearch(query) {
                fetch(`{{ route('institution.students.search') }}?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideSearchLoading();
                    
                    if (data.students && data.students.length > 0) {
                        displaySearchResults(data.students);
                    } else {
                        showSearchEmpty();
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    hideSearchLoading();
                    showSearchEmpty();
                });
            }

            // Display search results
            function displaySearchResults(students) {
                searchResultsList.innerHTML = '';
                searchEmpty.style.display = 'none';
                
                students.forEach(function(student) {
                    const listItem = document.createElement('a');
                    listItem.href = student.url;
                    listItem.className = 'list-group-item list-group-item-action';
                    listItem.style.cursor = 'pointer';
                    
                    const photoHtml = student.photo 
                        ? `<img src="/${student.photo}" alt="${student.name}" class="avatar avatar-sm avatar-rounded me-2" style="object-fit: cover;" onerror="this.onerror=null; this.src=''; this.outerHTML='<span class=\\'avatar avatar-sm avatar-rounded bg-light border me-2\\'><i class=\\'ti ti-user text-muted\\'></i></span>';">`
                        : `<span class="avatar avatar-sm avatar-rounded bg-light border me-2"><i class="ti ti-user text-muted"></i></span>`;
                    
                    listItem.innerHTML = `
                        <div class="d-flex align-items-center">
                            ${photoHtml}
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold">${escapeHtml(student.name)}</h6>
                                <small class="text-muted">
                                    <span class="me-2"><i class="ti ti-id me-1"></i>${escapeHtml(student.student_id || 'N/A')}</span>
                                    ${student.admission_number ? `<span class="me-2"><i class="ti ti-ticket me-1"></i>${escapeHtml(student.admission_number)}</span>` : ''}
                                    ${student.roll_number ? `<span class="me-2"><i class="ti ti-hash me-1"></i>${escapeHtml(student.roll_number)}</span>` : ''}
                                </small>
                                <div class="mt-1">
                                    <span class="badge badge-soft-primary me-1">${escapeHtml(student.class)}</span>
                                    ${student.section !== 'N/A' ? `<span class="badge badge-soft-secondary">${escapeHtml(student.section)}</span>` : ''}
                                </div>
                            </div>
                            <i class="ti ti-chevron-right text-muted"></i>
                        </div>
                    `;
                    
                    searchResultsList.appendChild(listItem);
                });
                
                searchResults.style.display = 'block';
            }

            // Show loading state
            function showSearchLoading() {
                searchResultsList.innerHTML = '';
                searchEmpty.style.display = 'none';
                searchLoading.style.display = 'block';
                searchResults.style.display = 'block';
            }

            // Hide loading state
            function hideSearchLoading() {
                searchLoading.style.display = 'none';
            }

            // Show empty state
            function showSearchEmpty() {
                searchResultsList.innerHTML = '';
                searchLoading.style.display = 'none';
                searchEmpty.style.display = 'block';
                searchResults.style.display = 'block';
            }

            // Hide search results
            function hideSearchResults() {
                searchResults.style.display = 'none';
                searchResultsList.innerHTML = '';
                searchLoading.style.display = 'none';
                searchEmpty.style.display = 'none';
            }

            // Handle keyboard navigation
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideSearchResults();
                    this.blur();
                }
            });
        });

        // Students with Siblings functionality
        document.addEventListener('DOMContentLoaded', function() {
            const viewSiblingsBtn = document.getElementById('viewSiblingsBtn');
            const backToClassesBtn = document.getElementById('backToClassesBtn');
            const siblingsSection = document.getElementById('siblings-section');
            const classesSection = document.getElementById('classes-section');
            const siblingsCardsContainer = document.getElementById('siblings-cards-container');
            const siblingsLoading = document.getElementById('siblings-loading');
            const siblingsEmpty = document.getElementById('siblings-empty');

            // View students with siblings
            if (viewSiblingsBtn) {
                viewSiblingsBtn.addEventListener('click', function() {
                    loadStudentsWithSiblings();
                });
            }

            // Back to classes
            if (backToClassesBtn) {
                backToClassesBtn.addEventListener('click', function() {
                    classesSection.style.display = 'block';
                    siblingsSection.style.display = 'none';
                });
            }

            function loadStudentsWithSiblings() {
                // Show siblings section and hide classes section
                classesSection.style.display = 'none';
                siblingsSection.style.display = 'block';
                siblingsCardsContainer.innerHTML = '';
                siblingsLoading.style.display = 'block';
                siblingsEmpty.style.display = 'none';

                fetch('{{ route("institution.students.with-siblings") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    siblingsLoading.style.display = 'none';
                    
                    if (data.students && data.students.length > 0) {
                        displaySiblingsCards(data.students);
                    } else {
                        siblingsEmpty.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error loading students with siblings:', error);
                    siblingsLoading.style.display = 'none';
                    siblingsEmpty.style.display = 'block';
                });
            }

            function displaySiblingsCards(students) {
                siblingsCardsContainer.innerHTML = '';
                
                students.forEach(function(student) {
                    const card = document.createElement('div');
                    card.className = 'col-lg-4 col-md-6 mb-4';
                    
                    const photoHtml = student.photo 
                        ? `<img src="/${student.photo}" alt="${student.name}" class="avatar avatar-xl avatar-rounded mb-3" style="object-fit: cover;" onerror="this.onerror=null; this.src=''; this.outerHTML='<span class=\\'avatar avatar-xl avatar-rounded bg-light border mb-3\\'><i class=\\'ti ti-user fs-24 text-muted\\'></i></span>';"`
                        : `<span class="avatar avatar-xl avatar-rounded bg-light border mb-3"><i class="ti ti-user fs-24 text-muted"></i></span>`;
                    
                    let siblingsHtml = '';
                    if (student.siblings && student.siblings.length > 0) {
                        siblingsHtml = '<div class="mt-3"><h6 class="fs-14 fw-semibold mb-2"><i class="ti ti-users me-1"></i>Siblings:</h6><div class="list-group list-group-flush">';
                        student.siblings.forEach(function(sibling) {
                            const siblingPhoto = sibling.photo 
                                ? `<img src="/${sibling.photo}" alt="${sibling.name}" class="avatar avatar-sm avatar-rounded me-2" style="object-fit: cover;" onerror="this.onerror=null; this.src=''; this.outerHTML='<span class=\\'avatar avatar-sm avatar-rounded bg-light border me-2\\'><i class=\\'ti ti-user text-muted\\'></i></span>';"`
                                : `<span class="avatar avatar-sm avatar-rounded bg-light border me-2"><i class="ti ti-user text-muted"></i></span>`;
                            
                            siblingsHtml += `
                                <a href="${sibling.url}" class="list-group-item list-group-item-action border-0 px-0 py-2">
                                    <div class="d-flex align-items-center">
                                        ${siblingPhoto}
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fs-13 fw-semibold">${escapeHtml(sibling.name)}</h6>
                                            <small class="text-muted">
                                                <span class="me-2">${escapeHtml(sibling.student_id)}</span>
                                                <span class="badge badge-soft-primary">${escapeHtml(sibling.class)}</span>
                                            </small>
                                        </div>
                                        <i class="ti ti-chevron-right text-muted"></i>
                                    </div>
                                </a>
                            `;
                        });
                        siblingsHtml += '</div></div>';
                    }
                    
                    card.innerHTML = `
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                ${photoHtml}
                                <h5 class="card-title mb-2">
                                    <a href="${student.url}" class="text-dark text-decoration-none">
                                        ${escapeHtml(student.name)}
                                    </a>
                                </h5>
                                <p class="text-muted mb-2">
                                    <i class="ti ti-id me-1"></i>${escapeHtml(student.student_id || 'N/A')}
                                </p>
                                ${student.admission_number ? `<p class="text-muted mb-2"><i class="ti ti-ticket me-1"></i>${escapeHtml(student.admission_number)}</p>` : ''}
                                ${student.roll_number ? `<p class="text-muted mb-2"><i class="ti ti-hash me-1"></i>${escapeHtml(student.roll_number)}</p>` : ''}
                                <div class="mb-2">
                                    <span class="badge badge-soft-primary me-1">${escapeHtml(student.class)}</span>
                                    ${student.section !== 'N/A' ? `<span class="badge badge-soft-secondary">${escapeHtml(student.section)}</span>` : ''}
                                </div>
                                <div class="mb-2">
                                    <span class="badge badge-soft-info">
                                        <i class="ti ti-users me-1"></i>${student.siblings_count} Sibling${student.siblings_count !== 1 ? 's' : ''}
                                    </span>
                                </div>
                                ${siblingsHtml}
                                <div class="mt-3">
                                    <a href="${student.url}" class="btn btn-sm btn-primary">
                                        <i class="ti ti-eye me-1"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    siblingsCardsContainer.appendChild(card);
                });
            }
        });
    </script>
    <script src="{{ asset('custom/js/institution/students.js') }}"></script>
@endpush
