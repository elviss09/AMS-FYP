<<<<<<< HEAD
@php
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
@endphp

<!-- Sidebar Toggle Button for Mobile -->
<button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

<!-- Sidebar -->
<div class="sidebar">
    <div class="profile">
        <div class="user-icon">
            <img src="{{ asset('img/profile-user.png') }}" alt="icon">
        </div>
        <div class="user-name">
            <p class="user-name">{{ $patient->full_name }}</p>
        </div>
        <div class="user-details-1">
            <div class="user-type"><p>Patient</p></div>
            <div class="age"><p>{{ $age }} years old</p></div>
        </div>
        <div class="user-details-2">
            <div class="gender">
                <p class="details-heading">Gender</p>
                <p>{{ $patient->gender }}</p>
            </div>
            <div class="height">
                <p class="details-heading">Height</p>
                <p>{{ $patient->height }} m</p>
            </div>
            <div class="weight">
                <p class="details-heading">Weight</p>
                <p>{{ $patient->weight }} kg</p>
            </div>
        </div>
    </div>

    <div class="navi-bar">
        <a href="{{ url('patient-dashboard') }}" class="{{ request()->is('patient-dashboard') ? 'active' : '' }}">
            <span class="dashboard-icon"><img src="{{ asset('img/dashboard-interface.png') }}" alt="icon"></span>Dashboard
        </a>
        <a href="{{ url('patient-profile') }}" class="{{ request()->is('patient-profile') ? 'active' : '' }}">
            <span class="my-profile-icon"><img src="{{ asset('img/user.png') }}" alt="icon"></span> My Profile
        </a>
        <a href="{{ url('request-appointment') }}" class="{{ request()->is('request-appointment') ? 'active' : '' }}">
            <span class="req-appointment-icon"><img src="{{ asset('img/calendar-req.png') }}" alt="icon"></span> Request Appointment
        </a>
        <a href="{{ url('all-appointment-record') }}" class="{{ request()->is('all-appointment-record') ? 'active' : '' }}">
            <span class="record-icon"><img src="{{ asset('img/file.png') }}" alt="icon"></span> Appointment Record
        </a>
        <a href="{{ url('notification') }}" class="{{ request()->is('notification') ? 'active' : '' }}">
            <span class="notification-icon"><img src="{{ asset('img/notification.png') }}" alt="icon"></span> Notification
            @if ($unreadCount > 0)
                <span class="badge">{{ $unreadCount }}</span>
            @endif
        </a>
    </div>
</div>

<!-- Sidebar Toggle Script -->
<script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('active');
    }
</script>
=======
@php
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
@endphp

<!-- Sidebar Toggle Button for Mobile -->
<button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

<!-- Sidebar -->
<div class="sidebar">
    <div class="profile">
        <div class="user-icon">
            <img src="{{ asset('img/profile-user.png') }}" alt="icon">
        </div>
        <div class="user-name">
            <p class="user-name">{{ $patient->full_name }}</p>
        </div>
        <div class="user-details-1">
            <div class="user-type"><p>Patient</p></div>
            <div class="age"><p>{{ $age }} years old</p></div>
        </div>
        <div class="user-details-2">
            <div class="gender">
                <p class="details-heading">Gender</p>
                <p>{{ $patient->gender }}</p>
            </div>
            <div class="height">
                <p class="details-heading">Height</p>
                <p>{{ $patient->height }} m</p>
            </div>
            <div class="weight">
                <p class="details-heading">Weight</p>
                <p>{{ $patient->weight }} kg</p>
            </div>
        </div>
    </div>

    <div class="navi-bar">
        <a href="{{ url('patient-dashboard') }}" class="{{ request()->is('patient-dashboard') ? 'active' : '' }}">
            <span class="dashboard-icon"><img src="{{ asset('img/dashboard-interface.png') }}" alt="icon"></span>Dashboard
        </a>
        <a href="{{ url('patient-profile') }}" class="{{ request()->is('patient-profile') ? 'active' : '' }}">
            <span class="my-profile-icon"><img src="{{ asset('img/user.png') }}" alt="icon"></span> My Profile
        </a>
        <a href="{{ url('request-appointment') }}" class="{{ request()->is('request-appointment') ? 'active' : '' }}">
            <span class="req-appointment-icon"><img src="{{ asset('img/calendar-req.png') }}" alt="icon"></span> Request Appointment
        </a>
        <a href="{{ url('all-appointment-record') }}" class="{{ request()->is('all-appointment-record') ? 'active' : '' }}">
            <span class="record-icon"><img src="{{ asset('img/file.png') }}" alt="icon"></span> Appointment Record
        </a>
        <a href="{{ url('notification') }}" class="{{ request()->is('notification') ? 'active' : '' }}">
            <span class="notification-icon"><img src="{{ asset('img/notification.png') }}" alt="icon"></span> Notification
            @if ($unreadCount > 0)
                <span class="badge">{{ $unreadCount }}</span>
            @endif
        </a>
    </div>
</div>

<!-- Sidebar Toggle Script -->
<script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('active');
    }
</script>
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
