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
            UserSeeder::class,
            RolesAndPermissionsSeeder::class,
            ArchivosPermissionsSeeder::class,
            PaisSeeder::class,
            EstadosSeeder::class,
            MunicipioSeeder::class,
            LocalidadSeeder::class,
            TiposAsentamientoSeeder::class,
            AsentamientosSeeder::class,
            SectoresSeeder::class,
           ActividadesEconomicasSeeder::class,
           CatalogoArchivoSeeder::class,
        ]);
    }
}
