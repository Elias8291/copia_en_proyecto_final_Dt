<?php

namespace App\Services\Revisiones;

use App\Services\RevisionService;
use App\Services\Tramites\DataRetrievalService;
use App\Models\Tramite;
use App\ViewModels\FormDataViewModel;

class RevisionDigitalService extends RevisionService
{
    private DataRetrievalService $dataRetrievalService;

    public function __construct(DataRetrievalService $dataRetrievalService = null)
    {
        $this->dataRetrievalService = $dataRetrievalService ?? app(DataRetrievalService::class);
    }

    // Obtiene todos los datos necesarios para la revisión digital
    public function obtenerDatosRevisionDigital(int $tramiteId): array
    {
        $datos = $this->obtenerDatosRevisionBase($tramiteId);
        
        $datos['tipoRevision'] = 'Digital';
        $datos['vistaRevision'] = 'revisiones.revision-digital';
        $datos['archivosSubidos'] = $this->prepararArchivosParaCotejo($datos['archivos']);
        $datos['historialTramites'] = $this->obtenerHistorialTramites($tramiteId);
        $datos['viewModel'] = $this->obtenerViewModel($tramiteId);
        $datos['estadisticasHistorial'] = $this->obtenerEstadisticasHistorial($tramiteId);
        
        return $datos;
    }

    // Obtiene el historial de trámites del mismo proveedor
    private function obtenerHistorialTramites(int $tramiteId): \Illuminate\Support\Collection
    {
        $tramite = Tramite::findOrFail($tramiteId);
        
        return Tramite::where('proveedor_id', $tramite->proveedor->id)
            ->with(['proveedor', 'revisiones.revisor'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($tramite) {
                return [
                    'id' => $tramite->id,
                    'status' => $tramite->status,
                    'created_at' => $tramite->created_at,
                    'razon_social' => $tramite->proveedor->razon_social ?? $tramite->proveedor->nombre,
                ];
            });
    }

    // Crea el ViewModel con todos los datos del trámite usando DataRetrievalService
    private function obtenerViewModel(int $tramiteId): FormDataViewModel
    {
        $tramite = Tramite::findOrFail($tramiteId);
        
        $datosCompletos = $this->dataRetrievalService->obtenerDatosTramite($tramite);
        $datosCompletos['datos_generales']['tipo_persona'] = $tramite->proveedor->tipo_persona;

        return new FormDataViewModel($datosCompletos);
    }

    // Calcula estadísticas del historial de trámites del proveedor
    private function obtenerEstadisticasHistorial(int $tramiteId): array
    {
        $tramite = Tramite::findOrFail($tramiteId);
        $tramitesProveedor = Tramite::where('proveedor_id', $tramite->proveedor->id)->get();
        
        return [
            'total' => $tramitesProveedor->count(),
            'aprobados' => $tramitesProveedor->where('status', 'Aprobado')->count(),
            'rechazados' => $tramitesProveedor->where('status', 'Rechazado')->count(),
            'pendientes' => $tramitesProveedor->whereNotIn('status', ['Aprobado', 'Rechazado'])->count(),
        ];
    }
} 