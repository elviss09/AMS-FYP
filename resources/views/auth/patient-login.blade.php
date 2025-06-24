<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
        @if(session('success'))
            <div class="success-message" style="color: green; font-weight: bold; margin-bottom: 10px;">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <form method="POST" action="{{ route('patient.login') }}">
                @csrf

                <div class="input-group">
                    <label for="mykad">MyKad</label>
                    <div class="input-icon">
                        <div class="icon-mykad"><img src="{{ asset('img/id-card.png') }}" alt="icon"></div>
                        <input type="text" id="mykad" name="mykad" placeholder="Enter your MyKad number" required value="{{ old('mykad') }}">
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <div class="icon-password"><img src="{{ asset('img/lock.png') }}" alt="icon"></div>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <div class="icon-hide-pw" id="togglePassword">
                            <img id="toggleIcon" src="{{ asset('img/unhide.png') }}" alt="icon">
                        </div>
                    </div>
                </div>

                @error('login')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
                
                <div style="margin-top: 10px; text-align: right; font-style:italic">
                    <a href="{{ route('password.request') }}" style="font-size: 14px; color: #007bff;">Forgot your password?</a>
                </div>

                <button type="submit" class="btn">Enter</button>
                <a href="{{ route('register.step1') }}" class="register-button">Register</a>
            </form>
        </div>
    </div>

<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        const passwordInput = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon");

        const isPassword = passwordInput.getAttribute("type") === "password";
        passwordInput.setAttribute("type", isPassword ? "text" : "password");

        // Change icon image
        toggleIcon.src = isPassword 
            ? "{{ asset('img/hide.png') }}" 
            : "{{ asset('img/unhide.png') }}";
    });
</script>
</body>
</html>
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
        @if(session('success'))
            <div class="success-message" style="color: green; font-weight: bold; margin-bottom: 10px;">
                {{ session('success') }}
            </div>
        @endif
        <div class="card">
            <form method="POST" action="{{ route('patient.login') }}">
                @csrf

                <div class="input-group">
                    <label for="mykad">MyKad</label>
                    <div class="input-icon">
                        <div class="icon-mykad"><img src="{{ asset('img/id-card.png') }}" alt="icon"></div>
                        <input type="text" id="mykad" name="mykad" placeholder="Enter your MyKad number" required value="{{ old('mykad') }}">
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <div class="icon-password"><img src="{{ asset('img/lock.png') }}" alt="icon"></div>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <div class="icon-hide-pw" id="togglePassword">
                            <img id="toggleIcon" src="{{ asset('img/unhide.png') }}" alt="icon">
                        </div>
                    </div>
                </div>

                @error('login')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
                
                <div style="margin-top: 10px; text-align: right; font-style:italic">
                    <a href="{{ route('password.request') }}" style="font-size: 14px; color: #007bff;">Forgot your password?</a>
                </div>

                <button type="submit" class="btn">Enter</button>
                <a href="{{ route('register.step1') }}" class="register-button">Register</a>
            </form>
        </div>
    </div>

<script>
    document.getElementById("togglePassword").addEventListener("click", function() {
        const passwordInput = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon");

        const isPassword = passwordInput.getAttribute("type") === "password";
        passwordInput.setAttribute("type", isPassword ? "text" : "password");

        // Change icon image
        toggleIcon.src = isPassword 
            ? "{{ asset('img/hide.png') }}" 
            : "{{ asset('img/unhide.png') }}";
    });
</script>
</body>
</html>
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
