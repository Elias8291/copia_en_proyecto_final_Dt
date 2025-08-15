<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ArchivosPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos básicos para archivos
        $archivosPermissions = [
            'archivos.ver' => 'Ver archivos',
            'archivos.crear' => 'Crear archivos',
            'archivos.editar' => 'Editar archivos',
            'archivos.eliminar' => 'Eliminar archivos',
        ];

        foreach ($archivosPermissions as $permission => $description) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        if ($this->command) {
            $this->command->info('✅ Permisos básicos de archivos creados: ' . count($archivosPermissions) . ' permisos');
        }

        // Asignar permisos a roles existentes
        $this->asignarPermisosARoles();
    }

    private function asignarPermisosARoles(): void
    {
        // Super Administrador - todos los permisos
        $superAdmin = Role::where('name', 'Super Administrador')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo([
                'archivos.ver',
                'archivos.crear',
                'archivos.editar',
                'archivos.eliminar'
            ]);
        }

        // Administrador - todos los permisos
        $admin = Role::where('name', 'Administrador')->first();
        if ($admin) {
            $admin->givePermissionTo([
                'archivos.ver',
                'archivos.crear',
                'archivos.editar',
                'archivos.eliminar'
            ]);
        }

        // Revisor Digital - ver y editar archivos
        $revisorDigital = Role::where('name', 'Revisor Digital')->first();
        if ($revisorDigital) {
            $revisorDigital->givePermissionTo([
                'archivos.ver',
                'archivos.editar'
            ]);
        }

        // Revisor Presencial - ver y editar archivos
        $revisorPresencial = Role::where('name', 'Revisor Presencial')->first();
        if ($revisorPresencial) {
            $revisorPresencial->givePermissionTo([
                'archivos.ver',
                'archivos.editar'
            ]);
        }

        // Revisor Domiciliario - ver y editar archivos
        $revisorDomiciliario = Role::where('name', 'Revisor Domiciliario')->first();
        if ($revisorDomiciliario) {
            $revisorDomiciliario->givePermissionTo([
                'archivos.ver',
                'archivos.editar'
            ]);
        }

        // Proveedor - sin permisos de archivos
        $proveedor = Role::where('name', 'Proveedor')->first();
        if ($proveedor) {
            // No se asignan permisos de archivos al proveedor
        }

        // Solicitante - sin permisos de archivos
        $solicitante = Role::where('name', 'Solicitante')->first();
        if ($solicitante) {
            // No se asignan permisos de archivos al solicitante
        }

        if ($this->command) {
            $this->command->info('✅ Permisos de archivos asignados a roles exitosamente');
        }
    }
}
