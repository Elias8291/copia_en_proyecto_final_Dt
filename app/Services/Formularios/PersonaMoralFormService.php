<?php declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\Tramite;

class PersonaMoralFormService
{
    /**
     * Procesa los datos específicos de persona moral
     */
    public function procesar(Tramite $tramite, array $datos): void
    {
        // Datos constitutivos
        $datosConstitutivosService = app(DatosConstitutivosFormService::class);
        $datosConstitutivosService->procesar($tramite, $datos);

        // Apoderado legal
        $apoderadoLegalService = app(ApoderadoLegalFormService::class);
        $apoderadoLegalService->procesar($tramite, $datos);

        // Accionistas
        $accionistasService = app(AccionistasFormService::class);
        $accionistasService->procesar($tramite, $datos);
    }



    /**
     * Obtiene las reglas de validación para persona moral
     */
    public function getValidationRules(): array
    {
        $datosConstitutivosService = app(DatosConstitutivosFormService::class);
        $apoderadoLegalService = app(ApoderadoLegalFormService::class);
        $accionistasService = app(AccionistasFormService::class);

        return array_merge(
            $datosConstitutivosService->getValidationRules(),
            $apoderadoLegalService->getValidationRules(),
            $accionistasService->getValidationRules()
        );
    }

    /**
     * Obtiene los mensajes de error personalizados para persona moral
     */
    public function getValidationMessages(): array
    {
        $datosConstitutivosService = app(DatosConstitutivosFormService::class);
        $apoderadoLegalService = app(ApoderadoLegalFormService::class);
        $accionistasService = app(AccionistasFormService::class);

        return array_merge(
            $datosConstitutivosService->getValidationMessages(),
            $apoderadoLegalService->getValidationMessages(),
            $accionistasService->getValidationMessages()
        );
    }

    /**
     * Obtiene los nombres de atributos para persona moral
     */
    public function getValidationAttributes(): array
    {
        $datosConstitutivosService = app(DatosConstitutivosFormService::class);
        $apoderadoLegalService = app(ApoderadoLegalFormService::class);
        $accionistasService = app(AccionistasFormService::class);

        return array_merge(
            $datosConstitutivosService->getValidationAttributes(),
            $apoderadoLegalService->getValidationAttributes(),
            $accionistasService->getValidationAttributes()
        );
    }

    /**
     * Valida los datos de persona moral (método legacy)
     */
    public function validar(array $datos): array
    {
        $datosConstitutivosService = app(DatosConstitutivosFormService::class);
        $apoderadoLegalService = app(ApoderadoLegalFormService::class);
        $accionistasService = app(AccionistasFormService::class);

        $errores = [];
        $errores = array_merge($errores, $datosConstitutivosService->validar($datos));
        $errores = array_merge($errores, $apoderadoLegalService->validar($datos));
        $errores = array_merge($errores, $accionistasService->validar($datos));

        return $errores;
    }

    /**
     * Obtener datos de persona moral asociados a un trámite
     */
    public function obtenerDatos(Tramite $tramite): ?array
    {
        $datosConstitutivosService = app(DatosConstitutivosFormService::class);
        $apoderadoLegalService = app(ApoderadoLegalFormService::class);
        $accionistasService = app(AccionistasFormService::class);

        return [
            'datos_constitutivos' => $datosConstitutivosService->obtenerDatos($tramite),
            'apoderado_legal' => $apoderadoLegalService->obtenerDatos($tramite),
            'accionistas' => $accionistasService->obtenerDatos($tramite),
        ];
    }
}
