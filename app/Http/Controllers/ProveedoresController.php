<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Actividad;
use App\Models\Sector;
use App\Models\Tramite;
use App\Services\Tramites\DataRetrievalService;
use App\ViewModels\FormDataViewModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProveedoresExcelCompleto;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Carbon\Carbon;

class ProveedoresController extends Controller
{
    private DataRetrievalService $dataRetrievalService;

    public function __construct(DataRetrievalService $dataRetrievalService)
    {
        $this->dataRetrievalService = $dataRetrievalService;
        $this->middleware('auth');
        $this->middleware(PermissionMiddleware::class . ':proveedores.ver')->only(['index', 'show']);
        $this->middleware(PermissionMiddleware::class . ':proveedores.crear')->only(['create', 'store']);
        $this->middleware(PermissionMiddleware::class . ':proveedores.editar')->only(['edit', 'update']);
        $this->middleware(PermissionMiddleware::class . ':proveedores.eliminar')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Proveedor::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('rfc', 'like', "%{$search}%")
                  ->orWhere('razon_social', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) $query->where('estado_padron', $request->estado);
        if ($request->filled('tipo_persona')) $query->where('tipo_persona', $request->tipo_persona);

        if ($request->filled('vencimiento')) {
            $hoy = now();
            switch ($request->vencimiento) {
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

        if ($request->filled('año')) {
            $query->whereYear('fecha_alta_padron', $request->año);
        }

        // Filtros basados en el último trámite del proveedor
        if ($request->filled('sector')) {
            $sectorIds = $request->sector;
            if (!is_array($sectorIds)) {
                $sectorIds = [$sectorIds];
            }
            $sectorIds = array_filter($sectorIds, function($id) {
                return !empty($id);
            });
            
            if (!empty($sectorIds)) {
                $query->whereHas('tramites', function($tramiteQuery) use ($sectorIds) {
                    $tramiteQuery->whereRaw('id = (SELECT MAX(id) FROM tramites t WHERE t.proveedor_id = tramites.proveedor_id)')
                        ->whereHas('actividades.actividad', function($q) use ($sectorIds) {
                            $q->whereIn('sector_id', $sectorIds);
                        });
                });
            }
        }

        if ($request->filled('actividad_economica')) {
            $actividadIds = $request->actividad_economica;
            if (!is_array($actividadIds)) {
                $actividadIds = [$actividadIds];
            }
            $actividadIds = array_filter($actividadIds, function($id) {
                return !empty($id);
            });
            
            if (!empty($actividadIds)) {
                $query->whereHas('tramites', function($tramiteQuery) use ($actividadIds) {
                    $tramiteQuery->whereRaw('id = (SELECT MAX(id) FROM tramites t WHERE t.proveedor_id = tramites.proveedor_id)')
                        ->whereHas('actividades', function($q) use ($actividadIds) {
                            $q->whereIn('actividad_id', $actividadIds);
                        });
                });
            }
        }

        if ($request->filled('estado_geografico')) {
            $query->whereHas('tramites', function($tramiteQuery) use ($request) {
                $tramiteQuery->whereRaw('id = (SELECT MAX(id) FROM tramites t WHERE t.proveedor_id = tramites.proveedor_id)')
                    ->whereHas('direcciones.estado', function($q) use ($request) {
                        $q->where('id', $request->estado_geografico);
                    });
            });
        }

        // Filtro para proveedores con historial (múltiples trámites)
        if ($request->filled('con_historial')) {
            switch ($request->con_historial) {
                case 'si':
                    // Proveedores con 2 o más trámites
                    $query->has('tramites', '>=', 2);
                    break;
                case 'no':
                    // Proveedores con solo 1 trámite o ninguno
                    $query->has('tramites', '<=', 1);
                    break;
                case 'sin_tramites':
                    // Proveedores sin trámites
                    $query->doesntHave('tramites');
                    break;
                case 'renovadores':
                    // Proveedores que renuevan constantemente (patrón de renovación)
                    $query->whereHas('tramites', function($q) {
                        $q->where('tipo_tramite', 'Renovacion');
                    }, '>=', 2) // Al menos 2 renovaciones
                    ->whereHas('tramites', function($q) {
                        $q->where('tipo_tramite', 'Inscripcion');
                    }); // Y al menos 1 inscripción inicial
                    break;
            }
        }



        // Filtro por trámites específicos (tipo y/o año)
        if ($request->filled('tipo_tramite_año') || $request->filled('año_especifico')) {
            $query->whereHas('tramites', function($q) use ($request) {
                if ($request->filled('tipo_tramite_año')) {
                    $q->where('tipo_tramite', $request->tipo_tramite_año);
                }
                if ($request->filled('año_especifico')) {
                    $q->whereRaw('YEAR(COALESCE(fecha_finalizacion, fecha_inicio, created_at)) = ?', [$request->año_especifico]);
                }
            });
        }

        // Filtro trimestral - Proveedores activos en el período seleccionado
        if ($request->filled('año_trimestre') && $request->filled('trimestre')) {
            $año = (int) $request->año_trimestre;
            $trimestre = (int) $request->trimestre;
            
            // Definir rangos de meses por trimestre
            $rangosTrimestrales = [
                1 => ['inicio' => 1, 'fin' => 3],   // Q1: Enero-Marzo
                2 => ['inicio' => 4, 'fin' => 6],   // Q2: Abril-Junio  
                3 => ['inicio' => 7, 'fin' => 9],   // Q3: Julio-Septiembre
                4 => ['inicio' => 10, 'fin' => 12]  // Q4: Octubre-Diciembre
            ];
            
            if (isset($rangosTrimestrales[$trimestre])) {
                $mesInicio = $rangosTrimestrales[$trimestre]['inicio'];
                $mesFin = $rangosTrimestrales[$trimestre]['fin'];
                
                // Fechas del trimestre
                $inicioTrimestre = Carbon::create($año, $mesInicio, 1)->startOfMonth();
                $finTrimestre = Carbon::create($año, $mesFin, 1)->endOfMonth();
                
                // Filtrar proveedores que estuvieron activos durante este período
                // Un proveedor está activo si su fecha de vencimiento es posterior al inicio del trimestre
                $query->where(function($q) use ($inicioTrimestre, $finTrimestre) {
                    $q->where('fecha_vencimiento_padron', '>=', $inicioTrimestre)
                      ->where('fecha_alta_padron', '<=', $finTrimestre);
                });
            }
        }

        // Ordenamiento
        $ordenPor = $request->get('orden_por', 'id');
        $direccion = $request->get('direccion', 'desc');
        
        switch ($ordenPor) {
            case 'fecha_alta_padron':
                $query->orderBy('fecha_alta_padron', $direccion);
                break;
            case 'razon_social':
                $query->orderBy('razon_social', $direccion);
                break;
            case 'rfc':
                $query->orderBy('rfc', $direccion);
                break;
            case 'estado_padron':
                $query->orderBy('estado_padron', $direccion);
                break;
            case 'fecha_vencimiento_padron':
                $query->orderBy('fecha_vencimiento_padron', $direccion);
                break;
            default:
                $query->orderBy('id', $direccion);
        }
        $perPage = $request->get('per_page', 15);
        $todosProveedores = $query->paginate($perPage)->withQueryString();

        $sectores = Sector::with(['actividades' => fn($q) => $q->orderBy('nombre')])
            ->orderBy('nombre')->get(['id','nombre']);
        $estados = \App\Models\Estado::orderBy('nombre')->get();

        if ($request->wantsJson() || $request->get('format') === 'json') {
            return response()->json([
                'sectores' => $sectores->map(fn($s) => ['id' => $s->id, 'nombre' => $s->nombre])->values(),
                'actividades' => Actividad::orderBy('nombre')->get(['id','nombre'])
                    ->map(fn($a) => ['id' => $a->id, 'nombre' => $a->nombre])->values(),
            ]);
        }

        if ($request->get('export') == 'excel') {
            $filtros = array_filter($request->only(['search', 'estado', 'tipo_persona', 'vencimiento', 'año', 'sector', 'actividad_economica', 'estado_geografico']), fn($v) => is_array($v) ? !empty($v) : $v !== null && $v !== '');
            $nombreArchivo = 'proveedores_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download(new ProveedoresExcelCompleto($filtros), $nombreArchivo);
        }

        // Obtener años disponibles de fechas de alta de padrón
        $añosDisponibles = \App\Models\Proveedor::whereNotNull('fecha_alta_padron')
            ->selectRaw('DISTINCT YEAR(fecha_alta_padron) as año')
            ->orderBy('año', 'desc')
            ->pluck('año')
            ->filter(); // Eliminar valores nulos

        return view('proveedores.index', compact('todosProveedores', 'sectores', 'estados', 'añosDisponibles'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

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
        Proveedor::create($validated);
        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function show(Request $request, Proveedor $proveedor)
    {
        // Determinar el orden del historial
        $ordenHistorial = $request->get('orden_historial', 'reciente'); // 'reciente' o 'pasados'
        
        // Cargar trámites con el orden solicitado
        if ($ordenHistorial === 'pasados') {
            // Ordenar por fecha más antigua primero
            $proveedor->load(['usuario', 'tramites' => function($q) {
                $q->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) ASC');
            }]);
        } else {
            // Ordenar por fecha más reciente primero (default)
            $proveedor->load(['usuario', 'tramites' => function($q) {
                $q->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) DESC');
            }]);
        }
        
        $ultimoTramite = $proveedor->tramites->first();
        $datosCompletos = null;

        if ($ultimoTramite) {
            $datosServicio = $this->dataRetrievalService->obtenerDatosTramite($ultimoTramite);
            $datosServicio['datos_generales']['tipo_persona'] = $ultimoTramite->proveedor->tipo_persona;
            $viewModel = new FormDataViewModel($datosServicio);
            $datosCompletos = $viewModel->getAllFormData();
        }

        return view('proveedores.show', compact('proveedor', 'ultimoTramite', 'datosCompletos', 'ordenHistorial'));
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

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
        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado exitosamente.');
    }



    public function verTramiteDetalles($tramiteId)
    {
        $tramite = Tramite::with('proveedor.usuario')->findOrFail($tramiteId);
        $datosServicio = $this->dataRetrievalService->obtenerDatosTramiteHistorico($tramiteId);
        $datosServicio['datos_generales']['tipo_persona'] = $tramite->proveedor->tipo_persona;
        $viewModel = new FormDataViewModel($datosServicio);
        $datosCompletos = $viewModel->getAllFormData();

        return view('proveedores.tramite-detalles', compact('tramite', 'datosCompletos'));
    }

    public function export(Request $request)
    {
        // Obtener todos los filtros de la request, incluyendo los nuevos parámetros
        $filtros = array_filter($request->only([
            'search', 
            'estado', 
            'tipo_persona', 
            'vencimiento', 
            'año', 
            'sector', 
            'actividad_economica', 
            'estado_geografico',
            'con_historial',
            'tipo_tramite_año',
            'año_especifico',
            'proximidad_vencimiento',
            'dias_personalizados',
            'solo_activos',
            'año_trimestre',
            'trimestre'
        ]), fn($v) => is_array($v) ? !empty($v) : $v !== null && $v !== '');

        // Obtener columnas seleccionadas, por defecto todas las básicas
        $columnasSeleccionadas = $request->get('columns', [
            'id', 'rfc', 'razon_social', 'tipo_persona', 'estado_padron', 'telefono', 'domicilio', 'dias_restantes'
        ]);

        // Agregar configuración de exportación a los filtros
        $filtros['columns'] = $columnasSeleccionadas;
        
        // Generar nombre de archivo más descriptivo
        $sufijo = '';
        if ($request->get('solo_activos')) {
            $sufijo .= '_activos';
        }
        if ($request->get('año_trimestre') && $request->get('trimestre')) {
            $sufijo .= '_' . $request->get('año_trimestre') . 'Q' . $request->get('trimestre');
        }
        
        $nombreArchivo = 'proveedores' . $sufijo . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ProveedoresExcelCompleto($filtros), $nombreArchivo);
    }

}
