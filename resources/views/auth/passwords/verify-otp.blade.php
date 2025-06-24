<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
</head>
<body>
    <div class="container-login">
        <div class="card">
            <form method="POST" action="{{ route('password.verifyOtp') }}">
                @csrf
                <div>
                    <div class="card-title">OTP Verification</div>
                    <div class="card-details">Enter OTP code sent to your email.</div>
                </div>

                <label for="otp">OTP Code</label>
                <div class="input-icon">
                    <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
                </div>

                @error('otp')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <button type="submit">Verify</button>
            </form>
        </div>
    </div>
</body>
</html>
