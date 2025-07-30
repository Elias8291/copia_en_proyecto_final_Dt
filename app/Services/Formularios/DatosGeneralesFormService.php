<?php declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\Contacto;
use App\Models\DatosGenerales;
use App\Models\Tramite;

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
     * Obtiene las reglas de validación para datos generales
     */
    public function getValidationRules(): array
    {
        return [
            'razon_social' => 'required|string|min:3|max:255',
            'rfc' => 'required|string|min:10|max:13',
            'curp' => 'nullable|string|size:18',
            'pagina_web' => 'nullable|url|max:255',
            'telefono' => 'required|string|max:20',
            'email_contacto' => 'required|email|max:255',
            'cargo' => 'required|string|min:2|max:100',
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para datos generales
     */
    public function getValidationMessages(): array
    {
        return [
            'razon_social.required' => 'La razón social es obligatoria.',
            'razon_social.min' => 'La razón social debe tener al menos 3 caracteres.',
            'razon_social.max' => 'La razón social no puede exceder 255 caracteres.',
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.min' => 'El RFC debe tener al menos 10 caracteres.',
            'rfc.max' => 'El RFC no puede exceder 13 caracteres.',
            'curp.size' => 'El CURP debe tener exactamente 18 caracteres.',
            'pagina_web.url' => 'La página web debe ser una URL válida.',
            'pagina_web.max' => 'La página web no puede exceder 255 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.max' => 'El teléfono no puede exceder 20 caracteres.',
            'email_contacto.required' => 'El email de contacto es obligatorio.',
            'email_contacto.email' => 'El email de contacto debe ser una dirección válida.',
            'email_contacto.max' => 'El email no puede exceder 255 caracteres.',
            'cargo.required' => 'El cargo es obligatorio.',
            'cargo.min' => 'El cargo debe tener al menos 2 caracteres.',
            'cargo.max' => 'El cargo no puede exceder 100 caracteres.',
        ];
    }

    /**
     * Obtiene los nombres de atributos para datos generales
     */
    public function getValidationAttributes(): array
    {
        return [
            'razon_social' => 'razón social',
            'rfc' => 'RFC',
            'curp' => 'CURP',
            'pagina_web' => 'página web',
            'telefono' => 'teléfono',
            'email_contacto' => 'email de contacto',
            'cargo' => 'cargo',
        ];
    }

    /**
     * Valida los datos generales (método legacy)
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
        $contactos = $tramite->contactos;

        if (!$datosGenerales && $contactos->isEmpty()) {
            return null;
        }

        return [
            'razon_social' => $datosGenerales->razon_social ?? 'N/A',
            'rfc' => $datosGenerales->rfc ?? 'N/A',
            'tipo_persona' => $datosGenerales->tipo_persona ?? 'N/A',
            'email' => $contactos->first()?->correo_electronico ?? 'N/A',
            'telefono' => $datosGenerales->telefono ?? $contactos->first()?->telefono ?? 'N/A',
            'sitio_web' => $datosGenerales->pagina_web ?? 'N/A',
        ];
    }

    /**
     * Verificar si tiene datos completos
     */
    public function tienesDatosCompletos(Tramite $tramite): bool
    {
        return $tramite->datosGenerales !== null && !$tramite->contactos->isEmpty();
    }
}
