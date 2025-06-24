<?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="staff-card">
        <div class="staff-info">
            <div class="user-icon"><img src="<?php echo e(asset('img/human.png')); ?>" alt="Profile Picture"></div>
            <div class="name-position-threedot">
                <div class="name-position">
                    <div class="name"><?php echo e($member->full_name); ?></div>
                    <div class="position"><?php echo e($member->position); ?></div>
                </div>
                <div class="three-dot-button" data-id="<?php echo e($member->staff_id); ?>" onclick="toggleMenu(event, <?php echo e($member->staff_id); ?>)">
                    <img src="<?php echo e(asset('img/dots.png')); ?>" alt="">
                </div>
            </div>
        </div>

        <div class="staff-contact-info">
            <div class="email-info">
                <span class="icon"><img src="<?php echo e(asset('img/email.png')); ?>" alt="icon"></span>
                <?php echo e($member->email); ?>

            </div>
            <div class="phone-info">
                <span class="icon"><img src="<?php echo e(asset('img/phone-call.png')); ?>" alt="icon"></span>
                <?php echo e($member->phone_no); ?>

            </div>
        </div>

        <div class="pop-out-menu" id="menu-<?php echo e($member->staff_id); ?>" style="display: none;">
            <ul>
                <li><a href="<?php echo e(route('admin.manage-staff.edit', $member->staff_id)); ?>">Edit Staff</a></li>
                <li><a href="<?php echo e(route('admin.manage-staff.remove', $member->staff_id)); ?>" onclick="return confirm('Are you sure you want to remove this staff?')">Remove Staff</a></li>
            </ul>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/admin/staff-cards.blade.php ENDPATH**/ ?>