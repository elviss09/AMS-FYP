<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/patient-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appointment-details.css') }}">
</head>
<body>
    <div class="container">
        <div class="sidebar-box">
            @include('patient.sidebar') 
        </div>

        <div class="content">
             <div class="page-header">
                <div class="sidebar-btn"><button class="sidebar-toggle" onclick="toggleSidebar()"><img src="{{ asset('img/hamburger.png') }}" alt="icon"></button></div>
            </div>

            <div class="details-container">
                <div class="content-header">Appointment Details</div>
                <div class="details-section">
                    <div class="details-content">
                        <div class="appointment-id">
                            <div class="details-title">Appointment ID</div>
                            <div class="details-info">{{ $appointment->appointment_id }}</div>
                        </div>
                        <div class="created-at">
                            <div class="details-title">Created At</div>
                            <div class="details-info">{{ \Carbon\Carbon::parse($appointment->created_at)->format('d F Y, g:i A') }}</div>
                        </div>
                        <div class="appointment-type">
                            <div class="details-title">Appointment Type</div>
                            <div class="details-info">{{ $appointment->appointment_type }}</div>
                        </div>
                        <div class="assigned-doctor">
                            <div class="details-title">Assigned Doctor</div>
                            <div class="details-info">{{ $doctorName }}</div>
                        </div>
                        <div class="referral-letter">
                            <div class="details-title">Referral Letter</div>
                            <div class="details-info">
                                @if (!empty($appointment->referral_letter))
                                    <!-- <a href="{{ asset('storage/' . $appointment->referral_letter) }}" target="_blank">View Referral Letter</a> -->
                                    <a href="{{ asset('storage/' . rawurlencode($appointment->referral_letter)) }}" target="_blank">View Referral Letter</a>
                                    <!-- <span class="download-icon"><img src="{{ asset('img/downloads.png') }}" alt="icon"></span> -->
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="section-facility">
                            <div class="details-title">Section/Facility/Specialist</div>
                            <div class="details-info">{{ $section->section_name ?? '-' }}</div>
                        </div>
                        <div class="date">
                            <div class="details-title">Date</div>
                            <div class="details-info">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d F Y') }}</div>
                        </div>
                        <div class="time">
                            <div class="details-title">Time</div>
                            <div class="details-info">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</div>
                        </div>
                        <div class="status">
                            <div class="details-title">Status</div>
                            <div class="details-info {{ strtolower(str_replace(' ', '-', $appointment->status)) }}">
                                {{ $appointment->status }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="action-button">
                    <a class="cancel-btn" href="{{ url()->previous() }}">Back</a>

                    @php
                        $status = strtolower($appointment->status);
                    @endphp

                    @if ($status === 'pending')
                        <a class="delete-button" href="{{ route('patient.delete-appointment', ['id' => $appointment->appointment_id]) }}" 
                        onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                        <a class="reschedule-button" href="{{ route('patient.reschedule-appointment', ['id' => $appointment->appointment_id]) }}">Reschedule</a>

                    @elseif ($status === 'change requested')
                        <a class="delete-button" href="{{ route('patient.delete-appointment', ['id' => $appointment->appointment_id]) }}" 
                        onclick="return confirm('Are you sure you want to delete this appointment?')">Delete</a>
                        <a class="reschedule-button" href="{{ route('patient.edit-appointment', ['id' => $appointment->appointment_id]) }}">Edit</a>

                    @elseif ($status === 'approved')
                        <a class="delete-button" href="{{ route('patient.cancel-appointment', ['id' => $appointment->appointment_id]) }}" 
                        onclick="return confirm('Are you sure you want to cancel this appointment?')">Cancel</a>
                        <a class="reschedule-button" href="{{ route('patient.reschedule-appointment', ['id' => $appointment->appointment_id]) }}">Reschedule</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
