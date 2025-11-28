@extends('layouts.student')
@section('title', 'Student Dashboard')
@section('content')

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Dashboard</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div>
            <span class="text-muted">Welcome back, {{ Auth::guard('student')->user()->name }}</span>
            <a href="{{ route('student.id-card') }}" target="_blank" class="btn btn-sm btn-primary ms-3">
                <i class="ti ti-printer me-1"></i> Print ID Card
            </a>
        </div>
    </div>
    <!-- End Page Header -->
    <div class="row">
        <div class="col-md-6 col-xl-3 d-flex">
            <div class="card flex-fill">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="avatar avtar-lg bg-teal mb-2"><img src="{{ asset('adminpanel/img/icons/dashboard-card-icon-01.svg') }}" class="w-auto h-auto" alt="Icon"></div>
                            <h6 class="fs-14 fw-semibold mb-0">Assignments</h6>
                            <div class="fs-18 fw-bold" id="stat_assignments">0</div>
                        </div>
                        <div id="std_circle_1" style="width:120px;height:120px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 d-flex">
            <div class="card flex-fill">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="avatar avtar-lg bg-warning mb-2"><img src="{{ asset('adminpanel/img/icons/dashboard-card-icon-01.svg') }}" class="w-auto h-auto" alt="Icon"></div>
                            <h6 class="fs-14 fw-semibold mb-0">Pending</h6>
                            <div class="fs-18 fw-bold" id="stat_pending">0</div>
                        </div>
                        <div id="std_circle_2" style="width:120px;height:120px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 d-flex">
            <div class="card flex-fill">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="avatar avtar-lg bg-orange mb-2"><img src="{{ asset('adminpanel/img/icons/dashboard-card-icon-01.svg') }}" class="w-auto h-auto" alt="Icon"></div>
                            <h6 class="fs-14 fw-semibold mb-0">Subjects</h6>
                            <div class="fs-18 fw-bold" id="stat_subjects">0</div>
                        </div>
                        <div id="std_circle_3" style="width:120px;height:120px"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 d-flex">
            <div class="card flex-fill">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="avatar avtar-lg bg-teal mb-2"><img src="{{ asset('adminpanel/img/icons/dashboard-card-icon-01.svg') }}" class="w-auto h-auto" alt="Icon"></div>
                            <h6 class="fs-14 fw-semibold mb-0">Routine</h6>
                            <div class="fs-18 fw-bold" id="stat_routines">0</div>
                        </div>
                        <div id="std_circle_4" style="width:120px;height:120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6 col-xl-4 d-flex">
            <div class="card flex-fill">
                <div class="card-header"><h5 class="fw-bold mb-0">My Schedule</h5></div>
                <div class="card-body"><div id="std_polar"></div></div>
            </div>
        </div>
        <div class="col-md-6 col-xl-5 d-flex">
            <div class="card flex-fill">
                <div class="card-header"><h5 class="fw-bold mb-0">Assignments This Week</h5></div>
                <div class="card-body"><div id="std_apps"></div></div>
            </div>
        </div>
        <div class="col-md-12 col-xl-3 d-flex">
            <div class="card flex-fill">
                <div class="card-header"><h5 class="fw-bold mb-0">My Attendance</h5></div>
                <div class="card-body"><div id="std_attendance"></div></div>
            </div>
        </div>
    </div>
   
</div>
<!-- End Content -->

@endsection
@push('scripts')
<script src="{{ asset('adminpanel/plugins/chartjs/chart.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route('student.dashboard.data') }}')
        .then(res => res.json())
        .then(data => {
            document.getElementById('stat_assignments').textContent = (data.stats?.assignmentsTotal ?? 0).toLocaleString();
            document.getElementById('stat_pending').textContent = (data.stats?.pendingAssignments ?? 0).toLocaleString();
            document.getElementById('stat_subjects').textContent = (data.stats?.subjects ?? 0).toLocaleString();
            document.getElementById('stat_routines').textContent = (data.stats?.routines ?? 0).toLocaleString();

            function renderRing(id, vals, colors) {
                const el = document.getElementById(id);
                if (!el) return;
                const cv = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(cv);
                new Chart(cv, { type: 'doughnut', data: { labels: ['A','B'], datasets: [{ data: vals, backgroundColor: colors }] }, options: { plugins:{legend:{display:false}}, cutout: '70%', maintainAspectRatio: false } });
            }
            renderRing('std_circle_1', [data.stats?.assignmentsTotal || 0, data.stats?.pendingAssignments || 0], ['#6366f1','#e5e7eb']);
            renderRing('std_circle_2', [data.stats?.pendingAssignments || 0, data.stats?.assignmentsTotal || 0], ['#f59e0b','#e5e7eb']);
            renderRing('std_circle_3', [data.stats?.subjects || 0, data.stats?.routines || 0], ['#06b6d4','#e5e7eb']);
            renderRing('std_circle_4', [data.stats?.routines || 0, data.stats?.subjects || 0], ['#22c55e','#e5e7eb']);

            const polarTarget = document.getElementById('std_polar');
            if (polarTarget) {
                const c = document.createElement('canvas');
                polarTarget.innerHTML = '';
                polarTarget.appendChild(c);
                new Chart(c, { type: 'polarArea', data: { labels: data.schedulePolar.labels, datasets: [{ data: data.schedulePolar.series, backgroundColor: ['#6366f1','#22c55e','#f59e0b','#ef4444','#06b6d4','#a78bfa','#84cc16','#f97316'] }] }, options: { plugins:{legend:{display:true}} } });
            }

            const appsTarget = document.getElementById('std_apps');
            if (appsTarget) {
                const c2 = document.createElement('canvas');
                appsTarget.innerHTML = '';
                appsTarget.appendChild(c2);
                new Chart(c2, { type: 'bar', data: { labels: data.assignmentsTrend.labels, datasets: [{ label: 'Assignments', data: data.assignmentsTrend.series, backgroundColor: '#6366f1' }] }, options: { responsive: true, plugins:{legend:{display:false}} } });
            }

            const attTarget = document.getElementById('std_attendance');
            if (attTarget) {
                const c3 = document.createElement('canvas');
                attTarget.innerHTML = '';
                attTarget.appendChild(c3);
                new Chart(c3, { type: 'doughnut', data: { labels: ['Present','Absent','Late'], datasets: [{ data: [data.attendanceSummary.present, data.attendanceSummary.absent, data.attendanceSummary.late], backgroundColor: ['#22c55e','#ef4444','#f59e0b'] }] }, options: { plugins:{legend:{display:true}}, cutout: '60%', maintainAspectRatio: false } });
            }
        })
        .catch(err => console.error('Student dashboard data error', err));
});
</script>
@endpush
