<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentEnum;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('read_appointment');
        $appointments = Appointment::all();
        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create_appointment');
        return view('admin.appointments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create_appointment');
        $appointment = Appointment::create($request->all());
        return redirect()->route('appointments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        Gate::authorize('read_appointment');
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        Gate::authorize('update_appointment');



        return view('admin.appointments.edit', compact('appointment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update($request->all());
        return redirect()->route('appointments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        Gate::authorize('delete_appointment');
        $appointment->status = AppointmentEnum::CANCELED;
        $appointment->save();
        session()->flash('swal',[
              'icon' => 'success',
              'title' => '¡Éxito!',
              'text' => 'el Turno fue Cancelado',
        ]);

        return view('admin.appointments.edit', compact('appointment'));
    }

    /**
     * Liberar un turno cancelado para que esté disponible
     */
    public function release(Appointment $appointment)
    {
        Gate::authorize('update_appointment');
        
        $appointment->update([
            'status' => AppointmentEnum::AVAILABLE,
            'patient_id' => null,
            'reason' => null,
        ]);

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'El turno fue liberado y está disponible para otros pacientes',
        ]);

        return redirect()->route('admin.appointments.available');
    }

    /**
     * Mostrar lista de turnos disponibles (para admin)
     */
    public function available()
    {
        Gate::authorize('read_appointment');
        
        $appointments = Appointment::where('status', AppointmentEnum::AVAILABLE)
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->with(['doctor.user', 'doctor.speciality'])
            ->get();

        $patients = Patient::with('user')->get();

        return view('admin.appointments.available-simple', compact('appointments', 'patients'));
    }

    /**
     * Asignar un turno disponible a un paciente (desde admin)
     */
    public function assign(Request $request, Appointment $appointment)
    {
        Gate::authorize('update_appointment');

        $request->validate([
            'patient_id' => 'required|exists:patients,id'
        ]);

        $appointment->update([
            'patient_id' => $request->patient_id,
            'status' => AppointmentEnum::SCHEDULED,
        ]);

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'El turno fue asignado exitosamente',
        ]);

        return redirect()->route('admin.appointments.available');
    }

    public function consultation(Appointment $appointment)
    {
        Gate::authorize('update_appointment');
        return view('admin.appointments.consultation', compact('appointment'));
    }
}
