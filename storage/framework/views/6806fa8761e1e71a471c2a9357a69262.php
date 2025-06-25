<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Selection</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
        }
        .container-selection {
            width: 400px;
            margin: 100px auto;
            background: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            text-align: center;
        }
        .container-selection h1 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #333;
        }

        .btn-selection {
            display: block;
            justify-content: center;
            margin: 15px 0;
            padding: 15px;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 1px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .btn-selection:hover {
            background-color: #3366FF;
        }
        .btn-selection.patient {
            background-color: #28a745;
        }
        .btn-selection.patient:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container-login index">
        <div class="page-title">
            <h1>Welcome to </h1>
            <h1>AMS PKP UNIMAS</h1>
            <p>Login As</p>
        </div>
        <a href="<?php echo e(route('patient.login.page')); ?>" class="btn-selection patient">Patient</a>
        <a href="<?php echo e(route('staff.login.page')); ?>" class="btn-selection">Staff</a>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/index.blade.php ENDPATH**/ ?>