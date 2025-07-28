<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Services\CitaService;
use App\Models\DiaInhabil;
use Carbon\Carbon;

class TestCitaAgendamiento extends Command
{
    protected $signature = 'test:cita-agendamiento {tramite_id}';
    protected $description = 'Probar el agendamiento automático de citas';

    public function handle()
    {
        $tramiteId = $this->argument('tramite_id');
        
        $tramite = Tramite::with('proveedor')->find($tramiteId);
        
        if (!$tramite) {
            $this->error("Trámite #{$tramiteId} no encontrado");
            return 1;
        }

        $this->info("Probando agendamiento de cita para trámite #{$tramiteId}");
        $this->info("Proveedor: " . ($tramite->proveedor->rfc ?? 'N/A'));
        $this->info("Estado actual: " . $tramite->estado);

        // Verificar días hábiles
        $fechaActual = Carbon::now();
        $this->info("Fecha actual: " . $fechaActual->format('Y-m-d H:i:s'));
        
        $diaHabil = DiaInhabil::proximoDiaHabil($fechaActual);
        $this->info("Próximo día hábil: " . $diaHabil->format('Y-m-d'));
        
        $esHabil = DiaInhabil::esHabil($fechaActual);
        $this->info("¿Hoy es hábil? " . ($esHabil ? 'Sí' : 'No'));

        // Probar agendamiento
        $citaService = app(CitaService::class);
        $cita = $citaService->agendarCitaCotejo($tramite);

        if ($cita) {
            $this->info("✅ Cita agendada exitosamente!");
            $this->info("ID de cita: " . $cita->id);
            $this->info("Fecha: " . $cita->fecha_cita->format('Y-m-d H:i:s'));
            $this->info("Tipo: " . $cita->tipo_cita);
            $this->info("Estado: " . $cita->estado);
        } else {
            $this->error("❌ No se pudo agendar la cita");
        }

        return 0;
    }
} 