<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Services\CitaService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CitaController extends Controller
{
    protected $citaService;

    public function __construct(CitaService $citaService)
    {
        $this->citaService = $citaService;
    }

    public function index(Request $request)
    {
        $query = Cita::with(['user', 'atendidoPor', 'tramite']);
        
        // Filtro para mostrar solo citas de hoy
        if ($request->has('hoy') && $request->hoy == '1') {
            $query->whereDate('fecha_cita', today());
        }
        
        // Filtro por fecha específica
        if ($request->has('fecha') && $request->fecha) {
            $query->whereDate('fecha_cita', $request->fecha);
        }
        
        $citas = $query->orderBy('fecha_cita', 'asc')->paginate(10);
        
        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        return view('citas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'id_tramite' => 'nullable|exists:tramites,id',
            'fecha_cita' => 'required|date',
            'tipo_cita' => 'required|string',
            'motivo' => 'required|string'
        ]);

        Cita::create($request->all());

        return redirect()->route('citas.index')->with('success', 'Cita creada exitosamente.');
    }

    public function show(Cita $cita)
    {
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        return view('citas.edit', compact('cita'));
    }

    public function update(Request $request, Cita $cita)
    {
        $request->validate([
            'fecha_cita' => 'required|date',
            'tipo_cita' => 'required|string',
            'motivo' => 'required|string'
        ]);

        $cita->update($request->all());

        return redirect()->route('citas.index')->with('success', 'Cita actualizada exitosamente.');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();
        return redirect()->route('citas.index')->with('success', 'Cita eliminada exitosamente.');
    }

    /**
     * Obtener horarios disponibles para una fecha
     */
    public function obtenerHorariosDisponibles(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date'
        ]);

        $fecha = \Carbon\Carbon::parse($request->fecha);
        $horarios = $this->citaService->obtenerHorariosDisponibles($fecha);

        return response()->json([
            'success' => true,
            'horarios' => $horarios->map(function($horario) {
                return $horario->format('H:i');
            })
        ]);
    }

    /**
     * Obtener próximos días hábiles
     */
    public function obtenerDiasHabiles(Request $request)
    {
        $cantidad = $request->get('cantidad', 10);
        $dias = $this->citaService->obtenerProximosDiasHabiles($cantidad);

        return response()->json([
            'success' => true,
            'dias' => $dias
        ]);
    }

    /**
     * Obtener estadísticas de citas
     */
    public function obtenerEstadisticas(Request $request)
    {
        $fecha = $request->get('fecha', now()->format('Y-m-d'));
        $fechaCarbon = Carbon::parse($fecha);
        
        $totalCitasPosibles = $this->citaService->obtenerTotalCitasPorDia();
        $citasExistentes = Cita::whereDate('fecha_cita', $fecha)
            ->where('estado', '!=', 'Cancelada')
            ->count();
        $citasDisponibles = $totalCitasPosibles - $citasExistentes;

        return response()->json([
            'success' => true,
            'fecha' => $fecha,
            'total_citas_posibles' => $totalCitasPosibles,
            'citas_existentes' => $citasExistentes,
            'citas_disponibles' => $citasDisponibles,
            'horarios_disponibles' => $this->citaService->obtenerHorariosDisponibles($fechaCarbon)
        ]);
    }
} 