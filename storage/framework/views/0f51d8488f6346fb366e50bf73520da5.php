<?php
    $student = Auth::guard('student')->user();
?>
<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="<?php echo e(route('student.dashboard')); ?>" class="logo logo-normal">
                <?php if($student->photo): ?>
                    <img src="<?php echo e(asset($student->photo)); ?>" alt="Student Logo" style="max-height: 40px;">
                <?php else: ?>
                    <img src="<?php echo e(asset('/adminpanel/img/logo.png')); ?>" alt="Logo">
                <?php endif; ?>
            </a>

            <!-- Logo Small -->
            <a href="<?php echo e(route('student.dashboard')); ?>" class="logo-small">
                <?php if($student->photo): ?>
                    <img src="<?php echo e(asset($student->photo)); ?>" alt="Student Logo" style="max-height: 30px;">
                <?php else: ?>
                    <img src="<?php echo e(asset('/adminpanel/img/logo-small.png')); ?>" alt="Logo">
                <?php endif; ?>
            </a>

            <!-- Logo Dark -->
            <a href="<?php echo e(route('student.dashboard')); ?>" class="dark-logo">
                <img src="<?php echo e(asset('/adminpanel/img/logo-white.svg')); ?>" alt="Logo">
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
                            <a href="javascript:void(0);" class="<?php echo e(request()->routeIs('student.dashboard*') ? 'active subdrop' : ''); ?>">
                                <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="<?php echo e(route('student.dashboard')); ?>" class="<?php echo e(request()->routeIs('student.dashboard') ? 'active' : ''); ?>">Overview</a></li>
                            </ul>
                        </li>



                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="">
                            <a class="<?php echo e(request()->routeIs('student.attendance*') ? 'active' : ''); ?>" href="<?php echo e(route('student.attendance')); ?>">
                                <i class="ti ti-file"></i><span>Attendance</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="">
                                <i class="ti ti-layout-dashboard"></i><span>Academics</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('student.routine*') ? 'active' : ''); ?>" href="<?php echo e(route('student.routine.index')); ?>">
                                        <i class="ti ti-calendar-event"></i><span>Routine</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('student.assignments*') ? 'active' : ''); ?>" href="<?php echo e(route('student.assignments.index')); ?>">
                                        <i class="ti ti-report"></i><span>Assignments</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('student.events*') ? 'active' : ''); ?>" href="<?php echo e(route('student.events.index')); ?>">
                                        <i class="ti ti-calendar-event"></i><span>Events</span>
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
                                <i class="ti ti-layout-dashboard"></i><span>Examination</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="" href="">
                                        <i class="ti ti-report"></i><span>Routine</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="" href="">
                                        <i class="ti ti-report"></i><span>Progress Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="">
                            <a class="" href="">
                                <i class="ti ti-file"></i><span>Notices</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="">
                            <a class="<?php echo e(request()->routeIs('student.settings*') ? 'active' : ''); ?>" href="<?php echo e(route('student.settings.index')); ?>">
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
                        src="<?php echo e($student->photo ? asset($student->photo) : ''); ?>" alt=""></a>
                <div>
                    <h6 class="fs-14 fw-semibold mb-1"><a href="#"><?php echo e($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name); ?></a></h6>
                    <p class="fs-13 mb-0"><a href="mailto:<?php echo e($student->email); ?>"
                            class="__cf_email__"
                            data-cfemail="<?php echo e($student->email); ?>"><?php echo e($student->email); ?></a></p>
                </div>
            </div>
        </div>
        <div class="p-3 pt-0">
            <a href="<?php echo e(route('logout')); ?>" class="btn btn-danger w-100"><i class="ti ti-logout-2 me-1"></i>Logout</a>
        </div>
    </div>
    <!-- End Sidenav Menu -->

</div>
<!-- Sidenav Menu End -->
<?php /**PATH E:\eschool\resources\views////elements/student/left-side-bar.blade.php ENDPATH**/ ?>