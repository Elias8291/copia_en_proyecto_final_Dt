<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Tramite;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $proveedores = $this->getProveedoresFiltrados($request);
        return view('proveedores.index', compact('proveedores'));
    }

    private function getProveedoresFiltrados(Request $request)
    {
        $query = Proveedor::with(['tramites' => function($query) {
            $query->latest();
        }])->withCount('tramites');

        // Búsqueda por texto
        if ($request->filled('q')) {
            $searchTerm = $request->get('q');
            $query->where(function($q) use ($searchTerm) {
                $q->where('rfc', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('razon_social', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('pv_numero', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filtro por estado del padrón
        if ($request->filled('estado_padron')) {
            $query->where('estado_padron', $request->get('estado_padron'));
        }

        // Filtro por tipo de persona
        if ($request->filled('tipo_persona')) {
            $query->where('tipo_persona', $request->get('tipo_persona'));
        }

        // Filtro por número de trámites
        if ($request->filled('tramites_count')) {
            $tramitesFilter = $request->get('tramites_count');
            switch ($tramitesFilter) {
                case '0':
                    $query->having('tramites_count', '=', 0);
                    break;
                case '1-5':
                    $query->having('tramites_count', '>=', 1)->having('tramites_count', '<=', 5);
                    break;
                case '6-10':
                    $query->having('tramites_count', '>=', 6)->having('tramites_count', '<=', 10);
                    break;
                case '11-20':
                    $query->having('tramites_count', '>=', 11)->having('tramites_count', '<=', 20);
                    break;
                case '21+':
                    $query->having('tramites_count', '>=', 21);
                    break;
            }
        }

        // Filtro por número PV
        if ($request->filled('tiene_pv')) {
            if ($request->get('tiene_pv') == '1') {
                $query->whereNotNull('pv_numero');
            } else {
                $query->whereNull('pv_numero');
            }
        }

        // Filtro por fecha de vencimiento del padrón
        if ($request->filled('fecha_vencimiento')) {
            $vencimientoFilter = $request->get('fecha_vencimiento');
            $today = now();
            
            switch ($vencimientoFilter) {
                case 'vencidos':
                    $query->where('fecha_vencimiento_padron', '<', $today);
                    break;
                case 'por_vencer_30':
                    $query->where('fecha_vencimiento_padron', '>=', $today)
                          ->where('fecha_vencimiento_padron', '<=', $today->copy()->addDays(30));
                    break;
                case 'por_vencer_60':
                    $query->where('fecha_vencimiento_padron', '>=', $today)
                          ->where('fecha_vencimiento_padron', '<=', $today->copy()->addDays(60));
                    break;
                case 'por_vencer_90':
                    $query->where('fecha_vencimiento_padron', '>=', $today)
                          ->where('fecha_vencimiento_padron', '<=', $today->copy()->addDays(90));
                    break;
                case 'vigentes':
                    $query->where('fecha_vencimiento_padron', '>', $today->copy()->addDays(90));
                    break;
            }
        }

        // Filtro por fecha de registro
        if ($request->filled('fecha_registro')) {
            $registroFilter = $request->get('fecha_registro');
            $today = now();
            
            switch ($registroFilter) {
                case 'hoy':
                    $query->whereDate('created_at', $today);
                    break;
                case 'ultima_semana':
                    $query->where('created_at', '>=', $today->copy()->subWeek());
                    break;
                case 'ultimo_mes':
                    $query->where('created_at', '>=', $today->copy()->subMonth());
                    break;
                case 'ultimos_3_meses':
                    $query->where('created_at', '>=', $today->copy()->subMonths(3));
                    break;
                case 'ultimo_ano':
                    $query->where('created_at', '>=', $today->copy()->subYear());
                    break;
            }
        }

        // Filtro por actividad económica
        if ($request->filled('actividad_economica')) {
            \Log::info('Filtro actividad económica recibido:', [
                'valor' => $request->get('actividad_economica'),
                'todos_los_parametros' => $request->all()
            ]);
            
            $actividadesEconomicas = explode(',', $request->get('actividad_economica'));
            $actividadesEconomicas = array_map('trim', $actividadesEconomicas);
            $actividadesEconomicas = array_filter($actividadesEconomicas);
            
            \Log::info('Actividades procesadas:', [
                'actividades' => $actividadesEconomicas
            ]);
            
            if (!empty($actividadesEconomicas)) {
                $query->whereHas('actividades.actividad', function($q) use ($actividadesEconomicas) {
                    $q->whereIn('nombre', $actividadesEconomicas);
                });
                
                \Log::info('Filtro aplicado correctamente');
            }
        } else {
            \Log::info('No se recibió filtro de actividad económica');
        }

        // Ordenamiento
        $ordenarPor = $request->get('ordenar_por', 'created_at_desc');
        switch ($ordenarPor) {
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'razon_social_asc':
                $query->orderBy('razon_social', 'asc');
                break;
            case 'razon_social_desc':
                $query->orderBy('razon_social', 'desc');
                break;
            case 'rfc_asc':
                $query->orderBy('rfc', 'asc');
                break;
            case 'tramites_count_desc':
                $query->orderBy('tramites_count', 'desc');
                break;
            case 'tramites_count_asc':
                $query->orderBy('tramites_count', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Paginación
        $perPage = $request->get('per_page', 15);
        return $query->paginate($perPage);
    }

    public function show($id)
    {
        $proveedor = Proveedor::with([
            'tramites.datosGenerales',
            'tramites.actividades',
            'tramites.direcciones',
            'tramites.oficios'
        ])->findOrFail($id);

        $tramitesRecientes = $proveedor->tramites()->latest()->take(5)->get();
        $totalTramites = $proveedor->tramites()->count();
        $tramitesAprobados = $proveedor->tramites()->where('status', 'Aprobado')->count();

        return view('proveedores.show', compact('proveedor', 'tramitesRecientes', 'totalTramites', 'tramitesAprobados'));
    }

    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'razon_social' => 'required|string|max:255',
            'estado_padron' => 'required|in:Activo,Pendiente,Inactivo',
            'fecha_vencimiento_padron' => 'nullable|date',
        ]);

        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update($request->all());

        return redirect()->route('proveedores.show', $proveedor->id)
            ->with('success', 'Proveedor actualizado correctamente');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        
        // Verificar si tiene trámites asociados
        if ($proveedor->tramites()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el proveedor porque tiene trámites asociados');
        }

        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado correctamente');
    }

    public function estadisticas()
    {
        $estadisticas = [
            'total_proveedores' => Proveedor::count(),
            'proveedores_activos' => Proveedor::where('estado_padron', 'Activo')->count(),
            'proveedores_pendientes' => Proveedor::where('estado_padron', 'Pendiente')->count(),
            'proveedores_inactivos' => Proveedor::where('estado_padron', 'Inactivo')->count(),
            'proveedores_con_pv' => Proveedor::whereNotNull('pv_numero')->count(),
            'proveedores_sin_pv' => Proveedor::whereNull('pv_numero')->count(),
        ];

        // Gráfico de proveedores por mes (últimos 12 meses)
        $proveedoresPorMes = Proveedor::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return view('proveedores.estadisticas', compact('estadisticas', 'proveedoresPorMes'));
    }

    public function buscar(Request $request)
    {
        $proveedores = $this->getProveedoresFiltrados($request);
        $query = $request->get('q');
        return view('proveedores.index', compact('proveedores', 'query'));
    }

    public function exportar()
    {
        $proveedores = Proveedor::with(['tramites' => function($query) {
            $query->latest();
        }])
        ->withCount('tramites')
        ->get();

        // Aquí puedes implementar la exportación a Excel o CSV
        // Por ahora solo retornamos la vista con los datos
        return view('proveedores.exportar', compact('proveedores'));
    }

    // Método temporal para debug de filtros
    public function debugFiltros(Request $request)
    {
        $query = $this->getProveedoresFiltrados($request);
        
        // Obtener la consulta SQL generada
        $sql = $query->toSql();
        $bindings = $query->getBindings();
        
        return response()->json([
            'sql' => $sql,
            'bindings' => $bindings,
            'total' => $query->total(),
            'filters_applied' => $request->all()
        ]);
    }

    public function buscarActividades(Request $request)
    {
        $search = $request->get('q', '');
        
        $actividades = \App\Models\Actividad::where('nombre', 'LIKE', "%{$search}%")
            ->orderBy('nombre')
            ->limit(50)
            ->get(['id', 'nombre']);
        
        return response()->json($actividades);
    }
} 