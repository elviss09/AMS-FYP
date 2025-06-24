<!DOCTYPE html>
<html>
<head>
    <title>Appointment Reminder</title>
</head>
<body>
    <h2>Dear {{ $patient->full_name }},</h2>

    <p>This is a reminder for your upcoming appointment:</p>

    <ul>
        <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</li>
        <li><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</li>
        <li><strong>Location:</strong> {{ $appointment->section->section_name ?? 'PKP UNIMAS' }}</li>
        <li><strong>Type:</strong> {{ $appointment->appointment_type }}</li>
    </ul>

    <p>We are reminding you {{ $reminderTime }} before your appointment.</p>

    <p>Please make sure to arrive on time.</p>

    <p>Thank you.</p>
</body>
</html>
