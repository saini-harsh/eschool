<?php $__env->startSection('title', 'Student | Coming Soon'); ?>
<?php $__env->startSection('content'); ?>
<div class="content">
  <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
    <div class="flex-grow-1">
      <h5 class="fw-bold">Coming Soon</h5>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
          <li class="breadcrumb-item d-flex align-items-center"><a href="<?php echo e(route('teacher.dashboard')); ?>"><i class="ti ti-home me-1"></i>Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Coming Soon</li>
        </ol>
      </nav>
    </div>
  </div>
  <div class="card">
    <div class="card-body text-center py-5">
      <i class="ti ti-tools fs-48 mb-3"></i>
      <p class="mb-2">This feature is not available yet.</p>
      <p class="text-muted">Please check back later or use other sections from the sidebar.</p>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/student/coming-soon.blade.php ENDPATH**/ ?>