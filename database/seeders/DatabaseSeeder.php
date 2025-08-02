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
            PermissionSeeder::class,
            RoleSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            PaisSeeder::class,
            EstadosSeeder::class,
            MunicipioSeeder::class,
            LocalidadSeeder::class,
            TiposAsentamientoSeeder::class,
            AsentamientosSeeder::class,
            SectoresSeeder::class,
            ActividadesSeeder::class,
            CatalogoArchivoSeeder::class,
            DiasInhabilesSeeder::class,
            
        ]);
    }
}
