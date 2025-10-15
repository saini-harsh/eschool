
<?php $__env->startSection('title', 'Admin | Assign Subject Management'); ?>
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
                <h5 class="fw-bold">Assign Subject</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="index.html"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Assign Subject</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Assign Subject</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="assign-subject-form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" id="assign-subject-id">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Institution <span class="text-danger">*</span></label>
                                        <select class="form-select" name="institution_id" id="institution_id" required>
                                            <option value="">Select Institution</option>
                                            <?php if(isset($institutions) && !empty($institutions)): ?>
                                                <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($institution->id); ?>"><?php echo e($institution->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Class <span class="text-danger">*</span></label>
                                        <select class="form-select" name="class_id" id="class_id" required disabled>
                                            <option value="">Select Class</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Section <span class="text-danger">*</span></label>
                                        <select class="form-select" name="section_id" id="section_id" required disabled>
                                            <option value="">Select Section</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Subject <span class="text-danger">*</span></label>
                                        <select class="form-select" name="subject_id" id="subject_id" required disabled>
                                            <option value="">Select Subject</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <label class="form-label">Teacher <span class="text-danger">*</span></label>
                                        <select class="form-select" name="teacher_id" id="teacher_id" required disabled>
                                            <option value="">Select Teacher</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="status" value="1"
                                                id="assign-subject-status" checked>
                                            <label class="form-check-label" for="assign-subject-status">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="button" id="add-assign-subject">Submit</button>
                            <button class="btn btn-primary d-none" type="button" id="update-assign-subject">Update</button>
                            <button class="btn btn-secondary d-none" type="button" id="cancel-edit">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
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
                                                <a href="javascript:void(0);"
                                                    class="link-danger text-decoration-underline">Clear
                                                    All</a>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="" id="filter-form">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="form-label">Institution</label>
                                                    <a href="javascript:void(0);" class="link-primary mb-1 filter-reset" data-field="institution_ids">Reset</a>
                                                </div>
                                                <div class="dropdown">
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                        aria-expanded="true">
                                                        Select Institution
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu w-100">
                                                        <?php if(isset($institutions) && !empty($institutions)): ?>
                                                            <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox" 
                                                                               name="institution_ids[]" value="<?php echo e($institution->id); ?>">
                                                                        <?php echo e($institution->name); ?>

                                                                    </label>
                                                                </li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="form-label">Class</label>
                                                    <a href="javascript:void(0);" class="link-primary mb-1 filter-reset" data-field="class_ids">Reset</a>
                                                </div>
                                                <div class="dropdown">
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                        aria-expanded="true">
                                                        Select Class
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu w-100">
                                                        <?php if(isset($classes) && !empty($classes)): ?>
                                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox" 
                                                                               name="class_ids[]" value="<?php echo e($class->id); ?>">
                                                                        <?php echo e($class->name); ?>

                                                                    </label>
                                                                </li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="form-label">Teacher</label>
                                                    <a href="javascript:void(0);" class="link-primary mb-1 filter-reset" data-field="teacher_ids">Reset</a>
                                                </div>
                                                <div class="dropdown">
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                        aria-expanded="true">
                                                        Select Teacher
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu w-100">
                                                        <?php if(isset($teachers) && !empty($teachers)): ?>
                                                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox" 
                                                                               name="teacher_ids[]" value="<?php echo e($teacher->id); ?>">
                                                                        <?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?>

                                                                    </label>
                                                                </li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="form-label">Subject</label>
                                                    <a href="javascript:void(0);" class="link-primary mb-1 filter-reset" data-field="subject_ids">Reset</a>
                                                </div>
                                                <div class="dropdown">
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                        aria-expanded="true">
                                                        Select Subject
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu w-100">
                                                        <?php if(isset($subjects) && !empty($subjects)): ?>
                                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox" 
                                                                               name="subject_ids[]" value="<?php echo e($subject->id); ?>">
                                                                        <?php echo e($subject->name); ?>

                                                                    </label>
                                                                </li>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="form-label">Status</label>
                                                    <a href="javascript:void(0);" class="link-primary mb-1 filter-reset" data-field="status">Reset</a>
                                                </div>
                                                <div class="dropdown">
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                        aria-expanded="true">
                                                        Select Status
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu w-100">
                                                        <li>
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                <input class="form-check-input m-0 me-2" type="checkbox" 
                                                                       name="status[]" value="1">
                                                                Active
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label
                                                                class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                <input class="form-check-input m-0 me-2" type="checkbox" 
                                                                       name="status[]" value="0">
                                                                Inactive
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <label class="form-label">Created Date Range</label>
                                                    <a href="javascript:void(0);" class="link-primary mb-1 filter-reset" data-field="created_date_range">Reset</a>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <input type="date" name="created_date_from" class="form-control" placeholder="From Date">
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="date" name="created_date_to" class="form-control" placeholder="To Date">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer d-flex align-items-center justify-content-end">
                                            <button type="button" class="btn btn-outline-white me-2"
                                                id="close-filter">Close</button>
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
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Newest</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Oldest</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Desending</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-nowrap datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>Teacher</th>
                                <th>Institution</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($lists) && !empty($lists)): ?>
                                <?php $__currentLoopData = $lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-assign-subject-id="<?php echo e($list->id); ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <h6 class="fs-14 mb-0">
                                                        <?php echo e($list->teacher->first_name ?? ''); ?> 
                                                        <?php echo e($list->teacher->middle_name ?? ''); ?> 
                                                        <?php echo e($list->teacher->last_name ?? ''); ?>

                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <h6 class="fs-14 mb-0"><?php echo e($list->institution->name ?? 'N/A'); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <h6 class="fs-14 mb-0"><?php echo e($list->schoolClass->name ?? 'N/A'); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <h6 class="fs-14 mb-0"><?php echo e($list->section->name ?? 'N/A'); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <h6 class="fs-14 mb-0"><?php echo e($list->subject->name ?? 'N/A'); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <select class="form-select status-select assign-subject-status-select" data-assign-subject-id="<?php echo e($list->id); ?>" data-original-value="<?php echo e($list->status); ?>">
                                                    <option value="1" <?php echo e($list->status == 1 ? 'selected' : ''); ?>>Active</option>
                                                    <option value="0" <?php echo e($list->status == 0 ? 'selected' : ''); ?>>Inactive</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-inline-flex align-items-center">
                                                <a href="javascript:void(0);" data-assign-subject-id="<?php echo e($list->id); ?>"
                                                    class="btn btn-icon btn-sm btn-outline-white border-0 edit-assign-subject">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <a href="javascript:void(0);" data-assign-subject-id="<?php echo e($list->id); ?>"
                                                    data-teacher-name="<?php echo e($list->teacher->first_name ?? ''); ?> <?php echo e($list->teacher->last_name ?? ''); ?>"
                                                    data-subject-name="<?php echo e($list->subject->name ?? ''); ?>"
                                                    class="btn btn-icon btn-sm btn-outline-white border-0 delete-assign-subject">
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Subject Assignment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Delete Subject Assignment</h6>
                    <p>Are you sure you want to delete this subject assignment?</p>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/admin/assign-subject.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/admin/academic/assign-subject.blade.php ENDPATH**/ ?>