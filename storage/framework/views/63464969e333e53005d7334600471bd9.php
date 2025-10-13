<?php $__env->startSection('title', 'Institution | Assignment Management'); ?>
<?php $__env->startSection('content'); ?>

<!-- Start Content -->
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Assignment Management</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="<?php echo e(route('institution.dashboard')); ?>"><i class="ti ti-home me-1"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Academic</li>
                    <li class="breadcrumb-item active" aria-current="page">Assignments</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="row">
        <!-- Left Side - Form -->
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Add New Assignment</h6>
                </div>
                <div class="card-body">
                    <form id="assignment-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="assignment_id" id="assignment-id">
                        
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter assignment title" autocomplete="off" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter assignment description (optional)"></textarea>
                        </div>
                        
                        <!-- Institution is auto-set from logged-in institution -->
                        <input type="hidden" name="institution_id" id="institution_id" value="<?php echo e($currentInstitution->id); ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Class <span class="text-danger">*</span></label>
                            <select name="class_id" id="class_id" class="form-select" required>
                                <option value="">Select Class</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Section <span class="text-danger">*</span></label>
                            <select name="section_id" id="section_id" class="form-select" required>
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subject_id" class="form-select" required>
                                <option value="">Select Subject</option>
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Teacher <span class="text-danger">*</span></label>
                            <select name="teacher_id" id="teacher_id" class="form-select" required>
                                <option value="">Select Teacher</option>
                                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="text" name="due_date" id="due_date" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" placeholder="dd/mm/yyyy" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Assignment File</label>
                            <input type="file" name="assignment_file" id="assignment_file" class="form-control" accept=".pdf,.doc,.docx">
                            <small class="text-muted">Supported formats: PDF, DOC, DOCX (Max: 10MB)</small>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status" value="1" id="assignment-status" checked>
                                <label class="form-check-label" for="assignment-status">Active</label>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill" id="submit-btn">
                                <i class="ti ti-plus me-1"></i>Add Assignment
                            </button>
                            <button type="button" class="btn btn-secondary" id="cancel-btn" style="display: none;">
                                <i class="ti ti-x me-1"></i>Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Assignment List -->
        <div class="col-9">
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-bold mb-0">Assignment List</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Subject</th>
                                    <th>Teacher</th>
                                    <th>Due Date</th>
                                    <th>Submissions</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="assignments-table-body">
                                <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr data-assignment-id="<?php echo e($assignment->id); ?>">
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold"><?php echo e($assignment->title); ?></span>
                                                <?php if($assignment->assignment_file): ?>
                                                    <small class="text-muted">
                                                        <i class="ti ti-file-text me-1"></i>
                                                        <a href="<?php echo e(asset($assignment->assignment_file)); ?>" target="_blank" class="text-decoration-none">View File</a>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?php echo e($assignment->schoolClass->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($assignment->section->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($assignment->subject->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($assignment->teacher->first_name ?? ''); ?> <?php echo e($assignment->teacher->last_name ?? ''); ?></td>
                                        <td>
                                            <span class="badge <?php echo e(\Carbon\Carbon::parse($assignment->due_date)->isPast() ? 'bg-danger' : 'bg-success'); ?>">
                                                <?php echo e(\Carbon\Carbon::parse($assignment->due_date)->format('M d, Y')); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" data-assignment-id="<?php echo e($assignment->id); ?>"
                                                class="btn btn-icon btn-sm btn-outline-primary border-0 view-submissions"
                                                title="View Submissions (<?php echo e($assignment->studentAssignments->count()); ?>)">
                                                <i class="ti ti-users"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input status-toggle" 
                                                       data-assignment-id="<?php echo e($assignment->id); ?>" 
                                                       <?php echo e($assignment->status ? 'checked' : ''); ?>>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-icon btn-sm btn-outline-primary border-0 edit-assignment" 
                                                        data-assignment-id="<?php echo e($assignment->id); ?>" title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button class="btn btn-icon btn-sm btn-outline-danger border-0 delete-assignment" 
                                                        data-assignment-id="<?php echo e($assignment->id); ?>" title="Delete">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ti ti-clipboard-list fs-48 text-muted mb-2"></i>
                                                <span class="text-muted">No assignments found</span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Content -->

<!-- Submissions Modal -->
<div class="modal fade" id="submissions_modal" tabindex="-1" aria-labelledby="submissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="submissionsModalLabel">Student Submissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="submissions-content">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grade Modal -->
<div class="modal fade" id="grade_modal" tabindex="-1" aria-labelledby="gradeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradeModalLabel">Grade Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="grade-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="grade-student-assignment-id">
                    <input type="hidden" id="grade-assignment-id">

                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <input type="text" id="grade-student-name" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Marks <span class="text-danger">*</span></label>
                        <input type="number" id="grade-marks" class="form-control" min="0" max="100" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Feedback</label>
                        <textarea id="grade-feedback" class="form-control" rows="3" placeholder="Add feedback for the student"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submit-grade">Submit Grade</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('custom/js/institution/assignments.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/institution/academic/assignments/index.blade.php ENDPATH**/ ?>