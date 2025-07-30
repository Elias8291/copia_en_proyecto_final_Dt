<?php declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\Tramite;
use App\Models\Actividad;

class ActividadesEconomicasFormService
{
    /**
     * Procesa y guarda las actividades económicas del formulario
     */
    public function procesar(Tramite $tramite, array $datos): void
    {
        if (!empty($datos['actividades'])) {
            $this->guardarActividades($tramite, $datos['actividades']);
        }
    }

    /**
     * Guarda las actividades económicas
     */
    private function guardarActividades(Tramite $tramite, array $actividades): void
    {
        foreach ($actividades as $actividadId) {
            if (!empty($actividadId)) {
                // Aquí se guardaría la relación entre trámite y actividad
                // Dependiendo de tu estructura de base de datos
                $tramite->actividades()->attach($actividadId);
            }
        }
    }

    /**
     * Obtiene las reglas de validación para actividades económicas
     */
    public function getValidationRules(): array
    {
        return [
            'actividades' => 'required|array|min:1',
            'actividades.*' => 'required|integer|exists:actividades,id',
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para actividades económicas
     */
    public function getValidationMessages(): array
    {
        return [
            'actividades.required' => 'Debe seleccionar al menos una actividad económica.',
            'actividades.min' => 'Debe seleccionar al menos una actividad económica.',
            'actividades.*.required' => 'La actividad económica es obligatoria.',
            'actividades.*.integer' => 'La actividad económica debe ser un número válido.',
            'actividades.*.exists' => 'La actividad económica seleccionada no es válida.',
        ];
    }

    /**
     * Obtiene los nombres de atributos para actividades económicas
     */
    public function getValidationAttributes(): array
    {
        return [
            'actividades' => 'actividades económicas',
            'actividades.*' => 'actividad económica',
        ];
    }

    /**
     * Valida los datos de actividades económicas (método legacy)
     */
    public function validar(array $datos): array
    {
        $errores = [];

        if (empty($datos['actividades'])) {
            $errores[] = 'Debe seleccionar al menos una actividad económica';
        }

        if (!empty($datos['actividades']) && !is_array($datos['actividades'])) {
            $errores[] = 'Las actividades económicas deben ser una lista válida';
        }

        return $errores;
    }

    /**
     * Obtener actividades económicas de un trámite
     */
    public function obtenerDatos(Tramite $tramite): ?array
    {
        $actividades = $tramite->actividades;
        if ($actividades->isEmpty()) {
            return null;
        }

        return $actividades->map(function ($actividad) {
            return [
                'id' => $actividad->id,
                'nombre' => $actividad->nombre,
                'codigo' => $actividad->codigo,
                'sector' => $actividad->sector?->nombre ?? 'N/A',
            ];
        })->toArray();
    }

    /**
     * Verificar si tiene datos completos
     */
    public function tienesDatosCompletos(Tramite $tramite): bool
    {
        return !$tramite->actividades->isEmpty();
    }
} 