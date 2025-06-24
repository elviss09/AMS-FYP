<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - MyKad</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/forgot-password.css')); ?>">
</head>
<body>
    <div class="container-login">
        <div class="card">
            <form method="POST" action="<?php echo e(route('password.checkMyKad')); ?>">
                <?php echo csrf_field(); ?>

                <div>
                    <div class="card-title">Forgot your password?</div>
                    <div class="card-details">Enter your MyKad number to continue.</div>
                </div>

                <div class="input-group">
                    <label for="mykad">MyKad</label>
                    <div class="input-icon">
                        <div class="icon-mykad"><img src="<?php echo e(asset('img/id-card.png')); ?>" alt="icon"></div>
                        <input type="text" id="mykad" name="mykad" placeholder="Enter your MyKad" required>
                    </div>
                </div>

                <?php $__errorArgs = ['mykad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <button type="submit">Next</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/auth/passwords/forgot-password.blade.php ENDPATH**/ ?>