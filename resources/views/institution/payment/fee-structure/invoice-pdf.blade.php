<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Structure Invoice - {{ $feeStructure->fee_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .invoice-container {
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
        .invoice-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }
        .invoice-number {
            font-size: 14px;
            color: #666;
        }
        .invoice-details {
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
        .fee-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .amount-row {
            display: table;
            width: 100%;
            font-size: 16px;
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
    <div class="invoice-container">
        <div class="header">
            <div class="institution-name">{{ $feeStructure->institution->name }}</div>
            <div class="invoice-title">FEE STRUCTURE INVOICE</div>
            <div class="invoice-number">Invoice Date: {{ now()->format('d M Y') }}</div>
        </div>

        <div class="invoice-details">
            <div class="detail-section">
                <h3>Fee Information</h3>
                <div class="detail-row">
                    <span class="detail-label">Fee Name:</span>
                    <span class="detail-value">{{ $feeStructure->fee_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fee Type:</span>
                    <span class="detail-value">{{ ucfirst($feeStructure->fee_type) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Frequency:</span>
                    <span class="detail-value">{{ ucfirst($feeStructure->payment_frequency) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Amount:</span>
                    <span class="detail-value" style="color: #28a745; font-weight: bold;">₹{{ number_format($feeStructure->amount, 2) }}</span>
                </div>
            </div>

            <div class="detail-section">
                <h3>Applicable To</h3>
                <div class="detail-row">
                    <span class="detail-label">Class:</span>
                    <span class="detail-value">{{ $feeStructure->schoolClass->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Section:</span>
                    <span class="detail-value">{{ $feeStructure->section->name ?? 'All Sections' }}</span>
                </div>
                @if($feeStructure->due_date)
                <div class="detail-row">
                    <span class="detail-label">Due Date:</span>
                    <span class="detail-value">{{ $feeStructure->due_date->format('d M Y') }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Mandatory:</span>
                    <span class="detail-value">{{ $feeStructure->is_mandatory ? 'Yes' : 'No' }}</span>
                </div>
            </div>
        </div>

        @if($feeStructure->description)
        <div class="detail-section">
            <h3>Description</h3>
            <p style="color: #666; font-style: italic;">{{ $feeStructure->description }}</p>
        </div>
        @endif

        <div class="fee-summary">
            <h3 style="color: #007bff; margin-bottom: 15px;">Fee Summary</h3>
            <div class="detail-row">
                <span class="detail-label">Fee Name:</span>
                <span class="detail-value">{{ $feeStructure->fee_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Fee Type:</span>
                <span class="detail-value">{{ ucfirst($feeStructure->fee_type) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Frequency:</span>
                <span class="detail-value">{{ ucfirst($feeStructure->payment_frequency) }}</span>
            </div>
            <div class="amount-row">
                <span class="detail-label">Total Amount:</span>
                <span class="detail-value">₹{{ number_format($feeStructure->amount, 2) }}</span>
            </div>
        </div>

        <div class="footer">
            <p><strong>Fee Structure Invoice</strong></p>
            <p>This fee structure is applicable to all students in the specified class/section.</p>
            <p>Generated on: {{ now()->format('d M Y h:i A') }}</p>
            <p>For any queries, please contact the institution office.</p>
        </div>
    </div>
</body>
</html>
