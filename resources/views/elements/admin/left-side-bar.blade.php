<?php
$admin = @\App\Models\Admin::where('id', '=', 1)->first();
?>
<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="{{ route('admin.dashboard') }}" class="logo logo-normal">
                <img src="{{ asset('/adminpanel/img/logo.png') }}" alt="Logo">
            </a>

            <!-- Logo Small -->
            <a href="{{ route('admin.dashboard') }}" class="logo-small">
                <img src="{{ asset('/adminpanel/img/logo-small.png') }}" alt="Logo">
            </a>

            <!-- Logo Dark -->
            <a href="{{ route('admin.dashboard') }}" class="dark-logo">
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
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="{{ request()->routeIs('admin.dashboard*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="#"
                                        class="{{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">Dashboard</a>
                                </li>
                            </ul>
                        </li>
                        <!-- <li class="submenu">
                            <a href="javascript:void(0);" class="">
                                <i class="ti ti-apps"></i><span>Applications</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="chat.html" class="">Chat</a></li>
                                <li class="submenu submenu-two">
                                    <a href="#" class="">Calls<span
                                            class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="voice-call.html" class="">Voice Call</a></li>
                                        <li><a href="video-call.html" class="">Video Call</a></li>
                                        <li><a href="outgoing-call.html" class="">Outgoing Call</a></li>
                                        <li><a href="incoming-call.html" class="">Incoming Call</a></li>
                                        <li><a href="call-history.html" class="">Call History</a></li>
                                    </ul>
                                </li>
                                <li><a href="calendar.html" class="">Calendar</a></li>
                                <li><a href="contacts.html" class="">Contacts</a></li>
                                <li><a href="email.html" class="">Email</a></li>
                                <li class="submenu submenu-two">
                                    <a href="#" class="">Invoices<span
                                            class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="invoice.html" class="">Invoices</a></li>
                                        <li><a href="invoice-details.html" class="">Invoice Details</a></li>
                                    </ul>
                                </li>
                                <li><a href="todo.html" class="">To Do</a></li>
                                <li><a href="notes.html" class="">Notes</a></li>
                                <li><a href="kanban-view.html" class="">Kanban Board</a></li>
                                <li><a href="file-manager.html" class="">File Manager</a></li>
                                <li><a href="social-feed.html" class="">Social Feed</a></li>
                                <li><a href="search-list.html" class="">Search Result</a></li>
                            </ul>
                        </li> -->
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="{{ request()->routeIs('admin.institutions*') || request()->routeIs('admin.teachers*') || request()->routeIs('admin.students*') || request()->routeIs('admin.nonworkingstaff*') || request()->routeIs('admin.attendance*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Administration</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>

                                <li class="">
                                    <a class="{{ request()->routeIs('admin.institutions*') ? 'active' : '' }}"
                                        href="{{ route('admin.institutions.index') }}">
                                        <i class="ti ti-building-community"></i><span>Insitutions</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.teachers*') ? 'active' : '' }}"
                                        href="{{ route('admin.teachers.index') }}">
                                        <i class="ti ti-users-group"></i><span>Teachers</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.students*') ? 'active' : '' }}"
                                        href="{{ route('admin.students.index') }}">
                                        <i class="ti ti-users-group"></i><span>Students</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.nonworkingstaff*') ? 'active' : '' }}"
                                        href="{{ route('admin.nonworkingstaff.index') }}">
                                        <i class="ti ti-users-group"></i><span>Non-Working Staff</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.attendance*') ? 'active' : '' }}"
                                        href="{{ route('admin.attendance') }}">
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
                                class="{{ request()->routeIs('admin.classes*') || request()->routeIs('admin.sections*') || request()->routeIs('admin.subjects*') || request()->routeIs('admin.academic.assign-teacher*') || request()->routeIs('admin.assign-subject*') || request()->routeIs('admin.assignments*') || request()->routeIs('admin.academic.calendar*') || request()->routeIs('admin.events*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Academics</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>

                                <li class="">
                                    <a class="{{ request()->routeIs('admin.classes*') ? 'active' : '' }}"
                                        href="{{ route('admin.classes.index') }}">
                                        <i class="ti ti-report"></i><span>Class</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.sections*') ? 'active' : '' }}"
                                        href="{{ route('admin.sections.index') }}">
                                        <i class="ti ti-menu-2"></i><span>Section</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.subjects*') ? 'active' : '' }}"
                                        href="{{ route('admin.subjects.index') }}">
                                        <i class="ti ti-book"></i><span>Subject</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.academic.assign-teacher*') ? 'active' : '' }}"
                                        href="{{ route('admin.academic.assign-teacher.index') }}">
                                        <i class="ti ti-user"></i><span>Assign Class Teacher</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.assign-subject*') ? 'active' : '' }}"
                                        href="{{ route('admin.assign-subject.index') }}">
                                        <i class="ti ti-checks"></i><span>Assign Subject</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.assignments*') ? 'active' : '' }}"
                                        href="{{ route('admin.assignments.index') }}">
                                        <i class="ti ti-report"></i><span>Assignments</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.academic.calendar*') ? 'active' : '' }}"
                                        href="{{ route('admin.academic.calendar.index') }}">
                                        <i class="ti ti-calendar"></i><span>Calendar</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.events*') ? 'active' : '' }}"
                                        href="{{ route('admin.events.index') }}">
                                        <i class="ti ti-list"></i><span>Event Management</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <!-- <li class="menu-title"><span>Room Management</span></li> -->
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="{{ request()->routeIs('admin.email-sms*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Communication</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.email-sms*') ? 'active' : '' }}"
                                        href="{{ route('admin.email-sms.index') }}">
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
                                class="{{ request()->routeIs('admin.rooms*') || request()->routeIs('admin.invigilators*') || request()->routeIs('admin.exam-management*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Exam Management</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="" href="{{ route('admin.exam-management.exams') }}">
                                        <i class="ti ti-home"></i><span>Exams</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.exam-management.exam-type') ? 'active' : '' }}"
                                        href="{{ route('admin.exam-management.exam-type') }}">
                                        <i class="ti ti-home"></i><span>Exam Type</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.exam-management.exam-setup') ? 'active' : '' }}"
                                        href="{{ route('admin.exam-management.exam-setup') }}">
                                        <i class="ti ti-home"></i><span>Exam Setup</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.rooms*') ? 'active' : '' }}"
                                        href="{{ route('admin.rooms.index') }}">
                                        <i class="ti ti-home"></i><span>Class Rooms</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.invigilators*') ? 'active' : '' }}"
                                        href="#">
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
                                class="{{ request()->routeIs('admin.routines*') || request()->routeIs('admin.lesson-plans*') ? 'active subdrop' : '' }}">
                                <i class="ti ti-layout-dashboard"></i><span>Routine</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.routines*') ? 'active' : '' }}"
                                        href="{{ route('admin.routines.index') }}">
                                        <i class="ti ti-calendar-event"></i><span>Class Routine</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a class="{{ request()->routeIs('admin.lesson-plans*') ? 'active' : '' }}"
                                        href="{{ route('admin.lesson-plans.index') }}">
                                        <i class="ti ti-calendar-event"></i><span>Lesson Plan</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li class="">
                            <a class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}"
                                href="{{ route('admin.settings.index') }}">
                                <i class="ti ti-settings"></i><span>Settings</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!---<li class="menu-title"><span>Authentication</span></li>
                <li>
                    <ul>
                        <li>
                            <a href="login.html">
                                <i class="ti ti-login"></i><span>Login</span>
                            </a>
                        </li>
                        <li>
                            <a href="register.html">
                                <i class="ti ti-report"></i><span>Register</span>
                            </a>
                        </li>
                        <li>
                            <a href="forgot-password.html">
                                <i class="ti ti-lock-exclamation"></i><span>Forgot Password</span>
                            </a>
                        </li>
                        <li>
                            <a href="reset-password.html">
                                <i class="ti ti-restore"></i><span>Reset Password</span>
                            </a>
                        </li>
                        <li>
                            <a href="email-verification.html">
                                <i class="ti ti-mail-check"></i><span>Email Verification</span>
                            </a>
                        </li>
                        <li>
                            <a href="two-step-verification.html">
                                <i class="ti ti-discount-check"></i><span>2 Step Verification</span>
                            </a>
                        </li>
                        <li>
                            <a href="lock-screen.html">
                                <i class="ti ti-lock-square-rounded"></i><span>Lock Screen</span>
                            </a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-exclamation-mark-off"></i><span>Error Pages</span><span
                                    class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="error-404.html">404 Error</a></li>
                                <li><a href="error-500.html">500 Error</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="menu-title"><span>UI Interface</span></li>
                <li>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="">
                                <i class="ti ti-chart-pie"></i><span>Base UI</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="ui-accordion.html" class="">Accordion</a></li>
                                <li><a href="ui-alerts.html" class="">Alerts</a></li>
                                <li><a href="ui-avatar.html" class="">Avatar</a></li>
                                <li><a href="ui-badges.html" class="">Badges</a></li>
                                <li><a href="ui-breadcrumb.html" class="">Breadcrumb</a></li>
                                <li><a href="ui-buttons.html" class="">Buttons</a></li>
                                <li><a href="ui-buttons-group.html" class="">Button Group</a></li>
                                <li><a href="ui-cards.html" class="">Card</a></li>
                                <li><a href="ui-carousel.html" class="">Carousel</a></li>
                                <li><a href="ui-collapse.html" class="">Collapse</a></li>
                                <li><a href="ui-dropdowns.html" class="">Dropdowns</a></li>
                                <li><a href="ui-ratio.html" class="">Ratio</a></li>
                                <li><a href="ui-grid.html" class="">Grid</a></li>
                                <li><a href="ui-images.html" class="">Images</a></li>
                                <li><a href="ui-links.html" class="">Links</a></li>
                                <li><a href="ui-list-group.html" class="">List Group</a></li>
                                <li><a href="ui-modals.html" class="">Modals</a></li>
                                <li><a href="ui-offcanvas.html" class="">Offcanvas</a></li>
                                <li><a href="ui-pagination.html" class="">Pagination</a></li>
                                <li><a href="ui-placeholders.html" class="">Placeholders</a></li>
                                <li><a href="ui-popovers.html" class="">Popovers</a></li>
                                <li><a href="ui-progress.html" class="">Progress</a></li>
                                <li><a href="ui-scrollspy.html" class="">Scrollspy</a></li>
                                <li><a href="ui-spinner.html" class="">Spinner</a></li>
                                <li><a href="ui-nav-tabs.html" class="">Tabs</a></li>
                                <li><a href="ui-toasts.html" class="">Toasts</a></li>
                                <li><a href="ui-tooltips.html" class="">Tooltips</a></li>
                                <li><a href="ui-typography.html" class="">Typography</a></li>
                                <li><a href="ui-utilities.html" class="">Utilities</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="">
                                <i class="ti ti-radar"></i><span>Advanced UI</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="extended-dragula.html" class="">Dragula</a></li>
                                <li><a href="ui-clipboard.html" class="">Clipboard</a></li>
                                <li><a href="ui-rangeslider.html" class="">Range Slider</a></li>
                                <li><a href="ui-sweetalerts.html" class="">Sweet Alerts</a></li>
                                <li><a href="ui-lightbox.html" class="">Lightbox</a></li>
                                <li><a href="ui-rating.html" class="">Rating</a></li>
                                <li><a href="ui-scrollbar.html" class="">Scrollbar</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="">
                                <i class="ti ti-forms"></i><span>Forms</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="submenu submenu-two">
                                    <a href="javascript:void(0);" class="">Form Elements<span
                                            class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="form-basic-inputs.html" class="">Basic Inputs</a></li>
                                        <li><a href="form-checkbox-radios.html" class="">Checkbox & Radios</a>
                                        </li>
                                        <li><a href="form-input-groups.html" class="">Input Groups</a></li>
                                        <li><a href="form-grid-gutters.html" class="">Grid & Gutters</a></li>
                                        <li><a href="form-mask.html" class="">Input Masks</a></li>
                                        <li><a href="form-fileupload.html" class="">File Uploads</a></li>
                                    </ul>
                                </li>
                                <li class="submenu submenu-two">
                                    <a href="javascript:void(0);" class="">Layouts<span
                                            class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="form-horizontal.html" class="">Horizontal Form</a></li>
                                        <li><a href="form-vertical.html" class="">Vertical Form</a></li>
                                        <li><a href="form-floating-labels.html" class="">Floating Labels</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="form-validation.html" class="">Form Validation</a></li>
                                <li><a href="form-select2.html" class="">Select2</a></li>
                                <li><a href="form-wizard.html" class="">Form Wizard</a></li>
                                <li><a href="form-pickers.html" class="">Form Picker</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="">
                                <i class="ti ti-table-row"></i><span>Tables</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="tables-basic.html" class="">Basic Tables </a></li>
                                <li><a href="data-tables.html" class="">Data Table </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="">
                                <i class="ti ti-chart-donut"></i>
                                <span>Charts</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="chart-apex.html" class="">Apex Charts</a></li>
                                <li><a href="chart-c3.html" class="">Chart C3</a></li>
                                <li><a href="chart-js.html" class="">Chart Js</a></li>
                                <li><a href="chart-morris.html" class="">Morris Charts</a></li>
                                <li><a href="chart-flot.html" class="">Flot Charts</a></li>
                                <li><a href="chart-peity.html" class="">Peity Charts</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);" class="">
                                <i class="ti ti-icons"></i>
                                <span>Icons</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="icon-fontawesome.html" class="">Fontawesome Icons</a></li>
                                <li><a href="icon-tabler.html" class="">Tabler Icons</a></li>
                                <li><a href="icon-bootstrap.html" class="">Bootstrap Icons</a></li>
                                <li><a href="icon-remix.html" class="">Remix Icons</a></li>
                                <li><a href="icon-feather.html" class="">Feather Icons</a></li>
                                <li><a href="icon-ionic.html" class="">Ionic Icons</a></li>
                                <li><a href="icon-material.html" class="">Material Icons</a></li>
                                <li><a href="icon-pe7.html" class="">Pe7 Icons</a></li>
                                <li><a href="icon-simpleline.html" class="">Simpleline Icons</a></li>
                                <li><a href="icon-themify.html" class="">Themify Icons</a></li>
                                <li><a href="icon-weather.html" class="">Weather Icons</a></li>
                                <li><a href="icon-typicons.html" class="">Typicons Icons</a></li>
                                <li><a href="icon-flag.html" class="">Flag Icons</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="menu-title"><span>Help</span></li>
                <li>
                    <ul>
                        <li>
                            <a href="javascript:void(0);"><i
                                    class="ti ti-file-dots"></i><span>Documentation</span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);"><i
                                    class="ti ti-status-change"></i><span>Changelog</span><span
                                    class="badge bg-danger ms-2 badge-md rounded-2 fs-12 fw-medium">v2.0</span></a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="ti ti-versions"></i><span>Multi Level</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="javascript:void(0);">Multilevel 1</a></li>
                                <li class="submenu submenu-two">
                                    <a href="javascript:void(0);">Multilevel 2<span
                                            class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="javascript:void(0);">Multilevel 2.1</a></li>
                                        <li class="submenu submenu-two submenu-three">
                                            <a href="javascript:void(0);">Multilevel 2.2<span
                                                    class="menu-arrow inside-submenu inside-submenu-two"></span></a>
                                            <ul>
                                                <li><a href="javascript:void(0);">Multilevel 2.2.1</a></li>
                                                <li><a href="javascript:void(0);">Multilevel 2.2.2</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0);">Multilevel 3</a></li>
                            </ul>
                        </li>
                    </ul>
                </li> -->
            </ul>
        </div>
        <div class="sidebar-footer">
            <div class="bg-light p-2 rounded d-flex align-items-center">
                <a href="#" class="avatar avatar-md me-2"><img
                        src="{{ $admin->logo ? asset($admin->logo) : '' }}" alt=""></a>
                <div>
                    <h6 class="fs-14 fw-semibold mb-1"><a href="#">{{ $admin->name }}</a></h6>
                    <p class="fs-13 mb-0"><a href="mailto:{{ $admin->email }}" class="__cf_email__"
                            data-cfemail="{{ $admin->email }}">{{ $admin->email }}</a></p>
                </div>
            </div>
        </div>
        <div class="p-3 pt-0">
            <a href="{{ route('logout') }}" class="btn btn-danger w-100"><i
                    class="ti ti-logout-2 me-1"></i>Logout</a>
        </div>
    </div>

</div>
<!-- Sidenav Menu End -->
