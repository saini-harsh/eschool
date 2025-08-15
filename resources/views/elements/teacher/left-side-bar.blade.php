<!-- Sidenav Menu Start -->
<div class="sidebar" id="sidebar">

    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="index.html" class="logo logo-normal">
                <img src="{{ asset('/adminpanel/img/logo.png') }}" alt="Logo">
            </a>

            <!-- Logo Small -->
            <a href="index.html" class="logo-small">
                <img src="{{ asset('/adminpanel/img/logo-small.png') }}" alt="Logo">
            </a>

            <!-- Logo Dark -->
            <a href="index.html" class="dark-logo">
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
                <li class="menu-title"><span>Main Menu</span></li>
                <li>
                    <ul>
                        <li class="">
                            <a href="{{ route('teacher.dashboard') }}">
                                <i class="ti  ti-layout-dashboard"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="{{ route('teacher.attendance') }}">
                                <i class="ti ti-users-group"></i><span>Attendance</span>
                            </a>
                        </li>
                        <li class="">
                            <a href="">
                                <i class="ti ti-book"></i><span>Assignment</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="sidebar-footer">
            <div class="bg-light p-2 rounded d-flex align-items-center">
                <a href="#" class="avatar avatar-md me-2"><img
                        src="{{ asset('/adminpanel/img/users/avatar-2.jpg') }}" alt=""></a>
                <div>
                    <h6 class="fs-14 fw-semibold mb-1"><a href="#">sdgfdsf</a></h6>
                    <p class="fs-13 mb-0"><a href="https://dleohr.dreamstechnologies.com/cdn-cgi/l/email-protection"
                            class="__cf_email__"
                            data-cfemail="8aebeee7e3e4caeff2ebe7fae6efa4e9e5e7">[email&#160;protected]</a></p>
                </div>
            </div>
        </div>
        <div class="p-3 pt-0">
            <a href="login.html" class="btn btn-danger w-100"><i class="ti ti-logout-2 me-1"></i>Logout</a>
        </div>
    </div>

</div>
<!-- Sidenav Menu End -->
