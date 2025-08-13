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
    <title> Dashboard - | Dleohr</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dreams Technologies">

    <!-- Favicon -->

    <link rel="shortcut icon" href="{{ URL::asset('/admin/img/favicon.png') }}">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{ URL::asset('/admin/img/apple-icon.png') }}">


    <!-- Theme Config Js -->
    <script src="{{URL::asset('/admin/js/theme-script.js')}}" type=""></script>



    <!-- Daterangepikcer CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/admin/plugins/daterangepicker/daterangepicker.css') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/admin/css/bootstrap.min.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/admin/plugins/select2/css/select2.min.css') }}">

    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/admin/plugins/simplebar/simplebar.min.css') }}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/admin/plugins/tabler-icons/tabler-icons.min.css') }}">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/admin/plugins/flatpickr/flatpickr.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/admin/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('/admin/plugins/fontawesome/css/all.min.css') }}">

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/admin/css/dataTables.bootstrap5.min.css') }}">


    <!-- ChartC3 CSS -->
    <link rel="stylesheet" href="{{ URL::asset('/admin/plugins/c3-chart/c3.min.css') }}">

    <link rel="stylesheet" href="{{ URL::asset('/admin/css/style.css') }}" id="app-style">

</head>

<body>



    <!-- Start Main Wrapper -->
    <div class="main-wrapper">

        <!--Header-->
        @include('../elements/admin/header')

        <!--Content-->
        <!--Left Side Bar-->
        @include('../elements/admin/left-side-bar')

        <div class="page-wrapper">

        @yield('content')

      
         <!--Footer-->
            @include('../elements/admin/footer')

        </div>




    </div>
    <!-- End Main Wrapper -->
    <!-- jQuery -->
    <!-- <script src="{{URL::asset('/admin/js/jquery-3.7.1.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script> -->
    <script src="{{URL::asset('/admin/js/jquery-3.7.1.min.js')}}" type=""></script>


    <!-- Bootstrap Core JS -->
    <script src="{{URL::asset('/admin/js/bootstrap.bundle.min.js')}}" type=""></script>

    <!-- Select2 JS -->
    <script src="{{URL::asset('/admin/plugins/select2/js/select2.min.js')}}" type=""></script>

    <!-- Simplebar JS -->
    <script src="{{URL::asset('/admin/plugins/simplebar/simplebar.min.js')}}" type=""></script>



    <!-- Flatpickr JS -->
    <script src="{{URL::asset('/admin/plugins/flatpickr/flatpickr.min.js')}}" type=""></script>



    <!-- Sticky Sidebar JS -->
    <script src="{{URL::asset('/admin/plugins/theia-sticky-sidebar/ResizeSensor.js')}}" type=""></script>
    <script src="{{URL::asset('/admin/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js')}}" type=""></script>

    <!-- Daterangepikcer JS -->
    <script src="{{URL::asset('/admin/js/moment.min.js')}}" type=""></script>
    <script src="{{URL::asset('/admin/plugins/daterangepicker/daterangepicker.js')}}" type=""></script>

    <!-- Datatable JS -->
    <script src="{{URL::asset('/admin/js/jquery.dataTables.min.js')}}" type=""></script>
    <script src="{{URL::asset('/admin/js/dataTables.bootstrap5.min.js')}}" type=""></script>

    <!-- Chart JS -->
    <script src="{{URL::asset('/admin/plugins/apexchart/apexcharts.min.js')}}" type=""></script>
    <script src="{{URL::asset('/admin/plugins/apexchart/chart-data.js')}}" type=""></script>



    <!-- Chart JS -->
    <script src="{{URL::asset('/admin/plugins/c3-chart/d3.v5.min.js')}}" type=""></script>
    <script src="{{URL::asset('/admin/plugins/c3-chart/c3.min.js')}}" type=""></script>
    <script src="{{URL::asset('/admin/plugins/c3-chart/chart-data.js')}}" type=""></script>

    <!-- Select2 JS -->
    <script src="{{URL::asset('/admin/plugins/select2/js/select2.min.js')}}" type=""></script>

    <!-- Main JS -->
    <script src="{{URL::asset('/admin/js/script.js')}}" type=""></script>

    @yield('scripts')
</body>

</html>
