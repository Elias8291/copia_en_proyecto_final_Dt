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
            // ============================================================================
            // SEEDERS DE PERMISOS Y ROLES
            // ============================================================================
            PermissionSeeder::class,
            RoleSeeder::class,
            RolePermissionSeeder::class,
            
            // ============================================================================
            // SEEDERS DE USUARIOS
            // ============================================================================
            UserSeeder::class,
            
            // ============================================================================
            // SEEDERS DE DATOS GEOGRÁFICOS
            // ============================================================================
            PaisSeeder::class,
            EstadosSeeder::class,
            MunicipioSeeder::class,
            LocalidadSeeder::class,
            TiposAsentamientoSeeder::class,
            AsentamientosSeeder::class,
            
            // ============================================================================
            // SEEDERS DE DATOS DEL SISTEMA
            // ============================================================================
            SectoresSeeder::class,
            ActividadesSeeder::class,
            CatalogoArchivoSeeder::class,
            DiasInhabilesSeeder::class,
        ]);
    }
}
