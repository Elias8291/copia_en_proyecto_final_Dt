<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Services\Tramites\DataRetrievalService;
use App\ViewModels\TramiteViewModel;
use Illuminate\Http\Request;

class TramiteHistoricoController extends Controller
{
    private DataRetrievalService $dataRetrievalService;

    public function __construct(DataRetrievalService $dataRetrievalService)
    {
        $this->dataRetrievalService = $dataRetrievalService;
    }

    /**
     * Mostrar datos de un trámite histórico
     */
    public function mostrarTramiteHistorico(int $tramiteId)
    {
        try {
            // Obtener los datos del trámite usando DataRetrievalService
            $datos = $this->dataRetrievalService->obtenerDatosTramiteHistorico($tramiteId);
            
            // Obtener el trámite con sus relaciones básicas
            $tramite = Tramite::with(['proveedor'])->findOrFail($tramiteId);
            
            // Crear el ViewModel con los datos obtenidos
            $viewModel = new TramiteViewModel($datos);
            
            \Log::info('TramiteHistoricoController: Datos obtenidos para trámite histórico', [
                'tramite_id' => $tramiteId,
                'tipo_persona' => $tramite->proveedor->tipo_persona ?? 'No especificado',
                'datos_obtenidos' => array_keys($datos)
            ]);
            
            return view('tramites.tramite-historico', compact('tramite', 'viewModel'));
            
        } catch (\Exception $e) {
            \Log::error('TramiteHistoricoController: Error al obtener datos del trámite histórico', [
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('tramites.index')
                ->with('error', 'Error al cargar los datos del trámite histórico: ' . $e->getMessage());
        }
    }
} 