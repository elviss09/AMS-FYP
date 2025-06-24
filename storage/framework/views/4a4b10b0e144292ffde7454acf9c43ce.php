<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/forgot-password.css')); ?>">
</head>
<body>
    <div class="container-login">
        <div class="card">
            <form method="POST" action="<?php echo e(route('password.verifyOtp')); ?>">
                <?php echo csrf_field(); ?>
                <div>
                    <div class="card-title">OTP Verification</div>
                    <div class="card-details">Enter OTP code sent to your email.</div>
                </div>

                <label for="otp">OTP Code</label>
                <div class="input-icon">
                    <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
                </div>

                <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <button type="submit">Verify</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/auth/passwords/verify-otp.blade.php ENDPATH**/ ?>