<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MarcaSeeder::class,
            EstadoSeeder::class,
            RolSeeder::class,
            TipoLicenciaSeeder::class,
            TipoVehiculoSeeder::class,
            TipoEmergenciaSeeder::class,
        ]);
    }
}
