<?php

namespace App\Services;

use App\Models\Tramite;
use App\Models\RevisionTramite;
use App\Models\SeccionRevision;
use App\Models\Archivo;
use App\Models\User;
use App\Models\Cita;
use App\Enums\TramiteStatus;
use App\Services\CitasService;
use App\Services\NotificacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

// Servicio para gestión de revisiones de trámites
class RevisionService
{
    protected CitasService $citasService;
    protected NotificacionService $notificacionService;

    public function __construct(CitasService $citasService, NotificacionService $notificacionService = null)
    {
        $this->citasService = $citasService;
        $this->notificacionService = $notificacionService;
    }

    // Obtener trámites pendientes con filtros
    public function obtenerTramitesPendientes(Request $request)
    {
        $user = auth()->user();
        
        $query = Tramite::with(['proveedor', 'revisiones', 'datosGenerales', 'contactos', 'revisorDigital', 'citas.asignadoA'])
            ->whereIn('status', [
                TramiteStatus::PENDIENTE->value,
                TramiteStatus::REVISION_DIGITAL->value,
                TramiteStatus::REVISION_PRESENCIAL->value,
                TramiteStatus::REVISION_DOMICILIARIA->value,
                TramiteStatus::PARA_CORRECCION->value,
                TramiteStatus::APROBADO->value,
                TramiteStatus::RECHAZADO->value
            ]);

        // Búsqueda general por RFC, razón social o CURP
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                // Buscar en datos del proveedor
                $q->whereHas('proveedor', function ($proveedorQuery) use ($searchTerm) {
                    $proveedorQuery->where('rfc', 'like', '%' . $searchTerm . '%')
                        ->orWhere('razon_social', 'like', '%' . $searchTerm . '%');
                })
                // Buscar en datos generales del trámite
                ->orWhereHas('datosGenerales', function ($datosQuery) use ($searchTerm) {
                    $datosQuery->where('curp', 'like', '%' . $searchTerm . '%')
                        ->orWhere('razon_social', 'like', '%' . $searchTerm . '%');
                })
                // Buscar en contactos
                ->orWhereHas('contactos', function ($contactoQuery) use ($searchTerm) {
                    $contactoQuery->where('nombre_contacto', 'like', '%' . $searchTerm . '%')
                        ->orWhere('correo_electronico', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Filtro por estado específico
        if ($request->filled('estado')) {
            $query->where('status', $request->estado);
        }

        // Filtro por tipo de trámite
        if ($request->filled('tipo_tramite')) {
            $query->where('tipo_tramite', $request->tipo_tramite);
        }

        // Filtros de fecha
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Filtros de fecha predefinidos
        if ($request->filled('rango_fecha')) {
            $this->aplicarRangoFecha($query, $request->rango_fecha);
        }

        // Filtros de antigüedad
        if ($request->filled('antiguedad')) {
            $this->aplicarFiltroAntiguedad($query, $request->antiguedad);
        }

        // Filtro de prioridad por tipo
        if ($request->filled('tipo_prioridad')) {
            switch ($request->tipo_prioridad) {
                case 'renovacion':
                    $query->where('tipo_tramite', 'Renovacion');
                    break;
                case 'nuevo':
                    $query->where('tipo_tramite', 'Inscripcion');
                    break;
                case 'correccion':
                    $query->where('status', TramiteStatus::PARA_CORRECCION->value);
                    break;
            }
        }

        // Filtro por asignación - MEJORADO
        if ($request->filled('asignado_a')) {
            $this->aplicarFiltroAsignacion($query, $request->asignado_a);
        }

        // Filtro por rol del usuario - Por defecto según el rol del usuario
        $filtroRol = $request->get('filtro_rol');
        
        // Si no se especifica filtro de rol, usar el rol del usuario como predeterminado
        if (!$filtroRol && !$user->hasAnyRole(['Super Admin', 'Admin'])) {
            $userRole = $user->getRoleNames()->first();
            if ($userRole) {
                $filtroRol = strtolower(str_replace(' ', '_', $userRole));
            }
        }
        
        // Aplicar filtro por rol si no es 'todos'
        if ($filtroRol && $filtroRol !== 'todos') {
            switch ($filtroRol) {
                case 'revisor_digital':
                    $query->where('status', 'Revision_Digital');
                    break;
                    
                case 'revisor_presencial':
                    $query->where('status', 'Revision_Presencial');
                    break;
                    
                case 'revisor_domiciliario':
                    $query->where('status', 'Revision_Domiciliaria');
                    break;
            }
        }

        // Filtros específicos para revisores - Por defecto 'mis_pendientes'
        $filtroRevisor = $request->get('filtro_revisor', 'mis_pendientes');
        if ($filtroRevisor && $filtroRevisor !== 'todos') {
            switch ($filtroRevisor) {
                case 'mis_pendientes':
                    // Trámites asignados a mí que están pendientes de revisión
                    $query->where(function ($q) use ($user) {
                        $q->where(function ($subQ) use ($user) {
                            // Revisión digital asignada a mí
                            $subQ->where('status', TramiteStatus::REVISION_DIGITAL->value)
                                 ->where('revisor_digital_id', $user->id);
                        })->orWhere(function ($subQ) use ($user) {
                            // Revisiones presencial/domiciliaria asignadas a mí a través de citas
                            $subQ->whereIn('status', [TramiteStatus::REVISION_PRESENCIAL->value, TramiteStatus::REVISION_DOMICILIARIA->value])
                                 ->whereHas('citas', function ($citaQ) use ($user) {
                                     $citaQ->where('asignado_a', $user->id)
                                           ->where('estado', 'Asignada');
                                 });
                        })->orWhere(function ($subQ) use ($user) {
                            // Revisiones con registro en tabla revisiones
                            $subQ->whereHas('revisiones', function ($revQ) use ($user) {
                                $revQ->where('revisor_id', $user->id)
                                     ->where('estado', 'En_Proceso');
                            });
                        });
                    });
                    break;
                    
                case 'mis_completadas':
                    // Trámites que ya revisé completamente
                    $query->where(function ($q) use ($user) {
                        $q->where(function ($subQ) use ($user) {
                            // Aprobados/rechazados por mí como revisor digital
                            $subQ->whereIn('status', [TramiteStatus::APROBADO->value, TramiteStatus::RECHAZADO->value])
                                 ->where('revisor_digital_id', $user->id);
                        })->orWhere(function ($subQ) use ($user) {
                            // Revisiones completadas en tabla revisiones
                            $subQ->whereHas('revisiones', function ($revQ) use ($user) {
                                $revQ->where('revisor_id', $user->id)
                                     ->where('estado', 'Finalizada');
                            });
                        })->orWhere(function ($subQ) use ($user) {
                            // Citas completadas por mí
                            $subQ->whereHas('citas', function ($citaQ) use ($user) {
                                $citaQ->where('asignado_a', $user->id)
                                       ->where('estado', 'Asistida');
                            });
                        });
                    });
                    break;
                    
                case 'sin_asignar':
                    // Trámites sin revisor asignado
                    $query->where(function ($q) {
                        $q->where(function ($subQ) {
                            // Revisión digital sin revisor
                            $subQ->where('status', TramiteStatus::REVISION_DIGITAL->value)
                                 ->whereNull('revisor_digital_id');
                        })->orWhere(function ($subQ) {
                            // Sin citas asignadas para presencial/domiciliaria
                            $subQ->whereIn('status', [TramiteStatus::REVISION_PRESENCIAL->value, TramiteStatus::REVISION_DOMICILIARIA->value])
                                 ->whereDoesntHave('citas', function ($citaQ) {
                                     $citaQ->where('estado', 'Asignada');
                                 });
                        });
                    });
                    break;
                    
                case 'todos_sin_asignar':
                    // Todos los trámites que NO están asignados al usuario actual (incluyendo los asignados a otros)
                    $query->where(function ($q) use ($user) {
                        $q->where(function ($subQ) use ($user) {
                            // Revisión digital NO asignada a mí (sin asignar o asignada a otro)
                            $subQ->where('status', TramiteStatus::REVISION_DIGITAL->value)
                                 ->where(function ($revQ) use ($user) {
                                     $revQ->whereNull('revisor_digital_id')
                                          ->orWhere('revisor_digital_id', '!=', $user->id);
                                 });
                        })->orWhere(function ($subQ) use ($user) {
                            // Presencial/domiciliaria NO asignada a mí
                            $subQ->whereIn('status', [TramiteStatus::REVISION_PRESENCIAL->value, TramiteStatus::REVISION_DOMICILIARIA->value])
                                 ->where(function ($citaQ) use ($user) {
                                     $citaQ->whereDoesntHave('citas', function ($cQ) {
                                         $cQ->where('estado', 'Asignada');
                                     })->orWhereHas('citas', function ($cQ) use ($user) {
                                         $cQ->where('estado', 'Asignada')
                                            ->where('asignado_a', '!=', $user->id);
                                     });
                                 });
                        })->orWhere(function ($subQ) use ($user) {
                            // Trámites pendientes (todos están disponibles)
                            $subQ->where('status', TramiteStatus::PENDIENTE->value);
                        })->orWhere(function ($subQ) use ($user) {
                            // Otros estados NO asignados a mí
                            $subQ->whereIn('status', [TramiteStatus::PARA_CORRECCION->value, TramiteStatus::APROBADO->value, TramiteStatus::RECHAZADO->value])
                                 ->where(function ($revQ) use ($user) {
                                     $revQ->whereDoesntHave('revisiones', function ($rQ) use ($user) {
                                         $rQ->where('revisor_id', $user->id);
                                     })->orWhere('revisor_digital_id', '!=', $user->id)
                                       ->orWhereNull('revisor_digital_id');
                                 });
                        });
                    });
                    break;
                    
                case 'todos':
                default:
                    // No aplicar filtro adicional, mostrar todos
                    break;
            }
        }

        // Solo mis trámites asignados (filtro legacy mantenido para compatibilidad)
        if ($request->filled('mis_tramites') && $request->mis_tramites) {
            $query->where(function ($q) use ($user) {
                $q->where('revisor_digital_id', $user->id)
                  ->orWhereHas('revisiones', function ($revQ) use ($user) {
                      $revQ->where('revisor_id', $user->id);
                  })
                  ->orWhereHas('citas', function ($citaQ) use ($user) {
                      $citaQ->where('asignado_a', $user->id);
                  });
            });
        }

        // Ordenamiento
        $ordenarPor = $request->get('ordenar_por', 'fecha_desc');
        $this->aplicarOrdenamiento($query, $ordenarPor);

        // Paginación dinámica
        $perPage = $request->get('per_page', 15);
        return $query->paginate($perPage)->appends($request->all());
    }

    // Aplicar rango de fecha predefinido
    private function aplicarRangoFecha($query, $rango)
    {
        $now = now();
        switch ($rango) {
            case 'hoy':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'ayer':
                $query->whereDate('created_at', $now->subDay()->toDateString());
                break;
            case 'semana':
                $query->whereBetween('created_at', [$now->subWeek()->startOfDay(), $now->endOfDay()]);
                break;
            case 'mes':
                $query->whereBetween('created_at', [$now->subMonth()->startOfDay(), $now->endOfDay()]);
                break;
            case 'trimestre':
                $query->whereBetween('created_at', [$now->subMonths(3)->startOfDay(), $now->endOfDay()]);
                break;
        }
    }

    // Aplicar filtro de antigüedad
    private function aplicarFiltroAntiguedad($query, $antiguedad)
    {
        $now = now();
        switch ($antiguedad) {
            case 'hoy':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'semana':
                $query->whereBetween('created_at', [$now->subWeek()->startOfDay(), $now->endOfDay()]);
                break;
            case 'mes':
                $query->whereBetween('created_at', [$now->subMonth()->startOfDay(), $now->endOfDay()]);
                break;
            case 'urgente':
                $query->where('created_at', '<=', $now->subDays(7));
                break;
            case 'muy_urgente':
                $query->where('created_at', '<=', $now->subDays(15));
                break;
        }
    }

    // Aplicar filtro de asignación
    private function aplicarFiltroAsignacion($query, $asignacion)
    {
        switch ($asignacion) {
            case 'mi_usuario':
                $query->whereHas('revisiones', function ($q) {
                    $q->where('revisor_id', auth()->id());
                });
                break;
            case 'sin_asignar':
                $query->whereDoesntHave('revisiones')
                    ->orWhereHas('revisiones', function ($q) {
                        $q->whereNull('revisor_id');
                    });
                break;
            case 'otros':
                $query->whereHas('revisiones', function ($q) {
                    $q->whereNotNull('revisor_id')
                      ->where('revisor_id', '!=', auth()->id());
                });
                break;
        }
    }

    // Aplicar ordenamiento
    private function aplicarOrdenamiento($query, $ordenarPor)
    {
        switch ($ordenarPor) {
            case 'fecha_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'prioridad':
                // Ordenar por antigüedad (más antiguos primero = mayor prioridad)
                $query->orderBy('created_at', 'asc');
                break;
            case 'estado':
                $query->orderBy('status', 'asc')->orderBy('created_at', 'desc');
                break;
            case 'tipo':
                $query->orderBy('tipo_tramite', 'asc')->orderBy('created_at', 'desc');
                break;
            case 'fecha_desc':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
    }

    // Obtener datos para selección de tipo de revisión
    public function obtenerDatosSeleccionTipo(int $tramiteId): array
    {
        $tramite = Tramite::with(['proveedor', 'revisiones'])->findOrFail($tramiteId);
        
        $tiposDisponibles = $this->determinarTiposRevisionDisponibles($tramite);
        $informacionCita = $this->obtenerInformacionCita($tramiteId);
        $personaResponsable = $this->obtenerPersonaResponsable($tramite);
        
        $revisionExistente = RevisionTramite::where('tramite_id', $tramiteId)
            ->where('estado', '!=', 'Finalizada')
            ->orderBy('created_at', 'desc')
            ->first();

        return [
            'tramite' => $tramite,
            'tiposDisponibles' => $tiposDisponibles,
            'informacionCita' => $informacionCita,
            'personaResponsable' => $personaResponsable,
            'revisionExistente' => $revisionExistente
        ];
    }

    // Iniciar proceso de revisión
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
        
        $revision = RevisionTramite::where('tramite_id', $tramiteId)
            ->where('tipo_revision', $tipoRevision)
            ->where('estado', '!=', 'Finalizada')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($revision) {
            if (!$revision->revisor_id) {
                $usuarioActual = Auth::user();
                $rolRequerido = match($tipoRevision) {
                    'Digital' => 'Revisor Digital',
                    'Presencial' => 'Revisor Presencial',
                    'Domiciliaria' => 'Revisor Domiciliario',
                    default => 'Revisor Digital'
                };
                
                if ($usuarioActual && $usuarioActual->hasRole($rolRequerido)) {
                    $revision->update([
                        'revisor_id' => $usuarioActual->id,
                        'estado' => 'En_Proceso',
                        'fecha_inicio' => now()
                    ]);
                }
            } else {
                $revision->update([
                    'estado' => 'En_Proceso',
                    'fecha_inicio' => now()
                ]);
            }
        }
    }

    // Obtener datos base para revisión
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

        // Log para verificar que se están cargando los archivos correctos
        \Log::info("Cargando archivos para trámite {$tramiteId}", [
            'total_archivos' => $archivos->count(),
            'archivos_ids' => $archivos->pluck('id')->toArray(),
            'archivos_nombres' => $archivos->pluck('nombre_original')->toArray()
        ]);

        return [
            'tramite' => $tramite,
            'revision' => $revision,
            'archivos' => $archivos
        ];
    }

    // Preparar archivos para cotejo
    protected function prepararArchivosParaCotejo($archivos): array
    {
        $archivosPreparados = $archivos->map(function($archivo) {
            return [
                'id' => $archivo->id,
                'nombre_original' => $archivo->nombre_original,
                'nombre_catalogo' => $archivo->catalogoArchivo ? $archivo->catalogoArchivo->nombre : null,
                'extension' => $archivo->extension,
                'tamaño' => $archivo->tamaño,
                'status' => $archivo->status,
                'comentario_revision' => $archivo->comentario_revision,
                'fecha_revision' => $archivo->fecha_revision,
                'revisor' => $archivo->revisor ? $archivo->revisor->name : null,
                'catalogo_archivo_id' => $archivo->catalogo_archivo_id,
                'existe_en_storage' => Storage::exists($archivo->ruta),
                'ruta' => $archivo->ruta
            ];
        })->values()->toArray();
        
        return $archivosPreparados;
    }

    // Obtener vista según tipo de revisión
    public function obtenerVistaRevision(string $tipoRevision): string
    {
        return match($tipoRevision) {
            'Digital' => 'revisiones.revision-digital',
            'Presencial' => 'revisiones.revision-presencial',
            'Domiciliaria' => 'revisiones.revision-domiciliaria',
            default => 'revisiones.revision-digital'
        };
    }

    // Determinar tipos disponibles según estado
    private function determinarTiposRevisionDisponibles(Tramite $tramite): array
    {
        if ($tramite->status === TramiteStatus::REVISION_DIGITAL->value) {
            return ['Digital'];
        }
        
        if ($tramite->status === TramiteStatus::REVISION_PRESENCIAL->value) {
            return ['Presencial'];
        }
        
        if ($tramite->status === TramiteStatus::REVISION_DOMICILIARIA->value) {
            return ['Domiciliaria'];
        }

        if ($tramite->status === TramiteStatus::PENDIENTE->value) {
            return ['Digital', 'Presencial', 'Domiciliaria'];
        }

        return [];
    }

    // Obtener información de cita
    private function obtenerInformacionCita(int $tramiteId): ?array
    {
        $cita = Cita::where('tramite_id', $tramiteId)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$cita) {
            return null;
        }

        return [
            'id' => $cita->id,
            'fecha' => $cita->fecha_cita->format('d/m/Y'),
            'hora' => $cita->fecha_cita->format('H:i'),
            'estado' => $cita->estado,
            'tipo' => $cita->tipo_cita
        ];
    }

    // Obtener persona responsable
    private function obtenerPersonaResponsable($tramite): ?array
    {
        $contacto = $tramite->contactos()->first();
        
        if (!$contacto) {
            return null;
        }

        return [
            'nombre' => $contacto->nombre_contacto,
            'cargo' => $contacto->cargo,
            'telefono' => $contacto->telefono,
            'email' => $contacto->correo_electronico
        ];
    }

    // Obtener datos según tipo de revisión
    public function obtenerDatosRevision(int $tramiteId, string $tipoRevision): array
    {
        return match($tipoRevision) {
            'Digital' => app(\App\Services\Revisiones\RevisionDigitalService::class)->obtenerDatosRevisionDigital($tramiteId),
            'Presencial' => app(\App\Services\Revisiones\RevisionPresencialService::class)->obtenerDatosRevisionPresencial($tramiteId),
            'Domiciliaria' => app(\App\Services\Revisiones\RevisionDomiciliariaService::class)->obtenerDatosRevisionDomiciliaria($tramiteId),
            default => app(\App\Services\Revisiones\RevisionDigitalService::class)->obtenerDatosRevisionDigital($tramiteId)
        };
    }

    // Obtener datos para vista solo lectura
    public function obtenerDatosVistaSoloLectura(int $tramiteId): array
    {
        return $this->obtenerDatosRevisionBase($tramiteId);
    }

    // Obtener estado de evaluación de sección
    public function obtenerEstadoSeccion(int $tramiteId, string $seccion): array
    {
        $seccionRevision = \App\Models\SeccionRevision::where('tramite_id', $tramiteId)
            ->where('seccion', $seccion)
            ->first();

        if (!$seccionRevision) {
            return [
                'evaluada' => false,
                'estado' => null,
                'comentario' => null
            ];
        }

        return [
            'evaluada' => true,
            'estado' => $seccionRevision->estado,
            'comentario' => $seccionRevision->comentario
        ];
    }

    // Evaluar sección específica
    public function evaluarSeccion(int $tramiteId, array $datos): array
    {
        $seccionRevision = \App\Models\SeccionRevision::updateOrCreate(
            [
                'tramite_id' => $tramiteId,
                'seccion' => $datos['seccion']
            ],
            [
                'estado' => $datos['estado'],
                'comentario' => $datos['comentario'] ?? null,
                'revisado_por' => auth()->id()
            ]
        );

        return [
            'id' => $seccionRevision->id,
            'estado' => $seccionRevision->estado,
            'comentario' => $seccionRevision->comentario
        ];
    }

    // Obtener estado general del trámite
    public function obtenerEstadoGeneral(int $tramiteId): array
    {
        $tramite = Tramite::findOrFail($tramiteId);
        
        $secciones = ['datos_generales', 'actividades', 'domicilio'];
        if ($tramite->proveedor->tipo_persona === 'Moral') {
            $secciones = array_merge($secciones, ['constitucion', 'accionistas', 'apoderado']);
        }
        $secciones[] = 'archivos';
        
        $seccionesDetalle = [];
        foreach ($secciones as $seccion) {
            $revision = \App\Models\SeccionRevision::where('tramite_id', $tramiteId)
                ->where('seccion', $seccion)
                ->first();
            
            $seccionesDetalle[] = [
                'nombre' => $this->obtenerNombreSeccion($seccion),
                'seccion' => $seccion,
                'estado' => $revision ? $revision->estado : 'Pendiente',
                'comentario' => $revision ? $revision->comentario : null
            ];
        }
        
        $seccionesEvaluadas = \App\Models\SeccionRevision::where('tramite_id', $tramiteId)
            ->whereIn('seccion', $secciones)
            ->count();
        
        $seccionesAprobadas = \App\Models\SeccionRevision::where('tramite_id', $tramiteId)
            ->whereIn('seccion', $secciones)
            ->where('estado', 'Aprobado')
            ->count();
        
        $seccionesRechazadas = \App\Models\SeccionRevision::where('tramite_id', $tramiteId)
            ->whereIn('seccion', $secciones)
            ->where('estado', 'Rechazado')
            ->count();
        
        $totalSecciones = count($secciones);
        
        $estado = TramiteStatus::REVISION_DIGITAL->value;
        if ($seccionesEvaluadas === $totalSecciones) {
            if ($seccionesRechazadas > 0) {
                $estado = TramiteStatus::PARA_CORRECCION->value;
            } else {
                $estado = TramiteStatus::APROBADO->value;
            }
        }
        
        $todasAprobadas = ($seccionesEvaluadas === $totalSecciones) && ($seccionesAprobadas === $totalSecciones);
        
        return [
            'success' => true,
            'estado' => $estado,
            'totalSecciones' => $totalSecciones,
            'seccionesEvaluadas' => $seccionesEvaluadas,
            'seccionesAprobadas' => $seccionesAprobadas,
            'seccionesRechazadas' => $seccionesRechazadas,
            'todas_aprobadas' => $todasAprobadas,
            'secciones' => $seccionesDetalle
        ];
    }
    
    // Obtener nombre legible de sección
    private function obtenerNombreSeccion(string $seccion): string
    {
        return match($seccion) {
            'datos_generales' => 'Datos Generales',
            'actividades' => 'Actividades Económicas',
            'domicilio' => 'Domicilio',
            'constitucion' => 'Constitución',
            'accionistas' => 'Accionistas',
            'apoderado' => 'Apoderado Legal',
            'archivos' => 'Archivos',
            default => ucfirst(str_replace('_', ' ', $seccion))
        };
    }

    // Agendar cita
    public function agendarCita(int $tramiteId, array $datos)
    {
        return $this->citasService->agendarCita($tramiteId, $datos);
    }

    // Reagendar cita existente
    public function reagendarCita(int $citaId, array $datos)
    {
        return $this->citasService->reagendarCita($citaId, $datos);
    }

    // Obtener horarios disponibles
    public function obtenerHorariosDisponibles(string $fecha, string $tipo = 'Presencial'): array
    {
        return $this->citasService->obtenerHorariosDisponibles($fecha, $tipo);
    }

    // Obtener secciones evaluadas para AJAX
    public function obtenerSeccionesEvaluadas(int $tramiteId): array
    {
        $tramite = Tramite::findOrFail($tramiteId);
        
        $secciones = ['datos_generales', 'actividades', 'domicilio'];
        if ($tramite->proveedor->tipo_persona === 'Moral') {
            $secciones = array_merge($secciones, ['constitucion', 'accionistas', 'apoderado']);
        }
        $secciones[] = 'archivos';
        
        $seccionesEvaluadas = [];
        
        foreach ($secciones as $seccion) {
            $revision = SeccionRevision::where('tramite_id', $tramiteId)
                ->where('seccion', $seccion)
                ->first();
            
            $seccionesEvaluadas[$seccion] = [
                'estado' => $revision ? $revision->estado : 'Pendiente',
                'comentario' => $revision ? $revision->comentario : null,
                'fecha_evaluacion' => $revision ? $revision->created_at : null,
                'evaluado_por' => $revision ? $revision->revisado_por : null
            ];
            
            if ($seccion === 'archivos') {
                $archivos = \App\Models\Archivo::where('tramite_id', $tramiteId)->get();
                $archivosIndividuales = [];
                
                foreach ($archivos as $archivo) {
                    $archivosIndividuales[$archivo->id] = [
                        'estado' => $archivo->status ?? 'Pendiente',
                        'comentario' => $archivo->comentario_revision ?? null,
                        'fecha_evaluacion' => $archivo->updated_at,
                        'evaluado_por' => $archivo->revisado_por,
                        'ya_evaluada' => !empty($archivo->status) && $archivo->status !== 'Pendiente'
                    ];
                }
                
                $seccionesEvaluadas[$seccion]['archivos_individuales'] = $archivosIndividuales;
            }
        }
        
        return $seccionesEvaluadas;
    }

    // Obtener revisiones anteriores del proveedor
    public function obtenerInformacionRevisionesAnteriores(int $tramiteId): array
    {
        $tramite = Tramite::findOrFail($tramiteId);
        
        $revisionesAnteriores = Tramite::where('proveedor_id', $tramite->proveedor->id)
            ->where('id', '!=', $tramiteId)
            ->with(['revisiones.revisor'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($tramiteAnterior) {
                return [
                    'id' => $tramiteAnterior->id,
                    'status' => $tramiteAnterior->status,
                    'created_at' => $tramiteAnterior->created_at->format('d/m/Y'),
                    'tipo_tramite' => $tramiteAnterior->tipo_tramite,
                    'revisiones_count' => $tramiteAnterior->revisiones->count(),
                    'ultima_revision' => $tramiteAnterior->revisiones->first() ? [
                        'fecha' => $tramiteAnterior->revisiones->first()->created_at->format('d/m/Y'),
                        'revisor' => $tramiteAnterior->revisiones->first()->revisor->name ?? 'N/A'
                    ] : null
                ];
            })
            ->toArray();
        
        return $revisionesAnteriores;
    }
}