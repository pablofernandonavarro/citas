<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $roles= Role::all();
        return view('admin.users.create', compact('roles'));  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        $data=$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'dni' => 'required|string|max:20|unique:users',
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            
        ]);
        $user= User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
            'dni' => $data['dni'],
            'address' => $data['address'],
        ]);
        $user->roles()->attach($data['role_id']);
        
        // Si el rol seleccionado es Paciente, redirigir a crear paciente
        $role = Role::find($data['role_id']);
        if ($role && strtolower($role->name) === 'paciente') {
            session()->flash('swal',
              [
                'icon' => 'info',
                'title' => 'Usuario creado',
                'text' => 'Ahora completa los datos del paciente.',
              ]
            );
            return redirect()->route('admin.patients.create')->with('user_id', $user->id);
        }
        
        session()->flash('swal',
          [
            'icon' => 'success',
            'title' => 'Usuario creado exitosamente',
            'text' => 'El usuario se ha creado correctamente en el sistema.',
          ]
        );
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'dni' => 'required|string|max:20|unique:users,dni,' . $user->id,
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->dni = $data['dni'];
        $user->address = $data['address'];
        $user->save();

        $user->roles()->sync($data['role_id']);

        session()->flash('swal',
          [
            'icon' => 'success',
            'title' => 'Usuario actualizado exitosamente',
            'text' => 'El usuario se ha actualizado correctamente en el sistema.',
          ]
        );
        return redirect()->route('admin.users.index', compact('user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('swal',
          [
            'icon' => 'success',
            'title' => 'Usuario fue eliminado exitosamente',
            'text' => 'El usuario se ha eliminado correctamente del sistema.',
          ]
        );
            return redirect()->route('admin.users.index');
    }
}
