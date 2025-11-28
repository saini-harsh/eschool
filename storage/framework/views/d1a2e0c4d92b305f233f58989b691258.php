<?php
    if (!function_exists('getSubjectNames')) {
        function getSubjectNames($subjectIds)
        {
            if (empty($subjectIds)) {
                return [];
            }
            $subjects = \App\Models\Subject::whereIn('id', $subjectIds)->get();
            return $subjects->pluck('name')->toArray();
        }
    }
?>
<?php $__env->startSection('title', 'Institution | Exam Management | Exams'); ?>
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
                        <li class="breadcrumb-item d-flex align-items-center"><a
                                href="<?php echo e(route('institution.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a></li>
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
                    action="<?php echo e(route('institution.exam-management.exams')); ?>">
                    <!-- Institution Dropdown -->
                    <div class="col-md-2">
                        <label for="institution" class="form-label">Institution</label>
                        <select class="form-select" id="institution" name="institution">
                            <option value="">Select Institution</option>
                            <?php if(isset($institutions) && count($institutions) > 0): ?>
                                <?php $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($institution->id); ?>"
                                        <?php echo e(auth()->user()->id == $institution->id ? 'selected' : ''); ?>>
                                        <?php echo e($institution->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <option value="">No institutions found</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2" id="class-field">
                        <label for="class" class="form-label">Exam Type</label>
                        <select class="form-select" id="exam-type" name="exam_type">

                        </select>
                    </div>
                    <!-- Class Dropdown (for students) -->
                    

                    <!-- Action Buttons -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo e(route('institution.exam-management.exams')); ?>" class="btn btn-outline-secondary w-100">
                            <i class="ti ti-x me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Exam Timetable Template -->
        <?php if(isset($lists) &&
                !empty($lists) &&
                (request()->filled('institution') || request()->filled('class') || request()->filled('section'))): ?>
            <?php
                $groupedExams = $lists->groupBy(function ($exam) {
                    return $exam->class ? $exam->class->name : 'Unknown Class';
                });
                $examTitle = $lists->first()->title ?? 'Exam Timetable';
                $institutionName = Auth::user()->name ?? 'Institute Name';
                $examType = $lists->first()->examType->title ?? '';
                // Debug: Let's see what data we have
                // dd($lists->first()->toArray());
            ?>

            <!-- Header -->
            <div class="exam-header mb-4"
                style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 20px; border-radius: 12px; border: 2px solid #dee2e6; margin-bottom: 30px;">
                <h3 class="text-center fw-bold text-primary"
                    style="margin: 0; font-size: 1.8rem; color: #495057; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
                    <?php echo e($institutionName); ?> - <?php echo e($examType); ?>

                </h3>
            </div>

            <!-- Class Sections Container -->
            <div class="class-sections-container">
                <div class="row g-4">
                    <?php $__currentLoopData = $groupedExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className => $exams): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="class-section-card"
                                style="background: #ffffff; border: 2px solid #dee2e6; border-radius: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); overflow: hidden; transition: all 0.3s ease; height: 100%;">
                                <div class="class-header"
                                    style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 15px 20px; text-align: center;">
                                    <h5 class="class-title"
                                        style="margin: 0; font-size: 1.2rem; font-weight: 600; text-shadow: 1px 1px 2px rgba(0,0,0,0.2);color:white">
                                        Class: <?php echo e($className); ?></h5>
                                </div>

                                <div class="class-content" style="padding: 20px; min-height: 300px;">
                                    <?php if(!empty($exams)): ?>
                                        <?php
                                            $firstExam = $exams->first();
                                            $subjectDates = $firstExam->subject_dates
                                                ? json_decode($firstExam->subject_dates, true)
                                                : [];
                                            $morningSubjects = $firstExam->morning_subjects
                                                ? json_decode($firstExam->morning_subjects, true)
                                                : [];
                                            $eveningSubjects = $firstExam->evening_subjects
                                                ? json_decode($firstExam->evening_subjects, true)
                                                : [];

                                            // Get all subject IDs to fetch names in one query
                                            $allSubjectIds = array_merge(
                                                array_filter($morningSubjects ?: []),
                                                array_filter($eveningSubjects ?: []),
                                            );
                                            $allSubjectIds = array_unique($allSubjectIds);

                                            // Get subject names for all IDs
                                            $subjectNames = [];
                                            if (!empty($allSubjectIds)) {
                                                $subjects = \App\Models\Subject::whereIn('id', $allSubjectIds)->get();
                                                $subjectNames = $subjects->pluck('name', 'id')->toArray();
                                            }

                                            // Create date-subject mapping
                                            $dateSubjectMapping = [];
                                            if (!empty($subjectDates)) {
                                                foreach ($subjectDates as $index => $date) {
                                                    $morningSubjectId = isset($morningSubjects[$index])
                                                        ? $morningSubjects[$index]
                                                        : null;
                                                    $eveningSubjectId = isset($eveningSubjects[$index])
                                                        ? $eveningSubjects[$index]
                                                        : null;

                                                    $morningSubjectName =
                                                        $morningSubjectId && isset($subjectNames[$morningSubjectId])
                                                            ? $subjectNames[$morningSubjectId]
                                                            : null;
                                                    $eveningSubjectName =
                                                        $eveningSubjectId && isset($subjectNames[$eveningSubjectId])
                                                            ? $subjectNames[$eveningSubjectId]
                                                            : null;

                                                    $dateSubjectMapping[$date] = [
                                                        'morning' => $morningSubjectName,
                                                        'evening' => $eveningSubjectName,
                                                    ];
                                                }
                                            }
                                        ?>

                                        <?php if(!empty($dateSubjectMapping)): ?>
                                            <div class="schedule-table" style="width: 100%; border-collapse: collapse;">
                                                <div class="table-header"
                                                    style="display: flex; background: #f8f9fa; border-radius: 8px 8px 0 0; border: 1px solid #dee2e6; border-bottom: 2px solid #007bff;">
                                                    <div class="header-cell"
                                                        style="flex: 1; padding: 12px 8px; text-align: center; font-weight: 600; color: #495057; font-size: 0.9rem; border-right: 1px solid #dee2e6;">
                                                        Date</div>
                                                    <div class="header-cell"
                                                        style="flex: 1; padding: 12px 8px; text-align: center; font-weight: 600; color: #495057; font-size: 0.9rem; border-right: 1px solid #dee2e6;">
                                                        Morning
                                                        <?php if($firstExam->morning_time): ?>
                                                            <br><small
                                                                style="font-size: 0.7rem; color: #6c757d;"><?php echo e($firstExam->morning_time); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="header-cell"
                                                        style="flex: 1; padding: 12px 8px; text-align: center; font-weight: 600; color: #495057; font-size: 0.9rem;">
                                                        Evening
                                                        <?php if($firstExam->evening_time): ?>
                                                            <br><small
                                                                style="font-size: 0.7rem; color: #6c757d;"><?php echo e($firstExam->evening_time); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <?php $__currentLoopData = $dateSubjectMapping; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $subjects): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="table-row"
                                                        style="display: flex; border-left: 1px solid #dee2e6; border-right: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6; transition: background-color 0.2s ease;">
                                                        <div class="date-cell"
                                                            style="flex: 1; padding: 12px 8px; text-align: center; font-weight: 600; color: #007bff; border-right: 1px solid #dee2e6; background: #f8f9fa; font-size: 0.9rem;">
                                                            <?php echo e(\Carbon\Carbon::parse($date)->format('M d')); ?>

                                                        </div>
                                                        <div class="subject-cell morning"
                                                            style="flex: 1; padding: 12px 8px; text-align: center; border-right: 1px solid #dee2e6; font-size: 0.85rem; min-height: 50px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #fff3cd, #ffeaa7); color: #856404; font-weight: 500;">
                                                            <?php if(!empty($subjects['morning'])): ?>
                                                                <div style="font-weight: 600;"><?php echo e($subjects['morning']); ?>

                                                                </div>
                                                            <?php else: ?>
                                                                <span class="empty-slot"
                                                                    style="color: #6c757d; font-style: italic; font-size: 0.8rem;">-</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="subject-cell evening"
                                                            style="flex: 1; padding: 12px 8px; text-align: center; font-size: 0.85rem; min-height: 50px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #d1ecf1, #bee5eb); color: #0c5460; font-weight: 500;">
                                                            <?php if(!empty($subjects['evening'])): ?>
                                                                <div style="font-weight: 600;"><?php echo e($subjects['evening']); ?>

                                                                </div>
                                                            <?php else: ?>
                                                                <span class="empty-slot"
                                                                    style="color: #6c757d; font-style: italic; font-size: 0.8rem;">-</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="empty-schedule"
                                                style="display: flex; align-items: center; justify-content: center; height: 200px; background: #f8f9fa; border-radius: 8px; border: 2px dashed #dee2e6;">
                                                <div class="empty-message" style="text-align: center; color: #6c757d;">
                                                    <i class="ti ti-calendar-x"
                                                        style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                                                    <p style="margin: 0; font-size: 0.9rem;">No schedule available</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="empty-schedule"
                                            style="display: flex; align-items: center; justify-content: center; height: 200px; background: #f8f9fa; border-radius: 8px; border: 2px dashed #dee2e6;">
                                            <div class="empty-message" style="text-align: center; color: #6c757d;">
                                                <i class="ti ti-calendar-x"
                                                    style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                                                <p style="margin: 0; font-size: 0.9rem;">No exams scheduled</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <?php if(!request()->filled('institution') && !request()->filled('class') && !request()->filled('section')): ?>
                            <i class="ti ti-filter fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Apply Filters to View Exam Timetable</h5>
                            <p class="text-muted">Please select an institution, class, or section to view the exam
                                timetable.</p>
                        <?php else: ?>
                            <i class="ti ti-inbox fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">No Exam Records Found</h5>
                            <p class="text-muted">No exam records found for the selected filters. Try adjusting your search
                                criteria.</p>
                            <a href="<?php echo e(route('institution.exam-management.exam-setup')); ?>" class="btn btn-primary">
                                <i class="ti ti-plus me-2"></i>Create Exam
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Template-style design */
        .exam-header {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 20px;
            border-radius: 12px;
            border: 2px solid #dee2e6;
            margin-bottom: 30px;
        }

        .exam-header h3 {
            margin: 0;
            font-size: 1.8rem;
            color: #495057;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .class-sections-container {
            margin-top: 20px;
        }

        .class-section-card {
            background: #ffffff;
            border: 2px solid #dee2e6;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }

        .class-section-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .class-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        .class-title {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .class-content {
            padding: 20px;
            min-height: 300px;
        }

        /* Schedule Table Styling */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header {
            display: flex;
            background: #f8f9fa;
            border-radius: 8px 8px 0 0;
            border: 1px solid #dee2e6;
            border-bottom: 2px solid #007bff;
        }

        .header-cell {
            flex: 1;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            border-right: 1px solid #dee2e6;
        }

        .header-cell:last-child {
            border-right: none;
        }

        .table-row {
            display: flex;
            border-left: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
            transition: background-color 0.2s ease;
        }

        .table-row:hover {
            background-color: #f8f9fa;
        }

        .table-row:last-child {
            border-radius: 0 0 8px 8px;
        }

        .date-cell {
            flex: 1;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
            color: #007bff;
            border-right: 1px solid #dee2e6;
            background: #f8f9fa;
            font-size: 0.9rem;
        }

        .subject-cell {
            flex: 1;
            padding: 12px 8px;
            text-align: center;
            border-right: 1px solid #dee2e6;
            font-size: 0.85rem;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .subject-cell:last-child {
            border-right: none;
        }

        .subject-cell.morning {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
            font-weight: 500;
        }

        .subject-cell.evening {
            background: linear-gradient(135deg, #d1ecf1, #bee5eb);
            color: #0c5460;
            font-weight: 500;
        }

        .empty-slot {
            color: #6c757d;
            font-style: italic;
            font-size: 0.8rem;
        }

        /* Empty State Styling */
        .empty-schedule {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 200px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
        }

        .empty-message {
            text-align: center;
            color: #6c757d;
        }

        .empty-message i {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
        }

        .empty-message p {
            margin: 0;
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .exam-header h3 {
                font-size: 1.4rem;
            }

            .class-section-card {
                margin-bottom: 20px;
            }

            .header-cell,
            .date-cell,
            .subject-cell {
                padding: 8px 4px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {

            .table-header,
            .table-row {
                flex-direction: column;
            }

            .header-cell,
            .date-cell,
            .subject-cell {
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }

            .header-cell:last-child,
            .date-cell:last-child,
            .subject-cell:last-child {
                border-bottom: none;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('custom/js/institution/exams.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/institution/examination/exam/index.blade.php ENDPATH**/ ?>