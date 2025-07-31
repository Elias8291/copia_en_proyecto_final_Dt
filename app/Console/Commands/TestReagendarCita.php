<?php

namespace App\Console\Commands;

use App\Models\Tramite;
use App\Services\CitaService;
use Illuminate\Console\Command;

class TestReagendarCita extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:reagendar-cita {tramite_id? : ID del trámite a probar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba la funcionalidad de reagendamiento de citas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        if ($tramiteId) {
            $tramite = Tramite::find($tramiteId);
            if (!$tramite) {
                $this->error("Trámite con ID {$tramiteId} no encontrado.");
                return 1;
            }
            $this->probarReagendamiento($tramite);
        } else {
            // Probar con el primer trámite disponible
            $tramite = Tramite::first();
            if (!$tramite) {
                $this->error("No hay trámites disponibles para probar.");
                return 1;
            }
            $this->probarReagendamiento($tramite);
        }
        
        return 0;
    }
    
    private function probarReagendamiento(Tramite $tramite)
    {
        $this->info("Probando reagendamiento para trámite #{$tramite->id}");
        
        $citaService = app(CitaService::class);
        
        // Verificar si existe cita activa
        $citaExistente = $citaService->obtenerCitaActiva($tramite);
        
        if ($citaExistente) {
            $this->info("Cita existente encontrada:");
            $this->line("  - ID: {$citaExistente->id}");
            $this->line("  - Fecha: {$citaExistente->fecha_cita}");
            $this->line("  - Estado: {$citaExistente->estado}");
            $this->line("  - Tipo: {$citaExistente->tipo_cita}");
        } else {
            $this->info("No hay cita activa para este trámite.");
        }
        
        // Intentar reagendar
        $this->info("\nIntentando reagendar cita...");
        $citaActualizada = $citaService->reagendarCitaTramite($tramite);
        
        if ($citaActualizada) {
            $this->info("✅ Cita reagendada exitosamente:");
            $this->line("  - ID: {$citaActualizada->id}");
            $this->line("  - Fecha: {$citaActualizada->fecha_cita}");
            $this->line("  - Estado: {$citaActualizada->estado}");
            $this->line("  - Tipo: {$citaActualizada->tipo_cita}");
            $this->line("  - Observaciones: {$citaActualizada->observaciones}");
            
            if ($citaExistente && $citaExistente->id === $citaActualizada->id) {
                $this->info("  - ✅ La cita existente fue actualizada (no se creó una nueva)");
            }
        } else {
            $this->error("❌ No se pudo reagendar la cita.");
        }
        
        // Verificar cita activa después del reagendamiento
        $citaActivaDespues = $citaService->obtenerCitaActiva($tramite);
        if ($citaActivaDespues) {
            $this->info("\n✅ Cita activa después del reagendamiento:");
            $this->line("  - ID: {$citaActivaDespues->id}");
            $this->line("  - Fecha: {$citaActivaDespues->fecha_cita}");
            $this->line("  - Estado: {$citaActivaDespues->estado}");
        } else {
            $this->error("\n❌ No se encontró cita activa después del reagendamiento.");
        }
    }
} 