<?php $__env->startSection('title', 'Admin | Salary Structures'); ?>
<?php $__env->startSection('content'); ?>
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Salary Structures</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Salary Structures</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('admin.salary.structures.create')); ?>" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Add Structure
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.salary.structures.index')); ?>" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Institution</label>
                    <select name="institution_id" class="form-select">
                        <option value="">All Institutions</option>
                        <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($institution->id); ?>" <?php echo e(request('institution_id') == $institution->id ? 'selected' : ''); ?>>
                                <?php echo e($institution->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" <?php echo e(request('status') === '1' ? 'selected' : ''); ?>>Active</option>
                        <option value="0" <?php echo e(request('status') === '0' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by name..." value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="ti ti-filter"></i></button>
                    <a href="<?php echo e(route('admin.salary.structures.index')); ?>" class="btn btn-outline-secondary"><i class="ti ti-x"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Structures Table -->
    <div class="card">
        <div class="card-body p-0">
            <?php if($structures->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Institution</th>
                                <th>Name</th>
                                <th>Components</th>
                                <th>Earnings</th>
                                <th>Deductions</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $structures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $structure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($structures->firstItem() + $index); ?></td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary">
                                            <?php echo e($structure->institution->name ?? '-'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <strong><?php echo e($structure->name); ?></strong>
                                        <?php if($structure->description): ?>
                                            <br><small class="text-muted"><?php echo e(Str::limit($structure->description, 50)); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($structure->components->count()); ?></td>
                                    <td>
                                        <?php if($structure->earnings->count() > 0): ?>
                                            <span class="text-success"><?php echo e($structure->earnings->count()); ?> items</span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($structure->deductions->count() > 0): ?>
                                            <span class="text-danger"><?php echo e($structure->deductions->count()); ?> items</span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox" 
                                                data-id="<?php echo e($structure->id); ?>" 
                                                <?php echo e($structure->status ? 'checked' : ''); ?>>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('admin.salary.structures.edit', $structure->id)); ?>" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger delete-btn" 
                                                    data-id="<?php echo e($structure->id); ?>" title="Delete">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    <?php echo e($structures->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="ti ti-file-text fs-1 text-muted"></i>
                    <p class="mt-3 mb-0 text-muted">No salary structures found</p>
                    <a href="<?php echo e(route('admin.salary.structures.create')); ?>" class="btn btn-primary mt-3">
                        <i class="ti ti-plus me-1"></i> Create First Structure
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Status toggle
    $('.status-toggle').on('change', function() {
        const id = $(this).data('id');
        $.ajax({
            url: '<?php echo e(url("admin/salary/structures")); ?>/' + id + '/status',
            type: 'POST',
            data: { _token: '<?php echo e(csrf_token()); ?>' },
            success: function(response) {
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Failed to update status');
                $(this).prop('checked', !$(this).prop('checked'));
            }
        });
    });

    // Delete
    $('.delete-btn').on('click', function() {
        if (confirm('Are you sure you want to delete this salary structure?')) {
            const id = $(this).data('id');
            $('#deleteForm').attr('action', '<?php echo e(url("admin/salary/structures")); ?>/' + id + '/delete').submit();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/admin/salary/structures/index.blade.php ENDPATH**/ ?>