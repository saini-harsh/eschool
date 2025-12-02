<?php $__env->startSection('title', 'Admin | Institutions Management'); ?>
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
                <h5 class="fw-bold">Institutions</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('admin.institutions.create')); ?>"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Institutions</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?php echo e(route('admin.institutions.create')); ?>" class="btn btn-primary"><i class="ti ti-circle-plus me-1"></i>New Institution</a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="datatable-search">
                <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown me-2">
                    <a href="javascript:void(0);" class="btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <i class="ti ti-filter me-1"></i>Filter
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0" id="filter-dropdown">
                        <div class="card mb-0">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0">Filter</h6>
                                    <div class="d-flex align-items-center">
                                        <a href="<?php echo e(route('admin.institutions.index')); ?>" class="link-danger text-decoration-underline">Clear
                                            All</a>
                                    </div>
                                </div>
                            </div>
                            <form action="<?php echo e(route('admin.institutions.index')); ?>" method="GET">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Name</label>
                                            <a href="<?php echo e(route('admin.institutions.index')); ?>" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <select name="name" id="filter-name" class="form-select">
                                            <option value="">Select</option>
                                            <?php if(isset($allInstitutionNames) && $allInstitutionNames->count()): ?>
                                                <?php $__currentLoopData = $allInstitutionNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($n); ?>" <?php echo e(request('name') == $n ? 'selected' : ''); ?>><?php echo e($n); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Email</label>
                                            <a href="<?php echo e(route('admin.institutions.index')); ?>" class="link-primary mb-1">Reset</a>
                                        </div>
                                        <input type="text" name="email" id="filter-email" class="form-control" placeholder="Institution email" value="<?php echo e(request('email')); ?>">
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
                        <th>Status</th>
                        <th class="no-sort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($institutions) && !empty($institutions)): ?>
                        <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="company-details.html"
                                            class="avatar avatar-sm rounded-circle bg-light border">
                                            <img src="<?php echo e(asset($institution->logo)); ?>"
                                                class="w-auto h-auto" alt="img">
                                        </a>
                                        <div class="ms-2">
                                            <h6 class="fs-14 mb-0"><a
                                                    href="company-details.html"><?php echo e($institution->name); ?></a></h6>
                                        </div>
                                    </div>
                                </td>
                                <td><a href="https://dleohr.dreamstechnologies.com/cdn-cgi/l/email-protection"
                                        class="__cf_email__"
                                        data-cfemail="43202c2d37222037032d263b22202c31266d202c2e"><?php echo e($institution->email); ?></a>
                                </td>
                                <td><?php echo e($institution->phone); ?></td>
                                <td>
                                    <div>
                                        <select class="select">
                                        <option value="1" <?php echo e($institution->status === 1 ? 'selected' : ''); ?>>Active</option>
                                        <option value="0" <?php echo e($institution->status === 0 ? 'selected' : ''); ?>>Inactive</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-inline-flex align-items-center">
                                        <a href="<?php echo e(route('admin.institutions.edit', $institution->id)); ?>"
                                            class="btn btn-icon btn-sm btn-outline-white border-0"><i
                                                class="ti ti-edit"></i></a>
                                        <a href="javascript:void(0);" onclick="confirmDelete(`<?php echo e(route('admin.students.delete', $institution->id)); ?>`)"
                                            class="btn btn-icon btn-sm btn-outline-white border-0" data-bs-toggle="modal"
                                            data-bs-target="#delete_modal"><i class="ti ti-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
    <!-- End Content -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/admin/institutions.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/admin/administration/institutions/index.blade.php ENDPATH**/ ?>