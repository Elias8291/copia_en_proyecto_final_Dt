<?php

namespace App\Services\Revisiones;

use App\Services\HistorialTramitesService;
use App\Services\Tramites\DataRetrievalService;

class RevisionDigitalService extends RevisionService
{
    /**
     * Obtiene datos específicos para revisión digital
     */
    public function obtenerDatosRevisionDigital(int $tramiteId): array
    {
        $datos = $this->obtenerDatosRevision($tramiteId);
        
        // Agregar datos específicos para revisión digital
        $datos['tipoRevision'] = 'Digital';
        $datos['vistaRevision'] = 'revisiones.revision-digital';
        
        return $datos;
    }

    /**
     * Configuraciones específicas para revisión digital
     */
    public function getConfiguracion(): array
    {
        return [
            'permite_cotejo' => true,
            'permite_comparacion_documentos' => true,
            'requiere_firma_digital' => false,
            'tiempo_maximo_minutos' => 120
        ];
    }
} 