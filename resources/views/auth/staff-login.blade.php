<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container-login">
        <div class="page-title">
            <h1>Welcome Back</h1>
            <p>Log in to your account.</p>
        </div> 
        <div class="card">
            <form method="POST" action="{{ route('staff.login') }}">
                @csrf
                <div class="input-group">
                    <label for="staff_id">Staff ID</label>
                    <div class="input-icon">
                        <div class="icon-mykad"><img src="{{ asset('img/staff-id.png') }}" alt="icon"></div>
                        <input type="text" id="staff_id" name="staff_id" value="{{ old('staff_id') }}" placeholder="Enter your Staff ID" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <div class="icon-password"><img src="{{ asset('img/lock.png') }}" alt="icon"></div>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <div class="icon-hide-pw"><img src="{{ asset('img/unhide.png') }}" alt="icon"></div>
                    </div>
                </div>

                @if($errors->any())
                    <p style="color: red;">{{ $errors->first() }}</p>
                @endif

                <button type="submit" class="btn">Log In</button>
            </form>
        </div>
    </div>
</body>
</html>
