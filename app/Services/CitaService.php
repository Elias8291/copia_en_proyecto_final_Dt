<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Tramite;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CitaService
{
    protected $notificacionService;

    public function __construct(NotificacionService $notificacionService)
    {
        $this->notificacionService = $notificacionService;
    }

    /**
     * Agenda una cita para cotejo de documentos
     */
    public function agendarCitaCotejo(Tramite $tramite, string $fechaCita, ?int $atendidoPor = null): Cita
    {
        try {
            // Verificar que no exista ya una cita para este trámite
            $citaExistente = Cita::where('tramite_id', $tramite->id)
                ->where('tipo_cita', 'Cotejo')
                ->where('estado', '!=', 'Cancelada')
                ->first();

            if ($citaExistente) {
                throw new \Exception('Ya existe una cita de cotejo para este trámite');
            }

            $cita = Cita::create([
                'tramite_id' => $tramite->id,
                'proveedor_id' => $tramite->proveedor_id,
                'fecha_cita' => $fechaCita,
                'tipo_cita' => 'Cotejo',
                'estado' => 'Agendada',
                'atendido_por' => $atendidoPor
            ]);

            // Notificar al usuario sobre la nueva cita
            $usuarioId = $tramite->proveedor->user->id;
            $fechaFormateada = Carbon::parse($fechaCita)->format('d/m/Y H:i');
            
            $this->notificacionService->notificarNuevaCita(
                $usuarioId,
                $tramite->id,
                $fechaFormateada
            );

            Log::info('Cita de cotejo agendada exitosamente', [
                'tramite_id' => $tramite->id,
                'cita_id' => $cita->id,
                'fecha_cita' => $fechaCita
            ]);

            return $cita;
        } catch (\Exception $e) {
            Log::error('Error al agendar cita de cotejo', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Obtiene las citas de un trámite
     */
    public function obtenerCitasTramite(int $tramiteId)
    {
        return Cita::where('tramite_id', $tramiteId)
            ->orderBy('fecha_cita', 'desc')
            ->get();
    }

    /**
     * Actualiza el estado de una cita
     */
    public function actualizarEstadoCita(int $citaId, string $nuevoEstado, ?string $observaciones = null): bool
    {
        try {
            $cita = Cita::findOrFail($citaId);
            $cita->estado = $nuevoEstado;
            
            if ($observaciones) {
                $cita->observaciones = $observaciones;
            }
            
            $cita->save();

            Log::info('Estado de cita actualizado', [
                'cita_id' => $citaId,
                'nuevo_estado' => $nuevoEstado
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error al actualizar estado de cita', [
                'cita_id' => $citaId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Cancela una cita
     */
    public function cancelarCita(int $citaId, string $motivo = null): bool
    {
        try {
            $cita = Cita::findOrFail($citaId);
            $cita->estado = 'Cancelada';
            $cita->observaciones = $motivo;
            $cita->save();

            // Notificar cancelación
            $usuarioId = $cita->proveedor->user->id;
            $this->notificacionService->crearNotificacion(
                $usuarioId,
                $cita->tramite_id,
                'Cita',
                'Cita cancelada',
                "Su cita programada para el día " . Carbon::parse($cita->fecha_cita)->format('d/m/Y H:i') . " ha sido cancelada." . ($motivo ? " Motivo: {$motivo}" : "")
            );

            Log::info('Cita cancelada', [
                'cita_id' => $citaId,
                'motivo' => $motivo
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error al cancelar cita', [
                'cita_id' => $citaId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Obtiene las citas pendientes de un proveedor
     */
    public function obtenerCitasPendientesProveedor(int $proveedorId)
    {
        return Cita::where('proveedor_id', $proveedorId)
            ->where('estado', 'Agendada')
            ->where('fecha_cita', '>=', now())
            ->orderBy('fecha_cita', 'asc')
            ->get();
    }

    /**
     * Verifica disponibilidad de horario para una fecha
     */
    public function verificarDisponibilidad(string $fecha, int $duracionMinutos = 60): bool
    {
        $fechaInicio = Carbon::parse($fecha);
        $fechaFin = $fechaInicio->copy()->addMinutes($duracionMinutos);

        $citasExistentes = Cita::where('estado', 'Agendada')
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_cita', [$fechaInicio, $fechaFin])
                    ->orWhere(function ($q) use ($fechaInicio, $fechaFin) {
                        $q->where('fecha_cita', '<', $fechaInicio)
                            ->whereRaw('DATE_ADD(fecha_cita, INTERVAL 60 MINUTE) > ?', [$fechaInicio]);
                    });
            })
            ->count();

        return $citasExistentes === 0;
    }
} 