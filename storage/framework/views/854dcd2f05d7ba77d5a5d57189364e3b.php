<?php $__env->startSection('title', 'Institution | Students Management'); ?>
<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo e(session('success')); ?>

                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Start Content -->
    <div class="content">

        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Students</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="<?php echo e(route('institution.students.index')); ?>"><i class="ti ti-home me-1"></i>Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Students</li>
                    </ol>
                </nav>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importStudentsModal">
                    <i class="ti ti-upload me-1"></i>Import Students
                </button>
                <a href="<?php echo e(route('institution.students.create')); ?>" class="btn btn-primary"><i
                        class="ti ti-circle-plus me-1"></i>New Student</a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Classes Cards Section -->
        <div id="classes-section">
            <div class="row" id="classes-grid">
                <?php if(isset($classes) && !empty($classes)): ?>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card class-card h-100" data-class-id="<?php echo e($class->id); ?>"
                                style="cursor: pointer; transition: all 0.3s ease;">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div class="avatar avatar-lg bg-primary text-white rounded-circle mx-auto">
                                            <i class="ti ti-school fs-24"></i>
                                        </div>
                                    </div>
                                    <h5 class="card-title mb-2"><?php echo e($class->name); ?></h5>
                                    <p class="text-muted mb-3">
                                        <i class="ti ti-users me-1"></i>
                                        <?php echo e($class->students_count ?? 0); ?> Students
                                    </p>
                                    <div class="d-flex justify-content-center">
                                        <span class="badge badge-soft-primary">Click to view students</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="avatar avatar-lg bg-light text-muted rounded-circle mx-auto mb-3">
                                <i class="ti ti-school fs-24"></i>
                            </div>
                            <h5 class="text-muted">No Classes Found</h5>
                            <p class="text-muted">Create classes first to manage students.</p>
                            <a href="<?php echo e(route('institution.classes.index')); ?>" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>Create Class
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
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
                    <form id="importStudentsForm" action="<?php echo e(route('institution.students.import')); ?>" method="POST"
                        enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="class_id" class="form-label">Select Class <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="class_id" name="class_id" required>
                                        <option value="">Choose Class</option>
                                        <?php if(isset($classes) && !empty($classes)): ?>
                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="section_id" class="form-label">Select Section <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="section_id" name="section_id" required>
                                        <option value="">Choose Section</option>
                                    </select>
                                    <?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="csv_file" class="form-label">Upload CSV File <span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="csv_file" name="csv_file"
                                    accept=".csv" required>
                                <div class="form-text">
                                    <small class="text-muted">
                                        <i class="ti ti-info-circle me-1"></i>
                                        Please upload a CSV file with student data.
                                        <a href="#" id="downloadTemplate" class="text-primary">Download
                                            template</a>
                                    </small>
                                </div>
                                <?php $__errorArgs = ['csv_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- CSV Template Information -->
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="ti ti-info-circle me-1"></i>CSV Format Requirements
                                </h6>
                                <p class="mb-2">Your CSV file should contain the following columns:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Required Fields:</strong><br>
                                            • first_name<br>
                                            • last_name<br>
                                            • email<br>
                                            • phone<br>
                                            • dob (YYYY-MM-DD)<br>
                                            • address<br>
                                            • pincode<br>
                                            • gender (Male/Female/Other)<br>
                                            • district
                                        </small>
                                    </div>
                                    <div class="col-md-6">
                                        <small>
                                            <strong>Optional Fields:</strong><br>
                                            • middle_name<br>
                                            • permanent_address<br>
                                            • caste_tribe<br>
                                            • admission_date<br>
                                            • admission_number<br>
                                            • roll_number<br>
                                            • religion<br>
                                            • blood_group<br>
                                            • father_name<br>
                                            • mother_name
                                        </small>
                                    </div>
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

                // Create CSV template content
                const csvContent = [
                    'first_name,last_name,middle_name,email,phone,dob,address,permanent_address,pincode,gender,caste_tribe,district,admission_date,admission_number,roll_number,religion,blood_group,father_name,mother_name',
                    'John,Doe,Michael,john.doe@example.com,1234567890,2010-05-15,123 Main St,123 Main St,12345,Male,General,New York,2024-01-15,ADM001,001,Christian,A+,John Doe Sr,Jane Doe',
                    'Jane,Smith,,jane.smith@example.com,0987654321,2010-08-20,456 Oak Ave,,54321,Female,General,California,2024-01-15,ADM002,002,Muslim,B+,Robert Smith,Mary Smith'
                ].join('\n');

                // Create and download file
                const blob = new Blob([csvContent], {
                    type: 'text/csv'
                });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'students_import_template.csv';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/institution/students.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/institution/administration/students/index.blade.php ENDPATH**/ ?>