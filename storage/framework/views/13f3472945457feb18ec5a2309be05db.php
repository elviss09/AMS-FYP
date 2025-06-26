<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>

    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/patient-sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/notification.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/toast.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="notifications"></div>

<div class="container">
    <div class="sidebar-box">
        <?php echo $__env->make('patient.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="content">
        <div class="page-header">
            <header>
                <h1>Notification</h1>
            </header>
        </div>

        <div class="noti-container">
            <div class="recent-noti">
                <div class="header-box">Recent Notifications</div>
                <div class="noti-list">
                    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $noti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="noti-box <?php echo e($noti->patient_read ? 'read' : 'unread'); ?> <?php echo e($firstNoti && $noti->id == $firstNoti->id ? 'selected' : ''); ?>"
                            data-id="<?php echo e($noti->id); ?>"
                            data-title="<?php echo e($noti->title); ?>"
                            data-message="<?php echo e($noti->patient_message); ?>">
                            <div class="noti-icon">
                                <span><img src="<?php echo e(asset('img/noti-icon.png')); ?>" alt="icon"></span>
                            </div>
                            <div class="noti-title"><?php echo e($noti->title); ?></div>
                            <div class="noti-time"><?php echo e(\Carbon\Carbon::parse($noti->created_at)->format('d M Y, H:i')); ?></div>
                            <?php if(!$noti->patient_read): ?>
                                <!-- <div class="mark-as-read-icon">
                                    <span><img src="<?php echo e(asset('img/mark.png')); ?>" alt="Mark" title="Mark as read"></span>
                                </div> -->
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div>No notifications found.</div>
                    <?php endif; ?>
                </div>

                <div class="noti-details">
                    <div class="title-read-icon">
                        <div class="noti-title" id="noti-detail-title"><?php echo e($firstNoti->title ?? ''); ?></div>
                        <div class="action-icon">
                            <div class="read-icon"><span><img src="<?php echo e(asset('img/mark.png')); ?>" alt="icon" title="Mark as read"></span></div>
                            <div class="delete-icon"><span><img src="<?php echo e(asset('img/trash.png')); ?>" alt="icon" title="Delete"></span></div>
                        </div>
                    </div>
                    <div class="noti-message" id="noti-detail-message"><?php echo e($firstNoti->patient_message ?? ''); ?></div>
                </div>
            </div>

            <div class="noti-setting">
                <div class="header-box">Notification Preferences</div>
                <div class="section-noti-setting">Appointment Reminder</div>
                <div class="noti-setting-details">Choose when to receive reminders before your appointment.</div>
                <form method="POST" action="<?php echo e(route('patient.notification.preferences')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="reminder-timing-option">
                        <div>1 day before</div>
                        <div>
                            <label class="switch">
                                <input type="checkbox" name="notify_1day" <?php echo e($prefs->notify_1day ? 'checked' : ''); ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="reminder-timing-option">
                        <div>3 days before</div>
                        <div>
                            <label class="switch">
                                <input type="checkbox" name="notify_3days" <?php echo e($prefs->notify_3days ? 'checked' : ''); ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="reminder-timing-option">
                        <div>1 week before</div>
                        <div>
                            <label class="switch">
                                <input type="checkbox" name="notify_1week" <?php echo e($prefs->notify_1week ? 'checked' : ''); ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="button">
                        <button type="submit" class="save-button">Save Preferences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.noti-box').forEach(box => {
        box.addEventListener('click', function () {
            document.querySelectorAll('.noti-box').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');

            document.getElementById('noti-detail-title').textContent = this.dataset.title;
            document.getElementById('noti-detail-message').textContent = this.dataset.message;
        });
    });

    document.querySelectorAll('.mark-as-read-icon, .read-icon span img').forEach(el => {
        el.addEventListener('click', function (e) {
            e.stopPropagation();
            const selected = document.querySelector('.noti-box.selected') || this.closest('.noti-box');
            if (!selected) return;

            fetch("<?php echo e(route('patient.notification.mark')); ?>", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                body: JSON.stringify({ id: selected.dataset.id })
            }).then(res => res.json()).then(data => {
                if (data.status === 'success') {
                    selected.classList.remove('unread');
                    selected.classList.add('read');
                    selected.querySelector('.mark-as-read-icon')?.remove();
                }
            });
        });
    });

    document.querySelector('.delete-icon span img').addEventListener('click', function () {
        const selected = document.querySelector('.noti-box.selected');
        if (!selected) return;

        fetch("<?php echo e(route('patient.notification.delete')); ?>", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
            body: JSON.stringify({ id: selected.dataset.id })
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                location.reload();
            }
        });
    });

    <?php if(session('success')): ?>
        createToast('success', 'fa-solid fa-circle-check', 'Success', '<?php echo e(session('success')); ?>');
    <?php endif; ?>
    <?php if(session('error')): ?>
        createToast('error', 'fa-solid fa-circle-exclamation', 'Error', '<?php echo e(session('error')); ?>');
    <?php endif; ?>

    function createToast(type, icon, title, text) {
        const notifications = document.querySelector('.notifications');
        const toast = document.createElement('div');
        toast.innerHTML = `
            <div class="toast ${type}">
                <i class="${icon}"></i>
                <div class="content">
                    <div class="title">${title}</div>
                    <span>${text}</span>
                </div>
                <i class="fa-solid fa-xmark" onclick="this.parentElement.remove()"></i>
            </div>`;
        notifications.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }
</script>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/patient/notification.blade.php ENDPATH**/ ?>