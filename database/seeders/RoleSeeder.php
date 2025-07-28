<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar roles existentes de forma segura
        $this->command->info('🧹 Limpiando roles existentes...');
        
        // Eliminar roles de forma segura
        $roles = Role::all();
        foreach ($roles as $role) {
            $role->delete();
        }

        // ============================================================================
        // ROLES DEL SISTEMA
        // ============================================================================

        // 1. Super Administrador
        $superAdmin = Role::create([
            'name' => 'Super Administrador',
            'guard_name' => 'web'
        ]);

        // 2. Administrador
        $admin = Role::create([
            'name' => 'Administrador',
            'guard_name' => 'web'
        ]);

        // 3. Revisor
        $revisor = Role::create([
            'name' => 'Revisor',
            'guard_name' => 'web'
        ]);

        // 4. Recepcionista
        $recepcionista = Role::create([
            'name' => 'Recepcionista',
            'guard_name' => 'web'
        ]);

        // 5. Proveedor
        $proveedor = Role::create([
            'name' => 'Proveedor',
            'guard_name' => 'web'
        ]);

        // 6. Solicitante
        $solicitante = Role::create([
            'name' => 'Solicitante',
            'guard_name' => 'web'
        ]);

        // 7. Consultor
        $consultor = Role::create([
            'name' => 'Consultor',
            'guard_name' => 'web'
        ]);

        $this->command->info('✅ Roles creados exitosamente: 7 roles');
        
        // Mostrar roles creados
        $roles = Role::all();
        foreach ($roles as $role) {
            $this->command->info("   - {$role->name}");
        }
    }
}
