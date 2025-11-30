<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            'En Camino',
            'Pendiente',
            'Entregado',
        ];

        foreach ($estados as $nombre) {
            Estado::firstOrCreate([
                'nombre_estado' => $nombre,
            ]);
        }
    }
}
