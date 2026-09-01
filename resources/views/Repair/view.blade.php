<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Repair System - View Records</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
    <header>
        <h1>Report Records</h1>
        <nav>
            <a href="{{ route('repair.dashboard') }}">Dashboard</a> |
            <a href="{{ route('repair.create') }}">Create Request</a> 
        </nav>
    </header>
    <hr>

    <div>
        <table border="5" cellpadding="14" cellspacing="2">
            <thead>
        <tr>
            <th>Reporter Name</th>
            <th>Location / Landmark</th>
            <th>Facility Type</th>
            <th>Contact Number</th>
            <th>Damage Description</th>
            <th>Date Observed</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($repairs as $repair)
            <tr>
                <td>{{ $repair->Reporter_name }}</td>
                <td>{{ $repair->Location_landmark }}</td>
                <td>{{ $repair->Facility_type }}</td>
                <td>{{ $repair->Contact_number }}</td>
                <td>{{ $repair->Damage_description }}</td>
                <td>{{ $repair->Date_observed }}</td>
                <td>
                    @if($repair->Image)
                        <a href="{{ asset('storage/' . $repair->Image) }}" target="_blank">
                            <img src="{{ asset('storage/' . $repair->Image) }}" style="width: 80px; height: 80px; object-fit: cover;">
                        </a>
                    @else
                        No Image
                    @endif
                </td>
                <td>
    <div style="display: flex; gap: 8px; align-items: center; white-space: nowrap;">
        <!-- Edit Link -->
        <a href="{{ route('repair.edit', $repair->id) }}" class="btn btn-primary" style="padding: 6px 12px; background-color: #0d6efd; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">Edit</a>

        <!-- Delete Form -->
        <form action="{{ route('repair.destroy', $repair->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this report?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="padding: 6px 12px; background-color: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">Delete</button>
        </form>
    </div>
</td>
                    </tr>
                @empty
    <tr>
        <td colspan="8" style="text-align: center;">No repair records found.</td>
    </tr>
@endforelse
            </tbody>
        </table>
    </div>
</body>
</html>