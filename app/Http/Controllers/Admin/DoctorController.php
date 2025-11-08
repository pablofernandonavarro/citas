<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Speciality;
use Illuminate\Support\Facades\Gate;

class DoctorController extends Controller
{
   
    public function index()
    {   
        Gate::authorize('read_doctor');
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
        Gate::authorize('update_doctor');
        $specialities = Speciality::all();

        return view('admin.doctors.edit', compact('doctor', 'specialities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {   
        Gate::authorize('update_doctor');
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

  public function schedules(Doctor $doctor)
    {
        
        return view('admin.doctors.schedules', compact('doctor'));
    }
}
