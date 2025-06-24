<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Email</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/forgot-password.css')); ?>">
</head>
<body>
    <div class="container-login">
        <div class="card">
            <div>
                <div class="card-title">An OTP will be sent to this email.</div>
            </div>
            <p style="margin-top: 20px;"><?php echo e($email); ?></p>

            <form method="POST" action="<?php echo e(route('password.sendOtp')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit">Send OTP</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/auth/passwords/email.blade.php ENDPATH**/ ?>