<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\CitasPermissionsSeeder;

class CreateCitasPermissions extends Command
{
    protected $signature = 'permissions:citas';
    protected $description = 'Crear permisos básicos para citas (CRUD)';

    public function handle()
    {
        $this->info('🔧 Creando permisos básicos para citas...');

        try {
            $seeder = new CitasPermissionsSeeder();
            $seeder->run();

            $this->info('✅ Permisos de citas creados exitosamente');
            $this->info('📋 Permisos creados:');
            $this->line('   • citas.ver - Ver citas');
            $this->line('   • citas.crear - Crear citas');
            $this->line('   • citas.editar - Editar citas');
            $this->line('   • citas.eliminar - Eliminar citas');

        } catch (\Exception $e) {
            $this->error('❌ Error al crear permisos de citas: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
