<?php

namespace App\Console\Commands;

use App\Models\Tramite;
use App\Services\CitaService;
use Illuminate\Console\Command;

class TestLimiteReagendamientos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:limite-reagendamientos {tramite_id? : ID del trámite a probar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el sistema de límite de reagendamientos';

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
            $this->probarLimiteReagendamientos($tramite);
        } else {
            // Probar con el primer trámite disponible
            $tramite = Tramite::first();
            if (!$tramite) {
                $this->error("No hay trámites disponibles para probar.");
                return 1;
            }
            $this->probarLimiteReagendamientos($tramite);
        }
        
        return 0;
    }
    
    private function probarLimiteReagendamientos(Tramite $tramite)
    {
        $this->info("Probando límite de reagendamientos para trámite #{$tramite->id}");
        
        $citaService = app(CitaService::class);
        
        // Obtener información inicial
        $infoInicial = $citaService->obtenerInfoReagendamientos($tramite);
        $this->info("Estado inicial:");
        $this->line("  - Puede reagendar: " . ($infoInicial['puede_reagendar'] ? 'Sí' : 'No'));
        $this->line("  - Reagendamientos usados: {$infoInicial['reagendamientos_usados']}");
        $this->line("  - Reagendamientos disponibles: {$infoInicial['reagendamientos_disponibles']}");
        
        // Intentar reagendar hasta el límite
        $intentos = 0;
        $maxIntentos = 3; // Para probar que se detiene en 2
        
        while ($intentos < $maxIntentos) {
            $intentos++;
            $this->info("\n--- Intento {$intentos} ---");
            
            try {
                $cita = $citaService->reagendarCitaTramite($tramite);
                
                if ($cita) {
                    $this->info("✅ Cita reagendada exitosamente:");
                    $this->line("  - ID: {$cita->id}");
                    $this->line("  - Fecha: {$cita->fecha_cita}");
                    $this->line("  - Estado: {$cita->estado}");
                    $this->line("  - Contador reagendamientos: {$cita->contador_reagendamientos}");
                    $this->line("  - Máximo reagendamientos: {$cita->max_reagendamientos}");
                } else {
                    $this->error("❌ No se pudo reagendar la cita.");
                    break;
                }
                
                // Verificar estado después del reagendamiento
                $infoDespues = $citaService->obtenerInfoReagendamientos($tramite);
                $this->info("Estado después del reagendamiento:");
                $this->line("  - Puede reagendar: " . ($infoDespues['puede_reagendar'] ? 'Sí' : 'No'));
                $this->line("  - Reagendamientos usados: {$infoDespues['reagendamientos_usados']}");
                $this->line("  - Reagendamientos disponibles: {$infoDespues['reagendamientos_disponibles']}");
                
                if ($infoDespues['limite_alcanzado']) {
                    $this->warn("⚠️  Límite de reagendamientos alcanzado!");
                    break;
                }
                
            } catch (\Exception $e) {
                $this->error("❌ Error en intento {$intentos}: " . $e->getMessage());
                break;
            }
        }
        
        $this->info("\n=== Resumen ===");
        $infoFinal = $citaService->obtenerInfoReagendamientos($tramite);
        $this->line("Estado final:");
        $this->line("  - Puede reagendar: " . ($infoFinal['puede_reagendar'] ? 'Sí' : 'No'));
        $this->line("  - Reagendamientos usados: {$infoFinal['reagendamientos_usados']}");
        $this->line("  - Reagendamientos disponibles: {$infoFinal['reagendamientos_disponibles']}");
        $this->line("  - Límite alcanzado: " . ($infoFinal['limite_alcanzado'] ? 'Sí' : 'No'));
    }
} 