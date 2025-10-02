<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->receipt_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .receipt-container {
            max-width: 100%;
            margin: 0 auto;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .institution-name {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 8px;
        }
        .receipt-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }
        .receipt-number {
            font-size: 14px;
            color: #666;
        }
        .receipt-details {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .detail-section {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .detail-section:last-child {
            padding-right: 0;
        }
        .detail-section h3 {
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 8px;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .detail-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .detail-label {
            display: table-cell;
            font-weight: bold;
            color: #555;
            width: 40%;
        }
        .detail-value {
            display: table-cell;
            color: #333;
            width: 60%;
        }
        .payment-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .amount-row {
            display: table;
            width: 100%;
            font-size: 14px;
            font-weight: bold;
            color: #28a745;
            border-top: 2px solid #28a745;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <div class="institution-name">{{ $payment->institution->name }}</div>
            <div class="receipt-title">PAYMENT RECEIPT</div>
            <div class="receipt-number">Receipt No: {{ $payment->receipt_number }}</div>
        </div>

        <div class="receipt-details">
            <div class="detail-section">
                <h3>Student Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $payment->student->first_name }} {{ $payment->student->last_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Admission No:</span>
                    <span class="detail-value">{{ $payment->student->admission_number ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Class:</span>
                    <span class="detail-value">{{ $payment->studentFee->feeStructure->schoolClass->name ?? 'N/A' }}</span>
                </div>
                @if($payment->studentFee->feeStructure->section_id)
                <div class="detail-row">
                    <span class="detail-label">Section:</span>
                    <span class="detail-value">{{ $payment->studentFee->feeStructure->section->name ?? 'N/A' }}</span>
                </div>
                @endif
            </div>

            <div class="detail-section">
                <h3>Payment Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Payment Ref:</span>
                    <span class="detail-value">{{ $payment->payment_reference }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Date:</span>
                    <span class="detail-value">{{ $payment->payment_date->format('d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">{{ ucfirst($payment->payment_method) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #28a745; font-weight: bold;">{{ ucfirst($payment->payment_status) }}</span>
                </div>
            </div>
        </div>

        <div class="payment-summary">
            <h3 style="color: #007bff; margin-bottom: 15px;">Fee Details</h3>
            <div class="detail-row">
                <span class="detail-label">Fee Name:</span>
                <span class="detail-value">{{ $payment->studentFee->feeStructure->fee_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Fee Type:</span>
                <span class="detail-value">{{ ucfirst($payment->studentFee->feeStructure->fee_type) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Fee Amount:</span>
                <span class="detail-value">₹{{ number_format($payment->studentFee->amount, 2) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Previously Paid:</span>
                <span class="detail-value">₹{{ number_format($payment->studentFee->paid_amount - $payment->amount, 2) }}</span>
            </div>
            <div class="amount-row">
                <span class="detail-label">Amount Paid:</span>
                <span class="detail-value">₹{{ number_format($payment->amount, 2) }}</span>
            </div>
        </div>

        @if($payment->payment_notes)
        <div class="detail-section">
            <h3>Notes</h3>
            <p style="color: #666; font-style: italic;">{{ $payment->payment_notes }}</p>
        </div>
        @endif

        <div class="footer">
            <p><strong>Thank you for your payment!</strong></p>
            <p>This is a computer-generated receipt. No signature required.</p>
            <p>Generated on: {{ now()->format('d M Y h:i A') }}</p>
            <p>For any queries, please contact the institution office.</p>
        </div>
    </div>
</body>
</html>
