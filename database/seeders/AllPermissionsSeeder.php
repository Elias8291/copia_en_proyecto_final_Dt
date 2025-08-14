<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AllPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Primero eliminar todos los permisos existentes
        Permission::query()->delete();
        
        // También eliminar todos los roles existentes para empezar limpio
        Role::query()->delete();

        $allPermissions = [
            'usuarios.ver' => 'Ver usuarios',
            'usuarios.crear' => 'Crear usuarios',
            'usuarios.editar' => 'Editar usuarios',
            'usuarios.eliminar' => 'Eliminar usuarios',
            'usuarios.asignar_roles' => 'Asignar roles a usuarios',
            'usuarios.ver_propio' => 'Ver propio perfil',
            'usuarios.editar_propio' => 'Editar propio perfil',
            'usuarios.cambiar_password' => 'Cambiar contraseñas',

            'roles.ver' => 'Ver roles',
            'roles.crear' => 'Crear roles',
            'roles.editar' => 'Editar roles',
            'roles.eliminar' => 'Eliminar roles',
            'roles.asignar_permisos' => 'Asignar permisos a roles',

            'archivos.ver' => 'Ver archivos',
            'archivos.crear' => 'Crear archivos',
            'archivos.editar' => 'Editar archivos',
            'archivos.eliminar' => 'Eliminar archivos',

            'citas.ver' => 'Ver citas',
            'citas.crear' => 'Crear citas',
            'citas.editar' => 'Editar citas',
            'citas.eliminar' => 'Eliminar citas',
            'citas.ver_propias' => 'Ver propias citas',

            'tramites.ver' => 'Ver trámites',
            'tramites.crear' => 'Crear trámites',
            'tramites.editar' => 'Editar trámites',
            'tramites.eliminar' => 'Eliminar trámites',
            'tramites.revisar' => 'Revisar trámites',
            'tramites.ver_propios' => 'Ver propios trámites',

            'revisiones.ver' => 'Ver revisiones',
            'revisiones.revisar' => 'Revisar trámites',

            'notificaciones.ver' => 'Ver notificaciones',
            'notificaciones.marcar_leida' => 'Marcar notificaciones como leídas',

            'proveedores.ver' => 'Ver proveedores',
            'proveedores.crear' => 'Crear proveedores',
            'proveedores.editar' => 'Editar proveedores',
            'proveedores.eliminar' => 'Eliminar proveedores',
            'proveedores.reportes.trimestrales' => 'Ver reportes trimestrales de proveedores',

            'logs.ver' => 'Ver logs',
            'logs.exportar' => 'Exportar logs',
            'logs.eliminar' => 'Eliminar logs',

            'dashboard.ver' => 'Ver dashboard',
            'mi-estado.ver' => 'Ver mi estado',
        ];

        // Crear los nuevos permisos
        foreach ($allPermissions as $permission => $description) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('Todos los permisos anteriores han sido eliminados y se han creado ' . count($allPermissions) . ' nuevos permisos.');
    }
}
