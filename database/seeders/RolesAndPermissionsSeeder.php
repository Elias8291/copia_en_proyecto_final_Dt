<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::where('name', 'Super Administrador')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::all());
        }

        $admin = Role::where('name', 'Administrador')->first();
        if ($admin) {
            $admin->givePermissionTo([
                'usuarios.ver',
                'usuarios.crear',
                'usuarios.editar',
                'usuarios.asignar_roles',
                'usuarios.ver_propio',
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
                'roles.ver',
                'roles.ver_permisos',
            ]);
        }

        $revisorDigital = Role::where('name', 'Revisor Digital')->first();
        if ($revisorDigital) {
            $revisorDigital->givePermissionTo([
                'usuarios.ver_propio',
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
                'roles.ver',
            ]);
        }

        $revisorPresencial = Role::where('name', 'Revisor Presencial')->first();
        if ($revisorPresencial) {
            $revisorPresencial->givePermissionTo([
                'usuarios.ver_propio',
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
                'roles.ver',
            ]);
        }

        $revisorDomiciliario = Role::where('name', 'Revisor Domiciliario')->first();
        if ($revisorDomiciliario) {
            $revisorDomiciliario->givePermissionTo([
                'usuarios.ver_propio',
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
                'roles.ver',
            ]);
        }



        $proveedor = Role::where('name', 'Proveedor')->first();
        if ($proveedor) {
            $proveedor->givePermissionTo([
                'usuarios.ver_propio',
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
            ]);
        }

        $solicitante = Role::where('name', 'Solicitante')->first();
        if ($solicitante) {
            $solicitante->givePermissionTo([
                'usuarios.ver_propio',
                'usuarios.editar_propio',
                'usuarios.cambiar_password',
            ]);
        }

        $this->command->info('✅ Permisos asignados a roles exitosamente');
    }
} 