<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/staff-sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/patient-calendar.css')); ?>">
</head>
<body>
<div class="container">
    <div class="sidebar-box">
        <?php echo $__env->make('staff.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="content">
        <div class="page-header">
            <div class="sidebar-btn"><button class="sidebar-toggle" onclick="toggleSidebar()"><img src="<?php echo e(asset('img/hamburger.png')); ?>" alt="icon"></button></div>
            <header><h1>Dashboard</h1></header>
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
                    <div class="stat-title"><p>Next Appointment</p></div>
                    <div class="stat-content"><h3><?php echo e($nextAppointmentText); ?></h3></div>
                    <div class="stat-icon-calendar">
                        <span class="stat-img-calendar"><img src="<?php echo e(asset('img/calendar-icon.png')); ?>" alt="icon"></span>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-title"><p>Pending Request</p></div>
                    <div class="stat-content"><h3><?php echo e($pendingCount); ?></h3></div>
                    <div class="stat-icon-clock">
                        <span class="stat-img-clock"><img src="<?php echo e(asset('img/wall-clock.png')); ?>" alt="icon"></span>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-title"><p>Total Appointment</p></div>
                    <div class="stat-content"><h3><?php echo e($totalAppointments); ?></h3></div>
                    <div class="stat-icon-check">
                        <span class="stat-img-check"><img src="<?php echo e(asset('img/check-mark.png')); ?>" alt="icon"></span>
                    </div>
                </div>
            </section>

            <section class="appointments">
                <h2>Upcoming Appointments</h2>
                <?php if($upcomingAppointments->count()): ?>
                    <?php $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('doctor-appointment.show', ['id' => $row->appointment_id])); ?>" class="appointment-link">
                            <div class="appointment-card">
                                <div class="appointment-type"><p><?php echo e($row->appointment_type); ?></p></div>
                                <div class="appointment-place"><p><?php echo e($row->section_name); ?></p></div>
                                <div class="date">
                                    <p><?php echo e(\Carbon\Carbon::parse($row->appointment_date . ' ' . $row->appointment_time)->format('M j, g:i A')); ?></p>
                                </div>
                                <div class="status">
                                    <span class="<?php echo e(strtolower(str_replace(' ', '-', $row->status))); ?>">
                                        <?php echo e($row->status); ?>

                                    </span>
                                </div>
                            </div>
                        </a>
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

            <section class="notifications">
                <h2>Recent Notifications</h2>
                <?php if($notifications->count()): ?>
                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $noti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('staff.notification', ['id' => $noti->id])); ?>" style="text-decoration:none; color:inherit;" class="noti-link">
                            <div class="noti-box">
                                <div class="noti-icon">
                                    <span class="img-noti-icon"><img src="<?php echo e(asset('img/noti-icon.png')); ?>" alt="icon"></span>
                                </div>
                                <div class="noti-title"><h3><?php echo e($noti->title); ?></h3></div>
                                <div class="noti-time">
                                    <p><?php echo e(\Carbon\Carbon::parse($noti->created_at)->diffForHumans()); ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p>No notifications found.</p>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/doctor/dashboard.blade.php ENDPATH**/ ?>