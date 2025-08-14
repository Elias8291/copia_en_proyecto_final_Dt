<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use Carbon\Carbon;
use App\Models\Notificacion;

class NotificarCorreccionesPendientes extends Command
{
    protected $signature = 'tramites:notificar-correcciones-pendientes {--dias=3 : Días de espera para notificar} {--dry-run : Solo mostrar cuántos se notificarían}';

    public function handle()
    {
        $hoy = Carbon::today();
        $diasEspera = (int) $this->option('dias');
        $fechaLimite = $hoy->copy()->subDays($diasEspera);

        $queryTramitesVencidos = Tramite::query()
            ->where('status', 'Para_Correccion')
            ->where('updated_at', '<=', $hoy->copy()->subDays(3))
            ->with('proveedor.usuario');

        if ($this->option('dry-run')) {
            $this->info((string) $queryTramitesVencidos->count());
            return 0;
        }

        $tramitesVencidos = $queryTramitesVencidos->get();
        $tramitesCancelados = 0;

        foreach ($tramitesVencidos as $tramite) {
            if ($tramite->proveedor->usuario) {
                $diasPendiente = $hoy->diffInDays($tramite->updated_at);
                
                $tramite->update([
                    'status' => 'Cancelado',
                    'observaciones' => 'Trámite cancelado por falta de correcciones después de 3 días'
                ]);

                $tramite->citas()->where('estado', 'Asignada')->update(['estado' => 'Cancelada']);

                Notificacion::crear(
                    $tramite->proveedor->usuario->id,
                    'error',
                    'Trámite Cancelado',
                    "Su trámite #{$tramite->id} ha sido cancelado por no realizar las correcciones solicitadas después de {$diasPendiente} días. Si desea continuar, deberá iniciar un nuevo trámite.",
                    [
                        'tramite_id' => $tramite->id,
                        'dias_pendiente' => $diasPendiente,
                        'motivo_cancelacion' => 'Falta de correcciones'
                    ]
                );
                
                $this->info("Trámite cancelado - Usuario ID: {$tramite->proveedor->usuario->id}, Trámite ID: {$tramite->id} - pendiente {$diasPendiente} días");
                $tramitesCancelados++;
            }
        }

        $queryRecordatorios = Tramite::query()
            ->where('status', 'Para_Correccion')
            ->where('updated_at', '>', $hoy->copy()->subDays(3))
            ->where('updated_at', '<=', $fechaLimite)
            ->with('proveedor.usuario');

        $tramitesRecordatorio = $queryRecordatorios->get();

        foreach ($tramitesRecordatorio as $tramite) {
            if ($tramite->proveedor->usuario) {
                $diasPendiente = $hoy->diffInDays($tramite->updated_at);
                
                Notificacion::crear(
                    $tramite->proveedor->usuario->id,
                    'Tramite',
                    'Correcciones pendientes',
                    "Su trámite #{$tramite->id} requiere correcciones desde hace {$diasPendiente} días. Complete las correcciones solicitadas para continuar con el proceso. " . ($tramite->observaciones ? "Observaciones: {$tramite->observaciones}" : ''),
                    [
                        'tramite_id' => $tramite->id,
                        'dias_pendiente' => $diasPendiente,
                        'observaciones' => $tramite->observaciones
                    ]
                );
                
                $this->info("Recordatorio enviado - Usuario ID: {$tramite->proveedor->usuario->id}, Trámite ID: {$tramite->id} - pendiente {$diasPendiente} días");
            }
        }

        $this->info((string) ($tramitesCancelados + $tramitesRecordatorio->count()));
        return 0;
    }
}
