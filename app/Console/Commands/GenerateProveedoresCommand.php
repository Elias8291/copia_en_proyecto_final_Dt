<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\ProveedoresCompleteSeeder;

class GenerateProveedoresCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:proveedores {count=5 : Número de proveedores a generar (máximo 3000)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera proveedores aleatorios con todos sus datos asociados (trámites, direcciones, contactos, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');
        
        if ($count <= 0 || $count > 3000) {
            $this->error('El número de proveedores debe estar entre 1 y 3000');
            return 1;
        }

        // Mostrar advertencia para cantidades grandes
        if ($count > 100) {
            if (!$this->confirm("Vas a generar {$count} proveedores. Esto puede tomar varios minutos. ¿Continuar?")) {
                $this->info('Operación cancelada.');
                return 0;
            }
        }

        $this->info("Generando {$count} proveedores con datos completos...");
        
        try {
            // Verificar que existan las tablas necesarias
            $this->checkRequiredTables();
            
            // Ejecutar el seeder personalizado
            $seeder = new ProveedoresCompleteSeeder();
            $seeder->setCommand($this);
            $seeder->setCount($count);
            $seeder->run();
            
            $this->info("\n✅ ¡Proceso completado exitosamente!");
            $this->info("Se han generado {$count} proveedores con:");
            $this->line("  • Datos básicos del proveedor");
            $this->line("  • Trámite asociado");
            $this->line("  • Datos generales");
            $this->line("  • Dirección completa");
            $this->line("  • Información de contacto");
            $this->line("  • Actividades económicas");
            $this->line("  • Accionistas (para personas morales)");
            $this->line("  • Apoderados legales con instrumentos notariales (para personas morales)");
            $this->line("  • Datos constitutivos con instrumentos notariales (para personas morales)");
            
        } catch (\Exception $e) {
            $this->error("Error al generar proveedores: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }

    private function checkRequiredTables()
    {
        $tables = [
            'users', 'proveedores', 'tramites', 'datos_generales', 
            'direcciones', 'contactos', 'actividades', 'accionistas',
            'actividad', 'estados', 'municipios', 'asentamientos'
        ];
        
        foreach ($tables as $table) {
            if (!\Schema::hasTable($table)) {
                throw new \Exception("La tabla '{$table}' no existe. Ejecuta las migraciones primero.");
            }
        }
        
        // Verificar que existan datos mínimos
        if (\App\Models\User::count() === 0) {
            throw new \Exception("No hay usuarios en la base de datos. Ejecuta 'php artisan db:seed --class=UserSeeder' primero.");
        }
        
        if (\DB::table('actividad')->count() === 0) {
            $this->warn("No hay actividades económicas. Ejecuta 'php artisan db:seed --class=ActividadesSeeder' para mejor calidad de datos.");
        }
        
        if (\DB::table('estados')->count() === 0) {
            $this->warn("No hay estados cargados. Los datos de ubicación pueden ser limitados.");
        }
    }
}
