<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notification</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/staff-sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/notification.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/toast.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="container">
    <div class="sidebar-box">
        <?php echo $__env->make('staff.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="content">
        <div class="page-header">
            <div class="sidebar-btn"><button class="sidebar-toggle" onclick="toggleSidebar()"><img src="<?php echo e(asset('img/hamburger.png')); ?>" alt="icon"></button></div>
            <header>
                <h1>Notification</h1>
            </header>
        </div>

        <div class="noti-container">
            <div class="recent-noti">
                <div class="header-box">Recent Notifications</div>

                <div class="noti-list">
                    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $noti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="noti-box <?php echo e($noti->staff_read ? 'read' : 'unread'); ?> <?php echo e($firstNotification && $noti->id == $firstNotification->id ? 'selected' : ''); ?>" 
                             data-id="<?php echo e($noti->id); ?>" 
                             data-title="<?php echo e($noti->title); ?>" 
                             data-message="<?php echo e($noti->staff_message); ?>">

                            <div class="noti-icon"><span><img src="<?php echo e(asset('img/noti-icon.png')); ?>" alt="icon"></span></div>
                            <div class="noti-title"><?php echo e($noti->title); ?></div>
                            <div class="noti-time"><?php echo e(\Carbon\Carbon::parse($noti->created_at)->format('d M Y, H:i')); ?></div>

                            <!-- <?php if(!$noti->staff_read): ?>
                                <div class="mark-as-read-icon">
                                    <span><img src="<?php echo e(asset('img/mark.png')); ?>" title="Mark as read"></span>
                                </div>
                            <?php endif; ?> -->
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div>No notifications found.</div>
                    <?php endif; ?>
                </div>

                <div class="noti-details">
                    <?php if($firstNotification): ?>
                        <div class="title-read-icon">
                            <div class="noti-title"><?php echo e($firstNotification->title); ?></div>
                            <div class="action-icon">
                                <div class="read-icon"><span><img src="<?php echo e(asset('img/mark.png')); ?>" title="Mark as read"></span></div>
                                <div class="delete-icon"><span><img src="<?php echo e(asset('img/trash.png')); ?>" title="Delete"></span></div>
                            </div>
                        </div>
                        <div class="noti-message"><?php echo e($firstNotification->staff_message); ?></div>
                    <?php else: ?>
                        <p>No notification selected.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="notifications"></div>

<script>
    document.querySelectorAll('.noti-box').forEach(box => {
        box.addEventListener('click', function () {
            document.querySelectorAll('.noti-box').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
            document.querySelector('.noti-details .noti-title').innerText = this.dataset.title;
            document.querySelector('.noti-details .noti-message').innerText = this.dataset.message;
        });
    });

    // Mark as read (inside box)
    document.querySelectorAll('.mark-as-read-icon').forEach(icon => {
        icon.addEventListener('click', function (e) {
            e.stopPropagation();
            const box = this.closest('.noti-box');
            const id = box.dataset.id;
            fetch('<?php echo e(route("staff.notification.markAsRead")); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                body: JSON.stringify({ id: id })
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    box.classList.remove('unread');
                    box.classList.add('read');
                    this.remove();
                    createToast('success', 'Marked as read');
                }
            });
        });
    });

    // Mark selected as read (right panel)
    document.querySelector('.read-icon span img').addEventListener('click', function () {
        const selectedBox = document.querySelector('.noti-box.selected');
        if (!selectedBox) return;
        const id = selectedBox.dataset.id;
        fetch('<?php echo e(route("staff.notification.markAsRead")); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
            body: JSON.stringify({ id: id })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                selectedBox.classList.remove('unread');
                selectedBox.classList.add('read');
                selectedBox.querySelector('.mark-as-read-icon')?.remove();
                createToast('success', 'Marked as read');
            }
        });
    });

    // Delete selected
    document.querySelector('.delete-icon span img').addEventListener('click', function () {
        const selectedBox = document.querySelector('.noti-box.selected');
        if (!selectedBox) return;
        const id = selectedBox.dataset.id;

        fetch('<?php echo e(route("staff.notification.delete")); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
            body: JSON.stringify({ id: id })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                createToast('success', 'Notification deleted');
                location.reload();
            }
        });
    });

    function createToast(type, icon, title, text) {
        const notifications = document.querySelector('.notifications');
        let newToast = document.createElement('div');
        newToast.innerHTML = `
            <div class="toast ${type}">
                <i class="${icon}"></i>
                <div class="content">
                    <div class="title">${title}</div>
                    <span>${text}</span>
                </div>
                <i class="fa-solid fa-xmark" onclick="(this.parentElement).remove()"></i>
            </div>`;
        notifications.appendChild(newToast);
        newToast.timeOut = setTimeout(() => newToast.remove(), 5000);
    }

    // PHP to JavaScript message pass
    const successMessage = <?php echo json_encode(session('success')); ?>;
    const errorMessage =<?php echo json_encode(session('error')); ?>;


    if (successMessage) {
        createToast('success', 'fa-solid fa-circle-check', 'Success', successMessage);
    }
    if (errorMessage) {
        createToast('error', 'fa-solid fa-circle-exclamation', 'Error', errorMessage);
    }
</script>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/staff/notification.blade.php ENDPATH**/ ?>