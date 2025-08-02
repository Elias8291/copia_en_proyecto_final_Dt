<?php

declare(strict_types=1);

namespace App\Services\Tramites;

use App\Services\Core\BaseResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

/**
 * Servicio para manejo uniforme de respuestas HTTP específicas de trámites
 * Responsabilidad: Respuestas específicas de trámites usando servicio base
 */
class RespuestaHttpService extends BaseResponseService
{
    /**
     * Respuesta específica de aprobación para trámites (mantiene compatibilidad)
     */
    public function respuestaAprobacionTramite(array $resultado): JsonResponse|RedirectResponse
    {
        return parent::respuestaAprobacion($resultado, route('revision.index'));
    }

    /**
     * Respuesta específica para citas de trámites
     */
    public function respuestaCitaTramite(array $resultado): JsonResponse|RedirectResponse
    {
        return parent::respuestaCita($resultado);
    }

    /**
     * Respuesta específica para formularios de trámites
     */
    public function respuestaFormularioTramite(
        array $resultado,
        string $rutaExito = null
    ): JsonResponse|RedirectResponse {
        return parent::respuestaFormulario(
            $resultado, 
            $rutaExito ?? route('tramites.exito')
        );
    }
}