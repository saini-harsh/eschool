 <!-- Topbar Start -->
 <header class="navbar-header">
     <div class="page-container topbar-menu">
         <div class="d-flex align-items-center gap-2">

             <!-- Logo -->
             <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo">

                 <!-- Logo Normal -->
                 <span class="logo-light">
                     <span class="logo-lg"><img src="<?php echo e(asset('/adminpanel/img/logo.svg')); ?>" alt="logo"></span>
                     <span class="logo-sm"><img src="<?php echo e(asset('/adminpanel/img/logo-small.svg')); ?>" alt="small logo"></span>
                 </span>

                 <!-- Logo Dark -->
                 <span class="logo-dark">
                     <span class="logo-lg"><img src="<?php echo e(asset('/adminpanel/img/logo-white.svg')); ?>" alt="dark logo"></span>
                 </span>
             </a>

             <!-- Sidebar Mobile Button -->
             <a id="mobile_btn" class="mobile-btn" href="#sidebar">
                 <i class="ti ti-menu-deep fs-24"></i>
             </a>

             <button class="sidenav-toggle-btn btn p-0" id="toggle_btn2">
                 <i class="ti ti-chevron-left-pipe"></i>
             </button>

             <!-- Search -->
             <div class="me-auto d-flex align-items-center header-search d-lg-flex d-none">
                 <div class="input-icon-start position-relative">
                     <span class="input-icon-addon">
                         <i class="ti ti-search"></i>
                     </span>
                     <input type="text" class="form-control" placeholder="Search Keyword">
                     <span class="input-icon-addon text-dark fs-18 d-inline-flex p-0 header-search-icon"><i
                             class="ti ti-command"></i></span>
                 </div>
             </div>

         </div>

         <div class="d-flex align-items-center">

             <!-- Search for Mobile -->
             <div class="header-item d-flex d-lg-none me-2">
                 <button class="topbar-link btn btn-icon" data-bs-toggle="modal" data-bs-target="#searchModal"
                     type="button">
                     <i class="ti ti-search fs-16"></i>
                 </button>
             </div>

             <!-- Flag -->
             <div class="header-item">
                 <div class="dropdown me-2">
                     <button class="topbar-link btn" data-bs-toggle="dropdown" data-bs-offset="0,24" type="button"
                         aria-haspopup="false" aria-expanded="false">
                         <img src="<?php echo e(asset('/adminpanel/img/flags/us.svg')); ?>" alt="Language" height="16">
                     </button>

                     <div class="dropdown-menu dropdown-menu-end">

                         <!-- item-->
                         <a href="javascript:void(0);" class="dropdown-item">
                             <img src="<?php echo e(asset('/adminpanel/img/flags/us.svg')); ?>" alt="" class="me-1"
                                 height="16"> <span class="align-middle">English</span>
                         </a>

                         <!-- item-->
                         <a href="javascript:void(0);" class="dropdown-item">
                             <img src="<?php echo e(asset('/adminpanel/img/flags/de.svg')); ?>" alt="" class="me-1"
                                 height="16"> <span class="align-middle">German</span>
                         </a>

                         <!-- item-->
                         <a href="javascript:void(0);" class="dropdown-item">
                             <img src="<?php echo e(asset('/adminpanel/img/flags/fr.svg')); ?>" alt="" class="me-1"
                                 height="16"> <span class="align-middle">French</span>
                         </a>

                         <!-- item-->
                         <a href="javascript:void(0);" class="dropdown-item">
                             <img src="<?php echo e(asset('/adminpanel/img/flags/ae.svg')); ?>" alt="" class="me-1"
                                 height="16"> <span class="align-middle">Arabic</span>
                         </a>

                     </div>
                 </div>
             </div>

             <!-- Full Screen -->
             <div class="header-item">
                 <div class="me-2">
                     <a href="javascript:void(0);" class="btn topbar-link" id="btnFullscreen"><i
                             class="ti ti-maximize fs-16"></i></a>
                 </div>
             </div>

             <!-- Light/Dark Mode Button -->
             <div class="header-item d-none d-sm-flex me-2">
                 <button class="topbar-link btn btn-icon topbar-link" id="light-dark-mode" type="button">
                     <i class="ti ti-moon fs-16"></i>
                 </button>
             </div>

             <!-- Calendar -->
             <div class="header-item">
                 <div class="me-2">
                     <a href="<?php echo e(route('admin.events.index')); ?>" class="btn topbar-link"><i
                             class="ti ti-calendar-star fs-16"></i></a>
                 </div>
             </div>

             <!-- Notification Dropdown -->
             <div class="header-item">
                 <div class="dropdown me-2">

                     <button class="topbar-link btn btn-icon topbar-link dropdown-toggle drop-arrow-none"
                         data-bs-toggle="dropdown" data-bs-offset="0,24" type="button" aria-haspopup="false"
                         aria-expanded="false">
                         <i class="ti ti-bell fs-16 animate-ring fs-16"></i>
                         <span class="notification-badge"></span>
                     </button>

                     <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg" style="min-height: 300px;">

                         <div class="p-2 border-bottom">
                             <div class="row align-items-center">
                                 <div class="col">
                                     <h6 class="m-0 fs-16 fw-semibold"> Notifications</h6>
                                 </div>
                             </div>
                         </div>

                         <!-- Notification Body -->
                         <div class="notification-body position-relative z-2 rounded-0" data-simplebar>

                             <!-- Item-->
                             <div class="dropdown-item notification-item py-3 text-wrap border-bottom"
                                 id="notification-1">
                                 <div class="d-flex">
                                     <div class="me-2 position-relative flex-shrink-0">
                                         <img src="<?php echo e(asset('/adminpanel/img/users/avatar-2.jpg')); ?>"
                                             class="avatar-md rounded-circle" alt="">
                                     </div>
                                     <div class="flex-grow-1">
                                         <p class="mb-0 fw-medium text-dark">Daniel Martinz</p>
                                         <p class="mb-1 text-wrap">
                                             <span class="fw-medium text-dark">Daniel Martinz</span> equested Sick
                                             Leave from May 28 2025 to May 29 2025
                                         </p>
                                         <div class="d-flex justify-content-between align-items-center">
                                             <span class="fs-12"><i class="ti ti-clock me-1"></i>4 min ago</span>
                                             <div
                                                 class="notification-action d-flex align-items-center float-end gap-2">
                                                 <a href="javascript:void(0);"
                                                     class="notification-read rounded-circle bg-danger"
                                                     data-bs-toggle="tooltip" title=""
                                                     data-bs-original-title="Make as Read"
                                                     aria-label="Make as Read"></a>
                                                 <button class="btn rounded-circle p-0"
                                                     data-dismissible="#notification-1">
                                                     <i class="ti ti-x"></i>
                                                 </button>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>

                             <!-- Item-->
                             <div class="dropdown-item notification-item py-3 text-wrap border-bottom"
                                 id="notification-2">
                                 <div class="d-flex">
                                     <div class="me-2 position-relative flex-shrink-0">
                                         <img src="<?php echo e(asset('/adminpanel/img/users/user-02.jpg')); ?>"
                                             class="avatar-md rounded-circle" alt="">
                                     </div>
                                     <div class="flex-grow-1">
                                         <p class="mb-0 fw-medium text-dark">Emily Clark</p>
                                         <p class="mb-1 text-wrap">
                                             Leave for <span class="fw-medium text-dark"> Emily Clark</span> has been
                                             approved.
                                         </p>
                                         <div class="d-flex justify-content-between align-items-center">
                                             <span class="fs-12"><i class="ti ti-clock me-1"></i>8 min ago</span>
                                             <div
                                                 class="notification-action d-flex align-items-center float-end gap-2">
                                                 <a href="javascript:void(0);"
                                                     class="notification-read rounded-circle bg-danger"
                                                     data-bs-toggle="tooltip" title=""
                                                     data-bs-original-title="Make as Read"
                                                     aria-label="Make as Read"></a>
                                                 <button class="btn rounded-circle p-0"
                                                     data-dismissible="#notification-2">
                                                     <i class="ti ti-x"></i>
                                                 </button>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>

                             <!-- Item-->
                             <div class="dropdown-item notification-item py-3 text-wrap border-bottom"
                                 id="notification-3">
                                 <div class="d-flex">
                                     <div class="me-2 position-relative flex-shrink-0">
                                         <img src="<?php echo e(asset('/adminpanel/img/users/user-04.jpg')); ?>"
                                             class="avatar-md rounded-circle" alt="">
                                     </div>
                                     <div class="flex-grow-1">
                                         <p class="mb-0 fw-medium text-dark"> David</p>
                                         <p class="mb-1 text-wrap">
                                             Leave request from <span class="fw-medium text-dark">David
                                                 Anderson</span>has been rejected.
                                         </p>
                                         <div class="d-flex justify-content-between align-items-center">
                                             <span class="fs-12"><i class="ti ti-clock me-1"></i>15 min ago</span>
                                             <div
                                                 class="notification-action d-flex align-items-center float-end gap-2">
                                                 <a href="javascript:void(0);"
                                                     class="notification-read rounded-circle bg-danger"
                                                     data-bs-toggle="tooltip" title=""
                                                     data-bs-original-title="Make as Read"
                                                     aria-label="Make as Read"></a>
                                                 <button class="btn rounded-circle p-0"
                                                     data-dismissible="#notification-3">
                                                     <i class="ti ti-x"></i>
                                                 </button>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>

                             <!-- Item-->
                             <div class="dropdown-item notification-item py-3 text-wrap" id="notification-4">
                                 <div class="d-flex">
                                     <div class="me-2 position-relative flex-shrink-0">
                                         <img src="<?php echo e(asset('/adminpanel/img/users/user-24.jpg')); ?>"
                                             class="avatar-md rounded-circle" alt="">
                                     </div>
                                     <div class="flex-grow-1">
                                         <p class="mb-0 fw-medium text-dark">Ann McClure</p>
                                         <p class="mb-1 text-wrap">
                                             cancelled her appointment scheduled for <span
                                                 class="fw-medium text-dark">February 5, 2024</span>
                                         </p>
                                         <div class="d-flex justify-content-between align-items-center">
                                             <span class="fs-12"><i class="ti ti-clock me-1"></i>20 min ago</span>
                                             <div
                                                 class="notification-action d-flex align-items-center float-end gap-2">
                                                 <a href="javascript:void(0);"
                                                     class="notification-read rounded-circle bg-danger"
                                                     data-bs-toggle="tooltip" title=""
                                                     data-bs-original-title="Make as Read"
                                                     aria-label="Make as Read"></a>
                                                 <button class="btn rounded-circle p-0"
                                                     data-dismissible="#notification-4">
                                                     <i class="ti ti-x"></i>
                                                 </button>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>

                         </div>

                         <!-- View All-->
                         <div class="p-2 rounded-bottom border-top text-center">
                             <a href="notifications.html" class="text-center text-decoration-underline fs-14 mb-0">
                                 View All Notifications
                             </a>
                         </div>

                     </div>
                 </div>
             </div>

             <!-- Settings -->
             <div class="header-item">
                 <div>
                     <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn topbar-link"><i class="ti ti-settings fs-16"></i></a>
                 </div>
             </div>

         </div>
     </div>
 </header>
 <!-- Topbar End -->

 <!-- Search Modal -->
 <div class="modal fade" id="searchModal">
     <div class="modal-dialog modal-lg">
         <div class="modal-content bg-transparent">
             <div class="card shadow-none mb-0">
                 <div class="px-3 py-2 d-flex flex-row align-items-center" id="search-top">
                     <i class="ti ti-search fs-22"></i>
                     <input type="search" class="form-control border-0" placeholder="Search">
                     <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close"><i
                             class="ti ti-x fs-22"></i></button>
                 </div>
             </div>
         </div>
     </div>
 </div>
<?php /**PATH F:\Github\eschool\resources\views////elements/admin/header.blade.php ENDPATH**/ ?>