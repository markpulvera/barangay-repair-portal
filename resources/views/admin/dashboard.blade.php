<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Admin Portal - Management Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/gwapo.css') }}">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .admin-map { height: 150px; width: 100%; border-radius: 6px; margin-top: 5px; }
    </style>
</head>
<body>

<div class="portal-container">
    <div class="main-card">
        <!-- Admin Header -->
        <div class="admin-header">
            <div>
                <h3 class="portal-header">Barangay Admin Portal</h3>
                <span class="header-subtitle">Confidential Record Oversight & Case Management</span>
            </div>
            <div class="header-user-actions">
                <span class="user-name">Logged in as Administrator: {{ Auth::user()->name ?? 'mark pulvera' }}</span>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-logout">Logout</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- OVERVIEW STATS (4 Side-by-Side Cards) -->
        <div class="stats-grid">
            <div class="stats-card card-grey-1">
                <div class="stats-label">Pending Audit</div>
                <div class="stats-number">{{ $pendingCount }}</div>
            </div>
            <div class="stats-card card-grey-2">
                <div class="stats-label">In Progress</div>
                <div class="stats-number">{{ $inProgressCount }}</div>
            </div>
            <div class="stats-card card-blue-1">
                <div class="stats-label">Resolved Today</div>
                <div class="stats-number">{{ $resolvedTodayCount }}</div>
            </div>
            <div class="stats-card card-blue-2">
                <div class="stats-label">Total System Records</div>
                <div class="stats-number">{{ $repairs->count() }}</div>
            </div>
        </div>

        <!-- CONFIDENTIAL ADMINISTRATIVE TABLE -->
        <h5 class="portal-header table-title">System Case Queue & Audit Log</h5>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Case ID</th>
                        <th>Reporter Info <span class="confidential-badge">Private</span></th>
                        <th>Location & Map Pin</th>
                        <th>Facility & Description</th>
                        <th>Risk Level</th>
                        <th>Assigned Dept</th>
                        <th>Status</th>
                        <th>Logged Date</th>
                        <th>Evidence / Proof</th>
                        <th>Admin Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($repairs as $repair)
                    <tr>
                        <td class="fw-bold">#{{ $repair->id }}</td>
                        <td class="text-start">
                            <strong>{{ $repair->Reporter_name }}</strong>
                        </td>
                        <td>
                            {{ $repair->Location_landmark }}
                            @if($repair->latitude && $repair->longitude)
                                <div id="admin-map-{{ $repair->id }}" class="admin-map"></div>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        var map = L.map('admin-map-{{ $repair->id }}', {
                                            zoomControl: false,
                                            dragging: false,
                                            scrollWheelZoom: false
                                        }).setView([{{ $repair->latitude }}, {{ $repair->longitude }}], 15);

                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            maxZoom: 19
                                        }).addTo(map);

                                        L.marker([{{ $repair->latitude }}, {{ $repair->longitude }}]).addTo(map);
                                    });
                                </script>
                            @else
                                <br><small class="text-muted">No GPS coordinates pinned</small>
                            @endif
                        </td>
                        <td class="text-start">
                            <span class="badge badge-facility">{{ $repair->Facility_type }}</span>
                            <div class="desc-text">{{ $repair->Damage_description }}</div>
                        </td>
                        <td>
                            <span class="badge badge-risk {{ strtolower($repair->Risk_level) }}">
                                {{ $repair->Risk_level }}
                            </span>
                        </td>
                        <td>{{ $repair->Assigned_department ?? 'Unassigned' }}</td>
                        <td>
                            <span class="badge badge-status {{ strtolower(str_replace(' ', '-', $repair->Status ?? 'pending')) }}">
                                {{ $repair->Status ?? 'Pending' }}
                            </span>
                        </td>
                        <td class="small-text">{{ $repair->Date_observed }}</td>
                        <td>
                            <div>
                                <small><strong>Initial:</strong></small><br>
                                @if($repair->Image)
                                    <a href="{{ asset('storage/' . $repair->Image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $repair->Image) }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;">
                                    </a>
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </div>
                            <div style="margin-top: 6px;">
                                <small><strong>Proof (Fixed):</strong></small><br>
                                @if($repair->proof_image)
                                    <a href="{{ asset('storage/' . $repair->proof_image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $repair->proof_image) }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px; border: 2px solid #198754;">
                                    </a>
                                @else
                                    <span class="text-muted">Pending Proof</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="actions-group">
                                <!-- Status Button -->
                                <button class="btn btn-status" onclick="document.getElementById('updateModal{{ $repair->id }}').style.display='flex'">
                                    Status
                                </button>

                                <!-- Modify Details Button -->
                                <a href="{{ route('repair.edit', $repair->id) }}" class="btn btn-modify">Modify</a>
                                
                                <!-- Role Selector Dropdown -->
                                @if($repair->user_id)
                                    <form action="{{ route('admin.users.updateRole', $repair->user_id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" class="form-select form-select-sm">
                                            <option value="resident" {{ in_array($repair->user->role ?? '', ['resident', 'user']) ? 'selected' : '' }}>Resident</option>
                                            <option value="admin" {{ ($repair->user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="text-muted small">No User ID</span>
                                @endif

                                <!-- Delete Record Form -->
                                <form action="{{ route('repair.destroy', $repair->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Permanently delete Case #{{ $repair->id }} from system logs?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- STATUS & TRIAGE MODAL -->
                    <div id="updateModal{{ $repair->id }}" class="modal-overlay" style="display: none;">
                        <div class="modal-card">
                            <form action="{{ route('admin.repair.updateStatus', $repair->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h4>Update Status & Proof — Case #{{ $repair->id }}</h4>
                                    <span class="modal-close" onclick="document.getElementById('updateModal{{ $repair->id }}').style.display='none'">&times;</span>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Risk Assessment</label>
                                        <select name="Risk_level" class="form-control">
                                            <option value="Low" {{ $repair->Risk_level == 'Low' ? 'selected' : '' }}>Low</option>
                                            <option value="Medium" {{ $repair->Risk_level == 'Medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="High" {{ $repair->Risk_level == 'High' ? 'selected' : '' }}>High</option>
                                            <option value="Critical" {{ $repair->Risk_level == 'Critical' ? 'selected' : '' }}>Critical</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Assigned Department</label>
                                        <input type="text" name="Assigned_department" class="form-control" value="{{ $repair->Assigned_department ?? 'City Engineering Office' }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Case Status</label>
                                        <select name="Status" class="form-control">
                                            <option value="Pending" {{ $repair->Status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="In Progress" {{ $repair->Status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Resolved" {{ $repair->Status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                        </select>
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label>Upload Proof Image (After Repair):</label>
                                        <input type="file" name="proof_image" class="form-control" accept="image/*">
                                        <small class="text-muted">Upload evidence showing the problem is fixed.</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-modify" onclick="document.getElementById('updateModal{{ $repair->id }}').style.display='none'">Close</button>
                                    <button type="submit" class="btn btn-status">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="10" class="empty-cell">No records available in database.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</body>
</html>