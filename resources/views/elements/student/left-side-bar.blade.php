<?php
    $student = Auth::guard('student')->user();
    use App\Helpers\PermissionHelper;
?>
<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="{{ route('student.dashboard') }}" class="logo logo-normal">
                @if($student->photo)
                    <img src="{{ asset($student->photo) }}" alt="Student Logo" style="max-height: 40px;">
                @else
                    <img src="{{ asset('/adminpanel/img/logo.png') }}" alt="Logo">
                @endif
            </a>

            <!-- Logo Small -->
            <a href="{{ route('student.dashboard') }}" class="logo-small">
                @if($student->photo)
                    <img src="{{ asset($student->photo) }}" alt="Student Logo" style="max-height: 30px;">
                @else
                    <img src="{{ asset('/adminpanel/img/logo-small.png') }}" alt="Logo">
                @endif
            </a>

            <!-- Logo Dark -->
            <a href="{{ route('student.dashboard') }}" class="dark-logo">
                <img src="{{ asset('/adminpanel/img/logo-white.svg') }}" alt="Logo">
            </a>
        </div>
        <button class="sidenav-toggle-btn btn p-0" id="toggle_btn">
            <i class="ti ti-chevron-left-pipe"></i>
        </button>

        <!-- Sidebar Menu Close -->
        <button class="sidebar-close">
            <i class="ti ti-x align-middle"></i>
        </button>
    </div>
    <!-- End Logo -->

    <!-- Sidenav Menu -->
    <div class="sidebar-inner" data-simplebar>
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                @if(PermissionHelper::canShowMenu('dashboard'))
                <li>
                    <ul>
                        <!-- Dashboard -->
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs('student.dashboard*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'active' : '' }}">Overview</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                @endif

                @if(PermissionHelper::canShowMenu('attendance'))
                <li>
                    <ul>
                        <li class="">
                            <a class="{{ request()->routeIs('student.attendance*') ? 'active' : '' }}" href="{{ route('student.attendance') }}">
                                <i class="ti ti-file"></i><span>Attendance</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="">
                                <i class="ti ti-layout-dashboard"></i><span>Academics</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                @if(PermissionHelper::canShowMenu('class_routine'))
                                <li class="">
                                    <a class="{{ request()->routeIs('student.routine*') ? 'active' : '' }}" href="{{ route('student.routine.index') }}">
                                        <i class="ti ti-calendar-event"></i><span>Routine</span>
                                    </a>
                                </li>
                                @endif

                                @if(PermissionHelper::canShowMenu('assignments'))
                                <li class="">
                                    <a class="{{ request()->routeIs('student.assignments*') ? 'active' : '' }}" href="{{ route('student.assignments.index') }}">
                                        <i class="ti ti-report"></i><span>Assignments</span>
                                    </a>
                                </li>
                                @endif

                                <li class="">
                                    <a class="{{ request()->routeIs('student.events*') ? 'active' : '' }}" href="{{ route('student.events.index') }}">
                                        <i class="ti ti-calendar-event"></i><span>Events</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                @if(PermissionHelper::canShowMenu('exams') || PermissionHelper::canShowMenu('results'))
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs('student.exam*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Examination</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                @if(PermissionHelper::canShowMenu('exams'))
                                <li class="">
                                    <a class="{{ request()->routeIs('student.exams*') ? 'active' : '' }}" href="{{ route('student.exams.index') }}">
                                        <i class="ti ti-report"></i><span>Routine</span>
                                    </a>
                                </li>
                                @endif

                                @if(PermissionHelper::canShowMenu('results'))
                                <li class="">
                                    <a class="{{ request()->routeIs('student.coming-soon*') ? 'active' : '' }}" href="{{ route('student.coming-soon') }}">
                                        <i class="ti ti-report"></i><span>Progress Report</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </li>
                @endif

                @if(PermissionHelper::canShowMenu('settings'))
                <li>
                    <ul>
                        <li class="">
                            <a class="{{ request()->routeIs('student.settings*') ? 'active' : '' }}" href="{{ route('student.settings.index') }}">
                                <i class="ti ti-settings"></i><span>Settings</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

            </ul>
        </div>
        <div class="sidebar-footer">
            <div class="bg-light p-2 rounded d-flex align-items-center">
                <a href="#" class="avatar avatar-md me-2"><img
                        src="{{ $student->photo ? asset($student->photo) : '' }}" alt=""></a>
                <div>
                    <h6 class="fs-14 fw-semibold mb-1"><a href="#">{{ $student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name }}</a></h6>
                    <p class="fs-13 mb-0"><a href="mailto:{{ $student->email }}"
                            class="__cf_email__"
                            data-cfemail="{{ $student->email }}">{{ $student->email }}</a></p>
                </div>
            </div>
        </div>
        <div class="p-3 pt-0">
            <a href="{{ route('logout')}}" class="btn btn-danger w-100"><i class="ti ti-logout-2 me-1"></i>Logout</a>
        </div>
    </div>
    <!-- End Sidenav Menu -->

</div>
<!-- Sidenav Menu End -->
