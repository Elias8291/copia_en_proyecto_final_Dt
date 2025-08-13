<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AssignSuperAdminPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Crear el rol Super Admin si no existe
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        // Obtener todos los permisos
        $allPermissions = Permission::all();

        // Asignar todos los permisos al rol Super Admin
        $superAdminRole->syncPermissions($allPermissions);

        // Buscar usuarios con correo que contenga 'admin' o 'super' y asignarles el rol
        $adminUsers = User::where('correo', 'like', '%admin%')
                         ->orWhere('correo', 'like', '%super%')
                         ->orWhere('nombre', 'like', '%admin%')
                         ->orWhere('nombre', 'like', '%super%')
                         ->get();

        foreach ($adminUsers as $user) {
            $user->assignRole($superAdminRole);
        }

        // También asignar el rol al primer usuario si no hay usuarios admin
        if ($adminUsers->isEmpty()) {
            $firstUser = User::first();
            if ($firstUser) {
                $firstUser->assignRole($superAdminRole);
            }
        }

        $this->command->info('Rol Super Admin creado con todos los permisos asignados.');
        $this->command->info('Usuarios asignados al rol Super Admin: ' . ($adminUsers->count() ?: 1));
    }
}
