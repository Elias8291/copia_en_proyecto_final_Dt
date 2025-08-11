<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ListRevisionesPermissions extends Command
{
    protected $signature = 'permissions:list-revisiones';
    protected $description = 'Listar permisos de revisiones y su asignación a roles';

    public function handle()
    {
        $this->info('📋 Permisos de revisiones creados:');
        
        $permissions = Permission::where('name', 'like', 'revisiones.%')->get();
        
        if ($permissions->isEmpty()) {
            $this->warn('No se encontraron permisos de revisiones');
            return 1;
        }

        foreach ($permissions as $permission) {
            $this->line("   • {$permission->name}");
        }

        $this->newLine();
        $this->info('👥 Asignación de permisos por roles:');

        $roles = Role::all();
        
        foreach ($roles as $role) {
            $rolePermissions = $role->permissions()->where('name', 'like', 'revisiones.%')->pluck('name')->toArray();
            
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
