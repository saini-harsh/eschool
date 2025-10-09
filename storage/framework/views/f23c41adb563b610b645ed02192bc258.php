<?php $__env->startSection('title', 'Student | Routine'); ?>
<?php $__env->startSection('content'); ?>

<!-- Start Content -->
<div class="content">
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Class Routine</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="<?php echo e(route('student.dashboard')); ?>"><i class="ti ti-home me-1"></i>Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Academics</li>
                    <li class="breadcrumb-item active" aria-current="page">Routine</li>
                </ol>
            </nav>
        </div>
        <div>
            <span class="text-muted">Class: <?php echo e($student->schoolClass->name ?? 'N/A'); ?> - Section: <?php echo e($student->section->name ?? 'N/A'); ?></span>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Routine Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Class Routine</h6>
            <button type="button" id="print_routine" class="btn btn-primary btn-sm">
                <i class="ti ti-printer me-1"></i> PRINT
            </button>
        </div>
        <div class="card-body">
            <div id="routine_content">
                <div class="table-responsive">
                    <table class="table table-bordered" id="routine-table">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Time</th>
                                <th class="text-center">SATURDAY</th>
                                <th class="text-center">SUNDAY</th>
                                <th class="text-center">MONDAY</th>
                                <th class="text-center">TUESDAY</th>
                                <th class="text-center">WEDNESDAY</th>
                                <th class="text-center">THURSDAY</th>
                                <th class="text-center">FRIDAY</th>
                            </tr>
                        </thead>
                        <tbody id="routine-tbody">
                            <?php if($routines->count() > 0): ?>
                                <?php
                                    $timeSlots = [];
                                    $allRoutines = $groupedRoutines->flatten();
                                    
                                    // Get all unique time slots
                                    foreach($allRoutines as $routine) {
                                        $timeKey = $routine->start_time . '-' . $routine->end_time;
                                        if (!isset($timeSlots[$timeKey])) {
                                            $timeSlots[$timeKey] = [
                                                'start_time' => $routine->start_time,
                                                'end_time' => $routine->end_time
                                            ];
                                        }
                                    }
                                    
                                    // Sort time slots by start time
                                    uasort($timeSlots, function($a, $b) {
                                        return strcmp($a['start_time'], $b['start_time']);
                                    });
                                ?>
                                
                                <?php $__currentLoopData = $timeSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeSlot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-center fw-bold">
                                            <?php echo e(\Carbon\Carbon::parse($timeSlot['start_time'])->format('H:i')); ?> - 
                                            <?php echo e(\Carbon\Carbon::parse($timeSlot['end_time'])->format('H:i')); ?>

                                        </td>
                                        <?php $__currentLoopData = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <td class="text-center">
                                                <?php
                                                    $dayRoutines = $groupedRoutines->get($day, collect())->filter(function($routine) use ($timeSlot) {
                                                        return $routine->start_time == $timeSlot['start_time'] && $routine->end_time == $timeSlot['end_time'];
                                                    });
                                                ?>
                                                
                                                <?php if($dayRoutines->isNotEmpty()): ?>
                                                    <?php $__currentLoopData = $dayRoutines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $routine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="routine-cell p-2 border rounded mb-1" style="background-color: #f8f9fa;">
                                                            <div class="fw-bold text-primary"><?php echo e($routine->subject->name ?? 'N/A'); ?></div>
                                                            <div class="text-muted small"><?php echo e($routine->teacher->first_name ?? 'N/A'); ?> <?php echo e($routine->teacher->last_name ?? ''); ?></div>
                                                            <div class="text-muted small"><?php echo e($routine->classRoom->room_no ?? 'N/A'); ?></div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <div class="text-muted">-</div>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="ti ti-calendar-event fs-48 mb-3 d-block"></i>
                                        No routine found for your class and section
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
<!-- End Content -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Print-specific styles */
    @media print {
        body * {
            visibility: hidden;
        }
        #routine_content, #routine_content * {
            visibility: visible;
        }
        #routine_content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .card-header, .btn {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-body {
            padding: 0 !important;
        }
        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        th, td {
            border: 1px solid #000 !important;
            padding: 8px !important;
            text-align: center !important;
        }
        th {
            background-color: #f5f5f5 !important;
            font-weight: bold !important;
        }
        .routine-cell {
            background-color: #f8f9fa !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            padding: 4px !important;
            margin: 2px 0 !important;
        }
        .routine-cell div:first-child {
            font-weight: bold !important;
            color: #007bff !important;
        }
        .routine-cell div:not(:first-child) {
            color: #666 !important;
            font-size: 0.9em !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Student Routine Management
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Student Routine page loaded');
        
        // Print functionality
        document.getElementById('print_routine').addEventListener('click', function() {
            printRoutine();
        });
    });

    function printRoutine() {
        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        
        // Get the routine table HTML
        const routineTable = document.getElementById('routine-table').outerHTML;
        
        // Get student info
        const studentInfo = document.querySelector('.text-muted').textContent;
        
        // Create print-friendly HTML in rectangle format
        const printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Class Routine</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 15px;
                        background: white;
                        font-size: 12px;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 15px;
                        border: 2px solid #333;
                        padding: 10px;
                        background-color: #f8f9fa;
                    }
                    .print-header h1 {
                        margin: 0;
                        color: #333;
                        font-size: 20px;
                        font-weight: bold;
                    }
                    .print-header p {
                        margin: 3px 0 0 0;
                        color: #666;
                        font-size: 12px;
                    }
                    .routine-container {
                        border: 2px solid #333;
                        margin-top: 10px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 0;
                    }
                    th, td {
                        border: 1px solid #333;
                        padding: 6px;
                        text-align: center;
                        vertical-align: top;
                        font-size: 10px;
                    }
                    th {
                        background-color: #e9ecef;
                        font-weight: bold;
                        font-size: 11px;
                        padding: 8px 6px;
                    }
                    td {
                        min-height: 50px;
                        width: 12.5%;
                    }
                    .time-cell {
                        font-weight: bold;
                        background-color: #f8f9fa;
                        font-size: 10px;
                        width: 12.5%;
                    }
                    .routine-cell {
                        background-color: #ffffff !important;
                        border: 1px solid #333 !important;
                        border-radius: 3px !important;
                        padding: 3px !important;
                        margin: 1px 0 !important;
                        text-align: center !important;
                        font-size: 9px;
                    }
                    .routine-cell div:first-child {
                        font-weight: bold;
                        color: #000;
                        font-size: 9px;
                        margin-bottom: 1px;
                    }
                    .routine-cell div:not(:first-child) {
                        color: #333;
                        font-size: 8px;
                        margin-top: 1px;
                    }
                    .empty-cell {
                        color: #999;
                        font-size: 9px;
                    }
                    @media print {
                        body { 
                            margin: 10px; 
                            font-size: 11px;
                        }
                        .print-header { 
                            page-break-after: avoid; 
                            margin-bottom: 10px;
                        }
                        .routine-container {
                            page-break-inside: avoid;
                        }
                        table { 
                            page-break-inside: auto; 
                        }
                        tr { 
                            page-break-inside: avoid; 
                        }
                        th, td {
                            font-size: 9px;
                            padding: 4px;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h1>CLASS ROUTINE</h1>
                    <p>${studentInfo}</p>
                    <p>Academic Year: ${new Date().getFullYear()}</p>
                </div>
                <div class="routine-container">
                    ${routineTable}
                </div>
            </body>
            </html>
        `;
        
        // Write content to print window
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Wait for content to load, then print
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/student/academic/routine/index.blade.php ENDPATH**/ ?>