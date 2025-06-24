<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Step 3</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/create-acc.css') }}">
</head>
<body>
    <div class="container-register">
        <div class="page-title">
            <h1>Create your account.</h1>
            <p>Step 3 of 4.</p>
        </div>

        <div class="card">
            <!-- Stepper -->
            <div class="stepper">
                <div class="step active">1</div>
                <div class="step-line active"></div>
                <div class="step active">2</div>
                <div class="step-line active"></div>
                <div class="step active">3</div>
                <div class="step-line"></div>
                <div class="step">4</div>
            </div>

            <div class="card-title">
                OTP Verification
                <div class="card-details">
                    One-Time Password will be sent to this phone number/email.
                </div>
            </div>

            <form method="POST" action="{{ route('create.acc.requestOtp') }}">
                @csrf

                <div class="input-group otp">
                    <input type="text" id="otp_destination" name="otp_destination" value="{{ $otp_destination }}" readonly>
                </div>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p style="color: red;">{{ $error }}</p>
                    @endforeach
                @endif

                <button type="submit" name="request_otp" class="btn">Request OTP</button>
            </form>
        </div>
    </div>
</body>
</html>
