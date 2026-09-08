<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RepairApiController extends Controller
{
    // GET /api/repairs - Get all repair records as JSON
    public function index()
    {
        $repairs = Repair::orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $repairs
        ], 200);
    }

    // POST /api/repairs - Create a new repair report via API
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Reporter_name'      => 'required|string',
            'Location_landmark'  => 'required|string',
            'Risk_level'         => 'required|string',
            'Facility_type'      => 'required|string',
            'Damage_description' => 'required|string',
            'Date_observed'      => 'required|date',
            'Image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('Image')) {
            $imagePath = $request->file('Image')->store('repairs', 'public');
        }

        $repair = Repair::create([
            'user_id'            => auth('sanctum')->id() ?? $request->user_id,
            'Reporter_name'      => $validated['Reporter_name'],
            'Location_landmark'  => $validated['Location_landmark'],
            'Risk_level'         => $validated['Risk_level'],
            'Facility_type'      => $validated['Facility_type'],
            'Damage_description' => $validated['Damage_description'],
            'Date_observed'      => $validated['Date_observed'],
            'Image'              => $imagePath,
            'Status'             => 'Pending',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Repair report created successfully.',
            'data'    => $repair
        ], 201);
    }

    // GET /api/repairs/{id} - Get a single repair record
    public function show($id)
    {
        $repair = Repair::find($id);

        if (!$repair) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Repair record not found.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $repair
        ], 200);
    }

    // PUT/PATCH /api/repairs/{id} - Update a repair record
    public function update(Request $request, $id)
    {
        $repair = Repair::find($id);

        if (!$repair) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Repair record not found.'
            ], 404);
        }

        $validated = $request->validate([
            'Reporter_name'      => 'sometimes|required|string',
            'Location_landmark'  => 'sometimes|required|string',
            'Risk_level'         => 'sometimes|required|string',
            'Facility_type'      => 'sometimes|required|string',
            'Damage_description' => 'sometimes|required|string',
            'Date_observed'      => 'sometimes|required|date',
            'Status'             => 'sometimes|required|in:Pending,In Progress,Resolved',
            'Assigned_department'=> 'nullable|string',
            'Image'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('Image')) {
            if ($repair->Image && Storage::disk('public')->exists($repair->Image)) {
                Storage::disk('public')->delete($repair->Image);
            }
            $validated['Image'] = $request->file('Image')->store('repairs', 'public');
        }

        $repair->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Repair record updated successfully.',
            'data'    => $repair
        ], 200);
    }

    // DELETE /api/repairs/{id} - Delete a repair record
    public function destroy($id)
    {
        $repair = Repair::find($id);

        if (!$repair) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Repair record not found.'
            ], 404);
        }

        if ($repair->Image && Storage::disk('public')->exists($repair->Image)) {
            Storage::disk('public')->delete($repair->Image);
        }

        $repair->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Repair record deleted successfully.'
        ], 200);
    }
}