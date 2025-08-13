<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\RevisoresRolesSeeder;

class SetupRevisoresCitas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:revisores-citas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configurar roles y permisos para el sistema de citas de revisores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Configurando sistema de citas para revisores...');

        try {
            // Ejecutar el seeder
            $seeder = new RevisoresRolesSeeder();
            $seeder->setCommand($this);
            $seeder->run();

            $this->info('✅ Configuración completada exitosamente!');
            $this->info('📋 Roles creados: Revisor Digital, Revisor Presencial, Revisor Domiciliario');
            $this->info('🔑 Permisos asignados para gestión de citas');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error durante la configuración: ' . $e->getMessage());
            return 1;
        }
    }
}
