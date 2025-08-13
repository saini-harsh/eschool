<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>   

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Dleohr is a clean and modern human resource management admin dashboard template which is based on HTML 5, Bootstrap 5. Try Demo and Buy Now!">
	<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
    <title> Dashboard - | Dleohr</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Dreams Technologies">

        <!-- Favicon -->
         
    <link rel="shortcut icon" href="{{URL::asset('public/admin/img/favicon.png')}}">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{URL::asset('public/admin/img/apple-icon.png')}}">

    
    <!-- Theme Config Js -->
    <script src="{{URL::asset('public/admin/js/theme-script.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>

 

    <!-- Daterangepikcer CSS -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/plugins/daterangepicker/daterangepicker.css')}}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/css/bootstrap.min.css')}}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/plugins/select2/css/select2.min.css')}}">

    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/plugins/simplebar/simplebar.min.css')}}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/plugins/tabler-icons/tabler-icons.min.css')}}">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/plugins/flatpickr/flatpickr.min.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('public/admin/plugins/fontawesome/css/all.min.css')}}">

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/css/dataTables.bootstrap5.min.css')}}">
   

    <!-- ChartC3 CSS -->
    <link rel="stylesheet" href="{{URL::asset('public/admin/plugins/c3-chart/c3.min.css')}}">

    <link rel="stylesheet" href="{{URL::asset('public/admin/css/style.css')}}" id="app-style">

</head>
<body>

    

        <!-- Start Main Wrapper -->    
    <div class="main-wrapper">
    
        <!--Header-->
        @include('../elements/admin/header')
		
        <!--Content-->
        <!-- <div class="app-body"> -->
            <!--Left Side Bar-->
            @include('../elements/admin/left-side-bar')
            
            @yield('content')
            
        <!-- </div> -->
        <!--Footer-->
        @include('../elements/admin/footer')

   

        
    </div>
    <!-- End Main Wrapper -->

    <!-- jQuery -->
    <script data-cfasync="false" src="../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js')}}"></script><script src="{{URL::asset('public/admin/js/jquery-3.7.1.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{URL::asset('public/admin/js/bootstrap.bundle.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>

    <!-- Select2 JS -->
    <script src="{{URL::asset('public/admin/plugins/select2/js/select2.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>    

    <!-- Simplebar JS -->
    <script src="{{URL::asset('public/admin/plugins/simplebar/simplebar.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>

    

    <!-- Flatpickr JS -->
    <script src="{{URL::asset('public/admin/plugins/flatpickr/flatpickr.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>

    

    <!-- Sticky Sidebar JS -->
    <script src="{{URL::asset('public/admin/plugins/theia-sticky-sidebar/ResizeSensor.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>
    <script src="{{URL::asset('public/admin/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>

    <!-- Daterangepikcer JS -->
    <script src="{{URL::asset('public/admin/js/moment.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>
    <script src="{{URL::asset('public/admin/plugins/daterangepicker/daterangepicker.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>
    
    <!-- Datatable JS -->
    <script src="{{URL::asset('public/admin/js/jquery.dataTables.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>
    <script src="{{URL::asset('public/admin/js/dataTables.bootstrap5.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>

    <!-- Chart JS -->
    <script src="{{URL::asset('public/admin/plugins/apexchart/apexcharts.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>
    <script src="{{URL::asset('public/admin/plugins/apexchart/chart-data.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>



    <!-- Chart JS -->
    <script src="{{URL::asset('public/admin/plugins/c3-chart/d3.v5.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>
    <script src="{{URL::asset('public/admin/plugins/c3-chart/c3.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>
    <script src="{{URL::asset('public/admin/plugins/c3-chart/chart-data.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>

    <!-- Select2 JS -->
    <script src="{{URL::asset('public/admin/plugins/select2/js/select2.min.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>

    <!-- Main JS -->
    <script src="{{URL::asset('public/admin/js/script.js')}}" type="816fa5b22d2c9cff7571ef15-text/javascript"></script>
    
  
    <script src="../../../cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js')}}" data-cf-settings="816fa5b22d2c9cff7571ef15-|49" defer></script><script defer src="https://static.cloudflareinsights.com/beacon.min.js')}}/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"96e5b9843d94897e","version":"2025.7.0","serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"3ca157e612a14eccbb30cf6db6691c29","b":1}' crossorigin="anonymous"></script>
    @yield('scripts')	
</body>
</html>