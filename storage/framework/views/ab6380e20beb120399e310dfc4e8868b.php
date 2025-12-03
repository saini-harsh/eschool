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

    <title><?php echo $__env->yieldContent('title', 'Institution Dashboard'); ?> - EnvisionTechSolution</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Favicon -->
    
    <link rel="shortcut icon" href="<?php echo e(URL::asset('/adminpanel/img/favicon.png')); ?>">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="<?php echo e(URL::asset('/adminpanel/img/apple-icon.png')); ?>">

    <!-- Theme Config Js -->
    <script src="<?php echo e(URL::asset('/adminpanel/js/theme-script.js')); ?>" type=""></script>

    <?php echo $__env->make('components.dashboard.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</head>

<body>
    <!-- Start Main Wrapper -->
    <div class="main-wrapper">

        <!--Header-->
        <?php echo $__env->make('../elements/student/header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!--Content-->
        <!--Left Side Bar-->
        <?php echo $__env->make('../elements/student/left-side-bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <div class="page-wrapper">

            <?php echo $__env->yieldContent('content'); ?>

            <!--Footer-->
            <?php echo $__env->make('../elements/student/footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>

    </div>
    <!-- End Main Wrapper -->
    <!-- jQuery -->
    <?php echo $__env->make('components.dashboard.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html>
<?php /**PATH E:\eschool\resources\views/layouts/student.blade.php ENDPATH**/ ?>