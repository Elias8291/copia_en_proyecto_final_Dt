<?php declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\Accionista;
use App\Models\Tramite;

class AccionistasFormService
{
    /**
     * Procesa y guarda los datos de accionistas del formulario
     */
    public function procesar(Tramite $tramite, array $datos): void
    {
        if (!empty($datos['accionistas'])) {
            $this->guardarAccionistas($tramite, $datos['accionistas']);
        }
    }

    /**
     * Guarda los accionistas
     */
    private function guardarAccionistas(Tramite $tramite, array $accionistas): void
    {
        foreach ($accionistas as $index => $accionista) {
            if (!empty($accionista['nombre']) || !empty($accionista['rfc'])) {
                Accionista::create([
                    'tramite_id' => $tramite->id,
                    'nombre_completo' => $accionista['nombre'] ?? 'Accionista ' . ($index + 1),
                    'rfc' => $accionista['rfc'] ?? null,
                    'porcentaje_participacion' => (float) ($accionista['porcentaje'] ?? 0),
                    'activo' => true,
                ]);
            }
        }
    }

    /**
     * Obtiene las reglas de validación para accionistas
     */
    public function getValidationRules(): array
    {
        return [
            'accionistas' => 'required|array|min:1',
            'accionistas.*.nombre' => 'required|string|min:3|max:255',
            'accionistas.*.rfc' => 'required|string|regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-V1-9A-Z0-9]{3}$/',
            'accionistas.*.porcentaje' => 'required|numeric|min:0.01|max:100',
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para accionistas
     */
    public function getValidationMessages(): array
    {
        return [
            'accionistas.required' => 'Debe agregar al menos un accionista.',
            'accionistas.min' => 'Debe agregar al menos un accionista.',
            'accionistas.*.nombre.required' => 'El nombre del accionista es obligatorio.',
            'accionistas.*.nombre.min' => 'El nombre del accionista debe tener al menos 3 caracteres.',
            'accionistas.*.nombre.max' => 'El nombre del accionista no puede exceder 255 caracteres.',
            'accionistas.*.rfc.required' => 'El RFC del accionista es obligatorio.',
            'accionistas.*.rfc.regex' => 'El RFC del accionista debe tener un formato válido.',
            'accionistas.*.porcentaje.required' => 'El porcentaje del accionista es obligatorio.',
            'accionistas.*.porcentaje.numeric' => 'El porcentaje debe ser un número.',
            'accionistas.*.porcentaje.min' => 'El porcentaje debe ser mayor a 0.',
            'accionistas.*.porcentaje.max' => 'El porcentaje no puede exceder 100.',
        ];
    }

    /**
     * Obtiene los nombres de atributos para accionistas
     */
    public function getValidationAttributes(): array
    {
        return [
            'accionistas' => 'accionistas',
            'accionistas.*.nombre' => 'nombre del accionista',
            'accionistas.*.rfc' => 'RFC del accionista',
            'accionistas.*.porcentaje' => 'porcentaje del accionista',
        ];
    }

    /**
     * Valida los datos de accionistas (método legacy)
     */
    public function validar(array $datos): array
    {
        $errores = [];

        if (empty($datos['accionistas'])) {
            $errores[] = 'Debe agregar al menos un accionista';
            return $errores;
        }

        $errores = array_merge($errores, $this->validarAccionistas($datos['accionistas']));

        return $errores;
    }

    /**
     * Valida los datos de accionistas
     */
    private function validarAccionistas(array $accionistas): array
    {
        $errores = [];
        $totalPorcentaje = 0;

        foreach ($accionistas as $index => $accionista) {
            $posicion = $index + 1;

            if (empty($accionista['nombre'])) {
                $errores[] = "El nombre del accionista #{$posicion} es requerido";
            }

            if (!empty($accionista['rfc'])) {
                if (!preg_match('/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/', $accionista['rfc'])) {
                    $errores[] = "El RFC del accionista #{$posicion} no tiene formato válido";
                }
            }

            $porcentaje = (float) ($accionista['porcentaje'] ?? 0);
            if ($porcentaje <= 0 || $porcentaje > 100) {
                $errores[] = "El porcentaje del accionista #{$posicion} debe estar entre 1 y 100";
            }

            $totalPorcentaje += $porcentaje;
        }

        if ($totalPorcentaje > 100) {
            $errores[] = 'La suma total de participaciones no puede exceder 100%';
        }

        return $errores;
    }

    /**
     * Obtener datos de accionistas de un trámite
     */
    public function obtenerDatos(Tramite $tramite): ?array
    {
        $accionistas = $tramite->accionistas;
        if ($accionistas->isEmpty()) {
            return null;
        }

        return $accionistas->map(function ($accionista) {
            return [
                'nombre' => $accionista->nombre_completo,
                'rfc' => $accionista->rfc,
                'porcentaje' => $accionista->porcentaje_participacion,
            ];
        })->toArray();
    }

    /**
     * Verificar si tiene datos completos
     */
    public function tienesDatosCompletos(Tramite $tramite): bool
    {
        return !$tramite->accionistas->isEmpty();
    }
} 