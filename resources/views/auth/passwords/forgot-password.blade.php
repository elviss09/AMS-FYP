<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - MyKad</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
</head>
<body>
    <div class="container-login">
        <div class="card">
            <form method="POST" action="{{ route('password.checkMyKad') }}">
                @csrf

                <div>
                    <div class="card-title">Forgot your password?</div>
                    <div class="card-details">Enter your MyKad number to continue.</div>
                </div>

                <div class="input-group">
                    <label for="mykad">MyKad</label>
                    <div class="input-icon">
                        <div class="icon-mykad"><img src="{{ asset('img/id-card.png') }}" alt="icon"></div>
                        <input type="text" id="mykad" name="mykad" placeholder="Enter your MyKad" required>
                    </div>
                </div>

                @error('mykad')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <button type="submit">Next</button>
            </form>
        </div>
    </div>
</body>
</html>
