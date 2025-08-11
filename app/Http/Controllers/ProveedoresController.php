<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Actividad;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ProveedoresPorVencerExport;
use App\Exports\DashboardEjecutivoExport;
use App\Exports\ProveedoresGeograficoExport;
use App\Exports\ProveedoresGiroEconomicoExport;
use App\Exports\ProveedoresEstadoPadronExport;
use App\Exports\ListaContactosExport;
use App\Exports\ReporteFiltradoExport;
use App\Exports\ProveedoresTrimestralesExport;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;

class ProveedoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(PermissionMiddleware::class . ':proveedores.ver')->only(['index', 'show']);
        $this->middleware(PermissionMiddleware::class . ':proveedores.crear')->only(['create', 'store']);
        $this->middleware(PermissionMiddleware::class . ':proveedores.editar')->only(['edit', 'update']);
        $this->middleware(PermissionMiddleware::class . ':proveedores.eliminar')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Log para depuración
        \Log::info('Filtros recibidos en index', [
            'estado_padron' => $request->get('estado'),
            'estado_geografico' => $request->get('estado_geografico'),
            'busqueda' => $request->get('search'),
            'tipo_persona' => $request->get('tipo_persona')
        ]);
        
        $query = Proveedor::query();

        // Búsqueda por texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('rfc', 'like', "%{$search}%")
                  ->orWhere('razon_social', 'like', "%{$search}%");
            });
        }

        // Filtro por estado del padrón
        if ($request->filled('estado')) {
            \Log::info('Aplicando filtro de estado', [
                'estado_valor' => $request->estado
            ]);
            $query->where('estado_padron', $request->estado);
        }

        // Filtro por tipo de persona
        if ($request->filled('tipo_persona')) {
            $query->where('tipo_persona', $request->tipo_persona);
        }

        // Filtro por vencimiento
        if ($request->filled('vencimiento')) {
            $vencimiento = $request->vencimiento;
            $hoy = now();
            
            switch ($vencimiento) {
                case 'vencido':
                    $query->where('fecha_vencimiento_padron', '<', $hoy);
                    break;
                case 'por_vencer':
                    $query->whereBetween('fecha_vencimiento_padron', [$hoy, $hoy->copy()->addDays(30)]);
                    break;
                case 'sin_fecha':
                    $query->whereNull('fecha_vencimiento_padron');
                    break;
            }
        }

        // Filtro por año
        if ($request->filled('año')) {
            $query->whereYear('created_at', $request->año);
        }

        // Filtro por sector económico
        if ($request->filled('sector')) {
            $sectorIds = $request->sector;
            if (!is_array($sectorIds)) {
                $sectorIds = [$sectorIds];
            }
            // Filtrar valores vacíos
            $sectorIds = array_filter($sectorIds, function($id) {
                return !empty($id);
            });
            
            if (!empty($sectorIds)) {
                $query->whereHas('tramites.actividades.actividad', function($q) use ($sectorIds) {
                    $q->whereIn('sector_id', $sectorIds);
                });
            }
        }

        // Filtro por actividad económica
        if ($request->filled('actividad_economica')) {
            $actividadIds = $request->actividad_economica;
            if (!is_array($actividadIds)) {
                $actividadIds = [$actividadIds];
            }
            // Filtrar valores vacíos
            $actividadIds = array_filter($actividadIds, function($id) {
                return !empty($id);
            });
            
            if (!empty($actividadIds)) {
                $query->whereHas('tramites.actividades', function($q) use ($actividadIds) {
                    $q->whereIn('actividad_id', $actividadIds);
                });
            }
        }

        // Filtro por estado geográfico
        if ($request->filled('estado_geografico')) {
            $estadoId = $request->estado_geografico;
            
            \Log::info('Aplicando filtro de estado geográfico', [
                'estado_id' => $estadoId
            ]);
            
            $query->whereHas('tramites.direcciones.estado', function($q) use ($estadoId) {
                $q->where('id', $estadoId);
            });
        }

        // Ordenar por ID descendente
        $query->orderBy('id', 'desc');

        // Log de la query antes de ejecutar
        \Log::info('Query SQL generada', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);
        
        // Paginación
        $perPage = $request->get('per_page', 15);
        $todosProveedores = $query->paginate($perPage)->withQueryString();
        
        // Log del resultado
        \Log::info('Resultados de la consulta', [
            'total_registros' => $todosProveedores->total(),
            'registros_en_pagina' => $todosProveedores->count()
        ]);

        // Obtener actividades económicas agrupadas por sector para el modal
        $sectores = Sector::with(['actividades' => function($query) {
            $query->orderBy('nombre');
        }])->orderBy('nombre')->get();

        // Obtener estados geográficos de México para el filtro
        $estados = \App\Models\Estado::orderBy('nombre')->get();

        // Manejar reporte trimestral
        if ($request->get('reporte_trimestral') == '1' && $request->get('formato') == 'excel') {
            \Log::info('Generando reporte trimestral', [
                'ano' => $request->get('ano'),
                'trimestre' => $request->get('trimestre'),
                'fecha_inicio' => $request->get('fecha_inicio'),
                'fecha_fin' => $request->get('fecha_fin')
            ]);
            
            $ano = $request->get('ano');
            $trimestre = $request->get('trimestre');
            $fechaInicio = $request->get('fecha_inicio');
            $fechaFin = $request->get('fecha_fin');
            
            // Crear filtros para el reporte trimestral usando la misma lógica del ReporteFiltradoExport
            $filtrosTrimestre = [
                'estado_padron' => 'Activo',
                'periodo_trimestral' => true,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'trimestre' => $trimestre,
                'ano' => $ano
            ];
            
            $nombreArchivo = "Reporte_Trimestral_Q{$trimestre}_{$ano}_" . date('Y-m-d_H-i-s');
            
            try {
                return Excel::download(new ReporteFiltradoExport($filtrosTrimestre, 'trimestral'), 
                                     $nombreArchivo . '.xlsx');
            } catch (\Exception $e) {
                \Log::error('Error generando reporte trimestral', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json(['error' => 'Error generando el reporte: ' . $e->getMessage()], 500);
            }
        }

        return view('proveedores.index', compact('todosProveedores', 'sectores', 'estados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rfc' => 'required|string|max:13|unique:proveedores,rfc',
            'razon_social' => 'required|string|max:255',
            'tipo_persona' => 'required|in:Física,Moral',
            'estado_padron' => 'required|in:Activo,Inactivo,Vencido,Pendiente,Cancelado',
            'fecha_alta_padron' => 'nullable|date',
            'fecha_vencimiento_padron' => 'nullable|date|after:fecha_alta_padron',
            'pv_numero' => 'nullable|string|max:20'
        ]);

        $validated['usuario_id'] = auth()->id();

        $proveedor = Proveedor::create($validated);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        // Cargar relaciones básicas del proveedor
        $proveedor->load(['usuario', 'tramites' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        // Obtener el último trámite del proveedor
        $ultimoTramite = $proveedor->tramites->first();
        
        $datosCompletos = null;
        
        if ($ultimoTramite) {
            // Cargar todas las relaciones del último trámite
            $ultimoTramite->load([
                'datosGenerales',
                'direcciones.estado',
                'direcciones.coordenada',
                'contactos',
                'actividades.actividad',
                'accionistas',
                'apoderadosLegales.instrumentoNotarial.estado',
                'datosConstitutivos.instrumentoNotarial.estado',
                'archivos.catalogoArchivo'
            ]);

            // Estructurar los datos para la vista - compatibles con los componentes existentes
            $direccion = $ultimoTramite->direcciones->first();
            $datosGenerales = $ultimoTramite->datosGenerales->first();
            $contacto = $ultimoTramite->contactos->first();
            
            $datosCompletos = [
                'datos_generales' => $datosGenerales ? array_merge($datosGenerales->toArray(), [
                    // Agregar datos de contacto a datos generales para que aparezcan en la misma sección
                    'nombre_contacto' => $contacto->nombre_contacto ?? '',
                    'cargo' => $contacto->cargo ?? '',
                    'telefono_contacto' => $contacto->telefono ?? '',
                    'correo_contacto' => $contacto->correo ?? '',
                ]) : [],
                'direccion' => $direccion ? [
                    'codigo_postal' => $direccion->codigo_postal,
                    'estado' => $direccion->estado->nombre ?? '',
                    'estado_id' => $direccion->estado_id,
                    'municipio' => $direccion->municipio,
                    'localidad' => $direccion->localidad,
                    'asentamiento' => $direccion->asentamiento,
                    'colonia' => $direccion->asentamiento, // alias para compatibilidad
                    'calle' => $direccion->calle,
                    'numero_exterior' => $direccion->numero_exterior,
                    'numero_interior' => $direccion->numero_interior,
                    'entre_calle' => $direccion->entre_calle,
                    'y_calle' => $direccion->y_calle,
                    'latitud' => $direccion->coordenada->latitud ?? null,
                    'longitud' => $direccion->coordenada->longitud ?? null
                ] : [],
                'actividades_economicas' => $ultimoTramite->actividades->map(function($actividad) {
                    return [
                        'id' => $actividad->actividad_id,
                        'nombre' => $actividad->actividad->nombre ?? 'Actividad no encontrada',
                        'descripcion' => $actividad->actividad->descripcion ?? '',
                        'sector_id' => $actividad->actividad->sector_id ?? null
                    ];
                })->toArray(),
                'accionistas' => $ultimoTramite->accionistas->toArray(),
                'apoderado_legal' => $this->formatApoderadoData($ultimoTramite->apoderadosLegales->first()),
                'constitucion' => $this->formatConstitucionData($ultimoTramite->datosConstitutivos->first()),
                'documentos' => $ultimoTramite->archivos->map(function($archivo) {
                    return [
                        'id' => $archivo->id,
                        'nombre' => $archivo->catalogoArchivo->nombre ?? 'Documento',
                        'ruta' => $archivo->ruta_archivo,
                        'tipo' => $archivo->catalogoArchivo->tipo ?? 'general',
                        'status' => $archivo->status
                    ];
                })->toArray()
            ];
        }

        return view('proveedores.show', compact('proveedor', 'ultimoTramite', 'datosCompletos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $validated = $request->validate([
            'rfc' => 'required|string|max:13|unique:proveedores,rfc,' . $proveedor->id,
            'razon_social' => 'required|string|max:255',
            'tipo_persona' => 'required|in:Física,Moral',
            'estado_padron' => 'required|in:Activo,Inactivo,Vencido,Pendiente,Cancelado',
            'fecha_alta_padron' => 'nullable|date',
            'fecha_vencimiento_padron' => 'nullable|date|after:fecha_alta_padron',
            'pv_numero' => 'nullable|string|max:20'
        ]);

        $proveedor->update($validated);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }

    /**
     * Formatear datos del apoderado legal para el componente
     */
    private function formatApoderadoData($apoderado)
    {
        if (!$apoderado) {
            return [];
        }

        $instrumentoNotarial = $apoderado->instrumentoNotarial;
        
        return [
            'nombre_apoderado' => $apoderado->nombre_apoderado,
            'rfc' => $apoderado->rfc,
            'numero_escritura_poder' => $instrumentoNotarial->numero_escritura ?? '',
            'fecha_poder' => $instrumentoNotarial->fecha_constitucion ?? '',
            'nombre_notario_poder' => $instrumentoNotarial->nombre_notario ?? '',
            'numero_notario_poder' => $instrumentoNotarial->numero_notario ?? '',
            'numero_escritura_constitutiva_poder' => $apoderado->numero_escritura_constitutiva_poder ?? '',
            'numero_registro_publico_poder' => $apoderado->numero_registro_publico_poder ?? '',
            'fecha_inscripcion_poder' => $apoderado->fecha_inscripcion_poder ?? '',
            'estado_id' => $instrumentoNotarial->estado_id ?? '',
            'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
        ];
    }

    /**
     * Formatear datos constitutivos para el componente
     */
    private function formatConstitucionData($constitutivo)
    {
        if (!$constitutivo) {
            return [];
        }

        $instrumentoNotarial = $constitutivo->instrumentoNotarial;
        
        return [
            'estado_id' => $instrumentoNotarial->estado_id ?? '',
            'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
            'numero_escritura' => $instrumentoNotarial->numero_escritura ?? '',
            'numero_escritura_constitutiva' => $instrumentoNotarial->numero_escritura_constitutiva ?? '',
            'fecha_constitucion' => $instrumentoNotarial->fecha_constitucion ?? '',
            'nombre_notario' => $instrumentoNotarial->nombre_notario ?? '',
            'numero_notario' => $instrumentoNotarial->numero_notario ?? '',
            'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? '',
            'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? '',
        ];
    }

    /**
     * Exportar reporte de proveedores por vencer en Excel
     */
    public function exportarProveedoresPorVencer(Request $request)
    {
        $diasVencer = (int) $request->get('dias', 30); // Por defecto 30 días
        
        $filename = 'proveedores_activos_por_vencer_' . $diasVencer . '_dias_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ProveedoresPorVencerExport($diasVencer), $filename);
    }

    /**
     * Exportar Dashboard Ejecutivo
     */
    public function exportarDashboardEjecutivo()
    {
        $filename = 'dashboard_ejecutivo_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new DashboardEjecutivoExport(), $filename);
    }

    /**
     * Exportar Reporte Geográfico
     */
    public function exportarReporteGeografico()
    {
        $filename = 'proveedores_distribucion_geografica_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ProveedoresGeograficoExport(), $filename);
    }

    /**
     * Exportar Reporte por Giros Económicos
     */
    public function exportarReporteGiroEconomico()
    {
        $filename = 'proveedores_por_giro_economico_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ProveedoresGiroEconomicoExport(), $filename);
    }

    /**
     * Exportar Reporte por Estado del Padrón
     */
    public function exportarReporteEstadoPadron(Request $request)
    {
        $estadoFiltro = $request->get('estado'); // 'Activo', 'Vencido', 'Pendiente', 'Inactivo'
        
        $filename = 'proveedores_estado_padron';
        if ($estadoFiltro) {
            $filename .= '_' . strtolower($estadoFiltro);
        }
        $filename .= '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ProveedoresEstadoPadronExport($estadoFiltro), $filename);
    }

    /**
     * Exportar Lista de Contactos
     */
    public function exportarListaContactos(Request $request)
    {
        $tipoLista = $request->get('tipo', 'todos'); // 'todos', 'por_vencer', 'activos', 'vencidos'
        
        $filename = 'lista_contactos_' . $tipoLista . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ListaContactosExport($tipoLista), $filename);
    }

    /**
     * Exportar Reporte Filtrado de Proveedores
     */
    public function exportarReporteFiltrado(Request $request)
    {
        // Log para depuración
        \Log::info('Exportación iniciada', [
            'parametros' => $request->all(),
            'columnas' => $request->get('columnas')
        ]);
        
        // Obtener todos los filtros aplicados en la vista
        $filtros = [
            'search' => $request->get('search'),
            'estado_padron' => $request->get('estado'), // Mapear 'estado' a 'estado_padron'
            'tipo_persona' => $request->get('tipo_persona'),
            'vencimiento' => $request->get('vencimiento'),
            'año' => $request->get('año'),
            'sector' => $request->get('sector'),
            'actividad_economica' => $request->get('actividad_economica', []),
            'estado_geografico' => $request->get('estado_geografico'),
            'municipio' => $request->get('municipio'),
            'fecha_alta_desde' => $request->get('fecha_alta_desde'),
            'fecha_alta_hasta' => $request->get('fecha_alta_hasta'),
            'fecha_vencimiento_desde' => $request->get('fecha_vencimiento_desde'),
            'fecha_vencimiento_hasta' => $request->get('fecha_vencimiento_hasta'),
            'dias_vencer' => $request->get('dias_vencer')
        ];

        // Obtener columnas seleccionadas
        $columnasSeleccionadas = $request->get('columnas');
        if ($columnasSeleccionadas) {
            $columnasSeleccionadas = explode(',', $columnasSeleccionadas);
            // Limpiar espacios en blanco
            $columnasSeleccionadas = array_map('trim', $columnasSeleccionadas);
            // Filtrar columnas vacías
            $columnasSeleccionadas = array_filter($columnasSeleccionadas);
        } else {
            // Columnas por defecto si no se especifican
            $columnasSeleccionadas = ['id', 'pv_numero', 'razon_social', 'rfc', 'tipo_persona', 'estado_padron', 'fecha_alta', 'fecha_vencimiento'];
        }
        
        \Log::info('Columnas seleccionadas para exportar', [
            'columnas' => $columnasSeleccionadas,
            'cantidad' => count($columnasSeleccionadas)
        ]);

        // Filtrar solo los filtros que tienen valor
        $filtros = array_filter($filtros, function($valor) {
            if (is_array($valor)) {
                return !empty($valor);
            }
            return !is_null($valor) && $valor !== '';
        });

        // Generar nombre del archivo dinámico
        $nombreArchivo = 'proveedores_filtrado';
        
        if (!empty($filtros['estado_padron'])) {
            $nombreArchivo .= '_' . strtolower($filtros['estado_padron']);
        }
        
        if (!empty($filtros['tipo_persona'])) {
            $nombreArchivo .= '_' . strtolower(str_replace(' ', '_', $filtros['tipo_persona']));
        }
        
        if (!empty($filtros['vencimiento'])) {
            $nombreArchivo .= '_' . $filtros['vencimiento'];
        }
        
        if (!empty($filtros['sector'])) {
            $sectorCount = is_array($filtros['sector']) ? count($filtros['sector']) : 1;
            $nombreArchivo .= '_sectores_' . $sectorCount;
        }
        
        if (!empty($filtros['actividad_economica'])) {
            $nombreArchivo .= '_actividades_' . count($filtros['actividad_economica']);
        }

        $nombreArchivo .= '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        \Log::info('Generando archivo Excel', [
            'filtros_aplicados' => $filtros,
            'nombre_archivo' => $nombreArchivo,
            'columnas' => $columnasSeleccionadas
        ]);

        try {
            return Excel::download(new ReporteFiltradoExport($filtros, 'personalizado', $columnasSeleccionadas), $nombreArchivo);
        } catch (\Exception $e) {
            \Log::error('Error al generar archivo Excel', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al generar el archivo de exportación',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar vista de reportes trimestrales
     */
    public function reportesTrimestrales()
    {
        // Verificar permisos
        if (!auth()->user()->can('proveedores.reportes.trimestrales')) {
            abort(403, 'No tienes permisos para acceder a los reportes trimestrales.');
        }

        // Obtener años disponibles basados en las fechas de creación de proveedores
        $años = Proveedor::selectRaw('YEAR(created_at) as año')
            ->distinct()
            ->orderBy('año', 'desc')
            ->pluck('año');

        // Si no hay años, agregar el año actual por defecto
        if ($años->isEmpty()) {
            $años = collect([date('Y')]);
        }

        return view('reportes.trimestrales', compact('años'));
    }

    /**
     * Generar reporte trimestral
     */
    public function generarReporteTrimestral(Request $request)
    {
        $request->validate([
            'año' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'trimestre' => 'required|integer|min:1|max:4',
            'formato' => 'required|in:excel,pdf'
        ]);

        $año = $request->año;
        $trimestre = $request->trimestre;
        $formato = $request->formato;

        // Calcular fechas del trimestre
        $fechas = $this->calcularFechasTrimestre($año, $trimestre);
        
        \Log::info('Generando reporte trimestral', [
            'año' => $año,
            'trimestre' => $trimestre,
            'fecha_inicio' => $fechas['inicio'],
            'fecha_fin' => $fechas['fin'],
            'formato' => $formato
        ]);

        // Obtener proveedores activos en el trimestre
        $proveedores = $this->obtenerProveedoresActivosTrimestre($fechas['inicio'], $fechas['fin']);

        \Log::info('Proveedores encontrados para el trimestre', [
            'total' => $proveedores->count()
        ]);

        // Generar export
        $export = new ProveedoresTrimestralesExport($proveedores, $año, $trimestre, $fechas);
        $filename = "reporte_trimestral_{$año}_T{$trimestre}_" . now()->format('Y_m_d_H_i_s') . '.xlsx';

        return Excel::download($export, $filename);
    }

    /**
     * Calcular fechas de inicio y fin del trimestre
     */
    private function calcularFechasTrimestre($año, $trimestre)
    {
        switch ($trimestre) {
            case 1:
                return [
                    'inicio' => "{$año}-01-01",
                    'fin' => "{$año}-03-31"
                ];
            case 2:
                return [
                    'inicio' => "{$año}-04-01",
                    'fin' => "{$año}-06-30"
                ];
            case 3:
                return [
                    'inicio' => "{$año}-07-01",
                    'fin' => "{$año}-09-30"
                ];
            case 4:
                return [
                    'inicio' => "{$año}-10-01",
                    'fin' => "{$año}-12-31"
                ];
        }
    }

    /**
     * Obtener proveedores activos en el trimestre
     */
    private function obtenerProveedoresActivosTrimestre($fechaInicio, $fechaFin)
    {
        return Proveedor::with(['tramites.direcciones.estado', 'actividades', 'sectores'])
            ->where(function($query) use ($fechaInicio, $fechaFin) {
                $query->where(function($q) use ($fechaInicio, $fechaFin) {
                    // Proveedor creado antes o durante el trimestre
                    $q->where('created_at', '<=', $fechaFin)
                      // Y que no haya vencido antes del trimestre O que no tenga fecha de vencimiento
                      ->where(function($subQuery) use ($fechaInicio) {
                          $subQuery->whereNull('fecha_vencimiento_padron')
                                   ->orWhere('fecha_vencimiento_padron', '>=', $fechaInicio);
                      });
                });
            })
            ->orderBy('razon_social')
            ->get();
    }
}
