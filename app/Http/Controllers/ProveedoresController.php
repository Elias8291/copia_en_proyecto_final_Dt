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
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Middleware\PermissionMiddleware;

class ProveedoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['publico']);
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

        // Ordenar por ID descendente
        $query->orderBy('id', 'desc');

        // Paginación
        $perPage = $request->get('per_page', 15);
        $todosProveedores = $query->paginate($perPage)->withQueryString();

        // Obtener actividades económicas agrupadas por sector para el modal
        $sectores = Sector::with(['actividades' => function($query) {
            $query->orderBy('nombre');
        }])->orderBy('nombre')->get();

        // Obtener estados geográficos de México para el filtro
        $estados = \App\Models\Estado::orderBy('nombre')->get();

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
        } else {
            // Columnas por defecto si no se especifican
            $columnasSeleccionadas = ['id', 'pv_numero', 'razon_social', 'rfc', 'tipo_persona', 'estado_padron', 'fecha_alta', 'fecha_vencimiento'];
        }

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

        return Excel::download(new ReporteFiltradoExport($filtros, 'personalizado', $columnasSeleccionadas), $nombreArchivo);
    }

    /**
     * Mostrar información pública del proveedor (sin autenticación)
     * Esta ruta es utilizada por el QR code en los oficios
     */
    public function publico(Proveedor $proveedor)
    {
        // Cargar relaciones necesarias
        $proveedor->load(['direcciones.estado']);
        
        // Obtener direcciones del proveedor
        $direcciones = $proveedor->direcciones;
        
        return view('proveedores.publico', compact('proveedor', 'direcciones'));
    }
}
