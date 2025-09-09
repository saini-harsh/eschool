<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Dleohr is a clean and modern human resource management admin dashboard template which is based on HTML 5, Bootstrap 5. Try Demo and Buy Now!">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
    <!-- <title> Dashboard - | ESchool</title> -->
    <title> TripgoOnline</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Favicon -->

    <!-- <link rel="shortcut icon" href="{{ URL::asset('/adminpanel/img/favicon.png') }}"> -->
    <link rel="shortcut icon" href="https://tripgoonline.com/favicon.ico">

    <!-- Apple Icon -->
    <!-- <link rel="apple-touch-icon" href="{{ URL::asset('/adminpanel/img/apple-icon.png') }}"> -->
    <link rel="apple-touch-icon" href="https://tripgoonline.com/favicon.ico">


    <!-- Theme Config Js -->
    <script src="{{URL::asset('/adminpanel/js/theme-script.js')}}" type=""></script>

    @include('components.dashboard.styles')

</head>

<body>
    <!-- Start Main Wrapper -->
    <div class="main-wrapper">

        <!--Header-->
        @include('../elements/admin/header')

        <!--Content-->
        <!--Left Side Bar-->
        @if(Auth::guard('teacher')->check())
            @include('../elements/teacher/left-side-bar')
        @elseif(Auth::guard('admin')->check())
            @include('../elements/admin/left-side-bar')
        @endif
        <div class="page-wrapper">

            @yield('content')

            <!--Footer-->
            @include('../elements/admin/footer')

        </div>

    </div>
    <!-- End Main Wrapper -->
    <!-- jQuery -->
    @include('components.dashboard.scripts')
</body>

</html>
