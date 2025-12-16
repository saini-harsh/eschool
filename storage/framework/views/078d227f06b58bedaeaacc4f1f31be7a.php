<?php $__env->startSection('title', 'Admission List'); ?>
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

    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Admission Forms</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <i class="ti ti-home me-1"></i>Home
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('institution.students.index')); ?>">Students</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Admissions</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?php echo e(route('institution.admission.admission-form')); ?>" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i>New Admission
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Filters Card -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="ti ti-filter me-2"></i>Filters</h6>
                <a href="<?php echo e(route('institution.admission.list')); ?>" class="link-danger text-decoration-underline">
                    <i class="ti ti-x me-1"></i>Clear All
                </a>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('institution.admission.list')); ?>" method="GET" id="filterForm">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" name="search" id="search" class="form-control"
                                value="<?php echo e(request('search')); ?>" placeholder="Name, Admission No, Phone, Email...">
                        </div>

                        <!-- Class Filter -->
                        <div class="col-md-3">
                            <label for="class_id" class="form-label">Class</label>
                            <select name="class_id" id="class_id" class="form-select">
                                <option value="">All Classes</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"
                                        <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                                        <?php echo e($class->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Admission Date From -->
                        <div class="col-md-2">
                            <label for="admission_date_from" class="form-label">Admission Date From</label>
                            <input type="date" name="admission_date_from" id="admission_date_from" class="form-control"
                                value="<?php echo e(request('admission_date_from')); ?>">
                        </div>

                        <!-- Admission Date To -->
                        <div class="col-md-2">
                            <label for="admission_date_to" class="form-label">Admission Date To</label>
                            <input type="date" name="admission_date_to" id="admission_date_to" class="form-control"
                                value="<?php echo e(request('admission_date_to')); ?>">
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search me-1"></i>Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Admissions Table -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">Admission Forms (<?php echo e($admissions->total()); ?>)</h6>
                <div>
                    <a href="<?php echo e(route('institution.admission.export', request()->query())); ?>"
                        class="btn btn-success btn-sm">
                        <i class="ti ti-file-excel me-1"></i>Export
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if($admissions->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Admission No</th>
                                    <th>Class</th>
                                    <th>Phone</th>
                                    <th>Admission Date</th>
                                    <th>Submitted Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $admissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if($admission->photo): ?>
                                                    <img src="<?php echo e(asset($admission->photo)); ?>" alt="Photo"
                                                        class="rounded-circle me-2"
                                                        style="width: 32px; height: 32px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="avatar avatar-sm bg-primary text-white rounded-circle me-2">
                                                        <?php echo e(strtoupper(substr($admission->first_name, 0, 1))); ?>

                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($admission->first_name); ?>

                                                        <?php echo e($admission->last_name); ?></h6>
                                                    <?php if($admission->email): ?>
                                                        <small class="text-muted"><?php echo e($admission->email); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold"><?php echo e($admission->admission_number ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($admission->schoolClass->name ?? 'N/A'); ?></span>
                                        </td>
                                        <td><?php echo e($admission->phone ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if($admission->admission_date): ?>
                                                <?php echo e(\Carbon\Carbon::parse($admission->admission_date)->format('d M, Y')); ?>

                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($admission->created_at->format('d M, Y')); ?>

                                            <br>
                                            <small class="text-muted"><?php echo e($admission->created_at->format('h:i A')); ?></small>
                                        </td>
                                        <td>
                                            <?php if($admission->status == 'approved'): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php elseif($admission->status == 'rejected'): ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo e(route('institution.admission.show', $admission->id)); ?>"
                                                    class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('institution.admission.print', $admission->id)); ?>"
                                                    target="_blank" class="btn btn-sm btn-outline-secondary"
                                                    title="Print Form">
                                                    <i class="ti ti-printer"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        <?php echo e($admissions->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="avatar avatar-lg bg-light text-muted rounded-circle mx-auto mb-3">
                            <i class="ti ti-file-text fs-24"></i>
                        </div>
                        <h5 class="text-muted">No Admissions Found</h5>
                        <p class="text-muted">
                            <?php if(request()->anyFilled(['search', 'class_id', 'admission_date_from', 'admission_date_to'])): ?>
                                Try adjusting your filters or
                            <?php endif; ?>
                            <a href="<?php echo e(route('institution.admission.admission-form')); ?>">submit a new admission form</a>.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Auto-submit form on filter change (optional)
            document.addEventListener('DOMContentLoaded', function() {
                // Optional: Auto-submit on filter change
                // const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input[type="date"]');
                // filterInputs.forEach(input => {
                //     input.addEventListener('change', function() {
                //         document.getElementById('filterForm').submit();
                //     });
                // });
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\envision\eschool\resources\views/institution/administration/students/admission/admission-list.blade.php ENDPATH**/ ?>