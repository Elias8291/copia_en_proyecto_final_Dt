<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            RolesAndPermissionsSeeder::class,
            ArchivosPermissionsSeeder::class,
            UserSeeder::class,
            PaisSeeder::class,
            EstadosSeeder::class,
            MunicipioSeeder::class,
            LocalidadSeeder::class,
            TiposAsentamientoSeeder::class,
            // AsentamientosSeeder::class, // Temporarily commented out due to large dataset
            SectoresSeeder::class,
           ActividadesEconomicasSeeder::class,
           CatalogoArchivoSeeder::class,
        ]);
    }
}
