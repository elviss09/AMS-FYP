<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Step 4</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/create-acc.css') }}">
</head>
<body>
    <div class="container-register">
        <div class="page-title">
            <h1>Create your account.</h1>
            <p>Step 4 of 4.</p>
        </div>

        <div class="card">
            <!-- Stepper -->
            <div class="stepper">
                <div class="step active">1</div>
                <div class="step-line active"></div>
                <div class="step active">2</div>
                <div class="step-line active"></div>
                <div class="step active">3</div>
                <div class="step-line active"></div>
                <div class="step active">4</div>
            </div>

            <div class="card-title">
                Enter OTP
            </div>

            <form method="POST" action="{{ route('create.acc.verifyOtp') }}">
                @csrf

                <div class="input-group">
                    <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
                </div>

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <p style="color: red;">{{ $error }}</p>
                    @endforeach
                @endif

                <button type="submit" class="btn">Confirm</button>
            </form>
        </div>
    </div>
</body>
</html>
