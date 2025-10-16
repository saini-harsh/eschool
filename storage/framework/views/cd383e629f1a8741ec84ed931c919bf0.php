<?php $__env->startSection('title', 'Payment Receipt'); ?>
<?php $__env->startSection('content'); ?>

<!-- Start Content -->
<div class="content">

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
        <div class="flex-grow-1">
            <h5 class="fw-bold">Payment Receipt</h5>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-divide p-0 mb-0">
                    <li class="breadcrumb-item d-flex align-items-center">
                        <i class="ti ti-home me-1"></i>Home
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?php echo e(route('institution.payments.index')); ?>">Payment History</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Receipt</li>
                </ol>
            </nav>
        </div>
        <div>
            <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                <i class="ti ti-printer me-1"></i>Print Receipt
            </button>
            <a href="<?php echo e(route('institution.payments.index')); ?>" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to History
            </a>
        </div>
    </div>
    <!-- End Page Header -->

    <!-- Payment Receipt -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="receipt-container">
                <!-- Receipt Header -->
                <div class="receipt-header">
                    <div class="header-left">
                        <div class="institution-logo">
                            <?php if($payment->institution->logo): ?>
                                <img src="<?php echo e(asset($payment->institution->logo)); ?>" alt="Logo" class="logo-img">
                            <?php else: ?>
                                <div class="logo-placeholder">
                                    <i class="ti ti-school"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="header-center">
                        <h2 class="institution-name"><?php echo e($payment->institution->name); ?></h2>
                        <p class="institution-address"><?php echo e($payment->institution->address); ?></p>
                        <p class="institution-contact">
                            <?php echo e($payment->institution->phone); ?> | <?php echo e($payment->institution->email); ?>

                        </p>
                    </div>
                    <div class="header-right">
                        <div class="receipt-title">
                            <h1>PAYMENT RECEIPT</h1>
                            <div class="receipt-number">
                                <span class="label">Receipt No:</span>
                                <span class="value"><?php echo e($payment->receipt_number); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Receipt Body -->
                <div class="receipt-body">
                    <!-- Main Content Grid -->
                    <div class="main-content">
                        <!-- Left Column -->
                        <div class="content-left">
                            <!-- Student Information -->
                            <div class="info-section">
                                <h3 class="section-title">Student Information</h3>
                                <div class="info-list">
                                    <div class="info-item">
                                        <span class="label">Name:</span>
                                        <span class="value"><?php echo e($payment->student->first_name); ?> <?php echo e($payment->student->last_name); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Admission No:</span>
                                        <span class="value"><?php echo e($payment->student->admission_number ?: '-'); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Class:</span>
                                        <span class="value"><?php echo e($payment->feeStructure->schoolClass->name ?? 'N/A'); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Section:</span>
                                        <span class="value"><?php echo e($payment->feeStructure->section->name ?? 'All Sections'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Fee Information -->
                            <div class="info-section">
                                <h3 class="section-title">Fee Information</h3>
                                <div class="info-list">
                                    <div class="info-item">
                                        <span class="label">Fee Structure:</span>
                                        <span class="value"><?php echo e($payment->feeStructure->name); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Fee Type:</span>
                                        <span class="value fee-type"><?php echo e($payment->feeStructure->fee_type == 'onetime' ? 'One Time' : ucfirst($payment->feeStructure->fee_type)); ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if($payment->notes): ?>
                            <!-- Notes Section -->
                            <div class="info-section">
                                <h3 class="section-title">Notes</h3>
                                <div class="notes-content"><?php echo e($payment->notes); ?></div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Right Column -->
                        <div class="content-right">
                            <!-- Payment Information -->
                            <div class="info-section">
                                <h3 class="section-title">Payment Information</h3>
                                <div class="info-list">
                                    <div class="info-item">
                                        <span class="label">Payment Date:</span>
                                        <span class="value"><?php echo e($payment->payment_date->format('d M Y')); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Payment Method:</span>
                                        <span class="value payment-method"><?php echo e(ucfirst(str_replace('_', ' ', $payment->payment_method))); ?></span>
                                    </div>
                                    <?php if($payment->transaction_id): ?>
                                    <div class="info-item">
                                        <span class="label">Transaction ID:</span>
                                        <span class="value"><?php echo e($payment->transaction_id); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="info-item">
                                        <span class="label">Status:</span>
                                        <span class="status-badge completed">PAID</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Amount Section -->
                            <div class="amount-section">
                                <div class="amount-box">
                                    <div class="amount-label">Amount Paid</div>
                                    <div class="amount-value">₹<?php echo e(number_format($payment->amount, 2)); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Info -->
                    <div class="footer-info">
                        <div class="footer-left">
                            <p class="footer-text">
                                <strong>Generated On:</strong> <?php echo e($payment->created_at->format('d M Y, h:i A')); ?>

                            </p>
                        </div>
                        <div class="footer-right">
                            <p class="footer-text">
                                This is a computer generated receipt and does not require a signature.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Receipt Footer -->
                <div class="receipt-footer">
                    <p class="footer-text">
                        For any queries, please contact the institution office.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Payment Receipt -->

</div>
<!-- End Content -->

<style>
/* Receipt Container - Compact One Page Design */
.receipt-container {
    background: white;
    border: 2px solid #2c3e50;
    border-radius: 8px;
    padding: 0;
    margin: 20px auto;
    max-width: 900px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 12px;
    line-height: 1.4;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    height: fit-content;
}

/* Receipt Header - Compact Design */
.receipt-header {
    background: #f8f9fa;
    color: #2c3e50;
    padding: 15px 20px;
    border-radius: 6px 6px 0 0;
    border-bottom: 2px solid #3e007c;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 80px;
}

.header-left {
    flex: 0 0 auto;
}

.header-center {
    flex: 1;
    text-align: center;
    padding: 0 20px;
}

.header-right {
    flex: 0 0 auto;
    text-align: right;
}

.institution-logo {
    margin: 0;
}

.logo-img {
    max-width: 120px;
    border-radius: 4px;
}

.logo-placeholder {
    width: 80px;
    height: 80px;
    background: rgba(52, 62, 80, 0.1);
    border: 2px solid #2c3e50;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.institution-name {
    font-size: 16px;
    font-weight: bold;
    margin: 0 0 5px 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.institution-address {
    font-size: 11px;
    margin: 2px 0;
    opacity: 0.9;
}

.institution-contact {
    font-size: 10px;
    margin: 2px 0;
    opacity: 0.9;
}

.receipt-title h1 {
    font-size: 14px;
    font-weight: bold;
    margin: 0 0 5px 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.receipt-number {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 2px;
}

.receipt-number .label {
    font-weight: normal;
    font-size: 10px;
    opacity: 0.9;
}

.receipt-number .value {
    font-weight: bold;
    font-size: 12px;
}

/* Receipt Body - Compact Layout */
.receipt-body {
    padding: 15px 20px;
    background: #fafbfc;
}

.main-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 15px;
}

.content-left, .content-right {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-section {
    background: white;
    padding: 12px;
    border: 1px solid #e1e8ed;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.section-title {
    font-size: 11px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 2px solid #3e007c;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
    border-bottom: 1px dotted #bdc3c7;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item .label {
    font-weight: 600;
    color: #34495e;
    font-size: 10px;
    flex: 0 0 40%;
}

.info-item .value {
    font-weight: 500;
    color: #2c3e50;
    text-align: right;
    font-size: 10px;
    flex: 1;
}

.fee-type {
    background: #e8f4fd;
    color: #2980b9;
    padding: 2px 6px;
    border: 1px solid #3e007c;
    border-radius: 3px;
    font-size: 9px;
    font-weight: 600;
}

.payment-method {
    background: #e8f5e8;
    color: #27ae60;
    padding: 2px 6px;
    border: 1px solid #2ecc71;
    border-radius: 3px;
    font-size: 9px;
    font-weight: 600;
}

/* Amount Section - Compact */
.amount-section {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    padding: 15px;
    border-radius: 6px;
    text-align: center;
    color: white;
    margin-top: auto;
    box-shadow: 0 3px 6px rgba(46, 204, 113, 0.3);
}

.amount-box {
    background: rgba(255,255,255,0.1);
    padding: 12px;
    border-radius: 4px;
    border: 1px solid rgba(255,255,255,0.2);
}

.amount-label {
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 6px;
    opacity: 0.9;
}

.amount-value {
    font-size: 20px;
    font-weight: bold;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

/* Notes Section */
.notes-content {
    font-style: italic;
    color: #7f8c8d;
    font-size: 10px;
    line-height: 1.4;
    margin-top: 5px;
}

/* Footer Info */
.footer-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-top: 2px solid #ecf0f1;
    margin-bottom: 10px;
}

.footer-left, .footer-right {
    flex: 1;
}

.footer-right {
    text-align: right;
}

.footer-text {
    font-size: 9px;
    color: #7f8c8d;
    margin: 0;
}

/* Status Badge */
.status-badge {
    padding: 3px 8px;
    border-radius: 15px;
    font-weight: bold;
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.completed {
    background: #d5f4e6;
    color: #27ae60;
    border: 1px solid #2ecc71;
}

/* Receipt Footer */
.receipt-footer {
    background: #f8f9fa;
    padding: 10px 20px;
    text-align: center;
    border-top: 2px solid #ecf0f1;
    border-radius: 0 0 6px 6px;
}

.footer-text {
    font-size: 9px;
    color: #7f8c8d;
    margin: 0;
}

/* Print Styles - Exact Web Design Match */
@media print {
    /* Hide all navigation and page elements */
    .btn, .breadcrumb, .page-header, .sidebar, .navbar, .main-header, .page-title, .content-header {
        display: none !important;
    }
    
    /* Hide the main page wrapper and show only receipt */
    .main-content-wrapper, .content-wrapper, .page-wrapper {
        padding: 0 !important;
        margin: 0 !important;
    }
    
    /* Page setup for clean printing */
    @page {
        margin: 0.2in !important;
        size: A4;
    }
    
    /* Reset body for print */
    body {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Make receipt container fill the page */
    .receipt-container {
        border: 2px solid #2c3e50 !important;
        box-shadow: none !important;
        margin: 0 !important;
        max-width: none !important;
        width: 100% !important;
        height: auto !important;
        page-break-inside: avoid !important;
        font-size: 12px !important;
        position: relative !important;
        top: 0 !important;
        left: 0 !important;
        background: white !important;
    }
    
     /* Receipt header - match web design */
     .receipt-header {
         background: #f8f9fa !important;
         color: #2c3e50 !important;
         padding: 15px 20px !important;
         border-radius: 6px 6px 0 0 !important;
         border-bottom: 2px solid #3e007c !important;
         display: flex !important;
         align-items: center !important;
         justify-content: space-between !important;
         min-height: 100px !important;
         page-break-inside: avoid !important;
     }
     
     /* Logo styles for print */
     .logo-img {
         max-width: 120px !important;
         border-radius: 4px !important;
     }
     
     .logo-placeholder {
         width: 80px !important;
         height: 80px !important;
         background: rgba(52, 62, 80, 0.1) !important;
         border: 2px solid #2c3e50 !important;
         border-radius: 4px !important;
         display: inline-flex !important;
         align-items: center !important;
         justify-content: center !important;
         font-size: 2rem !important;
     }
    
    /* Receipt body - match web design */
    .receipt-body {
        background: #fafbfc !important;
        padding: 15px 20px !important;
    }
    
    /* Main content grid - match web design */
    .main-content {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 20px !important;
        margin-bottom: 15px !important;
    }
    
    /* Content columns */
    .content-left, .content-right {
        display: flex !important;
        flex-direction: column !important;
        gap: 15px !important;
    }
    
    /* Info sections - match web design */
    .info-section {
        background: white !important;
        padding: 12px !important;
        border: 1px solid #e1e8ed !important;
        border-radius: 6px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
    }
    
    /* Section titles - match web design */
    .section-title {
        font-size: 11px !important;
        font-weight: bold !important;
        color: #2c3e50 !important;
        margin-bottom: 10px !important;
        padding-bottom: 5px !important;
        border-bottom: 2px solid #3e007c !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }
    
    /* Info lists */
    .info-list {
        display: flex !important;
        flex-direction: column !important;
        gap: 6px !important;
    }
    
    /* Info items */
    .info-item {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 4px 0 !important;
        border-bottom: 1px dotted #bdc3c7 !important;
    }
    
    .info-item:last-child {
        border-bottom: none !important;
    }
    
    .info-item .label {
        font-weight: 600 !important;
        color: #34495e !important;
        font-size: 10px !important;
        flex: 0 0 40% !important;
    }
    
    .info-item .value {
        font-weight: 500 !important;
        color: #2c3e50 !important;
        text-align: right !important;
        font-size: 10px !important;
        flex: 1 !important;
    }
    
    /* Badges - match web design */
    .fee-type {
        background: #e8f4fd !important;
        color: #2980b9 !important;
        padding: 2px 6px !important;
        border: 1px solid #3e007c !important;
        border-radius: 3px !important;
        font-size: 9px !important;
        font-weight: 600 !important;
    }
    
    .payment-method {
        background: #e8f5e8 !important;
        color: #27ae60 !important;
        padding: 2px 6px !important;
        border: 1px solid #2ecc71 !important;
        border-radius: 3px !important;
        font-size: 9px !important;
        font-weight: 600 !important;
    }
    
    /* Amount section - match web design */
    .amount-section {
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%) !important;
        padding: 15px !important;
        border-radius: 6px !important;
        text-align: center !important;
        color: white !important;
        margin-top: auto !important;
        box-shadow: 0 3px 6px rgba(46, 204, 113, 0.3) !important;
    }
    
    .amount-box {
        background: rgba(255,255,255,0.1) !important;
        padding: 12px !important;
        border-radius: 4px !important;
        border: 1px solid rgba(255,255,255,0.2) !important;
    }
    
    .amount-label {
        font-size: 11px !important;
        font-weight: 600 !important;
        margin-bottom: 6px !important;
        opacity: 0.9 !important;
    }
    
    .amount-value {
        font-size: 20px !important;
        font-weight: bold !important;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2) !important;
    }
    
    /* Status badge */
    .status-badge.completed {
        background: #d5f4e6 !important;
        color: #27ae60 !important;
        border: 1px solid #2ecc71 !important;
        padding: 3px 8px !important;
        border-radius: 15px !important;
        font-weight: bold !important;
        font-size: 9px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }
    
    /* Notes content */
    .notes-content {
        font-style: italic !important;
        color: #7f8c8d !important;
        font-size: 10px !important;
        line-height: 1.4 !important;
        margin-top: 5px !important;
    }
    
    /* Footer info */
    .footer-info {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 10px 0 !important;
        border-top: 2px solid #ecf0f1 !important;
        margin-bottom: 10px !important;
    }
    
    .footer-left, .footer-right {
        flex: 1 !important;
    }
    
    .footer-right {
        text-align: right !important;
    }
    
    .footer-text {
        font-size: 9px !important;
        color: #7f8c8d !important;
        margin: 0 !important;
    }
    
    /* Receipt footer */
    .receipt-footer {
        background: #f8f9fa !important;
        padding: 10px 20px !important;
        text-align: center !important;
        border-top: 2px solid #ecf0f1 !important;
        border-radius: 0 0 6px 6px !important;
    }
    
    /* Hide any browser print elements */
    .print-header,
    .print-footer,
    .browser-header,
    .browser-footer {
        display: none !important;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .receipt-container {
        margin: 10px;
        border-radius: 4px;
    }
    
    .main-content {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .receipt-header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .header-center {
        padding: 0;
    }
    
    .institution-name {
        font-size: 14px;
    }
    
    .amount-value {
        font-size: 18px;
    }
    
    .receipt-body {
        padding: 12px 15px;
    }
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\eschool\resources\views/institution/payment/payments/show.blade.php ENDPATH**/ ?>