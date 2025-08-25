<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Actividad;
use App\Models\Sector;
use App\Models\Tramite;
use App\Services\Tramites\DataRetrievalService;
use App\ViewModels\FormDataViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProveedoresExcelCompleto;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Carbon\Carbon;

/**
 * Controlador para gestión de proveedores del sistema
 */
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

        if ($request->filled('con_historial')) {
            switch ($request->con_historial) {
                case 'si':
                    $query->has('tramites', '>=', 2);
                    break;
                case 'no':
                    $query->has('tramites', '<=', 1);
                    break;
                case 'sin_tramites':
                    $query->doesntHave('tramites');
                    break;
                case 'renovadores':
                    $query->whereHas('tramites', function($q) {
                        $q->where('tipo_tramite', 'Renovacion');
                    }, '>=', 2)
                    ->whereHas('tramites', function($q) {
                        $q->where('tipo_tramite', 'Inscripcion');
                    });
                    break;
            }
        }



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

        if ($request->filled('año_trimestre') && $request->filled('trimestre')) {
            $año = (int) $request->año_trimestre;
            $trimestre = (int) $request->trimestre;
            
            $rangosTrimestrales = [
                1 => ['inicio' => 1, 'fin' => 3],
                2 => ['inicio' => 4, 'fin' => 6],
                3 => ['inicio' => 7, 'fin' => 9],
                4 => ['inicio' => 10, 'fin' => 12]
            ];
            
            if (isset($rangosTrimestrales[$trimestre])) {
                $mesInicio = $rangosTrimestrales[$trimestre]['inicio'];
                $mesFin = $rangosTrimestrales[$trimestre]['fin'];
                
                $inicioTrimestre = Carbon::create($año, $mesInicio, 1)->startOfMonth();
                $finTrimestre = Carbon::create($año, $mesFin, 1)->endOfMonth();
                
                $query->where(function($q) use ($inicioTrimestre, $finTrimestre) {
                    $q->where('fecha_vencimiento_padron', '>=', $inicioTrimestre)
                      ->where('fecha_alta_padron', '<=', $finTrimestre);
                });
            }
        }

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

        $añosDisponibles = \App\Models\Proveedor::whereNotNull('fecha_alta_padron')
            ->selectRaw('DISTINCT YEAR(fecha_alta_padron) as año')
            ->orderBy('año', 'desc')
            ->pluck('año')
            ->filter();

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
        $validated['usuario_id'] = Auth::id();
        Proveedor::create($validated);
        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function show(Request $request, Proveedor $proveedor)
    {
        $ordenHistorial = $request->get('orden_historial', 'reciente');
        
        $rfc = $proveedor->rfc;
        
        $historialTramitesQuery = Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })->with(['proveedor', 'datosGenerales', 'oficios']);
        
        if ($ordenHistorial === 'pasados') {
            $historialTramites = $historialTramitesQuery
                ->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) ASC')
                ->get();
        } else {
            $historialTramites = $historialTramitesQuery
                ->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) DESC')
                ->get();
        }
        
        $proveedor->load(['usuario']);
        
        $ultimoTramite = $historialTramites->first();
        $datosCompletos = null;

        if ($ultimoTramite) {
            $datosServicio = $this->dataRetrievalService->obtenerDatosTramiteHistorico($ultimoTramite->id);
            $datosServicio['datos_generales']['tipo_persona'] = $ultimoTramite->proveedor->tipo_persona;
            $viewModel = new FormDataViewModel($datosServicio);
            $datosCompletos = $viewModel->getAllFormData();
        }

        return view('proveedores.show', compact('proveedor', 'ultimoTramite', 'datosCompletos', 'ordenHistorial', 'historialTramites', 'rfc'));
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

        $columnasSeleccionadas = $request->get('columns', [
            'id', 'rfc', 'razon_social', 'tipo_persona', 'estado_padron', 'telefono', 'domicilio', 'dias_restantes'
        ]);

        $filtros['columns'] = $columnasSeleccionadas;
        
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

    public function publico(Proveedor $proveedor)
    {
        $proveedor->load(['usuario']);
        
        $ultimoTramite = $proveedor->tramites()
            ->with(['actividades.actividad', 'direcciones.estado'])
            ->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) DESC')
            ->first();
        
        $direcciones = collect();
        if ($ultimoTramite && $ultimoTramite->direcciones->count() > 0) {
            $direcciones = $ultimoTramite->direcciones;
        }

        return view('proveedores.publico', compact('proveedor', 'ultimoTramite', 'direcciones'));
    }

    public function publicoPorToken(string $token)
    {
        $proveedor = Proveedor::buscarPorToken($token);
        
        if (!$proveedor) {
            abort(404, 'Token no válido o proveedor no encontrado');
        }

        $proveedor->load(['usuario']);
        
        $ultimoTramite = $proveedor->tramites()
            ->with(['actividades.actividad', 'direcciones.estado'])
            ->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) DESC')
            ->first();
        
        $direcciones = collect();
        if ($ultimoTramite && $ultimoTramite->direcciones->count() > 0) {
            $direcciones = $ultimoTramite->direcciones;
        }

        return view('proveedores.publico', compact('proveedor', 'ultimoTramite', 'direcciones'));
    }

}
