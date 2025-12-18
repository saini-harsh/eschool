@extends('layouts.institution')
@section('title', 'Print Admission Form')
@section('content')

    <style>
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .print-container {
            background: white;
            border: 1px solid #ddd;
            padding: 0;
            margin: 20px auto;
            width: 210mm;
            min-height: 297mm;
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            box-sizing: border-box;
        }

        .form-header {
            background: #f5f5f5;
            padding: 15px 20px;
            border-bottom: 3px solid #333;
            display: table;
            width: 100%;
            table-layout: fixed;
            page-break-inside: avoid;
            box-sizing: border-box;
        }

        .header-left {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
            padding-right: 20px;
        }

        .header-center {
            display: table-cell;
            width: auto;
            text-align: center;
            vertical-align: middle;
            padding: 0 15px;
        }

        .logo-img {
            width: 100px;
            max-width: 100px;
            height: auto;
            max-height: 100px;
            display: block;
            object-fit: contain;
        }

        .logo-placeholder {
            width: 100px;
            height: 100px;
            background: #e0e0e0;
            border: 2px solid #999;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #666;
        }

        .institution-name {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000;
        }

        .institution-address {
            font-size: 11px;
            margin: 3px 0;
            color: #333;
            line-height: 1.3;
        }

        .institution-contact {
            font-size: 10px;
            margin: 3px 0 0 0;
            color: #333;
        }

        .form-title-section {
            background: white;
            padding: 15px 20px;
            text-align: center;
            border-bottom: 2px solid #333;
            page-break-inside: avoid;
        }

        .form-title-text {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #000;
        }

        .form-number {
            font-size: 11px;
            font-weight: 600;
            margin: 0;
            color: #333;
        }

        .form-body {
            padding: 20px;
            background: white;
        }

        .section {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin-bottom: 12px;
            padding-bottom: 6px;
            border-bottom: 2px solid #333;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .field {
            margin-bottom: 8px;
            padding-bottom: 6px;
            display: flex;
            align-items: flex-start;
        }

        .field:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .field label {
            font-weight: 600;
            color: #000;
            font-size: 10px;
            min-width: 140px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .field span {
            color: #000;
            font-size: 10px;
            flex: 1;
            word-wrap: break-word;
        }

        .form-footer {
            background: #f5f5f5;
            padding: 20px;
            border-top: 2px solid #ddd;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-top: 10px;
        }

        .signature-left,
        .signature-right {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-bottom: 2px solid #333;
            height: 50px;
            margin-bottom: 8px;
            width: 100%;
        }

        .signature-label {
            font-size: 11px;
            font-weight: 600;
            color: #000;
            margin: 0;
            text-transform: uppercase;
        }

        .footer-text-section {
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
        }

        .footer-text {
            font-size: 9px;
            color: #666;
            margin: 2px 0;
        }

        .personal-info-row {
            align-items: flex-start;
        }

        .photo-column {
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        .student-photo-container {
            text-align: center;
            padding: 0;
            width: 100%;
        }

        .student-photo {
            max-width: 50px;
            max-height: 50px;
            width: auto;
            height: auto;
            border: 2px solid #ddd;
            /* padding: 3px; */
            display: block;
            /* margin: 0 auto; */
        }

        .details-column .field label {
            min-width: 120px;
        }

        @media print {

            .btn,
            .breadcrumb,
            .page-header,
            .sidebar,
            .navbar,
            .main-header,
            .content>.d-flex {
                display: none !important;
            }

            @page {
                margin: 0 !important;
                size: A4;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                font-size: 12px !important;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
            }

            .content {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            .print-container {
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
                max-width: 100% !important;
                width: 210mm !important;
                min-height: 297mm !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }

            .form-header {
                background: #f5f5f5 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                display: table !important;
                width: 100% !important;
                table-layout: fixed !important;
            }

            .header-left {
                display: table-cell !important;
                width: 120px !important;
                vertical-align: middle !important;
                padding-right: 20px !important;
            }

            .header-center {
                display: table-cell !important;
                width: auto !important;
                text-align: center !important;
                vertical-align: middle !important;
                padding: 0 15px !important;
            }

            .logo-img {
                width: 100px !important;
                max-width: 100px !important;
                height: auto !important;
                display: block !important;
            }

            .logo-placeholder {
                width: 100px !important;
                height: 100px !important;
                display: inline-flex !important;
            }

            .form-title-section {
                background: white !important;
                page-break-inside: avoid !important;
            }

            .form-body {
                background: white !important;
                padding: 20px !important;
            }

            .section {
                border: 1px solid #ddd !important;
                page-break-inside: avoid !important;
                margin-bottom: 15px !important;
                padding: 15px !important;
            }

            .section-title {
                border-bottom: 2px solid #333 !important;
            }

            .form-footer {
                background: #f5f5f5 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .signature-section {
                display: flex !important;
                justify-content: space-between !important;
            }

            .signature-line {
                border-bottom: 2px solid #333 !important;
            }

            .row {
                margin-left: -15px !important;
                margin-right: -15px !important;
            }

            .col-md-6,
            .col-md-4,
            .col-md-12,
            .col-md-3,
            .col-md-9 {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            /* Preserve exact layout from screen */
            .personal-info-row {
                display: flex !important;
                align-items: flex-start !important;
                flex-wrap: nowrap !important;
            }

            .photo-column {
                flex: 0 0 25% !important;
                max-width: 25% !important;
                width: 25% !important;
                display: flex !important;
                align-items: flex-start !important;
                justify-content: center !important;
            }

            .details-column {
                flex: 0 0 75% !important;
                max-width: 75% !important;
                width: 75% !important;
            }

            .details-column .field label {
                min-width: 120px !important;
            }

            .student-photo-container {
                width: 100% !important;
            }

            .student-photo {
                max-width: 50px !important;
                max-height: 60px !important;
                margin: 0 auto !important;
            }
        }
    </style>

    <div class="content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold">Admission Form</h5>
            <div>
                <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                    <i class="ti ti-printer me-1"></i>Print Form
                </button>
                <a href="{{ route('institution.admission.success', $admission->id) }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="print-container">
                    <!-- Letterhead Header -->
                    <div class="form-header">
                        <div class="header-left">
                            @if ($admission->institution->logo)
                                <img src="{{ asset($admission->institution->logo) }}" alt="Logo" class="logo-img">
                            @else
                                <div class="logo-placeholder">
                                    <i class="ti ti-school"></i>
                                </div>
                            @endif
                        </div>
                        <div class="header-center">
                            <h2 class="institution-name">{{ $admission->institution->name }}</h2>
                            <p class="institution-address">{{ $admission->institution->address }}</p>
                            <p class="institution-contact">
                                @if ($admission->institution->phone)
                                    Phone: {{ $admission->institution->phone }}
                                @endif
                                @if ($admission->institution->phone && $admission->institution->email)
                                    |
                                @endif
                                @if ($admission->institution->email)
                                    Email: {{ $admission->institution->email }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Form Title Section -->
                    <div class="form-title-section">
                        <h1 class="form-title-text">ADMISSION FORM</h1>
                        <p class="form-number">Admission No: {{ $admission->admission_number ?? 'N/A' }}</p>
                    </div>

                    <!-- Form Body -->
                    <div class="form-body">
                        <!-- Academic Information -->
                        <div class="section">
                            <h3 class="section-title">ACADEMIC INFORMATION</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Academic Year:</label>
                                        <span>{{ date('Y') }}-{{ date('Y') + 1 }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Admission Date:</label>
                                        <span>{{ $admission->admission_date ? \Carbon\Carbon::parse($admission->admission_date)->format('d M, Y') : 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Admission Number:</label>
                                        <span>{{ $admission->admission_number ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Roll Number:</label>
                                        <span>{{ $admission->roll_number ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Class:</label>
                                        <span>{{ $admission->schoolClass->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>PEN Number:</label>
                                        <span>{{ $admission->pen_no ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="section">
                            <h3 class="section-title">PERSONAL INFORMATION</h3>
                            <div class="row personal-info-row">
                                <!-- Photo on the left -->
                                <div class="col-md-2 photo-column">
                                    @if ($admission->photo)
                                        <div class="student-photo-container">
                                            <img src="{{ asset($admission->photo) }}" alt="Student Photo"
                                                class="student-photo">
                                        </div>
                                    @endif
                                </div>
                                <!-- Details on the right -->
                                <div class="col-md-10 details-column">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="field">
                                                <label>First Name:</label>
                                                <span>{{ $admission->first_name }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field">
                                                <label>Last Name:</label>
                                                <span>{{ $admission->last_name }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field">
                                                <label>Gender:</label>
                                                <span>{{ $admission->gender ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field">
                                                <label>Date of Birth:</label>
                                                <span>{{ $admission->dob ? \Carbon\Carbon::parse($admission->dob)->format('d M, Y') : 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field">
                                                <label>DOB Status:</label>
                                                <span>{{ $admission->dob_status ?? 'Not Verified' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field">
                                                <label>Age (as of Jan 2026):</label>
                                                <span>{{ $admission->age_years ?? '0' }} Years,
                                                    {{ $admission->age_months ?? '0' }} Months</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field">
                                                <label>Religion:</label>
                                                <span>{{ $admission->religion ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="field">
                                                <label>Caste/Tribe:</label>
                                                <span>{{ $admission->caste_tribe ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="section">
                            <h3 class="section-title">CONTACT INFORMATION</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Email:</label>
                                        <span>{{ $admission->email ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Phone:</label>
                                        <span>{{ $admission->phone ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="field">
                                        <label>Current Address:</label>
                                        <span>{{ $admission->address ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Pincode:</label>
                                        <span>{{ $admission->pincode ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>District:</label>
                                        <span>{{ $admission->district ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="field">
                                        <label>Permanent Address:</label>
                                        <span>{{ $admission->permanent_address ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Permanent Pincode:</label>
                                        <span>{{ $admission->permanent_pincode ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Permanent District:</label>
                                        <span>{{ $admission->permanent_district ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Information -->
                        <div class="section d-none">
                            <h3 class="section-title">MEDICAL INFORMATION</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="field">
                                        <label>Blood Group:</label>
                                        <span>{{ $admission->blood_group ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="field">
                                        <label>Height (in):</label>
                                        <span>{{ $admission->height ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="field">
                                        <label>Weight (kg):</label>
                                        <span>{{ $admission->weight ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Parents Information -->
                        <div class="section">
                            <h3 class="section-title">PARENTS INFORMATION</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Father Name:</label>
                                        <span>{{ $admission->father_name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Mother Name:</label>
                                        <span>{{ $admission->mother_name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Father Occupation:</label>
                                        <span>{{ $admission->father_occupation ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Father Phone:</label>
                                        <span>{{ $admission->father_phone ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information -->
                        @if ($admission->guardian_name)
                            <div class="section">
                                <h3 class="section-title">GUARDIAN INFORMATION</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="field">
                                            <label>Guardian Name:</label>
                                            <span>{{ $admission->guardian_name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="field">
                                            <label>Relation:</label>
                                            <span>{{ $admission->guardian_relation_text ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="field">
                                            <label>Guardian Phone:</label>
                                            <span>{{ $admission->guardian_phone ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="field">
                                            <label>Guardian Address:</label>
                                            <span>{{ $admission->guardian_address ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Previous Academic Information -->
                        @if ($admission->previous_school_name)
                            <div class="section">
                                <h3 class="section-title">PREVIOUS ACADEMIC INFORMATION</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="field">
                                            <label>School Name:</label>
                                            <span>{{ $admission->previous_school_name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="field">
                                            <label>Class:</label>
                                            <span>{{ $admission->previousSchoolClass->name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="field">
                                            <label>School Address:</label>
                                            <span>{{ $admission->previous_school_address ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="field">
                                            <label>Result:</label>
                                            <span>{{ $admission->previous_school_result ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Aadhaar Information -->
                        @if ($admission->aadhaar_no)
                            <div class="section">
                                <h3 class="section-title">AADHAAR INFORMATION</h3>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="field">
                                            <label>Aadhaar Number:</label>
                                            <span>{{ $admission->aadhaar_no }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="signature-section">
                            <div class="signature-left">
                                <div class="signature-line"></div>
                                <p class="signature-label">Parent's Signature</p>
                            </div>
                            <div class="signature-right">
                                <div class="signature-line"></div>
                                <p class="signature-label">Principal's Signature</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
