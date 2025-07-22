<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
            PaisSeeder::class,
            EstadosSeeder::class,
            MunicipioSeeder::class,
            LocalidadSeeder::class,
            TiposAsentamientoSeeder::class,
            AsentamientosSeeder::class,
            SectoresSeeder::class,
            ActividadesSeeder::class,
            CatalogoArchivoSeeder::class,
        ]);
    }
}
