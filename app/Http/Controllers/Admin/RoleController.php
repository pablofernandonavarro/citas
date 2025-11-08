<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  
       Gate::authorize('read_role');
        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
       Gate::authorize('create_role');
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

       Gate::authorize('create_role');
       $request->validate([
        'name' => 'required|unique:roles,name',
       ]);
         Role::create(
            ['name' => $request->name]
         );
         session()->flash('swal',
          [
            'icon' => 'success',
            'title' => 'Rol fue creado exitosamente',
            'text' => 'El rol se ha creado correctamente en el sistema.',
          ]
        );
            return redirect()->route('admin.roles.index')->with('info', 'Rol creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {  
       Gate::authorize('read_role');
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)

    {  
       Gate::authorize('update_role');
        if($role->id <= 4){
            session()->flash('swal',
            [
              'icon' => 'error',
              'title' => 'Acción no permitida',
              'text' => 'Los roles del sistema no pueden ser editados.',
            ]
          );
              return redirect()->route('admin.roles.index');
        }
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {  
       Gate::authorize('update_role');
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->update(
            ['name' => $request->name]
         );
         session()->flash('swal',
          [
            'icon' => 'success',
            'title' => 'Rol fue actualizado exitosamente',
            'text' => 'El rol se ha actualizado correctamente en el sistema.',
          ]
        );
            return redirect()->route('admin.roles.index');

        
    }


    public function destroy(Role $role)
    {   
       Gate::authorize('delete_role');
        if($role->id <= 4){
            session()->flash('swal',
            [
              'icon' => 'error',
              'title' => 'Acción no permitida',
              'text' => 'Los roles del sistema no pueden ser eliminados.',
            ]
          );
              return redirect()->route('admin.roles.index');
        }
       
        $role->delete();
        session()->flash('swal',
          [
            'icon' => 'success',
            'title' => 'Rol fue eliminado exitosamente',
            'text' => 'El rol se ha eliminado correctamente del sistema.',
          ]
        );
            return redirect()->route('admin.roles.index');
        
    }
}
