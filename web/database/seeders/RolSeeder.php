<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $roles = [
            'Voluntario',
            'Administrador',
            'Voluntario-Conductor',
        ];

        foreach ($roles as $titulo) {
            Rol::firstOrCreate([
                'titulo_rol' => $titulo,
            ]);
        }
    }
}
