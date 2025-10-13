<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - <?php echo e($payment->receipt_number); ?></title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #2c3e50;
            background: white;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Page setup */
        @page {
            margin: 0.5in;
            size: A4;
        }

        /* Receipt Container */
        .receipt-container {
            background: white;
            border: 2px solid #2c3e50;
            border-radius: 8px;
            padding: 0;
            margin: 0 auto;
            max-width: 100%;
            width: 100%;
            font-size: 12px;
            line-height: 1.4;
            box-shadow: none;
            height: fit-content;
        }

        /* Receipt Header */
        .receipt-header {
            background: #f8f9fa;
            color: #2c3e50;
            padding: 20px;
            border-radius: 6px 6px 0 0;
            border-bottom: 2px solid #3e007c;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 100px;
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
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .institution-address {
            font-size: 12px;
            margin: 4px 0;
            opacity: 0.9;
        }

        .institution-contact {
            font-size: 11px;
            margin: 4px 0;
            opacity: 0.9;
        }

        .receipt-title h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .receipt-number {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .receipt-number .label {
            font-weight: normal;
            font-size: 11px;
            opacity: 0.9;
        }

        .receipt-number .value {
            font-weight: bold;
            font-size: 14px;
        }

        /* Receipt Body */
        .receipt-body {
            padding: 20px;
            background: #fafbfc;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 20px;
        }

        .content-left, .content-right {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .info-section {
            background: white;
            padding: 15px;
            border: 1px solid #e1e8ed;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #3e007c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px dotted #bdc3c7;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item .label {
            font-weight: 600;
            color: #34495e;
            font-size: 11px;
            flex: 0 0 40%;
        }

        .info-item .value {
            font-weight: 500;
            color: #2c3e50;
            text-align: right;
            font-size: 11px;
            flex: 1;
        }

        .fee-type {
            background: #e8f4fd;
            color: #2980b9;
            padding: 3px 8px;
            border: 1px solid #3e007c;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
        }

        .payment-method {
            background: #e8f5e8;
            color: #27ae60;
            padding: 3px 8px;
            border: 1px solid #2ecc71;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
        }

        /* Amount Section */
        .amount-section {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            color: white;
            margin-top: auto;
            box-shadow: 0 3px 6px rgba(46, 204, 113, 0.3);
        }

        .amount-box {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .amount-label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            opacity: 0.9;
        }

        .amount-value {
            font-size: 24px;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        /* Notes Section */
        .notes-content {
            font-style: italic;
            color: #7f8c8d;
            font-size: 11px;
            line-height: 1.4;
            margin-top: 8px;
        }

        /* Footer Info */
        .footer-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-top: 2px solid #ecf0f1;
            margin-bottom: 15px;
        }

        .footer-left, .footer-right {
            flex: 1;
        }

        .footer-right {
            text-align: right;
        }

        .footer-text {
            font-size: 10px;
            color: #7f8c8d;
            margin: 0;
        }

        /* Status Badge */
        .status-badge {
            padding: 4px 10px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 10px;
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
            padding: 15px 20px;
            text-align: center;
            border-top: 2px solid #ecf0f1;
            border-radius: 0 0 6px 6px;
        }

        /* Print-specific styles */
        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            .receipt-container {
                border: 2px solid #2c3e50 !important;
                box-shadow: none !important;
                margin: 0 !important;
                max-width: none !important;
                width: 100% !important;
                height: auto !important;
                page-break-inside: avoid !important;
            }

            .receipt-header {
                background: #f8f9fa !important;
                color: #2c3e50 !important;
                page-break-inside: avoid !important;
            }

            .receipt-body {
                background: #fafbfc !important;
            }

            .amount-section {
                background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%) !important;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .receipt-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .header-center {
                padding: 0;
            }

            .institution-name {
                font-size: 16px;
            }

            .amount-value {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
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

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
<?php /**PATH E:\eschool\resources\views/institution/payment/payments/print.blade.php ENDPATH**/ ?>