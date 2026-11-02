<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repair;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RepairController extends Controller
{
    // READ: Dashboard / Home landing page
    public function index()
    {
        // If an admin navigates to the resident dashboard, push them to the admin portal
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('repair.index'); // Resident dashboard view
    }

    // CREATE: Form view
    public function create() 
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Administrators cannot file reports. Use the admin panel to manage records.');
        }

        return view('repair.create');
    }

    // STORE: Open to all logged-in users to submit reports
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Reporter_name'      => 'required',
            'Location_landmark'  => 'required',
            'latitude'           => 'nullable|numeric',
            'longitude'          => 'nullable|numeric',
            'Risk_level'         => 'required',
            'Facility_type'      => 'required',
            'Damage_description' => 'required',
            'Date_observed'      => 'required',
            'Image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = [
            'user_id'            => Auth::id(), // Attaches the currently logged-in user's ID
            'Reporter_name'      => $request->input('Reporter_name'),
            'Location_landmark'  => $request->input('Location_landmark'),
            'latitude'           => $request->input('latitude'),
            'longitude'          => $request->input('longitude'),
            'Risk_level'         => $request->input('Risk_level'),
            'Facility_type'      => $request->input('Facility_type'),
            'Damage_description' => $request->input('Damage_description'),
            'Date_observed'      => $request->input('Date_observed'),
            'Image'              => null,
            'Status'             => 'Pending', // Default status upon creation
        ];

        if ($request->hasFile('Image')) {
            $data['Image'] = $request->file('Image')->store('repairs', 'public');
        }

        Repair::create($data);

        return redirect()->route('repair.view')->with('success', 'Report saved successfully!');
    }

    // VIEW: Accessible by all users to view records
    public function view() 
    {
        $repairs = Repair::all();  // kwaon sa database ang tanan nga repair records
        return view('repair.view', compact('repairs')); 
    }              

    // ADMIN DASHBOARD: Display triage counters & complete case list
    public function adminDashboard()
    {
        // Calculate statistics based on status
        $pendingCount = Repair::where('Status', 'Pending')->orWhereNull('Status')->count();
        $inProgressCount = Repair::where('Status', 'In Progress')->count();
        $resolvedTodayCount = Repair::where('Status', 'Resolved')
            ->whereDate('updated_at', now()->today())
            ->count();

        // Get all repair records ordered by newest first
        $repairs = Repair::orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact(
            'repairs',
            'pendingCount',
            'inProgressCount',
            'resolvedTodayCount'
        ));
    }

    // ADMIN STATUS UPDATE: Quick update for status, risk level, department assignment, and proof image
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'Status'              => 'required|in:Pending,In Progress,Resolved',
            'Risk_level'          => 'required|in:Low,Medium,High,Critical',
            'Assigned_department' => 'required|string|max:255',
            'proof_image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $repair = Repair::findOrFail($id);
        
        $data = [
            'Status'              => $request->Status,
            'Risk_level'          => $request->Risk_level,
            'Assigned_department' => $request->Assigned_department,
        ];

        // Ensure it updates 'proof_image' and leaves initial 'Image' untouched!
        if ($request->hasFile('proof_image')) {
            if ($repair->proof_image && Storage::disk('public')->exists($repair->proof_image)) {
                Storage::disk('public')->delete($repair->proof_image);
            }
            $data['proof_image'] = $request->file('proof_image')->store('proofs', 'public');
        }

        $repair->update($data);

        return redirect()->back()->with('success', 'Case #' . $id . ' status and proof updated successfully!');
    }

    // EDIT: Restricted to Admin
    public function edit($id) 
    {
        $repair = Repair::findOrFail($id);
        return view('repair.update', compact('repair'));
    }

    // UPDATE: Restricted to Admin
    public function update(Request $request, $id)
    {
        $repair = Repair::findOrFail($id);

        $data = $request->validate([
            'Reporter_name'      => 'required',
            'Location_landmark'  => 'required',
            'Risk_level'         => 'required',
            'Facility_type'      => 'required',
            'Damage_description' => 'required',
            'Date_observed'      => 'required',
            'Image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('Image')) {
            // Delete old image if present
            if ($repair->Image && Storage::disk('public')->exists($repair->Image)) {
                Storage::disk('public')->delete($repair->Image);
            }

            // Store new image using capital 'Image' key
            $data['Image'] = $request->file('Image')->store('repairs', 'public');
        } else {
            // Prevent overwriting existing image with null if no new file is uploaded
            unset($data['Image']);
        }

        $repair->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'Report updated successfully!');
    }

    // DELETE: Restricted to Admin
    public function destroy($id)
    {
        $repair = Repair::findOrFail($id);
        $repair->delete(); // Ensure this line exists and is executed

        return redirect()->route('admin.dashboard')->with('success', 'Report deleted successfully!');
    }

    public function show($id)
    {
        $repair = Repair::find($id);

        if (!$repair) {
            return redirect()->route('admin.dashboard')->with('error', 'That report no longer exists.');
        }

        return view('admin.repair.show', compact('repair'));
    }
}