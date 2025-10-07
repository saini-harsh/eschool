    <script src="<?php echo e(URL::asset('/adminpanel/js/jquery-3.7.1.min.js')); ?>" type=""></script>
    <!-- Bootstrap Core JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/js/bootstrap.bundle.min.js')); ?>" type=""></script>
    <!-- Select2 JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/select2/js/select2.min.js')); ?>" type=""></script>
    <!-- Simplebar JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/simplebar/simplebar.min.js')); ?>" type=""></script>
    <!-- Flatpickr JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/flatpickr/flatpickr.min.js')); ?>" type=""></script>
    <!-- Fullcalendar JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/fullcalendar/index.global.min.js')); ?>" type=""></script>
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/fullcalendar/calendar-data.js')); ?>" type=""></script>
    <!-- Sticky Sidebar JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/theia-sticky-sidebar/ResizeSensor.js')); ?>" type=""></script>
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js')); ?>" type=""></script>
    <!-- Daterangepikcer JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/js/moment.min.js')); ?>" type=""></script>
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/daterangepicker/daterangepicker.js')); ?>" type=""></script>
    <!-- Datatable JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/js/jquery.dataTables.min.js')); ?>" type=""></script>
    <script src="<?php echo e(URL::asset('/adminpanel/js/dataTables.bootstrap5.min.js')); ?>" type=""></script>
    <?php if(request()->routeIs('institution.dashboard') || request()->routeIs('admin.dashboard')): ?>
    <!-- Chart JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/apexchart/apexcharts.min.js')); ?>" type=""></script>
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/apexchart/chart-data.js')); ?>" type=""></script>

    <!-- Chart JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/c3-chart/d3.v5.min.js')); ?>" type=""></script>
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/c3-chart/c3.min.js')); ?>" type=""></script>
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/c3-chart/chart-data.js')); ?>" type=""></script>
    <?php endif; ?>
    <!-- Select2 JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/plugins/select2/js/select2.min.js')); ?>" type=""></script>
    <!-- Main JS -->
    <script src="<?php echo e(URL::asset('/adminpanel/js/script.js')); ?>" type=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
<?php /**PATH F:\Github\eschool\resources\views/components/dashboard/scripts.blade.php ENDPATH**/ ?>