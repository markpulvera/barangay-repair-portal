<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Barangay Portal</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container" style="max-width: 420px; text-align: center;">
        <h1>Barangay Portal</h1>
        <h2>Enter Verification Code</h2>
        <p>We've sent a code to: <strong>{{ $phone ?? request('phone') }}</strong></p>

        @if (session('success'))
            <div style="color: green; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="color: #e53e3e; margin-bottom: 15px; text-align: left;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.otp.verify.submit') }}" method="POST" style="text-align: left;">
            @csrf
            <input type="hidden" name="phone_number" value="{{ $phone ?? request('phone') }}">
            <div>
                <label for="otp">6-Digit OTP Code:</label>
                <input type="text" name="otp" id="otp" placeholder="123456" maxlength="6" required>
            </div>
            <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">Verify & Log In</button>
        </form>
    </div>
</body>
</html>