<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CabinetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('read_cabinet');
        $cabinets = Cabinet::with('doctors.user')->get();
        return view('admin.cabinets.index', compact('cabinets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create_cabinet');
        return view('admin.cabinets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create_cabinet');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        Cabinet::create($validated);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Gabinete creado exitosamente',
        ]);
        
        return redirect()->route('admin.cabinets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cabinet $cabinet)
    {
        Gate::authorize('read_cabinet');
        $cabinet->load('doctors.user', 'appointments');
        return view('admin.cabinets.show', compact('cabinet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cabinet $cabinet)
    {
        Gate::authorize('update_cabinet');
        return view('admin.cabinets.edit', compact('cabinet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cabinet $cabinet)
    {
        Gate::authorize('update_cabinet');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $cabinet->update($validated);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Gabinete actualizado exitosamente',
        ]);
        
        return redirect()->route('admin.cabinets.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cabinet $cabinet)
    {
        Gate::authorize('delete_cabinet');
        
        $cabinet->delete();
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Gabinete eliminado exitosamente',
        ]);
        
        return redirect()->route('admin.cabinets.index');
    }
    
    /**
     * Asignar gabinetes a un doctor
     */
    public function assignToDoctor(Request $request, Doctor $doctor)
    {
        Gate::authorize('update_cabinet');
        
        $validated = $request->validate([
            'cabinet_ids' => 'required|array',
            'cabinet_ids.*' => 'exists:cabinets,id',
        ]);
        
        $doctor->cabinets()->sync($validated['cabinet_ids']);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Gabinetes asignados exitosamente al doctor',
        ]);
        
        return back();
    }
}
