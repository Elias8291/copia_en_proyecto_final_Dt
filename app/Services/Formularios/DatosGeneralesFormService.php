<?php

declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\Tramite;
use App\Models\DatosGenerales;
use App\Models\Contacto;

class DatosGeneralesFormService
{
    /**
     * Procesa y guarda los datos generales del formulario
     */
    public function procesar(Tramite $tramite, array $datos): void
    {
        if ($this->tieneDatosGenerales($datos)) {
            $this->guardarDatosGenerales($tramite, $datos);
        }

        if ($this->tieneContacto($datos)) {
            $this->guardarContacto($tramite, $datos);
        }
    }

    /**
     * Verifica si tiene datos generales
     */
    private function tieneDatosGenerales(array $datos): bool
    {
        return !empty($datos['razon_social']) || !empty($datos['rfc']);
    }

    /**
     * Verifica si tiene datos de contacto
     */
    private function tieneContacto(array $datos): bool
    {
        return !empty($datos['email_contacto']) || !empty($datos['telefono']);
    }

    /**
     * Guarda los datos generales
     */
    private function guardarDatosGenerales(Tramite $tramite, array $datos): void
    {
        DatosGenerales::create([
            'tramite_id' => $tramite->id,
            'razon_social' => $datos['razon_social'] ?? 'Sin especificar',
            'telefono' => $datos['telefono'] ?? null,
            'curp' => $datos['curp'] ?? null,
            'pagina_web' => $datos['pagina_web'] ?? null,
        ]);
    }

    /**
     * Guarda el contacto
     */
    private function guardarContacto(Tramite $tramite, array $datos): void
    {
        Contacto::create([
            'tramite_id' => $tramite->id,
            'nombre_contacto' => $datos['nombre_contacto'] ?? $datos['razon_social'] ?? 'Sin especificar',
            'cargo' => $datos['cargo'] ?? 'Representante',
            'correo_electronico' => $datos['email_contacto'] ?? $datos['correo_electronico'] ?? 'sin-email@ejemplo.com',
            'telefono' => $datos['telefono'] ?? '000-000-0000',
        ]);
    }

    /**
     * Valida los datos generales
     */
    public function validar(array $datos): array
    {
        $errores = [];

        if (empty($datos['razon_social'])) {
            $errores[] = 'La razón social es requerida';
        }

        if (empty($datos['rfc'])) {
            $errores[] = 'El RFC es requerido';
        }

        if (!empty($datos['email_contacto']) && !filter_var($datos['email_contacto'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email de contacto no es válido';
        }

        return $errores;
    }

    /**
     * Obtener datos generales de un trámite
     */
    public function obtenerDatos(Tramite $tramite): ?array
    {
        $datosGenerales = $tramite->datosGenerales;
        $contacto = $tramite->contacto;

        if (!$datosGenerales && !$contacto) {
            return null;
        }

        return [
            'datos_generales' => $datosGenerales ? [
                'razon_social' => $datosGenerales->razon_social,
                'telefono' => $datosGenerales->telefono,
                'curp' => $datosGenerales->curp,
                'pagina_web' => $datosGenerales->pagina_web,
            ] : null,
            'contacto' => $contacto ? [
                'nombre_contacto' => $contacto->nombre_contacto,
                'cargo' => $contacto->cargo,
                'correo_electronico' => $contacto->correo_electronico,
                'telefono' => $contacto->telefono,
            ] : null,
        ];
    }

    /**
     * Verificar si tiene datos completos
     */
    public function tienesDatosCompletos(Tramite $tramite): bool
    {
        return $tramite->datosGenerales !== null && $tramite->contacto !== null;
    }
}