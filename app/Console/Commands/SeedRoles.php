<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\RolesSeeder;

class SeedRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed basic roles and permissions for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding roles and permissions...');
        
        $seeder = new RolesSeeder();
        $seeder->run();
        
        $this->info('Roles and permissions seeded successfully!');
        
        return Command::SUCCESS;
    }
}
