<?php

namespace App\Services\Revisiones;

use App\Models\Tramite;
use App\Models\RevisionTramite;
use App\Models\Archivo;
use App\Services\HistorialTramitesService;
use App\Services\Tramites\DataRetrievalService;
use App\ViewModels\FormDataViewModel;
use Illuminate\Support\Collection;

class RevisionService
{
    protected $historialService;
    protected $dataRetrievalService;

    public function __construct(
        HistorialTramitesService $historialService,
        DataRetrievalService $dataRetrievalService
    ) {
        $this->historialService = $historialService;
        $this->dataRetrievalService = $dataRetrievalService;
    }

    /**
     * Obtiene los datos necesarios para la revisión
     */
    public function obtenerDatosRevision(int $tramiteId): array
    {
        $tramite = Tramite::with('proveedor:id,rfc,tipo_persona')->findOrFail($tramiteId);
        
        // Obtener revisión existente
        $revision = RevisionTramite::where('tramite_id', $tramiteId)
            ->orderBy('created_at', 'desc')
            ->first();

        // Obtener archivos del trámite
        $archivosSubidos = $this->obtenerArchivosSubidos($tramiteId);

        // Obtener datos del trámite para el ViewModel
        $datosTramite = $this->dataRetrievalService->obtenerDatosTramiteHistorico($tramiteId);
        
        // Crear ViewModel con los datos correctos
        $viewModel = new FormDataViewModel($datosTramite);

        // Obtener historial
        $historialTramites = $this->historialService->obtenerHistorialPorTramite($tramiteId);
        $estadisticasHistorial = $this->historialService->obtenerEstadisticasHistorial($tramite->proveedor->rfc);

        return [
            'tramite' => $tramite,
            'revision' => $revision,
            'archivosSubidos' => $archivosSubidos,
            'viewModel' => $viewModel,
            'historialTramites' => $historialTramites,
            'estadisticasHistorial' => $estadisticasHistorial
        ];
    }

    /**
     * Obtiene los archivos subidos para un trámite
     */
    public function obtenerArchivosSubidos(int $tramiteId): Collection
    {
        return Archivo::where('tramite_id', $tramiteId)
            ->with('catalogoArchivo:id,nombre')
            ->get()
            ->map(function ($archivo) {
                return [
                    'id' => $archivo->id,
                    'nombre_catalogo' => $archivo->catalogoArchivo->nombre ?? 'Sin nombre',
                    'nombre_original' => $archivo->nombre_original,
                    'extension' => $archivo->extension ?? pathinfo($archivo->nombre_original, PATHINFO_EXTENSION),
                    'tamaño' => $archivo->tamaño ?? 0,
                    'ruta' => $archivo->ruta ?? ''
                ];
            });
    }

    /**
     * Finaliza una revisión
     */
    public function finalizarRevision(int $tramiteId, string $tipoRevision, string $decision, ?string $observaciones = null): RevisionTramite
    {
        $tramite = Tramite::findOrFail($tramiteId);

        // Verificar si ya existe una revisión para este trámite
        $revision = RevisionTramite::where('tramite_id', $tramiteId)->first();

        if (!$revision) {
            // Crear nueva revisión si no existe
            $revision = RevisionTramite::create([
                'tramite_id' => $tramiteId,
                'tipo_revision' => $tipoRevision,
                'estado' => 'Finalizada',
                'decision' => $decision,
                'observaciones' => $observaciones,
                'fecha_inicio' => now(),
                'fecha_finalizacion' => now(),
            ]);
        } else {
            // Actualizar revisión existente
            $revision->update([
                'decision' => $decision,
                'observaciones' => $observaciones,
                'estado' => 'Finalizada',
                'fecha_finalizacion' => now(),
            ]);
        }

        // Actualizar estado del trámite
        $tramite->update([
            'status' => $decision === 'aprobado' ? 'Aprobado' : 'Rechazado'
        ]);

        return $revision;
    }

    /**
     * Obtiene datos para vista read-only (historial)
     */
    public function obtenerDatosVistaSoloLectura(int $tramiteId): array
    {
        $tramite = Tramite::with('proveedor:id,rfc,tipo_persona')->findOrFail($tramiteId);
        
        // Obtener datos del trámite para el ViewModel
        $datosTramite = $this->dataRetrievalService->obtenerDatosTramiteHistorico($tramiteId);
        
        // Crear ViewModel con los datos correctos
        $viewModel = new FormDataViewModel($datosTramite);

        return [
            'tramite' => $tramite,
            'viewModel' => $viewModel
        ];
    }
} 