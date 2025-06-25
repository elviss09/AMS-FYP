<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Email</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
</head>
<body>
    <div class="container-login">
        <div class="card">
            <div>
                <div class="card-title">An OTP will be sent to this email.</div>
            </div>
            <p style="margin-top: 20px;">{{ $email }}</p>

            <form method="POST" action="{{ route('password.sendOtp') }}">
                @csrf
                <button type="submit">Send OTP</button>
            </form>
        </div>
    </div>
</body>
</html>
