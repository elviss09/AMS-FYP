<div class="sidebar">
    <div class="profile">
        <div class="user-icon">
            <img src="{{ asset('img/profile-user.png') }}" alt="icon">
        </div>
        <div class="staff-name">{{ $staff->full_name }}</div>
        <div class="staff-role">{{ $staff->role }}</div>
        <div class="staff-specialise">{{ $nurseTable->nurse_specialisation ?? '-' }}</div>
        <div class="staff-id">Staff ID : <strong>{{ $staff->staff_id }}</strong></div>
    </div>

    <div class="navi-bar">
        <a href="{{ route('nurse.dashboard') }}" class="{{ request()->routeIs('nurse.dashboard') ? 'active' : '' }}">
            <span><img src="{{ asset('img/dashboard-interface.png') }}" alt="icon"></span> Dashboard
        </a>
        <a href="#">My Profile</a>
        <a href="#">Register Patient</a>
        <a href="#">Manage Appointment</a>
        <a href="#">Slot Management</a>
        <a href="#">
            Notification
            @if($unreadCount > 0)
                <span class="badge">{{ $unreadCount }}</span>
            @endif
        </a>
    </div>
</div>
