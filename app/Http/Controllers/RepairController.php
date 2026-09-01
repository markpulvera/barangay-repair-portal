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
        return view('repair.index'); 
    }

    // CREATE: Form view
    public function create() 
    {
        return view('repair.create');
    }

    // STORE: Open to all logged-in users to submit reports
    public function store(Request $request)
    {
        $request->validate([
            'Reporter_name'      => 'required',
            'Location_landmark'  => 'required',
            'Contact_number'     => 'required',
            'Facility_type'      => 'required',
            'Damage_description' => 'required',
            'Date_observed'      => 'required',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = [
            'Reporter_name'      => $request->input('Reporter_name'),
            'Location_landmark'  => $request->input('Location_landmark'),
            'Contact_number'     => $request->input('Contact_number'),
            'Facility_type'      => $request->input('Facility_type'),
            'Damage_description' => $request->input('Damage_description'),
            'Date_observed'      => $request->input('Date_observed'),
            'image'              => null,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('repairs', 'public');
        }

        Repair::create($data);

        return redirect()->route('repair.view')->with('success', 'Report saved successfully!');
    }

    // VIEW: Accessible by all users to view records
    public function view() 
    {
        $repairs = Repair::all();
        return view('repair.view', compact('repairs'));
    }

    // EDIT: Restricted to Admin
    public function edit($id) 
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can edit records.');
        }

        $repair = Repair::findOrFail($id);
        return view('repair.update', compact('repair'));
    }

    // UPDATE: Restricted to Admin
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can update records.');
        }

        $repair = Repair::findOrFail($id);

        $data = $request->validate([
            'Reporter_name'      => 'required',
            'Location_landmark'  => 'required',
            'Contact_number'     => 'required|numeric',
            'Facility_type'      => 'required',
            'Damage_description' => 'required',
            'Date_observed'      => 'required',
            'image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($repair->image && Storage::disk('public')->exists($repair->image)) {
                Storage::disk('public')->delete($repair->image);
            }

            $data['image'] = $request->file('image')->store('repairs', 'public');
        }

        $repair->update($data);

        return redirect()->route('repair.view')->with('success', 'Report updated successfully!');
    }

    // DELETE: Restricted to Admin
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can delete records.');
        }

        $repair = Repair::findOrFail($id);

        if ($repair->image && Storage::disk('public')->exists($repair->image)) {
            Storage::disk('public')->delete($repair->image);
        }

        $repair->delete();

        return redirect()->route('repair.view')->with('success', 'Report deleted successfully!');
    } 
}