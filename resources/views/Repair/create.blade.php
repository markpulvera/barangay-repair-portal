<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Damage</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 350px; width: 100%; border-radius: 8px; margin-bottom: 15px; }
    </style>
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

                <!-- Pinpoint Location on Map -->
                <label>Pinpoint Location on Map:</label>
                <div id="map"></div>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

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
                <input type="file" name="Image" accept="image/*"><br>

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
        </section>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Default center (Barangay Center / Cebu City)
        const defaultLat = 10.3157;
        const defaultLng = 123.8854;

        const map = L.map('map').setView([defaultLat, defaultLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        function updateMarker(lat, lng) {
            marker.setLatLng([lat, lng]);
            map.setView([lat, lng], map.getZoom());
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        }

        map.on('click', function(e) {
            updateMarker(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            document.getElementById('latitude').value = pos.lat;
            document.getElementById('longitude').value = pos.lng;
        });

        // Initialize hidden inputs
        document.getElementById('latitude').value = defaultLat;
        document.getElementById('longitude').value = defaultLng;
    </script>
</body>
</html>