@extends('layouts.student')
@section('title','Student | Exams')
@section('content')
<div class="content">
  <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
    <div class="flex-grow-1">
      <h5 class="fw-bold">Exams</h5>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
          <li class="breadcrumb-item d-flex align-items-center"><a href="{{ route('student.dashboard') }}"><i class="ti ti-home me-1"></i>Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Exams</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <h6 class="card-title mb-3">Filter</h6>
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <label class="form-label">Month</label>
          <select id="month_filter" class="form-select">
            <option value="">Select Month</option>
            @for($m=1;$m<=12;$m++)
              <option value="{{ $m }}">{{ DateTime::createFromFormat('!m',$m)->format('F') }}</option>
            @endfor
          </select>
        </div>
        <div class="col-md-2">
          <button type="button" id="search_btn" class="btn btn-primary w-100"><i class="ti ti-filter me-1"></i>Filter</button>
        </div>
        <div class="col-md-2">
          <button type="button" id="clear_btn" class="btn btn-outline-secondary w-100"><i class="ti ti-x me-1"></i>Clear</button>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body" id="schedule_container">
      <div class="text-center text-muted py-5">
        <i class="ti ti-calendar-event fs-48 mb-3"></i>
        <p>Select month to view your exam schedule</p>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
  function loadSchedule(){
    var m = document.getElementById('month_filter').value; var qs = new URLSearchParams(); if (m) qs.append('month', m);
    fetch('/student/exams/schedule?'+qs.toString()).then(r=>r.json()).then(function(resp){ if(resp.success){ renderSchedule(resp.data||[]); } });
  }
  function renderSchedule(list){
    var c = document.getElementById('schedule_container');
    if (!list.length){ c.innerHTML = '<div class="text-center text-muted py-5"><i class="ti ti-user-exclamation fs-48 mb-3"></i><p>No exams found for the selected month</p></div>'; return; }
    var html = '<div class="table-responsive"><table class="table table-bordered"><thead class="table-light"><tr><th>Date</th><th>Morning</th><th>Evening</th></tr></thead><tbody>';
    list.forEach(function(row){
      var mt = row.morning_time ? ('<br><small class="text-muted">'+row.morning_time+'</small>') : ''; var et = row.evening_time ? ('<br><small class="text-muted">'+row.evening_time+'</small>') : '';
      var md = row.morning_subject ? row.morning_subject : '-'; var ed = row.evening_subject ? row.evening_subject : '-';
      html += '<tr><td>'+new Date(row.date).toLocaleDateString()+'</td><td>'+md+mt+'</td><td>'+ed+et+'</td></tr>';
    });
    html += '</tbody></table></div>';
    c.innerHTML = html;
  }
  document.getElementById('search_btn').addEventListener('click', loadSchedule);
  document.getElementById('clear_btn').addEventListener('click', function(){ document.getElementById('month_filter').value=''; loadSchedule(); });
  loadSchedule();
});
</script>
@endsection
