<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Created Successfully</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/create-acc.css')); ?>">
    <style>
        .success-container {
            max-width: 500px;
            margin: 80px auto;
            text-align: center;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background: #f9fff9;
        }
        .success-container h1 {
            color: #16A34A;
            margin-bottom: 20px;
        }
        .btn-login {
            background: #16A34A;
            color: #fff;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-login:hover {
            background: #128C3A;
        }
    </style>
</head>
<body>

    <div class="success-container">
        <h1>Account Created Successfully!</h1>
        <p>Your account has been created. You can now log in to the system.</p>
        <a href="<?php echo e(route('patient.login')); ?>" class="btn-login">Proceed to Login</a>
    </div>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/patient/create-acc-success.blade.php ENDPATH**/ ?>