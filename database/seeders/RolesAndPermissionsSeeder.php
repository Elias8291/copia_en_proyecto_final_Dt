<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Permisos de usuarios
            'view users',
            'create users',
            'edit users',
            'delete users',
            'restore users',
            'manage users',
            
            // Permisos de roles
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'manage roles',
            
            // Permisos de trámites
            'view tramites',
            'create tramites',
            'edit tramites',
            'delete tramites',
            'review tramites',
            'manage tramites',
            
            // Permisos de archivos
            'view archivos',
            'create archivos',
            'edit archivos',
            'delete archivos',
            'manage archivos',
            
            // Permisos de notificaciones
            'view notifications',
            'manage notifications',
            
            // Permisos de citas
            'view citas',
            'create citas',
            'edit citas',
            'delete citas',
            'manage citas',
            
            // Permisos de días inhábiles
            'view dias_inhabiles',
            'create dias_inhabiles',
            'edit dias_inhabiles',
            'delete dias_inhabiles',
            'manage dias_inhabiles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles
        $roles = [
            'admin' => [
                'view users', 'create users', 'edit users', 'delete users', 'restore users', 'manage users',
                'view roles', 'create roles', 'edit roles', 'delete roles', 'manage roles',
                'view tramites', 'create tramites', 'edit tramites', 'delete tramites', 'review tramites', 'manage tramites',
                'view archivos', 'create archivos', 'edit archivos', 'delete archivos', 'manage archivos',
                'view notifications', 'manage notifications',
                'view citas', 'create citas', 'edit citas', 'delete citas', 'manage citas',
                'view dias_inhabiles', 'create dias_inhabiles', 'edit dias_inhabiles', 'delete dias_inhabiles', 'manage dias_inhabiles',
            ],
            'moderator' => [
                'view users',
                'view tramites', 'review tramites',
                'view archivos',
                'view notifications',
                'view citas', 'create citas', 'edit citas',
                'view dias_inhabiles',
            ],
            'user' => [
                'view tramites', 'create tramites',
                'view notifications',
                'view citas', 'create citas',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }

        $this->command->info('Roles y permisos creados exitosamente.');
    }
} 