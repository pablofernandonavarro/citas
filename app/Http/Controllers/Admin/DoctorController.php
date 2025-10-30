<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Speciality;

class DoctorController extends Controller
{
   
    public function index()
    {
        return view('admin.doctors.index');
    }

  
    public function store(Request $request)
    {
        
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {  
        $specialities = Speciality::all();

        return view('admin.doctors.edit', compact('doctor', 'specialities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'speciality_id' => 'required|exists:specialities,id',
            'medical_license_number' => 'nullable|string|max:255|unique:doctors,medical_license_number,' . $doctor->id,
            'biography' => 'nullable|string',
            'active' =>  ['required', 'in:1,0'],
        ]);
        
        $doctor->update($data);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Datos del doctor actualizados con éxito.',
            
        ]);

        return redirect()->route('admin.doctors.edit', $doctor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        //
    }
}
