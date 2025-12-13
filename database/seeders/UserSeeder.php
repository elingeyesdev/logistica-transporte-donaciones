<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );

        $user = User::updateOrCreate(
            ['correo_electronico' => 'anagvillafanis@gmail.com'],
            [
                'nombre' => 'Ana Gracia',
                'apellido' => 'Villafani',
                'password' => Hash::make('anita123'),
                'ci' => '7567717',
                'telefono' => '77312304',
                'administrador' => true,
                'activo' => true,
                'email_verified_at' => now(),
            ]
        );

        if (! $user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }
    }
}
