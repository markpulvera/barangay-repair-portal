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
        <a href="{{ route('repair.create') }}">
            <button type="button">File a Repair Request</button>
        </a>
    </main>
</div>
</body>
</html>