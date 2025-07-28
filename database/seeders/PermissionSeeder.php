<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar permisos existentes de forma segura
        $this->command->info('🧹 Limpiando permisos existentes...');
        
        // Eliminar permisos de forma segura
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $permission->delete();
        }

        // ============================================================================
        // PERMISOS DE USUARIOS
        // ============================================================================
        $userPermissions = [
            'users.view' => 'Ver usuarios',
            'users.create' => 'Crear usuarios',
            'users.edit' => 'Editar usuarios',
            'users.delete' => 'Eliminar usuarios',
            'users.restore' => 'Restaurar usuarios',
            'users.assign_roles' => 'Asignar roles',
            'users.view_own' => 'Ver propio perfil',
            'users.edit_own' => 'Editar propio perfil',
        ];

        // ============================================================================
        // PERMISOS DE ROLES
        // ============================================================================
        $rolePermissions = [
            'roles.view' => 'Ver roles',
            'roles.create' => 'Crear roles',
            'roles.edit' => 'Editar roles',
            'roles.delete' => 'Eliminar roles',
            'roles.assign_permissions' => 'Asignar permisos',
        ];

        // ============================================================================
        // PERMISOS DE PROVEEDORES
        // ============================================================================
        $providerPermissions = [
            'providers.view' => 'Ver proveedores',
            'providers.create' => 'Crear proveedores',
            'providers.edit' => 'Editar proveedores',
            'providers.delete' => 'Eliminar proveedores',
            'providers.view_all' => 'Ver todos los proveedores',
            'providers.view_own' => 'Ver propio perfil',
            'providers.edit_own' => 'Editar propio perfil',
        ];

        // ============================================================================
        // PERMISOS DE TRÁMITES
        // ============================================================================
        $tramitePermissions = [
            'tramites.view' => 'Ver trámites',
            'tramites.create' => 'Crear trámites',
            'tramites.edit' => 'Editar trámites',
            'tramites.delete' => 'Eliminar trámites',
            'tramites.change_status' => 'Cambiar estado de trámites',
            'tramites.review' => 'Revisar documentos',
            'tramites.export' => 'Exportar datos de trámites',
            'tramites.view_own' => 'Ver propios trámites',
            'tramites.edit_own' => 'Editar propios trámites',
            'tramites.delete_own' => 'Eliminar propios trámites',
        ];

        // ============================================================================
        // PERMISOS DE CITAS
        // ============================================================================
        $citaPermissions = [
            'citas.view' => 'Ver citas',
            'citas.create' => 'Crear citas',
            'citas.edit' => 'Editar citas',
            'citas.delete' => 'Eliminar citas',
            'citas.cancel' => 'Cancelar citas',
            'citas.manage_all' => 'Gestionar todas las citas',
            'citas.view_own' => 'Ver propias citas',
            'citas.edit_own' => 'Editar propias citas',
            'citas.cancel_own' => 'Cancelar propias citas',
        ];

        // ============================================================================
        // PERMISOS DE REVISIÓN
        // ============================================================================
        $revisionPermissions = [
            'revision.view' => 'Ver revisiones',
            'revision.approve' => 'Aprobar documentos',
            'revision.reject' => 'Rechazar documentos',
            'revision.comment' => 'Agregar comentarios',
            'revision.change_status' => 'Cambiar estado de revisión',
            'revision.export' => 'Exportar revisiones',
        ];

        // ============================================================================
        // PERMISOS DE ARCHIVOS
        // ============================================================================
        $filePermissions = [
            'files.view' => 'Ver archivos',
            'files.upload' => 'Subir archivos',
            'files.download' => 'Descargar archivos',
            'files.delete' => 'Eliminar archivos',
            'files.categorize' => 'Categorizar archivos',
            'files.view_own' => 'Ver propios archivos',
            'files.upload_own' => 'Subir propios archivos',
            'files.delete_own' => 'Eliminar propios archivos',
        ];

        // ============================================================================
        // PERMISOS DE DÍAS INHÁBILES
        // ============================================================================
        $diaInhabilPermissions = [
            'dias_inhabiles.view' => 'Ver días inhábiles',
            'dias_inhabiles.create' => 'Crear días inhábiles',
            'dias_inhabiles.edit' => 'Editar días inhábiles',
            'dias_inhabiles.delete' => 'Eliminar días inhábiles',
        ];

        // ============================================================================
        // PERMISOS DE NOTIFICACIONES
        // ============================================================================
        $notificacionPermissions = [
            'notifications.view' => 'Ver notificaciones',
            'notifications.create' => 'Crear notificaciones',
            'notifications.send' => 'Enviar notificaciones',
            'notifications.manage' => 'Gestionar notificaciones',
            'notifications.view_own' => 'Ver propias notificaciones',
        ];

        // ============================================================================
        // PERMISOS DE SISTEMA
        // ============================================================================
        $systemPermissions = [
            'system.config' => 'Configuración del sistema',
            'system.logs' => 'Ver logs del sistema',
            'system.backup' => 'Respaldos del sistema',
            'system.reports' => 'Reportes del sistema',
            'system.export' => 'Exportar datos del sistema',
        ];

        // ============================================================================
        // PERMISOS DE ACTIVIDADES ECONÓMICAS
        // ============================================================================
        $actividadPermissions = [
            'actividades.view' => 'Ver actividades económicas',
            'actividades.create' => 'Crear actividades económicas',
            'actividades.edit' => 'Editar actividades económicas',
            'actividades.delete' => 'Eliminar actividades económicas',
            'actividades.search' => 'Buscar actividades económicas',
        ];

        // ============================================================================
        // PERMISOS DE CATÁLOGO DE ARCHIVOS
        // ============================================================================
        $catalogoArchivoPermissions = [
            'catalogo_archivos.view' => 'Ver catálogo de archivos',
            'catalogo_archivos.create' => 'Crear catálogo de archivos',
            'catalogo_archivos.edit' => 'Editar catálogo de archivos',
            'catalogo_archivos.delete' => 'Eliminar catálogo de archivos',
        ];

        // ============================================================================
        // PERMISOS DE REPORTES
        // ============================================================================
        $reportePermissions = [
            'reports.view' => 'Ver reportes',
            'reports.generate' => 'Generar reportes',
            'reports.export' => 'Exportar reportes',
            'reports.statistics' => 'Ver estadísticas',
        ];

        // Combinar todos los permisos
        $allPermissions = array_merge(
            $userPermissions,
            $rolePermissions,
            $providerPermissions,
            $tramitePermissions,
            $citaPermissions,
            $revisionPermissions,
            $filePermissions,
            $diaInhabilPermissions,
            $notificacionPermissions,
            $systemPermissions,
            $actividadPermissions,
            $catalogoArchivoPermissions,
            $reportePermissions
        );

        // Crear permisos
        foreach ($allPermissions as $permission => $description) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('✅ Permisos creados exitosamente: ' . count($allPermissions) . ' permisos');
    }
} 