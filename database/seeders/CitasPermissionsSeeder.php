<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CitasPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos básicos para citas
        $citasPermissions = [
            'citas.ver' => 'Ver citas',
            'citas.crear' => 'Crear citas',
            'citas.editar' => 'Editar citas',
            'citas.eliminar' => 'Eliminar citas',
        ];

        foreach ($citasPermissions as $permission => $description) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        if ($this->command) {
            $this->command->info('✅ Permisos básicos de citas creados: ' . count($citasPermissions) . ' permisos');
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
                'citas.ver',
                'citas.crear',
                'citas.editar',
                'citas.eliminar'
            ]);
        }

        // Administrador - todos los permisos
        $admin = Role::where('name', 'Administrador')->first();
        if ($admin) {
            $admin->givePermissionTo([
                'citas.ver',
                'citas.crear',
                'citas.editar',
                'citas.eliminar'
            ]);
        }

        // Revisor Digital - ver y editar citas
        $revisorDigital = Role::where('name', 'Revisor Digital')->first();
        if ($revisorDigital) {
            $revisorDigital->givePermissionTo([
                'citas.ver',
                'citas.editar'
            ]);
        }

        // Revisor Presencial - ver y editar citas
        $revisorPresencial = Role::where('name', 'Revisor Presencial')->first();
        if ($revisorPresencial) {
            $revisorPresencial->givePermissionTo([
                'citas.ver',
                'citas.editar'
            ]);
        }

        // Revisor Domiciliario - ver y editar citas
        $revisorDomiciliario = Role::where('name', 'Revisor Domiciliario')->first();
        if ($revisorDomiciliario) {
            $revisorDomiciliario->givePermissionTo([
                'citas.ver',
                'citas.editar'
            ]);
        }

        // Proveedor - solo ver citas (no puede crear/editar/eliminar)
        $proveedor = Role::where('name', 'Proveedor')->first();
        if ($proveedor) {
            $proveedor->givePermissionTo([
                'citas.ver'
            ]);
        }

        // Solicitante - solo ver citas (no puede crear/editar/eliminar)
        $solicitante = Role::where('name', 'Solicitante')->first();
        if ($solicitante) {
            $solicitante->givePermissionTo([
                'citas.ver'
            ]);
        }

        if ($this->command) {
            $this->command->info('✅ Permisos de citas asignados a roles exitosamente');
        }
    }
}
