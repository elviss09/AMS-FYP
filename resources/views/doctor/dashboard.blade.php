<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/patient-calendar.css') }}">
</head>
<body>
<div class="container">
    <div class="sidebar-box">
        @include('staff.sidebar')
    </div>

    <div class="content">
        <div class="page-header">
            <div class="sidebar-btn"><button class="sidebar-toggle" onclick="toggleSidebar()"><img src="{{ asset('img/hamburger.png') }}" alt="icon"></button></div>
            <header><h1>Dashboard</h1></header>
        </div>

        <div class="mid-content">
            <section class="welcome-card">
                <div class="welcome-text">
                    <h3><span class="hello-text">Hello</span> {{ $staff->full_name }},</h3>
                    <p>Have a nice day and don’t forget to take care of your health!</p>
                </div>
                <div class="welcome-img">
                    <img src="{{ asset('img/welcome-icon.svg') }}" alt="icon">
                </div>
            </section>

            <section class="stats">
                <div class="stat-box">
                    <div class="stat-title"><p>Next Appointment</p></div>
                    <div class="stat-content"><h3>{{ $nextAppointmentText }}</h3></div>
                    <div class="stat-icon-calendar">
                        <span class="stat-img-calendar"><img src="{{ asset('img/calendar-icon.png') }}" alt="icon"></span>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-title"><p>Pending Request</p></div>
                    <div class="stat-content"><h3>{{ $pendingCount }}</h3></div>
                    <div class="stat-icon-clock">
                        <span class="stat-img-clock"><img src="{{ asset('img/wall-clock.png') }}" alt="icon"></span>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-title"><p>Total Appointment</p></div>
                    <div class="stat-content"><h3>{{ $totalAppointments }}</h3></div>
                    <div class="stat-icon-check">
                        <span class="stat-img-check"><img src="{{ asset('img/check-mark.png') }}" alt="icon"></span>
                    </div>
                </div>
            </section>

            <section class="appointments">
                <h2>Upcoming Appointments</h2>
                @if ($upcomingAppointments->count())
                    @foreach ($upcomingAppointments as $row)
                        <a href="{{ route('doctor-appointment.show', ['id' => $row->appointment_id]) }}" class="appointment-link">
                            <div class="appointment-card">
                                <div class="appointment-type"><p>{{ $row->appointment_type }}</p></div>
                                <div class="appointment-place"><p>{{ $row->section_name }}</p></div>
                                <div class="date">
                                    <p>{{ \Carbon\Carbon::parse($row->appointment_date . ' ' . $row->appointment_time)->format('M j, g:i A') }}</p>
                                </div>
                                <div class="status">
                                    <span class="{{ strtolower(str_replace(' ', '-', $row->status)) }}">
                                        {{ $row->status }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <p>No upcoming appointments.</p>
                @endif
            </section>
        </div>

        <div class="right-content">
            <section class="calendar-box">
                @include('staff.staff-calendar')
            </section>

            <section class="notifications">
                <h2>Recent Notifications</h2>
                @if ($notifications->count())
                    @foreach ($notifications as $noti)
                        <a href="{{ route('staff.notification', ['id' => $noti->id]) }}" style="text-decoration:none; color:inherit;" class="noti-link">
                            <div class="noti-box">
                                <div class="noti-icon">
                                    <span class="img-noti-icon"><img src="{{ asset('img/noti-icon.png') }}" alt="icon"></span>
                                </div>
                                <div class="noti-title"><h3>{{ $noti->title }}</h3></div>
                                <div class="noti-time">
                                    <p>{{ \Carbon\Carbon::parse($noti->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <p>No notifications found.</p>
                @endif
            </section>
        </div>
    </div>
</div>
</body>
</html>
