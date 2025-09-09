<?php
    $institution = Auth::guard('institution')->user();
?>
<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="{{ route('institution.dashboard') }}" class="logo logo-normal">
                @if($institution->logo)
                    <img src="{{ asset($institution->logo) }}" alt="Institution Logo" style="max-height: 40px;">
                @else
                    <img src="{{ asset('/adminpanel/img/logo.png') }}" alt="Logo">
                @endif
            </a>

            <!-- Logo Small -->
            <a href="{{ route('institution.dashboard') }}" class="logo-small">
                @if($institution->logo)
                    <img src="{{ asset($institution->logo) }}" alt="Institution Logo" style="max-height: 30px;">
                @else
                    <img src="{{ asset('/adminpanel/img/logo-small.png') }}" alt="Logo">
                @endif
            </a>

            <!-- Logo Dark -->
            <a href="{{ route('institution.dashboard') }}" class="dark-logo">
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
                            <a href="javascript:void(0);" class="{{ request()->routeIs('institution.dashboard*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="{{ route('institution.dashboard') }}" class="{{ request()->routeIs('institution.dashboard') ? 'active' : '' }}">Overview</a></li>
                            </ul>
                        </li>



                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs('institution.institutions*') || request()->routeIs('institution.teachers*') || request()->routeIs('institution.students*') || request()->routeIs('institution.nonworkingstaff*') || request()->routeIs('institution.attendance*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Administration</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="{{ request()->routeIs('institution.teachers*') ? 'active' : '' }}" href="{{ route('institution.teachers.index') }}">
                                        <i class="ti ti-users-group"></i><span>Teachers</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('institution.students*') ? 'active' : '' }}" href="{{ route('institution.students.index') }}">
                                        <i class="ti ti-users-group"></i><span>Students</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('institution.nonworkingstaff*') ? 'active' : '' }}" href="{{ route('institution.nonworkingstaff.index') }}">
                                        <i class="ti ti-users-group"></i><span>Non-Working Staff</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li>
                 {{-- <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs('institution.email-sms*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Communication</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="{{ request()->routeIs('institution.email-sms*') ? 'active' : '' }}" href="{{ route('institution.email-sms.index') }}">
                                        <i class="ti ti-menu-2"></i><span>Email / Sms</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> --}}
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="{{ request()->routeIs('institution.classes*') || request()->routeIs('institution.sections*') || request()->routeIs('institution.subjects*') || request()->routeIs('institution.academic.assign-teacher*') || request()->routeIs('institution.academic.calendar*') || request()->routeIs('institution.events*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Academics</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>

                            <li class="">
                                <a class="{{ request()->routeIs('institution.classes*') ? 'active' : '' }}" href="{{ route('institution.classes.index')}}">
                                    <i class="ti ti-report"></i><span>Class</span>
                                </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('institution.sections*') ? 'active' : '' }}" href="{{ route('institution.sections.index') }}">
                                        <i class="ti ti-menu-2"></i><span>Section</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('institution.subjects*') ? 'active' : '' }}" href="{{ route('institution.subjects.index') }}">
                                        <i class="ti ti-book"></i><span>Subject</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('institution.academic.assign-teacher*') ? 'active' : '' }}" href="{{ route('institution.academic.assign-teacher.index') }}">
                                        <i class="ti ti-user"></i><span>Assign Class Teacher</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('institution.academic.calendar*') ? 'active' : '' }}" href="{{ route('institution.academic.calendar.index') }}">
                                        <i class="ti ti-calendar"></i><span>Calendar</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('institution.events*') ? 'active' : '' }}" href="{{ route('institution.events.index') }}">
                                        <i class="ti ti-list"></i><span>Event Management</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
        <div class="sidebar-footer">
            <div class="bg-light p-2 rounded d-flex align-items-center">
                <a href="#" class="avatar avatar-md me-2"><img
                        src="{{ $institution->logo ? asset($institution->logo) : '' }}" alt=""></a>
                <div>
                    <h6 class="fs-14 fw-semibold mb-1"><a href="#">{{ $institution->name }}</a></h6>
                    <p class="fs-13 mb-0"><a href="mailto:{{ $institution->email }}"
                            class="__cf_email__"
                            data-cfemail="{{ $institution->email }}">{{ $institution->email }}</a></p>
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
