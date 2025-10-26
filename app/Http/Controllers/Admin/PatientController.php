<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::all();
        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user_id = session('user_id');
        $socialWorks = \App\Models\SocialWork::all();
        return view('admin.patients.create', compact('user_id', 'socialWorks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'social_work_id' => 'nullable|exists:social_works,id',
            'affiliate_number' => 'nullable|string|max:100',
            'allergies' => 'nullable|string',
            'medical_record_number' => 'nullable|string|unique:patients',
            'chronic_conditions' => 'nullable|string',
            'surgeries_history' => 'nullable|string',
            'family_history' => 'nullable|string',
            'genetic_conditions' => 'nullable|string',
            'other_conditions' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        Patient::create($validated);

        session()->flash('swal',
          [
            'icon' => 'success',
            'title' => 'Paciente creado exitosamente',
            'text' => 'El paciente se ha creado correctamente en el sistema.',
          ]
        );

        return redirect()->route('admin.patients.index');   
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $socialWorks = \App\Models\SocialWork::all();
        return view('admin.patients.edit', compact('patient', 'socialWorks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'social_work_id' => 'nullable|exists:social_works,id',
            'affiliate_number' => 'nullable|string|max:100',
            'allergies' => 'nullable|string',
            'medical_record_number' => 'nullable|string|unique:patients,medical_record_number,' . $patient->id,
            'chronic_conditions' => 'nullable|string',
            'surgeries_history' => 'nullable|string',
            'family_history' => 'nullable|string',
            'genetic_conditions' => 'nullable|string',
            'other_conditions' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        $patient->update($validated);

        session()->flash('swal',
          [
            'icon' => 'success',
            'title' => 'Paciente actualizado exitosamente',
            'text' => 'El paciente se ha actualizado correctamente en el sistema.',
          ]
        );

        return redirect()->route('admin.patients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        session()->flash('swal',
          [
            'icon' => 'success',
            'title' => 'Paciente eliminado exitosamente',
            'text' => 'El paciente se ha eliminado correctamente del sistema.',
          ]
        );

        return redirect()->route('admin.patients.index');
    }
}
