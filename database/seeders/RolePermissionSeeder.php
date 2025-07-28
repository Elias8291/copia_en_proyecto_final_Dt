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
            'tramites.view', 'tramites.create', 'tramites.edit', 'tramites.delete',
            'tramites.change_status', 'tramites.review', 'tramites.export',
            
            // Gestión de citas
            'citas.view', 'citas.create', 'citas.edit', 'citas.delete',
            'citas.cancel', 'citas.manage_all',
            
            // Revisión
            'revision.view', 'revision.approve', 'revision.reject',
            'revision.comment', 'revision.change_status', 'revision.export',
            
            // Archivos
            'files.view', 'files.upload', 'files.download', 'files.delete',
            'files.categorize',
            
            // Días inhábiles
            'dias_inhabiles.view', 'dias_inhabiles.create', 'dias_inhabiles.edit',
            'dias_inhabiles.delete',
            
            // Notificaciones
            'notifications.view', 'notifications.create', 'notifications.send',
            'notifications.manage',
            
            // Reportes
            'reports.view', 'reports.generate', 'reports.export', 'reports.statistics',
            
            // Actividades económicas
            'actividades.view', 'actividades.create', 'actividades.edit',
            'actividades.delete', 'actividades.search',
            
            // Catálogo de archivos
            'catalogo_archivos.view', 'catalogo_archivos.create',
            'catalogo_archivos.edit', 'catalogo_archivos.delete',
            
            // Proveedores (solo ver y editar)
            'providers.view_all', 'providers.edit',
            
            // Sistema (limitado)
            'system.reports', 'system.export',
        ];
        $admin->syncPermissions($adminPermissions);
        $this->command->info('✅ Administrador: ' . count($adminPermissions) . ' permisos asignados');

        // ============================================================================
        // REVISOR
        // ============================================================================
        $revisor = Role::where('name', 'Revisor')->first();
        $revisorPermissions = [
            // Ver trámites
            'tramites.view', 'tramites.change_status', 'tramites.review',
            
            // Gestión de citas
            'citas.view', 'citas.create', 'citas.edit', 'citas.cancel',
            
            // Revisión
            'revision.view', 'revision.approve', 'revision.reject',
            'revision.comment', 'revision.change_status',
            
            // Archivos
            'files.view', 'files.download',
            
            // Días inhábiles
            'dias_inhabiles.view',
            
            // Notificaciones
            'notifications.view', 'notifications.create', 'notifications.send',
            
            // Reportes
            'reports.view', 'reports.generate',
            
            // Actividades económicas
            'actividades.view', 'actividades.search',
            
            // Catálogo de archivos
            'catalogo_archivos.view',
            
            // Proveedores (solo ver)
            'providers.view_all',
        ];
        $revisor->syncPermissions($revisorPermissions);
        $this->command->info('✅ Revisor: ' . count($revisorPermissions) . ' permisos asignados');

        // ============================================================================
        // RECEPCIONISTA
        // ============================================================================
        $recepcionista = Role::where('name', 'Recepcionista')->first();
        $recepcionistaPermissions = [
            // Ver trámites
            'tramites.view',
            
            // Gestión de citas
            'citas.view', 'citas.create', 'citas.edit', 'citas.cancel',
            
            // Archivos
            'files.view', 'files.download',
            
            // Días inhábiles
            'dias_inhabiles.view',
            
            // Notificaciones
            'notifications.view', 'notifications.create', 'notifications.send',
            
            // Actividades económicas
            'actividades.view', 'actividades.search',
            
            // Catálogo de archivos
            'catalogo_archivos.view',
            
            // Proveedores (solo ver)
            'providers.view_all',
        ];
        $recepcionista->syncPermissions($recepcionistaPermissions);
        $this->command->info('✅ Recepcionista: ' . count($recepcionistaPermissions) . ' permisos asignados');

        // ============================================================================
        // PROVEEDOR
        // ============================================================================
        $proveedor = Role::where('name', 'Proveedor')->first();
        $proveedorPermissions = [
            // Gestión de perfil
            'users.view_own', 'users.edit_own',
            'providers.view_own', 'providers.edit_own',
            
            // Trámites propios
            'tramites.create', 'tramites.view_own', 'tramites.edit_own',
            'tramites.delete_own',
            
            // Citas propias
            'citas.view_own', 'citas.create', 'citas.edit_own',
            'citas.cancel_own',
            
            // Archivos propios
            'files.view_own', 'files.upload_own', 'files.download',
            'files.delete_own',
            
            // Notificaciones propias
            'notifications.view_own',
            
            // Actividades económicas
            'actividades.view', 'actividades.search',
            
            // Catálogo de archivos
            'catalogo_archivos.view',
        ];
        $proveedor->syncPermissions($proveedorPermissions);
        $this->command->info('✅ Proveedor: ' . count($proveedorPermissions) . ' permisos asignados');

        // ============================================================================
        // SOLICITANTE
        // ============================================================================
        $solicitante = Role::where('name', 'Solicitante')->first();
        $solicitantePermissions = [
            // Gestión básica de perfil
            'users.view_own', 'users.edit_own',
            
            // Trámites básicos
            'tramites.create', 'tramites.view_own', 'tramites.edit_own',
            'tramites.delete_own',
            
            // Citas básicas
            'citas.view_own', 'citas.create', 'citas.cancel_own',
            
            // Archivos básicos
            'files.view_own', 'files.upload_own', 'files.download',
            'files.delete_own',
            
            // Notificaciones propias
            'notifications.view_own',
            
            // Actividades económicas
            'actividades.view', 'actividades.search',
            
            // Catálogo de archivos
            'catalogo_archivos.view',
        ];
        $solicitante->syncPermissions($solicitantePermissions);
        $this->command->info('✅ Solicitante: ' . count($solicitantePermissions) . ' permisos asignados');

        // ============================================================================
        // CONSULTOR
        // ============================================================================
        $consultor = Role::where('name', 'Consultor')->first();
        $consultorPermissions = [
            // Solo lectura
            'tramites.view',
            'citas.view',
            'files.view', 'files.download',
            'reports.view', 'reports.export',
            'reports.statistics',
            'providers.view_all',
            'actividades.view',
            'catalogo_archivos.view',
        ];
        $consultor->syncPermissions($consultorPermissions);
        $this->command->info('✅ Consultor: ' . count($consultorPermissions) . ' permisos asignados');

        $this->command->info('🎉 Permisos asignados exitosamente a todos los roles');
    }
} 