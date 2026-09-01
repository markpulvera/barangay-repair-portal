<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Repair System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
    <header>
        <h1>Barangay Infrastructure Repair Portal</h1>
    </header>

    <main>
        <section>
            <h2>Welcome Residents</h2>
            <p>Report damaged public facilities, broken streetlights, or clogged drainage in our community so local officials can act quickly.</p>
        </section>

        <section>
            <h2>How It Works</h2>
            <ul>
                <li>Submit your report details and location landmark.</li>
                <li>Local maintenance officers review the ticket.</li>
                <li>Service crews are dispatched for repairs.</li>
            </ul>
        </section>

        <br>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="{{ route('repair.create') }}">
                <button type="button">File a Repair Request</button>
            </a>

            <a href="{{ route('repair.view') }}">
                <button type="button" style="background-color: #6c757d; color: white; border: none; padding: 10px 18px; border-radius: 4px; cursor: pointer;">View All Reports</button>
            </a>

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 10px 18px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                    Logout
                </button>
            </form>
        </div>
    </main>
</div>
</body>
</html>