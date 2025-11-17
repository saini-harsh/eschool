<?php $__env->startSection('title', 'Admin | Exam Management | Exam Setup'); ?>
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
                <h5 class="fw-bold">Exam Setup</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('admin.dashboard')); ?>"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Exam Setup</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Add Exam</h6>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('admin.exam-management.exam-setup.store')); ?>" method="post"
                            id="exam-type-form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" id="exam-type-id">
                            <div class="row">
                                <div class="col-3 mb-3">
                                    <label class="form-label">Institution <span class="text-danger">*</span></label>
                                    <select name="institution_id" id="institution_id" class="form-select">
                                        <option value="">Select Institution</option>
                                        <?php if(isset($institutions) && !empty($institutions)): ?>
                                            <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($institution->id); ?>"><?php echo e($institution->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="form-label">Exam Type <span class="text-danger">*</span></label>
                                    <select name="exam_type" id="exam_type" class="form-select" required>
                                        <option value="">Select Exam Type</option>
                                        <?php if(isset($data['exam_types']) && !empty($data['exam_types'])): ?>
                                            <?php $__currentLoopData = $data['exam_types']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($examType->id); ?>"><?php echo e($examType->title); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        placeholder="Enter Exam type title" autocomplete="off" required>
                                </div>

                                <div class="col-3 mb-3">
                                    <label class="form-label">Exam Type Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" id="code" class="form-control"
                                        placeholder="Enter Exam type code" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 mb-3">
                                    <label class="form-label">Class</label>
                                    <select name="class_id" id="class_id" class="form-select">
                                        <option value="">Select Class</option>
                                        <?php if(isset($data['classes']) && !empty($data['classes'])): ?>
                                            <?php $__currentLoopData = $data['classes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-3 mb-3">
                                    <label class="form-label">Section</label>
                                    <select name="section_id" id="section_id" class="form-select">
                                        <option value="">Select Section</option>

                                    </select>
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                                </div>
                            </div>
                            <hr>

                            <h6 class="fw-bold">Schedule Subjects</h6>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered" id="schedule-table">
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Morning</th>
                                                <th>Evening</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows will be injected here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-3 mb-3">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/admin/exam-setup.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/admin/examination/exam/setup.blade.php ENDPATH**/ ?>