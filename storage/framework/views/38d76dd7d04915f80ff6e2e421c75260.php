<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Detail</title>
    <style>
        @page { margin: 24px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #333; }
        .header { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 3px solid <?php echo e($primaryColor); ?>; }
        .brand { font-size: 18px; font-weight: 700; color: <?php echo e($primaryColor); ?>; }
        .small { font-size: 12px; color: #666; }
        .section-title { background: <?php echo e($primaryColor); ?>; color: #fff; padding: 8px 12px; font-weight: 600; margin: 18px 0 8px; }
        .card { border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px; margin-bottom: 12px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 16px; }
        .row { display: flex; align-items: flex-start; margin-bottom: 6px; }
        .label { width: 32%; font-weight: 600; color: #555; }
        .value { width: 68%; }
        .avatar { width: 100px; height: 100px; border-radius: 8px; border: 1px solid #ddd; object-fit: cover; }
        .muted { color: #777; }
        .divider { height: 1px; background: #e5e7eb; margin: 10px 0; }
    </style>
    </head>
<body>
    <div class="header">
        <div>
            <div class="brand"><?php echo e($student->institution->name ?? 'Institution'); ?></div>
            <div class="small">Student Detail Report</div>
        </div>
        <div class="small">Generated on <?php echo e(\Carbon\Carbon::now()->format('d M Y, h:i A')); ?></div>
    </div>

    <div class="section-title">Personal Information</div>
    <div class="card">
        <div class="grid">
            <div>
                <?php if($student->photo): ?>
                    <img class="avatar" src="<?php echo e(public_path($student->photo)); ?>" alt="Student Photo">
                <?php else: ?>
                    <div class="muted">No Photo</div>
                <?php endif; ?>
            </div>
            <div>
                <div class="row"><div class="label">Name</div><div class="value"><?php echo e(trim($student->first_name.' '.$student->middle_name.' '.$student->last_name)); ?></div></div>
                <div class="row"><div class="label">Student ID</div><div class="value"><?php echo e($student->student_id ?? 'N/A'); ?></div></div>
                <div class="row"><div class="label">Email</div><div class="value"><?php echo e($student->email ?? 'N/A'); ?></div></div>
                <div class="row"><div class="label">Phone</div><div class="value"><?php echo e($student->phone ?? 'N/A'); ?></div></div>
                <div class="row"><div class="label">Date of Birth</div><div class="value"><?php echo e($student->dob ? \Carbon\Carbon::parse($student->dob)->format('d M Y') : 'N/A'); ?></div></div>
                <div class="row"><div class="label">Gender</div><div class="value"><?php echo e($student->gender ?? 'N/A'); ?></div></div>
            </div>
        </div>
    </div>

    <div class="section-title">Academic Information</div>
    <div class="card">
        <div class="row"><div class="label">Institution</div><div class="value"><?php echo e($student->institution->name ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Class</div><div class="value"><?php echo e($student->schoolClass->name ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Section</div><div class="value"><?php echo e($student->section->name ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Teacher</div><div class="value"><?php echo e($student->teacher ? ($student->teacher->first_name.' '.$student->teacher->last_name) : 'N/A'); ?></div></div>
        <div class="row"><div class="label">Admission Date</div><div class="value"><?php echo e($student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M Y') : 'N/A'); ?></div></div>
        <div class="row"><div class="label">Institution Code</div><div class="value"><?php echo e($student->institution_code ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">PEN Number</div><div class="value"><?php echo e($student->pen_no ?? 'N/A'); ?></div></div>
    </div>

    <div class="section-title">Address Information</div>
    <div class="card">
        <div class="row"><div class="label">Current Address</div><div class="value"><?php echo e($student->address ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Permanent Address</div><div class="value"><?php echo e($student->permanent_address ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">District</div><div class="value"><?php echo e($student->district ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Pincode</div><div class="value"><?php echo e($student->pincode ?? 'N/A'); ?></div></div>
    </div>

    <div class="section-title">Parents & Guardian</div>
    <div class="card">
        <div class="row"><div class="label">Father Name</div><div class="value"><?php echo e($student->father_name ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Father Phone</div><div class="value"><?php echo e($student->father_phone ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Father Occupation</div><div class="value"><?php echo e($student->father_occupation ?? 'N/A'); ?></div></div>
        <div class="divider"></div>
        <div class="row"><div class="label">Mother Name</div><div class="value"><?php echo e($student->mother_name ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Mother Phone</div><div class="value"><?php echo e($student->mother_phone ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Mother Occupation</div><div class="value"><?php echo e($student->mother_occupation ?? 'N/A'); ?></div></div>
        <div class="divider"></div>
        <div class="row"><div class="label">Guardian Name</div><div class="value"><?php echo e($student->guardian_name ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Guardian Phone</div><div class="value"><?php echo e($student->guardian_phone ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Guardian Relation</div><div class="value"><?php echo e($student->guardian_relation ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Guardian Occupation</div><div class="value"><?php echo e($student->guardian_occupation ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Guardian Address</div><div class="value"><?php echo e($student->guardian_address ?? 'N/A'); ?></div></div>
    </div>

    <div class="section-title">Documents</div>
    <div class="card">
        <div class="row"><div class="label">Aadhaar Number</div><div class="value"><?php echo e($student->aadhaar_no ?? 'N/A'); ?></div></div>
        <?php if($student->aadhaar_front || $student->aadhaar_back): ?>
            <div class="grid">
                <div>
                    <?php if($student->aadhaar_front): ?>
                        <img class="avatar" src="<?php echo e(public_path($student->aadhaar_front)); ?>" alt="Aadhaar Front">
                    <?php endif; ?>
                </div>
                <div>
                    <?php if($student->aadhaar_back): ?>
                        <img class="avatar" src="<?php echo e(public_path($student->aadhaar_back)); ?>" alt="Aadhaar Back">
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="divider"></div>
        <div class="row"><div class="label">PAN Number</div><div class="value"><?php echo e($student->pan_no ?? 'N/A'); ?></div></div>
        <?php if($student->pan_front || $student->pan_back): ?>
            <div class="grid">
                <div>
                    <?php if($student->pan_front): ?>
                        <img class="avatar" src="<?php echo e(public_path($student->pan_front)); ?>" alt="PAN Front">
                    <?php endif; ?>
                </div>
                <div>
                    <?php if($student->pan_back): ?>
                        <img class="avatar" src="<?php echo e(public_path($student->pan_back)); ?>" alt="PAN Back">
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="divider"></div>
        <div class="row"><div class="label">Other Documents</div><div class="value">
            <?php $docs = []; ?>
            <?php if($student->document_01_file): ?>
                <?php $docs[] = ($student->document_01_title ?? 'Document 01'); ?>
            <?php endif; ?>
            <?php if($student->document_02_file): ?>
                <?php $docs[] = ($student->document_02_title ?? 'Document 02'); ?>
            <?php endif; ?>
            <?php if($student->document_03_file): ?>
                <?php $docs[] = ($student->document_03_title ?? 'Document 03'); ?>
            <?php endif; ?>
            <?php if($student->document_04_file): ?>
                <?php $docs[] = ($student->document_04_title ?? 'Document 04'); ?>
            <?php endif; ?>
            <?php echo e(empty($docs) ? 'N/A' : implode(', ', $docs)); ?>

        </div></div>
    </div>

    <div class="section-title">Medical Information</div>
    <div class="card">
        <div class="row"><div class="label">Blood Group</div><div class="value"><?php echo e($student->blood_group ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Category</div><div class="value"><?php echo e($student->category ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Caste/Tribe</div><div class="value"><?php echo e($student->caste_tribe ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">District</div><div class="value"><?php echo e($student->district ?? 'N/A'); ?></div></div>
    </div>

    <div class="section-title">Student Settings</div>
    <div class="card">
        <div class="row"><div class="label">Email</div><div class="value"><?php echo e($student->email ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Phone</div><div class="value"><?php echo e($student->phone ?? 'N/A'); ?></div></div>
        <div class="row"><div class="label">Status</div><div class="value"><?php echo e(($student->status ?? 0) == 1 || ($student->status ?? '') === 'active' ? 'Active' : 'Inactive'); ?></div></div>
    </div>

    <div class="small">This is a system-generated document.</div>
</body>
</html>
<?php /**PATH E:\eschool\resources\views/admin/administration/students/pdf.blade.php ENDPATH**/ ?>