<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cita;
use Carbon\Carbon;
use App\Models\Notificacion;

class NotificarRecordatoriosCitas extends Command
{
    protected $signature = 'citas:notificar-recordatorios {--horas=24 : Horas de anticipación para notificar} {--dry-run : Solo mostrar cuántos se notificarían}';

    public function handle()
    {
        $ahora = Carbon::now();
        $horasAnticipacion = (int) $this->option('horas');
        $fechaLimite = $ahora->copy()->addHours($horasAnticipacion);

        $query = Cita::query()
            ->where('estado', 'Asignada')
            ->where('fecha_cita', '>', $ahora)
            ->where('fecha_cita', '<=', $fechaLimite)
            ->with(['tramite.proveedor.usuario', 'asignadoA']);

        if ($this->option('dry-run')) {
            $conteo = $query->count();
            $this->info((string) $conteo);
            return 0;
        }

        $citas = $query->get();

        if ($citas->isEmpty()) {
            $this->info('0');
            return 0;
        }

        foreach ($citas as $cita) {
            if ($cita->tramite->proveedor->usuario) {
                $horasRestantes = $ahora->diffInHours($cita->fecha_cita);
                $fechaFormateada = $cita->fecha_cita->format('d/m/Y H:i');
                $revisor = $cita->asignadoA->nombre ?? 'Revisor asignado';
                
                $titulo = $horasRestantes <= 2 ? 'Cita en 2 horas' : 'Recordatorio de cita';
                $mensaje = $this->generarMensajeRecordatorio($cita, $horasRestantes, $fechaFormateada, $revisor);
                
                Notificacion::crear(
                    $cita->tramite->proveedor->usuario->id,
                    'Cita',
                    $titulo,
                    $mensaje,
                    [
                        'tramite_id' => $cita->tramite->id,
                        'cita_id' => $cita->id,
                        'fecha_cita' => $cita->fecha_cita->format('Y-m-d H:i:s'),
                        'horas_restantes' => $horasRestantes
                    ]
                );
                
                $this->info("Recordatorio enviado al usuario ID: {$cita->tramite->proveedor->usuario->id} para cita ID: {$cita->id} - en {$horasRestantes} horas");
            }
        }

        $this->info((string) $citas->count());
        return 0;
    }

    private function generarMensajeRecordatorio($cita, $horasRestantes, $fechaFormateada, $revisor)
    {
        $tipoCita = $cita->tipo_cita;
        $documentosRequeridos = $this->obtenerDocumentosRequeridos($tipoCita);
        
        if ($horasRestantes <= 2) {
            return "¡IMPORTANTE! Su cita {$tipoCita} está programada para hoy a las {$fechaFormateada} con {$revisor}. " .
                   "Documentos requeridos: {$documentosRequeridos}. Por favor, asista puntualmente.";
        } else {
            return "Recordatorio: Su cita {$tipoCita} está programada para mañana a las {$fechaFormateada} con {$revisor}. " .
                   "Documentos requeridos: {$documentosRequeridos}. Confirme su asistencia.";
        }
    }

    private function obtenerDocumentosRequeridos($tipoCita)
    {
        return match($tipoCita) {
            'Presencial' => 'INE, RFC, documentos del trámite',
            'Domiciliaria' => 'INE, RFC, documentos del trámite, comprobante de domicilio',
            'Digital' => 'Documentos digitalizados del trámite',
            default => 'Documentos del trámite'
        };
    }
}
