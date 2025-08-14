<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Models\Notificacion;
use App\Services\CitasService;
use Carbon\Carbon;

class ProcesarCitasVencidas extends Command
{
    protected $signature = 'citas:procesar-vencidas {--dry-run}';

    public function handle()
    {
        $ahora = Carbon::now();

        $tramites = Tramite::where('status', 'Revision_Digital')
            ->whereHas('citas', fn($q) => $q->where('fecha_cita', '<', $ahora))
            ->with('citas', 'proveedor.usuario')
            ->get();

        if ($tramites->isEmpty()) return;

        foreach ($tramites as $tramite) {
            $cita = $tramite->citas->first(); 
            if (!$cita) continue;
            
            $intentos = $cita->intento ?? 1;

            if ($this->option('dry-run')) {
                $this->info((string) $tramite->id);
                continue;
            }

            if ($intentos >= 2) {
                $this->cancelar($tramite, $cita);
            } else {
                $this->reagendar($tramite, $cita);
            }
        }
    }

    private function reagendar($tramite, $cita)
    {
        try {
            $citasService = app(CitasService::class);
            $resultado = $citasService->reagendarCita($cita->id);
            
            if ($resultado['success']) {
                $this->notificar($tramite, 'reagendado');
            } else {
                $this->cancelar($tramite, $cita);
            }
        } catch (\Exception $e) {
            $this->cancelar($tramite, $cita);
        }
    }

    private function cancelar($tramite, $cita)
    {
        $tramite->update(['status' => 'Cancelado', 'observaciones' => 'No asistió a la cita']);
        $cita->update(['estado' => 'Cancelada']);
        $this->notificar($tramite, 'cancelado');
    }

    private function notificar($tramite, $tipo)
    {
        if (!$tramite->proveedor->usuario) return;

        $usuarioId = $tramite->proveedor->usuario->id;
        
        if ($tipo === 'cancelado') {
            $titulo = 'Trámite Cancelado';
            $mensaje = 'Su trámite ha sido cancelado por no asistir a la cita';
        } else {
            $titulo = 'Cita Reagendada';
            $mensaje = 'Su cita ha sido reagendada para el próximo día disponible';
        }
        
        Notificacion::crear(
            $usuarioId,
            'Tramite',
            $titulo,
            $mensaje,
            ['tramite_id' => $tramite->id]
        );
        
        $this->info("Notificación enviada al usuario ID: {$usuarioId} para trámite ID: {$tramite->id} - {$tipo}");
    }
}
