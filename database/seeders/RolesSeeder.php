<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles básicos si no existen
        $roles = [
            'admin' => 'Administrador',
            'user' => 'Usuario',
            'moderator' => 'Moderador'
        ];

        foreach ($roles as $name => $displayName) {
            Role::firstOrCreate(['name' => $name], [
                'name' => $name,
                'guard_name' => 'web'
            ]);
        }

        $this->command->info('Roles creados exitosamente.');
    }
} 