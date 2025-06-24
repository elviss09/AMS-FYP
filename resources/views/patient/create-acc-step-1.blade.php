<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Step 1</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/create-acc.css') }}">
</head>
<body>
    <div class="container-register">
        <div class="page-title">
            <h1>Create your account.</h1>
            <p>Step 1 of 4.</p>
        </div>      

        <div class="card">
            <!-- Stepper -->
            <div class="stepper">
                <div class="step active">1</div>
                <div class="step-line"></div>
                <div class="step">2</div>
                <div class="step-line"></div>
                <div class="step">3</div>
                <div class="step-line"></div>
                <div class="step">4</div>
            </div>

            <div class="card-title">
                Please enter your MyKad number.
            </div>

            <form method="POST" action="{{ route('create.acc.handleStep1') }}">
                @csrf
                <div class="input-group">
                    <input type="text" id="mykad" name="mykad" placeholder="Enter your MyKad number" value="{{ old('mykad') }}" required>
                </div>

                @if ($errors->has('mykad'))
                    <p style="color: red;">{{ $errors->first('mykad') }}</p>
                @endif

                <button type="submit" class="btn">Enter</button>
            </form>
        </div>
    </div>
</body>
</html>
