<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialWork;
use Illuminate\Support\Facades\Gate;

class SocialWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('access_socialwork');
        return view('admin.socialworks.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.socialworks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:social_works',
        ]);

        SocialWork::create($validated);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Obra Social creada exitosamente',
            'text' => 'La obra social se ha creado correctamente en el sistema.',
        ]);

        return redirect()->route('admin.socialworks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('admin.socialworks.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $socialwork = SocialWork::findOrFail($id);
        return view('admin.socialworks.edit', compact('socialwork'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $socialwork = SocialWork::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:social_works,name,' . $socialwork->id,
        ]);

        $socialwork->update($validated);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Obra Social actualizada exitosamente',
            'text' => 'La obra social se ha actualizado correctamente en el sistema.',
        ]);

        return redirect()->route('admin.socialworks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $socialwork = SocialWork::findOrFail($id);
        $socialwork->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Obra Social eliminada exitosamente',
            'text' => 'La obra social se ha eliminado correctamente del sistema.',
        ]);

        return redirect()->route('admin.socialworks.index');
    }
}
