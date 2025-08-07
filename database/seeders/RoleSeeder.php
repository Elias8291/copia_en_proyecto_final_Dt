<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->delete();

        $roles = [
            'Super Administrador',
            'Administrador',
            'Revisor Digital',
            'Revisor Presencial',
            'Revisor Domiciliario',
            'Recepcionista',
            'Consultor',
            'Proveedor',
            'Solicitante'
        ];

        foreach ($roles as $roleName) {
            Role::create([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
        }
    }
}
