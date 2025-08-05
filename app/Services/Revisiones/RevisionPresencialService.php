<?php

namespace App\Services\Revisiones;

use App\Services\HistorialTramitesService;
use App\Services\Tramites\DataRetrievalService;

class RevisionPresencialService extends RevisionService
{
    /**
     * Obtiene datos específicos para revisión presencial
     */
    public function obtenerDatosRevisionPresencial(int $tramiteId): array
    {
        $datos = $this->obtenerDatosRevision($tramiteId);
        
        // Agregar datos específicos para revisión presencial
        $datos['tipoRevision'] = 'Presencial';
        $datos['vistaRevision'] = 'revisiones.revision-presencial';
        
        // Para revisión presencial no se requiere historial
        unset($datos['historialTramites']);
        unset($datos['estadisticasHistorial']);
        
        return $datos;
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