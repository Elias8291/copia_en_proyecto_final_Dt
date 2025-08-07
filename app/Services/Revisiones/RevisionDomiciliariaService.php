<?php

namespace App\Services\Revisiones;

use App\Services\RevisionService;
use App\Services\CitasService;
use App\Services\NotificacionService;

class RevisionDomiciliariaService extends RevisionService
{
    public function __construct(CitasService $citasService, NotificacionService $notificacionService = null)
    {
        parent::__construct($citasService, $notificacionService);
    }

    /**
     * Obtiene datos específicos para revisión domiciliaria
     */
    public function obtenerDatosRevisionDomiciliaria(int $tramiteId): array
    {
        $datos = $this->obtenerDatosRevisionBase($tramiteId);
        
        // Agregar datos específicos para revisión domiciliaria
        $datos['tipoRevision'] = 'Domiciliaria';
        $datos['vistaRevision'] = 'revisiones.revision-domiciliaria';
        
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