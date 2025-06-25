<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/staff-sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin-dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/patient-calendar.css')); ?>">
</head>

<body>
    <div class="container">
        <div class="sidebar-box">
            <?php echo $__env->make('staff.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <div class="content">
            <div class="page-header">
                <header>
                    <h1>Dashboard</h1>
                </header>
            </div>

            <div class="mid-content">
                <section class="welcome-card">
                    <div class="welcome-text">
                        <h3><span class="hello-text">Hello</span> <?php echo e($staff->full_name); ?>,</h3>
                        <p>Have a nice day and don’t forget to take care of your health!</p>
                    </div>
                    <div class="welcome-img">
                        <img src="<?php echo e(asset('img/welcome-icon.svg')); ?>" alt="icon">
                    </div>             
                </section>

                <section class="stats">
                    <div class="stat-box">
                        <div class="stat-title">
                            <p>New Staff Added Today</p>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo e($newStaffToday); ?></h3>
                        </div>
                        <div class="stat-icon-calendar">
                            <span class="stat-img-calendar"><img src="<?php echo e(asset('img/calendar-icon.png')); ?>" alt="icon"></span>
                        </div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-title">
                            <p>Total Active Staff</p>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo e($totalActiveStaff); ?></h3>
                        </div>
                        <div class="stat-icon-clock">
                            <span class="stat-img-clock"><img src="<?php echo e(asset('img/wall-clock.png')); ?>" alt="icon"></span>
                        </div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-title">
                            <p>Total Appointment Made</p>
                        </div>
                        <div class="stat-content">
                            <h3><?php echo e($totalAppointments); ?></h3>
                        </div>
                        <div class="stat-icon-check">
                            <span class="stat-img-check"><img src="<?php echo e(asset('img/check-mark.png')); ?>" alt="icon"></span>
                        </div>
                    </div>
                </section>

                <section class="appointments">
                    <h2>Upcoming Appointments</h2>
                    <?php if(count($upcomingAppointments) > 0): ?>
                        <?php $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="appointment-card">
                                <div class="appointment-type">
                                    <p><?php echo e($appointment->appointment_type); ?></p>
                                </div>
                                <div class="appointment-place">
                                    <p><?php echo e($appointment->appointment_location); ?></p>
                                </div>
                                <div class="date">
                                    <p><?php echo e(\Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time)->format('M j, g:i A')); ?></p>
                                </div>
                                <div class="status">
                                    <span class="<?php echo e(strtolower(str_replace(' ', '-', $appointment->status))); ?>">
                                        <?php echo e($appointment->status); ?>

                                    </span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p>No upcoming appointments.</p>
                    <?php endif; ?>
                </section>
            </div>

            <div class="right-content">
                <section class="calendar-box">
                    <?php echo $__env->make('staff.staff-calendar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </section>

                <!-- <section class="notifications">
                    <h2>Recent Notifications</h2>
                    <div class="noti-box">
                        <div class="noti-icon">
                            <span class="img-noti-icon"><img src="<?php echo e(asset('img/noti-icon.png')); ?>" alt="icon"></span>
                        </div>
                        <div class="noti-title">
                            <h3>Appointment Reminder</h3>
                        </div>
                        <div class="noti-time">
                            <p>2 hours ago</p>
                        </div>
                    </div>
                </section> -->
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>