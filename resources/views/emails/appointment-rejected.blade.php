<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Appointment Rejected</title></head>
<body>
    <p>Dear <strong>{{ $patientName }}</strong>,</p>
    <p>Your appointment (ID: <strong>{{ $appointmentId }}</strong>) has been <strong>rejected</strong>.</p>
    <p><strong>Reason:</strong> {{ $reason }}</p>
    <p>Thank you,<br>PRIMA UNIMAS Health Center</p>
</body>
</html>
