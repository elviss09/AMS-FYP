<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notification</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="container">
    <div class="sidebar-box">
        @include('staff.sidebar')
    </div>

    <div class="content">
        <div class="page-header">
            <div class="sidebar-btn"><button class="sidebar-toggle" onclick="toggleSidebar()"><img src="{{ asset('img/hamburger.png') }}" alt="icon"></button></div>
            <header>
                <h1>Notification</h1>
            </header>
        </div>

        <div class="noti-container">
            <div class="recent-noti">
                <div class="header-box">Recent Notifications</div>

                <div class="noti-list">
                    @forelse($notifications as $noti)
                        <div class="noti-box {{ $noti->staff_read ? 'read' : 'unread' }} {{ $firstNotification && $noti->id == $firstNotification->id ? 'selected' : '' }}" 
                             data-id="{{ $noti->id }}" 
                             data-title="{{ $noti->title }}" 
                             data-message="{{ $noti->staff_message }}">

                            <div class="noti-icon"><span><img src="{{ asset('img/noti-icon.png') }}" alt="icon"></span></div>
                            <div class="noti-title">{{ $noti->title }}</div>
                            <div class="noti-time">{{ \Carbon\Carbon::parse($noti->created_at)->format('d M Y, H:i') }}</div>

                            <!-- @if (!$noti->staff_read)
                                <div class="mark-as-read-icon">
                                    <span><img src="{{ asset('img/mark.png') }}" title="Mark as read"></span>
                                </div>
                            @endif -->
                        </div>
                    @empty
                        <div>No notifications found.</div>
                    @endforelse
                </div>

                <div class="noti-details">
                    @if ($firstNotification)
                        <div class="title-read-icon">
                            <div class="noti-title">{{ $firstNotification->title }}</div>
                            <div class="action-icon">
                                <div class="read-icon"><span><img src="{{ asset('img/mark.png') }}" title="Mark as read"></span></div>
                                <div class="delete-icon"><span><img src="{{ asset('img/trash.png') }}" title="Delete"></span></div>
                            </div>
                        </div>
                        <div class="noti-message">{{ $firstNotification->staff_message }}</div>
                    @else
                        <p>No notification selected.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
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
            fetch('{{ route("staff.notification.markAsRead") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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
        fetch('{{ route("staff.notification.markAsRead") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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

        fetch('{{ route("staff.notification.delete") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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
