<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
</head>
<body>
    <div class="container-login">
        <div class="page-title">
            <h1>Reset Password</h1>
            <p>Enter your new password.</p>
        </div> 
        <div class="card">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <label for="password">New Password</label>
                <div class="input-icon">
                    <input type="password" id="password" name="password" placeholder="New Password" required>
                    <div class="icon-hide-pw" id="togglePassword1">
                        <img id="toggleIcon1" src="{{ asset('img/unhide.png') }}" alt="icon">
                    </div>
                </div>

                <label for="password_confirmation" style="margin-top: 20px; display: block;">Confirm Password</label>
                <div class="input-icon">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                    <div class="icon-hide-pw" id="togglePassword2">
                        <img id="toggleIcon2" src="{{ asset('img/unhide.png') }}" alt="icon">
                    </div>
                </div>

                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
<script>
    // First toggle for New Password
    document.getElementById("togglePassword1").addEventListener("click", function() {
        const passwordInput = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon1");

        const isPassword = passwordInput.getAttribute("type") === "password";
        passwordInput.setAttribute("type", isPassword ? "text" : "password");

        toggleIcon.src = isPassword 
            ? "{{ asset('img/hide.png') }}" 
            : "{{ asset('img/unhide.png') }}";
    });

    // Second toggle for Confirm Password
    document.getElementById("togglePassword2").addEventListener("click", function() {
        const passwordInput = document.getElementById("password_confirmation");
        const toggleIcon = document.getElementById("toggleIcon2");

        const isPassword = passwordInput.getAttribute("type") === "password";
        passwordInput.setAttribute("type", isPassword ? "text" : "password");

        toggleIcon.src = isPassword 
            ? "{{ asset('img/hide.png') }}" 
            : "{{ asset('img/unhide.png') }}";
    });
</script>
</body>
</html>
