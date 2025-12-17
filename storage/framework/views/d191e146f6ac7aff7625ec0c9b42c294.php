<?php $__env->startSection('title', 'Salary Structures'); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Salary Structures</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('institution.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Salary Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Salary Structures</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('institution.salary.structures.create')); ?>" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Add Salary Structure
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <?php if($structures->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Components</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $structures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $structure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($structures->firstItem() + $index); ?></td>
                                        <td>
                                            <strong><?php echo e($structure->name); ?></strong>
                                        </td>
                                        <td><?php echo e(Str::limit($structure->description, 50) ?? '-'); ?></td>
                                        <td>
                                            <span class="badge bg-success me-1"><?php echo e($structure->earnings->count()); ?> Earnings</span>
                                            <span class="badge bg-danger"><?php echo e($structure->deductions->count()); ?> Deductions</span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" type="checkbox" 
                                                    data-id="<?php echo e($structure->id); ?>" 
                                                    <?php echo e($structure->status ? 'checked' : ''); ?>>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-info view-components" 
                                                    data-bs-toggle="modal" data-bs-target="#viewModal"
                                                    data-structure='<?php echo json_encode($structure->components, 15, 512) ?>' 
                                                    data-name="<?php echo e($structure->name); ?>">
                                                    <i class="ti ti-eye"></i>
                                                </button>
                                                <a href="<?php echo e(route('institution.salary.structures.edit', $structure->id)); ?>" 
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-id="<?php echo e($structure->id); ?>" data-name="<?php echo e($structure->name); ?>">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <?php echo e($structures->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="ti ti-receipt-2 fs-1 text-muted"></i>
                        <h5 class="mt-3">No Salary Structures Found</h5>
                        <p class="text-muted">Create your first salary structure to define earnings and deductions.</p>
                        <a href="<?php echo e(route('institution.salary.structures.create')); ?>" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Create Salary Structure
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- View Components Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span id="structureName"></span> - Components</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success mb-3"><i class="ti ti-plus me-1"></i>Earnings</h6>
                            <div id="earningsList"></div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-danger mb-3"><i class="ti ti-minus me-1"></i>Deductions</h6>
                            <div id="deductionsList"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Salary Structure</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete "<strong id="deleteName"></strong>"?</p>
                    <p class="text-muted small">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    let deleteId = null;

    // View components
    $('.view-components').on('click', function() {
        const components = $(this).data('structure');
        const name = $(this).data('name');
        
        $('#structureName').text(name);
        
        let earningsHtml = '';
        let deductionsHtml = '';
        
        components.forEach(comp => {
            const amountText = comp.is_percentage ? `${comp.amount}%` : `₹${parseFloat(comp.amount).toLocaleString()}`;
            const html = `
                <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                    <span>${comp.name}</span>
                    <span class="badge ${comp.type === 'earning' ? 'bg-success' : 'bg-danger'}">${amountText}</span>
                </div>
            `;
            
            if (comp.type === 'earning') {
                earningsHtml += html;
            } else {
                deductionsHtml += html;
            }
        });
        
        $('#earningsList').html(earningsHtml || '<p class="text-muted">No earnings defined</p>');
        $('#deductionsList').html(deductionsHtml || '<p class="text-muted">No deductions defined</p>');
    });

    // Status toggle
    $('.status-toggle').on('change', function() {
        const id = $(this).data('id');
        const checkbox = $(this);
        
        $.ajax({
            url: `/institution/salary/structures/${id}/status`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                }
            },
            error: function() {
                checkbox.prop('checked', !checkbox.prop('checked'));
                toastr.error('Failed to update status');
            }
        });
    });

    // Delete button
    $('.delete-btn').on('click', function() {
        deleteId = $(this).data('id');
        $('#deleteName').text($(this).data('name'));
        $('#deleteModal').modal('show');
    });

    // Confirm delete
    $('#confirmDelete').on('click', function() {
        if (!deleteId) return;
        
        $.ajax({
            url: `/institution/salary/structures/${deleteId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Failed to delete');
            }
        });
        
        $('#deleteModal').modal('hide');
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\envision\eschool\resources\views/institution/salary/structures/index.blade.php ENDPATH**/ ?>