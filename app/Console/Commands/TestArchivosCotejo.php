<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Models\Archivo;
use App\Services\Revisiones\RevisionDigitalService;
use App\Services\CitasService;
use App\Services\NotificacionService;

class TestArchivosCotejo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:archivos-cotejo {tramite_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba la funcionalidad de preparación de archivos para cotejo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        try {
            // Verificar que el trámite existe
            $tramite = Tramite::findOrFail($tramiteId);
            $this->info("Trámite encontrado: ID {$tramite->id}");
            
            // Obtener archivos del trámite
            $archivos = Archivo::where('tramite_id', $tramiteId)
                ->with('catalogoArchivo')
                ->get();
            
            $this->info("Archivos encontrados: {$archivos->count()}");
            
            if ($archivos->isEmpty()) {
                $this->warn("No hay archivos para este trámite");
                return;
            }
            
            // Mostrar archivos originales
            $this->info("\n=== ARCHIVOS ORIGINALES ===");
            foreach ($archivos as $archivo) {
                $this->line("ID: {$archivo->id}");
                $this->line("  Nombre original: {$archivo->nombre_original}");
                $this->line("  Nombre catálogo: " . ($archivo->catalogoArchivo ? $archivo->catalogoArchivo->nombre : 'NULL'));
                $this->line("  Extensión: {$archivo->extension}");
                $this->line("  Status: {$archivo->status}");
                $this->line("---");
            }
            
            // Probar la función de preparación
            $revisionService = new RevisionDigitalService(
                app(CitasService::class),
                app(NotificacionService::class)
            );
            
            $archivosPreparados = $revisionService->prepararArchivosParaCotejo($archivos);
            
            $this->info("\n=== ARCHIVOS PREPARADOS PARA COTEJO ===");
            foreach ($archivosPreparados as $archivo) {
                $this->line("ID: {$archivo['id']}");
                $this->line("  Nombre original: {$archivo['nombre_original']}");
                $this->line("  Nombre catálogo: " . ($archivo['nombre_catalogo'] ?? 'NULL'));
                $this->line("  Extensión: {$archivo['extension']}");
                $this->line("  Status: {$archivo['status']}");
                $this->line("  Revisor: " . ($archivo['revisor'] ?? 'NULL'));
                $this->line("---");
            }
            
            $this->info("\n✅ Prueba completada exitosamente");
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
        }
    }
} 