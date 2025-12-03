@extends('layouts.teacher')
@section('title', 'Teacher | Marks Entry')
@section('content')
<div class="content">
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Marks Entry</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('teacher.dashboard') }}"><i class="ti ti-home me-1"></i>Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Marks Entry</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><h6 class="fw-bold mb-0">Select Criteria</h6></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="form-label">Exam Type</label>
                    <select id="exam_type_filter" class="form-select">
                        <option value="">Select Exam Type</option>
                        @foreach(($examTypes ?? []) as $type)
                            <option value="{{ $type->id }}">{{ $type->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Month</label>
                    <select id="month_filter" class="form-select">
                        <option value="">Select Month</option>
                        @for ($m=1;$m<=12;$m++)
                            <option value="{{ $m }}">{{ DateTime::createFromFormat('!m',$m)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Class</label>
                    <select id="class_filter" class="form-select">
                        <option value="">Select Class</option>
                        @foreach(($classes ?? []) as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Section</label>
                    <select id="section_filter" class="form-select" disabled>
                        <option value="">Select Section</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Subject</label>
                    <select id="subject_filter" class="form-select" disabled>
                        <option value="">Select Subject</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="button" id="search_marks" class="btn btn-primary"><i class="ti ti-search me-1"></i>SEARCH</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Total Marks</label>
                    <input type="number" id="total_marks" class="form-control" value="100" min="1">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Pass Marks</label>
                    <input type="number" id="pass_marks" class="form-control" value="33" min="0">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-0">Students</h6>
                <div class="text-muted small">Pass: <span id="summary_pass">0</span> · Fail: <span id="summary_fail">0</span></div>
            </div>
            <button type="button" id="submit_marks" class="btn btn-primary btn-sm"><i class="ti ti-device-floppy me-1"></i>Submit</button>
        </div>
        <div class="card-body" id="marks_content">
            <div class="text-center text-muted py-5">
                <i class="ti ti-calendar-event fs-48 mb-3"></i>
                <p>Select filters to load students</p>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var classSel = document.getElementById('class_filter');
    var sectionSel = document.getElementById('section_filter');
    var subjectSel = document.getElementById('subject_filter');
    function fetchSections(classId){
        if (!classId){ sectionSel.innerHTML = '<option value="">Select Section</option>'; sectionSel.disabled = true; return; }
        fetch('/teacher/marks-entries/sections/'+classId).then(r=>r.json()).then(function(resp){ if(resp.success){ sectionSel.innerHTML = '<option value="">Select Section</option>'; resp.data.forEach(function(s){ sectionSel.innerHTML += '<option value="'+s.id+'">'+s.name+'</option>'; }); sectionSel.disabled = false; }});
        fetch('/teacher/marks-entries/subjects/'+classId).then(r=>r.json()).then(function(resp){ if(resp.success){ subjectSel.innerHTML = '<option value="">Select Subject</option>'; resp.data.forEach(function(s){ var code = s.code?(' ('+s.code+')'):''; subjectSel.innerHTML += '<option value="'+s.id+'">'+s.name+code+'</option>'; }); subjectSel.disabled = false; }});
    }
    if (classSel){ classSel.addEventListener('change', function(){ fetchSections(this.value); }); }

    function renderRows(list){
        var c = document.getElementById('marks_content');
        if (!list || !list.length){ c.innerHTML = '<div class="text-center text-muted py-5"><i class="ti ti-user-exclamation fs-48 mb-3"></i><p>No students found for the selected criteria</p></div>'; return; }
        var html = '<div class="table-responsive"><table class="table table-bordered"><thead class="table-light"><tr><th>Roll No</th><th>Name</th><th>Marks</th></tr></thead><tbody id="marks_tbody">';
        list.forEach(function(row){ var v = (row.marks_obtained!=null)?row.marks_obtained:''; html += '<tr data-student-id="'+row.student_id+'"><td>'+(row.roll_number||'')+'</td><td>'+row.name+'</td><td><input type="number" class="form-control form-control-sm entry-mark" min="0" step="0.5" value="'+v+'"></td></tr>'; });
        html += '</tbody></table></div>';
        c.innerHTML = html;
        updateSummary();
        document.querySelectorAll('.entry-mark').forEach(function(inp){ inp.addEventListener('input', updateSummary); });
    }
    function updateSummary(){
        var pass = 0, fail = 0; var passMarks = parseFloat(document.getElementById('pass_marks').value||'0');
        document.querySelectorAll('#marks_tbody .entry-mark').forEach(function(inp){ var v = parseFloat(inp.value||'0'); if (isNaN(v)) v=0; if (v>=passMarks) pass++; else fail++; });
        document.getElementById('summary_pass').textContent = pass; document.getElementById('summary_fail').textContent = fail;
    }
    document.getElementById('pass_marks').addEventListener('input', updateSummary);
    document.getElementById('search_marks').addEventListener('click', function(){
        var params = new URLSearchParams();
        var et = document.getElementById('exam_type_filter').value; if (et) params.append('exam_type_id', et);
        var mo = document.getElementById('month_filter').value; if (mo) params.append('month', mo);
        var cl = classSel.value; var se = sectionSel.value; var su = subjectSel.value;
        if (!cl || !se || !su) { alert('Select class, section, subject'); return; }
        params.append('class_id', cl); params.append('section_id', se); params.append('subject_id', su);
        fetch('/teacher/marks-entries/report?'+params.toString()).then(function(res){ return res.json(); }).then(function(resp){ if (resp.success) renderRows(resp.students||[]); else alert('Failed to load students'); });
    });
    document.getElementById('submit_marks').addEventListener('click', function(){
        var entries = []; document.querySelectorAll('#marks_tbody tr').forEach(function(tr){ var sid = tr.getAttribute('data-student-id'); var v = tr.querySelector('.entry-mark').value; entries.push({student_id: sid, marks_obtained: v}); });
        var body = { exam_type_id: document.getElementById('exam_type_filter').value || null, month: document.getElementById('month_filter').value || null, class_id: classSel.value, section_id: sectionSel.value, subject_id: subjectSel.value, total_marks: document.getElementById('total_marks').value, pass_marks: document.getElementById('pass_marks').value, entries: entries };
        fetch('/teacher/marks-entries/store', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify(body) }).then(function(res){ return res.json(); }).then(function(resp){ if (resp.success) { document.getElementById('summary_pass').textContent = resp.summary.pass; document.getElementById('summary_fail').textContent = resp.summary.fail; alert('Marks saved'); } else { alert('Failed to save marks'); } });
    });
});
</script>
@endsection
