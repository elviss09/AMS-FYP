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
    <div class="container-login">
        <div class="page-title">
            <h1>Create Password</h1>
        </div>     

        <div class="card">
            <div class="card-title">
                Please enter your Staff ID.
            </div>

            <form method="POST" action="{{ route('create.acc.handleStep1') }}">
                @csrf
                <div class="input-group">
                    <input type="text" id="staff-id" name="staff-id" placeholder="Enter your Staff ID" value="{{ old('staff-id') }}" required>
                </div>

                @if ($errors->has('staff-id'))
                    <p style="color: red;">{{ $errors->first('staff-id') }}</p>
                @endif

                <button type="submit" class="btn">Enter</button>
            </form>
        </div>
    </div>
</body>
</html>
