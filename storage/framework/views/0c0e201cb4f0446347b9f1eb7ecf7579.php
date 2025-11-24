<?php $__env->startSection('title', 'Admin | Students Management'); ?>
<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo e(session('success')); ?>

                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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
                        <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('admin.students.index')); ?>"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Students</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?php echo e(route('admin.students.create')); ?>" class="btn btn-primary"><i class="ti ti-circle-plus me-1"></i>New Student</a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="datatable-search">
                <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown">
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
                                        <a href="<?php echo e(route('admin.students.index')); ?>"
                                            class="link-danger text-decoration-underline">Clear All</a>
                                    </div>
                                </div>
                            </div>
                            <form action="<?php echo e(route('admin.students.index')); ?>" method="GET">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Name</label>
                                            <a href="<?php echo e(route('admin.students.index')); ?>" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <select name="name" id="filter-name" class="form-select">
                                            <option value="">Select</option>
                                            <?php if(isset($allStudentNames) && $allStudentNames->count()): ?>
                                                <?php $__currentLoopData = $allStudentNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($n); ?>" <?php echo e(request('name') == $n ? 'selected' : ''); ?>><?php echo e($n); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Institution</label>
                                            <a href="<?php echo e(route('admin.students.index')); ?>" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <select name="institution_id" id="filter-institution" class="form-select">
                                            <option value="">Select</option>
                                            <?php if(isset($institutions) && $institutions->count()): ?>
                                                <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inst): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($inst->id); ?>" <?php echo e(request('institution_id') == $inst->id ? 'selected' : ''); ?>><?php echo e($inst->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Teacher</label>
                                            <a href="<?php echo e(route('admin.students.index')); ?>" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <select name="teacher_id" id="filter-teacher" class="form-select">
                                            <option value="">Select</option>
                                            <?php if(isset($teachers) && $teachers->count()): ?>
                                                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($t->id); ?>" <?php echo e(request('teacher_id') == $t->id ? 'selected' : ''); ?>><?php echo e($t->first_name); ?> <?php echo e($t->last_name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Email</label>
                                            <a href="<?php echo e(route('admin.students.index')); ?>" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <input type="text" name="email" id="filter-email" class="form-control" placeholder="Student email" value="<?php echo e(request('email')); ?>">
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
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-nowrap datatable">
                <thead class="thead-ight">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Insitution</th>
                        <th>Teacher</th>
                        <th>Status</th>
                        <th class="no-sort">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(isset($students) && !empty($students)): ?>
                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <a href="<?php echo e(route('admin.students.show', $student->id)); ?>" class="avatar avatar-sm avatar-rounded">
                                    <img src="<?php echo e(asset($student->photo)); ?>" alt="img">
                                </a>
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0"><a href="<?php echo e(route('admin.students.show', $student->id)); ?>"><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></a></h6>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ms-2">
                                    <h6 class="fs-14 mb-0"><a href="javascript:void(0);"><?php echo e($student->email); ?></a></h6>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($student->phone); ?></td>
                        
                        <td>
                        <!-- <a href="<?php echo e(URL::to('/admin/agents/view/'.base64_encode(convert_uuencode(@$agentdetail->id)))); ?>"> -->
                            <span class="badge badge-soft-secondary">
                                <?php if($student->institution): ?>
                                    <?php echo e($student->institution->name); ?>

                                <?php else: ?>
                                    <?php echo e($student->institution_code); ?>

                                <?php endif; ?>
                            </span>
                        <!-- </a>     -->

                        </td>
                        <td>
                        <!-- <a href="<?php echo e(URL::to('/admin/agents/view/'.base64_encode(convert_uuencode(@$agentdetail->id)))); ?>"> -->
                            <span class="badge badge-soft-orange">
                                <?php if($student->teacher): ?>
                                    <?php echo e($student->teacher->first_name); ?> <?php echo e($student->teacher->last_name); ?>

                                <?php else: ?>
                                    Not Assigned
                                <?php endif; ?>
                            </span>
                        <!-- </a>     -->

                        </td>
                        <td>
                            <div>
                                <select class="form-select form-select-sm status-select" data-student-id="<?php echo e($student->id); ?>">
                                    <option value="1" <?php echo e($student->status == 1 ? 'selected' : ''); ?>>Active</option>
                                    <option value="0" <?php echo e($student->status == 0 ? 'selected' : ''); ?>>Inactive</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center">
                                <a href="<?php echo e(route('admin.students.show', $student->id)); ?>" class="btn btn-icon btn-sm btn-outline-white border-0" title="View Details">
                                    <i class="ti ti-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.students.edit', $student->id)); ?>" class="btn btn-icon btn-sm btn-outline-white border-0" title="Edit">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 delete-student" 
                                   data-delete-url="<?php echo e(route('admin.students.delete', $student->id)); ?>"
                                   data-student-name="<?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>"
                                   title="Delete">
                                    <i class="ti ti-trash"></i>
                                </a>
                                <a href="<?php echo e(route('admin.students.download-pdf', $student->id)); ?>" class="btn btn-icon btn-sm btn-outline-success border-0" title="Download PDF">
                                    <i class="ti ti-file-type-pdf"></i>
                                </a>
                                <a href="<?php echo e(route('admin.students.print-id-card', $student->id)); ?>" target="_blank" class="btn btn-icon btn-sm btn-outline-primary border-0" title="Print ID Card">
                                    <i class="ti ti-printer"></i>
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



<script>
    // Auto-hide existing toast notifications
    setTimeout(() => {
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            const bsToast = bootstrap.Toast.getOrCreateInstance(toastEl);
            bsToast.hide();
        }
    }, 3000); // Hide after 3 seconds
</script>
<!-- End Content -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/admin/students.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/admin/administration/students/index.blade.php ENDPATH**/ ?>