<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\ProveedoresPruebaSeeder;

class CrearProveedoresPrueba extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proveedores:crear-prueba 
                            {--rfc= : RFC personalizado para los proveedores (default: EMP123456789)}
                            {--email= : Email del usuario de prueba (default: proveedor.prueba@example.com)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear proveedores de prueba con el mismo RFC pero diferentes períodos de vigencia y trámites';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Creando proveedores de prueba...');
        $this->newLine();
        
        try {
            // Ejecutar el seeder
            $seeder = new ProveedoresPruebaSeeder();
            $seeder->setCommand($this);
            $seeder->run();
            
            $this->newLine();
            $this->info('🎉 ¡Proveedores de prueba creados exitosamente!');
            $this->newLine();
            
            $this->comment('📋 Datos creados:');
            $this->line('   • 2 Proveedores con el mismo RFC pero diferentes períodos');
            $this->line('   • 1 Usuario de prueba para acceder al sistema');
            $this->line('   • 4 Trámites (1 Inscripción + 3 Renovaciones)');
            $this->line('   • Datos completos (generales, direcciones, contactos)');
            
            $this->newLine();
            $this->comment('🔍 Para probar la funcionalidad de agrupación por RFC:');
            $this->line('   1. Inicia sesión con: proveedor.prueba@example.com / password123');
            $this->line('   2. Ve a "Mi Estado" o busca el proveedor en la lista');
            $this->line('   3. Verifica que aparezcan ambos proveedores agrupados por RFC');
            $this->line('   4. Observa el historial completo de trámites por PV');
            
        } catch (\Exception $e) {
            $this->error('❌ Error al crear los proveedores de prueba:');
            $this->error($e->getMessage());
            return 1;
        }
        
        return 0;
    }
}