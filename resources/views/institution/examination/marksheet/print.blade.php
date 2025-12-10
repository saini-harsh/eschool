<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marksheet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .institution-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .exam-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .student-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .student-info td {
            padding: 5px;
        }
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .marks-table th, .marks-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .marks-table th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .signature {
            float: right;
            border-top: 1px solid #000;
            padding-top: 5px;
            width: 200px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="institution-name">{{ $institution->name ?? 'Institution Name' }}</div>
        <div>{{ $institution->address ?? 'Address' }}</div>
        <div class="exam-title">{{ $exam->title }}</div>
    </div>

    <table class="student-info">
        <tr>
            <td><strong>Student Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</td>
            <td><strong>Admission No:</strong> {{ $student->admission_number }}</td>
        </tr>
        <tr>
            <td><strong>Class:</strong> {{ $student->schoolClass->name ?? 'N/A' }}</td>
            <td><strong>Section:</strong> {{ $student->section->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Roll No:</strong> {{ $student->roll_number }}</td>
            <td><strong>Date of Birth:</strong> {{ $student->dob ? $student->dob->format('d-m-Y') : 'N/A' }}</td>
        </tr>
    </table>

    <table class="marks-table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Total Marks</th>
                <th>Pass Marks</th>
                <th>Obtained Marks</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalObtained = 0;
                $totalMax = 0;
            @endphp
            @forelse($marks as $mark)
                <tr>
                    <td>{{ $mark->subject->name ?? 'N/A' }}</td>
                    <td>{{ $mark->total_marks }}</td>
                    <td>{{ $mark->pass_marks }}</td>
                    <td>{{ $mark->marks_obtained }}</td>
                    <td>
                        {{-- Simple grading logic, can be customized --}}
                        @if($mark->marks_obtained >= $mark->pass_marks)
                            Pass
                        @else
                            Fail
                        @endif
                    </td>
                </tr>
                @php
                    $totalObtained += $mark->marks_obtained;
                    $totalMax += $mark->total_marks;
                @endphp
            @empty
                <tr>
                    <td colspan="5">No marks found for this exam.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th>{{ $totalMax }}</th>
                <th></th>
                <th>{{ $totalObtained }}</th>
                <th>{{ $totalMax > 0 ? number_format(($totalObtained / $totalMax) * 100, 2) . '%' : '0%' }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature">
            Principal Signature
        </div>
    </div>

</body>
</html>
