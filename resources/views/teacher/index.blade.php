@extends('layouts.teacher')
@section('title', 'Teacher Dashboard')
@section('content')

<!-- Start Content -->
<div class="content">

    
    <div class="row">
        <div class="col-lg-8 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                                <li class="breadcrumb-item d-flex align-items-center"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item fw-medium active" aria-current="page">Dashboard</li>
                            </ol>
                        </nav>
                        <h5 class="fw-bold mb-0">Teacher Dashboard</h5>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-xl-3 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="avatar avtar-lg bg-teal mb-2"><img src="{{ asset('adminpanel/img/icons/dashboard-card-icon-01.svg') }}" class="w-auto h-auto" alt="Icon"></div>
                                    <h6 class="fs-14 fw-semibold mb-0">Students</h6>
                                    <div class="fs-18 fw-bold">{{ number_format($stats['students'] ?? 0) }}</div>
                                </div>
                                <div id="circle_chart_1"></div>
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
                                    <h6 class="fs-14 fw-semibold mb-0">Teachers</h6>
                                    <div class="fs-18 fw-bold">{{ number_format($stats['teachers'] ?? 0) }}</div>
                                </div>
                                <div id="circle_chart_2"></div>
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
                                    <h6 class="fs-14 fw-semibold mb-0">Classes</h6>
                                    <div class="fs-18 fw-bold">{{ number_format($stats['classes'] ?? 0) }}</div>
                                </div>
                                <div id="circle_chart_3"></div>
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
                                    <h6 class="fs-14 fw-semibold mb-0">Sections</h6>
                                    <div class="fs-18 fw-bold">{{ number_format($stats['sections'] ?? 0) }}</div>
                                </div>
                                <div id="circle_chart_7"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-xl-4 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="mb-1">
                                        <p class="mb-1 text-dark">Total Sections</p>
                                        <h6 class="fs-16 fw-semibold mb-1">{{ number_format($stats['sections'] ?? 0) }}</h6>
                                    </div>
                                    <p class="fs-12 text-truncate text-dark mb-0"><span class="text-success me-1"><i class="ti ti-trending-up"></i></span>+1.4% from last week</p>
                                </div>
                                <div id="circle_chart_4"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="mb-1">
                                        <p class="mb-1 text-dark">Total Subjects</p>
                                        <h6 class="fs-16 fw-semibold mb-1">{{ number_format($stats['subjects'] ?? 0) }}</h6>
                                    </div>
                                    <p class="fs-12 text-truncate text-dark mb-0"><span class="text-success me-1"><i class="ti ti-trending-up"></i></span>+1.4% from last week</p>
                                </div>
                                <div id="circle_chart_5"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="mb-1">
                                        <p class="mb-1 text-dark">Total Assignments</p>
                                        <h6 class="fs-16 fw-semibold mb-1">{{ number_format($stats['assignments'] ?? 0) }}</h6>
                                    </div>
                                    <p class="fs-12 text-truncate tex-dark mb-0"><span class="text-success me-1"><i class="ti ti-trending-up"></i></span>+1.4% from last week</p>
                                </div>
                                <div id="circle_chart_6"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="index-profile text-center">
                        <img src="{{ $teacher->profile_image ? asset($teacher->profile_image) : '' }}" alt="img" class="avatar avatar-xxl rounded-circle shadow">
                        <div class="text-center mb-0">
                            <h5 class="fw-bold mb-1">Welcome {{ Auth::guard('teacher')->user()->name }}</h5>
                            <p class="mb-0">{{ $stats['dateToday'] ?? '' }}</p>
                        </div>
                    </div>
                    <div class="index-profile-links">
                        <a href="index.html" class="dashboard-toggle active">Teacher Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-4 d-flex">
            <div class="card flex-fill">
                <div class="card-header"><h5 class="fw-bold mb-0">Total Students</h5></div>
                <div class="card-body"><div id="polarchart"></div></div>
            </div>
        </div>
        <div class="col-md-6 col-xl-5 d-flex">
            <div class="card flex-fill">
                <div class="card-header"><h5 class="fw-bold mb-0">Total Applications</h5></div>
                <div class="card-body"><div id="applications_chart"></div></div>
            </div>
        </div>
        <div class="col-md-12 col-xl-3 d-flex">
            <div class="card flex-fill">
                <div class="card-header"><h5 class="fw-bold mb-0">Structure</h5></div>
                <div class="card-body">
                    <div class="d-flex d-xl-block align-items-center justify-content-center flex-wrap text-center">
                        <div><div id="chart_male"></div><p class="text-center fw-semibold text-dark mb-0">Male</p></div>
                        <div><div id="chart_female"></div><p class="text-center fw-semibold text-dark mb-0">Female</p></div>
                    </div>
                </div>
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
    fetch('{{ route('teacher.dashboard.data') }}')
        .then(res => res.json())
        .then(data => {
            const polarTarget = document.getElementById('polarchart');
            if (polarTarget) {
                const c = document.createElement('canvas');
                polarTarget.innerHTML = '';
                polarTarget.appendChild(c);
                new Chart(c, { type: 'polarArea', data: { labels: data.studentsPerClass.labels, datasets: [{ data: data.studentsPerClass.series, backgroundColor: ['#6366f1','#22c55e','#f59e0b','#ef4444','#06b6d4','#a78bfa','#84cc16','#f97316'] }] }, options: { plugins:{legend:{display:true}} } });
            }

            const appsTarget = document.getElementById('applications_chart');
            if (appsTarget) {
                const c2 = document.createElement('canvas');
                appsTarget.innerHTML = '';
                appsTarget.appendChild(c2);
                new Chart(c2, { type: 'bar', data: { labels: data.assignmentsTrend.labels, datasets: [{ label: 'Applications', data: data.assignmentsTrend.series, backgroundColor: '#6366f1' }] }, options: { responsive: true, plugins:{legend:{display:false}} } });
            }

            function renderCircle(id, roleData) {
                const el = document.getElementById(id);
                if (!el) return;
                const cv = document.createElement('canvas');
                el.innerHTML = '';
                el.appendChild(cv);
                new Chart(cv, { type: 'doughnut', data: { labels: ['Present','Absent','Late'], datasets: [{ data: [roleData.present, roleData.absent, roleData.late], backgroundColor: ['#22c55e','#ef4444','#f59e0b'] }] }, options: { plugins:{legend:{display:false}}, cutout: '70%' } });
            }
            if (data.attendanceToday) {
                renderCircle('circle_chart_1', data.attendanceToday.student);
                renderCircle('circle_chart_2', data.attendanceToday.teacher);
                renderCircle('circle_chart_3', data.attendanceToday.student);
                const el7 = document.getElementById('circle_chart_7');
                if (el7) {
                    const cv7 = document.createElement('canvas');
                    el7.innerHTML = '';
                    el7.appendChild(cv7);
                    new Chart(cv7, { type: 'doughnut', data: { labels: ['Sections','Subjects'], datasets: [{ data: [data.stats.sections, data.stats.subjects], backgroundColor: ['#06b6d4','#a78bfa'] }] }, options: { plugins:{legend:{display:false}}, cutout: '70%' } });
                }
            }

            if (data.structure) {
                const maleEl = document.getElementById('chart_male');
                if (maleEl) {
                    const cm = document.createElement('canvas');
                    maleEl.innerHTML = '';
                    maleEl.appendChild(cm);
                    new Chart(cm, { type: 'doughnut', data: { labels: ['Male','Other'], datasets: [{ data: [data.structure.male || 0, (data.structure.female || 0)], backgroundColor: ['#06b6d4','#e5e7eb'] }] }, options: { plugins:{legend:{display:false}}, cutout: '70%' } });
                }
                const femaleEl = document.getElementById('chart_female');
                if (femaleEl) {
                    const cf = document.createElement('canvas');
                    femaleEl.innerHTML = '';
                    femaleEl.appendChild(cf);
                    new Chart(cf, { type: 'doughnut', data: { labels: ['Female','Other'], datasets: [{ data: [data.structure.female || 0, (data.structure.male || 0)], backgroundColor: ['#a78bfa','#e5e7eb'] }] }, options: { plugins:{legend:{display:false}}, cutout: '70%' } });
                }
            }

            const activitiesList = document.createElement('ul');
            activitiesList.className = 'list-unstyled mb-0';
            if (Array.isArray(data.recentActivities)) {
                activitiesList.innerHTML = data.recentActivities.map(a => `
                    <li class="d-flex align-items-center justify-content-between py-1 border-bottom">
                        <span><span class="badge bg-light text-dark me-2">${a.type}</span>${a.text}</span>
                        <span class="text-muted">${a.time_formatted}</span>
                    </li>
                `).join('');
                const activitiesContainer = document.querySelector('.card.shadow.flex-fill .card-body');
                if (activitiesContainer) {
                    activitiesContainer.innerHTML = '';
                    activitiesContainer.appendChild(activitiesList);
                }
            }
        })
        .catch(err => console.error('Teacher dashboard data error', err));
});
</script>
@endpush
