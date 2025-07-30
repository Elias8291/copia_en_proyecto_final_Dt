<?php

declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\Tramite;

class ActividadesFormService
{
    /**
     * Procesa y guarda las actividades económicas
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
        $actividadesValidas = $this->filtrarActividadesValidas($actividades);

        if (!empty($actividadesValidas)) {
            $tramite->actividades()->attach($actividadesValidas);
        }
    }

    /**
     * Filtra actividades válidas
     */
    private function filtrarActividadesValidas(array $actividades): array
    {
        return array_map('intval', array_filter($actividades, function ($id) {
            return !empty($id) && is_numeric($id);
        }));
    }

    /**
     * Obtiene las reglas de validación para actividades
     */
    public function getValidationRules(): array
    {
        return [
            'actividades' => 'required|array|min:1',
            'actividades.*' => 'required|integer|exists:actividades_economicas,id',
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para actividades
     */
    public function getValidationMessages(): array
    {
        return [
            'actividades.required' => 'Debe seleccionar al menos una actividad económica.',
            'actividades.min' => 'Debe seleccionar al menos una actividad económica.',
            'actividades.*.required' => 'Debe seleccionar una actividad económica válida.',
            'actividades.*.exists' => 'La actividad económica seleccionada no es válida.',
        ];
    }

    /**
     * Obtiene los nombres de atributos para actividades
     */
    public function getValidationAttributes(): array
    {
        return [
            'actividades' => 'actividades económicas',
        ];
    }

    /**
     * Valida las actividades económicas (método legacy)
     */
    public function validar(array $datos): array
    {
        $errores = [];
        $actividades = $datos['actividades'] ?? [];

        if (empty($actividades)) {
            $errores[] = 'Debe seleccionar al menos una actividad económica';
            return $errores;
        }

        if (!is_array($actividades)) {
            $errores[] = 'Las actividades deben ser un arreglo válido';
            return $errores;
        }

        foreach ($actividades as $index => $actividadId) {
            if (!is_numeric($actividadId) || (int) $actividadId <= 0) {
                $errores[] = "La actividad en posición " . ($index + 1) . " no es válida";
            }
        }

        return $errores;
    }

    /**
     * Obtener actividades asociadas a un trámite
     */
    public function obtenerDatos(Tramite $tramite): array
    {
        return $tramite->actividades->map(function($actividad) {
            return [
                'id' => $actividad->id,
                'nombre' => $actividad->nombre,
                'descripcion' => $actividad->descripcion,
                'categoria' => $actividad->categoria,
            ];
        })->toArray();
    }
}