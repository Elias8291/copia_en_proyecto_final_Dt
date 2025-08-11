<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ListCitasPermissions extends Command
{
    protected $signature = 'permissions:list-citas';
    protected $description = 'Listar permisos de citas y su asignación a roles';

    public function handle()
    {
        $this->info('📋 Permisos de citas creados:');
        
        $permissions = Permission::where('name', 'like', 'citas.%')->get();
        
        if ($permissions->isEmpty()) {
            $this->warn('No se encontraron permisos de citas');
            return 1;
        }

        foreach ($permissions as $permission) {
            $this->line("   • {$permission->name}");
        }

        $this->newLine();
        $this->info('👥 Asignación de permisos por roles:');

        $roles = Role::all();
        
        foreach ($roles as $role) {
            $rolePermissions = $role->permissions()->where('name', 'like', 'citas.%')->pluck('name')->toArray();
            
            if (!empty($rolePermissions)) {
                $this->line("   {$role->name}:");
                foreach ($rolePermissions as $permission) {
                    $this->line("     - {$permission}");
                }
                $this->newLine();
            }
        }

        return 0;
    }
}
