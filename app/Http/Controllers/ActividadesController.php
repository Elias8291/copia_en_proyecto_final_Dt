<?php

namespace App\Http\Controllers;

use App\Models\ActividadEconomica;
use Illuminate\Http\Request;

class ActividadesController extends Controller
{
    public function buscador(Request $request)
    {
        if ($request->filled('nombre')) {
            $resultados = ActividadEconomica::where('nombre', 'like', '%'.$request->nombre.'%')
                ->where('estado_validacion', 'Validada')
                ->take(10)
                ->get(['id', 'nombre']);

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
