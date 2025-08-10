<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\ArchivosPermissionsSeeder;

class CreateArchivosPermissions extends Command
{
    protected $signature = 'permissions:archivos';
    protected $description = 'Crear permisos básicos para archivos (CRUD)';

    public function handle()
    {
        $this->info('🔧 Creando permisos básicos para archivos...');

        try {
            $seeder = new ArchivosPermissionsSeeder();
            $seeder->run();

            $this->info('✅ Permisos de archivos creados exitosamente');
            $this->info('📋 Permisos creados:');
            $this->line('   • archivos.ver - Ver archivos');
            $this->line('   • archivos.crear - Crear archivos');
            $this->line('   • archivos.editar - Editar archivos');
            $this->line('   • archivos.eliminar - Eliminar archivos');

        } catch (\Exception $e) {
            $this->error('❌ Error al crear permisos de archivos: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
