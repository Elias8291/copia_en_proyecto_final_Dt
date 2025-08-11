<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateProveedoresPermissions extends Command
{
    protected $signature = 'permissions:proveedores';
    protected $description = 'Crear permisos básicos para proveedores (CRUD)';

    public function handle()
    {
        $this->info('🔧 Creando permisos básicos para proveedores...');

        try {
            // Crear permisos
            $permissions = [
                'proveedores.ver' => 'Ver proveedores',
                'proveedores.crear' => 'Crear proveedores',
                'proveedores.editar' => 'Editar proveedores',
                'proveedores.eliminar' => 'Eliminar proveedores',
            ];

            foreach ($permissions as $permission => $description) {
                Permission::firstOrCreate(['name' => $permission], [
                    'name' => $permission,
                    'guard_name' => 'web',
                    'description' => $description
                ]);
            }

            // Asignar permisos a roles
            $roles = [
                'Super Administrador' => ['proveedores.ver', 'proveedores.crear', 'proveedores.editar', 'proveedores.eliminar'],
                'Administrador' => ['proveedores.ver', 'proveedores.crear', 'proveedores.editar', 'proveedores.eliminar'],
                'Revisor Digital' => ['proveedores.ver'],
                'Revisor Presencial' => ['proveedores.ver'],
                'Revisor Domiciliario' => ['proveedores.ver'],
                'Solicitante' => ['proveedores.ver'],
            ];

            foreach ($roles as $roleName => $rolePermissions) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    foreach ($rolePermissions as $permission) {
                        $role->givePermissionTo($permission);
                    }
                    $this->line("   ✅ Permisos asignados al rol: {$roleName}");
                } else {
                    $this->warn("   ⚠️  Rol '{$roleName}' no encontrado");
                }
            }

            $this->info('✅ Permisos de proveedores creados exitosamente');
            $this->info('📋 Permisos creados:');
            $this->line('   • proveedores.ver - Ver proveedores');
            $this->line('   • proveedores.crear - Crear proveedores');
            $this->line('   • proveedores.editar - Editar proveedores');
            $this->line('   • proveedores.eliminar - Eliminar proveedores');

        } catch (\Exception $e) {
            $this->error('❌ Error al crear permisos de proveedores: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
