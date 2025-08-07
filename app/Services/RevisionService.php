<?php

namespace App\Services;

use App\Models\Tramite;
use App\Models\RevisionTramite;
use App\Models\SeccionRevision;
use App\Models\Archivo;
use App\Models\User;
use App\Enums\TramiteStatus;
use App\Services\CitasService;
use App\Services\NotificacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RevisionService
{
    protected CitasService $citasService;
    protected NotificacionService $notificacionService;

    public function __construct(CitasService $citasService, NotificacionService $notificacionService)
    {
        $this->citasService = $citasService;
        $this->notificacionService = $notificacionService;
    }
    /**
     * Procesar revisión digital con evaluación por secciones
     */
    public function procesarRevisionDigital(Tramite $tramite, array $data)
    {
        return DB::transaction(function () use ($tramite, $data) {
            $usuario = Auth::user();
            $tipoRevision = $data['tipo_revision'] ?? 'Digital';
            
            // 1. Crear o actualizar la revisión del trámite
            $revision = $this->crearOActualizarRevision($tramite, $tipoRevision, $usuario);
            
            // 2. Procesar secciones evaluadas
            $estadosSecciones = $this->procesarSecciones($tramite, $data['secciones'] ?? [], $usuario);
            
            // 3. Procesar archivos si hay comentarios/decisiones
            if (isset($data['archivos'])) {
                $this->procesarArchivos($tramite, $data['archivos'], $usuario);
            }
            
            // 4. Determinar estado final del trámite
            $estadoFinal = $this->determinarEstadoFinal($data['decision_final'] ?? null, $estadosSecciones);
            
            if (($data['decision_final'] ?? null) === 'agendar_cita') {
                $resultadoCita = $this->citasService->agendarCitaRevisionDigital($tramite->id);
                if (!$resultadoCita['success']) {
                    throw new \Exception('Error al agendar la cita: ' . $resultadoCita['message']);
                }
            }
            
            $this->actualizarTramite($tramite, $estadoFinal, $data['observaciones_generales'] ?? null);
            $revision->update([
                'estado' => 'Finalizada',
                'fecha_fin' => Carbon::now(),
                'observaciones' => $data['observaciones_generales'] ?? null
            ]);

            $this->enviarNotificacionSegunDecision($tramite, $data['decision_final'] ?? null, $data['observaciones_generales'] ?? null);
            
            return [
                'success' => true,
                'revision' => $revision,
                'estado_tramite' => $estadoFinal,
                'message' => 'Revisión procesada exitosamente'
            ];
        });
    }
    
    /**
     * Procesar revisión presencial (enfocada en documentos)
     */
    public function procesarRevisionPresencial(Tramite $tramite, array $data)
    {
        return DB::transaction(function () use ($tramite, $data) {
            $usuario = Auth::user();
            
            // 1. Crear o actualizar la revisión del trámite
            $revision = $this->crearOActualizarRevision($tramite, 'Presencial', $usuario);
            
            // 2. Procesar evaluación de documentos
            if (isset($data['archivos'])) {
                $this->procesarArchivos($tramite, $data['archivos'], $usuario);
            }
            
            // 3. Crear sección especial para documentos presenciales
            SeccionRevision::updateOrCreate(
                [
                    'tramite_id' => $tramite->id,
                    'seccion' => 'documentos_presencial'
                ],
                [
                    'estado' => $data['decision_documentos'] ?? 'Pendiente',
                    'comentario' => $data['comentarios_presencial'] ?? null,
                    'revisado_por' => $usuario->id
                ]
            );
            
            // 4. Determinar estado final
            $estadoFinal = $data['decision'] === 'aprobado' ? 
                Tramite::STATUS_APROBADO : 
                Tramite::STATUS_RECHAZADO;
            
            // 5. Actualizar trámite
            $this->actualizarTramite($tramite, $estadoFinal, $data['observaciones'] ?? null);
            
            // 6. Finalizar revisión
            $revision->update([
                'estado' => 'Finalizada',
                'fecha_fin' => Carbon::now(),
                'observaciones' => $data['observaciones'] ?? null
            ]);
            
            return [
                'success' => true,
                'revision' => $revision,
                'estado_tramite' => $estadoFinal,
                'message' => 'Revisión presencial procesada exitosamente'
            ];
        });
    }
    
    /**
     * Crear o actualizar revisión del trámite
     */
    protected function crearOActualizarRevision(Tramite $tramite, string $tipoRevision, User $usuario)
    {
        return RevisionTramite::updateOrCreate(
            [
                'tramite_id' => $tramite->id,
                'tipo_revision' => $tipoRevision
            ],
            [
                'revisor_id' => $usuario->id,
                'fecha_inicio' => Carbon::now(),
                'estado' => 'En_Proceso'
            ]
        );
    }
    
    /**
     * Procesar secciones de la revisión
     */
    protected function procesarSecciones(Tramite $tramite, array $secciones, User $usuario): array
    {
        $estadosSecciones = [];
        
        foreach ($secciones as $nombreSeccion => $datosSeccion) {
            $estado = $datosSeccion['decision'] ?? 'Pendiente';
            $comentario = $datosSeccion['comentario'] ?? null;
            
            SeccionRevision::updateOrCreate(
                [
                    'tramite_id' => $tramite->id,
                    'seccion' => $nombreSeccion
                ],
                [
                    'estado' => $estado,
                    'comentario' => $comentario,
                    'revisado_por' => $usuario->id
                ]
            );
            
            $estadosSecciones[$nombreSeccion] = $estado;
        }
        
        return $estadosSecciones;
    }
    
    /**
     * Procesar archivos con comentarios
     */
    protected function procesarArchivos(Tramite $tramite, array $archivos, User $usuario)
    {
        foreach ($archivos as $archivoId => $datosArchivo) {
            $archivo = Archivo::where('id', $archivoId)
                             ->where('tramite_id', $tramite->id)
                             ->first();
            
            if ($archivo) {
                // Mapear 'decision' a 'status' si es necesario
                $status = $datosArchivo['status'] ?? $datosArchivo['decision'] ?? $archivo->status;
                $comentario = $datosArchivo['comentario'] ?? null;
                
                $archivo->update([
                    'status' => $status,
                    'comentario_revision' => $comentario,
                    'revisado_por' => $usuario->id
                ]);
                
                \Log::info('Archivo procesado en revisión', [
                    'archivo_id' => $archivoId,
                    'tramite_id' => $tramite->id,
                    'status' => $status,
                    'comentario' => $comentario,
                    'revisado_por' => $usuario->id
                ]);
            }
        }
    }
    
    /**
     * Determinar estado final del trámite
     */
    protected function determinarEstadoFinal(?string $decisionFinal, array $estadosSecciones): string
    {
        // Si hay decisión final explícita
        if ($decisionFinal) {
            return match($decisionFinal) {
                'aprobado' => TramiteStatus::APROBADO->value,
                'rechazado' => TramiteStatus::RECHAZADO->value,
                'agendar_cita' => TramiteStatus::REVISION_PRESENCIAL->value,
                'correcciones' => TramiteStatus::PARA_CORRECCION->value,
                default => TramiteStatus::REVISION_DIGITAL->value
            };
        }
        
        // Evaluar automáticamente basado en secciones
        $secciones = array_values($estadosSecciones);
        
        if (empty($secciones)) {
            return TramiteStatus::REVISION_DIGITAL->value;
        }
        
        // Si todas las secciones están aprobadas
        if (array_filter($secciones, fn($estado) => $estado === 'Aprobado') === $secciones) {
            return TramiteStatus::APROBADO->value;
        }
        
        // Si hay alguna sección rechazada
        if (in_array('Rechazado', $secciones)) {
            return TramiteStatus::PARA_CORRECCION->value;
        }
        
        // Por defecto, mantener en revisión digital
        return TramiteStatus::REVISION_DIGITAL->value;
    }
    
    /**
     * Actualizar estado del trámite
     */
    protected function actualizarTramite(Tramite $tramite, string $estado, ?string $observaciones)
    {
        \Log::info('actualizarTramite llamado', [
            'tramite_id' => $tramite->id,
            'estado_anterior' => $tramite->status,
            'estado_nuevo' => $estado,
            'observaciones' => $observaciones
        ]);
        
        $tramite->update([
            'status' => $estado,
            'observaciones' => $observaciones
        ]);
        
        \Log::info('Trámite actualizado exitosamente', [
            'tramite_id' => $tramite->id,
            'estado_actual' => $tramite->fresh()->status
        ]);
    }
    
    /**
     * Obtener resumen de la revisión
     */
    public function obtenerResumenRevision(Tramite $tramite): array
    {
        $secciones = SeccionRevision::where('tramite_id', $tramite->id)->get();
        $archivos = Archivo::where('tramite_id', $tramite->id)
                          ->whereNotNull('comentario_revision')
                          ->get();
        
        return [
            'secciones_evaluadas' => $secciones->count(),
            'secciones_aprobadas' => $secciones->where('estado', 'Aprobado')->count(),
            'secciones_rechazadas' => $secciones->where('estado', 'Rechazado')->count(),
            'archivos_comentados' => $archivos->count(),
            'detalle_secciones' => $secciones->toArray(),
            'detalle_archivos' => $archivos->toArray()
        ];
    }

    /**
     * Enviar notificación según la decisión tomada
     */
    protected function enviarNotificacionSegunDecision(Tramite $tramite, ?string $decision, ?string $observaciones): void
    {
        match($decision) {
            'aprobado', 'agendar_cita' => $this->notificacionService->notificarTramiteAprobado($tramite),
            'rechazado' => $this->notificacionService->notificarTramiteRechazado($tramite, $observaciones),
            'correcciones' => $this->notificacionService->notificarCorrecciones($tramite, $observaciones),
            default => null
        };
    }

    public function obtenerTramitesPendientes(Request $request)
    {
        $query = Tramite::with(['proveedor', 'datosGenerales' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }])
        ->orderBy('created_at', 'desc');

        // Filtro por estado - si no se especifica, mostrar todos los estados de revisión
        if ($request->filled('estado')) {
            $query->where('status', $request->estado);
        } else {
            // Por defecto, mostrar trámites pendientes y en revisión
            $query->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria']);
        }

        // Filtro para mostrar solo trámites asignados al usuario actual
        if ($request->boolean('mis_tramites')) {
            $usuario = Auth::user();
            $query->whereHas('revisiones', function($q) use ($usuario) {
                $q->where('revisor_id', $usuario->id)
                  ->where('estado', '!=', 'Finalizada');
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('proveedor', function($subQ) use ($search) {
                    $subQ->where('rfc', 'like', "%{$search}%");
                })
                ->orWhereHas('datosGenerales', function($subQ) use ($search) {
                    $subQ->where('razon_social', 'like', "%{$search}%")
                         ->orWhere('curp', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('tipo_tramite')) {
            $query->where('tipo_tramite', $request->tipo_tramite);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        return $query->paginate($request->get('per_page', 15));
    }

    public function obtenerDatosSeleccionTipo(int $tramiteId): array
    {
        $tramite = Tramite::with(['proveedor', 'datosGenerales' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }])->findOrFail($tramiteId);

        $revisionExistente = RevisionTramite::where('tramite_id', $tramiteId)
            ->where('estado', '!=', 'Finalizada')
            ->first();

        $tiposRevisionDisponibles = $this->determinarTiposRevisionDisponibles($tramite);

        $citaInfo = null;
        if ($tramite->status === 'Revision_Presencial') {
            $citaInfo = $this->obtenerInformacionCita($tramite->id);
        }

        return [
            'tramite' => $tramite,
            'revisionExistente' => $revisionExistente,
            'tiposRevisionDisponibles' => $tiposRevisionDisponibles,
            'citaInfo' => $citaInfo
        ];
    }

    public function iniciarRevision(int $tramiteId, string $tipoRevision): void
    {
        $tramite = Tramite::findOrFail($tramiteId);
        $nuevoEstado = match($tipoRevision) {
            'Digital' => TramiteStatus::REVISION_DIGITAL->value,
            'Presencial' => TramiteStatus::REVISION_PRESENCIAL->value,
            'Domiciliaria' => TramiteStatus::REVISION_DOMICILIARIA->value,
            default => TramiteStatus::REVISION_DIGITAL->value
        };
        
        $tramite->update(['status' => $nuevoEstado]);
    }

    public function obtenerDatosRevision(int $tramiteId, string $tipoRevision): array
    {
        return match($tipoRevision) {
            'Digital' => app(\App\Services\Revisiones\RevisionDigitalService::class)->obtenerDatosRevisionDigital($tramiteId),
            'Presencial' => app(\App\Services\Revisiones\RevisionPresencialService::class)->obtenerDatosRevisionPresencial($tramiteId),
            'Domiciliaria' => app(\App\Services\Revisiones\RevisionDomiciliariaService::class)->obtenerDatosRevisionDomiciliaria($tramiteId),
            default => app(\App\Services\Revisiones\RevisionDigitalService::class)->obtenerDatosRevisionDigital($tramiteId)
        };
    }

    public function obtenerVistaRevision(string $tipoRevision): string
    {
        return match($tipoRevision) {
            'Digital' => 'revisiones.revision-digital',
            'Presencial' => 'revisiones.revision-presencial', 
            'Domiciliaria' => 'revisiones.revision-domiciliaria',
            default => 'revisiones.revision-digital'
        };
    }

    private function determinarTiposRevisionDisponibles(Tramite $tramite): array
    {
        $disponibles = [
            'Digital' => false,
            'Presencial' => false,
            'Domiciliaria' => false
        ];

        if ($tramite->status === 'Pendiente') {
            $disponibles = [
                'Digital' => true,
                'Presencial' => false,
                'Domiciliaria' => false
            ];
        }
        elseif (in_array($tramite->status, ['Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria'])) {
            switch ($tramite->status) {
                case 'Revision_Digital':
                    $disponibles['Digital'] = true;
                    break;
                case 'Revision_Presencial':
                    $disponibles['Presencial'] = true;
                    break;
                case 'Revision_Domiciliaria':
                    $disponibles['Domiciliaria'] = true;
                    break;
            }
        }

        return $disponibles;
    }

    private function obtenerInformacionCita(int $tramiteId): ?array
    {
        $cita = \App\Models\Cita::where('tramite_id', $tramiteId)
            ->where('estado', 'Asignada')
            ->with(['asignadoA'])
            ->orderBy('fecha_cita', 'desc')
            ->first();

        if (!$cita) {
            return null;
        }

        $personaResponsable = $this->obtenerPersonaResponsable($cita->tramite);

        return [
            'fecha' => $cita->fecha_cita->format('d/m/Y'),
            'hora' => $cita->fecha_cita->format('H:i'),
            'fecha_completa' => $cita->fecha_cita->format('d/m/Y H:i'),
            'revisor' => $cita->asignadoA ? ($cita->asignadoA->nombre ?: 'No asignado') : 'No asignado',
            'personaResponsable' => $personaResponsable,
            'estado' => $cita->estado,
            'intento' => $cita->intento
        ];
    }

    private function obtenerPersonaResponsable($tramite): ?array
    {
        $proveedor = $tramite->proveedor;
        
        if (!$proveedor) {
            return null;
        }

        if ($proveedor->tipo_persona === 'Moral') {
            $apoderadoService = app(\App\Services\Tramites\ApoderadoService::class);
            $apoderado = $apoderadoService->obtener($tramite);
            
            if ($apoderado && isset($apoderado['nombre_apoderado'])) {
                return [
                    'nombre' => $apoderado['nombre_apoderado'],
                    'tipo' => 'Apoderado'
                ];
            }
        }
        
        if ($proveedor->tipo_persona === 'Fisica' && $proveedor->usuario) {
            return [
                'nombre' => $proveedor->usuario->nombre,
                'tipo' => 'La persona'
            ];
        }

        return null;
    }

    public function obtenerDatosVistaSoloLectura(int $tramiteId): array
    {
        $tramite = Tramite::with(['proveedor', 'datosGenerales' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }])->findOrFail($tramiteId);

        $revision = RevisionTramite::where('tramite_id', $tramiteId)
            ->orderBy('created_at', 'desc')
            ->first();

        $archivos = Archivo::where('tramite_id', $tramiteId)
            ->with('catalogoArchivo')
            ->get();

        return [
            'tramite' => $tramite,
            'revision' => $revision,
            'archivos' => $archivos
        ];
    }

    public function finalizarRevision(int $tramiteId, string $tipoRevision, string $decision, ?string $observaciones = null): void
    {
        $tramite = Tramite::findOrFail($tramiteId);
        
        $nuevoEstado = match($decision) {
            'aprobado' => TramiteStatus::APROBADO->value,
            'rechazado' => TramiteStatus::RECHAZADO->value,
            default => TramiteStatus::REVISION_DIGITAL->value
        };
        
        $tramite->update([
            'status' => $nuevoEstado,
            'observaciones' => $observaciones
        ]);

        RevisionTramite::updateOrCreate(
            ['tramite_id' => $tramiteId],
            [
                'tipo_revision' => $tipoRevision,
                'estado' => 'Finalizada',
                'observaciones' => $observaciones,
                'fecha_fin' => Carbon::now(),
                'revisor_id' => Auth::id()
            ]
        );
    }

    /**
     * Obtener datos base para cualquier tipo de revisión
     */
    protected function obtenerDatosRevisionBase(int $tramiteId): array
    {
        $tramite = Tramite::with(['proveedor', 'datosGenerales' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(1);
        }])->findOrFail($tramiteId);

        $revision = RevisionTramite::where('tramite_id', $tramiteId)
            ->orderBy('created_at', 'desc')
            ->first();

        $archivos = Archivo::where('tramite_id', $tramiteId)
            ->with('catalogoArchivo')
            ->get();

        return [
            'tramite' => $tramite,
            'revision' => $revision,
            'archivos' => $archivos
        ];
    }

    /**
     * Obtener datos específicos para un tipo de revisión
     */
} 