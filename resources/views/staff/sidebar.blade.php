@php
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
@endphp


<div class="sidebar">
    <div class="profile">
        <div class="user-icon">
            <img src="{{ asset('img/profile-user.png') }}" alt="icon">
        </div>
        <div class="staff-name">
            <p class="staff-name">{{ $staff->full_name }}</p>
        </div>
        <div class="staff-role">
            <p class="staff-role">{{ $staff->role }}</p>
        </div>
        <div class="staff-specialise">
            <p class="staff-specialise">
                @if ($staff->role == 'Doctor')
                    {{ $staff->doctor->doc_specialisation ?? '' }}
                @elseif ($staff->role == 'Nurse')
                    {{ $staff->nurse->nurse_specialisation ?? '' }}
                @endif
            </p>
        </div>
        <div class="staff-id">
            <p class="staff-id">Staff ID: <strong>{{ $staff->staff_id }}</strong></p>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="navi-bar">
        @if ($staff->role == 'Doctor')
            <a href="{{ route('doctor.dashboard') }}" class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                <span class="dashboard-icon"><img src="{{ asset('img/dashboard-interface.png') }}" alt="icon"></span> Dashboard
            </a>
            <a href="{{ route('staff.profile') }}" class="{{ request()->routeIs('staff.profile') ? 'active' : '' }}">
                <span class="my-profile-icon"><img src="{{ asset('img/user.png') }}" alt="icon"></span> My Profile
            </a>
            <a href="{{ route('doctor.book-appointment.create') }}" class="{{ request()->routeIs('doctor.book-appointment.create') ? 'active' : '' }}">
                <span class="req-appointment-icon"><img src="{{ asset('img/calendar-req.png') }}" alt="icon"></span> Book Appointment
            </a>
            <a href="{{ route('staff.appointment-record') }}" class="{{ request()->routeIs('staff.appointment-record') ? 'active' : '' }}">
                <span class="record-icon"><img src="{{ asset('img/file.png') }}" alt="icon"></span> Manage Appointment
            </a>
            <a href="{{ route('staff.notification') }}" class="{{ request()->routeIs('staff.notification') ? 'active' : '' }}">
                <span class="notification-icon"><img src="{{ asset('img/notification.png') }}" alt="icon"></span> Notification
                @if ($unreadCount > 0)
                    <span class="badge">{{ $unreadCount }}</span>
                @endif
            </a>

        @elseif ($staff->role == 'Nurse')
            <a href="{{ route('nurse.dashboard') }}" class="{{ request()->routeIs('nurse.dashboard') ? 'active' : '' }}">
                <span class="dashboard-icon"><img src="{{ asset('img/dashboard-interface.png') }}" alt="icon"></span> Dashboard
            </a>
            <a href="{{ route('staff.profile') }}" class="{{ request()->routeIs('staff.profile') ? 'active' : '' }}">
                <span class="my-profile-icon"><img src="{{ asset('img/user.png') }}" alt="icon"></span> My Profile
            </a>
            <a href="{{ route('nurse.register-patient') }}" class="{{ request()->routeIs('nurse.register-patient') ? 'active' : '' }}">
                <span class="req-appointment-icon"><img src="{{ asset('img/registered.png') }}" alt="icon"></span> Register Patient
            </a>
            <a href="{{ route('staff.appointment-record') }}" class="{{ request()->routeIs('staff.appointment-record') ? 'active' : '' }}">
                <span class="record-icon"><img src="{{ asset('img/manage-appointment.png') }}" alt="icon"></span> Manage Appointment
            </a>
            <a href="{{ route('nurse.slot.manage') }}" class="{{ request()->routeIs('nurse.slot.manage') ? 'active' : '' }}">
                <span class="notification-icon"><img src="{{ asset('img/manage-slot.png') }}" alt="icon"></span> Slot Management
            </a>
            <a href="{{ route('staff.notification') }}" class="{{ request()->routeIs('staff.notification') ? 'active' : '' }}">
                <span class="notification-icon"><img src="{{ asset('img/notification.png') }}" alt="icon"></span> Notification
                @if ($unreadCount > 0)
                    <span class="badge">{{ $unreadCount }}</span>
                @endif
            </a>

        @elseif ($staff->role == 'System Admin')
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="dashboard-icon"><img src="{{ asset('img/dashboard-interface.png') }}" alt="icon"></span> Dashboard
            </a>
            <a href="{{ route('staff.profile') }}" class="{{ request()->routeIs('staff.profile') ? 'active' : '' }}">
                <span class="my-profile-icon"><img src="{{ asset('img/user.png') }}" alt="icon"></span> My Profile
            </a>
            <a href="{{ route('admin.manage-staff') }}" class="{{ request()->routeIs('admin.manage-staff') ? 'active' : '' }}">
                <span class="req-appointment-icon"><img src="{{ asset('img/calendar-req.png') }}" alt="icon"></span> Staff Management
            </a>
        @endif
    </div>
</div>

