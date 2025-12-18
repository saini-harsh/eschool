<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Student Detail</title>
    <style>
        @page {
            margin: 24px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #333;
            font-size: 13px;
            line-height: 1.4;
        }

        .header-table {
            width: 100%;
            border-bottom: 3px solid {{ $primaryColor }};
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .brand {
            font-size: 20px;
            font-weight: 700;
            color: {{ $primaryColor }};
        }

        .small {
            font-size: 11px;
            color: #666;
        }

        .section-title {
            background: {{ $primaryColor }};
            color: #fff;
            padding: 6px 12px;
            font-weight: 600;
            margin: 15px 0 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .label {
            width: 30%;
            font-weight: 600;
            color: #555;
        }

        .value {
            width: 70%;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .muted {
            color: #777;
        }

        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 10px 0;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .photo-cell {
            width: 120px;
            text-align: center;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td>
                <div class="brand">{{ $student->institution->name ?? 'Institution' }}</div>
                <div class="small">Student Detail Report</div>
            </td>
            <td style="text-align: right; vertical-align: bottom;">
                <div class="small">Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Personal Information</div>
    <div class="card">
        <table style="width: 100%;">
            <tr>
                <td class="photo-cell">
                    @if ($student->photo && file_exists(public_path($student->photo)))
                        <img class="avatar" src="{{ public_path($student->photo) }}" alt="Photo">
                    @else
                        <div class="muted" style="border: 1px solid #ddd; padding: 30px 10px; border-radius: 8px;">No
                            Photo</div>
                    @endif
                </td>
                <td>
                    <table class="info-table">
                        <tr>
                            <td class="label">Name</td>
                            <td class="value">
                                {{ trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name) }}</td>
                        </tr>
                        <tr>
                            <td class="label">Student ID</td>
                            <td class="value">{{ $student->student_id ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Email</td>
                            <td class="value">{{ $student->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Phone</td>
                            <td class="value">{{ $student->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Date of Birth</td>
                            <td class="value">
                                {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d M Y') : 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Gender</td>
                            <td class="value">{{ $student->gender ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Academic Information</div>
    <div class="card">
        <table class="info-table">
            <tr>
                <td class="label">Institution</td>
                <td class="value">{{ $student->institution->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Class</td>
                <td class="value">{{ $student->schoolClass->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Section</td>
                <td class="value">{{ $student->section->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Teacher</td>
                <td class="value">
                    {{ $student->teacher ? $student->teacher->first_name . ' ' . $student->teacher->last_name : 'N/A' }}
                </td>
            </tr>
            <tr>
                <td class="label">Admission Date</td>
                <td class="value">
                    {{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M Y') : 'N/A' }}
                </td>
            </tr>
            <tr>
                <td class="label">Institution Code</td>
                <td class="value">{{ $student->institution_code ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">PEN Number</td>
                <td class="value">{{ $student->pen_no ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Address Information</div>
    <div class="card">
        <table class="info-table">
            <tr>
                <td class="label">Current Address</td>
                <td class="value">{{ $student->address ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Permanent Address</td>
                <td class="value">{{ $student->permanent_address ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">District</td>
                <td class="value">{{ $student->district ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Pincode</td>
                <td class="value">{{ $student->pincode ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Parents & Guardian</div>
    <div class="card">
        <table class="info-table">
            <tr>
                <td class="label">Father Name</td>
                <td class="value">{{ $student->father_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Father Phone</td>
                <td class="value">{{ $student->father_phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Father Occupation</td>
                <td class="value">{{ $student->father_occupation ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="divider"></div>
                </td>
            </tr>
            <tr>
                <td class="label">Mother Name</td>
                <td class="value">{{ $student->mother_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Mother Phone</td>
                <td class="value">{{ $student->mother_phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Mother Occupation</td>
                <td class="value">{{ $student->mother_occupation ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="divider"></div>
                </td>
            </tr>
            <tr>
                <td class="label">Guardian Name</td>
                <td class="value">{{ $student->guardian_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Guardian Phone</td>
                <td class="value">{{ $student->guardian_phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Relation</td>
                <td class="value">{{ $student->guardian_relation ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Address</td>
                <td class="value">{{ $student->guardian_address ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Documents</div>
    <div class="card">
        <table class="info-table">
            <tr>
                <td class="label">Aadhaar Number</td>
                <td class="value">{{ $student->aadhaar_no ?? 'N/A' }}</td>
            </tr>
        </table>

        @if ($student->aadhaar_front || $student->aadhaar_back)
            <table style="width: 100%; margin-top: 10px;">
                <tr>
                    <td style="text-align: center; width: 50%;">
                        @if ($student->aadhaar_front && file_exists(public_path($student->aadhaar_front)))
                            <div class="small">Front</div>
                            <img class="avatar" src="{{ public_path($student->aadhaar_front) }}" alt="Front">
                        @endif
                    </td>
                    <td style="text-align: center; width: 50%;">
                        @if ($student->aadhaar_back && file_exists(public_path($student->aadhaar_back)))
                            <div class="small">Back</div>
                            <img class="avatar" src="{{ public_path($student->aadhaar_back) }}" alt="Back">
                        @endif
                    </td>
                </tr>
            </table>
        @endif

        <div class="divider"></div>
        <table class="info-table">
            <tr>
                <td class="label">PAN Number</td>
                <td class="value">{{ $student->pan_no ?? 'N/A' }}</td>
            </tr>
        </table>

        @if ($student->pan_front || $student->pan_back)
            <table style="width: 100%; margin-top: 10px;">
                <tr>
                    <td style="text-align: center; width: 50%;">
                        @if ($student->pan_front && file_exists(public_path($student->pan_front)))
                            <div class="small">Front</div>
                            <img class="avatar" src="{{ public_path($student->pan_front) }}" alt="Front">
                        @endif
                    </td>
                    <td style="text-align: center; width: 50%;">
                        @if ($student->pan_back && file_exists(public_path($student->pan_back)))
                            <div class="small">Back</div>
                            <img class="avatar" src="{{ public_path($student->pan_back) }}" alt="Back">
                        @endif
                    </td>
                </tr>
            </table>
        @endif
    </div>

    <div class="section-title">Medical Information</div>
    <div class="card">
        <table class="info-table">
            <tr>
                <td class="label">Blood Group</td>
                <td class="value">{{ $student->blood_group ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Category</td>
                <td class="value">{{ $student->category ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Caste/Tribe</td>
                <td class="value">{{ $student->caste_tribe ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">District</td>
                <td class="value">{{ $student->district ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px;">
        <div class="small">This is a system-generated document.</div>
    </div>
</body>

</html>
