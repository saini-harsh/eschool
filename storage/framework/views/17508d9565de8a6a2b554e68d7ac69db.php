<?php
$institution = Auth::guard('institution')->user();
?>
<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="<?php echo e(route('institution.dashboard')); ?>" class="logo logo-normal">
                <?php if($institution->logo): ?>
                    <img src="<?php echo e(asset($institution->logo)); ?>" alt="Institution Logo" style="max-height: 40px;">
                <?php else: ?>
                    <img src="<?php echo e(asset('/institutionpanel/img/logo.png')); ?>" alt="Logo">
                <?php endif; ?>
            </a>

            <!-- Logo Small -->
            <a href="<?php echo e(route('institution.dashboard')); ?>" class="logo-small">
                <?php if($institution->logo): ?>
                    <img src="<?php echo e(asset($institution->logo)); ?>" alt="Institution Logo" style="max-height: 30px;">
                <?php else: ?>
                    <img src="<?php echo e(asset('/institutionpanel/img/logo-small.png')); ?>" alt="Logo">
                <?php endif; ?>
            </a>

            <!-- Logo Dark -->
            <a href="<?php echo e(route('institution.dashboard')); ?>" class="dark-logo">
                <img src="<?php echo e(asset('/institutionpanel/img/logo-white.svg')); ?>" alt="Logo">
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
                            <a href="javascript:void(0);"
                                class="<?php echo e(request()->routeIs('institution.dashboard*') ? 'active subdrop' : ''); ?>">
                                <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="<?php echo e(route('institution.dashboard')); ?>"
                                        class="<?php echo e(request()->routeIs('institution.dashboard') ? 'active' : ''); ?>">Overview</a>
                                </li>
                            </ul>
                        </li>



                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(request()->routeIs('institution.institutions*') || request()->routeIs('institution.teachers*') || request()->routeIs('institution.students*') || request()->routeIs('institution.nonworkingstaff*') || request()->routeIs('institution.attendance*') ? 'active subdrop' : ''); ?>">
                                <i class="ti ti-layout-dashboard"></i><span>Administration</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.teachers*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.teachers.index')); ?>">
                                        <i class="ti ti-users-group"></i><span>Teachers</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.students*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.students.index')); ?>">
                                        <i class="ti ti-users-group"></i><span>Students</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.nonworkingstaff*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.nonworkingstaff.index')); ?>">
                                        <i class="ti ti-users-group"></i><span>Non-Working Staff</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.attendance*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.attendance')); ?>">
                                        <i class="ti ti-activity"></i><span>Attendance</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(request()->routeIs('institution.classes*') || request()->routeIs('institution.sections*') || request()->routeIs('institution.subjects*') || request()->routeIs('institution.academic.assign-teacher*') || request()->routeIs('institution.assign-subject*') || request()->routeIs('institution.academic.calendar*') || request()->routeIs('institution.events*') ? 'active subdrop' : ''); ?>">
                                <i class="ti ti-layout-dashboard"></i><span>Academics</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>

                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.classes*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.classes.index')); ?>">
                                        <i class="ti ti-report"></i><span>Class</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.sections*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.sections.index')); ?>">
                                        <i class="ti ti-menu-2"></i><span>Section</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.subjects*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.subjects.index')); ?>">
                                        <i class="ti ti-book"></i><span>Subject</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.academic.assign-teacher*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.academic.assign-teacher.index')); ?>">
                                        <i class="ti ti-user"></i><span>Assign Class Teacher</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.assign-subject*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.assign-subject.index')); ?>">
                                        <i class="ti ti-checks"></i><span>Assign Subject</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.assignments*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.assignments.index')); ?>">
                                        <i class="ti ti-report"></i><span>Assignments</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.academic.calendar*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.academic.calendar.index')); ?>">
                                        <i class="ti ti-calendar"></i><span>Calendar</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.events*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.events.index')); ?>">
                                        <i class="ti ti-list"></i><span>Event Management</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(request()->routeIs('institution.rooms*') || request()->routeIs('institution.invigilators*') || request()->routeIs('institution.exam-management*') ? 'active subdrop' : ''); ?>">
                                <i class="ti ti-layout-dashboard"></i><span>Exam Management</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="" href="<?php echo e(route('institution.exam-management.exams')); ?>">
                                        <i class="ti ti-home"></i><span>Exams</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.exam-management.exam-type') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.exam-management.exam-type')); ?>">
                                        <i class="ti ti-home"></i><span>Exam Type</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.exam-management.exam-setup') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.exam-management.exam-setup')); ?>">
                                        <i class="ti ti-home"></i><span>Exam Setup</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="" href="<?php echo e(route('institution.exam-management.rooms.index')); ?>">
                                        <i class="ti ti-home"></i><span>Exam Rooms Setup</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class=""
                                        href="<?php echo e(route('institution.exam-management.invigilator.index')); ?>">
                                        <i class="ti ti-users"></i><span>Invigilator / Assign Teacher</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(request()->routeIs('institution.email-sms*') ? 'active subdrop' : ''); ?>">
                                <i class="ti ti-layout-dashboard"></i><span>Communication</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.email-sms*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.email-sms.index')); ?>">
                                        <i class="ti ti-menu-2"></i><span>Email / Sms</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(request()->routeIs('institution.routines*') || request()->routeIs('institution.lesson-plans*') ? 'active subdrop' : ''); ?>">
                                <i class="ti ti-layout-dashboard"></i><span>Routine</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.routines*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.routines.index')); ?>">
                                        <i class="ti ti-calendar-event"></i><span>Class Routine</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.lesson-plans*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.lesson-plans.index')); ?>">
                                        <i class="ti ti-calendar-event"></i><span>Lesson Plan</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(request()->routeIs('institution.fee-structure*') || request()->routeIs('institution.payments*') ? 'active subdrop' : ''); ?>">
                                <i class="ti ti-credit-card"></i><span>Fee Management</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.fee-structure*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.fee-structure.index')); ?>">
                                        <i class="ti ti-receipt"></i><span>Fee Structure</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="<?php echo e(request()->routeIs('institution.payments*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('institution.payments.index')); ?>">
                                        <i class="ti ti-history"></i><span>Payment History</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="">
                            <a class="<?php echo e(request()->routeIs('institution.settings*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('institution.settings.index')); ?>">
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
                        src="<?php echo e($institution->logo ? asset($institution->logo) : ''); ?>" alt=""></a>
                <div>
                    <h6 class="fs-14 fw-semibold mb-1"><a href="#"><?php echo e($institution->name); ?></a></h6>
                    <p class="fs-13 mb-0"><a href="mailto:<?php echo e($institution->email); ?>" class="__cf_email__"
                            data-cfemail="<?php echo e($institution->email); ?>"><?php echo e($institution->email); ?></a></p>
                </div>
            </div>
        </div>
        <div class="p-3 pt-0">
            <a href="<?php echo e(route('logout')); ?>" class="btn btn-danger w-100"><i
                    class="ti ti-logout-2 me-1"></i>Logout</a>
        </div>
    </div>
    <!-- End Sidenav Menu -->

</div>
<!-- Sidenav Menu End -->
<?php /**PATH E:\eschool\resources\views////elements/institution/left-side-bar.blade.php ENDPATH**/ ?>