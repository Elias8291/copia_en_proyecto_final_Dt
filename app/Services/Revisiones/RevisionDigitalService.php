<?php

namespace App\Services\Revisiones;

use App\Services\RevisionService;
use App\Services\HistorialTramitesService;
use App\Services\Tramites\DataRetrievalService;
use App\Services\CitasService;
use App\Services\NotificacionService;
use App\Models\Tramite;
use App\Models\RevisionTramite;
use App\Models\SeccionRevision;
use App\Models\Archivo;
use App\Models\User;
use App\Enums\TramiteStatus;
use App\ViewModels\FormDataViewModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RevisionDigitalService extends RevisionService
{
    public function __construct(CitasService $citasService, NotificacionService $notificacionService)
    {
        parent::__construct($citasService, $notificacionService);
    }

    /**
     * Obtiene datos específicos para revisión digital
     */
    public function obtenerDatosRevisionDigital(int $tramiteId): array
    {
        $datos = $this->obtenerDatosRevisionBase($tramiteId);
        
        // Agregar datos específicos para revisión digital
        $datos['tipoRevision'] = 'Digital';
        $datos['vistaRevision'] = 'revisiones.revision-digital';
        
        // Agregar datos faltantes que necesita la vista
        $datos['estadisticasHistorial'] = $this->obtenerEstadisticasHistorial($tramiteId);
        $datos['historialTramites'] = $this->obtenerHistorialTramites($tramiteId);
        $datos['viewModel'] = $this->obtenerViewModel($tramiteId);
        $datos['archivosSubidos'] = $datos['archivos']; // Alias para compatibilidad
        
        // Nota: Los datos de secciones evaluadas y revisiones anteriores se cargan vía AJAX
        // para mejorar el rendimiento y permitir actualizaciones dinámicas
        
        // Agregar estadísticas y progreso específicos de revisión digital
        $datos['estadisticasRevision'] = $this->obtenerEstadisticasRevisionDigital($tramiteId);
        $datos['progresoRevision'] = $this->obtenerProgresoRevisionDigital($tramiteId);
        $datos['validacionRevision'] = $this->validarRevisionCompleta($tramiteId);
        $datos['configuracionRevision'] = $this->getConfiguracion();
        
        return $datos;
    }

    /**
     * Procesar revisión digital con evaluación por secciones
     * Método específico para revisión digital que extiende la funcionalidad base
     */
    public function procesarRevisionDigital(Tramite $tramite, array $data)
    {
        return DB::transaction(function () use ($tramite, $data) {
            $usuario = Auth::user();
            $tipoRevision = 'Digital';
            
            // 1. Crear o actualizar la revisión del trámite
            $revision = $this->crearOActualizarRevision($tramite, $tipoRevision, $usuario);
            
            // 2. Procesar secciones evaluadas (específico para revisión digital)
            $estadosSecciones = $this->procesarSeccionesDigital($tramite, $data['secciones'] ?? [], $usuario);
            
            // 3. Procesar archivos si hay comentarios/decisiones
            if (isset($data['archivos'])) {
                \Log::info('Procesando archivos en revisión digital', [
                    'tramite_id' => $tramite->id,
                    'archivos_data' => $data['archivos'],
                    'archivos_count' => count($data['archivos'])
                ]);
                $this->procesarArchivos($tramite, $data['archivos'], $usuario);
            } else {
                \Log::info('No se encontraron datos de archivos en la revisión digital', [
                    'tramite_id' => $tramite->id,
                    'data_keys' => array_keys($data)
                ]);
            }
            
            // 4. Determinar estado final del trámite
            \Log::info('Determinando estado final de revisión digital', [
                'tramite_id' => $tramite->id,
                'decision_final' => $data['decision_final'] ?? null,
                'estados_secciones' => $estadosSecciones
            ]);
            
            $estadoFinal = $this->determinarEstadoFinalDigital($data['decision_final'] ?? null, $estadosSecciones);
            
            \Log::info('Estado final determinado', [
                'tramite_id' => $tramite->id,
                'estado_final' => $estadoFinal,
                'decision_final_original' => $data['decision_final'] ?? null
            ]);
            
            // 5. Manejar agendamiento de cita si es necesario
            if (($data['decision_final'] ?? null) === 'agendar_cita') {
                $resultadoCita = $this->citasService->agendarCitaRevisionDigital($tramite->id);
                if (!$resultadoCita['success']) {
                    throw new \Exception('Error al agendar la cita: ' . $resultadoCita['message']);
                }
            }
            
            // 6. Actualizar trámite y revisión
            \Log::info('Actualizando trámite con nuevo estado', [
                'tramite_id' => $tramite->id,
                'estado_anterior' => $tramite->status,
                'estado_nuevo' => $estadoFinal,
                'observaciones' => $data['observaciones_generales'] ?? null
            ]);
            
            $this->actualizarTramite($tramite, $estadoFinal, $data['observaciones_generales'] ?? null);
            $revision->update([
                'estado' => 'Finalizada',
                'fecha_fin' => Carbon::now(),
                'observaciones' => $data['observaciones_generales'] ?? null
            ]);

            // 7. Enviar notificaciones
            $this->enviarNotificacionSegunDecision($tramite, $data['decision_final'] ?? null, $data['observaciones_generales'] ?? null);
            
            return [
                'success' => true,
                'revision' => $revision,
                'estado_tramite' => $estadoFinal,
                'message' => 'Revisión digital procesada exitosamente'
            ];
        });
    }

    /**
     * Procesar secciones específicas para revisión digital
     */
    protected function procesarSeccionesDigital(Tramite $tramite, array $secciones, User $usuario): array
    {
        $estadosSecciones = [];
        
        foreach ($secciones as $seccion => $datos) {
            $decision = $datos['decision'] ?? 'Pendiente';
            $comentario = $datos['comentario'] ?? null;
            
            // Crear o actualizar sección de revisión
            SeccionRevision::updateOrCreate(
                [
                    'tramite_id' => $tramite->id,
                    'seccion' => $seccion
                ],
                [
                    'estado' => $decision,
                    'comentario' => $comentario,
                    'revisor_id' => $usuario->id,
                    'fecha_revision' => Carbon::now()
                ]
            );
            
            $estadosSecciones[$seccion] = $decision;
        }
        
        return $estadosSecciones;
    }

    /**
     * Determinar estado final específico para revisión digital
     */
    protected function determinarEstadoFinalDigital(?string $decisionFinal, array $estadosSecciones): string
    {
        \Log::info('determinarEstadoFinalDigital llamado', [
            'decision_final' => $decisionFinal,
            'estados_secciones' => $estadosSecciones
        ]);
        
        // Si hay una decisión final explícita, usarla
        if ($decisionFinal) {
            $estado = match($decisionFinal) {
                'aprobado' => TramiteStatus::APROBADO->value,
                'rechazado' => TramiteStatus::RECHAZADO->value,
                'correcciones' => TramiteStatus::PARA_CORRECCION->value,
                'agendar_cita' => TramiteStatus::REVISION_PRESENCIAL->value,
                default => TramiteStatus::EN_REVISION->value
            };
            
            \Log::info('Estado determinado por decisión final', [
                'decision_final' => $decisionFinal,
                'estado_resultante' => $estado
            ]);
            
            return $estado;
        }
        
        // Si no hay decisión final, analizar las secciones
        if (empty($estadosSecciones)) {
            return TramiteStatus::EN_REVISION->value;
        }
        
        // Contar estados
        $aprobados = 0;
        $rechazados = 0;
        $total = count($estadosSecciones);
        
        foreach ($estadosSecciones as $estado) {
            if ($estado === 'Aprobado') {
                $aprobados++;
            } elseif ($estado === 'Rechazado') {
                $rechazados++;
            }
        }
        
        // Lógica de decisión basada en secciones
        if ($rechazados > 0) {
            return TramiteStatus::PARA_CORRECCION->value;
        } elseif ($aprobados === $total) {
            return TramiteStatus::APROBADO->value;
        } else {
            return TramiteStatus::EN_REVISION->value;
        }
    }

    /**
     * Obtener configuración específica para revisión digital
     */
    public function getConfiguracion(): array
    {
        return [
            'permite_cotejo' => true,
            'permite_comparacion_documentos' => true,
            'requiere_firma_digital' => false,
            'tiempo_maximo_minutos' => 120,
            'secciones_requeridas' => [
                'datos_generales',
                'actividades',
                'domicilio',
                'archivos'
            ],
            'secciones_opcionales' => [
                'constitucion',
                'accionistas',
                'apoderado'
            ]
        ];
    }

    /**
     * Validar que todas las secciones requeridas hayan sido evaluadas
     */
    public function validarSeccionesRequeridas(array $seccionesEvaluadas): array
    {
        $config = $this->getConfiguracion();
        $seccionesRequeridas = $config['secciones_requeridas'];
        $seccionesFaltantes = [];
        
        foreach ($seccionesRequeridas as $seccion) {
            if (!isset($seccionesEvaluadas[$seccion]) || 
                $seccionesEvaluadas[$seccion]['decision'] === 'Pendiente') {
                $seccionesFaltantes[] = $seccion;
            }
        }
        
        return [
            'valido' => empty($seccionesFaltantes),
            'secciones_faltantes' => $seccionesFaltantes
        ];
    }

    /**
     * Obtener estadísticas de revisión digital
     */
    public function obtenerEstadisticasRevisionDigital(int $tramiteId): array
    {
        $secciones = SeccionRevision::where('tramite_id', $tramiteId)->get();
        
        $estadisticas = [
            'total_secciones' => $secciones->count(),
            'aprobadas' => $secciones->where('estado', 'Aprobado')->count(),
            'rechazadas' => $secciones->where('estado', 'Rechazado')->count(),
            'pendientes' => $secciones->where('estado', 'Pendiente')->count(),
            'porcentaje_completado' => 0
        ];
        
        if ($estadisticas['total_secciones'] > 0) {
            $completadas = $estadisticas['aprobadas'] + $estadisticas['rechazadas'];
            $estadisticas['porcentaje_completado'] = round(($completadas / $estadisticas['total_secciones']) * 100, 2);
        }
        
        return $estadisticas;
    }

    /**
     * Cargar secciones evaluadas anteriormente
     */
    public function cargarSeccionesEvaluadas(int $tramiteId): array
    {
        $seccionesEvaluadas = [];
        
        // Cargar secciones de revisión
        $seccionesRevision = SeccionRevision::where('tramite_id', $tramiteId)
            ->with('revisor')
            ->get();
            
        foreach ($seccionesRevision as $seccion) {
            $seccionesEvaluadas[$seccion->seccion] = [
                'estado' => $seccion->estado,
                'comentario' => $seccion->comentario,
                'revisor' => $seccion->revisor ? $seccion->revisor->name : null,
                'fecha_revision' => $seccion->fecha_revision ? $seccion->fecha_revision->toISOString() : null,
                'ya_evaluada' => true
            ];
        }
        
        // Cargar archivos individuales
        $archivos = Archivo::where('tramite_id', $tramiteId)
            ->with('revisor')
            ->get();
            
        $archivosIndividuales = [];
        foreach ($archivos as $archivo) {
            if ($archivo->status !== 'Pendiente' || $archivo->comentario_revision) {
                $archivosIndividuales[$archivo->id] = [
                    'estado' => $archivo->status,
                    'comentario' => $archivo->comentario_revision,
                    'revisor' => $archivo->revisor ? $archivo->revisor->name : null,
                    'fecha_revision' => $archivo->fecha_revision ? $archivo->fecha_revision->toISOString() : null,
                    'ya_evaluada' => $archivo->status !== 'Pendiente'
                ];
            }
        }
        
        // Agregar archivos individuales a la sección de archivos
        if (!empty($archivosIndividuales)) {
            if (!isset($seccionesEvaluadas['archivos'])) {
                $seccionesEvaluadas['archivos'] = [
                    'estado' => 'Pendiente',
                    'comentario' => null,
                    'revisor' => null,
                    'fecha_revision' => null,
                    'ya_evaluada' => false
                ];
            }
            $seccionesEvaluadas['archivos']['archivos_individuales'] = $archivosIndividuales;
        }
        
        return $seccionesEvaluadas;
    }

    /**
     * Obtener información sobre revisiones anteriores del trámite
     */
    public function obtenerInformacionRevisionesAnteriores(int $tramiteId): array
    {
        $revisiones = RevisionTramite::where('tramite_id', $tramiteId)
            ->where('tipo_revision', 'Digital')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $informacion = [
            'total_revisiones' => $revisiones->count(),
            'ultima_revision' => null,
            'revisiones_anteriores' => []
        ];
        
        if ($revisiones->isNotEmpty()) {
            $ultimaRevision = $revisiones->first();
            $informacion['ultima_revision'] = [
                'id' => $ultimaRevision->id,
                'estado' => $ultimaRevision->estado,
                'fecha_inicio' => $ultimaRevision->created_at,
                'fecha_fin' => $ultimaRevision->fecha_fin,
                'revisor' => $ultimaRevision->revisor ? $ultimaRevision->revisor->name : null,
                'observaciones' => $ultimaRevision->observaciones
            ];
            
            // Obtener revisiones anteriores (excluyendo la actual)
            $revisionesAnteriores = $revisiones->skip(1);
            foreach ($revisionesAnteriores as $revision) {
                $informacion['revisiones_anteriores'][] = [
                    'id' => $revision->id,
                    'estado' => $revision->estado,
                    'fecha_inicio' => $revision->created_at,
                    'fecha_fin' => $revision->fecha_fin,
                    'revisor' => $revision->revisor ? $revision->revisor->name : null,
                    'observaciones' => $revision->observaciones
                ];
            }
        }
        
        return $informacion;
    }

    /**
     * Obtener progreso de revisión digital por sección
     */
    public function obtenerProgresoRevisionDigital(int $tramiteId): array
    {
        $config = $this->getConfiguracion();
        $todasSecciones = array_merge($config['secciones_requeridas'], $config['secciones_opcionales']);
        $progreso = [];
        
        foreach ($todasSecciones as $seccion) {
            $seccionRevision = SeccionRevision::where('tramite_id', $tramiteId)
                ->where('seccion', $seccion)
                ->first();
            
            $progreso[$seccion] = [
                'estado' => $seccionRevision ? $seccionRevision->estado : 'Pendiente',
                'comentario' => $seccionRevision ? $seccionRevision->comentario : null,
                'fecha_revision' => $seccionRevision ? $seccionRevision->fecha_revision : null,
                'revisor' => $seccionRevision && $seccionRevision->revisor ? $seccionRevision->revisor->name : null,
                'requerida' => in_array($seccion, $config['secciones_requeridas'])
            ];
        }
        
        return $progreso;
    }

    /**
     * Validar que la revisión esté completa
     */
    public function validarRevisionCompleta(int $tramiteId): array
    {
        $secciones = SeccionRevision::where('tramite_id', $tramiteId)->get();
        $config = $this->getConfiguracion();
        
        $seccionesRequeridas = $config['secciones_requeridas'];
        $seccionesEvaluadas = $secciones->pluck('estado', 'seccion')->toArray();
        
        $seccionesFaltantes = [];
        foreach ($seccionesRequeridas as $seccion) {
            if (!isset($seccionesEvaluadas[$seccion]) || $seccionesEvaluadas[$seccion] === 'Pendiente') {
                $seccionesFaltantes[] = $seccion;
            }
        }
        
        return [
            'completa' => empty($seccionesFaltantes),
            'secciones_faltantes' => $seccionesFaltantes,
            'total_requeridas' => count($seccionesRequeridas),
            'evaluadas' => count($seccionesEvaluadas)
        ];
    }

    /**
     * Obtener estadísticas del historial de trámites
     */
    private function obtenerEstadisticasHistorial(int $tramiteId): array
    {
        $tramite = Tramite::findOrFail($tramiteId);
        $proveedor = $tramite->proveedor;
        
        // Obtener todos los trámites del mismo proveedor
        $tramitesProveedor = Tramite::where('proveedor_id', $proveedor->id)
            ->with('datosGenerales')
            ->get();
        
        $total = $tramitesProveedor->count();
        $aprobados = $tramitesProveedor->where('status', 'Aprobado')->count();
        $rechazados = $tramitesProveedor->where('status', 'Rechazado')->count();
        $pendientes = $tramitesProveedor->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria'])->count();
        
        return [
            'total' => $total,
            'aprobados' => $aprobados,
            'rechazados' => $rechazados,
            'pendientes' => $pendientes
        ];
    }

    /**
     * Obtener historial de trámites del proveedor
     */
    private function obtenerHistorialTramites(int $tramiteId): \Illuminate\Support\Collection
    {
        $tramite = Tramite::findOrFail($tramiteId);
        $proveedor = $tramite->proveedor;
        
        return Tramite::where('proveedor_id', $proveedor->id)
            ->with(['datosGenerales' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(1);
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($tramite) {
                $datosGenerales = $tramite->datosGenerales->first();
                return [
                    'id' => $tramite->id,
                    'status' => $tramite->status,
                    'razon_social' => $datosGenerales ? $datosGenerales->razon_social : 'Sin datos',
                    'created_at' => $tramite->created_at
                ];
            });
    }

    /**
     * Obtener ViewModel para los formularios
     */
    private function obtenerViewModel(int $tramiteId): \App\ViewModels\FormDataViewModel
    {
        $tramite = Tramite::with([
            'datosGenerales', 
            'actividades', 
            'direcciones.coordenada', 
            'datosConstitutivos.instrumentoNotarial.estado', 
            'accionistas', 
            'apoderadosLegales.instrumentoNotarial.estado', 
            'contactos',
            'archivos.catalogoArchivo'
        ])->findOrFail($tramiteId);
        
        // Obtener domicilio con coordenadas
        $domicilio = $tramite->direcciones->first();
        $domicilioData = [];
        if ($domicilio) {
            $domicilioData = $domicilio->toArray();
            // Asegurar que las coordenadas estén disponibles
            if ($domicilio->coordenada) {
                $domicilioData['coordenada'] = $domicilio->coordenada->toArray();
            }
        }
        
        // Preparar datos en el formato que espera FormDataViewModel
        $formData = [
            'datos_generales' => [
                'razon_social' => $tramite->datosGenerales->first() ? $tramite->datosGenerales->first()->razon_social : '',
                'rfc' => $tramite->proveedor->rfc ?? '',
                'tipo_persona' => $tramite->proveedor->tipo_persona ?? 'Física',
                'curp' => $tramite->datosGenerales->first() ? $tramite->datosGenerales->first()->curp : '',
                'pagina_web' => $tramite->datosGenerales->first() ? $tramite->datosGenerales->first()->pagina_web : '',
                'telefono' => $tramite->datosGenerales->first() ? $tramite->datosGenerales->first()->telefono : '',
            ],
            'actividades' => $tramite->actividades->toArray(),
            'domicilio' => $domicilioData,
            'contacto' => $tramite->contactos->first() ? $tramite->contactos->first()->toArray() : [],
            'archivos' => $tramite->archivos->toArray(),
        ];
        
        // Agregar datos específicos para persona moral
        if ($tramite->proveedor->tipo_persona === 'Moral') {
            $datosConstitutivos = $tramite->datosConstitutivos->first();
            $constitucionData = [];
            
            if ($datosConstitutivos && $datosConstitutivos->instrumentoNotarial) {
                $instrumentoNotarial = $datosConstitutivos->instrumentoNotarial;
                $constitucionData = [
                    'numero_escritura' => $instrumentoNotarial->numero_escritura ?? '',
                    'numero_escritura_constitutiva' => $instrumentoNotarial->numero_escritura_constitutiva ?? '',
                    'fecha_constitucion' => $instrumentoNotarial->fecha_constitucion ?? '',
                    'nombre_notario' => $instrumentoNotarial->nombre_notario ?? '',
                    'numero_notario' => $instrumentoNotarial->numero_notario ?? '',
                    'estado_id' => $instrumentoNotarial->estado_id ?? '',
                    'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
                    'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? '',
                    'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? '',
                ];
            }
            
            $formData['constitucion'] = $constitucionData;
            

            
            $formData['accionistas'] = $tramite->accionistas->toArray();
            
            // Obtener datos del apoderado con instrumento notarial
            $apoderado = $tramite->apoderadosLegales->first();
            $apoderadoData = [];
            
            if ($apoderado) {
                $apoderadoData = $apoderado->toArray();
                
                // Agregar datos del instrumento notarial si existe
                if ($apoderado->instrumentoNotarial) {
                    $instrumentoNotarial = $apoderado->instrumentoNotarial;
                    $apoderadoData = array_merge($apoderadoData, [
                        'numero_escritura' => $instrumentoNotarial->numero_escritura ?? '',
                        'numero_escritura_poder' => $instrumentoNotarial->numero_escritura_constitutiva ?? '',
                        'fecha_poder' => $instrumentoNotarial->fecha_constitucion ?? '',
                        'nombre_notario_poder' => $instrumentoNotarial->nombre_notario ?? '',
                        'numero_notario_poder' => $instrumentoNotarial->numero_notario ?? '',
                        'estado_id' => $instrumentoNotarial->estado_id ?? '',
                        'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
                        'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? '',
                        'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? '',
                    ]);
                }
            }
            
            $formData['apoderado'] = $apoderadoData;
        }
        
        return new \App\ViewModels\FormDataViewModel($formData);
    }
} 