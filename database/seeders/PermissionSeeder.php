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
            'usuarios.ver' => 'Ver usuarios',
            'usuarios.crear' => 'Crear usuarios',
            'usuarios.editar' => 'Editar usuarios',
            'usuarios.eliminar' => 'Eliminar usuarios',
            'usuarios.restaurar' => 'Restaurar usuarios',
            'usuarios.asignar_roles' => 'Asignar roles a usuarios',
            'usuarios.ver_propio' => 'Ver propio perfil',
            'usuarios.editar_propio' => 'Editar propio perfil',
            'usuarios.cambiar_password' => 'Cambiar contraseñas',
        ];

        // ============================================================================
        // PERMISOS DE ROLES
        // ============================================================================
        $rolePermissions = [
            'roles.ver' => 'Ver roles',
            'roles.crear' => 'Crear roles',
            'roles.editar' => 'Editar roles',
            'roles.eliminar' => 'Eliminar roles',
            'roles.asignar_permisos' => 'Asignar permisos a roles',
            'roles.ver_permisos' => 'Ver permisos de roles',
        ];

        // ============================================================================
        // PERMISOS DE PROVEEDORES
        // ============================================================================
        $providerPermissions = [
            'proveedores.ver' => 'Ver proveedores',
            'proveedores.crear' => 'Crear proveedores',
            'proveedores.editar' => 'Editar proveedores',
            'proveedores.eliminar' => 'Eliminar proveedores',
            'proveedores.ver_todos' => 'Ver todos los proveedores',
            'proveedores.ver_propio' => 'Ver propio perfil de proveedor',
            'proveedores.editar_propio' => 'Editar propio perfil de proveedor',
            'proveedores.exportar' => 'Exportar datos de proveedores',
        ];

        // ============================================================================
        // PERMISOS DE TRÁMITES
        // ============================================================================
        $tramitePermissions = [
            'tramites.ver' => 'Ver trámites',
            'tramites.crear' => 'Crear trámites',
            'tramites.editar' => 'Editar trámites',
            'tramites.eliminar' => 'Eliminar trámites',
            'tramites.cambiar_estado' => 'Cambiar estado de trámites',
            'tramites.revisar' => 'Revisar documentos de trámites',
            'tramites.aprobar' => 'Aprobar trámites',
            'tramites.rechazar' => 'Rechazar trámites',
            'tramites.enviar_cotejo' => 'Enviar trámites a cotejo',
            'tramites.exportar' => 'Exportar datos de trámites',
            'tramites.ver_propios' => 'Ver propios trámites',
            'tramites.editar_propios' => 'Editar propios trámites',
            'tramites.eliminar_propios' => 'Eliminar propios trámites',
            'tramites.ver_todos' => 'Ver todos los trámites',
            'tramites.gestionar_todos' => 'Gestionar todos los trámites',
        ];

        // ============================================================================
        // PERMISOS DE CITAS
        // ============================================================================
        $citaPermissions = [
            'citas.ver' => 'Ver citas',
            'citas.crear' => 'Crear citas',
            'citas.editar' => 'Editar citas',
            'citas.eliminar' => 'Eliminar citas',
            'citas.cancelar' => 'Cancelar citas',
            'citas.reprogramar' => 'Reprogramar citas',
            'citas.confirmar' => 'Confirmar citas',
            'citas.gestionar_todas' => 'Gestionar todas las citas',
            'citas.ver_propias' => 'Ver propias citas',
            'citas.editar_propias' => 'Editar propias citas',
            'citas.cancelar_propias' => 'Cancelar propias citas',
            'citas.ver_todas' => 'Ver todas las citas',
            'citas.exportar' => 'Exportar citas',
        ];

        // ============================================================================
        // PERMISOS DE REVISIÓN
        // ============================================================================
        $revisionPermissions = [
            'revision.ver' => 'Ver revisiones',
            'revision.aprobar_documentos' => 'Aprobar documentos',
            'revision.rechazar_documentos' => 'Rechazar documentos',
            'revision.agregar_comentarios' => 'Agregar comentarios a documentos',
            'revision.cambiar_estado' => 'Cambiar estado de revisión',
            'revision.exportar' => 'Exportar revisiones',
            'revision.ver_todas' => 'Ver todas las revisiones',
            'revision.ver_propias' => 'Ver propias revisiones',
            'revision.finalizar_revision' => 'Finalizar revisión de trámite',
        ];

        // ============================================================================
        // PERMISOS DE ARCHIVOS
        // ============================================================================
        $filePermissions = [
            'archivos.ver' => 'Ver archivos',
            'archivos.subir' => 'Subir archivos',
            'archivos.descargar' => 'Descargar archivos',
            'archivos.eliminar' => 'Eliminar archivos',
            'archivos.categorizar' => 'Categorizar archivos',
            'archivos.ver_propios' => 'Ver propios archivos',
            'archivos.subir_propios' => 'Subir propios archivos',
            'archivos.eliminar_propios' => 'Eliminar propios archivos',
            'archivos.ver_todos' => 'Ver todos los archivos',
            'archivos.gestionar_todos' => 'Gestionar todos los archivos',
        ];

        // ============================================================================
        // PERMISOS DE DÍAS INHÁBILES
        // ============================================================================
        $diaInhabilPermissions = [
            'dias_inhabiles.ver' => 'Ver días inhábiles',
            'dias_inhabiles.crear' => 'Crear días inhábiles',
            'dias_inhabiles.editar' => 'Editar días inhábiles',
            'dias_inhabiles.eliminar' => 'Eliminar días inhábiles',
            'dias_inhabiles.gestionar' => 'Gestionar días inhábiles',
        ];

        // ============================================================================
        // PERMISOS DE NOTIFICACIONES
        // ============================================================================
        $notificacionPermissions = [
            'notificaciones.ver' => 'Ver notificaciones',
            'notificaciones.crear' => 'Crear notificaciones',
            'notificaciones.enviar' => 'Enviar notificaciones',
            'notificaciones.gestionar' => 'Gestionar notificaciones',
            'notificaciones.ver_propias' => 'Ver propias notificaciones',
            'notificaciones.marcar_leidas' => 'Marcar notificaciones como leídas',
            'notificaciones.eliminar' => 'Eliminar notificaciones',
        ];

        // ============================================================================
        // PERMISOS DE SISTEMA
        // ============================================================================
        $systemPermissions = [
            'sistema.configuracion' => 'Configuración del sistema',
            'sistema.logs' => 'Ver logs del sistema',
            'sistema.respaldos' => 'Respaldos del sistema',
            'sistema.reportes' => 'Reportes del sistema',
            'sistema.exportar' => 'Exportar datos del sistema',
            'sistema.mantenimiento' => 'Modo mantenimiento',
            'sistema.estadisticas' => 'Ver estadísticas del sistema',
        ];

        // ============================================================================
        // PERMISOS DE ACTIVIDADES ECONÓMICAS
        // ============================================================================
        $actividadPermissions = [
            'actividades.ver' => 'Ver actividades económicas',
            'actividades.crear' => 'Crear actividades económicas',
            'actividades.editar' => 'Editar actividades económicas',
            'actividades.eliminar' => 'Eliminar actividades económicas',
            'actividades.buscar' => 'Buscar actividades económicas',
            'actividades.importar' => 'Importar actividades económicas',
            'actividades.exportar' => 'Exportar actividades económicas',
        ];

        // ============================================================================
        // PERMISOS DE CATÁLOGO DE ARCHIVOS
        // ============================================================================
        $catalogoArchivoPermissions = [
            'catalogo_archivos.ver' => 'Ver catálogo de archivos',
            'catalogo_archivos.crear' => 'Crear catálogo de archivos',
            'catalogo_archivos.editar' => 'Editar catálogo de archivos',
            'catalogo_archivos.eliminar' => 'Eliminar catálogo de archivos',
            'catalogo_archivos.gestionar' => 'Gestionar catálogo de archivos',
        ];

        // ============================================================================
        // PERMISOS DE REPORTES
        // ============================================================================
        $reportePermissions = [
            'reportes.ver' => 'Ver reportes',
            'reportes.generar' => 'Generar reportes',
            'reportes.exportar' => 'Exportar reportes',
            'reportes.estadisticas' => 'Ver estadísticas',
            'reportes.tramites' => 'Reportes de trámites',
            'reportes.citas' => 'Reportes de citas',
            'reportes.usuarios' => 'Reportes de usuarios',
        ];

        // ============================================================================
        // PERMISOS DE DASHBOARD
        // ============================================================================
        $dashboardPermissions = [
            'dashboard.ver' => 'Ver dashboard',
            'dashboard.estadisticas' => 'Ver estadísticas del dashboard',
            'dashboard.reportes' => 'Ver reportes del dashboard',
            'dashboard.notificaciones' => 'Ver notificaciones del dashboard',
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
            $reportePermissions,
            $dashboardPermissions
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