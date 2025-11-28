<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="EnvisionTechSolution Institution Management System - Manage your educational institution efficiently">
    <meta name="keywords"
        content="institution, school, management, dashboard, education, students, teachers, academic">
        
    <title>@yield('title', 'Institution Dashboard') - EnvisionTechSolution</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Favicon -->
    
    <link rel="shortcut icon" href="{{ URL::asset('/adminpanel/img/favicon.png') }}">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{ URL::asset('/adminpanel/img/apple-icon.png') }}">

    <!-- Theme Config Js -->
    <script src="{{URL::asset('/adminpanel/js/theme-script.js')}}" type=""></script>

    @include('components.dashboard.styles')

</head>

<body>
    <!-- Start Main Wrapper -->
    <div class="main-wrapper">

        <!--Header-->
        @include('../elements/teacher/header')

        <!--Content-->
        <!--Left Side Bar-->
        @include('../elements/teacher/left-side-bar')
        
        <div class="page-wrapper">

            @yield('content')

            <!--Footer-->
            @include('../elements/teacher/footer')

        </div>

    </div>
    <!-- End Main Wrapper -->
    <!-- jQuery -->
    @include('components.dashboard.scripts')
</body>

</html>
