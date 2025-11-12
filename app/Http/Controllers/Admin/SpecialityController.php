<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Speciality;
use Illuminate\Support\Facades\Gate;

class SpecialityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('access_speciality');
        return view('admin.specialities.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.specialities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specialities',
        ]);

        Speciality::create($validated);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Especialidad creada exitosamente',
            'text' => 'La especialidad se ha creado correctamente en el sistema.',
        ]);

        return redirect()->route('admin.specialities.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('admin.specialities.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $speciality = Speciality::findOrFail($id);
        return view('admin.specialities.edit', compact('speciality'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $speciality = Speciality::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specialities,name,' . $speciality->id,
        ]);

        $speciality->update($validated);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Especialidad actualizada exitosamente',
            'text' => 'La especialidad se ha actualizado correctamente en el sistema.',
        ]);

        return redirect()->route('admin.specialities.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $speciality = Speciality::findOrFail($id);
        $speciality->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Especialidad eliminada exitosamente',
            'text' => 'La especialidad se ha eliminado correctamente del sistema.',
        ]);

        return redirect()->route('admin.specialities.index');
    }
}
