<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Repair Report</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
    <h2>Update Infrastructure Damage Report</h2>

    <form action="{{ route('repair.update', $repair->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Reporter Name:</label><br>
        <input type="text" name="Reporter_name" value="{{ old('Reporter_name', $repair->Reporter_name) }}" required><br><br>

        <label>Location / Landmark:</label><br>
        <input type="text" name="Location_landmark" value="{{ old('Location_landmark', $repair->Location_landmark) }}" required><br><br>

        <label>Risk Level:</label>
        <select name="Risk_level" required>
            <option value="Low" {{ old('Risk_level', $repair->Risk_level) == 'Low' ? 'selected' : '' }}>Low</option>
            <option value="Medium" {{ old('Risk_level', $repair->Risk_level) == 'Medium' ? 'selected' : '' }}>Medium</option>
            <option value="High" {{ old('Risk_level', $repair->Risk_level) == 'High' ? 'selected' : '' }}>High</option>
        </select><br>

        <label>Facility Type:</label><br>
        <select name="Facility_type" required>
            @foreach(['Streetlight / Post', 'Drainage / Canal', 'Water Pipe', 'Public Pathway / Road'] as $type)
                <option value="{{ $type }}" {{ old('Facility_type', $repair->Facility_type) == $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select><br><br>

        <label>Damage Description:</label><br>
        <textarea name="Damage_description" required>{{ old('Damage_description', $repair->Damage_description) }}</textarea><br><br>

        <label>Date Observed:</label><br>
        <input type="date" name="Date_observed" value="{{ old('Date_observed', $repair->Date_observed) }}" required><br><br>
        
        <form action="{{ route('repair.update', $repair->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Upload Evidence Image: </label>
        <input type="file" name="Image" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>

        <a href="{{ route('admin.dashboard') }}">Cancel</a>
    </form>
    
   
</div>
</body>
</html>