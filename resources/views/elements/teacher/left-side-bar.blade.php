<?php
    $teacher = Auth::guard('teacher')->user();
?>
<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="{{ route('teacher.dashboard') }}" class="logo logo-normal">
                @if($teacher->profile_image)
                    <img src="{{ asset($teacher->profile_image) }}" alt="Teacher Logo" style="max-height: 40px;">
                @else
                    <img src="{{ asset('/adminpanel/img/logo.png') }}" alt="Logo">
                @endif
            </a>

            <!-- Logo Small -->
            <a href="{{ route('teacher.dashboard') }}" class="logo-small">
                @if($teacher->profile_image)
                    <img src="{{ asset($teacher->profile_image) }}" alt="Teacher Logo" style="max-height: 30px;">
                @else
                    <img src="{{ asset('/adminpanel/img/logo-small.png') }}" alt="Logo">
                @endif
            </a>

            <!-- Logo Dark -->
            <a href="{{ route('teacher.dashboard') }}" class="dark-logo">
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
                <li>
                    <ul>
                        <!-- Dashboard -->
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs('teacher.dashboard*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('teacher.dashboard') }}" class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">Overview</a></li>
                            </ul>
                        </li>



                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs('teacher.students*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Administration</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="{{ request()->routeIs('teacher.students*') ? 'active' : '' }}" href="{{ route('teacher.students.index') }}">
                                        <i class="ti ti-users-group"></i><span>Students</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="" href="">
                                        <i class="ti ti-files"></i><span>Classes</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="submenu">
                        <a href="javascript:void(0);" class="{{ request()->routeIs('teacher.routines*') || request()->routeIs('teacher.lesson-plans*') || request()->routeIs('teacher.assignments*') || request()->routeIs('teacher.classes*') || request()->routeIs('teacher.attendance*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Academics</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                              <li class="">
                                    <a class="{{ request()->routeIs('teacher.attendance*') ? 'active' : '' }}" href="{{ route('teacher.attendance') }}">
                                        <i class="ti ti-activity"></i><span>Attendance</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('teacher.classes*') ? 'active' : '' }}" href="{{ route('teacher.classes.index') }}">
                                        <i class="ti ti-files"></i><span>Classes</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('teacher.routines*') ? 'active' : '' }}" href="{{ route('teacher.routines.index') }}">
                                        <i class="ti ti-calendar-event"></i><span>Class Routine</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('teacher.lesson-plans*') ? 'active' : '' }}" href="{{ route('teacher.lesson-plans.index') }}">
                                        <i class="ti ti-calendar-event"></i><span>Lesson Plan</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('teacher.assignments*') ? 'active' : '' }}" href="{{ route('teacher.assignments.index') }}">
                                        <i class="ti ti-file-text"></i><span>Assignments</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="submenu">
                        <a href="javascript:void(0);" class="">
                                <i class="ti ti-layout-dashboard"></i><span>Exams</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="" href="">
                                        <i class="ti ti-calendar-event"></i><span>Marks Entry</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="" href="">
                                        <i class="ti ti-calendar-event"></i><span>Results</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <ul>
                        <li class="">
                            <a class="{{ request()->routeIs('teacher.settings*') ? 'active' : '' }}" href="{{ route('teacher.settings.index') }}">
                                <i class="ti ti-settings"></i><span>Settings</span>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
        <div class="sidebar-footer">
            <div class="bg-light p-2 rounded d-flex align-items-center">
                <a href="#" class="avatar avatar-md me-2"><img
                        src="{{ $teacher->profile_image ? asset($teacher->profile_image) : '' }}" alt=""></a>
                <div>
                    <h6 class="fs-14 fw-semibold mb-1"><a href="#">{{ $teacher->first_name . ' ' . $teacher->middle_name . ' ' . $teacher->last_name }}</a></h6>
                    <p class="fs-13 mb-0"><a href="mailto:{{ $teacher->email }}"
                            class="__cf_email__"
                            data-cfemail="{{ $teacher->email }}">{{ $teacher->email }}</a></p>
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
