<?php

namespace App\Http\Controllers;

use App\Models\ActividadEconomica;
use App\Services\ActividadesService;
use Illuminate\Http\Request;

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
}
