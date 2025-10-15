
<?php $__env->startSection('title', 'Institution | Lesson Plans'); ?>
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
            <h5 class="fw-bold">Lesson Plans</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('institution.dashboard')); ?>"><i
                                class="ti ti-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lesson Plans</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Page Header -->
    
    <div class="row">
        <!-- Left Side - Create Form -->
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-bold">Add Lesson Plan</h6>
                </div>
                <div class="card-body">
                    <form action="" method="post" id="lesson-plan-form" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" id="lesson-plan-id">
                        
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter lesson plan title" autocomplete="off" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="2" placeholder="Enter lesson plan description (optional)"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Institution <span class="text-danger">*</span></label>
                            <select name="institution_id" id="institution_id" class="form-select" required>
                                <option value="">Select Institution</option>
                                <?php if(isset($institutions) && !empty($institutions)): ?>
                                    <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($institution->id); ?>"><?php echo e($institution->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Teacher <span class="text-danger">*</span></label>
                            <select name="teacher_id" id="teacher_id" class="form-select" required disabled>
                                <option value="">Select Teacher</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Class <span class="text-danger">*</span></label>
                            <select name="class_id" id="class_id" class="form-select" required disabled>
                                <option value="">Select Class</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subject_id" class="form-select" required disabled>
                                <option value="">Select Subject</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Lesson Plan File (PDF) <span class="text-danger" id="file-required">*</span></label>
                            <input type="file" name="lesson_plan_file" id="lesson_plan_file" class="form-control" accept=".pdf" required>
                            <div class="form-text">Upload a PDF file (Maximum size: 10MB)</div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="lesson-plan-status" checked>
                                <label class="form-check-label" for="lesson-plan-status">Active</label>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary" type="button" id="add-lesson-plan">Submit</button>
                        <button class="btn btn-primary d-none" type="button" id="update-lesson-plan">Update</button>
                        <button class="btn btn-secondary d-none" type="button" id="cancel-edit">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Right Side - List -->
        <div class="col-9">
            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                <div class="datatable-search">
                    <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);"
                            class="btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ti ti-filter me-1"></i>Filter
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0" id="filter-dropdown">
                            <div class="card mb-0">
                                <div class="card-header">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="fw-bold mb-0">Filter</h6>
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:void(0);" class="link-danger text-decoration-underline">Clear All</a>
                                        </div>
                                    </div>
                                </div>
                                <form action="<?php echo e(route('institution.lesson-plans.index')); ?>" method="GET" id="filter-form">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Class</label>
                                                <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                                                    Select Class
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu w-100">
                                                    <?php if(isset($classes) && $classes->count() > 0): ?>
                                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="class_id[]" value="<?php echo e($class->id); ?>" <?php echo e(in_array($class->id, (array) request('class_id', [])) ? 'checked' : ''); ?>>
                                                                    <?php echo e($class->name); ?>

                                                                </label>
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <li><span class="dropdown-item text-muted">No classes available</span></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Subject</label>
                                                <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                                                    Select Subject
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu w-100">
                                                    <?php if(isset($subjects) && $subjects->count() > 0): ?>
                                                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="subject_id[]" value="<?php echo e($subject->id); ?>" <?php echo e(in_array($subject->id, (array) request('subject_id', [])) ? 'checked' : ''); ?>>
                                                                    <?php echo e($subject->name); ?>

                                                                </label>
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <li><span class="dropdown-item text-muted">No subjects available</span></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Teacher</label>
                                                <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                                                    Select Teacher
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu w-100">
                                                    <?php if(isset($teachers) && $teachers->count() > 0): ?>
                                                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="teacher_id[]" value="<?php echo e($teacher->id); ?>" <?php echo e(in_array($teacher->id, (array) request('teacher_id', [])) ? 'checked' : ''); ?>>
                                                                    <?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?>

                                                                </label>
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <li><span class="dropdown-item text-muted">No teachers available</span></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label">Status</label>
                                                <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                                                    Select Status
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu w-100">
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                            <input class="form-check-input m-0 me-2" type="checkbox" name="status[]" value="1" <?php echo e(in_array('1', (array) request('status', [])) ? 'checked' : ''); ?>>
                                                            Active
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                            <input class="form-check-input m-0 me-2" type="checkbox" name="status[]" value="0" <?php echo e(in_array('0', (array) request('status', [])) ? 'checked' : ''); ?>>
                                                            Inactive
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-end">
                                        <button type="button" class="btn btn-outline-white me-2" id="close-filter">Close</button>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="dropdown">
                            <a href="javascript:void(0);"
                                class="dropdown-toggle btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                                data-bs-toggle="dropdown">
                                <i class="ti ti-sort-descending-2 text-dark me-1"></i>Sort By : Newest
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end p-1">
                                <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Newest</a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Oldest</a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Desending</a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a></li>
                                <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-nowrap datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>Title</th>
                            <th>Institution</th>
                            <th>Teacher</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>File</th>
                            <th>Status</th>
                            <th class="no-sort">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($lessonPlans) && !empty($lessonPlans)): ?>
                            <?php $__currentLoopData = $lessonPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lessonPlan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr data-lesson-plan-id="<?php echo e($lessonPlan->id); ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0"><?php echo e($lessonPlan->title); ?></h6>
                                                <?php if($lessonPlan->description): ?>
                                                    <small class="text-muted"><?php echo e(Str::limit($lessonPlan->description, 30)); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0"><?php echo e($lessonPlan->institution->name ?? 'N/A'); ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0">
                                                    <?php echo e($lessonPlan->teacher->first_name ?? ''); ?> 
                                                    <?php echo e($lessonPlan->teacher->middle_name ?? ''); ?> 
                                                    <?php echo e($lessonPlan->teacher->last_name ?? ''); ?>

                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0"><?php echo e($lessonPlan->schoolClass->name ?? 'N/A'); ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0"><?php echo e($lessonPlan->subject->name ?? 'N/A'); ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($lessonPlan->lesson_plan_file): ?>
                                            <a href="<?php echo e(asset($lessonPlan->lesson_plan_file)); ?>" 
                                               target="_blank" class="btn btn-sm btn-outline-primary me-1" title="View PDF">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.lesson-plans.download', $lessonPlan->id)); ?>" 
                                               class="btn btn-sm btn-outline-success" title="Download PDF">
                                                <i class="ti ti-download"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">No file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div>
                                            <select class="form-select status-select lesson-plan-status-select" data-lesson-plan-id="<?php echo e($lessonPlan->id); ?>" data-original-value="<?php echo e($lessonPlan->status); ?>">
                                                <option value="1" <?php echo e($lessonPlan->status == 1 ? 'selected' : ''); ?>>Active</option>
                                                <option value="0" <?php echo e($lessonPlan->status == 0 ? 'selected' : ''); ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-inline-flex align-items-center">
                                            <a href="javascript:void(0);" data-lesson-plan-id="<?php echo e($lessonPlan->id); ?>"
                                                class="btn btn-icon btn-sm btn-outline-white border-0 edit-lesson-plan">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <a href="javascript:void(0);" data-lesson-plan-id="<?php echo e($lessonPlan->id); ?>"
                                                data-lesson-plan-title="<?php echo e($lessonPlan->title); ?>"
                                                class="btn btn-icon btn-sm btn-outline-white border-0 delete-lesson-plan">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- End Content -->

<!-- Delete Modal -->
<div class="modal fade" id="delete_modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Lesson Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Delete Lesson Plan</h6>
                <p>Are you sure you want to delete this lesson plan?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Close filter dropdown
        document.getElementById('close-filter')?.addEventListener('click', function() {
            const dropdown = bootstrap.Dropdown.getInstance(document.querySelector('[data-bs-toggle="dropdown"]'));
            if (dropdown) {
                dropdown.hide();
            }
        });

        // Filter form submission
        const filterForm = document.getElementById('filter-form');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(this);
                const params = new URLSearchParams();
                
                // Add form data to params
                for (let [key, value] of formData.entries()) {
                    if (value) {
                        params.append(key, value);
                    }
                }
                
                // Redirect with filter parameters
                const url = new URL(window.location);
                url.search = params.toString();
                window.location.href = url.toString();
            });
        }

        // Clear all filters
        const clearAllLink = document.querySelector('a[href="javascript:void(0);"].link-danger');
        if (clearAllLink) {
            clearAllLink.addEventListener('click', function() {
                window.location.href = '<?php echo e(route("institution.lesson-plans.index")); ?>';
            });
        }

        // Reset individual filters
        document.querySelectorAll('.link-primary').forEach(function(resetLink) {
            if (resetLink.textContent.trim() === 'Reset') {
                resetLink.addEventListener('click', function() {
                    const parentDiv = this.closest('.mb-3');
                    const checkboxes = parentDiv.querySelectorAll('input[type="checkbox"]');
                    
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                });
            }
        });

        // Sort functionality
        const sortDropdown = document.querySelector('.dropdown:last-child .dropdown-menu');
        if (sortDropdown) {
            sortDropdown.querySelectorAll('a.dropdown-item').forEach(function(sortLink) {
                sortLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const sortValue = this.textContent.trim().toLowerCase();
                    let sortParam = 'newest';
                    
                    switch(sortValue) {
                        case 'oldest':
                            sortParam = 'oldest';
                            break;
                        case 'title (a-z)':
                            sortParam = 'title_asc';
                            break;
                        case 'title (z-a)':
                            sortParam = 'title_desc';
                            break;
                    }
                    
                    // Update sort button text
                    const sortButton = document.querySelector('.dropdown:last-child [data-bs-toggle="dropdown"]');
                    if (sortButton) {
                        sortButton.innerHTML = '<i class="ti ti-sort-descending-2 text-dark me-1"></i>Sort By : ' + this.textContent.trim();
                    }
                    
                    // Redirect with sort parameter
                    const url = new URL(window.location);
                    url.searchParams.set('sort_by', sortParam);
                    window.location.href = url.toString();
                });
            });
        }

        // Search functionality
        const searchInput = document.querySelector('.datatable-search .input-text');
        if (searchInput) {
            searchInput.addEventListener('click', function() {
                const searchBox = document.createElement('input');
                searchBox.type = 'text';
                searchBox.className = 'form-control form-control-sm';
                searchBox.placeholder = 'Search lesson plans...';
                searchBox.style.width = '200px';
                searchBox.value = '<?php echo e(request("search")); ?>';
                
                this.parentNode.replaceChild(searchBox, this);
                searchBox.focus();
                
                searchBox.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const url = new URL(window.location);
                        if (this.value.trim()) {
                            url.searchParams.set('search', this.value.trim());
                        } else {
                            url.searchParams.delete('search');
                        }
                        window.location.href = url.toString();
                    }
                });
                
                searchBox.addEventListener('blur', function() {
                    const searchIcon = document.createElement('a');
                    searchIcon.href = 'javascript:void(0);';
                    searchIcon.className = 'input-text';
                    searchIcon.innerHTML = '<i class="ti ti-search"></i>';
                    
                    this.parentNode.replaceChild(searchIcon, this);
                });
            });
        }
    });
</script>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('custom/js/institution/lesson-plans.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/routines/lesson-plans/index.blade.php ENDPATH**/ ?>