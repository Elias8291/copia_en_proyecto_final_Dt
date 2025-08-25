<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CatalogoActividadController extends Controller
{
    /**
     * Buscar actividades económicas por nombre o descripción
     */
    public function buscar(Request $request)
    {
        try {
            $query = trim($request->input('q'));
            if (!$query || strlen($query) < 2) {
                return response()->json([]);
            }   
            $actividades = Actividad::where('nombre', 'like', "%{$query}%")
                ->orWhere('descripcion', 'like', "%{$query}%")
                ->orderBy('nombre')
                ->limit(10)
                ->get(['id', 'nombre']);

            return response()->json($actividades);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de actividades: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
} 