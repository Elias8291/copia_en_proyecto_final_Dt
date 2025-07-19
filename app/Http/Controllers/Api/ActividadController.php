<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatalogoActividad;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActividadController extends Controller
{
    public function buscar(Request $request): JsonResponse
    {
        $termino = $request->get('q', '');
        
        if (strlen($termino) < 3) {
            return response()->json([
                'actividades' => [],
                'mensaje' => 'Ingrese al menos 3 caracteres para buscar'
            ]);
        }

        $actividades = CatalogoActividad::aprobadas()
            ->buscarPorNombre($termino)
            ->with('sector')
            ->limit(20)
            ->get()
            ->map(function ($actividad) {
                return [
                    'id' => $actividad->id,
                    'nombre' => $actividad->nombre,
                    'codigo_scian' => $actividad->codigo_scian,
                    'sector' => $actividad->sector->nombre ?? 'Sin sector'
                ];
            });

        return response()->json([
            'actividades' => $actividades,
            'total' => $actividades->count()
        ]);
    }
} 