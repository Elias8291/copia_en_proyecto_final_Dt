<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Ejecuta los seeds de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Creando roles...');
        
        // === SUPER ADMINISTRADOR ===
        Role::firstOrCreate([
            'name' => 'Super Administrador',
            'guard_name' => 'web'
        ]);

        // === ADMINISTRADOR ===
        Role::firstOrCreate([
            'name' => 'Administrador',
            'guard_name' => 'web'
        ]);

        // === REVISOR DE TRÁMITES ===
        Role::firstOrCreate([
            'name' => 'Revisor de Trámites',
            'guard_name' => 'web'
        ]);

        // === GESTOR DE PROVEEDORES ===
        Role::firstOrCreate([
            'name' => 'Gestor de Proveedores',
            'guard_name' => 'web'
        ]);

        // === SOLICITANTE ===
        Role::firstOrCreate([
            'name' => 'Solicitante',
            'guard_name' => 'web'
        ]);

        // === PROVEEDOR ===
        Role::firstOrCreate([
            'name' => 'Proveedor',
            'guard_name' => 'web'
        ]);

        // === OPERADOR ===
        Role::firstOrCreate([
            'name' => 'Operador',
            'guard_name' => 'web'
        ]);

        $this->command->info('✅ Roles creados exitosamente');
    }

    /**
     * Asigna permisos de manera segura, ignorando los que no existen
     */
    private function asignarPermisosSeguro($role, $permisos)
    {
        $permisosExistentes = [];
        
        foreach ($permisos as $permiso) {
            if (Permission::where('name', $permiso)->where('guard_name', 'web')->exists()) {
                $permisosExistentes[] = $permiso;
            } else {
                $this->command->warn("⚠️  Permiso '{$permiso}' no existe, se omite para el rol '{$role->name}'");
            }
        }

        if (!empty($permisosExistentes)) {
            $role->syncPermissions($permisosExistentes);
        }
    }
}