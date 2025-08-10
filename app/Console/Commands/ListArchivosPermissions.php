<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ListArchivosPermissions extends Command
{
    protected $signature = 'permissions:list-archivos';
    protected $description = 'Listar permisos de archivos y su asignación a roles';

    public function handle()
    {
        $this->info('📋 Permisos de archivos creados:');
        
        $permissions = Permission::where('name', 'like', 'archivos.%')->get();
        
        if ($permissions->isEmpty()) {
            $this->warn('No se encontraron permisos de archivos');
            return 1;
        }

        foreach ($permissions as $permission) {
            $this->line("   • {$permission->name}");
        }

        $this->newLine();
        $this->info('👥 Asignación de permisos por roles:');

        $roles = Role::all();
        
        foreach ($roles as $role) {
            $rolePermissions = $role->permissions()->where('name', 'like', 'archivos.%')->pluck('name')->toArray();
            
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
