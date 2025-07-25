<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\Tramite;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificacionService
{
    /**
     * Crea una notificación para un usuario
     */
    public function crearNotificacion(
        int $usuarioId,
        int $tramiteId,
        string $tipo,
        string $titulo,
        string $mensaje
    ): Notificacion {
        try {
            return Notificacion::create([
                'usuario_id' => $usuarioId,
                'tramite_id' => $tramiteId,
                'tipo' => $tipo,
                'titulo' => $titulo,
                'mensaje' => $mensaje,
                'leida' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear notificación', [
                'usuario_id' => $usuarioId,
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Notifica cambio de estado de trámite
     */
    public function notificarCambioEstado(Tramite $tramite, string $estadoAnterior, string $nuevoEstado): void
    {
        try {
            $usuario = $tramite->proveedor->user;
            
            // Mensajes amigables según el nuevo estado
            $mensajes = [
                'Aprobado' => 'Tu trámite fue aprobado.',
                'Por_Cotejar' => 'Tu trámite fue enviado a cotejo.',
                'Rechazado' => 'Tu trámite fue rechazado.',
                'Para_Correccion' => 'Tu trámite fue enviado a corrección.',
                'Cancelado' => 'Tu trámite fue cancelado.',
                'En_Revision' => 'Tu trámite fue enviado a revisión.',
                'Pendiente' => 'Tu trámite fue marcado como pendiente.'
            ];
            
            $titulo = "Actualización de trámite";
            $mensaje = $mensajes[$nuevoEstado] ?? "Tu trámite fue actualizado.";
            
            if ($nuevoEstado === 'Por_Cotejar') {
                $mensaje .= " Se ha agendado una cita para la verificación de documentos.";
            }

            $this->crearNotificacion(
                $usuario->id,
                $tramite->id,
                'Tramite',
                $titulo,
                $mensaje
            );

            Log::info('Notificación de cambio de estado enviada', [
                'tramite_id' => $tramite->id,
                'usuario_id' => $usuario->id,
                'estado_anterior' => $estadoAnterior,
                'nuevo_estado' => $nuevoEstado
            ]);
        } catch (\Exception $e) {
            Log::error('Error al notificar cambio de estado', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Notifica nueva cita agendada
     */
    public function notificarNuevaCita(int $usuarioId, int $tramiteId, string $fechaCita): void
    {
        try {
            $titulo = "Nueva cita agendada";
            $mensaje = "Se ha agendado una nueva cita para su trámite #{$tramiteId} el día {$fechaCita}.";

            $this->crearNotificacion(
                $usuarioId,
                $tramiteId,
                'Cita',
                $titulo,
                $mensaje
            );

            Log::info('Notificación de nueva cita enviada', [
                'usuario_id' => $usuarioId,
                'tramite_id' => $tramiteId,
                'fecha_cita' => $fechaCita
            ]);
        } catch (\Exception $e) {
            Log::error('Error al notificar nueva cita', [
                'usuario_id' => $usuarioId,
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtiene notificaciones no leídas de un usuario
     */
    public function obtenerNotificacionesNoLeidas(int $usuarioId)
    {
        return Notificacion::where('usuario_id', $usuarioId)
            ->where('leida', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Marca una notificación como leída
     */
    public function marcarComoLeida(int $notificacionId): bool
    {
        try {
            $notificacion = Notificacion::findOrFail($notificacionId);
            $notificacion->marcarComoLeida();
            return true;
        } catch (\Exception $e) {
            Log::error('Error al marcar notificación como leída', [
                'notificacion_id' => $notificacionId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Marca todas las notificaciones de un usuario como leídas
     */
    public function marcarTodasComoLeidas(int $usuarioId): bool
    {
        try {
            Notificacion::where('usuario_id', $usuarioId)
                ->where('leida', false)
                ->update(['leida' => true]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error al marcar todas las notificaciones como leídas', [
                'usuario_id' => $usuarioId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
} 