<?php $__env->startSection('title', 'Admin | Event Management'); ?>
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
                <h5 class="fw-bold">Event Management</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('institution.dashboard')); ?>"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Event Management</li>
                    </ol>
                </nav>
            </div>
        </div>  
        <!-- End Page Header -->

        <div class="row">
            <!-- Left Side - Add Event Form -->
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Add Event</h6>
                    </div>
                    <div class="card-body">
                        <form id="event-form" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" id="event-id">
                            
                            <div class="mb-3">
                                <label class="form-label">EVENT TITLE <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Enter event title" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" name="role" required>
                                    <option value="">Select</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="student">Students</option>
                                    <option value="nonworkingstaff">Non Working Staff</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" name="category" required>
                                    <option value="">Select</option>
                                    <option value="Exam">Exam</option>
                                    <option value="Holiday">Holiday</option>
                                    <option value="Meeting">Meeting</option>
                                    <option value="Event">Event</option>
                                    <option value="Deadline">Deadline</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Institution</label>
                                <select class="form-select" name="institution_id" readonly disabled>
                                    <option value="<?php echo e($currentInstitution->id); ?>" selected><?php echo e($currentInstitution->name); ?></option>
                                </select>
                                <input type="hidden" name="institution_id" value="<?php echo e($currentInstitution->id); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">EVENT LOCATION <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control" placeholder="Enter event location" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">FROM DATE <span class="text-danger">*</span></label>
                                        <input type="text" name="start_date" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">START TIME</label>
                                        <input type="time" name="start_time" data-provider="timepickr" data-time-basic="true" class="form-control" placeholder="HH:MM">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">DESCRIPTION <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Enter event description" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">URL</label>
                                <input type="url" name="url" class="form-control" placeholder="Enter event URL (optional)">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">File</label>
                                <div class="input-group">
                                    <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.gif">
                                    <button class="btn btn-outline-secondary" type="button">BROWSE</button>
                                </div>
                                <small class="text-muted">(JPG, JPEG, PNG, GIF are allowed for upload)</small>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="status" value="0">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" id="event-status" checked>
                                    <label class="form-check-label" for="event-status">
                                        Active
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100" id="submit-btn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <span class="btn-text">
                                        <i class="ti ti-check me-1"></i>SAVE
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side - Event List -->
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Event List</h6>
                    </div>
                    <div class="card-body">
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
                                                    <h6 class="fw-bold mb-0">Filter</h6>
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
                                                            <label class="form-label">Category</label>
                                                            <a href="javascript:void(0);" class="link-primary mb-1">Reset</a>
                                                        </div>
                                                        <div class="dropdown">
                                                            <a href="javascript:void(0);"
                                                                class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                                data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="true">
                                                                Select
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu w-100">
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                                        Exam
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                                        Holiday
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                                        Meeting
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                                        Event
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                                        Deadline
                                                                    </label>
                                                                </li>
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
                                                                data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                aria-expanded="true">
                                                                Select
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu w-100">
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                                        Active
                                                                    </label>
                                                                </li>
                                                                <li>
                                                                    <label
                                                                        class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                        <input class="form-check-input m-0 me-2" type="checkbox">
                                                                        Inactive
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

                        <div class="table-responsive">
                            <table class="table table-nowrap datatable" id="event-table">
                                <thead class="thead-ight">
                                    <tr>
                                        <th>Title</th>
                                        <th>Role</th>
                                        <th>Category</th>
                                        <th>Institution</th>
                                        <th>Location</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(isset($events) && !empty($events)): ?>
                                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm avatar-rounded bg-light">
                                                    <i class="ti ti-calendar-event text-primary"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <h6 class="fs-14 mb-0"><a href="javascript:void(0);"><?php echo e($event->title); ?></a></h6>
                                                    <?php if($event->description): ?>
                                                        <small class="text-muted"><?php echo e(Str::limit($event->description, 50)); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo e(ucfirst($event->role ?? 'N/A')); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: <?php echo e($event->color); ?>; color: white;">
                                                <?php echo e($event->category); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?php echo e($event->institution_name ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?php echo e($event->location ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <?php echo e(\Carbon\Carbon::parse($event->start_date)->format('d M, Y')); ?>

                                            <?php if($event->start_time): ?>
                                                <br><small class="text-muted"><?php echo e($event->start_time); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div>
                                                <select class="select status-toggle" data-id="<?php echo e($event->id); ?>">
                                                    <option value="1" <?php echo e($event->status === 1 ? 'selected' : ''); ?>>Active</option>
                                                    <option value="0" <?php echo e($event->status === 0 ? 'selected' : ''); ?>>Inactive</option>
                                                </select>
                                            </div>
                                        </td>
                                                                <td>
                            <div class="d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 edit-event-btn" data-id="<?php echo e($event->id); ?>">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 delete-event-btn" data-id="<?php echo e($event->id); ?>" data-title="<?php echo e($event->title); ?>">
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
        </div>

</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete_modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Delete Event</h6>
                <p>Are you sure you want to delete this event?</p>
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

<!-- End Content -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/institution/events.js')); ?>"></script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/academic/events/index.blade.php ENDPATH**/ ?>