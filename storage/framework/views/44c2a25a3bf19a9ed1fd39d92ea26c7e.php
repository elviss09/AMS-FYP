<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
</head>
<body>
    <div class="container-login">
        <div class="page-title">
            <h1>Welcome Back</h1>
            <p>Log in to your account.</p>
        </div> 
        <?php if(session('success')): ?>
            <div class="success-message" style="color: green; font-weight: bold; margin-bottom: 10px;">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <div class="card">
            <form method="POST" action="<?php echo e(route('patient.login')); ?>">
                <?php echo csrf_field(); ?>

                <div class="input-group">
                    <label for="mykad">MyKad</label>
                    <div class="input-icon">
                        <div class="icon-mykad"><img src="<?php echo e(asset('img/id-card.png')); ?>" alt="icon"></div>
                        <input type="text" id="mykad" name="mykad" placeholder="Enter your MyKad number" required value="<?php echo e(old('mykad')); ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <div class="icon-password"><img src="<?php echo e(asset('img/lock.png')); ?>" alt="icon"></div>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <div class="icon-hide-pw" id="togglePassword">
                            <img id="toggleIcon" src="<?php echo e(asset('img/unhide.png')); ?>" alt="icon">
                        </div>
                    </div>
                </div>

                <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p style="color: red;"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                
                <div style="margin-top: 10px; text-align: right; font-style:italic">
                    <a href="<?php echo e(route('password.request')); ?>" style="font-size: 14px; color: #007bff;">Forgot your password?</a>
                </div>

                <button type="submit" class="btn">Enter</button>
                <a href="<?php echo e(route('register.step1')); ?>" class="register-button">Register</a>
            </form>
        </div>
    </div>

<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        const passwordInput = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon");

        const isPassword = passwordInput.getAttribute("type") === "password";
        passwordInput.setAttribute("type", isPassword ? "text" : "password");

        // Change icon image
        toggleIcon.src = isPassword 
            ? "<?php echo e(asset('img/hide.png')); ?>" 
            : "<?php echo e(asset('img/unhide.png')); ?>";
    });
</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/auth/patient-login.blade.php ENDPATH**/ ?>