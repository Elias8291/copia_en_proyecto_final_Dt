<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\RevisionesPermissionsSeeder;

class CreateRevisionesPermissions extends Command
{
    protected $signature = 'permissions:revisiones';
    protected $description = 'Crear permisos básicos para revisiones de trámites';

    public function handle()
    {
        $this->info('🔧 Creando permisos básicos para revisiones de trámites...');

        try {
            $seeder = new RevisionesPermissionsSeeder();
            $seeder->run();

            $this->info('✅ Permisos de revisiones creados exitosamente');
            $this->info('📋 Permisos creados:');
            $this->line('   • revisiones.ver - Ver revisiones de trámites');
            $this->line('   • revisiones.revisar - Revisar trámites');

        } catch (\Exception $e) {
            $this->error('❌ Error al crear permisos de revisiones: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
