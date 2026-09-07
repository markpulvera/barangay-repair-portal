<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Damage</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <header>
            <h1>Infrastructure Damage Reporting System</h1>
        </header>

        <nav>
            <a href="{{ route('repair.dashboard') }}">Dashboard</a> |
            <a href="{{ route('repair.view') }}">View Reports</a> 
        </nav>

        <section id="create">
            <h2>Report Infrastructure Damage</h2>


        <form action="{{ route('repair.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Reporter Name:</label>
    <input type="text" name="Reporter_name" placeholder="Full Name" required><br>

    <label>Location:</label>
    <input type="text" name="Location_landmark" placeholder="Landmark" required><br>

    <label>Risk Level:</label>
    <select name="Risk_level" required>
        <option value="Low">Low</option>
        <option value="Medium">Medium</option>
        <option value="High">High</option>
    </select><br>

    <label>Facility Type:</label>
    <select name="Facility_type" required>
        <option value="Streetlight / Post">Streetlight / Post</option>
        <option value="Drainage / Canal">Drainage / Canal</option>
        <option value="Water Pipe">Water Pipe</option>
        <option value="Public Pathway / Road">Public Pathway / Road</option>
    </select><br>

    <label>Damage Description:</label>
    <textarea name="Damage_description" placeholder="Describe the damage..." required></textarea><br>

    <label>Date Observed:</label>
    <input type="date" name="Date_observed" required><br>

    <label>Upload Evidence Image: </label>
    <input type="file" name="Image" accept="image/*">

    <button type="submit">Submit Report</button>
</form>


    @if ($errors->any())
    <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 10px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</body>
</html>