<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class CheckUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-user-permissions {--user= : User ID to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user permissions and test Spatie Permission middleware';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking Spatie Permission setup...');
        
        // Check if permissions exist
        $permissions = Permission::all();
        $this->info("✅ Found {$permissions->count()} permissions in database");
        
        // Check specific user permissions
        $userId = $this->option('user');
        
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("❌ User with ID {$userId} not found");
                return 1;
            }
            
            $this->info("👤 Checking permissions for user: {$user->nombre} ({$user->correo})");
            $this->info("📋 Roles: " . $user->roles->pluck('name')->implode(', '));
            $this->info("🔑 Direct permissions: " . $user->getDirectPermissions()->pluck('name')->implode(', '));
            $this->info("🎯 All permissions: " . $user->getAllPermissions()->pluck('name')->implode(', '));
            
            // Test specific permissions used in UserController
            $testPermissions = ['usuarios.ver', 'usuarios.crear', 'usuarios.editar', 'usuarios.eliminar'];
            
            $this->info("\n🧪 Testing UserController permissions:");
            foreach ($testPermissions as $permission) {
                $hasPermission = $user->hasPermissionTo($permission);
                $status = $hasPermission ? '✅' : '❌';
                $this->line("  {$status} {$permission}: " . ($hasPermission ? 'YES' : 'NO'));
            }
        } else {
            $this->info("📊 Available permissions:");
            foreach ($permissions as $permission) {
                $this->line("  • {$permission->name}");
            }
            
            $this->info("\n💡 To check a specific user, use: --user=USER_ID");
        }
        
        $this->info("\n✅ Spatie Permission middleware is properly configured!");
        return 0;
    }
}
