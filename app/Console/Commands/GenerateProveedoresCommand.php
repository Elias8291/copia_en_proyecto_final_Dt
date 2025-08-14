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

    
    public function handle()
    {
        $count = (int) $this->argument('count');
        
        if ($count <= 0 || $count > 3000) {
            $this->error('El número de proveedores debe estar entre 1 y 3000');
            return 1;
        }

        try {
            $this->checkRequiredTables();
            
            $seeder = new ProveedoresCompleteSeeder();
            $seeder->setCommand($this);
            $seeder->setCount($count);
            $seeder->run();
            
            $this->info("{$count} proveedores generados");
            
        } catch (\Exception $e) {
            $this->error($e->getMessage());
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
        
        if (\App\Models\User::count() === 0) {
            throw new \Exception("No hay usuarios en la base de datos. Ejecuta 'php artisan db:seed --class=UserSeeder' primero.");
        }
    }
}
