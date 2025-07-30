<?php declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\DatosConstitutivos;
use App\Models\InstrumentoNotarial;
use App\Models\Tramite;

class DatosConstitutivosFormService
{
    /**
     * Procesa y guarda los datos constitutivos del formulario
     */
    public function procesar(Tramite $tramite, array $datos): void
    {
        if ($this->tieneDatosConstitutivos($datos)) {
            $this->guardarDatosConstitutivos($tramite, $datos);
        }
    }

    /**
     * Verifica si tiene datos constitutivos
     */
    private function tieneDatosConstitutivos(array $datos): bool
    {
        return !empty($datos['numero_escritura']) ||
            !empty($datos['fecha_constitucion']) ||
            !empty($datos['notario_nombre']);
    }

    /**
     * Guarda los datos constitutivos
     */
    private function guardarDatosConstitutivos(Tramite $tramite, array $datos): void
    {
        $instrumentoNotarial = InstrumentoNotarial::create([
            'numero_escritura' => $datos['numero_escritura'] ?? 'S/N',
            'numero_escritura_constitutiva' => $datos['numero_escritura_constitutiva'] ?? $datos['numero_escritura'] ?? 'S/N',
            'fecha_constitucion' => $datos['fecha_constitucion'] ?? now()->toDateString(),
            'nombre_notario' => $datos['notario_nombre'] ?? 'Sin especificar',
            'entidad_federativa' => $datos['entidad_federativa'] ?? 'Puebla',
            'numero_notario' => (int) ($datos['notario_numero'] ?? 1),
            'numero_registro_publico' => $datos['numero_registro'] ?? 'S/N',
            'fecha_inscripcion' => $datos['fecha_inscripcion'] ?? now()->toDateString(),
        ]);

        DatosConstitutivos::create([
            'tramite_id' => $tramite->id,
            'instrumento_notarial_id' => $instrumentoNotarial->id,
        ]);
    }

    /**
     * Obtiene las reglas de validación para datos constitutivos
     */
    public function getValidationRules(): array
    {
        return [
            'numero_escritura' => 'required|string|min:3|max:255',
            'fecha_constitucion' => 'required|date|before_or_equal:today',
            'notario_nombre' => 'required|string|min:3|max:255',
            'entidad_federativa' => 'required|string|max:255',
            'notario_numero' => 'required|integer|min:1|max:999999',
            'numero_registro' => 'required|string|min:3|max:255',
            'fecha_inscripcion' => 'required|date|after_or_equal:fecha_constitucion|before_or_equal:today',
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para datos constitutivos
     */
    public function getValidationMessages(): array
    {
        return [
            'numero_escritura.required' => 'El número de escritura es obligatorio.',
            'numero_escritura.min' => 'El número de escritura debe tener al menos 3 caracteres.',
            'numero_escritura.max' => 'El número de escritura no puede exceder 255 caracteres.',
            'fecha_constitucion.required' => 'La fecha de constitución es obligatoria.',
            'fecha_constitucion.date' => 'La fecha de constitución debe ser una fecha válida.',
            'fecha_constitucion.before_or_equal' => 'La fecha de constitución no puede ser posterior a hoy.',
            'notario_nombre.required' => 'El nombre del notario es obligatorio.',
            'notario_nombre.min' => 'El nombre del notario debe tener al menos 3 caracteres.',
            'notario_nombre.max' => 'El nombre del notario no puede exceder 255 caracteres.',
            'entidad_federativa.required' => 'La entidad federativa es obligatoria.',
            'entidad_federativa.max' => 'La entidad federativa no puede exceder 255 caracteres.',
            'notario_numero.required' => 'El número de notario es obligatorio.',
            'notario_numero.integer' => 'El número de notario debe ser un número entero.',
            'notario_numero.min' => 'El número de notario debe ser mayor a 0.',
            'notario_numero.max' => 'El número de notario no puede exceder 999999.',
            'numero_registro.required' => 'El número de registro es obligatorio.',
            'numero_registro.min' => 'El número de registro debe tener al menos 3 caracteres.',
            'numero_registro.max' => 'El número de registro no puede exceder 255 caracteres.',
            'fecha_inscripcion.required' => 'La fecha de inscripción es obligatoria.',
            'fecha_inscripcion.date' => 'La fecha de inscripción debe ser una fecha válida.',
            'fecha_inscripcion.after_or_equal' => 'La fecha de inscripción debe ser posterior o igual a la fecha de constitución.',
            'fecha_inscripcion.before_or_equal' => 'La fecha de inscripción no puede ser posterior a hoy.',
        ];
    }

    /**
     * Obtiene los nombres de atributos para datos constitutivos
     */
    public function getValidationAttributes(): array
    {
        return [
            'numero_escritura' => 'número de escritura',
            'fecha_constitucion' => 'fecha de constitución',
            'notario_nombre' => 'nombre del notario',
            'entidad_federativa' => 'entidad federativa',
            'notario_numero' => 'número de notario',
            'numero_registro' => 'número de registro',
            'fecha_inscripcion' => 'fecha de inscripción',
        ];
    }

    /**
     * Valida los datos constitutivos (método legacy)
     */
    public function validar(array $datos): array
    {
        $errores = [];

        if (empty($datos['numero_escritura'])) {
            $errores[] = 'El número de escritura es requerido';
        }

        if (empty($datos['fecha_constitucion'])) {
            $errores[] = 'La fecha de constitución es requerida';
        }

        if (empty($datos['notario_nombre'])) {
            $errores[] = 'El nombre del notario es requerido';
        }

        return $errores;
    }

    /**
     * Obtener datos constitutivos de un trámite
     */
    public function obtenerDatos(Tramite $tramite): ?array
    {
        $datosConstitutivos = $tramite->datosConstitutivos;
        if (!$datosConstitutivos) {
            return null;
        }

        $instrumentoNotarial = $datosConstitutivos->instrumentoNotarial;

        return [
            'fecha_constitucion' => $instrumentoNotarial->fecha_constitucion ?? 'N/A',
            'numero_escritura' => $instrumentoNotarial->numero_escritura ?? 'N/A',
            'notario' => $instrumentoNotarial->nombre_notario ?? 'N/A',
            'entidad_federativa' => $instrumentoNotarial->entidad_federativa ?? 'N/A',
            'numero_notario' => $instrumentoNotarial->numero_notario ?? 'N/A',
            'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? 'N/A',
            'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? 'N/A',
        ];
    }

    /**
     * Verificar si tiene datos completos
     */
    public function tienesDatosCompletos(Tramite $tramite): bool
    {
        return $tramite->datosConstitutivos !== null;
    }
} 