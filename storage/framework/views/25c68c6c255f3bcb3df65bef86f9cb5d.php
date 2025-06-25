<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
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
        <div class="card">
            <form method="POST" action="<?php echo e(route('staff.login')); ?>">
                <?php echo csrf_field(); ?>
                <div class="input-group">
                    <label for="staff_id">Staff ID</label>
                    <div class="input-icon">
                        <div class="icon-mykad"><img src="<?php echo e(asset('img/staff-id.png')); ?>" alt="icon"></div>
                        <input type="text" id="staff_id" name="staff_id" value="<?php echo e(old('staff_id')); ?>" placeholder="Enter your Staff ID" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <div class="icon-password"><img src="<?php echo e(asset('img/lock.png')); ?>" alt="icon"></div>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <div class="icon-hide-pw"><img src="<?php echo e(asset('img/unhide.png')); ?>" alt="icon"></div>
                    </div>
                </div>

                <?php if($errors->any()): ?>
                    <p style="color: red;"><?php echo e($errors->first()); ?></p>
                <?php endif; ?>

                <button type="submit" class="btn">Log In</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/auth/staff-login.blade.php ENDPATH**/ ?>