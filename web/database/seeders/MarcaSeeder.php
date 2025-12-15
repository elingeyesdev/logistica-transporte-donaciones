<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas = [
            'Ford',
            'Honda',
            'Suzuki',
            'GAC',
            'BYD',
            'Mercedes',
            'BMW',
            'Toyota',
            'Gelly',
            'Chevrolet',
            'Nissan',
            'Kia',
            'Mazda',
        ];

        foreach ($marcas as $nombre) {
            Marca::firstOrCreate([
                'nombre_marca' => $nombre,
            ]);
        }
    }
}
