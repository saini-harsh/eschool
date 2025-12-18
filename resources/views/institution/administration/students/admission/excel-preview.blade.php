<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission List - Excel Preview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            background-color: #f8f9fa;
        }

        .spreadsheet-container {
            width: 100%;
            height: 100vh;
            overflow: auto;
            background-color: white;
        }

        table {
            border-collapse: collapse;
            width: max-content;
            min-width: 100%;
        }

        th {
            background-color: #ffff00;
            color: #000;
            font-weight: bold;
            text-align: left;
            padding: 8px 12px;
            border: 1px solid #000;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        td {
            padding: 6px 12px;
            border: 1px solid #000;
            white-space: nowrap;
        }

        tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        tr:hover {
            background-color: #f1f3f5;
        }

        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-approved {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #842029;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #664d03;
        }
    </style>
</head>

<body>
    <div class="spreadsheet-container">
        <table>
            <thead>
                <tr>
                    <th>Old / New</th>
                    <th>Roll No.</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>DOB Status</th>
                    <th>PEN No.</th>
                    <th>Aadhaar No.</th>
                    <th>Mother's Name</th>
                    <th>Father's Name</th>
                    <th>WhatsApp No.</th>
                    <th>Admission Date</th>
                    <th>Address</th>
                    <th>Discount Category</th>
                    <th>Discount Amount</th>
                    <th>Verification</th>
                    <th>Admission Amount</th>
                    <th>KSO</th>
                    <th>ID</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Admission Status</th>
                    <th>Sibling</th>
                    <th>Name of the School</th>
                    <th>Class</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admissions as $admission)
                    <tr>
                        <td>{{ $admission->admission_number ? 'Old' : 'New' }}</td>
                        <td>{{ $admission->roll_number ?? 'N/A' }}</td>
                        <td>{{ $admission->first_name }} {{ $admission->last_name }}</td>
                        <td>{{ $admission->gender ?? 'N/A' }}</td>
                        <td>{{ $admission->dob ? \Carbon\Carbon::parse($admission->dob)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $admission->dob_status ?? 'Not Verified' }}</td>
                        <td>{{ $admission->pen_no ?? 'N/A' }}</td>
                        <td>{{ $admission->aadhaar_no ?? 'N/A' }}</td>
                        <td>{{ $admission->mother_name ?? 'N/A' }}</td>
                        <td>{{ $admission->father_name ?? 'N/A' }}</td>
                        <td>{{ $admission->phone ?? 'N/A' }}</td>
                        <td>{{ $admission->admission_date ? \Carbon\Carbon::parse($admission->admission_date)->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td>{{ $admission->address ?? 'N/A' }}</td>
                        <td>{{ $admission->discount_category ?? 'None' }}</td>
                        <td>{{ number_format($admission->discount_amount, 2) }}</td>
                        <td>{{ ucfirst($admission->status) }}</td>
                        <td>{{ number_format($admission->admission_fee_amount, 2) }}</td>
                        <td>N/A</td>
                        <td>{{ $admission->admission_number ?? 'N/A' }}</td>
                        <td>{{ number_format($admission->admission_fee_amount + $admission->tuition_fee_amount, 2) }}
                        </td>
                        <td>{{ $admission->admission_payment_method ?? 'N/A' }}</td>
                        <td>
                            <span class="status-badge status-{{ $admission->status ?? 'pending' }}">
                                {{ ucfirst($admission->status ?? 'Pending') }}
                            </span>
                        </td>
                        <td>
                            @if ($admission->has_sibling && $admission->sibling_ids)
                                @php
                                    $siblingNames = \App\Models\Student::whereIn('id', $admission->sibling_ids)
                                        ->get()
                                        ->map(function ($s) {
                                            return $s->first_name . ' ' . $s->last_name;
                                        })
                                        ->implode(', ');
                                @endphp
                                {{ $siblingNames }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $admission->previous_school_name ?? 'N/A' }}</td>
                        <td>{{ $admission->previousSchoolClass->name ?? 'N/A' }}</td>
                        <td>{{ $admission->previous_school_result ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
