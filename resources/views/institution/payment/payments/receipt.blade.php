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
            background-color: #f5f5f5;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .institution-name {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .receipt-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }
        .receipt-number {
            font-size: 18px;
            color: #666;
        }
        .receipt-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .detail-section h3 {
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .detail-value {
            color: #333;
        }
        .payment-summary {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .amount-row {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
            border-top: 2px solid #28a745;
            padding-top: 15px;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
        }
        .print-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .print-button:hover {
            background-color: #0056b3;
        }
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
                border-radius: 0;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <button class="print-button" onclick="window.print()">🖨️ Print Receipt</button>
        
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
                @if($payment->studentFee->feeStructure->section)
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
            <h3 style="color: #007bff; margin-bottom: 20px;">Fee Details</h3>
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
                <span>Amount Paid:</span>
                <span>₹{{ number_format($payment->amount, 2) }}</span>
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

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
