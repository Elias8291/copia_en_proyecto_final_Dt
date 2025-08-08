<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignUserRole extends Command
{
    protected $signature = 'user:assign-role {user_id} {role_name}';
    protected $description = 'Assign a role to a user';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $roleName = $this->argument('role_name');

        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }

        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role '{$roleName}' not found");
            return 1;
        }

        $user->assignRole($role);
        $this->info("Role '{$roleName}' assigned to user '{$user->nombre}' successfully");

        return 0;
    }
} 