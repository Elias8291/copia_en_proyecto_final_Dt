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
        try {
            $tramite = $cita->tramite;
            $usuarioTramite = $tramite->proveedor->usuario ?? null;
            $revisor = User::find($cita->asignado_a);

            // Determinar el tipo de cita para personalizar el mensaje
            $tipoCita = $cita->tipo_cita;
            $tituloUsuario = match($tipoCita) {
                'Domiciliaria' => 'Cita de Cotejo Domiciliario Agendada',
                'Presencial' => 'Cita Presencial Agendada',
                'Digital' => 'Cita Digital Agendada',
                default => 'Cita Agendada'
            };

            $tituloRevisor = match($tipoCita) {
                'Domiciliaria' => 'Nueva Cita de Cotejo Domiciliario Asignada',
                'Presencial' => 'Nueva Cita Presencial Asignada',
                'Digital' => 'Nueva Cita Digital Asignada',
                default => 'Nueva Cita Asignada'
            };

            $mensajeUsuario = match($tipoCita) {
                'Domiciliaria' => "Su trámite #{$tramite->id} tiene una cita de cotejo domiciliario agendada para el " . 
                                 $cita->fecha_cita->format('d/m/Y H:i') . " con {$revisor->nombre}",
                'Presencial' => "Su trámite #{$tramite->id} tiene una cita presencial agendada para el " . 
                               $cita->fecha_cita->format('d/m/Y H:i') . " con {$revisor->nombre}",
                'Digital' => "Su trámite #{$tramite->id} tiene una cita digital agendada para el " . 
                            $cita->fecha_cita->format('d/m/Y H:i') . " con {$revisor->nombre}",
                default => "Su trámite #{$tramite->id} tiene una cita agendada para el " . 
                          $cita->fecha_cita->format('d/m/Y H:i') . " con {$revisor->nombre}"
            };

            $mensajeRevisor = match($tipoCita) {
                'Domiciliaria' => "Se le ha asignado una cita de cotejo domiciliario para revisar el trámite #{$tramite->id} el " . 
                                 $cita->fecha_cita->format('d/m/Y H:i'),
                'Presencial' => "Se le ha asignado una cita presencial para revisar el trámite #{$tramite->id} el " . 
                               $cita->fecha_cita->format('d/m/Y H:i'),
                'Digital' => "Se le ha asignado una cita digital para revisar el trámite #{$tramite->id} el " . 
                            $cita->fecha_cita->format('d/m/Y H:i'),
                default => "Se le ha asignado una cita para revisar el trámite #{$tramite->id} el " . 
                          $cita->fecha_cita->format('d/m/Y H:i')
            };

            if ($usuarioTramite) {
                Notificacion::create([
                    'usuario_id' => $usuarioTramite->id,
                    'tipo' => 'Cita',
                    'titulo' => $tituloUsuario,
                    'mensaje' => $mensajeUsuario,
                    'datos_adicionales' => json_encode([
                        'tramite_id' => $tramite->id,
                        'cita_id' => $cita->id,
                        'fecha_cita' => $cita->fecha_cita->format('Y-m-d H:i:s'),
                        'revisor' => $revisor->nombre,
                        'tipo_cita' => $tipoCita
                    ]),
                    'accion_url' => route('tramites.estado')
                ]);
            }

            if ($revisor) {
                Notificacion::create([
                    'usuario_id' => $revisor->id,
                    'tipo' => 'Cita',
                    'titulo' => $tituloRevisor,
                    'mensaje' => $mensajeRevisor,
                    'datos_adicionales' => json_encode([
                        'tramite_id' => $tramite->id,
                        'cita_id' => $cita->id,
                        'fecha_cita' => $cita->fecha_cita->format('Y-m-d H:i:s'),
                        'proveedor' => $tramite->proveedor->razon_social ?? 'N/A',
                        'tipo_cita' => $tipoCita
                    ]),
                    'accion_url' => route('revisiones.index')
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error al crear notificación de cita agendada', [
                'cita_id' => $cita->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function notificarCitaReagendada(Cita $cita): void
    {
        try {
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
        } catch (\Exception $e) {
            \Log::error('Error al crear notificación de cita reagendada', [
                'cita_id' => $cita->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function notificarTramiteAprobado(Tramite $tramite): void
    {
        try {
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
        } catch (\Exception $e) {
            \Log::error('Error al crear notificación de trámite aprobado', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function notificarProveedorAsignado(Tramite $tramite, string $numeroProveedor): void
    {
        try {
            \Log::info('Iniciando notificación de proveedor asignado', [
                'tramite_id' => $tramite->id,
                'numero_proveedor' => $numeroProveedor
            ]);

            // Cargar las relaciones necesarias
            $tramite->load(['proveedor.usuario']);
            
            $usuarioTramite = $tramite->proveedor->usuario ?? null;

            \Log::info('Usuario encontrado para notificación', [
                'tramite_id' => $tramite->id,
                'usuario_id' => $usuarioTramite?->id,
                'usuario_nombre' => $usuarioTramite?->nombre,
                'usuario_email' => $usuarioTramite?->correo
            ]);

            if ($usuarioTramite) {
                $notificacion = Notificacion::create([
                    'usuario_id' => $usuarioTramite->id,
                    'tipo' => 'exito',
                    'titulo' => '¡Trámite Aprobado - Proveedor Asignado!',
                    'mensaje' => "¡Felicidades! Su trámite ha sido aprobado y se le ha asignado el número de proveedor: {$numeroProveedor}. Puede descargar su oficio oficial desde el estado del trámite.",
                    'datos_adicionales' => json_encode([
                        'tramite_id' => $tramite->id,
                        'numero_proveedor' => $numeroProveedor,
                        'fecha_asignacion' => now()->format('Y-m-d H:i:s'),
                        'proveedor_id' => $tramite->proveedor->id,
                        'tipo_tramite' => $tramite->tipo_tramite
                    ]),
                    'accion_url' => route('tramites.estado')
                ]);

                \Log::info('Notificación de proveedor asignado creada exitosamente', [
                    'tramite_id' => $tramite->id,
                    'notificacion_id' => $notificacion->id,
                    'usuario_id' => $usuarioTramite->id,
                    'numero_proveedor' => $numeroProveedor
                ]);
            } else {
                \Log::warning('No se encontró usuario asociado al proveedor para notificar', [
                    'tramite_id' => $tramite->id,
                    'proveedor_id' => $tramite->proveedor->id ?? null
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error al crear notificación de proveedor asignado', [
                'tramite_id' => $tramite->id,
                'numero_proveedor' => $numeroProveedor,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function notificarTramiteRechazado(Tramite $tramite, string $motivo = null): void
    {
        try {
            $usuarioTramite = $tramite->proveedor->usuario ?? null;

            if ($usuarioTramite) {
                Notificacion::create([
                    'usuario_id' => $usuarioTramite->id,
                    'tipo' => 'Tramite',
                    'titulo' => 'Trámite Rechazado',
                    'mensaje' => "Su trámite #{$tramite->id} ha sido rechazado." . ($motivo ? " Motivo: {$motivo}" : ''),
                    'datos_adicionales' => json_encode([
                        'tramite_id' => $tramite->id,
                        'tipo_tramite' => $tramite->tipo_tramite,
                        'fecha_rechazo' => now()->format('Y-m-d H:i:s'),
                        'motivo' => $motivo
                    ]),
                    'accion_url' => route('tramites.estado')
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error al crear notificación de trámite rechazado', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function notificarCorrecciones(Tramite $tramite, string $observaciones = null): void
    {
        try {
            $usuarioTramite = $tramite->proveedor->usuario ?? null;

            if ($usuarioTramite) {
                Notificacion::create([
                    'usuario_id' => $usuarioTramite->id,
                    'tipo' => 'Tramite',
                    'titulo' => 'Correcciones Requeridas',
                    'mensaje' => "Su trámite #{$tramite->id} requiere correcciones." . ($observaciones ? " Observaciones: {$observaciones}" : ''),
                    'datos_adicionales' => json_encode([
                        'tramite_id' => $tramite->id,
                        'tipo_tramite' => $tramite->tipo_tramite,
                        'fecha_correcciones' => now()->format('Y-m-d H:i:s'),
                        'observaciones' => $observaciones
                    ]),
                    'accion_url' => route('tramites.estado')
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error al crear notificación de correcciones', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
        }
    }
} 