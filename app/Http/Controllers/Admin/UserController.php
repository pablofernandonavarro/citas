<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  
        Gate::authorize('read_user');
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()

    {  
          Gate::authorize('create_user');
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
          Gate::authorize('create_user');
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'dni' => 'required|string|max:20|unique:users',
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',

        ]);
        $user = User::create($data);

        $user->roles()->attach($data['role_id']);

        session()->flash('swal',
            [
                'icon' => 'info',
                'title' => 'Usuario creado',
                'text' => 'Ahora completa los datos del paciente.',
            ]
        );
       if($user->hasRole('Paciente')){
           $patient = $user->patient()->create([]);
           return redirect()->route('admin.patients.edit', $patient);
        }
        if($user->hasRole('Doctor')){
            $doctor = $user->doctor()->create([]);
            return redirect()->route('admin.doctors.edit', $doctor);
         }
     

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {  
          Gate::authorize('read_user');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {   
          Gate::authorize('update_user');
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {   
         Gate::authorize('update_user');
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'dni' => 'required|string|max:20|unique:users,dni,'.$user->id,
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        if (! empty($data['password'])) {
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
         Gate::authorize('delete_user');
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
