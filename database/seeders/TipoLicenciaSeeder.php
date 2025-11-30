<?php

namespace Database\Seeders;

use App\Models\TipoLicencia;
use Illuminate\Database\Seeder;

class TipoLicenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $licencias = ['P', 'A', 'B', 'C'];

        foreach ($licencias as $codigo) {
            TipoLicencia::firstOrCreate([
                'licencia' => $codigo,
            ]);
        }
    }
}
