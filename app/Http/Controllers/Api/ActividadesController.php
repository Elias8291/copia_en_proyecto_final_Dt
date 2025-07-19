<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActividadesController extends Controller
{
    /**
     * Busca actividades económicas por texto
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function buscar(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q');
            
            if (!$query || strlen($query) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'El término de búsqueda debe tener al menos 2 caracteres.',
                    'data' => []
                ], 400);
            }

            // Buscar actividades que coincidan con el texto
            $actividades = DB::table('catalogo_actividades as a')
                ->leftJoin('catalogo_sectores as s', 'a.sector_id', '=', 's.id')
                ->select([
                    'a.id',
                    'a.nombre',
                    'a.codigo_scian',
                    's.nombre as sector'
                ])
                ->where(function($q) use ($query) {
                    $q->where('a.nombre', 'like', "%{$query}%")
                      ->orWhere('a.codigo_scian', 'like', "%{$query}%");
                })
                ->where('a.estado', 'Aprobada')
                ->orderBy('a.nombre')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Actividades encontradas correctamente.',
                'data' => $actividades,
                'total' => $actividades->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de actividades', [
                'query' => $request->get('q'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor. Intente nuevamente.',
                'data' => []
            ], 500);
        }
    }

    /**
     * Obtiene actividades por IDs
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function porIds(Request $request): JsonResponse
    {
        try {
            $idsString = $request->get('ids');
            
            if (!$idsString) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se proporcionaron IDs de actividades.',
                    'data' => []
                ], 400);
            }

            // Convertir string de IDs a array
            $ids = explode(',', $idsString);
            $ids = array_map('intval', $ids);
            $ids = array_filter($ids, function($id) { return $id > 0; });

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se proporcionaron IDs válidos.',
                    'data' => []
                ], 400);
            }

            // Buscar actividades por IDs
            $actividades = DB::table('catalogo_actividades as a')
                ->leftJoin('catalogo_sectores as s', 'a.sector_id', '=', 's.id')
                ->select([
                    'a.id',
                    'a.nombre',
                    'a.codigo_scian',
                    's.nombre as sector'
                ])
                ->whereIn('a.id', $ids)
                ->orderBy('a.nombre')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Actividades encontradas correctamente.',
                'data' => $actividades,
                'total' => $actividades->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener actividades por IDs', [
                'ids' => $request->get('ids'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor. Intente nuevamente.',
                'data' => []
            ], 500);
        }
    }
}