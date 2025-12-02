<?php $__env->startSection('title', 'Teacher | Exam Routine'); ?>
<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Exam Routine</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('teacher.dashboard')); ?>"><i class="ti ti-home me-1"></i>Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Exam Routine</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h6 class="fw-bold mb-0">Select Criteria</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Exam Type</label>
                    <select id="exam_type_filter" class="form-select">
                        <option value="">Select Exam Type</option>
                        <?php $__currentLoopData = ($examTypes ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->id); ?>"><?php echo e($type->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Month</label>
                    <select id="month_filter" class="form-select">
                        <option value="">Select Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Class</label>
                    <select id="class_filter" class="form-select">
                        <option value="">Select Class</option>
                        <?php $__currentLoopData = ($classes ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Section</label>
                    <select id="section_filter" class="form-select" disabled>
                        <option value="">Select Section</option>
                    </select>
                </div>
                <div class="col-md-12 d-flex align-items-end"><button type="button" id="search_exam_routine" class="btn btn-primary"><i class="ti ti-search me-1"></i>SEARCH</button></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Exam Schedule</h6>
            <button type="button" id="print_exam_routine" class="btn btn-primary btn-sm"><i class="ti ti-printer me-1"></i>PRINT</button>
        </div>
        <div class="card-body" id="exam_routine_content">
            <div class="text-center text-muted py-5">
                <i class="ti ti-calendar-event fs-48 mb-3"></i>
                <p>Select filters to view exam routine</p>
            </div>
        </div>
    </div>
</div>

<template id="exam-row-template">
    <tr>
        <td>__DATE__</td>
        <td>
            <div class="fw-semibold">__M_SUBJECT__</div>
            <div class="text-muted small">__M_TIME__</div>
        </td>
        <td>
            <div class="fw-semibold">__E_SUBJECT__</div>
            <div class="text-muted small">__E_TIME__</div>
        </td>
        <td>__ROOM_TAGS__</td>
    </tr>
    </template>

<script>
document.addEventListener('DOMContentLoaded', function(){
    var classSel = document.getElementById('class_filter');
    var sectionSel = document.getElementById('section_filter');
    var searchBtn = document.getElementById('search_exam_routine');
    var printBtn = document.getElementById('print_exam_routine');
    function fetchSections(classId){
        if (!classId){ sectionSel.innerHTML = '<option value="">Select Section</option>'; sectionSel.disabled = true; return; }
        fetch('/teacher/exam-routines/sections/'+classId)
            .then(res=>res.json()).then(function(resp){
                if (resp.success){
                    sectionSel.innerHTML = '<option value="">Select Section</option>';
                    resp.data.forEach(function(s){ sectionSel.innerHTML += '<option value="'+s.id+'">'+s.name+'</option>'; });
                    sectionSel.disabled = false;
                }
            });
    }
    if (classSel){ classSel.addEventListener('change', function(){ fetchSections(this.value); }); }
    if (printBtn){ printBtn.addEventListener('click', function(){ window.print(); }); }
    function formatTime(t){ if(!t) return ''; var parts=t.split(':'); var h=parseInt(parts[0]); var m=parts[1]; var ampm = h>=12?'PM':'AM'; var h12 = h%12||12; return h12+':'+m+' '+ampm; }
    function render(data){
        var container = document.getElementById('exam_routine_content');
        if (!data || !data.length){
            container.innerHTML = '<div class="text-center text-muted py-5"><i class="ti ti-calendar-event fs-48 mb-3"></i><p>No exam schedules found</p></div>';
            return;
        }
        var html = '<div class="table-responsive"><table class="table table-bordered"><thead class="table-light"><tr><th>Date</th><th>Morning</th><th>Evening</th><th>My Invigilation Rooms</th></tr></thead><tbody>';
        data.forEach(function(row){
            var tpl = document.getElementById('exam-row-template').innerHTML;
            var rooms = (row.invigilator_rooms||[]).map(function(r){ var nm = r.room_no || ''; if (r.room_name) nm += ' - '+r.room_name; return '<span class="badge bg-secondary me-1">'+nm+'</span>'; }).join('');
            tpl = tpl.replace('__DATE__', row.date)
                     .replace('__M_SUBJECT__', row.morning_subject || '-')
                     .replace('__M_TIME__', formatTime(row.morning_time))
                     .replace('__E_SUBJECT__', row.evening_subject || '-')
                     .replace('__E_TIME__', formatTime(row.evening_time))
                     .replace('__ROOM_TAGS__', rooms || '<span class="text-muted">None</span>');
            html += tpl;
        });
        html += '</tbody></table></div>';
        container.innerHTML = html;
    }
    function search(){
        var params = new URLSearchParams();
        var et = document.getElementById('exam_type_filter').value; if (et) params.append('exam_type_id', et);
        var mo = document.getElementById('month_filter').value; if (mo) params.append('month', mo);
        var cl = classSel.value; if (cl) params.append('class_id', cl);
        var se = sectionSel.value; if (se) params.append('section_id', se);
        fetch('/teacher/exam-routines/report?'+params.toString()).then(function(res){ return res.json(); }).then(function(resp){ if (resp.success) render(resp.data); else render([]); });
    }
    if (searchBtn){ searchBtn.addEventListener('click', search); }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/teacher/exam-management/exam-routine/index.blade.php ENDPATH**/ ?>