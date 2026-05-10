<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(SocialWorkSeeder::class);
        $this->call(SpecialitySeeder::class);

        CompanySetting::create([
            'business_name' => tenant('business_name'),
        ]);

        $adminEmail = tenant('admin_email') ?? 'admin@example.com';
        $adminName = tenant('admin_name') ?? 'Admin';

        $admin = User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => Hash::make(\Illuminate\Support\Str::random(16)),
            'dni' => '00000000',
            'phone' => '',
            'address' => '',
        ]);

        $admin->assignRole('Admin');

        // Send password reset email so they can set their own password
        \Illuminate\Support\Facades\Password::sendResetLink(['email' => $adminEmail]);
    }
}
