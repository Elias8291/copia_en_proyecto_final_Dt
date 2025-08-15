<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use App\Models\Actividad;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProveedorController extends Controller
{

    /**
     * Obtener todos los sectores económicos para los filtros
     */
    public function getSectores(): JsonResponse
    {
        try {
            $sectores = Sector::select('id', 'nombre')
                ->orderBy('nombre')
                ->get();

            return response()->json($sectores);
        } catch (\Exception $e) {
            Log::error('Error al obtener sectores: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar sectores'
            ], 500);
        }
    }

    /**
     * Obtener todas las actividades económicas para los filtros
     */
    public function getActividades(): JsonResponse
    {
        try {
            $actividades = Actividad::select('id', 'nombre')
                ->orderBy('nombre')
                ->get();

            return response()->json($actividades);
        } catch (\Exception $e) {
            Log::error('Error al obtener actividades: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar actividades'
            ], 500);
        }
    }
}
