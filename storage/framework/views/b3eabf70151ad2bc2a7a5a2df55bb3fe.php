<?php
    use Illuminate\Support\Facades\DB;

    $staff = DB::table('staff')
            ->leftJoin('hospital_section', 'staff.working_section', '=', 'hospital_section.section_id')
            ->where('staff.staff_id', session('staff_id'))
            ->select('staff.*', 'hospital_section.section_name')
            ->first();

    if ($staff->role == 'Doctor') {
        $specialisation = DB::table('doctor')->where('staff_id', $staff->staff_id)->value('doc_specialisation');
    } elseif ($staff->role == 'Nurse') {
        $specialisation = DB::table('nurse')->where('staff_id', $staff->staff_id)->value('nurse_specialisation');
    } else {
        $specialisation = '';
    }

    $unreadCount = DB::table('notifications')
            ->where(function($query) use ($staff) {
                $query->where('staff_id', $staff->staff_id);
                if ($staff->role == 'Nurse' && $staff->working_section) {
                    $query->orWhere('section_id', $staff->working_section);
                }
            })
            ->where('staff_read', 0)
            ->count();
?>


<div class="sidebar">
    <div class="profile">
        <div class="user-icon">
            <img src="<?php echo e(asset('img/profile-user.png')); ?>" alt="icon">
        </div>
        <div class="staff-name">
            <p class="staff-name"><?php echo e($staff->full_name); ?></p>
        </div>
        <div class="staff-role">
            <p class="staff-role"><?php echo e($staff->role); ?></p>
        </div>
        <div class="staff-specialise">
            <p class="staff-specialise">
                <?php if($staff->role == 'Doctor'): ?>
                    <?php echo e($staff->doctor->doc_specialisation ?? ''); ?>

                <?php elseif($staff->role == 'Nurse'): ?>
                    <?php echo e($staff->nurse->nurse_specialisation ?? ''); ?>

                <?php endif; ?>
            </p>
        </div>
        <div class="staff-id">
            <p class="staff-id">Staff ID: <strong><?php echo e($staff->staff_id); ?></strong></p>
        </div>
    </div>

    
    <div class="navi-bar">
        <?php if($staff->role == 'Doctor'): ?>
            <a href="<?php echo e(route('doctor.dashboard')); ?>" class="<?php echo e(request()->routeIs('doctor.dashboard') ? 'active' : ''); ?>">
                <span class="dashboard-icon"><img src="<?php echo e(asset('img/dashboard-interface.png')); ?>" alt="icon"></span> Dashboard
            </a>
            <a href="<?php echo e(route('staff.profile')); ?>" class="<?php echo e(request()->routeIs('staff.profile') ? 'active' : ''); ?>">
                <span class="my-profile-icon"><img src="<?php echo e(asset('img/user.png')); ?>" alt="icon"></span> My Profile
            </a>
            <a href="<?php echo e(route('doctor.book-appointment.create')); ?>" class="<?php echo e(request()->routeIs('doctor.book-appointment.create') ? 'active' : ''); ?>">
                <span class="req-appointment-icon"><img src="<?php echo e(asset('img/calendar-req.png')); ?>" alt="icon"></span> Book Appointment
            </a>
            <a href="<?php echo e(route('staff.appointment-record')); ?>" class="<?php echo e(request()->routeIs('staff.appointment-record') ? 'active' : ''); ?>">
                <span class="record-icon"><img src="<?php echo e(asset('img/file.png')); ?>" alt="icon"></span> Manage Appointment
            </a>
            <a href="<?php echo e(route('staff.notification')); ?>" class="<?php echo e(request()->routeIs('staff.notification') ? 'active' : ''); ?>">
                <span class="notification-icon"><img src="<?php echo e(asset('img/notification.png')); ?>" alt="icon"></span> Notification
                <?php if($unreadCount > 0): ?>
                    <span class="badge"><?php echo e($unreadCount); ?></span>
                <?php endif; ?>
            </a>

        <?php elseif($staff->role == 'Nurse'): ?>
            <a href="<?php echo e(route('nurse.dashboard')); ?>" class="<?php echo e(request()->routeIs('nurse.dashboard') ? 'active' : ''); ?>">
                <span class="dashboard-icon"><img src="<?php echo e(asset('img/dashboard-interface.png')); ?>" alt="icon"></span> Dashboard
            </a>
            <a href="<?php echo e(route('staff.profile')); ?>" class="<?php echo e(request()->routeIs('staff.profile') ? 'active' : ''); ?>">
                <span class="my-profile-icon"><img src="<?php echo e(asset('img/user.png')); ?>" alt="icon"></span> My Profile
            </a>
            <a href="<?php echo e(route('nurse.register-patient')); ?>" class="<?php echo e(request()->routeIs('nurse.register-patient') ? 'active' : ''); ?>">
                <span class="req-appointment-icon"><img src="<?php echo e(asset('img/registered.png')); ?>" alt="icon"></span> Register Patient
            </a>
            <a href="<?php echo e(route('staff.appointment-record')); ?>" class="<?php echo e(request()->routeIs('staff.appointment-record') ? 'active' : ''); ?>">
                <span class="record-icon"><img src="<?php echo e(asset('img/manage-appointment.png')); ?>" alt="icon"></span> Manage Appointment
            </a>
            <a href="<?php echo e(route('nurse.slot.manage')); ?>" class="<?php echo e(request()->routeIs('nurse.slot.manage') ? 'active' : ''); ?>">
                <span class="notification-icon"><img src="<?php echo e(asset('img/manage-slot.png')); ?>" alt="icon"></span> Slot Management
            </a>
            <a href="<?php echo e(route('staff.notification')); ?>" class="<?php echo e(request()->routeIs('staff.notification') ? 'active' : ''); ?>">
                <span class="notification-icon"><img src="<?php echo e(asset('img/notification.png')); ?>" alt="icon"></span> Notification
                <?php if($unreadCount > 0): ?>
                    <span class="badge"><?php echo e($unreadCount); ?></span>
                <?php endif; ?>
            </a>

        <?php elseif($staff->role == 'System Admin'): ?>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                <span class="dashboard-icon"><img src="<?php echo e(asset('img/dashboard-interface.png')); ?>" alt="icon"></span> Dashboard
            </a>
            <a href="<?php echo e(route('staff.profile')); ?>" class="<?php echo e(request()->routeIs('staff.profile') ? 'active' : ''); ?>">
                <span class="my-profile-icon"><img src="<?php echo e(asset('img/user.png')); ?>" alt="icon"></span> My Profile
            </a>
            <a href="<?php echo e(route('admin.manage-staff')); ?>" class="<?php echo e(request()->routeIs('admin.manage-staff') ? 'active' : ''); ?>">
                <span class="req-appointment-icon"><img src="<?php echo e(asset('img/calendar-req.png')); ?>" alt="icon"></span> Staff Management
            </a>
        <?php endif; ?>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/staff/sidebar.blade.php ENDPATH**/ ?>