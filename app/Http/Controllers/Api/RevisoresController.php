<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CitaRevisionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RevisoresController extends Controller
{
    protected CitaRevisionService $citaRevisionService;

    public function __construct(CitaRevisionService $citaRevisionService)
    {
        $this->citaRevisionService = $citaRevisionService;
    }

    /**
     * Obtener revisores disponibles por tipo de revisión
     */
    public function obtenerDisponibles(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'tipo_revision' => 'required|string|in:Digital,Presencial,Domiciliaria',
                'tramite_id' => 'required|integer|exists:tramites,id'
            ]);

            $tipoRevision = $request->input('tipo_revision');
            $tramiteId = $request->input('tramite_id');

            \Log::info('Obteniendo revisores disponibles', [
                'tipo_revision' => $tipoRevision,
                'tramite_id' => $tramiteId,
                'user_id' => auth()->id()
            ]);

            $revisores = $this->citaRevisionService->obtenerRevisoresDisponibles($tipoRevision);

            return response()->json([
                'success' => true,
                'revisores' => $revisores,
                'total' => $revisores->count(),
                'tipo_revision' => $tipoRevision
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error al obtener revisores disponibles', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor al obtener revisores'
            ], 500);
        }
    }
}
