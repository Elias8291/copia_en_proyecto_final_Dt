<?php

namespace App\Services\Revisiones;

use App\Services\RevisionService;
use App\Services\CitasService;
use App\Services\NotificacionService;

class RevisionPresencialService extends RevisionService
{
    public function __construct(CitasService $citasService, NotificacionService $notificacionService)
    {
        parent::__construct($citasService, $notificacionService);
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