<?php

namespace Database\Seeders;

use App\Models\TipoEmergencia;
use Illuminate\Database\Seeder;

class TipoEmergenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emergencias = [
            ['emergencia' => 'Incendio',           'prioridad' => 3],
            ['emergencia' => 'Inundacion',         'prioridad' => 3],
            ['emergencia' => 'Epidemia',           'prioridad' => 2],
            ['emergencia' => 'Escasez Alimentaria','prioridad' => 4],
            ['emergencia' => 'Otro',               'prioridad' => 2],
        ];

        foreach ($emergencias as $data) {
            TipoEmergencia::firstOrCreate([
                'emergencia' => $data['emergencia'],
            ], [
                'prioridad'  => $data['prioridad'],
            ]);
        }
    }
}
