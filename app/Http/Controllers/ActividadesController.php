<?php

namespace App\Http\Controllers;

use App\Models\ActividadEconomica;
use App\Services\ActividadesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActividadesController extends Controller
{
    protected $actividadesService;

    public function __construct(ActividadesService $actividadesService)
    {
        $this->actividadesService = $actividadesService;
    }

    public function buscador(Request $request)
    {
        if ($request->filled('nombre')) {
            $resultados = $this->actividadesService->buscarActividades($request->nombre);
            return response()->json($resultados);
        }

        return response()->json([]);
    }

    public function buscar(Request $request)
    {
        if ($request->filled('q')) {
            $resultados = ActividadEconomica::where('nombre', 'like', '%'.$request->q.'%')
                ->where('estado_validacion', 'Validada')
                ->take(10)
                ->get(['id', 'nombre', 'codigo', 'descripcion']);
            return response()->json($resultados);
        }

        return response()->json([]);
    }

    public function porIds(Request $request)
    {
        $ids = $request->get('ids', []);
        
        if (empty($ids)) {
            return response()->json([]);
        }

        $actividades = ActividadEconomica::whereIn('id', $ids)
            ->with('sector')
            ->get();

        return response()->json($actividades);
    }

    /**
     * Validar una actividad económica
     */
    public function validar(Request $request): JsonResponse
    {
        $request->validate([
            'actividad_id' => 'required|integer|exists:actividades_economicas,id'
        ]);

        try {
            $actividad = ActividadEconomica::findOrFail($request->actividad_id);
            $actividad->update(['estado_validacion' => 'Validada']);

            return response()->json([
                'success' => true,
                'message' => 'Actividad validada exitosamente',
                'actividad' => $actividad->load('sector')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al validar la actividad: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar una actividad económica
     */
    public function rechazar(Request $request): JsonResponse
    {
        $request->validate([
            'actividad_id' => 'required|integer|exists:actividades_economicas,id'
        ]);

        try {
            $actividad = ActividadEconomica::findOrFail($request->actividad_id);
            $actividad->update(['estado_validacion' => 'Rechazada']);

            return response()->json([
                'success' => true,
                'message' => 'Actividad rechazada exitosamente',
                'actividad' => $actividad->load('sector')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar la actividad: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información de una actividad específica
     */
    public function obtener(Request $request): JsonResponse
    {
        $request->validate([
            'actividad_id' => 'required|integer|exists:actividades_economicas,id'
        ]);

        try {
            $actividad = ActividadEconomica::with('sector')->findOrFail($request->actividad_id);
            
            return response()->json([
                'success' => true,
                'actividad' => $actividad
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la actividad: ' . $e->getMessage()
            ], 500);
        }
    }
}
