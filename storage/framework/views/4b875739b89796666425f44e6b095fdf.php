<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Accepted</title>
</head>
<body>
    <p>Dear <strong><?php echo e($patientName); ?></strong>,</p>
    <p>Your appointment (ID: <strong><?php echo e($appointmentId); ?></strong>) has been <strong>accepted</strong>.</p>
    <p>Thank you,<br>PRIMA UNIMAS Health Center</p>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/emails/appointment-accepted.blade.php ENDPATH**/ ?>