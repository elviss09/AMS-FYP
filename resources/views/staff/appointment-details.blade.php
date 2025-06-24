<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appointment-details.css') }}">
</head>
<body>
<div class="container">
    <div class="sidebar-box">
        @include('staff.sidebar')
    </div>

    <div class="content">
        <div class="details-container">
            <div class="content-header">Appointment Details</div>

            <div class="details-section">

                {{-- Patient Details --}}
                <div class="section-patient-details">
                    <div class="full-name">
                        <div class="details-title">Patient Name</div>
                        <div class="details-info">{{ $patient->full_name ?? '-' }}</div>
                    </div>
                    <div class="mykad-number">
                        <div class="details-title">MyKad Number</div>
                        <div class="details-info">{{ $appointment->patient_id }}</div>
                    </div>
                    <div class="dob">
                        <div class="details-title">Date of Birth</div>
                        <div class="details-info">{{ $patient ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d F Y') : '-' }}</div>
                    </div>
                    <div class="age">
                        <div class="details-title">Age</div>
                        <div class="details-info">{{ $age ?? '-' }} years old</div>
                    </div>
                    <div class="gender">
                        <div class="details-title">Gender</div>
                        <div class="details-info">{{ $patient->gender ?? '-' }}</div>
                    </div>
                </div>

                {{-- Appointment Details --}}
                <div class="details-content">
                    <div class="appointment-id">
                        <div class="details-title">Appointment ID</div>
                        <div class="details-info">{{ $appointment->appointment_id }}</div>
                    </div>
                    <div class="appointment-type">
                        <div class="details-title">Appointment Type</div>
                        <div class="details-info">{{ $appointment->appointment_type }}</div>
                    </div>
                    <div class="created-at">
                        <div class="details-title">Created At</div>
                        <div class="details-info">{{ \Carbon\Carbon::parse($appointment->created_at)->format('d F Y, g:i A') }}</div>
                    </div>
                    <div class="referral-letter">
                        <div class="details-title">Referral Letter</div>
                        <div class="details-info">
                            @if ($appointment->referral_letter)
                                <a href="{{ asset('storage/' . $appointment->referral_letter) }}" target="_blank">View Referral Letter</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="section-facility">
                        <div class="details-title">Section/Facility/Specialist</div>
                        <div class="details-info">{{ $section->section_name }}</div>
                    </div>
                    <div class="assigned-doctor">
                        <div class="details-title">Assigned Doctor</div>
                        <div class="details-info">{{ $assignedDoctorName }}</div>
                    </div>
                    <div class="approved-by">
                        <div class="details-title">Approved By</div>
                        <div class="details-info">{{ $approvedByName }}</div>
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
                        <div class="details-info {{ strtolower(str_replace(' ', '-', $appointment->status)) }}">{{ $appointment->status }}</div>
                    </div>
                    <div class="status-details"><div class="details-title">Status Details</div><div class="details-info">{{ $appointment->status_details ?? '-' }}</div></div>
                </div>
            </div>

            <div class="action-button">
                <a class="cancel-btn" href="{{ route('staff.appointment-record') }}">Back</a>
                @php $status = strtolower($appointment->status); @endphp
                @if ($status === 'pending')
                    <button class="delete-button" onclick="showRejectPopup()">Reject</button>
                    <button class="accept-button" onclick="showAcceptPopup()">Accept</button>
                    <button class="request-change-button" onclick="showRequestChangePopup()">Request Change</button>
                @elseif (in_array($status, ['approved', 'rejected', 'change requested']))
                    <button class="delete-button" onclick="showRejectPopup()">Cancel</button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- POPUPS --}}
<div id="rejectPopup" class="popup-box" style="display: none;">
    <div class="popup-content">
        <div class="popup-title">Reject Appointment</div>
        <textarea id="rejectReason" placeholder="State reason for rejection..."></textarea>
        <div class="popup-actions">
            <button onclick="submitReject()">Submit</button>
            <button onclick="closePopup('rejectPopup')">Cancel</button>
        </div>
    </div>
</div>

<div id="acceptPopup" class="popup-box" style="display: none;">
    <div class="popup-content">
        <div class="popup-title">Confirm Accept?</div>
        <div class="popup-actions">
            <button onclick="confirmAccept()">Yes</button>
            <button onclick="closePopup('acceptPopup')">No</button>
        </div>
    </div>
</div>

<div id="changePopup" class="popup-box" style="display: none;">
    <div class="popup-content">
        <div class="popup-title">Request Change</div>
        <textarea id="changeRequest" placeholder="State requested changes..."></textarea>
        <div class="popup-actions">
            <button onclick="submitChange()">Submit</button>
            <button onclick="closePopup('changePopup')">Cancel</button>
        </div>
    </div>
</div>

<script>
    function showRejectPopup() { document.getElementById('rejectPopup').style.display = 'flex'; }
    function showAcceptPopup() { document.getElementById('acceptPopup').style.display = 'flex'; }
    function showRequestChangePopup() { document.getElementById('changePopup').style.display = 'flex'; }
    function closePopup(id) { document.getElementById(id).style.display = 'none'; }

    function submitReject() {
        const reason = document.getElementById('rejectReason').value.trim();
        if (reason === "") { alert("Please enter a reason for rejection."); return; }
        window.location.href = `{{ url('reject-appointment/'.$appointment->appointment_id) }}?reason=${encodeURIComponent(reason)}`;
    }

    function confirmAccept() {
        window.location.href = `{{ url('accept-appointment/'.$appointment->appointment_id) }}`;
    }

    function submitChange() {
        const reason = document.getElementById('changeRequest').value.trim();
        if (reason === "") { alert("Please enter the requested change."); return; }
        window.location.href = `{{ url('request-change-appointment/'.$appointment->appointment_id) }}?change=${encodeURIComponent(reason)}`;
    }
</script>
</body>
</html>
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appointment-details.css') }}">
</head>
<body>
<div class="container">
    <div class="sidebar-box">
        @include('staff.sidebar')
    </div>

    <div class="content">
        <div class="details-container">
            <div class="content-header">Appointment Details</div>

            <div class="details-section">

                {{-- Patient Details --}}
                <div class="section-patient-details">
                    <div class="full-name">
                        <div class="details-title">Patient Name</div>
                        <div class="details-info">{{ $patient->full_name ?? '-' }}</div>
                    </div>
                    <div class="mykad-number">
                        <div class="details-title">MyKad Number</div>
                        <div class="details-info">{{ $appointment->patient_id }}</div>
                    </div>
                    <div class="dob">
                        <div class="details-title">Date of Birth</div>
                        <div class="details-info">{{ $patient ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d F Y') : '-' }}</div>
                    </div>
                    <div class="age">
                        <div class="details-title">Age</div>
                        <div class="details-info">{{ $age ?? '-' }} years old</div>
                    </div>
                    <div class="gender">
                        <div class="details-title">Gender</div>
                        <div class="details-info">{{ $patient->gender ?? '-' }}</div>
                    </div>
                </div>

                {{-- Appointment Details --}}
                <div class="details-content">
                    <div class="appointment-id">
                        <div class="details-title">Appointment ID</div>
                        <div class="details-info">{{ $appointment->appointment_id }}</div>
                    </div>
                    <div class="appointment-type">
                        <div class="details-title">Appointment Type</div>
                        <div class="details-info">{{ $appointment->appointment_type }}</div>
                    </div>
                    <div class="created-at">
                        <div class="details-title">Created At</div>
                        <div class="details-info">{{ \Carbon\Carbon::parse($appointment->created_at)->format('d F Y, g:i A') }}</div>
                    </div>
                    <div class="referral-letter">
                        <div class="details-title">Referral Letter</div>
                        <div class="details-info">
                            @if ($appointment->referral_letter)
                                <a href="{{ asset('storage/' . $appointment->referral_letter) }}" target="_blank">View Referral Letter</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="section-facility">
                        <div class="details-title">Section/Facility/Specialist</div>
                        <div class="details-info">{{ $section->section_name }}</div>
                    </div>
                    <div class="assigned-doctor">
                        <div class="details-title">Assigned Doctor</div>
                        <div class="details-info">{{ $assignedDoctorName }}</div>
                    </div>
                    <div class="approved-by">
                        <div class="details-title">Approved By</div>
                        <div class="details-info">{{ $approvedByName }}</div>
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
                        <div class="details-info {{ strtolower(str_replace(' ', '-', $appointment->status)) }}">{{ $appointment->status }}</div>
                    </div>
                    <div class="status-details"><div class="details-title">Status Details</div><div class="details-info">{{ $appointment->status_details ?? '-' }}</div></div>
                </div>
            </div>

            <div class="action-button">
                <a class="cancel-btn" href="{{ route('staff.appointment-record') }}">Back</a>
                @php $status = strtolower($appointment->status); @endphp
                @if ($status === 'pending')
                    <button class="delete-button" onclick="showRejectPopup()">Reject</button>
                    <button class="accept-button" onclick="showAcceptPopup()">Accept</button>
                    <button class="request-change-button" onclick="showRequestChangePopup()">Request Change</button>
                @elseif (in_array($status, ['approved', 'rejected', 'change requested']))
                    <button class="delete-button" onclick="showRejectPopup()">Cancel</button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- POPUPS --}}
<div id="rejectPopup" class="popup-box" style="display: none;">
    <div class="popup-content">
        <div class="popup-title">Reject Appointment</div>
        <textarea id="rejectReason" placeholder="State reason for rejection..."></textarea>
        <div class="popup-actions">
            <button onclick="submitReject()">Submit</button>
            <button onclick="closePopup('rejectPopup')">Cancel</button>
        </div>
    </div>
</div>

<div id="acceptPopup" class="popup-box" style="display: none;">
    <div class="popup-content">
        <div class="popup-title">Confirm Accept?</div>
        <div class="popup-actions">
            <button onclick="confirmAccept()">Yes</button>
            <button onclick="closePopup('acceptPopup')">No</button>
        </div>
    </div>
</div>

<div id="changePopup" class="popup-box" style="display: none;">
    <div class="popup-content">
        <div class="popup-title">Request Change</div>
        <textarea id="changeRequest" placeholder="State requested changes..."></textarea>
        <div class="popup-actions">
            <button onclick="submitChange()">Submit</button>
            <button onclick="closePopup('changePopup')">Cancel</button>
        </div>
    </div>
</div>

<script>
    function showRejectPopup() { document.getElementById('rejectPopup').style.display = 'flex'; }
    function showAcceptPopup() { document.getElementById('acceptPopup').style.display = 'flex'; }
    function showRequestChangePopup() { document.getElementById('changePopup').style.display = 'flex'; }
    function closePopup(id) { document.getElementById(id).style.display = 'none'; }

    function submitReject() {
        const reason = document.getElementById('rejectReason').value.trim();
        if (reason === "") { alert("Please enter a reason for rejection."); return; }
        window.location.href = `{{ url('reject-appointment/'.$appointment->appointment_id) }}?reason=${encodeURIComponent(reason)}`;
    }

    function confirmAccept() {
        window.location.href = `{{ url('accept-appointment/'.$appointment->appointment_id) }}`;
    }

    function submitChange() {
        const reason = document.getElementById('changeRequest').value.trim();
        if (reason === "") { alert("Please enter the requested change."); return; }
        window.location.href = `{{ url('request-change-appointment/'.$appointment->appointment_id) }}?change=${encodeURIComponent(reason)}`;
    }
</script>
</body>
</html>
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
