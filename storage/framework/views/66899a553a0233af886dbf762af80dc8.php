<?php $__env->startSection('title', 'Institution | Send Email/SMS'); ?>
<?php $__env->startSection('content'); ?>
<style>
    .individual-recipients-list {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        padding: 1rem;
        background-color: #f8f9fc;
    }
    
    .individual-recipients-list .form-check {
        padding: 0.5rem;
        border-radius: 0.25rem;
        transition: background-color 0.15s ease-in-out;
    }
    
    .individual-recipients-list .form-check:hover {
        background-color: #e3e6f0;
    }
    
    .individual-recipients-list .form-check-label {
        cursor: pointer;
        font-size: 0.875rem;
    }
    
    .badge-soft-success {
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
    }
    
    .badge-soft-primary {
        background-color: #cfe2ff;
        color: #084298;
        border: 1px solid #b6d4fe;
    }
    
    .badge-soft-warning {
        background-color: #fff3cd;
        color: #664d03;
        border: 1px solid #ffecb5;
    }
    
    .class-recipients-list {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        padding: 1rem;
        background-color: #f8f9fc;
    }
    
    .class-recipients-list .form-check {
        padding: 0.5rem;
        border-radius: 0.25rem;
        transition: background-color 0.15s ease-in-out;
        margin-bottom: 0.5rem;
    }
    
    .class-recipients-list .form-check:hover {
        background-color: #e3e6f0;
    }
    
    .class-recipients-list .form-check-label {
        cursor: pointer;
        font-size: 0.875rem;
    }
    
    .selected-recipient-item {
        transition: all 0.2s ease;
    }
    
    .selected-recipient-item:hover {
        background-color: #f8f9fc;
    }
    
    .selected-recipient-item .btn-outline-danger {
        opacity: 0.7;
        transition: opacity 0.2s ease;
    }
    
    .selected-recipient-item:hover .btn-outline-danger {
        opacity: 1;
    }
</style>
    <?php if(session('success')): ?>
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo e(session('success')); ?>

                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Start Content -->
    <div class="content">
        <!-- Page Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold">Send Email/SMS</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a href="<?php echo e(route('institution.dashboard')); ?>"><i class="ti ti-home me-1"></i>Home</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Communicate</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Send Email/SMS</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <!-- Left Panel - Message Composition -->
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Compose Message</h6>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="email-sms-form">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="message_id" id="message_id">
                            
                            <!-- Title Field -->
                            <div class="mb-3">
                                <label class="form-label">TITLE *</label>
                                <input type="text" name="title" id="message_title" class="form-control"
                                    placeholder="Enter message title" autocomplete="off" required>
                                
                                <!-- Selected Recipients Display -->
                                <div class="mt-2" id="selected-recipients-display">
                                    <!-- Recipients will be displayed here as small icons -->
                                </div>
                            </div>

                            <!-- Send Through Options -->
                            <div class="mb-3">
                                <label class="form-label">SEND THROUGH</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="send_through" value="email" 
                                               id="send_email" checked>
                                        <label class="form-check-label" for="send_email">
                                            Email
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="send_through" value="sms" 
                                               id="send_sms">
                                        <label class="form-check-label" for="send_sms">
                                            SMS
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="send_through" value="whatsapp" 
                                               id="send_whatsapp">
                                        <label class="form-check-label" for="send_whatsapp">
                                            WhatsApp
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Description Field -->
                            <div class="mb-3">
                                <label class="form-label">DESCRIPTION *</label>
                                <textarea name="description" id="message_description" class="form-control" rows="8" 
                                          placeholder="Enter your message content" required></textarea>
                                <div class="d-flex justify-content-end mt-1">
                                    <small class="text-muted">
                                        <i class="ti ti-grammar-check text-success"></i>
                                    </small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Recipient Selection -->
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Recipient Selection</h6>
                    </div>
                    <div class="card-body">
                        <!-- Recipient Type Tabs -->
                        <ul class="nav nav-tabs nav-bordered mb-3" id="recipient-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="group-tab" data-bs-toggle="tab" data-bs-target="#group" 
                                        type="button" role="tab" aria-controls="group" aria-selected="false">
                                    GROUP
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="individual-tab" data-bs-toggle="tab" 
                                        data-bs-target="#individual" type="button" role="tab" aria-controls="individual" 
                                        aria-selected="true">
                                    INDIVIDUAL
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="class-tab" data-bs-toggle="tab" data-bs-target="#class" 
                                        type="button" role="tab" aria-controls="class" aria-selected="false">
                                    CLASS
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="recipient-tab-content">
                            <!-- Individual Tab -->
                            <div class="tab-pane fade show active" id="individual" role="tabpanel" 
                                 aria-labelledby="individual-tab">
                                
                                <!-- Information Alert -->
                                <div class="alert alert-info mb-3">
                                    <i class="ti ti-info-circle me-2"></i>
                                    <strong>Individual Selection:</strong> Select an institution first, then choose a role, and finally select specific individuals or all from that role.
                                </div>
                                
                                <!-- Institution Selection -->
                                <div class="mb-3">
                                    <label class="form-label">Select Institution *</label>
                                    <select class="form-select" id="individual-institution-select">
                                        <option value="">Choose an institution...</option>
                                        <!-- Institutions will be loaded dynamically -->
                                    </select>
                                </div>
                                
                                <!-- Role Selection -->
                                <div class="mb-3" id="individual-role-container" style="display: none;">
                                    <label class="form-label">Select Role *</label>
                                    <select class="form-select" id="individual-role-select">
                                        <option value="">Choose a role...</option>
                                        <option value="students">Students</option>
                                        <option value="teachers">Teachers</option>
                                        <option value="parents">Parents</option>
                                        <option value="non_working_staff">Non-Working Staff</option>
                                    </select>
                                </div>
                                
                                <!-- Individual Selection -->
                                <div class="mb-3" id="individual-recipient-container" style="display: none;">
                                    <label class="form-label">Select Recipients *</label>
                                    
                                    <!-- Search Box -->
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="individual-search" 
                                               placeholder="Search individuals..." autocomplete="off">
                                    </div>
                                    
                                    <!-- Select All Option -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="select-all-individuals">
                                        <label class="form-check-label fw-bold" for="select-all-individuals">
                                            <i class="ti ti-checkbox me-1"></i>Select All
                                        </label>
                                    </div>
                                    
                                    <!-- Individual Recipients List -->
                                    <div class="individual-recipients-list" id="individual-recipients-list" style="max-height: 300px; overflow-y: auto;">
                                        <!-- Individual recipients will be loaded here -->
                                    </div>
                                </div>
                                
                                <!-- Selected Individuals Display -->
                                <div class="mb-3" id="individual-selected-display" style="display: none;">
                                    <label class="form-label">Selected Individuals:</label>
                                    <div id="selected-individuals-display" class="d-flex flex-wrap gap-2">
                                        <!-- Selected individuals will be displayed here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Group Tab -->
                            <div class="tab-pane fade" id="group" role="tabpanel" aria-labelledby="group-tab">
                                <!-- Information Alert -->
                                <div class="alert alert-info mb-3">
                                    <i class="ti ti-info-circle me-2"></i>
                                    <strong>Group Selection:</strong> Select an institution first, then choose one or more recipient groups. 
                                    You can select multiple groups at once for bulk messaging.
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Select Institution *</label>
                                    <select class="form-select" id="group-institution-select">
                                        <option value="">Choose an institution...</option>
                                        <!-- Institutions will be loaded dynamically -->
                                    </select>
                                </div>
                                
                                <div class="mb-3" id="group-recipient-container" style="display: none;">
                                    <label class="form-label">Select Recipient Groups *</label>
                                    
                                    <!-- Select All Option -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="select-all-groups">
                                        <label class="form-check-label fw-bold" for="select-all-groups">
                                            <i class="ti ti-checkbox me-1"></i>Select All Groups
                                        </label>
                                    </div>
                                    
                                    <div class="recipient-group-options">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input group-recipient-checkbox" type="checkbox" 
                                                   value="all_teachers" id="group_recipient_teachers">
                                            <label class="form-check-label" for="group_recipient_teachers">
                                                <i class="ti ti-users me-1"></i>All Teachers
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input group-recipient-checkbox" type="checkbox" 
                                                   value="all_students" id="group_recipient_students">
                                            <label class="form-check-label" for="group_recipient_students">
                                                <i class="ti ti-user me-1"></i>All Students
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input group-recipient-checkbox" type="checkbox" 
                                                   value="all_parents" id="group_recipient_parents">
                                            <label class="form-check-label" for="group_recipient_parents">
                                                <i class="ti ti-user-check me-1"></i>All Parents
                                            </label>
                                            <small class="form-text text-muted d-block ms-4">
                                                <i class="ti ti-info-circle me-1"></i>
                                                Uses student contact information when parent details are not available
                                            </small>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input group-recipient-checkbox" type="checkbox" 
                                                   value="all_non_working_staff" id="group_recipient_non_working_staff">
                                            <label class="form-check-label" for="group_recipient_non_working_staff">
                                                <i class="ti ti-user-star me-1"></i>All Non-Working Staff
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3" id="group-count-display" style="display: none;">
                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-2"></i>
                                        <span id="recipient-count-text">Selected recipients: 0</span>
                                    </div>
                                </div>
                                
                                <div class="mb-3" id="group-selected-groups" style="display: none;">
                                    <label class="form-label">Selected Groups:</label>
                                    <div id="selected-groups-display" class="d-flex flex-wrap gap-2">
                                        <!-- Selected groups will be displayed here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Class Tab -->
                            <div class="tab-pane fade" id="class" role="tabpanel" aria-labelledby="class-tab">
                                <!-- Information Alert -->
                                <div class="alert alert-info mb-3">
                                    <i class="ti ti-info-circle me-2"></i>
                                    <strong>Class Selection:</strong> Select an institution first, then choose a class, then select a section (optional), and finally select students and/or parents.
                                </div>
                                
                                <!-- Institution Selection -->
                                <div class="mb-3">
                                    <label class="form-label">Select Institution *</label>
                                    <select class="form-select" id="class-institution-select">
                                        <option value="">Choose an institution...</option>
                                        <!-- Institutions will be loaded dynamically -->
                                    </select>
                                </div>
                                
                                <!-- Class Selection -->
                                <div class="mb-3" id="class-container" style="display: none;">
                                    <label class="form-label">Select Class *</label>
                                    <select class="form-select" id="class-select">
                                        <option value="">Choose a class...</option>
                                        <!-- Classes will be loaded dynamically -->
                                    </select>
                                </div>
                                
                                <!-- Section Selection -->
                                <div class="mb-3" id="section-container" style="display: none;">
                                    <label class="form-label">Select Section (Optional)</label>
                                    <select class="form-select" id="section-select">
                                        <option value="">Choose a section...</option>
                                        <option value="all">All Sections</option>
                                        <!-- Sections will be loaded dynamically -->
                                    </select>
                                </div>
                                
                                <!-- Students and Parents Selection -->
                                <div class="mb-3" id="recipients-container" style="display: none;">
                                    <label class="form-label">Select Recipients *</label>
                                    
                                    <!-- Select All Options -->
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="select-all-students">
                                                <label class="form-check-label fw-bold" for="select-all-students">
                                                    <i class="ti ti-user me-1"></i>Select All Students
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="select-all-parents">
                                                <label class="form-check-label fw-bold" for="select-all-parents">
                                                    <i class="ti ti-user-check me-1"></i>Select All Parents
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Students List -->
                                    <div class="mb-3">
                                        <h6 class="fw-bold text-primary">
                                            <i class="ti ti-user me-1"></i>Students
                                        </h6>
                                        <div class="class-recipients-list" id="students-list" style="max-height: 200px; overflow-y: auto;">
                                            <!-- Students will be loaded here -->
                                        </div>
                                    </div>
                                    
                                    <!-- Parents List -->
                                    <div class="mb-3">
                                        <h6 class="fw-bold text-warning">
                                            <i class="ti ti-user-check me-1"></i>Parents
                                            <small class="text-muted">(Using student contact information)</small>
                                        </h6>
                                        <div class="class-recipients-list" id="parents-list" style="max-height: 200px; overflow-y: auto;">
                                            <!-- Parents will be loaded here -->
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Selected Recipients Display -->
                                <div class="mb-3" id="class-selected-display" style="display: none;">
                                    <label class="form-label">Selected Recipients:</label>
                                    
                                    <!-- Summary Badges -->
                                    <div id="selected-class-recipients-display" class="d-flex flex-wrap gap-2 mb-3">
                                        <!-- Selected recipients summary will be displayed here -->
                                    </div>
                                    
                                    <!-- Detailed List -->
                                    <div class="mt-3">
                                        <!-- Selected Students List -->
                                        <div id="selected-students-detail" style="display: none;">
                                            <h6 class="fw-bold text-primary mb-2">
                                                <i class="ti ti-user me-1"></i>Selected Students:
                                            </h6>
                                            <div id="selected-students-list" class="class-recipients-list">
                                                <!-- Selected students will be listed here -->
                                            </div>
                                        </div>
                                        
                                        <!-- Selected Parents List -->
                                        <div id="selected-parents-detail" style="display: none;">
                                            <h6 class="fw-bold text-warning mb-2">
                                                <i class="ti ti-user-check me-1"></i>Selected Parents:
                                            </h6>
                                            <div id="selected-parents-list" class="class-recipients-list">
                                                <!-- Selected parents will be listed here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="row mt-3">
            <div class="col-12">
                <!-- Alert Message -->
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="ti ti-alert-triangle me-2"></i>
                    For Sending Email / SMS, It may take some seconds. So please take patience.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <!-- Send Button -->
                <div class="text-center">
                    <button class="btn btn-primary btn-lg" type="button" id="send-message">
                        <i class="ti ti-check me-2"></i>SEND
                    </button>
                </div>
            </div>
        </div>

        <!-- Message History Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-bold">Message History</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                            <div class="datatable-search">
                                <a href="javascript:void(0);" class="input-text"><i class="ti ti-search"></i></a>
                            </div>
                            <div class="d-flex align-items-center">
                            <div class="dropdown">
                                <a href="javascript:void(0);"
                                    class="btn fs-14 py-1 btn-outline-white d-inline-flex align-items-center"
                                    data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                    <i class="ti ti-filter me-1"></i>Filter
                                </a>
                                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0" id="filter-dropdown">
                                    <div class="card mb-0">
                                        <div class="card-header">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="fw-bold mb-0">Filter</h6>
                                                <div class="d-flex align-items-center">
                                                    <a href="<?php echo e(route('admin.email-sms.index')); ?>"
                                                        class="link-danger text-decoration-underline">Clear All</a>
                                                </div>
                                            </div>
                                        </div>
                                        <form action="" id="filter-form" method="GET">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <label class="form-label">Type</label>
                                                        <a href="javascript:void(0);" class="link-primary mb-1 filter-reset" data-field="types">Reset</a>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);"
                                                            class="dropdown-toggle justify-content-between btn bg-light justify-content-start border w-100"
                                                            data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                            aria-expanded="true">
                                                            Select Type
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu w-100">
                                                            <?php $typeSelections = (array) request('types', []); ?>
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="types[]" value="email" <?php echo e(in_array('email', $typeSelections) ? 'checked' : ''); ?>>
                                                                    Email
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="types[]" value="sms" <?php echo e(in_array('sms', $typeSelections) ? 'checked' : ''); ?>>
                                                                    SMS
                                                                </label>
                                                            </li>
                                                            <li>
                                                                <label class="dropdown-item px-2 d-flex align-items-center rounded-1">
                                                                    <input class="form-check-input m-0 me-2" type="checkbox" name="types[]" value="whatsapp" <?php echo e(in_array('whatsapp', $typeSelections) ? 'checked' : ''); ?>>
                                                                    WhatsApp
                                                                </label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            
                                            </div>
                                            <div class="card-footer d-flex align-items-center justify-content-end">
                                                <button type="button" class="btn btn-outline-white me-2"
                                                    id="close-filter">Close</button>
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-nowrap datatable">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Recipients</th>
                                        <th>Status</th>
                                        <th>Sent At</th>
                                        <th class="no-sort">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($lists) && !empty($lists)): ?>
                                        <?php $__currentLoopData = $lists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-2">
                                                            <h6 class="fs-14 mb-0"><?php echo e($list->title); ?></h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo e($list->send_through === 'email' ? 'primary' : 'success'); ?>">
                                                        <?php echo e(strtoupper($list->send_through)); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo e(count($list->recipients)); ?> recipients
                                                    </small>
                                                </td>
                                                <td>
                                                    <div>
                                                        <select class="form-select message-status-select" data-message-id="<?php echo e($list->id); ?>">
                                                            <option value="pending" <?php echo e($list->status === 'pending' ? 'selected' : ''); ?>>Pending</option>
                                                            <option value="sent" <?php echo e($list->status === 'sent' ? 'selected' : ''); ?>>Sent</option>
                                                            <option value="failed" <?php echo e($list->status === 'failed' ? 'selected' : ''); ?>>Failed</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo e($list->sent_at ? $list->sent_at->format('M d, Y H:i') : 'Not sent'); ?>

                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="d-inline-flex align-items-center">
                                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 edit-message" 
                                                           data-message-id="<?php echo e($list->id); ?>">
                                                            <i class="ti ti-edit"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-outline-white border-0 delete-message" 
                                                           data-message-id="<?php echo e($list->id); ?>">
                                                            <i class="ti ti-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Content -->

    <!-- Delete Modal -->
    <div class="modal fade" id="delete_modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Delete Message</h6>
                    <p>Are you sure you want to delete this message?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('custom/js/institution/email-sms.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\eschool\resources\views/institution/communication/emailsms/index.blade.php ENDPATH**/ ?>