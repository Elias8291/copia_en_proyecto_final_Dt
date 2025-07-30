<?php declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\ApoderadoLegal;
use App\Models\InstrumentoNotarial;
use App\Models\Tramite;

class ApoderadoLegalFormService
{
    /**
     * Procesa y guarda los datos del apoderado legal del formulario
     */
    public function procesar(Tramite $tramite, array $datos): void
    {
        if ($this->tieneApoderadoLegal($datos)) {
            $this->guardarApoderadoLegal($tramite, $datos);
        }
    }

    /**
     * Verifica si tiene datos de apoderado legal
     */
    private function tieneApoderadoLegal(array $datos): bool
    {
        return !empty($datos['apoderado_nombre']) ||
            !empty($datos['apoderado_rfc']) ||
            !empty($datos['poder_numero_escritura']);
    }

    /**
     * Guarda el apoderado legal
     */
    private function guardarApoderadoLegal(Tramite $tramite, array $datos): void
    {
        $instrumentoNotarialPoder = InstrumentoNotarial::create([
            'numero_escritura' => $datos['poder_numero_escritura'] ?? 'S/N',
            'numero_escritura_constitutiva' => $datos['poder_numero_escritura'] ?? 'S/N',
            'fecha_constitucion' => $datos['poder_fecha_constitucion'] ?? now()->toDateString(),
            'nombre_notario' => $datos['poder_notario_nombre'] ?? 'Sin especificar',
            'entidad_federativa' => $datos['poder_entidad_federativa'] ?? 'Puebla',
            'numero_notario' => (int) ($datos['poder_notario_numero'] ?? 1),
            'numero_registro_publico' => $datos['poder_numero_registro'] ?? 'S/N',
            'fecha_inscripcion' => $datos['poder_fecha_constitucion'] ?? now()->toDateString(),
        ]);

        ApoderadoLegal::create([
            'tramite_id' => $tramite->id,
            'instrumento_notarial_id' => $instrumentoNotarialPoder->id,
            'nombre_apoderado' => $datos['apoderado_nombre'] ?? 'Sin especificar',
            'rfc' => $datos['apoderado_rfc'] ?? 'TEMP000000000',
        ]);
    }

    /**
     * Obtiene las reglas de validación para apoderado legal
     */
    public function getValidationRules(): array
    {
        return [
            'apoderado_nombre' => 'required|string|min:3|max:255',
            'apoderado_rfc' => 'required|string|size:13|regex:/^[A-ZÑ&]{4}[0-9]{6}[A-V1-9A-Z0-9]{3}$/',
            'poder_numero_escritura' => 'required|string|min:3|max:255',
            'poder_fecha_constitucion' => 'required|date|before_or_equal:today',
            'poder_notario_nombre' => 'required|string|min:3|max:255',
            'poder_entidad_federativa' => 'required|string|max:255',
            'poder_notario_numero' => 'required|integer|min:1|max:999999',
            'poder_numero_registro' => 'required|string|min:3|max:255',
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para apoderado legal
     */
    public function getValidationMessages(): array
    {
        return [
            'apoderado_nombre.required' => 'El nombre del apoderado es obligatorio.',
            'apoderado_nombre.min' => 'El nombre del apoderado debe tener al menos 3 caracteres.',
            'apoderado_nombre.max' => 'El nombre del apoderado no puede exceder 255 caracteres.',
            'apoderado_rfc.required' => 'El RFC del apoderado es obligatorio.',
            'apoderado_rfc.size' => 'El RFC del apoderado debe tener exactamente 13 caracteres.',
            'apoderado_rfc.regex' => 'El RFC del apoderado debe tener un formato válido de persona física.',
            'poder_numero_escritura.required' => 'El número de escritura del poder es obligatorio.',
            'poder_numero_escritura.min' => 'El número de escritura del poder debe tener al menos 3 caracteres.',
            'poder_numero_escritura.max' => 'El número de escritura del poder no puede exceder 255 caracteres.',
            'poder_fecha_constitucion.required' => 'La fecha de constitución del poder es obligatoria.',
            'poder_fecha_constitucion.date' => 'La fecha de constitución del poder debe ser una fecha válida.',
            'poder_fecha_constitucion.before_or_equal' => 'La fecha de constitución del poder no puede ser posterior a hoy.',
            'poder_notario_nombre.required' => 'El nombre del notario del poder es obligatorio.',
            'poder_notario_nombre.min' => 'El nombre del notario del poder debe tener al menos 3 caracteres.',
            'poder_notario_nombre.max' => 'El nombre del notario del poder no puede exceder 255 caracteres.',
            'poder_entidad_federativa.required' => 'La entidad federativa del poder es obligatoria.',
            'poder_entidad_federativa.max' => 'La entidad federativa del poder no puede exceder 255 caracteres.',
            'poder_notario_numero.required' => 'El número de notario del poder es obligatorio.',
            'poder_notario_numero.integer' => 'El número de notario del poder debe ser un número entero.',
            'poder_notario_numero.min' => 'El número de notario del poder debe ser mayor a 0.',
            'poder_notario_numero.max' => 'El número de notario del poder no puede exceder 999999.',
            'poder_numero_registro.required' => 'El número de registro del poder es obligatorio.',
            'poder_numero_registro.min' => 'El número de registro del poder debe tener al menos 3 caracteres.',
            'poder_numero_registro.max' => 'El número de registro del poder no puede exceder 255 caracteres.',
        ];
    }

    /**
     * Obtiene los nombres de atributos para apoderado legal
     */
    public function getValidationAttributes(): array
    {
        return [
            'apoderado_nombre' => 'nombre del apoderado',
            'apoderado_rfc' => 'RFC del apoderado',
            'poder_numero_escritura' => 'número de escritura del poder',
            'poder_fecha_constitucion' => 'fecha de constitución del poder',
            'poder_notario_nombre' => 'nombre del notario del poder',
            'poder_entidad_federativa' => 'entidad federativa del poder',
            'poder_notario_numero' => 'número de notario del poder',
            'poder_numero_registro' => 'número de registro del poder',
        ];
    }

    /**
     * Valida los datos del apoderado legal (método legacy)
     */
    public function validar(array $datos): array
    {
        $errores = [];

        if (empty($datos['apoderado_nombre'])) {
            $errores[] = 'El nombre del apoderado es requerido';
        }

        if (empty($datos['apoderado_rfc'])) {
            $errores[] = 'El RFC del apoderado es requerido';
        }

        if (!empty($datos['apoderado_rfc'])) {
            if (!preg_match('/^[A-ZÑ&]{4}[0-9]{6}[A-Z0-9]{3}$/', $datos['apoderado_rfc'])) {
                $errores[] = 'El RFC del apoderado debe tener formato válido de persona física';
            }
        }

        return $errores;
    }

    /**
     * Obtener datos del apoderado legal de un trámite
     */
    public function obtenerDatos(Tramite $tramite): ?array
    {
        $apoderadoLegal = $tramite->apoderadoLegal;
        if (!$apoderadoLegal) {
            return null;
        }

        $instrumentoNotarial = $apoderadoLegal->instrumentoNotarial;

        return [
            'apoderado_nombre' => $apoderadoLegal->nombre_apoderado ?? 'N/A',
            'apoderado_rfc' => $apoderadoLegal->rfc ?? 'N/A',
            'poder_numero_escritura' => $instrumentoNotarial->numero_escritura ?? 'N/A',
            'poder_fecha_constitucion' => $instrumentoNotarial->fecha_constitucion ?? 'N/A',
            'poder_notario_nombre' => $instrumentoNotarial->nombre_notario ?? 'N/A',
            'poder_entidad_federativa' => $instrumentoNotarial->entidad_federativa ?? 'N/A',
            'poder_notario_numero' => $instrumentoNotarial->numero_notario ?? 'N/A',
            'poder_numero_registro' => $instrumentoNotarial->numero_registro_publico ?? 'N/A',
        ];
    }

    /**
     * Verificar si tiene datos completos
     */
    public function tienesDatosCompletos(Tramite $tramite): bool
    {
        return $tramite->apoderadoLegal !== null;
    }
} 