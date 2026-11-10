<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Records - Barangay Portal</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .table-custom tbody td {
            font-family: "Times New Roman", Times, serif;
        }
        .table-custom tbody td.location-cell,
        .table-custom tbody td.facility-cell,
        .table-custom tbody td.status-cell {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="main-card">
        <h2 class="portal-header mb-1">Report Records</h2>
        <div class="mb-4">
            <a href="{{ route('repair.dashboard') }}" class="text-decoration-none me-2">Dashboard</a> |
            <a href="{{ route('repair.create') }}" class="text-decoration-none ms-2 me-2">Create Request</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle table-custom text-center">
                <thead>
                    <tr>
                        <th>Reporter Name</th>
                        <th>Location / Landmark</th>
                        <th>Facility Type</th>
                        <th>Risk Level</th>
                        <th>Damage Description</th>
                        <th>Date Observed</th>
                        <th>Status</th>
                        <th>Initial Image</th>
                        <th>Proof of Repair</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($repairs as $repair)
                    <tr>
                        <td>{{ $repair->Reporter_name }}</td>
                        <td class="location-cell">{{ $repair->Location_landmark }}</td>
                        <td class="facility-cell">{{ $repair->Facility_type }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $repair->Risk_level }}</span>
                        </td>
                        <td>{{ $repair->Damage_description }}</td>
                        <td>{{ $repair->Date_observed }}</td>
                        <td class="status-cell">
                            <span class="badge {{ $repair->Status == 'Resolved' ? 'bg-success' : ($repair->Status == 'In Progress' ? 'bg-primary' : 'bg-warning text-dark') }}">
                                {{ $repair->Status ?? 'Pending' }}
                            </span>
                        </td>
                        <td>
                            @if($repair->Image)
                                <a href="{{ asset('storage/' . $repair->Image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $repair->Image) }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                                </a>
                            @else
                                No Image
                            @endif
                        </td>
                        <td>
                            @if($repair->proof_image)
                                <a href="{{ asset('storage/' . $repair->proof_image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $repair->proof_image) }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 2px solid #198754;" title="Proof of Repair">
                                </a>
                                <br><small style="color: #198754; font-weight: bold;">Fixed Proof</small>
                            @else
                                <span class="text-muted">Awaiting Repair</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No repair records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>