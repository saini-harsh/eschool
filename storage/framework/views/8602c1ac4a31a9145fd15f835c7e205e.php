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
                                <a href="javascript:void(0);" class="link-danger text-decoration-underline">Clear
                                    All</a>
                            </div>
                        </div>
                    </div>
                    <form action="">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label">Institution</label>
                                    <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                </div>
                                <div class="dropdown">
                                    <a href="javascript:void(0);"
                                        class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                                        Select
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu w-100">
                                        <?php if(isset($institutions) && !empty($institutions)): ?>
                                            <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li>
                                                    <label
                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                        <?php echo e($institution->name); ?>

                                                    </label>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label">Status</label>
                                    <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                </div>
                                <div class="dropdown">
                                    <a href="javascript:void(0);"
                                        class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                                        Select
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu w-100">
                                        <li>
                                            <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                <input class="form-check-input m-0 me-2" type="checkbox">
                                                Active
                                            </label>
                                        </li>
                                        <li>
                                            <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                <input class="form-check-input m-0 me-2" type="checkbox">
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
    </div>
</div>
<?php /**PATH E:\eschool\resources\views/components/dashboard/filters.blade.php ENDPATH**/ ?>