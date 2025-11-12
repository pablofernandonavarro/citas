<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DoctorUnavailability;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorUnavailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unavailabilities = DoctorUnavailability::with('doctor.user')
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        return view('admin.unavailability.index', compact('unavailabilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = Doctor::with('user')->get();
        return view('admin.unavailability.create', compact('doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
            'all_day' => 'boolean',
            'start_time' => 'nullable|required_if:all_day,false',
            'end_time' => 'nullable|required_if:all_day,false',
        ]);

        DoctorUnavailability::create($validated);

        return redirect()->route('admin.unavailability.index')
            ->with('success', 'Período de bloqueo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DoctorUnavailability $unavailability)
    {
        $doctors = Doctor::with('user')->get();
        return view('admin.unavailability.edit', compact('unavailability', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DoctorUnavailability $unavailability)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
            'all_day' => 'boolean',
            'start_time' => 'nullable|required_if:all_day,false',
            'end_time' => 'nullable|required_if:all_day,false',
        ]);

        $unavailability->update($validated);

        return redirect()->route('admin.unavailability.index')
            ->with('success', 'Período de bloqueo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoctorUnavailability $unavailability)
    {
        $unavailability->delete();

        return redirect()->route('admin.unavailability.index')
            ->with('success', 'Período de bloqueo eliminado exitosamente.');
    }
}
