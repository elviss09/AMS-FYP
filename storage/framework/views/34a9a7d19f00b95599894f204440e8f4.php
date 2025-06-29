<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Step 2</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/create-acc.css')); ?>">
</head>
<body>
    <div class="container-register">
        <div class="page-title">
            <h1>Create your account.</h1>
            <p>Step 2 of 4.</p>
        </div>

        <div class="card">
            <!-- Stepper -->
            <div class="stepper">
                <div class="step active">1</div>
                <div class="step-line active"></div>
                <div class="step active">2</div>
                <div class="step-line"></div>
                <div class="step">3</div>
                <div class="step-line"></div>
                <div class="step">4</div>
            </div>

            <div class="card-title">
                Create a secure password.
            </div>

            <form action="<?php echo e(route('create.acc.handleStep2')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="input-group step2">
                    <label for="password">New Password</label>
                    <div class="input-icon">
                        <input type="password" id="password" name="password" id="password" placeholder="Enter your password" required>
                        <div class="icon-hide-pw" id="togglePassword">
                            <img id="toggleIcon" src="<?php echo e(asset('img/unhide.png')); ?>" alt="icon">
                        </div>
                    </div>
                </div>

                <div class="input-group step2">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-icon">
                        <input type="password" id="password2" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
                        <div class="icon-hide-pw" id="togglePassword2">
                            <img id="toggleIcon2" src="<?php echo e(asset('img/unhide.png')); ?>" alt="icon">
                        </div>
                    </div>
                </div>

                <?php if($errors->any()): ?>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p style="color: red;"><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                <button type="submit">Confirm</button>
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

    document.getElementById("togglePassword2").addEventListener("click", function() {
        const passwordInput = document.getElementById("password2");
        const toggleIcon = document.getElementById("toggleIcon2");

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
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/patient/create-acc-step-2.blade.php ENDPATH**/ ?>