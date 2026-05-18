<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'dni' => ['required', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'dni' => $input['dni'],
            'phone' => $input['phone'],
            'address' => $input['address'],
            'password' => Hash::make($input['password']),
        ]);

        // Asignar rol de Paciente por defecto
        $pacienteRole = Role::where('name', 'Paciente')->first();
        if ($pacienteRole) {
            $user->roles()->attach($pacienteRole->id);

            // Crear registro de paciente
            Patient::create([
                'user_id' => $user->id,
            ]);
        }

        return $user;
    }
}
