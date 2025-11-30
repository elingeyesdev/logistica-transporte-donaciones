<?php

namespace Database\Seeders;

use App\Models\TipoVehiculo;
use Illuminate\Database\Seeder;

class TipoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            'Minivan',
            'SUV',
            'Camioneta',
            'Camioneta Doble-Cabina',
            'Hatchback',
            'Convertible',
        ];

        foreach ($tipos as $nombre) {
            TipoVehiculo::firstOrCreate([
                'nombre_tipo_vehiculo' => $nombre,
            ]);
        }
    }
}
