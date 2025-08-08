<?php

namespace App\Services\Revisiones;

use App\Services\RevisionService;
use App\Services\CitasService;
use App\Services\NotificacionService;
use App\Services\Tramites\DataRetrievalService;
use App\Models\Tramite;
use App\ViewModels\FormDataViewModel;

class RevisionPresencialService extends RevisionService
{
    private DataRetrievalService $dataRetrievalService;

    public function __construct(CitasService $citasService, NotificacionService $notificacionService = null, DataRetrievalService $dataRetrievalService = null)
    {
        parent::__construct($citasService, $notificacionService);
        $this->dataRetrievalService = $dataRetrievalService ?? app(DataRetrievalService::class);
    }

    /**
     * Obtiene datos específicos para revisión presencial
     */
    public function obtenerDatosRevisionPresencial(int $tramiteId): array
    {
        $datos = $this->obtenerDatosRevisionBase($tramiteId);
        
        // Agregar datos específicos para revisión presencial
        $datos['tipoRevision'] = 'Presencial';
        $datos['vistaRevision'] = 'revisiones.revision-presencial';
        $datos['archivosSubidos'] = $this->prepararArchivosParaCotejo($datos['archivos']);
        $datos['viewModel'] = $this->obtenerViewModel($tramiteId);
        
        return $datos;
    }

    /**
     * Crea el ViewModel con todos los datos del trámite usando DataRetrievalService
     */
    private function obtenerViewModel(int $tramiteId): FormDataViewModel
    {
        $tramite = Tramite::findOrFail($tramiteId);
        
        $datosCompletos = $this->dataRetrievalService->obtenerDatosTramite($tramite);
        $datosCompletos['datos_generales']['tipo_persona'] = $tramite->proveedor->tipo_persona;

        return new FormDataViewModel($datosCompletos);
    }

    /**
     * Configuraciones específicas para revisión presencial
     */
    public function getConfiguracion(): array
    {
        return [
            'permite_cotejo' => true,
            'permite_comparacion_documentos' => true,
            'requiere_firma_digital' => false,
            'requiere_presencia_fisica' => true,
            'tiempo_maximo_minutos' => 180
        ];
    }
} 