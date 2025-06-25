<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Change Request</title></head>
<body>
    <p>Dear <strong>{{ $patientName }}</strong>,</p>
    <p>Your appointment (ID: <strong>{{ $appointmentId }}</strong>) requires some changes.</p>
    <p><strong>Change Requested:</strong> {{ $changeRequest }}</p>
    <p>Please log in to your account and review the appointment details.</p>
    <p>Thank you,<br>PRIMA UNIMAS Health Center</p>
</body>
</html>
