<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification</title>

    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/patient-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notification.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="notifications"></div>

<div class="container">
    <div class="sidebar-box">
        @include('patient.sidebar')
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
                    @forelse ($notifications as $noti)
                        <div class="noti-box {{ $noti->patient_read ? 'read' : 'unread' }} {{ $firstNoti && $noti->id == $firstNoti->id ? 'selected' : '' }}"
                            data-id="{{ $noti->id }}"
                            data-title="{{ $noti->title }}"
                            data-message="{{ $noti->patient_message }}">
                            <div class="noti-icon">
                                <span><img src="{{ asset('img/noti-icon.png') }}" alt="icon"></span>
                            </div>
                            <div class="noti-title">{{ $noti->title }}</div>
                            <div class="noti-time">{{ \Carbon\Carbon::parse($noti->created_at)->format('d M Y, H:i') }}</div>
                            @if (!$noti->patient_read)
                                <!-- <div class="mark-as-read-icon">
                                    <span><img src="{{ asset('img/mark.png') }}" alt="Mark" title="Mark as read"></span>
                                </div> -->
                            @endif
                        </div>
                    @empty
                        <div>No notifications found.</div>
                    @endforelse
                </div>

                <div class="noti-details">
                    <div class="title-read-icon">
                        <div class="noti-title" id="noti-detail-title">{{ $firstNoti->title ?? '' }}</div>
                        <div class="action-icon">
                            <div class="read-icon"><span><img src="{{ asset('img/mark.png') }}" alt="icon" title="Mark as read"></span></div>
                            <div class="delete-icon"><span><img src="{{ asset('img/trash.png') }}" alt="icon" title="Delete"></span></div>
                        </div>
                    </div>
                    <div class="noti-message" id="noti-detail-message">{{ $firstNoti->patient_message ?? '' }}</div>
                </div>
            </div>

            <div class="noti-setting">
                <div class="header-box">Notification Preferences</div>
                <div class="section-noti-setting">Appointment Reminder</div>
                <div class="noti-setting-details">Choose when to receive reminders before your appointment.</div>
                <form method="POST" action="{{ route('patient.notification.preferences') }}">
                    @csrf
                    <div class="reminder-timing-option">
                        <div>1 day before</div>
                        <div>
                            <label class="switch">
                                <input type="checkbox" name="notify_1day" {{ $prefs->notify_1day ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="reminder-timing-option">
                        <div>3 days before</div>
                        <div>
                            <label class="switch">
                                <input type="checkbox" name="notify_3days" {{ $prefs->notify_3days ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="reminder-timing-option">
                        <div>1 week before</div>
                        <div>
                            <label class="switch">
                                <input type="checkbox" name="notify_1week" {{ $prefs->notify_1week ? 'checked' : '' }}>
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

            fetch("{{ route('patient.notification.mark') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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

        fetch("{{ route('patient.notification.delete') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ id: selected.dataset.id })
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                location.reload();
            }
        });
    });

    @if(session('success'))
        createToast('success', 'fa-solid fa-circle-check', 'Success', '{{ session('success') }}');
    @endif
    @if(session('error'))
        createToast('error', 'fa-solid fa-circle-exclamation', 'Error', '{{ session('error') }}');
    @endif

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
