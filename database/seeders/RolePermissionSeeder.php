<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔐 Asignando permisos a roles...');

        // ============================================================================
        // SUPER ADMINISTRADOR - TODOS LOS PERMISOS
        // ============================================================================
        $superAdmin = Role::where('name', 'Super Administrador')->first();
        $superAdmin->syncPermissions(Permission::all());
        $this->command->info('✅ Super Administrador: Todos los permisos asignados');

        // ============================================================================
        // ADMINISTRADOR
        // ============================================================================
        $admin = Role::where('name', 'Administrador')->first();
        $adminPermissions = [
            // Gestión de trámites
            'tramites.ver', 'tramites.crear', 'tramites.editar', 'tramites.eliminar',
            'tramites.cambiar_estado', 'tramites.revisar', 'tramites.exportar',
            'tramites.ver_todos', 'tramites.gestionar_todos',
            
            // Gestión de citas
            'citas.ver', 'citas.crear', 'citas.editar', 'citas.eliminar',
            'citas.cancelar', 'citas.reprogramar', 'citas.confirmar', 'citas.gestionar_todas',
            'citas.ver_todas', 'citas.exportar',
            
            // Revisión
            'revision.ver', 'revision.aprobar_documentos', 'revision.rechazar_documentos',
            'revision.agregar_comentarios', 'revision.cambiar_estado', 'revision.exportar',
            'revision.ver_todas', 'revision.finalizar_revision',
            
            // Archivos
            'archivos.ver', 'archivos.subir', 'archivos.descargar', 'archivos.eliminar',
            'archivos.categorizar', 'archivos.ver_todos', 'archivos.gestionar_todos',
            
            // Días inhábiles
            'dias_inhabiles.ver', 'dias_inhabiles.crear', 'dias_inhabiles.editar',
            'dias_inhabiles.eliminar', 'dias_inhabiles.gestionar',
            
            // Notificaciones
            'notificaciones.ver', 'notificaciones.crear', 'notificaciones.enviar',
            'notificaciones.gestionar', 'notificaciones.marcar_leidas', 'notificaciones.eliminar',
            
            // Reportes
            'reportes.ver', 'reportes.generar', 'reportes.exportar', 'reportes.estadisticas',
            'reportes.tramites', 'reportes.citas', 'reportes.usuarios',
            
            // Actividades económicas
            'actividades.ver', 'actividades.crear', 'actividades.editar',
            'actividades.eliminar', 'actividades.buscar', 'actividades.importar', 'actividades.exportar',
            
            // Catálogo de archivos
            'catalogo_archivos.ver', 'catalogo_archivos.crear',
            'catalogo_archivos.editar', 'catalogo_archivos.eliminar', 'catalogo_archivos.gestionar',
            
            // Proveedores (solo ver y editar)
            'proveedores.ver_todos', 'proveedores.editar', 'proveedores.exportar',
            
            // Sistema (limitado)
            'sistema.reportes', 'sistema.exportar', 'sistema.estadisticas',
            
            // Dashboard
            'dashboard.ver', 'dashboard.estadisticas', 'dashboard.reportes', 'dashboard.notificaciones',
        ];
        $admin->syncPermissions($adminPermissions);
        $this->command->info('✅ Administrador: ' . count($adminPermissions) . ' permisos asignados');

        // ============================================================================
        // REVISOR
        // ============================================================================
        $revisor = Role::where('name', 'Revisor')->first();
        $revisorPermissions = [
            // Ver trámites
            'tramites.ver', 'tramites.cambiar_estado', 'tramites.revisar',
            'tramites.aprobar', 'tramites.rechazar', 'tramites.enviar_cotejo',
            
            // Gestión de citas
            'citas.ver', 'citas.crear', 'citas.editar', 'citas.cancelar',
            'citas.reprogramar', 'citas.confirmar',
            
            // Revisión
            'revision.ver', 'revision.aprobar_documentos', 'revision.rechazar_documentos',
            'revision.agregar_comentarios', 'revision.cambiar_estado', 'revision.ver_todas',
            'revision.finalizar_revision',
            
            // Archivos
            'archivos.ver', 'archivos.descargar',
            
            // Días inhábiles
            'dias_inhabiles.ver',
            
            // Notificaciones
            'notificaciones.ver', 'notificaciones.crear', 'notificaciones.enviar',
            'notificaciones.marcar_leidas',
            
            // Reportes
            'reportes.ver', 'reportes.generar', 'reportes.tramites',
            
            // Actividades económicas
            'actividades.ver', 'actividades.buscar',
            
            // Catálogo de archivos
            'catalogo_archivos.ver',
            
            // Proveedores (solo ver)
            'proveedores.ver_todos',
            
            // Dashboard
            'dashboard.ver', 'dashboard.estadisticas',
        ];
        $revisor->syncPermissions($revisorPermissions);
        $this->command->info('✅ Revisor: ' . count($revisorPermissions) . ' permisos asignados');

        // ============================================================================
        // RECEPCIONISTA
        // ============================================================================
        $recepcionista = Role::where('name', 'Recepcionista')->first();
        $recepcionistaPermissions = [
            // Ver trámites
            'tramites.ver',
            
            // Gestión de citas
            'citas.ver', 'citas.crear', 'citas.editar', 'citas.cancelar',
            'citas.reprogramar', 'citas.confirmar',
            
            // Archivos
            'archivos.ver', 'archivos.descargar',
            
            // Días inhábiles
            'dias_inhabiles.ver',
            
            // Notificaciones
            'notificaciones.ver', 'notificaciones.crear', 'notificaciones.enviar',
            'notificaciones.marcar_leidas',
            
            // Actividades económicas
            'actividades.ver', 'actividades.buscar',
            
            // Catálogo de archivos
            'catalogo_archivos.ver',
            
            // Proveedores (solo ver)
            'proveedores.ver_todos',
            
            // Dashboard
            'dashboard.ver',
        ];
        $recepcionista->syncPermissions($recepcionistaPermissions);
        $this->command->info('✅ Recepcionista: ' . count($recepcionistaPermissions) . ' permisos asignados');

        // ============================================================================
        // PROVEEDOR
        // ============================================================================
        $proveedor = Role::where('name', 'Proveedor')->first();
        $proveedorPermissions = [
            // Gestión de perfil
            'usuarios.ver_propio', 'usuarios.editar_propio',
            'proveedores.ver_propio', 'proveedores.editar_propio',
            
            // Trámites propios
            'tramites.crear', 'tramites.ver_propios', 'tramites.editar_propios',
            'tramites.eliminar_propios',
            
            // Citas propias
            'citas.ver_propias', 'citas.crear', 'citas.editar_propias',
            'citas.cancelar_propias',
            
            // Archivos propios
            'archivos.ver_propios', 'archivos.subir_propios', 'archivos.descargar',
            'archivos.eliminar_propios',
            
            // Notificaciones propias
            'notificaciones.ver_propias', 'notificaciones.marcar_leidas',
            
            // Actividades económicas
            'actividades.ver', 'actividades.buscar',
            
            // Catálogo de archivos
            'catalogo_archivos.ver',
            
            // Dashboard básico
            'dashboard.ver',
        ];
        $proveedor->syncPermissions($proveedorPermissions);
        $this->command->info('✅ Proveedor: ' . count($proveedorPermissions) . ' permisos asignados');

        // ============================================================================
        // SOLICITANTE
        // ============================================================================
        $solicitante = Role::where('name', 'Solicitante')->first();
        $solicitantePermissions = [
            // Gestión básica de perfil
            'usuarios.ver_propio', 'usuarios.editar_propio',
            
            // Trámites básicos
            'tramites.crear', 'tramites.ver_propios', 'tramites.editar_propios',
            'tramites.eliminar_propios',
            
            // Citas básicas
            'citas.ver_propias', 'citas.crear', 'citas.cancelar_propias',
            
            // Archivos básicos
            'archivos.ver_propios', 'archivos.subir_propios', 'archivos.descargar',
            'archivos.eliminar_propios',
            
            // Notificaciones propias
            'notificaciones.ver_propias', 'notificaciones.marcar_leidas',
            
            // Actividades económicas
            'actividades.ver', 'actividades.buscar',
            
            // Catálogo de archivos
            'catalogo_archivos.ver',
            
            // Dashboard básico
            'dashboard.ver',
        ];
        $solicitante->syncPermissions($solicitantePermissions);
        $this->command->info('✅ Solicitante: ' . count($solicitantePermissions) . ' permisos asignados');

        // ============================================================================
        // CONSULTOR
        // ============================================================================
        $consultor = Role::where('name', 'Consultor')->first();
        $consultorPermissions = [
            // Solo lectura
            'tramites.ver',
            'citas.ver',
            'archivos.ver', 'archivos.descargar',
            'reportes.ver', 'reportes.exportar',
            'reportes.estadisticas', 'reportes.tramites', 'reportes.citas', 'reportes.usuarios',
            'proveedores.ver_todos',
            'actividades.ver',
            'catalogo_archivos.ver',
            'dashboard.ver', 'dashboard.estadisticas', 'dashboard.reportes',
        ];
        $consultor->syncPermissions($consultorPermissions);
        $this->command->info('✅ Consultor: ' . count($consultorPermissions) . ' permisos asignados');

        $this->command->info('🎉 Permisos asignados exitosamente a todos los roles');
    }
} 