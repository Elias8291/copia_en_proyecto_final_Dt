<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\AllPermissionsSeeder;

class CreateAllPermissions extends Command
{
    protected $signature = 'permissions:create-all';
    protected $description = 'Crear todos los permisos del sistema';

    public function handle()
    {
        $seeder = new AllPermissionsSeeder();
        $seeder->setCommand($this);
        $seeder->run();
        
        return Command::SUCCESS;
    }
}
