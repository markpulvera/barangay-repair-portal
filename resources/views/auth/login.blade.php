<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Barangay Portal</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container" style="max-width: 420px; text-align: center;">
        <h1>Barangay Portal</h1>
        <h2>Resident Log In</h2>

        @if ($errors->any())
            <div style="color: #e53e3e; margin-bottom: 15px; text-align: left;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Google OAuth Login -->
        <a href="{{ route('auth.google') }}" class="btn" style="background-color: #db4437; width: 100%; display: block; margin-bottom: 20px; text-decoration: none;">
            Continue with Gmail
        </a>

        <div style="margin: 20px 0; border-bottom: 1px solid #e2e8f0; line-height: 0.1em;">
            <span style="background:#fff; padding:0 10px; color: #718096; font-size: 0.85rem;">OR</span>
        </div>

        <!-- Phone Number Login Form -->
        <form action="{{ route('login.phone') }}" method="POST" style="text-align: left;">
            @csrf
            <div>
                <label for="phone_number">Mobile Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number" placeholder="09123456789" required>
            </div>
            <button type="submit" class="btn" style="width: 100%; margin-top: 10px;">Log In with Phone</button>
        </form>
    </div>
</body>
</html>