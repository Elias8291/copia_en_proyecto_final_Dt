<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RevisoresRolesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Crear roles si no existen
        $roles = [
            'Revisor Digital',
            'Revisor Presencial', 
            'Revisor Domiciliario'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Crear permisos relacionados con citas si no existen
        $permissions = [
            'gestionar_citas_revision',
            'agendar_citas_revision',
            'ver_revisores_disponibles'
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Asignar permisos a roles
        $revisorDigital = Role::findByName('Revisor Digital');
        $revisorPresencial = Role::findByName('Revisor Presencial');
        $revisorDomiciliario = Role::findByName('Revisor Domiciliario');

        $revisorDigital->givePermissionTo([
            'gestionar_citas_revision',
            'agendar_citas_revision',
            'ver_revisores_disponibles'
        ]);

        $revisorPresencial->givePermissionTo([
            'gestionar_citas_revision',
            'agendar_citas_revision'
        ]);

        $revisorDomiciliario->givePermissionTo([
            'gestionar_citas_revision',
            'agendar_citas_revision'
        ]);

        $this->command->info('Roles y permisos para revisores creados exitosamente.');
    }
}
