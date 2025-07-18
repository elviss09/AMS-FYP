<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

$patientId = session('patient_id');

$patient = DB::table('patients')->where('patient_id', $patientId)->first();

$dob = Carbon::parse($patient->date_of_birth);
$age = $dob->age;

$unreadCount = DB::table('notifications')
    ->where('patient_id', $patientId)
    ->where('patient_read', 0)
    ->count();

$currentPage = request()->path();
?>


<!-- Sidebar -->
<div class="sidebar">
    <div class="profile">
        <div class="user-icon">
            <img src="<?php echo e(asset('img/profile-user.png')); ?>" alt="icon">
        </div>
        <div class="user-name">
            <p class="user-name"><?php echo e($patient->full_name); ?></p>
        </div>
        <div class="user-details-1">
            <div class="user-type"><p>Patient</p></div>
            <div class="age"><p><?php echo e($age); ?> years old</p></div>
        </div>
        <div class="user-details-2">
            <div class="gender">
                <p class="details-heading">Gender</p>
                <p><?php echo e($patient->gender); ?></p>
            </div>
            <div class="height">
                <p class="details-heading">Height</p>
                <p><?php echo e($patient->height); ?> m</p>
            </div>
            <div class="weight">
                <p class="details-heading">Weight</p>
                <p><?php echo e($patient->weight); ?> kg</p>
            </div>
        </div>
    </div>

    <div class="navi-bar">
        <a href="<?php echo e(url('patient-dashboard')); ?>" class="<?php echo e(request()->is('patient-dashboard') ? 'active' : ''); ?>">
            <span class="dashboard-icon"><img src="<?php echo e(asset('img/dashboard-interface.png')); ?>" alt="icon"></span>Dashboard
        </a>
        <a href="<?php echo e(url('patient-profile')); ?>" class="<?php echo e(request()->is('patient-profile') ? 'active' : ''); ?>">
            <span class="my-profile-icon"><img src="<?php echo e(asset('img/user.png')); ?>" alt="icon"></span> My Profile
        </a>
        <a href="<?php echo e(url('request-appointment')); ?>" class="<?php echo e(request()->is('request-appointment') ? 'active' : ''); ?>">
            <span class="req-appointment-icon"><img src="<?php echo e(asset('img/calendar-req.png')); ?>" alt="icon"></span> Request Appointment
        </a>
        <a href="<?php echo e(url('all-appointment-record')); ?>" class="<?php echo e(request()->is('all-appointment-record') ? 'active' : ''); ?>">
            <span class="record-icon"><img src="<?php echo e(asset('img/file.png')); ?>" alt="icon"></span> Appointment Record
        </a>
        <a href="<?php echo e(url('notification')); ?>" class="<?php echo e(request()->is('notification') ? 'active' : ''); ?>">
            <span class="notification-icon"><img src="<?php echo e(asset('img/notification.png')); ?>" alt="icon"></span> Notification
            <?php if($unreadCount > 0): ?>
                <span class="badge"><?php echo e($unreadCount); ?></span>
            <?php endif; ?>
        </a>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="<?php echo e(request()->is('logout') ? 'active' : ''); ?>" >
                <span class="record-icon">
                    <img src="<?php echo e(asset('img/exit.png')); ?>" alt="Logout Icon">
                </span>
                <span class="logout-icon">Logout</span>
            </button>
        </form>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('active');

        if (sidebar.classList.contains('active')) {
            document.addEventListener('click', outsideClickListener);
        } else {
            document.removeEventListener('click', outsideClickListener);
        }
    }

    function outsideClickListener(event) {
        const sidebar = document.querySelector('.sidebar');
        const toggleButton = document.querySelector('.sidebar-toggle');

        if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
            sidebar.classList.remove('active');
            document.removeEventListener('click', outsideClickListener);
        }
    }
</script>

<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/patient/sidebar.blade.php ENDPATH**/ ?>