<?php

namespace App\Services\Revisiones;

use App\Services\HistorialTramitesService;
use App\Services\Tramites\DataRetrievalService;

class RevisionDomiciliariaService extends RevisionService
{
    /**
     * Obtiene datos específicos para revisión domiciliaria
     */
    public function obtenerDatosRevisionDomiciliaria(int $tramiteId): array
    {
        $datos = $this->obtenerDatosRevision($tramiteId);
        
        // Agregar datos específicos para revisión domiciliaria
        $datos['tipoRevision'] = 'Domiciliaria';
        $datos['vistaRevision'] = 'revisiones.revision-domiciliaria';
        
        // Para revisión domiciliaria no se requiere historial ni archivos completos
        unset($datos['historialTramites']);
        unset($datos['estadisticasHistorial']);
        
        return $datos;
    }

    /**
     * Configuraciones específicas para revisión domiciliaria
     */
    public function getConfiguracion(): array
    {
        return [
            'permite_cotejo' => true,
            'permite_comparacion_documentos' => true,
            'requiere_firma_digital' => false,
            'requiere_visita_domicilio' => true,
            'tiempo_maximo_minutos' => 240,
            'requiere_evidencia_fotografica' => true
        ];
    }
} 