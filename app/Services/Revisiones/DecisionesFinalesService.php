<?php

namespace App\Services\Revisiones;

use App\Models\Tramite;
use App\Models\SeccionRevision;
use App\Models\Proveedor;
use App\Services\CitasService;
use App\Services\RevisionService;
use App\Services\RfcProveedorService;
use App\Services\NotificacionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DecisionesFinalesService
{
    public function __construct()
    {
    }

    public function aprobarYAgendarCita(int $tramiteId, ?string $comentarioGeneral = null): array
    {
        return DB::transaction(function() use ($tramiteId, $comentarioGeneral) {
            $tramite = Tramite::findOrFail($tramiteId);
            $estadoGeneral = app(RevisionService::class)->obtenerEstadoGeneral($tramiteId);
            
            if (!$estadoGeneral['todas_aprobadas']) {
                return ['success' => false, 'message' => 'No se puede aprobar el trámite. Todas las secciones deben estar aprobadas.'];
            }

            $resultado = app(CitasService::class)->agendarCitaRevisionDigital($tramiteId);
            if (!$resultado['success']) {
                return ['success' => false, 'message' => $resultado['message']];
            }

            $tramite->update(['status' => 'Revision_Presencial', 'observaciones' => $comentarioGeneral]);
            $this->guardarComentarioGeneral($tramiteId, $comentarioGeneral, 'Aprobado');

            return [
                'success' => true,
                'message' => 'Trámite aprobado y cita agendada exitosamente',
                'fecha_cita' => $resultado['fecha_formateada'],
                'cita_id' => $resultado['cita']->id
            ];
        });
    }

    public function rechazarParaCorreccion(int $tramiteId, ?string $comentarioGeneral = null): array
    {
        return DB::transaction(function() use ($tramiteId, $comentarioGeneral) {
            $tramite = Tramite::findOrFail($tramiteId);
            $tramite->update([
                'status' => 'Para_Correccion',
                'observaciones' => $comentarioGeneral,
                'correcciones_count' => $tramite->correcciones_count + 1
            ]);
            $this->guardarComentarioGeneral($tramiteId, $comentarioGeneral, 'Rechazado');
            
            // Notificar al usuario del trámite sobre las correcciones requeridas
            $notificacionService = app(NotificacionService::class);
            $notificacionService->notificarCorrecciones($tramite, $comentarioGeneral);
            
            return ['success' => true, 'message' => 'Trámite enviado para corrección exitosamente'];
        });
    }

    public function rechazarCompleto(int $tramiteId, ?string $comentarioGeneral = null): array
    {
        return DB::transaction(function() use ($tramiteId, $comentarioGeneral) {
            $tramite = Tramite::findOrFail($tramiteId);
            $tramite->update([
                'status' => 'Rechazado',
                'observaciones' => $comentarioGeneral,
                'fecha_finalizacion' => now()
            ]);
            $this->guardarComentarioGeneral($tramiteId, $comentarioGeneral, 'Rechazado');
            return ['success' => true, 'message' => 'Trámite rechazado exitosamente'];
        });
    }

    public function aprobarYRenovarProveedor(int $tramiteId, ?string $comentarioGeneral = null): array
    {
        return $this->aprobarYActivarProveedor($tramiteId, $comentarioGeneral);
    }

    public function aprobarYActualizarProveedor(int $tramiteId, ?string $comentarioGeneral = null): array
    {
        return DB::transaction(function() use ($tramiteId, $comentarioGeneral) {
                $tramite = Tramite::findOrFail($tramiteId);
                if (!$tramite->proveedor) {
                    throw new \Exception("El trámite {$tramiteId} no tiene un proveedor asociado");
                }
                
                $rfcProveedorService = app(RfcProveedorService::class);
            $rfcProveedorService->activarProveedor($tramite->proveedor, 'actualizacion');
            
                $tramite->update([
                    'status' => 'Aprobado',
                    'observaciones' => $comentarioGeneral,
                    'fecha_finalizacion' => now()
                ]);

            return ['success' => true, 'message' => 'Trámite actualizado y proveedor activado exitosamente'];
        });
    }



    public function aprobarYActivarProveedor(int $tramiteId, ?string $comentarioGeneral = null): array
    {
        return DB::transaction(function() use ($tramiteId, $comentarioGeneral) {
            $tramite = Tramite::findOrFail($tramiteId);
            if (!$tramite->proveedor) {
                throw new \Exception("El trámite {$tramiteId} no tiene un proveedor asociado");
            }
            
            $rfcProveedorService = app(RfcProveedorService::class);
            $rfcProveedorService->activarProveedor($tramite->proveedor, strtolower($tramite->tipo_tramite));
            $tramite->proveedor->refresh();
            
            // Generar oficio oficial
            $oficioService = app(\App\Services\OficioService::class);
            $oficio = $oficioService->generarOficioParaTramite($tramite);
            
            // Notificar al usuario sobre la activación del proveedor
            if ($tramite->proveedor->pv_numero) {
                $notificacionService = app(NotificacionService::class);
                $notificacionService->notificarProveedorAsignado($tramite, $tramite->proveedor->pv_numero);
            }
            
            $tramite->update([
                'status' => 'Aprobado',
                'observaciones' => $comentarioGeneral,
                'fecha_finalizacion' => now()
            ]);
            
            return [
                'success' => true, 
                'message' => "Trámite aprobado y proveedor activado exitosamente. Oficio generado: {$oficio->numero_oficio}",
                'pv_numero' => $tramite->proveedor->pv_numero,
                'oficio_generado' => [
                    'oficio_id' => $oficio->id,
                    'numero_oficio' => $oficio->numero_oficio,
                    'url' => $oficio->url
                ]
            ];
        });
    }

    private function guardarComentarioGeneral(int $tramiteId, ?string $comentario, string $estado): void
    {
        if ($comentario && auth()->check()) {
                SeccionRevision::updateOrCreate(
                ['tramite_id' => $tramiteId, 'seccion' => 'comentario_general'],
                ['estado' => $estado, 'comentario' => $comentario, 'revisado_por' => auth()->id()]
            );
        }
    }
    
    private function calcularFechaVencimiento(\Carbon\Carbon $fechaInicio, int $años): \Carbon\Carbon
    {
        $fechaVencimiento = $fechaInicio->copy()->addYears($años);
        if ($fechaInicio->month === 2 && $fechaInicio->day === 29 && !$fechaVencimiento->isLeapYear()) {
                $fechaVencimiento = $fechaVencimiento->setDay(28);
        }
        return $fechaVencimiento;
    }

    public function aprobarRevisionPresencial(int $tramiteId, ?string $comentarioGeneral = null): array
    {
        return DB::transaction(function() use ($tramiteId, $comentarioGeneral) {
            $tramite = Tramite::findOrFail($tramiteId);
            if (!$tramite->proveedor) {
                throw new \Exception("El trámite {$tramiteId} no tiene un proveedor asociado");
            }
            
            $tipoTramite = strtolower($tramite->tipo_tramite);
            $rfcProveedorService = app(RfcProveedorService::class);
            
            if ($tipoTramite === 'inscripcion' && !$tramite->proveedor->pv_numero) {
                $numeroPV = $rfcProveedorService->generarNumeroPV();
                $tramite->proveedor->update(['pv_numero' => $numeroPV]);
            }
            
            $rfcProveedorService->activarProveedor($tramite->proveedor, $tipoTramite);
            $tramite->proveedor->refresh();
            
            // Notificar al usuario sobre la asignación del número PV
            if ($tramite->proveedor->pv_numero) {
                $notificacionService = app(NotificacionService::class);
                $notificacionService->notificarProveedorAsignado($tramite, $tramite->proveedor->pv_numero);
            }
            
            $oficioService = app(\App\Services\OficioService::class);
            $oficio = $oficioService->generarOficioParaTramite($tramite);
            
            $citasService = app(\App\Services\CitasService::class);
            $resultadoCita = $citasService->agendarCitaCotejoDomiciliario($tramiteId);
            
            $mensajeCita = $resultadoCita['success'] ? 
                " Cita domiciliaria agendada para el " . $resultadoCita['fecha_formateada'] . "." :
                " Nota: No se pudo agendar cita domiciliaria automáticamente.";
            
            $tramite->update([
                'observaciones' => $comentarioGeneral,
                'fecha_finalizacion' => now()
            ]);
            
            $this->guardarComentarioGeneral($tramiteId, $comentarioGeneral, 'Aprobado');
            
            return [
                'success' => true,
                'message' => "Trámite aprobado exitosamente. Proveedor activado con PV: {$tramite->proveedor->pv_numero}. Oficio generado: {$oficio->numero_oficio}.{$mensajeCita}",
                'pv_numero' => $tramite->proveedor->pv_numero,
                'oficio_generado' => [
                    'oficio_id' => $oficio->id,
                    'numero_oficio' => $oficio->numero_oficio,
                    'url' => $oficio->url
                ],
                'cita_agendada' => $resultadoCita['success']
            ];
        });
    }
} 