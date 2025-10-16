<?php $__env->startSection('title', 'Payment History'); ?>
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
            <h5 class="fw-bold">Payment History</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Payment History</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('institution.fee-structure.index')); ?>" class="btn btn-primary">
                <i class="ti ti-circle-plus me-1"></i>Record New Payment
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Search and Filter Section -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="datatable-search">
            <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
        </div>
        <div class="d-flex align-items-center">
            <div class="d-flex align-items-center border rounded table-grid me-2">
                <a href="javascript:void(0);" class="btn p-1 btn-primary"><i class="ti ti-list"></i></a>
                <a href="javascript:void(0);" class="btn p-1"><i class="ti ti-layout-grid"></i></a>
            </div>
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
                                <h6 class="fw-bold mb-0">Filter Payments</h6>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);"
                                        class="link-danger text-decoration-underline">Clear All</a>
                                </div>
                            </div>
                        </div>
                        <form action="#">
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label">Payment Method</label>
                                        <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);"
                                            class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                            data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                            aria-expanded="true">
                                            Select Method
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu w-100">
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="payment_method[]" value="cash">
                                                    Cash
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="payment_method[]" value="online">
                                                    Online
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="payment_method[]" value="bank_transfer">
                                                    Bank Transfer
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="payment_method[]" value="cheque">
                                                    Cheque
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label">Student Name</label>
                                        <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" name="student_name" placeholder="Search by student name...">
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label">Class & Section</label>
                                        <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);"
                                            class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                            data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                            aria-expanded="true">
                                            Select Class & Section
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu w-100">
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="class_section[]" value="class_1_a">
                                                    Class 1 - Section A
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="class_section[]" value="class_1_b">
                                                    Class 1 - Section B
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="class_section[]" value="class_2_a">
                                                    Class 2 - Section A
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="class_section[]" value="class_2_b">
                                                    Class 2 - Section B
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="class_section[]" value="class_3_a">
                                                    Class 3 - Section A
                                                </label>
                                            </li>
                                            <li>
                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="class_section[]" value="class_3_b">
                                                    Class 3 - Section B
                                                </label>
                                            </li>
                                        </ul>
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
                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Amount (High to Low)</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Amount (Low to High)</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7 Days</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="row">
        <div class="col-12">
            <?php if($payments->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-nowrap datatable">
                        <thead class="thead-ight">
                            <tr>
                                <th>Student</th>
                                <th>Receipt No.</th>
                                <th>Fee Structure</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Payment Date</th>
                                <th>Status</th>
                                <th class="no-sort">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0">
                                                    <a href="<?php echo e(route('institution.payments.show', $payment->id)); ?>">
                                                        <?php echo e($payment->student->first_name); ?> <?php echo e($payment->student->last_name); ?>

                                                    </a>
                                                </h6>
                                                <small class="text-muted"><?php echo e($payment->student->admission_number ?: 'N/A'); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0">
                                                    <a href="<?php echo e(route('institution.payments.show', $payment->id)); ?>" class="text-primary">
                                                        <?php echo e($payment->receipt_number); ?>

                                                    </a>
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0"><?php echo e($payment->feeStructure->name); ?></h6>
                                                <small class="text-muted"><?php echo e($payment->feeStructure->schoolClass->name ?? 'N/A'); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0 text-success fw-bold">₹<?php echo e(number_format($payment->amount, 2)); ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <span class="badge bg-<?php echo e($payment->payment_method == 'cash' ? 'success' : ($payment->payment_method == 'online' ? 'primary' : ($payment->payment_method == 'bank_transfer' ? 'info' : 'secondary'))); ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $payment->payment_method))); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="fs-14 mb-0"><?php echo e($payment->payment_date->format('d M Y')); ?></h6>
                                                <small class="text-muted"><?php echo e($payment->payment_date->format('h:i A')); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <span class="badge bg-success">Completed</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-inline-flex align-items-center">
                                            <a href="<?php echo e(route('institution.payments.show', $payment->id)); ?>" 
                                               class="btn btn-icon btn-sm btn-outline-white border-0" title="View Receipt">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('institution.payments.show', $payment->id)); ?>" 
                                               class="btn btn-icon btn-sm btn-outline-white border-0" title="Download Receipt" 
                                               onclick="window.open(this.href, '_blank'); return false;">
                                                <i class="ti ti-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($payments->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="ti ti-credit-card-off" style="font-size: 3rem; color: #ccc;"></i>
                    </div>
                    <h5 class="text-muted">No Payments Found</h5>
                    <p class="text-muted">No payment records have been created yet.</p>
                    <a href="<?php echo e(route('institution.fee-structure.index')); ?>" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>Record First Payment
                    </a>
                </div>
            <?php endif; ?>
          
        </div>
    </div>
    <!-- End Payment History -->

</div>
<!-- End Content -->

<script>
    setTimeout(() => {
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            const bsToast = bootstrap.Toast.getOrCreateInstance(toastEl);
            bsToast.hide();
        }
    }, 3000); // Hide after 3 seconds

    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Close filter dropdown
        document.getElementById('close-filter')?.addEventListener('click', function() {
            const dropdown = bootstrap.Dropdown.getInstance(document.querySelector('[data-bs-toggle="dropdown"]'));
            if (dropdown) {
                dropdown.hide();
            }
        });

        // Search functionality
        const searchInput = document.querySelector('.datatable-search .input-text');
        if (searchInput) {
            searchInput.addEventListener('click', function() {
                const searchBox = document.createElement('input');
                searchBox.type = 'text';
                searchBox.className = 'form-control form-control-sm';
                searchBox.placeholder = 'Search payments...';
                searchBox.style.width = '200px';
                
                this.parentNode.replaceChild(searchBox, this);
                searchBox.focus();
                
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

<?php $__env->startPush('styles'); ?>
<style>
    .avatar-initials {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }
    
    .table-nowrap td {
        white-space: nowrap;
    }
    
    .datatable-search .input-text {
        cursor: pointer;
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background: white;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .datatable-search .input-text:hover {
        border-color: #6366f1;
        color: #6366f1;
    }
    
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .btn-icon:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
    }
    
    .badge {
        font-size: 11px;
        padding: 4px 8px;
    }
    
    .fs-14 {
        font-size: 14px;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
        vertical-align: middle;
        padding: 12px 8px;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/institution/payment/payments/index.blade.php ENDPATH**/ ?>