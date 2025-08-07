<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::query()->delete();

        $userPermissions = [
            'usuarios.ver' => 'Ver usuarios',
            'usuarios.crear' => 'Crear usuarios',
            'usuarios.editar' => 'Editar usuarios',
            'usuarios.eliminar' => 'Eliminar usuarios',
            'usuarios.asignar_roles' => 'Asignar roles a usuarios',
            'usuarios.ver_propio' => 'Ver propio perfil',
            'usuarios.editar_propio' => 'Editar propio perfil',
            'usuarios.cambiar_password' => 'Cambiar contraseñas',
        ];

        $rolePermissions = [
            'roles.ver' => 'Ver roles',
            'roles.crear' => 'Crear roles',
            'roles.editar' => 'Editar roles',
            'roles.eliminar' => 'Eliminar roles',
            'roles.asignar_permisos' => 'Asignar permisos a roles',
            'roles.ver_permisos' => 'Ver permisos de roles',
        ];

        $allPermissions = array_merge($userPermissions, $rolePermissions);

        foreach ($allPermissions as $permission => $description) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('✅ Permisos creados: ' . count($allPermissions) . ' permisos');
    }
} 