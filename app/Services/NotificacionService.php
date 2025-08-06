<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\User;
use App\Models\Tramite;
use App\Models\Cita;

class NotificacionService
{
    public function notificarCitaAgendada(Cita $cita): void
    {
        $tramite = $cita->tramite;
        $usuarioTramite = $tramite->proveedor->usuario ?? null;
        $revisor = User::find($cita->asignado_a);

        if ($usuarioTramite) {
            Notificacion::create([
                'usuario_id' => $usuarioTramite->id,
                'tipo' => 'Cita',
                'titulo' => 'Cita Presencial Agendada',
                'mensaje' => "Su trámite #{$tramite->id} tiene una cita presencial agendada para el " . 
                           $cita->fecha_cita->format('d/m/Y H:i') . " con {$revisor->nombre}",
                'datos_adicionales' => json_encode([
                    'tramite_id' => $tramite->id,
                    'cita_id' => $cita->id,
                    'fecha_cita' => $cita->fecha_cita->format('Y-m-d H:i:s'),
                    'revisor' => $revisor->nombre
                ]),
                'accion_url' => route('tramites.estado')
            ]);
        }

        if ($revisor) {
            Notificacion::create([
                'usuario_id' => $revisor->id,
                'tipo' => 'Cita',
                'titulo' => 'Nueva Cita Presencial Asignada',
                'mensaje' => "Se le ha asignado una cita presencial para revisar el trámite #{$tramite->id} el " . 
                           $cita->fecha_cita->format('d/m/Y H:i'),
                'datos_adicionales' => json_encode([
                    'tramite_id' => $tramite->id,
                    'cita_id' => $cita->id,
                    'fecha_cita' => $cita->fecha_cita->format('Y-m-d H:i:s'),
                    'proveedor' => $tramite->proveedor->razon_social ?? 'N/A'
                ]),
                'accion_url' => route('revisiones.index')
            ]);
        }
    }

    public function notificarCitaReagendada(Cita $cita): void
    {
        $tramite = $cita->tramite;
        $usuarioTramite = $tramite->proveedor->usuario ?? null;
        $revisor = User::find($cita->asignado_a);

        if ($usuarioTramite) {
            Notificacion::create([
                'usuario_id' => $usuarioTramite->id,
                'tipo' => 'Cita',
                'titulo' => 'Cita Reagendada',
                'mensaje' => "Su cita para el trámite #{$tramite->id} ha sido reagendada para el " . 
                           $cita->fecha_cita->format('d/m/Y H:i') . " con {$revisor->nombre}",
                'datos_adicionales' => json_encode([
                    'tramite_id' => $tramite->id,
                    'cita_id' => $cita->id,
                    'fecha_cita' => $cita->fecha_cita->format('Y-m-d H:i:s'),
                    'revisor' => $revisor->nombre,
                    'intento' => $cita->intento
                ]),
                'accion_url' => route('tramites.estado')
            ]);
        }
    }

    public function notificarTramiteAprobado(Tramite $tramite): void
    {
        $usuarioTramite = $tramite->proveedor->usuario ?? null;

        if ($usuarioTramite) {
            Notificacion::create([
                'usuario_id' => $usuarioTramite->id,
                'tipo' => 'Tramite',
                'titulo' => 'Trámite Aprobado',
                'mensaje' => "¡Felicidades! Su trámite #{$tramite->id} ha sido aprobado exitosamente.",
                'datos_adicionales' => json_encode([
                    'tramite_id' => $tramite->id,
                    'tipo_tramite' => $tramite->tipo_tramite,
                    'fecha_aprobacion' => now()->format('Y-m-d H:i:s')
                ]),
                'accion_url' => route('tramites.estado')
            ]);
        }
    }

    public function notificarTramiteRechazado(Tramite $tramite, string $motivo = null): void
    {
        $usuarioTramite = $tramite->proveedor->usuario ?? null;

        if ($usuarioTramite) {
            $mensaje = "Su trámite #{$tramite->id} ha sido rechazado.";
            if ($motivo) {
                $mensaje .= " Motivo: {$motivo}";
            }

            Notificacion::create([
                'usuario_id' => $usuarioTramite->id,
                'tipo' => 'Tramite',
                'titulo' => 'Trámite Rechazado',
                'mensaje' => $mensaje,
                'datos_adicionales' => json_encode([
                    'tramite_id' => $tramite->id,
                    'tipo_tramite' => $tramite->tipo_tramite,
                    'motivo' => $motivo,
                    'fecha_rechazo' => now()->format('Y-m-d H:i:s')
                ]),
                'accion_url' => route('tramites.estado')
            ]);
        }
    }

    public function notificarCorrecciones(Tramite $tramite, string $observaciones = null): void
    {
        $usuarioTramite = $tramite->proveedor->usuario ?? null;

        if ($usuarioTramite) {
            $mensaje = "Su trámite #{$tramite->id} requiere correcciones.";
            if ($observaciones) {
                $mensaje .= " Observaciones: {$observaciones}";
            }

            Notificacion::create([
                'usuario_id' => $usuarioTramite->id,
                'tipo' => 'Tramite',
                'titulo' => 'Correcciones Requeridas',
                'mensaje' => $mensaje,
                'datos_adicionales' => json_encode([
                    'tramite_id' => $tramite->id,
                    'tipo_tramite' => $tramite->tipo_tramite,
                    'observaciones' => $observaciones,
                    'fecha_solicitud' => now()->format('Y-m-d H:i:s')
                ]),
                'accion_url' => route('tramites.estado')
            ]);
        }
    }
} 