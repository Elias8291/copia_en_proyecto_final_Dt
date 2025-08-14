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

        $tramitePermissions = [
            'tramites.ver' => 'Ver trámites',
            'tramites.crear' => 'Crear trámites',
            'tramites.editar' => 'Editar trámites',
            'tramites.revisar' => 'Revisar trámites',
            'tramites.aprobar' => 'Aprobar trámites',
            'tramites.rechazar' => 'Rechazar trámites',
            'tramites.asignar_revisor' => 'Asignar revisor a trámites',
            'tramites.ver_estado' => 'Ver estado de trámites',
            'tramites.ver_mi_estado' => 'Ver estado de mis trámites',
            'tramites.ver_historial' => 'Ver historial de trámites',
            'tramites.cancelar' => 'Cancelar trámites',
            'tramites.reanudar' => 'Reanudar trámites cancelados',
        ];

        $proveedorPermissions = [
            'proveedores.ver' => 'Ver lista de proveedores',
            'proveedores.crear' => 'Crear nuevos proveedores',
            'proveedores.editar' => 'Editar información de proveedores',
            'proveedores.eliminar' => 'Eliminar proveedores',
            'proveedores.ver_detalle' => 'Ver detalles completos de proveedores',
        ];

        $revisionPermissions = [
            'revisiones.ver' => 'Ver revisiones',
            'revisiones.crear' => 'Crear nuevas revisiones',
            'revisiones.editar' => 'Editar revisiones',
            'revisiones.eliminar' => 'Eliminar revisiones',
            'revisiones.digital' => 'Realizar revisiones digitales',
            'revisiones.presencial' => 'Realizar revisiones presenciales',
            'revisiones.domiciliaria' => 'Realizar revisiones domiciliarias',
        ];

        $citaPermissions = [
            'citas.ver' => 'Ver citas',
            'citas.crear' => 'Crear citas',
            'citas.editar' => 'Editar citas',
            'citas.cancelar' => 'Cancelar citas',
        ];

        $notificacionPermissions = [
            'notificaciones.ver' => 'Ver notificaciones',
            'notificaciones.crear' => 'Crear notificaciones',
            'notificaciones.marcar_leida' => 'Marcar notificaciones como leídas',
        ];

        $oficioPermissions = [
            'oficios.ver' => 'Ver oficios',
            'oficios.crear' => 'Crear oficios',
            'oficios.editar' => 'Editar oficios',
            'oficios.eliminar' => 'Eliminar oficios',
        ];

        $systemPermissions = [
            'sistema.administrar' => 'Administrar sistema completo',
            'reportes.ver' => 'Ver reportes',
            'configuracion.editar' => 'Editar configuración del sistema',
            'mi-estado.ver' => 'Ver mi estado',
        ];

        $allPermissions = array_merge(
            $userPermissions, 
            $rolePermissions, 
            $tramitePermissions, 
            $proveedorPermissions,
            $revisionPermissions,
            $citaPermissions,
            $notificacionPermissions,
            $oficioPermissions,
            $systemPermissions
        );

        foreach ($allPermissions as $permission => $description) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('✅ Permisos creados: ' . count($allPermissions) . ' permisos');
    }
} 