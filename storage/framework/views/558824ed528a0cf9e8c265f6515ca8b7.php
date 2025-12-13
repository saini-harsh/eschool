<?php $__env->startSection('title', 'Print Admission Form'); ?>
<?php $__env->startSection('content'); ?>

    <div class="content">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold">Admission Form</h5>
            <div>
                <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                    <i class="ti ti-printer me-1"></i>Print Form
                </button>
                <a href="<?php echo e(route('institution.admission.success', $admission->id)); ?>" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="print-container">
                    <!-- Header -->
                    <div class="form-header">
                        <div class="header-left">
                            <?php if($admission->institution->logo): ?>
                                <img src="<?php echo e(asset($admission->institution->logo)); ?>" alt="Logo" class="logo-img">
                            <?php else: ?>
                                <div class="logo-placeholder">
                                    <i class="ti ti-school"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="header-center">
                            <h2 class="institution-name"><?php echo e($admission->institution->name); ?></h2>
                            <p class="institution-address"><?php echo e($admission->institution->address); ?></p>
                            <p class="institution-contact">
                                <?php echo e($admission->institution->phone); ?> | <?php echo e($admission->institution->email); ?>

                            </p>
                        </div>
                        <div class="header-right">
                            <div class="form-title">
                                <h1>ADMISSION FORM</h1>
                                <p class="form-number">Admission No: <?php echo e($admission->admission_number ?? 'N/A'); ?></p>
                            </div>
                        </div>
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
                                        <span><?php echo e(date('Y')); ?>-<?php echo e(date('Y') + 1); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Admission Date:</label>
                                        <span><?php echo e($admission->admission_date ? \Carbon\Carbon::parse($admission->admission_date)->format('d M, Y') : 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Admission Number:</label>
                                        <span><?php echo e($admission->admission_number ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Roll Number:</label>
                                        <span><?php echo e($admission->roll_number ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Class:</label>
                                        <span><?php echo e($admission->schoolClass->name ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>PEN Number:</label>
                                        <span><?php echo e($admission->pen_no ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="section">
                            <h3 class="section-title">PERSONAL INFORMATION</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>First Name:</label>
                                        <span><?php echo e($admission->first_name); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Last Name:</label>
                                        <span><?php echo e($admission->last_name); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Gender:</label>
                                        <span><?php echo e($admission->gender ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Date of Birth:</label>
                                        <span><?php echo e($admission->dob ? \Carbon\Carbon::parse($admission->dob)->format('d M, Y') : 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Religion:</label>
                                        <span><?php echo e($admission->religion ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Caste/Tribe:</label>
                                        <span><?php echo e($admission->caste_tribe ?? 'N/A'); ?></span>
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
                                        <span><?php echo e($admission->email ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Phone:</label>
                                        <span><?php echo e($admission->phone ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="field">
                                        <label>Current Address:</label>
                                        <span><?php echo e($admission->address ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Pincode:</label>
                                        <span><?php echo e($admission->pincode ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>District:</label>
                                        <span><?php echo e($admission->district ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="field">
                                        <label>Permanent Address:</label>
                                        <span><?php echo e($admission->permanent_address ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Permanent Pincode:</label>
                                        <span><?php echo e($admission->permanent_pincode ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Permanent District:</label>
                                        <span><?php echo e($admission->permanent_district ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Information -->
                        <div class="section">
                            <h3 class="section-title">MEDICAL INFORMATION</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="field">
                                        <label>Blood Group:</label>
                                        <span><?php echo e($admission->blood_group ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="field">
                                        <label>Height (in):</label>
                                        <span><?php echo e($admission->height ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="field">
                                        <label>Weight (kg):</label>
                                        <span><?php echo e($admission->weight ?? 'N/A'); ?></span>
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
                                        <span><?php echo e($admission->father_name ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Mother Name:</label>
                                        <span><?php echo e($admission->mother_name ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Father Occupation:</label>
                                        <span><?php echo e($admission->father_occupation ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Father Phone:</label>
                                        <span><?php echo e($admission->father_phone ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information -->
                        <div class="section">
                            <h3 class="section-title">GUARDIAN INFORMATION</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Guardian Name:</label>
                                        <span><?php echo e($admission->guardian_name ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Relation:</label>
                                        <span><?php echo e($admission->guardian_relation_text ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field">
                                        <label>Guardian Phone:</label>
                                        <span><?php echo e($admission->guardian_phone ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="field">
                                        <label>Guardian Address:</label>
                                        <span><?php echo e($admission->guardian_address ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Previous Academic Information -->
                        <?php if($admission->previous_school_name): ?>
                            <div class="section">
                                <h3 class="section-title">PREVIOUS ACADEMIC INFORMATION</h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="field">
                                            <label>School Name:</label>
                                            <span><?php echo e($admission->previous_school_name); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="field">
                                            <label>Class:</label>
                                            <span><?php echo e($admission->previousSchoolClass->name ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="field">
                                            <label>School Address:</label>
                                            <span><?php echo e($admission->previous_school_address ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="field">
                                            <label>Result:</label>
                                            <span><?php echo e($admission->previous_school_result ?? 'N/A'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Aadhaar Information -->
                        <?php if($admission->aadhaar_no): ?>
                            <div class="section">
                                <h3 class="section-title">AADHAAR INFORMATION</h3>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="field">
                                            <label>Aadhaar Number:</label>
                                            <span><?php echo e($admission->aadhaar_no); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer -->
                    <div class="form-footer">
                        <p class="footer-text">
                            <strong>Generated On:</strong> <?php echo e(now()->format('d M Y, h:i A')); ?>

                        </p>
                        <p class="footer-text">
                            This is a computer generated form and does not require a signature.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('styles'); ?>
        <style>
            .print-container {
                background: white;
                border: 2px solid #2c3e50;
                border-radius: 8px;
                padding: 0;
                margin: 20px auto;
                max-width: 1000px;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-size: 13px;
                line-height: 1.6;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .form-header {
                background: #f8f9fa;
                color: #2c3e50;
                padding: 20px;
                border-bottom: 2px solid #3e007c;
                display: flex;
                align-items: center;
                justify-content: space-between;
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

            .logo-img {
                max-width: 120px;
                border-radius: 4px;
            }

            .logo-placeholder {
                width: 100px;
                height: 100px;
                background: rgba(52, 62, 80, 0.1);
                border: 2px solid #2c3e50;
                border-radius: 4px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 2.5rem;
            }

            .institution-name {
                font-size: 20px;
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

            .form-title h1 {
                font-size: 18px;
                font-weight: bold;
                margin: 0 0 8px 0;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .form-number {
                font-size: 12px;
                font-weight: 600;
                margin: 0;
            }

            .form-body {
                padding: 25px;
                background: #fafbfc;
            }

            .section {
                background: white;
                padding: 20px;
                margin-bottom: 20px;
                border: 1px solid #e1e8ed;
                border-radius: 6px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .section-title {
                font-size: 14px;
                font-weight: bold;
                color: #2c3e50;
                margin-bottom: 15px;
                padding-bottom: 8px;
                border-bottom: 2px solid #3e007c;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .field {
                margin-bottom: 12px;
                padding-bottom: 8px;
                border-bottom: 1px dotted #bdc3c7;
            }

            .field:last-child {
                border-bottom: none;
            }

            .field label {
                font-weight: 600;
                color: #34495e;
                font-size: 11px;
                display: inline-block;
                min-width: 150px;
                margin-right: 10px;
            }

            .field span {
                color: #2c3e50;
                font-size: 12px;
            }

            .form-footer {
                background: #f8f9fa;
                padding: 15px 20px;
                text-align: center;
                border-top: 2px solid #ecf0f1;
                border-radius: 0 0 6px 6px;
            }

            .footer-text {
                font-size: 10px;
                color: #7f8c8d;
                margin: 5px 0;
            }

            @media print {

                .btn,
                .breadcrumb,
                .page-header,
                .sidebar,
                .navbar,
                .main-header {
                    display: none !important;
                }

                @page {
                    margin: 0.3in !important;
                    size: A4;
                }

                body {
                    margin: 0 !important;
                    padding: 0 !important;
                    background: white !important;
                }

                .print-container {
                    border: 2px solid #2c3e50 !important;
                    box-shadow: none !important;
                    margin: 0 !important;
                    max-width: none !important;
                    width: 100% !important;
                }

                .section {
                    page-break-inside: avoid;
                }
            }
        </style>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.institution', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\Github\envision\eschool\resources\views/institution/administration/students/admission/print-admission-form.blade.php ENDPATH**/ ?>