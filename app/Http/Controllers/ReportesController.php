<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Estado;
use App\Models\Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ReporteFiltradoExport;
use App\Exports\ReporteTrimestralExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vista principal de reportes con filtros
     */
    public function index(Request $request)
    {
        // Obtener datos para los filtros
        $estados = Estado::orderBy('nombre')->get();
        $actividades = Actividad::orderBy('nombre')->limit(50)->get(); // Primeras 50 actividades más comunes
        
        // Construir query base
        $query = Proveedor::with([
            'tramites' => function($q) {
                $q->orderBy('created_at', 'desc');
            },
            'tramites.direcciones.estado',
            'tramites.direcciones.coordenada',
            'tramites.actividades.actividad',
            'tramites.contactos',
            'tramites.datosGenerales'
        ]);

        // Aplicar filtros
        if ($request->filled('estado_padron')) {
            $query->where('estado_padron', $request->estado_padron);
        }

        if ($request->filled('tipo_persona')) {
            $query->where('tipo_persona', $request->tipo_persona);
        }

        if ($request->filled('estado_geografico')) {
            $query->whereHas('tramites.direcciones.estado', function($q) use ($request) {
                $q->where('id', $request->estado_geografico);
            });
        }

        if ($request->filled('municipio')) {
            $query->whereHas('tramites.direcciones', function($q) use ($request) {
                $q->where('municipio', 'like', '%' . $request->municipio . '%');
            });
        }

        if ($request->filled('actividad_economica')) {
            $query->whereHas('tramites.actividades.actividad', function($q) use ($request) {
                $q->where('id', $request->actividad_economica);
            });
        }

        if ($request->filled('fecha_alta_desde')) {
            $query->where('fecha_alta_padron', '>=', $request->fecha_alta_desde);
        }

        if ($request->filled('fecha_alta_hasta')) {
            $query->where('fecha_alta_padron', '<=', $request->fecha_alta_hasta);
        }

        if ($request->filled('fecha_vencimiento_desde')) {
            $query->where('fecha_vencimiento_padron', '>=', $request->fecha_vencimiento_desde);
        }

        if ($request->filled('fecha_vencimiento_hasta')) {
            $query->where('fecha_vencimiento_padron', '<=', $request->fecha_vencimiento_hasta);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('razon_social', 'like', '%' . $buscar . '%')
                  ->orWhere('rfc', 'like', '%' . $buscar . '%')
                  ->orWhere('pv_numero', 'like', '%' . $buscar . '%');
            });
        }

        // Filtro especial por días para vencer
        if ($request->filled('dias_vencer')) {
            $dias = (int) $request->dias_vencer;
            $hoy = Carbon::now();
            $fechaLimite = $hoy->copy()->addDays($dias);
            
            $query->where('estado_padron', 'Activo')
                  ->whereNotNull('fecha_vencimiento_padron')
                  ->where('fecha_vencimiento_padron', '>=', $hoy)
                  ->where('fecha_vencimiento_padron', '<=', $fechaLimite);
        }

        // Obtener resultados paginados
        $proveedores = $query->orderBy('updated_at', 'desc')
                           ->paginate(20)
                           ->appends($request->all());

        // Contar totales por estado
        $estadisticas = [
            'total' => Proveedor::count(),
            'activos' => Proveedor::where('estado_padron', 'Activo')->count(),
            'vencidos' => Proveedor::where('estado_padron', 'Vencido')->count(),
            'pendientes' => Proveedor::where('estado_padron', 'Pendiente')->count(),
            'inactivos' => Proveedor::where('estado_padron', 'Inactivo')->count(),
            'filtrados' => $proveedores->total()
        ];

        return view('reportes.index', compact('proveedores', 'estados', 'actividades', 'estadisticas'));
    }

    /**
     * Exportar datos filtrados
     */
    public function exportarFiltrado(Request $request)
    {
        // Validar tipo de reporte
        $tipoReporte = $request->get('tipo_reporte', 'completo');
        
        // Construir nombre de archivo descriptivo
        $filtros = [];
        if ($request->filled('estado_padron')) $filtros[] = $request->estado_padron;
        if ($request->filled('tipo_persona')) $filtros[] = $request->tipo_persona;
        if ($request->filled('municipio')) $filtros[] = 'municipio_' . str_replace(' ', '_', $request->municipio);
        if ($request->filled('dias_vencer')) $filtros[] = 'vence_' . $request->dias_vencer . '_dias';
        
        $nombreFiltros = !empty($filtros) ? '_' . implode('_', $filtros) : '';
        $filename = 'reporte_proveedores_' . $tipoReporte . $nombreFiltros . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ReporteFiltradoExport($request->all(), $tipoReporte), $filename);
    }

    /**
     * API para obtener municipios por estado
     */
    public function getMunicipiosPorEstado(Request $request)
    {
        $estadoId = $request->get('estado_id');
        
        $municipios = DB::table('direcciones')
            ->join('tramites', 'direcciones.tramite_id', '=', 'tramites.id')
            ->join('proveedores', 'tramites.proveedor_id', '=', 'proveedores.id')
            ->where('direcciones.estado_id', $estadoId)
            ->whereNotNull('direcciones.municipio')
            ->where('direcciones.municipio', '!=', '')
            ->select('direcciones.municipio')
            ->groupBy('direcciones.municipio')
            ->orderBy('direcciones.municipio')
            ->get()
            ->pluck('municipio');

        return response()->json($municipios);
    }

    /**
     * Vista de análisis estadístico
     */
    public function analisisEstadistico()
    {
        // Distribución por estado del padrón
        $distribucionEstados = Proveedor::select('estado_padron', DB::raw('count(*) as total'))
            ->groupBy('estado_padron')
            ->get();

        // Distribución por tipo de persona
        $distribucionTipos = Proveedor::select('tipo_persona', DB::raw('count(*) as total'))
            ->groupBy('tipo_persona')
            ->get();

        // Proveedores por estado geográfico
        $proveedoresPorEstado = DB::table('proveedores')
            ->join('tramites', 'proveedores.id', '=', 'tramites.proveedor_id')
            ->join('direcciones', 'tramites.id', '=', 'direcciones.tramite_id')
            ->join('estados', 'direcciones.estado_id', '=', 'estados.id')
            ->select('estados.nombre', DB::raw('count(DISTINCT proveedores.id) as total'))
            ->groupBy('estados.id', 'estados.nombre')
            ->orderBy('total', 'desc')
            ->get();

        // Actividades más comunes
        $actividadesComunes = DB::table('actividades_proveedores')
            ->join('actividades', 'actividades_proveedores.actividad_id', '=', 'actividades.id')
            ->join('tramites', 'actividades_proveedores.tramite_id', '=', 'tramites.id')
            ->join('proveedores', 'tramites.proveedor_id', '=', 'proveedores.id')
            ->select('actividades.nombre', DB::raw('count(DISTINCT proveedores.id) as total'))
            ->groupBy('actividades.id', 'actividades.nombre')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Tendencias por mes (últimos 12 meses)
        $tendenciasMensuales = Proveedor::select(
                DB::raw('YEAR(fecha_alta_padron) as año'),
                DB::raw('MONTH(fecha_alta_padron) as mes'),
                DB::raw('count(*) as total')
            )
            ->where('fecha_alta_padron', '>=', Carbon::now()->subMonths(12))
            ->groupBy('año', 'mes')
            ->orderBy('año', 'desc')
            ->orderBy('mes', 'desc')
            ->get();

        // Proveedores próximos a vencer (próximos 30 días)
        $hoy = Carbon::now();
        $proximosVencer = Proveedor::where('estado_padron', 'Activo')
            ->whereNotNull('fecha_vencimiento_padron')
            ->where('fecha_vencimiento_padron', '>=', $hoy)
            ->where('fecha_vencimiento_padron', '<=', $hoy->copy()->addDays(30))
            ->count();

        return view('reportes.analisis', compact(
            'distribucionEstados',
            'distribucionTipos', 
            'proveedoresPorEstado',
            'actividadesComunes',
            'tendenciasMensuales',
            'proximosVencer'
        ));
    }

    /**
     * Vista para seleccionar reportes trimestrales
     */
    public function reportesTrimestrales()
    {
        // Obtener años disponibles basados en fechas de alta
        $añosDisponibles = Proveedor::selectRaw('YEAR(fecha_alta_padron) as año')
            ->whereNotNull('fecha_alta_padron')
            ->groupBy('año')
            ->orderBy('año', 'desc')
            ->pluck('año')
            ->filter()
            ->values();

        // Si no hay años, agregar el año actual
        if ($añosDisponibles->isEmpty()) {
            $añosDisponibles = collect([date('Y')]);
        }

        // Estadísticas por trimestre del año actual
        $añoActual = date('Y');
        $estadisticasTrimestrales = [];

        for ($trimestre = 1; $trimestre <= 4; $trimestre++) {
            $reporteTrimestral = new ReporteTrimestralExport($añoActual, $trimestre);
            $proveedores = $reporteTrimestral->collection();
            
            $estadisticasTrimestrales[$trimestre] = [
                'total' => $proveedores->count(),
                'nuevos' => $proveedores->filter(function($proveedor) use ($trimestre, $añoActual) {
                    if (!$proveedor->fecha_alta_padron) return false;
                    $fechaAlta = Carbon::parse($proveedor->fecha_alta_padron);
                    return $fechaAlta->year == $añoActual && $this->getTrimestre($fechaAlta->month) == $trimestre;
                })->count(),
                'vencidos' => $proveedores->filter(function($proveedor) use ($trimestre, $añoActual) {
                    if (!$proveedor->fecha_vencimiento_padron) return false;
                    $fechaVencimiento = Carbon::parse($proveedor->fecha_vencimiento_padron);
                    return $fechaVencimiento->year == $añoActual && $this->getTrimestre($fechaVencimiento->month) == $trimestre;
                })->count()
            ];
        }

        return view('reportes.trimestrales', compact('añosDisponibles', 'estadisticasTrimestrales', 'añoActual'));
    }

    /**
     * Exportar reporte trimestral
     */
    public function exportarReporteTrimestral(Request $request)
    {
        $request->validate([
            'año' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'trimestre' => 'required|integer|min:1|max:4'
        ]);

        $año = (int) $request->año;
        $trimestre = (int) $request->trimestre;

        $trimestresNombres = [
            1 => 'Q1_Ene-Mar',
            2 => 'Q2_Abr-Jun',
            3 => 'Q3_Jul-Sep',
            4 => 'Q4_Oct-Dic'
        ];

        $filename = "reporte_trimestral_{$año}_{$trimestresNombres[$trimestre]}_" . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ReporteTrimestralExport($año, $trimestre), $filename);
    }

    /**
     * Obtener número de trimestre según el mes
     */
    private function getTrimestre($mes)
    {
        if ($mes >= 1 && $mes <= 3) return 1;
        if ($mes >= 4 && $mes <= 6) return 2;
        if ($mes >= 7 && $mes <= 9) return 3;
        return 4;
    }

    /**
     * Vista comparativa de trimestres
     */
    public function comparativoTrimestral(Request $request)
    {
        $añoSeleccionado = $request->get('año', date('Y'));
        
        // Datos para comparación trimestral
        $comparativo = [];
        
        for ($trimestre = 1; $trimestre <= 4; $trimestre++) {
            $reporteTrimestral = new ReporteTrimestralExport($añoSeleccionado, $trimestre);
            $proveedores = $reporteTrimestral->collection();
            
            $comparativo[$trimestre] = [
                'nombre' => 'Q' . $trimestre,
                'total_activos' => $proveedores->count(),
                'nuevos_registros' => $proveedores->filter(function($proveedor) use ($trimestre, $añoSeleccionado) {
                    if (!$proveedor->fecha_alta_padron) return false;
                    $fechaAlta = Carbon::parse($proveedor->fecha_alta_padron);
                    return $fechaAlta->year == $añoSeleccionado && $this->getTrimestre($fechaAlta->month) == $trimestre;
                })->count(),
                'vencimientos' => $proveedores->filter(function($proveedor) use ($trimestre, $añoSeleccionado) {
                    if (!$proveedor->fecha_vencimiento_padron) return false;
                    $fechaVencimiento = Carbon::parse($proveedor->fecha_vencimiento_padron);
                    return $fechaVencimiento->year == $añoSeleccionado && $this->getTrimestre($fechaVencimiento->month) == $trimestre;
                })->count(),
                'personas_fisicas' => $proveedores->where('tipo_persona', 'Persona Física')->count(),
                'personas_morales' => $proveedores->where('tipo_persona', 'Persona Moral')->count(),
            ];
        }

        // Años disponibles para el selector
        $añosDisponibles = Proveedor::selectRaw('YEAR(fecha_alta_padron) as año')
            ->whereNotNull('fecha_alta_padron')
            ->groupBy('año')
            ->orderBy('año', 'desc')
            ->pluck('año')
            ->filter();

        return view('reportes.comparativo-trimestral', compact('comparativo', 'añoSeleccionado', 'añosDisponibles'));
    }
}
