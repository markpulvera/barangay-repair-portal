<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Repair;

class RepairController extends Controller


{
    public function index()
    {
        return view('repair.index'); 
    }

    public function create() {
        return view('repair.create');
    }



    public function store(Request $request)
    {

        $request->validate([
            'Reporter_name'      => 'required',
            'Location_landmark'  => 'required',
            'Contact_number'     => 'required',
            'Facility_type'      => 'required',
            'Damage_description' => 'required',
            'Date_observed'      => 'required',

        
        $data = [
            'Reporter_name'      => $request->input('Reporter_name'),
            'Location_landmark'  => $request->input('Location_landmark'),
            'Contact_number'     => $request->input('Contact_number'),
            'Facility_type'      => $request->input('Facility_type'),
            'Damage_description' => $request->input('Damage_description'),
            'Date_observed'      => $request->input('Date_observed'),
            'image'              => null,
        ]
    ]);


$file = $request->file('image') ?? $request->file('image');

if ($file) {
    
    $data['image'] = $file->store('repairs', 'public');
} 

        
        Repair::create($data);


        return redirect()->route('repair.view')->with('success', 'Report saved successfully!');
    }


    public function view() {
        $repairs = Repair::all();
        return view('repair.view', compact('repairs'));
    }

    public function edit($id) {
        $repair = Repair::findOrFail($id);
        return view('repair.update', compact('repair'));
    }




    public function update(Request $request, $id)
{
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

        if ($repair->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($repair->image)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($repair->image);
        }
        

        $data['image'] = $request->file('image')->store('repairs', 'public');
    }

    $repair->update($data);

    return redirect()->route('repair.view')->with('success', 'Report updated successfully!');
}




  
    public function destroy($id){
        $repair = Repair::findOrFail($id);
        $repair->delete();

        return redirect()->route('repair.view')->with('success', 'Report deleted successfully!');
    } 
    
}
