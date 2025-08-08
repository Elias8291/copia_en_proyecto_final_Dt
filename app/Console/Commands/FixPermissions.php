<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FixPermissions extends Command
{
    protected $signature = 'fix:permissions';
    protected $description = 'Fix permissions and roles system';

    public function handle()
    {
        $this->info('🔧 Fixing permissions and roles system...');

        // Clear existing permissions
        Permission::query()->delete();
        $this->info('✅ Cleared existing permissions');

        // Create all permissions
        $permissions = [
            // Usuarios
            'usuarios.ver', 'usuarios.crear', 'usuarios.editar', 'usuarios.eliminar',
            'usuarios.asignar_roles', 'usuarios.ver_propio', 'usuarios.editar_propio',
            'usuarios.cambiar_password', 'usuarios.restaurar', 'usuarios.eliminar_permanente',
            
            // Roles
            'roles.ver', 'roles.crear', 'roles.editar', 'roles.eliminar',
            'roles.asignar_permisos', 'roles.ver_permisos', 'roles.gestionar',
            
            // Archivos
            'archivos.ver', 'archivos.crear', 'archivos.editar', 'archivos.eliminar',
            'archivos.descargar', 'archivos.subir', 'archivos.ver_propios',
            'archivos.gestionar_estado', 'archivos.ver_tramite',
            
            // Citas
            'citas.ver', 'citas.crear', 'citas.editar', 'citas.eliminar',
            'citas.asignar', 'citas.reagendar', 'citas.cancelar',
            'citas.marcar_asistida', 'citas.marcar_no_asistio', 'citas.ver_propias',
            'citas.gestionar_horarios',
            
            // Trámites
            'tramites.ver', 'tramites.crear', 'tramites.editar', 'tramites.eliminar',
            'tramites.revisar', 'tramites.aprobar', 'tramites.rechazar',
            'tramites.ver_propios', 'tramites.descargar_constancia',
            
            // Revisiones
            'revisiones.ver', 'revisiones.iniciar', 'revisiones.procesar_digital',
            'revisiones.procesar_presencial', 'revisiones.evaluar_seccion',
            'revisiones.tomar_decision', 'revisiones.ver_historico',
            
            // Notificaciones
            'notificaciones.ver', 'notificaciones.crear', 'notificaciones.editar',
            'notificaciones.eliminar', 'notificaciones.gestionar', 'notificaciones.marcar_leida',
            
            // Proveedores
            'proveedores.ver', 'proveedores.crear', 'proveedores.editar',
            'proveedores.eliminar', 'proveedores.exportar', 'proveedores.ver_estadisticas',
            
            // Dashboard
            'dashboard.ver', 'dashboard.estadisticas', 'reportes.generar', 'reportes.exportar',
            
            // Super Admin
            'super-admin', 'configuracion.gestionar', 'logs.ver', 'backup.gestionar',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $this->info('✅ Created ' . count($permissions) . ' permissions');

        // Create roles
        $roles = [
            'Super Administrador' => 'Acceso total al sistema',
            'Administrador' => 'Gestión general del sistema',
            'Revisor Digital' => 'Especialista en revisión de documentos digitales',
            'Revisor Presencial' => 'Especialista en cotejo presencial',
            'Revisor Domiciliario' => 'Especialista en verificaciones domiciliarias',
            'Proveedor' => 'Empresa o persona física que solicita servicios',
            'Solicitante' => 'Usuario individual que realiza solicitudes',
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate(['name' => $name], [
                'name' => $name,
                'description' => $description,
                'guard_name' => 'web'
            ]);
        }

        $this->info('✅ Created roles');

        // Assign permissions to Super Admin
        $superAdmin = Role::where('name', 'Super Administrador')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::all());
            $this->info('✅ Assigned all permissions to Super Administrador');
        }

        // Assign permissions to Admin
        $admin = Role::where('name', 'Administrador')->first();
        if ($admin) {
            $adminPermissions = [
                'usuarios.ver', 'usuarios.crear', 'usuarios.editar', 'usuarios.asignar_roles',
                'usuarios.ver_propio', 'usuarios.editar_propio', 'usuarios.cambiar_password',
                'usuarios.restaurar', 'roles.ver', 'roles.crear', 'roles.editar',
                'roles.asignar_permisos', 'roles.ver_permisos', 'roles.gestionar',
                'archivos.ver', 'archivos.crear', 'archivos.editar', 'archivos.eliminar',
                'archivos.descargar', 'archivos.subir', 'archivos.gestionar_estado',
                'archivos.ver_tramite', 'citas.ver', 'citas.crear', 'citas.editar',
                'citas.eliminar', 'citas.asignar', 'citas.reagendar', 'citas.cancelar',
                'citas.marcar_asistida', 'citas.marcar_no_asistio', 'citas.gestionar_horarios',
                'tramites.ver', 'tramites.crear', 'tramites.editar', 'tramites.eliminar',
                'tramites.revisar', 'tramites.aprobar', 'tramites.rechazar',
                'tramites.descargar_constancia', 'revisiones.ver', 'revisiones.iniciar',
                'revisiones.procesar_digital', 'revisiones.procesar_presencial',
                'revisiones.evaluar_seccion', 'revisiones.tomar_decision',
                'revisiones.ver_historico', 'notificaciones.ver', 'notificaciones.crear',
                'notificaciones.editar', 'notificaciones.eliminar', 'notificaciones.gestionar',
                'notificaciones.marcar_leida', 'proveedores.ver', 'proveedores.crear',
                'proveedores.editar', 'proveedores.eliminar', 'proveedores.exportar',
                'proveedores.ver_estadisticas', 'dashboard.ver', 'dashboard.estadisticas',
                'reportes.generar', 'reportes.exportar',
            ];
            $admin->givePermissionTo($adminPermissions);
            $this->info('✅ Assigned permissions to Administrador');
        }

        // Assign permissions to Revisor Digital
        $revisorDigital = Role::where('name', 'Revisor Digital')->first();
        if ($revisorDigital) {
            $revisorDigitalPermissions = [
                'usuarios.ver_propio', 'usuarios.editar_propio', 'usuarios.cambiar_password',
                'archivos.ver', 'archivos.descargar', 'archivos.ver_tramite',
                'citas.ver', 'citas.ver_propias', 'tramites.ver', 'tramites.revisar',
                'tramites.ver_propios', 'revisiones.ver', 'revisiones.iniciar',
                'revisiones.procesar_digital', 'revisiones.evaluar_seccion',
                'revisiones.tomar_decision', 'revisiones.ver_historico',
                'notificaciones.ver', 'notificaciones.marcar_leida', 'dashboard.ver',
            ];
            $revisorDigital->givePermissionTo($revisorDigitalPermissions);
            $this->info('✅ Assigned permissions to Revisor Digital');
        }

        // Assign permissions to Revisor Presencial
        $revisorPresencial = Role::where('name', 'Revisor Presencial')->first();
        if ($revisorPresencial) {
            $revisorPresencialPermissions = [
                'usuarios.ver_propio', 'usuarios.editar_propio', 'usuarios.cambiar_password',
                'archivos.ver', 'archivos.descargar', 'archivos.ver_tramite',
                'citas.ver', 'citas.crear', 'citas.editar', 'citas.asignar',
                'citas.reagendar', 'citas.cancelar', 'citas.marcar_asistida',
                'citas.marcar_no_asistio', 'citas.gestionar_horarios',
                'tramites.ver', 'tramites.revisar', 'tramites.aprobar', 'tramites.rechazar',
                'tramites.ver_propios', 'revisiones.ver', 'revisiones.iniciar',
                'revisiones.procesar_presencial', 'revisiones.evaluar_seccion',
                'revisiones.tomar_decision', 'revisiones.ver_historico',
                'notificaciones.ver', 'notificaciones.marcar_leida', 'dashboard.ver',
            ];
            $revisorPresencial->givePermissionTo($revisorPresencialPermissions);
            $this->info('✅ Assigned permissions to Revisor Presencial');
        }

        // Assign permissions to Revisor Domiciliario
        $revisorDomiciliario = Role::where('name', 'Revisor Domiciliario')->first();
        if ($revisorDomiciliario) {
            $revisorDomiciliarioPermissions = [
                'usuarios.ver_propio', 'usuarios.editar_propio', 'usuarios.cambiar_password',
                'archivos.ver', 'archivos.descargar', 'archivos.ver_tramite',
                'citas.ver', 'citas.crear', 'citas.editar', 'citas.asignar',
                'citas.reagendar', 'citas.cancelar', 'citas.marcar_asistida',
                'citas.marcar_no_asistio', 'citas.gestionar_horarios',
                'tramites.ver', 'tramites.revisar', 'tramites.aprobar', 'tramites.rechazar',
                'tramites.ver_propios', 'revisiones.ver', 'revisiones.iniciar',
                'revisiones.evaluar_seccion', 'revisiones.tomar_decision',
                'revisiones.ver_historico', 'notificaciones.ver', 'notificaciones.marcar_leida',
                'dashboard.ver',
            ];
            $revisorDomiciliario->givePermissionTo($revisorDomiciliarioPermissions);
            $this->info('✅ Assigned permissions to Revisor Domiciliario');
        }

        // Assign permissions to Proveedor
        $proveedor = Role::where('name', 'Proveedor')->first();
        if ($proveedor) {
            $proveedorPermissions = [
                'usuarios.ver_propio', 'usuarios.editar_propio', 'usuarios.cambiar_password',
                'archivos.ver_propios', 'archivos.crear', 'archivos.subir', 'archivos.descargar',
                'citas.ver_propias', 'tramites.crear', 'tramites.ver_propios',
                'tramites.editar', 'tramites.descargar_constancia',
                'notificaciones.ver', 'notificaciones.marcar_leida', 'dashboard.ver',
            ];
            $proveedor->givePermissionTo($proveedorPermissions);
            $this->info('✅ Assigned permissions to Proveedor');
        }

        // Assign permissions to Solicitante
        $solicitante = Role::where('name', 'Solicitante')->first();
        if ($solicitante) {
            $solicitantePermissions = [
                'usuarios.ver_propio', 'usuarios.editar_propio', 'usuarios.cambiar_password',
                'archivos.ver_propios', 'archivos.crear', 'archivos.subir', 'archivos.descargar',
                'citas.ver_propias', 'tramites.crear', 'tramites.ver_propios',
                'tramites.editar', 'tramites.descargar_constancia',
                'notificaciones.ver', 'notificaciones.marcar_leida', 'dashboard.ver',
            ];
            $solicitante->givePermissionTo($solicitantePermissions);
            $this->info('✅ Assigned permissions to Solicitante');
        }

        $this->info('🎉 Permissions and roles system fixed successfully!');
    }
} 