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

class RevisionService
{
    protected CitasService $citasService;
    protected NotificacionService $notificacionService;

    public function __construct(CitasService $citasService, NotificacionService $notificacionService = null)
    {
        $this->citasService = $citasService;
        $this->notificacionService = $notificacionService;
    }

    /**
     * Obtener trámites pendientes de revisión
     */
    public function obtenerTramitesPendientes(Request $request)
    {
        $query = Tramite::with(['proveedor', 'revisiones'])
            ->whereIn('status', [
                TramiteStatus::PENDIENTE->value,
                TramiteStatus::REVISION_DIGITAL->value,
                TramiteStatus::REVISION_PRESENCIAL->value,
                TramiteStatus::REVISION_DOMICILIARIA->value
            ]);

        // Filtros
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('rfc')) {
            $query->whereHas('proveedor', function ($q) use ($request) {
                $q->where('rfc', 'like', '%' . $request->rfc . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * Obtener datos para selección de tipo de revisión
     */
    public function obtenerDatosSeleccionTipo(int $tramiteId): array
    {
        $tramite = Tramite::with(['proveedor', 'revisiones'])->findOrFail($tramiteId);
        
        $tiposDisponibles = $this->determinarTiposRevisionDisponibles($tramite);
        $informacionCita = $this->obtenerInformacionCita($tramiteId);
        $personaResponsable = $this->obtenerPersonaResponsable($tramite);
        
        // Buscar revisión existente
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

    /**
     * Iniciar revisión
     */
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
        
        // Buscar la revisión existente para este trámite y tipo
        $revision = RevisionTramite::where('tramite_id', $tramiteId)
            ->where('tipo_revision', $tipoRevision)
            ->where('estado', '!=', 'Finalizada')
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($revision) {
            // Si no hay revisor asignado y el usuario actual tiene el rol correspondiente, asignarlo
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
                    
                    \Log::info('Revisor asignado automáticamente a la revisión', [
                        'tramite_id' => $tramiteId,
                        'tipo_revision' => $tipoRevision,
                        'revisor_id' => $usuarioActual->id
                    ]);
                }
            } else {
                // Si ya hay revisor asignado, solo cambiar el estado a En_Proceso
                $revision->update([
                    'estado' => 'En_Proceso',
                    'fecha_inicio' => now()
                ]);
            }
        }
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
     * Prepara los datos de archivos para el cotejo
     */
    protected function prepararArchivosParaCotejo($archivos): array
    {
        \Log::info("=== PREPARANDO ARCHIVOS PARA COTEJO ===");
        \Log::info("Total archivos recibidos: " . $archivos->count());
        
        $archivosPreparados = $archivos->filter(function($archivo) {
            $exists = Storage::exists($archivo->ruta);
            \Log::info("Archivo ID {$archivo->id}: {$archivo->nombre_original}");
            \Log::info("  Ruta: {$archivo->ruta}");
            \Log::info("  Existe en storage: " . ($exists ? 'SI' : 'NO'));
            \Log::info("  Storage path completo: " . storage_path('app/' . $archivo->ruta));
            \Log::info("  Archivo físico existe: " . (file_exists(storage_path('app/' . $archivo->ruta)) ? 'SI' : 'NO'));
            
            // Temporalmente, vamos a incluir todos los archivos para debugging
            return true; // Cambiar de return Storage::exists($archivo->ruta); a return true;
        })->map(function($archivo) {
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
                'catalogo_archivo_id' => $archivo->catalogo_archivo_id
            ];
        })->values()->toArray();
        
        \Log::info("Total archivos preparados: " . count($archivosPreparados));
        \Log::info("Archivos preparados: " . json_encode($archivosPreparados, JSON_PRETTY_PRINT));
        \Log::info("=== FIN PREPARACIÓN ARCHIVOS COTEJO ===");
        
        return $archivosPreparados;
    }

    /**
     * Obtener vista de revisión según el tipo
     */
    public function obtenerVistaRevision(string $tipoRevision): string
    {
        return match($tipoRevision) {
            'Digital' => 'revisiones.revision-digital',
            'Presencial' => 'revisiones.revision-presencial',
            'Domiciliaria' => 'revisiones.revision-domiciliaria',
            default => 'revisiones.revision-digital'
        };
    }

    /**
     * Determinar tipos de revisión disponibles
     */
    private function determinarTiposRevisionDisponibles(Tramite $tramite): array
    {
        // Si el trámite ya está en algún tipo de revisión, solo permitir ese tipo
        if ($tramite->status === TramiteStatus::REVISION_DIGITAL->value) {
            return ['Digital'];
        }
        
        if ($tramite->status === TramiteStatus::REVISION_PRESENCIAL->value) {
            return ['Presencial'];
        }
        
        if ($tramite->status === TramiteStatus::REVISION_DOMICILIARIA->value) {
            return ['Domiciliaria'];
        }

        // Si está pendiente, permitir todos los tipos
        if ($tramite->status === TramiteStatus::PENDIENTE->value) {
            return ['Digital', 'Presencial', 'Domiciliaria'];
        }

        // Para otros estados, no permitir ningún tipo de revisión
        return [];
    }

    /**
     * Obtener información de cita
     */
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

    /**
     * Obtener persona responsable del trámite
     */
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

    /**
     * Obtener datos de revisión según el tipo
     */
    public function obtenerDatosRevision(int $tramiteId, string $tipoRevision): array
    {
        return match($tipoRevision) {
            'Digital' => app(\App\Services\Revisiones\RevisionDigitalService::class)->obtenerDatosRevisionDigital($tramiteId),
            'Presencial' => app(\App\Services\Revisiones\RevisionPresencialService::class)->obtenerDatosRevisionPresencial($tramiteId),
            'Domiciliaria' => app(\App\Services\Revisiones\RevisionDomiciliariaService::class)->obtenerDatosRevisionDomiciliaria($tramiteId),
            default => app(\App\Services\Revisiones\RevisionDigitalService::class)->obtenerDatosRevisionDigital($tramiteId)
        };
    }

    /**
     * Obtener datos para vista de solo lectura
     */
    public function obtenerDatosVistaSoloLectura(int $tramiteId): array
    {
        return $this->obtenerDatosRevisionBase($tramiteId);
    }

    // Obtener estado de evaluación de una sección específica
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

    // Evaluar una sección específica
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
        
        // Obtener información detallada de cada sección
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
        
        // Determinar estado del trámite
        $estado = TramiteStatus::REVISION_DIGITAL->value;
        if ($seccionesEvaluadas === $totalSecciones) {
            if ($seccionesRechazadas > 0) {
                $estado = TramiteStatus::PARA_CORRECCION->value;
            } else {
                $estado = TramiteStatus::APROBADO->value;
            }
        }
        
        // Verificar si todas las secciones están aprobadas
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
    
    // Obtener nombre legible de la sección
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

    /**
     * Agendar cita para revisión
     */
    public function agendarCita(int $tramiteId, array $datos)
    {
        return $this->citasService->agendarCita($tramiteId, $datos);
    }

    /**
     * Reagendar cita existente
     */
    public function reagendarCita(int $citaId, array $datos)
    {
        return $this->citasService->reagendarCita($citaId, $datos);
    }

    /**
     * Obtener horarios disponibles para una fecha
     */
    public function obtenerHorariosDisponibles(string $fecha, string $tipo = 'Presencial'): array
    {
        return $this->citasService->obtenerHorariosDisponibles($fecha, $tipo);
    }
} 