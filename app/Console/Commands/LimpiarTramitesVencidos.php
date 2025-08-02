<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tramite;
use App\Models\Notificacion;
use App\Mail\TramiteRechazado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class LimpiarTramitesVencidos extends Command
{
    protected $signature = 'tramites:cancelar {--dry-run}';
    protected $description = 'Reagenda o rechaza trámites con citas vencidas por fecha u hora';

    public function handle()
    {
        $ahora = Carbon::now();

        $tramites = Tramite::where('estado', 'Por_Cotejar')
            ->whereHas('cita', fn($q) => $q->where('fecha_cita', '<', $ahora))
            ->with('cita', 'proveedor.user')
            ->get();

        if ($tramites->isEmpty()) return $this->info("No hay trámites vencidos.");

        foreach ($tramites as $t) {
            $reagendado = $t->cita->contador_reagendamientos ?? 0;

            if ($this->option('dry-run')) {
                $this->line("#{$t->id} se " . ($reagendado ? 'rechazaría' : 'reagendaría'));
                continue;
            }

            $reagendado ? $this->rechazar($t, 'No asistió a la segunda cita') : $this->reagendar($t);
        }
    }

    private function reagendar($t)
    {
        try {
            $cita = app(\App\Services\CitaService::class)->reagendarCitaTramite($t);
            if (!$cita) throw new \Exception('No se pudo reagendar');
            $this->notificar($t, 'reagendado');
        } catch (\Exception $e) {
            $this->rechazar($t, 'Alcanzó el límite de reagendamientos');
        }
    }

    private function rechazar($t, $motivo)
    {
        $t->update(['estado' => 'Rechazado', 'observaciones' => $motivo]);
        $t->cita->update(['estado' => 'Cancelada']);
        $this->notificar($t, 'rechazado');
    }

    private function notificar($t, $tipo)
    {
        $u = $t->proveedor->user;
        if (!$u->correo) return;

        Notificacion::create([
            'usuario_id' => $u->id,
            'tramite_id' => $t->id,
            'tipo' => 'Tramite',
            'titulo' => 'Actualización de Trámite',
            'mensaje' => ucfirst($tipo)
        ]);

        Mail::to($u->correo)->send(new TramiteRechazado($t, $tipo));
        $this->info("#{$t->id} {$tipo} ✓");
    }
}
