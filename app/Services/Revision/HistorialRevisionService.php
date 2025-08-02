<?php

namespace App\Services\Revision;

use App\Models\Tramite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Servicio especializado para el historial y auditoría de revisiones
 * Responsabilidad: Gestionar comentarios generales, historial y auditoría
 */
class HistorialRevisionService
{
    /**
     * Guarda un comentario general para un trámite
     */
    public function guardarComentarioGeneral(int $tramiteId, ?string $comentario): array
    {
        try {
            $tramite = Tramite::findOrFail($tramiteId);
            
            $tramite->update([
                'comentarios_revision' => $comentario,
                'comentarios_actualizados_por' => Auth::id(),
                'comentarios_actualizados_en' => now()
            ]);

            Log::info('Comentario general actualizado', [
                'tramite_id' => $tramiteId,
                'usuario_id' => Auth::id(),
                'longitud_comentario' => strlen($comentario ?? '')
            ]);

            return [
                'success' => true,
                'message' => 'Comentario guardado exitosamente',
                'comentario' => $comentario,
                'fecha_actualizacion' => $tramite->comentarios_actualizados_en
            ];

        } catch (\Exception $e) {
            Log::error('Error al guardar comentario general', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al guardar el comentario: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene el historial completo de un trámite
     */
    public function obtenerHistorialCompleto(Tramite $tramite): array
    {
        try {
            $tramite->load([
                'revisadoPor', 
                'proveedor.user',
                'archivos.catalogoArchivo',
                'revisionSecciones',
                'oficios',
                'cita'
            ]);

            $historial = [
                'tramite' => $this->obtenerDatosTramite($tramite),
                'revision' => $this->obtenerDatosRevision($tramite),
                'documentos' => $this->obtenerHistorialDocumentos($tramite),
                'secciones' => $this->obtenerHistorialSecciones($tramite),
                'eventos' => $this->obtenerEventosImportantes($tramite),
                'estadisticas' => $this->obtenerEstadisticasRevision($tramite)
            ];

            return [
                'success' => true,
                'historial' => $historial
            ];

        } catch (\Exception $e) {
            Log::error('Error al obtener historial completo', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al obtener el historial: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene el historial de estados de un trámite
     */
    public function obtenerHistorialEstados(Tramite $tramite): array
    {
        try {
            $tramite->load(['revisadoPor', 'proveedor']);
            
            // Por ahora retornamos información básica
            // En el futuro podrías implementar un modelo de historial específico
            $historial = [
                'estado_actual' => $tramite->estado,
                'revisado_por' => $tramite->revisadoPor ? [
                    'id' => $tramite->revisadoPor->id,
                    'nombre' => $tramite->revisadoPor->name,
                    'email' => $tramite->revisadoPor->email
                ] : null,
                'fecha_ultima_revision' => $tramite->updated_at,
                'fecha_creacion' => $tramite->created_at,
                'observaciones_actuales' => $tramite->observaciones,
                'comentarios_revision' => $tramite->comentarios_revision,
                'tiempo_en_revision' => $this->calcularTiempoEnRevision($tramite)
            ];

            return [
                'success' => true,
                'tramite' => $tramite->only(['id', 'tipo_tramite', 'estado']),
                'historial' => $historial
            ];

        } catch (\Exception $e) {
            Log::error('Error al obtener historial de estados', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al obtener el historial de estados.'
            ];
        }
    }

    /**
     * Registra una acción de revisión para auditoría
     */
    public function registrarAccionRevision(Tramite $tramite, string $accion, array $detalles = []): void
    {
        Log::info('Acción de revisión registrada', [
            'tramite_id' => $tramite->id,
            'accion' => $accion,
            'usuario_id' => Auth::id(),
            'timestamp' => now(),
            'detalles' => $detalles,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Obtiene métricas de revisión para un trámite
     */
    public function obtenerMetricasRevision(Tramite $tramite): array
    {
        $tramite->load(['archivos', 'revisionSecciones']);

        $documentos = $tramite->archivos;
        $secciones = $tramite->revisionSecciones;

        return [
            'documentos' => [
                'total' => $documentos->count(),
                'aprobados' => $documentos->where('aprobado', true)->count(),
                'rechazados' => $documentos->where('aprobado', false)->count(),
                'pendientes' => $documentos->where('aprobado', null)->count(),
                'con_comentarios' => $documentos->whereNotNull('observaciones')->count()
            ],
            'secciones' => [
                'total' => $secciones->count(),
                'aprobadas' => $secciones->where('estado', 'Aprobado')->count(),
                'rechazadas' => $secciones->where('estado', 'Rechazado')->count(),
                'pendientes' => $secciones->where('estado', 'Pendiente')->count()
            ],
            'progreso' => [
                'documentos_porcentaje' => $documentos->count() > 0 
                    ? round(($documentos->where('aprobado', true)->count() / $documentos->count()) * 100, 2) 
                    : 0,
                'secciones_porcentaje' => $secciones->count() > 0 
                    ? round(($secciones->where('estado', 'Aprobado')->count() / $secciones->count()) * 100, 2) 
                    : 0
            ],
            'tiempo' => [
                'dias_desde_creacion' => $tramite->created_at->diffInDays(now()),
                'dias_desde_ultima_actualizacion' => $tramite->updated_at->diffInDays(now())
            ]
        ];
    }

    /**
     * Obtiene datos básicos del trámite para historial
     */
    private function obtenerDatosTramite(Tramite $tramite): array
    {
        return [
            'id' => $tramite->id,
            'tipo' => $tramite->tipo_tramite,
            'estado' => $tramite->estado,
            'fecha_creacion' => $tramite->created_at,
            'fecha_actualizacion' => $tramite->updated_at,
            'proveedor' => [
                'rfc' => $tramite->proveedor->rfc,
                'usuario' => $tramite->proveedor->user->name
            ]
        ];
    }

    /**
     * Obtiene datos de revisión
     */
    private function obtenerDatosRevision(Tramite $tramite): array
    {
        return [
            'observaciones' => $tramite->observaciones,
            'comentarios_revision' => $tramite->comentarios_revision,
            'revisado_por' => $tramite->revisadoPor ? $tramite->revisadoPor->name : null,
            'fecha_revision' => $tramite->updated_at
        ];
    }

    /**
     * Obtiene historial de documentos
     */
    private function obtenerHistorialDocumentos(Tramite $tramite): array
    {
        return $tramite->archivos->map(function ($archivo) {
            return [
                'id' => $archivo->id,
                'nombre' => $archivo->catalogoArchivo->nombre ?? 'Documento',
                'estado' => $archivo->aprobado,
                'observaciones' => $archivo->observaciones,
                'fecha_cotejo' => $archivo->fecha_cotejo,
                'cotejado_por' => $archivo->cotejado_por
            ];
        })->toArray();
    }

    /**
     * Obtiene historial de secciones
     */
    private function obtenerHistorialSecciones(Tramite $tramite): array
    {
        return $tramite->revisionSecciones->map(function ($seccion) {
            return [
                'seccion' => $seccion->seccion,
                'estado' => $seccion->estado,
                'observaciones' => $seccion->observaciones,
                'fecha_revision' => $seccion->updated_at
            ];
        })->toArray();
    }

    /**
     * Obtiene eventos importantes del trámite
     */
    private function obtenerEventosImportantes(Tramite $tramite): array
    {
        $eventos = [];

        // Evento de creación
        $eventos[] = [
            'tipo' => 'creacion',
            'descripcion' => 'Trámite creado',
            'fecha' => $tramite->created_at
        ];

        // Evento de cita si existe
        if ($tramite->cita) {
            $eventos[] = [
                'tipo' => 'cita',
                'descripcion' => 'Cita agendada para cotejo',
                'fecha' => $tramite->cita->fecha_cita
            ];
        }

        // Evento de oficio si existe
        if ($tramite->oficios->count() > 0) {
            $eventos[] = [
                'tipo' => 'oficio',
                'descripcion' => 'Oficio generado',
                'fecha' => $tramite->oficios->first()->created_at
            ];
        }

        return collect($eventos)->sortBy('fecha')->values()->toArray();
    }

    /**
     * Obtiene estadísticas de revisión
     */
    private function obtenerEstadisticasRevision(Tramite $tramite): array
    {
        return $this->obtenerMetricasRevision($tramite);
    }

    /**
     * Calcula el tiempo que ha estado en revisión
     */
    private function calcularTiempoEnRevision(Tramite $tramite): array
    {
        $diasTotal = $tramite->created_at->diffInDays(now());
        $diasDesdeActualizacion = $tramite->updated_at->diffInDays(now());

        return [
            'dias_total' => $diasTotal,
            'dias_desde_ultima_actualizacion' => $diasDesdeActualizacion,
            'tiempo_promedio_estimado' => 5, // Días promedio estimado
            'estado_tiempo' => $diasTotal > 10 ? 'excedido' : ($diasTotal > 5 ? 'normal' : 'rapido')
        ];
    }
}