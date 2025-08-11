<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RevisionesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos básicos para revisiones de trámites
        $revisionesPermissions = [
            'revisiones.ver' => 'Ver revisiones de trámites',
            'revisiones.revisar' => 'Revisar trámites',
        ];

        foreach ($revisionesPermissions as $permission => $description) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        if ($this->command) {
            $this->command->info('✅ Permisos básicos de revisiones creados: ' . count($revisionesPermissions) . ' permisos');
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
                'revisiones.ver',
                'revisiones.revisar'
            ]);
        }

        // Administrador - todos los permisos
        $admin = Role::where('name', 'Administrador')->first();
        if ($admin) {
            $admin->givePermissionTo([
                'revisiones.ver',
                'revisiones.revisar'
            ]);
        }

        // Revisor Digital - ver y revisar trámites
        $revisorDigital = Role::where('name', 'Revisor Digital')->first();
        if ($revisorDigital) {
            $revisorDigital->givePermissionTo([
                'revisiones.ver',
                'revisiones.revisar'
            ]);
        }

        // Revisor Presencial - ver y revisar trámites
        $revisorPresencial = Role::where('name', 'Revisor Presencial')->first();
        if ($revisorPresencial) {
            $revisorPresencial->givePermissionTo([
                'revisiones.ver',
                'revisiones.revisar'
            ]);
        }

        // Revisor Domiciliario - ver y revisar trámites
        $revisorDomiciliario = Role::where('name', 'Revisor Domiciliario')->first();
        if ($revisorDomiciliario) {
            $revisorDomiciliario->givePermissionTo([
                'revisiones.ver',
                'revisiones.revisar'
            ]);
        }

        // Proveedor - solo ver revisiones (no puede revisar)
        $proveedor = Role::where('name', 'Proveedor')->first();
        if ($proveedor) {
            $proveedor->givePermissionTo([
                'revisiones.ver'
            ]);
        }

        // Solicitante - solo ver revisiones (no puede revisar)
        $solicitante = Role::where('name', 'Solicitante')->first();
        if ($solicitante) {
            $solicitante->givePermissionTo([
                'revisiones.ver'
            ]);
        }

        if ($this->command) {
            $this->command->info('✅ Permisos de revisiones asignados a roles exitosamente');
        }
    }
}
