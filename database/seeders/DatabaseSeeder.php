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
            UserSeeder::class,
            PaisSeeder::class,
            EstadosSeeder::class,
            MunicipioSeeder::class,
            LocalidadSeeder::class,
            TiposAsentamientoSeeder::class,
            AsentamientosSeeder::class,
            SectoresSeeder::class,
           ActividadesEconomicasSeeder::class,
           CatalogoSeeder::class,
        ]);
    }
}
