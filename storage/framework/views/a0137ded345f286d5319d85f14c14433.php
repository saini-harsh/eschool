<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student ID Card</title>
    <style>
        @page { margin: 12mm; }
        body {
            font-family: 'Arial', sans-serif;
            background: #e8f3ff;
            padding: 30px;
        }

        .id-card {
            display: flex;
            gap: 30px;
        }

        /* Common Card Styling */
        .card {
            width: 260px;
            height: 410px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            padding: 0;
        }

        /* Front */
        .front .school-header {
            background: #0b62d4;
            color: #fff;
            text-align: center;
            padding: 15px 10px;
        }

        .photo-box {
            text-align: center;
            margin-top: -20px;
        }

        .photo-box img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid #fff;
        }

        .details {
            padding: 20px;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Back */
        .back,.front {
            padding: 20px;
            margin-top: 5px;
        }

        .back h3 {
            background: #0b62d4;
            color: #fff;
            padding: 8px;
            font-size: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .terms {
            font-size: 12px;
            margin: 10px 0 20px;
            padding-left: 18px;
        }

        .contact-info, .dates {
            font-size: 13px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .principal {
            text-align: left;
            margin: 15px 0;
            font-weight: bold;
        }

        .footer-logo {
            text-align: center;
            margin-top: 25px;
        }

        .footer-logo img {
            width: 60px;
            margin-bottom: 5px;
        }

    </style>
    </head>
<body>
    <div class="id-card">

    <!-- Front Side -->
    <div class="card front">
        <div class="school-header">
            <h3><?php echo e($student->institution->name ?? 'School Name'); ?></h3>
            <p><?php echo e($student->institution->board ?? '(Govt. Recognised)'); ?></p>
        </div>

        <div class="photo-box">
            <?php if($student->photo): ?>
                <img src="<?php echo e(public_path($student->photo)); ?>" alt="Student Photo">
            <?php endif; ?>
        </div>

        <div class="details">
            <p><strong>ID:</strong> <?php echo e($student->student_id ?? $student->id); ?></p>
            <p><strong>Name:</strong> <?php echo e(trim($student->first_name.' '.$student->middle_name.' '.$student->last_name)); ?></p>
            <p><strong>Class:</strong> <?php echo e($student->schoolClass->name ?? 'N/A'); ?><?php echo e($student->section ? ' - '.$student->section->name : ''); ?></p>
            <p><strong>Father:</strong> <?php echo e($student->father_name ?? 'N/A'); ?></p>
        </div>
    </div>

    <!-- Back Side -->
    <div class="card back">

        <div class="contact-info">
            <p><strong>Phone :</strong> <?php echo e($student->phone ?? 'N/A'); ?></p>
            <p><strong>Email :</strong> <?php echo e($student->email ?? 'N/A'); ?></p>
            <p><strong>Website :</strong> <?php echo e($student->institution->website ?? 'N/A'); ?></p>
        </div>

        <div class="principal">
            <p>Principal</p>
        </div>

        <div class="dates">
            <p><strong>Joined Date :</strong> <?php echo e($student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d/m/Y') : 'N/A'); ?></p>
        </div>

        <div class="footer-logo">
            <?php if(isset($student->institution->logo) && $student->institution->logo): ?>
                <img src="<?php echo e(public_path($student->institution->logo)); ?>" alt="Logo">
            <?php endif; ?>
            <p><?php echo e($student->institution->board ?? ($student->institution->website ?? '')); ?></p>
        </div>
    </div>

</div>

</body>
</html>
<?php /**PATH E:\eschool\resources\views/admin/administration/students/id-card.blade.php ENDPATH**/ ?>