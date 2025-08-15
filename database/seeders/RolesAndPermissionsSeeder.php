<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurar que todos los permisos estén disponibles
        $allPermissions = Permission::all();
        
        if ($allPermissions->isEmpty()) {
            $this->command->error('❌ No hay permisos disponibles. Ejecute PermissionSeeder primero.');
            return;
        }

        $superAdmin = Role::where('name', 'Super Administrador')->first();
        if ($superAdmin) {
            
            $superAdmin->syncPermissions($allPermissions);
            $this->command->info("✅ Super Administrador: {$allPermissions->count()} permisos asignados");
        } else {
            $this->command->warn('⚠️ Rol Super Administrador no encontrado');
        }

        $admin = Role::where('name', 'Administrador')->first();
        if ($admin) {
            $admin->syncPermissions([
                'usuarios.ver',
                'usuarios.crear',
                'usuarios.editar',
                'usuarios.asignar_roles',
                'usuarios.ver_propio',
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
                'roles.ver',
                'roles.ver_permisos',
                'tramites.ver',
                'tramites.asignar_revisor',
                'tramites.ver_estado',
                'tramites.ver_historial',
                'tramites.cancelar',
                'tramites.reanudar',
                'proveedores.ver',
                'proveedores.crear',
                'proveedores.editar',
                'proveedores.ver_detalle',
                'revisiones.ver',
                'revisiones.crear',
                'citas.ver',
                'citas.crear',
                'citas.editar',
                'notificaciones.ver',
                'notificaciones.crear',
                'oficios.ver',
                'oficios.crear',
              
                'reportes.ver',
                'configuracion.editar',
            ]);
            $this->command->info("✅ Administrador: permisos asignados");
        }

        $revisorDigital = Role::where('name', 'Revisor Digital')->first();
        if ($revisorDigital) {
            $revisorDigital->syncPermissions([
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
                'usuarios.ver_propio',
                'tramites.revisar',
                'tramites.aprobar',
                'tramites.rechazar',
                'tramites.ver_historial',
                'proveedores.ver',
                'proveedores.ver_detalle',
                'revisiones.ver',
                'revisiones.crear',
                'revisiones.editar',
                'revisiones.presencial',
                'notificaciones.ver',
                'notificaciones.marcar_leida',
                'oficios.ver',
                'archivos.editar',
            ]);
            $this->command->info("✅ Revisor Digital: permisos asignados");
        }

        $revisorPresencial = Role::where('name', 'Revisor Presencial')->first();
        if ($revisorPresencial) {
            $revisorPresencial->syncPermissions([
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
                'usuarios.ver_propio',
                'tramites.revisar',
                'tramites.aprobar',
                'tramites.rechazar',
                'tramites.ver_estado',
                'tramites.ver_historial',
                'proveedores.ver',
                'proveedores.ver_detalle',
                'revisiones.ver',
                'revisiones.crear',
                'revisiones.editar',
                'revisiones.presencial',
                'citas.crear',
                'citas.editar',
                'citas.ver',
                'notificaciones.ver',
                'notificaciones.marcar_leida',
                'oficios.ver',
                'archivos.editar',
                'archivos.ver',
            ]);
            $this->command->info("✅ Revisor Presencial: permisos asignados");
        }

        $revisorDomiciliario = Role::where('name', 'Revisor Domiciliario')->first();
        if ($revisorDomiciliario) {
            $revisorDomiciliario->syncPermissions([
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
                'usuarios.ver_propio',
                'tramites.revisar',
                'tramites.aprobar',
                'tramites.rechazar',
                'tramites.ver_estado',
                'tramites.ver_historial',
                'proveedores.ver',
                'proveedores.ver_detalle',
                'revisiones.ver',
                'revisiones.crear',
                'revisiones.editar',
                'revisiones.presencial',
                'citas.crear',
                'citas.editar',
                'citas.ver',
                'notificaciones.ver',
                'notificaciones.marcar_leida',
                'oficios.ver',
                'archivos.editar',
                'archivos.ver',
            ]);
            $this->command->info("✅ Revisor Domiciliario: permisos asignados");
        }

        $proveedor = Role::where('name', 'Proveedor')->first();
        if ($proveedor) {
            $proveedor->syncPermissions([
                'usuarios.ver_propio',
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
                'tramites.crear',
                'tramites.ver',
                'tramites.editar',
                'tramites.ver_mi_estado', 
                'tramites.ver_historial',
                'proveedores.ver_detalle', 
                'citas.ver',
                'citas.crear',
                'notificaciones.ver',
                'notificaciones.marcar_leida',
                'oficios.ver',
                'mi-estado.ver',
            ]);
            $this->command->info("✅ Proveedor: permisos asignados");
        }

        $solicitante = Role::where('name', 'Solicitante')->first();
        if ($solicitante) {
            $solicitante->syncPermissions([
                'usuarios.ver_propio',
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
                'tramites.crear',
                'tramites.ver',
                'tramites.ver_mi_estado', 
                'notificaciones.ver',
                'notificaciones.marcar_leida',
                'mi-estado.ver',
            ]);
            $this->command->info("✅ Solicitante: permisos asignados");
        }

        $this->command->info('✅ Permisos asignados a roles exitosamente');
    }
} 