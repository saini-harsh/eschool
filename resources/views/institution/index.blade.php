@extends('layouts.institution')
@section('title', 'Institution Dashboard')
@section('content')

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Dashboard</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div>
            <span class="text-muted">Welcome back, {{ Auth::guard('institution')->user()->name }}</span>
        </div>
    </div>
    <!-- End Page Header -->
     

   
</div>
<!-- End Content -->

@endsection
@push('scripts')
<script>
    // Add any dashboard-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any charts or interactive elements
        console.log('Institution Dashboard loaded');
    });
</script>
@endpush

