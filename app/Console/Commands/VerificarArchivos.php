<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Archivo;
use Illuminate\Support\Facades\Storage;

class VerificarArchivos extends Command
{
    protected $signature = 'archivos:verificar';
    protected $description = 'Verifica el estado de los archivos en la base de datos y storage';

    public function handle()
    {
        $this->info('Verificando archivos...');
        
        $archivos = Archivo::all();
        $encontrados = 0;
        $perdidos = 0;
        
        foreach ($archivos as $archivo) {
            $posiblesRutas = [
                $archivo->ruta,
                'public/' . $archivo->ruta,
                'tramites/' . basename($archivo->ruta),
                'public/tramites/' . basename($archivo->ruta),
            ];
            
            $encontrado = false;
            $rutaEncontrada = null;
            
            foreach ($posiblesRutas as $ruta) {
                if (Storage::exists($ruta)) {
                    $encontrado = true;
                    $rutaEncontrada = $ruta;
                    break;
                }
            }
            
            if ($encontrado) {
                $this->line("✓ ID {$archivo->id}: {$archivo->nombre_original} -> {$rutaEncontrada}");
                $encontrados++;
            } else {
                $this->error("✗ ID {$archivo->id}: {$archivo->nombre_original} -> NO ENCONTRADO");
                $this->line("  Ruta en BD: {$archivo->ruta}");
                $perdidos++;
            }
        }
        
        $this->info("\nResumen:");
        $this->info("Archivos encontrados: {$encontrados}");
        $this->error("Archivos perdidos: {$perdidos}");
        $this->info("Total: " . $archivos->count());
    }
}
