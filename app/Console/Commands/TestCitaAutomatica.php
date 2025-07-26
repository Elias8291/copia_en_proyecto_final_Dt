<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\CitaService;
use App\Models\DiaInhabil;
use Carbon\Carbon;

class TestCitaAutomatica extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cita-automatica {tramite_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba el sistema de citas automáticas';

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
            $this->probarCitaParaTramite($tramite);
        } else {
            $this->probarSistemaCompleto();
        }

        return 0;
    }

    private function probarCitaParaTramite(Tramite $tramite)
    {
        $this->info("Probando cita automática para trámite #{$tramite->id}");
        
        $citaService = app(CitaService::class);
        $cita = $citaService->agendarCitaCotejo($tramite);

        if ($cita) {
            $this->info("✅ Cita agendada exitosamente:");
            $this->line("   - ID: {$cita->id}");
            $this->line("   - Fecha: {$cita->fecha_cita}");
            $this->line("   - Tipo: {$cita->tipo_cita}");
            $this->line("   - Estado: {$cita->estado}");
        } else {
            $this->error("❌ No se pudo agendar la cita");
        }
    }

    private function probarSistemaCompleto()
    {
        $this->info("=== PRUEBA DEL SISTEMA DE CITAS AUTOMÁTICAS ===");

        // 1. Verificar días inhábiles
        $this->info("\n1. Verificando días inhábiles...");
        $diasInhabiles = DiaInhabil::obtenerDiasInhabilesActivos();
        $this->line("   - Total días inhábiles: {$diasInhabiles->count()}");

        // 2. Verificar días hábiles próximos
        $this->info("\n2. Próximos días hábiles:");
        $fechaActual = Carbon::now();
        for ($i = 0; $i < 10; $i++) {
            $fecha = $fechaActual->copy()->addDays($i);
            $esHabil = DiaInhabil::esHabil($fecha);
            $estado = $esHabil ? "✅ Hábil" : "❌ Inhábil";
            $this->line("   - {$fecha->format('Y-m-d')} ({$fecha->format('l')}): {$estado}");
        }

        // 3. Probar agendación de cita
        $this->info("\n3. Probando agendación de cita...");
        $tramite = Tramite::first();
        if ($tramite) {
            $this->probarCitaParaTramite($tramite);
        } else {
            $this->warn("No hay trámites disponibles para probar");
        }

        // 4. Verificar horarios disponibles
        $this->info("\n4. Horarios disponibles para hoy:");
        $citaService = app(CitaService::class);
        $horarios = $citaService->obtenerHorariosDisponibles(Carbon::today());
        
        if (empty($horarios)) {
            $this->line("   - No hay horarios disponibles para hoy");
        } else {
            foreach ($horarios as $horario) {
                $this->line("   - {$horario->format('H:i')}");
            }
        }

        $this->info("\n=== PRUEBA COMPLETADA ===");
    }
} 