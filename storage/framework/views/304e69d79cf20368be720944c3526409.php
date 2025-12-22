<?php $__env->startSection('title', 'Institution | Boarding Management'); ?>
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

    <?php if(session('error')): ?>
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo e(session('error')); ?>

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
                <h5 class="fw-bold">Boarding Students</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="<?php echo e(route('institution.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Boarding</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="<?php echo e(route('institution.boarding.create')); ?>" class="btn btn-primary"><i
                        class="ti ti-circle-plus me-1"></i>Add Student to Boarding</a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('institution.boarding.index')); ?>" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search"
                                value="<?php echo e(request('search')); ?>" placeholder="Search by name, admission number...">
                        </div>
                        <div class="col-md-3">
                            <label for="class_id" class="form-label">Class</label>
                            <select class="form-select" id="class_id" name="class_id">
                                <option value="">All Classes</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"
                                        <?php echo e(request('class_id') == $class->id ? 'selected' : ''); ?>>
                                        <?php echo e($class->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="section_id" class="form-label">Section</label>
                            <select class="form-select" id="section_id" name="section_id">
                                <option value="">All Sections</option>
                                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($section->id); ?>"
                                        <?php echo e(request('section_id') == $section->id ? 'selected' : ''); ?>>
                                        <?php echo e($section->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i
                                    class="ti ti-search me-1"></i>Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Boarding Students Table -->
        <div class="card">
            <div class="card-body">
                <?php if($boardingStudents->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-nowrap">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Admission No.</th>
                                    <th>Roll No.</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Added Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $boardingStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $boarding): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php if($boarding->student->photo): ?>
                                                <img src="<?php echo e(asset($boarding->student->photo)); ?>" alt="Photo"
                                                    class="avatar avatar-sm rounded-circle">
                                            <?php else: ?>
                                                <div class="avatar avatar-sm bg-primary text-white rounded-circle">
                                                    <?php echo e(strtoupper(substr($boarding->student->first_name, 0, 1))); ?>

                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo e($boarding->student->first_name); ?>

                                                <?php echo e($boarding->student->middle_name); ?>

                                                <?php echo e($boarding->student->last_name); ?></strong>
                                        </td>
                                        <td><?php echo e($boarding->student->admission_number ?? 'N/A'); ?></td>
                                        <td><?php echo e($boarding->student->roll_number ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if($boarding->student->schoolClass): ?>
                                                <span
                                                    class="badge badge-soft-primary"><?php echo e($boarding->student->schoolClass->name); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($boarding->student->section): ?>
                                                <span
                                                    class="badge badge-soft-info"><?php echo e($boarding->student->section->name); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($boarding->student->email ?? 'N/A'); ?></td>
                                        <td><?php echo e($boarding->student->phone ?? 'N/A'); ?></td>
                                        <td><?php echo e($boarding->created_at->format('d M Y')); ?></td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="<?php echo e(route('institution.students.show', $boarding->student->id)); ?>"
                                                    class="btn btn-sm btn-outline-info" title="View Student">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('institution.boarding.edit', $boarding->id)); ?>"
                                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('institution.boarding.delete', $boarding->id)); ?>"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to remove this student from boarding?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('POST'); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Remove from Boarding">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        <?php echo e($boardingStudents->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="avatar avatar-lg bg-light text-muted rounded-circle mx-auto mb-3">
                            <i class="ti ti-home fs-48"></i>
                        </div>
                        <h5 class="text-muted">No Boarding Students</h5>
                        <p class="text-muted">No students have been added to boarding yet.</p>
                        <a href="<?php echo e(route('institution.boarding.create')); ?>" class="btn btn-primary">
                            <i class="ti ti-circle-plus me-1"></i>Add Student to Boarding
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <!-- End Content -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Auto-hide toast after 3 seconds
        setTimeout(function() {
            var toastEl = document.querySelector('.toast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl);
                toast.hide();
            }
        }, 3000);

        // Load sections when class changes
        document.getElementById('class_id')?.addEventListener('change', function() {
            const classId = this.value;
            const sectionSelect = document.getElementById('section_id');

            if (classId) {
                fetch(`<?php echo e(url('institution/sections/list')); ?>?class_id=${classId}`)
                    .then(response => response.json())
                    .then(data => {
                        sectionSelect.innerHTML = '<option value="">All Sections</option>';
                        if (data.sections) {
                            data.sections.forEach(section => {
                                const option = document.createElement('option');
                                option.value = section.id;
                                option.textContent = section.name;
                                sectionSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                sectionSelect.innerHTML = '<option value="">All Sections</option>';
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\envision\eschool\resources\views/institution/administration/boarding/index.blade.php ENDPATH**/ ?>