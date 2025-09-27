<?php $__env->startSection('title', 'Admin | Exam Management | Exams'); ?>
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

    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Exams</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('admin.dashboard')); ?>"><i
                                    class="ti ti-home me-1"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Exams</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card mb-4">
            <div class="card-body">
                <h6 class="card-title mb-3">Filter Exam Records</h6>
                <form id="exam-filter-form" class="row g-3 align-items-end" method="GET"
                    action="<?php echo e(route('admin.exam-management.exams')); ?>">
                    <!-- Institution Dropdown -->
                    <div class="col-md-2">
                        <label for="institution" class="form-label">Institution</label>
                        <select class="form-select" id="institution" name="institution">
                            <option value="">Select Institution</option>
                            <?php if(isset($institutions) && count($institutions) > 0): ?>
                                <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($institution->id); ?>"><?php echo e($institution->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <option value="">No institutions found</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Class Dropdown (for students) -->
                    <div class="col-md-2" id="class-field">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class">
                            <option value="">Select Class</option>
                        </select>
                    </div>

                    <!-- Section Dropdown (for students) -->
                    <div class="col-md-2" id="section-field">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" id="section" name="section">
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('admin.exam-management.exams')); ?>" class="btn btn-outline-secondary w-100">
                            <i class="ti ti-x me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Exam Timetable Cards -->
        <div class="row">
            <?php if(isset($lists) && !empty($lists)): ?>
                <?php
                    $groupedExams = $lists->groupBy(function ($exam) {
                        return $exam->class ? $exam->class->name : 'Unknown Class';
                    });
                ?>

                <?php $__currentLoopData = $groupedExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className => $exams): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header class-header text-white">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">
                                        <i class="ti ti-school me-2"></i>
                                        <?php echo e($className); ?> - Exam Timetable
                                    </h5>
                                    <span class="badge bg-light text-dark"><?php echo e(count($exams)); ?> Exam(s)</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="timetable-grid">
                                    <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="exam-timetable-card card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="exam-card-header">
                                                    <div class="d-flex align-items-start justify-content-between">
                                                        <div>
                                                            <h6 class="card-title text-primary mb-1 fw-bold">
                                                                <?php echo e($exam->title); ?></h6>
                                                            <small class="text-muted fw-medium">Code:
                                                                <?php echo e($exam->code); ?></small>
                                                        </div>
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item" href="#"
                                                                        title="View Details"><i
                                                                            class="ti ti-eye me-2"></i>View</a></li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        title="Edit"><i
                                                                            class="ti ti-edit me-2"></i>Edit</a></li>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                                <li><a class="dropdown-item text-danger delete-exam"
                                                                        href="javascript:void(0);" data-delete-url="#"
                                                                        data-exam-title="<?php echo e($exam->title); ?>"><i
                                                                            class="ti ti-trash me-2"></i>Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="exam-meta-info mb-3">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="ti ti-building"></i>
                                                        <span class="ms-2">
                                                            <?php if($exam->institution): ?>
                                                                <?php echo e($exam->institution->name); ?>

                                                            <?php else: ?>
                                                                Institution ID: <?php echo e($exam->institution_id); ?>

                                                            <?php endif; ?>
                                                        </span>
                                                    </div>

                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="ti ti-clipboard-list"></i>
                                                        <span class="ms-2">
                                                            <?php if($exam->examType): ?>
                                                                <?php echo e($exam->examType->title); ?>

                                                            <?php else: ?>
                                                                Type ID: <?php echo e($exam->exam_type_id); ?>

                                                            <?php endif; ?>
                                                        </span>
                                                    </div>

                                                    <?php if($exam->section): ?>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="ti ti-users"></i>
                                                            <span class="ms-2">Section:
                                                                <?php echo e($exam->section->name); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <div class="exam-date-badge text-center">
                                                            <small class="d-block opacity-75">Start Date</small>
                                                            <strong><?php echo e($exam->start_date ? \Carbon\Carbon::parse($exam->start_date)->format('M d') : 'N/A'); ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="exam-date-badge text-center">
                                                            <small class="d-block opacity-75">End Date</small>
                                                            <strong><?php echo e($exam->end_date ? \Carbon\Carbon::parse($exam->end_date)->format('M d') : 'N/A'); ?></strong>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if($exam->morning_time || $exam->evening_time): ?>
                                                    <div class="exam-time-info">
                                                        <div class="row g-2">
                                                            <?php if($exam->morning_time): ?>
                                                                <div class="col-6">
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="ti ti-sun text-warning"></i>
                                                                        <span class="ms-2 fw-medium">Morning:
                                                                            <?php echo e($exam->morning_time); ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if($exam->evening_time): ?>
                                                                <div class="col-6">
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="ti ti-moon text-info"></i>
                                                                        <span class="ms-2 fw-medium">Evening:
                                                                            <?php echo e($exam->evening_time); ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Subject Schedule Section -->
                                                <?php if($exam->subject_dates || $exam->morning_subjects || $exam->evening_subjects): ?>
                                                    <div class="mt-3">
                                                        <h6 class="text-primary mb-2">
                                                            <i class="ti ti-calendar-event me-1"></i>
                                                            Subject Schedule
                                                        </h6>

                                                        <?php
                                                            $subjectDates = $exam->subject_dates
                                                                ? json_decode($exam->subject_dates, true)
                                                                : [];
                                                            $morningSubjects = $exam->morning_subjects
                                                                ? json_decode($exam->morning_subjects, true)
                                                                : [];
                                                            $eveningSubjects = $exam->evening_subjects
                                                                ? json_decode($exam->evening_subjects, true)
                                                                : [];

                                                            // Helper function to get subject names from IDs
                                                            function getSubjectNames($subjectIds)
                                                            {
                                                                if (empty($subjectIds)) {
                                                                    return [];
                                                                }

                                                                $subjects = \App\Models\Subject::whereIn(
                                                                    'id',
                                                                    $subjectIds,
                                                                )->get();
                                                                return $subjects->pluck('name')->toArray();
                                                            }

                                                            // Create date-subject mapping
                                                            $dateSubjectMapping = [];
                                                            if (!empty($subjectDates) && !empty($morningSubjects)) {
                                                                foreach ($subjectDates as $index => $date) {
                                                                    $morningSubjectId = isset($morningSubjects[$index])
                                                                        ? $morningSubjects[$index]
                                                                        : null;
                                                                    $eveningSubjectId = isset($eveningSubjects[$index])
                                                                        ? $eveningSubjects[$index]
                                                                        : null;

                                                                    $morningSubjectName = $morningSubjectId
                                                                        ? getSubjectNames([$morningSubjectId])[0] ??
                                                                            null
                                                                        : null;
                                                                    $eveningSubjectName = $eveningSubjectId
                                                                        ? getSubjectNames([$eveningSubjectId])[0] ??
                                                                            null
                                                                        : null;

                                                                    $dateSubjectMapping[$date] = [
                                                                        'morning' => $morningSubjectName,
                                                                        'evening' => $eveningSubjectName,
                                                                    ];
                                                                }
                                                            }
                                                        ?>


                                                        <?php if(!empty($subjectDates) && is_array($subjectDates)): ?>
                                                            <div class="subject-schedule">
                                                                <?php $__currentLoopData = $subjectDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="schedule-item mb-2 p-2 bg-light rounded">
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-between mb-1">
                                                                            <strong class="schedule-date">
                                                                                <i class="ti ti-calendar me-1"></i>
                                                                                <?php echo e(\Carbon\Carbon::parse($date)->format('M d, Y')); ?>

                                                                            </strong>
                                                                            <small
                                                                                class="schedule-day"><?php echo e(\Carbon\Carbon::parse($date)->format('l')); ?></small>
                                                                        </div>

                                                                        <!-- Display morning subjects for this date -->
                                                                        <?php if(!empty($morningSubjectNames)): ?>
                                                                            <div class="d-flex align-items-center mb-2">
                                                                                <span
                                                                                    class="badge bg-warning me-2 time-slot-badge">
                                                                                    <i class="ti ti-sun me-1"></i>Morning
                                                                                </span>
                                                                                <div class="flex-grow-1">
                                                                                    <?php $__currentLoopData = $morningSubjectNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <span
                                                                                            class="badge bg-primary me-1 mb-1 subject-badge"><?php echo e($subjectName); ?></span>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>

                                                                        <!-- Display evening subjects for this date -->
                                                                        <?php if(!empty($eveningSubjectNames)): ?>
                                                                            <div class="d-flex align-items-center">
                                                                                <span
                                                                                    class="badge bg-info me-2 time-slot-badge">
                                                                                    <i class="ti ti-moon me-1"></i>Evening
                                                                                </span>
                                                                                <div class="flex-grow-1">
                                                                                    <?php $__currentLoopData = $eveningSubjectNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <span
                                                                                            class="badge bg-primary me-1 mb-1 subject-badge"><?php echo e($subjectName); ?></span>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>
                                                        <?php elseif($exam->subject_dates && !is_array($subjectDates)): ?>
                                                            <!-- Display raw subject_dates if JSON parsing failed -->
                                                            <div class="subject-schedule">
                                                                <div class="schedule-item mb-2 p-2 bg-light rounded">
                                                                    <div class="d-flex align-items-center mb-1">
                                                                        <span class="badge bg-info me-2">
                                                                            <i class="ti ti-calendar me-1"></i>Subject
                                                                            Schedule
                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        <span
                                                                            class="badge bg-primary subject-badge"><?php echo e($exam->subject_dates); ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <!-- Fallback to morning/evening subjects if subject_dates is empty -->
                                                            <div class="subject-schedule">
                                                                <?php if(!empty($morningSubjectNames)): ?>
                                                                    <div class="schedule-item mb-2 p-2 bg-light rounded">
                                                                        <div class="d-flex align-items-center mb-1">
                                                                            <span class="badge bg-warning me-2">
                                                                                <i class="ti ti-sun me-1"></i>Morning
                                                                                Subjects
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <?php $__currentLoopData = $morningSubjectNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <span
                                                                                    class="badge bg-primary me-1 mb-1 subject-badge"><?php echo e($subjectName); ?></span>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <?php if(!empty($eveningSubjectNames)): ?>
                                                                    <div class="schedule-item mb-2 p-2 bg-light rounded">
                                                                        <div class="d-flex align-items-center mb-1">
                                                                            <span class="badge bg-info me-2">
                                                                                <i class="ti ti-moon me-1"></i>Evening
                                                                                Subjects
                                                                            </span>
                                                                        </div>
                                                                        <div>
                                                                            <?php $__currentLoopData = $eveningSubjectNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <span
                                                                                    class="badge bg-primary me-1 mb-1 subject-badge"><?php echo e($subjectName); ?></span>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <?php if(empty($morningSubjectNames) && empty($eveningSubjectNames) && empty($subjectDates)): ?>
                                                                    <div class="text-center text-muted py-3">
                                                                        <i class="ti ti-info-circle me-2"></i>
                                                                        No subject schedule available
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-inbox fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">No Exam Records Found</h5>
                            <p class="text-muted">There are no exam records to display. Create your first exam to get
                                started.</p>
                            <a href="<?php echo e(route('admin.exam-management.exam-setup')); ?>" class="btn btn-primary">
                                <i class="ti ti-plus me-2"></i>Create Exam
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .exam-timetable-card {
            transition: all 0.3s ease;
            border-left: 4px solid #007bff;
        }

        .exam-timetable-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .exam-date-badge {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border-radius: 8px;
            padding: 8px 12px;
            font-weight: 600;
        }

        .exam-time-info {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 6px 10px;
            border-left: 3px solid #28a745;
        }

        .class-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 8px 8px 0 0;
        }

        .exam-card-header {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .exam-meta-info {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .exam-meta-info i {
            width: 16px;
            text-align: center;
        }

        .timetable-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        @media (max-width: 768px) {
            .timetable-grid {
                grid-template-columns: 1fr;
            }
        }

        .subject-schedule {
            max-height: 300px;
            overflow-y: auto;
        }

        .schedule-item {
            border-left: 3px solid #007bff;
            transition: all 0.2s ease;
        }

        .schedule-item:hover {
            background-color: #f8f9fa !important;
            transform: translateX(2px);
        }

        .subject-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
        }

        .time-slot-badge {
            font-size: 0.7rem;
            padding: 3px 6px;
            border-radius: 8px;
            font-weight: 600;
        }

        .schedule-date {
            font-size: 0.9rem;
            font-weight: 600;
            color: #007bff;
        }

        .schedule-day {
            font-size: 0.75rem;
            color: #6c757d;
            font-style: italic;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/admin/exams.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/admin/examination/exam/index.blade.php ENDPATH**/ ?>