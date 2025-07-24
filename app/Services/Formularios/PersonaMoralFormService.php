<?php

declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\Tramite;
use App\Models\InstrumentoNotarial;
use App\Models\DatosConstitutivos;
use App\Models\ApoderadoLegal;
use App\Models\Accionista;

class PersonaMoralFormService
{
    /**
     * Procesa los datos específicos de persona moral
     */
    public function procesar(Tramite $tramite, array $datos): void
    {
        // Datos constitutivos
        if ($this->tieneDatosConstitutivos($datos)) {
            $this->guardarDatosConstitutivos($tramite, $datos);
        }

        // Apoderado legal
        if ($this->tieneApoderadoLegal($datos)) {
            $this->guardarApoderadoLegal($tramite, $datos);
        }

        // Accionistas
        if (!empty($datos['accionistas'])) {
            $this->guardarAccionistas($tramite, $datos['accionistas']);
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
     * Verifica si tiene datos de apoderado legal
     */
    private function tieneApoderadoLegal(array $datos): bool
    {
        return !empty($datos['apoderado_nombre']) || 
               !empty($datos['apoderado_rfc']) || 
               !empty($datos['poder_numero_escritura']);
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
     * Valida los datos de persona moral
     */
    public function validar(array $datos): array
    {
        $errores = [];

        // Validar datos constitutivos
        if (!empty($datos['fecha_constitucion'])) {
            $fecha = \DateTime::createFromFormat('Y-m-d', $datos['fecha_constitucion']);
            if (!$fecha || $fecha > new \DateTime()) {
                $errores[] = 'La fecha de constitución no puede ser futura';
            }
        }

        // Validar apoderado legal
        if (!empty($datos['apoderado_rfc'])) {
            if (!preg_match('/^[A-ZÑ&]{4}[0-9]{6}[A-Z0-9]{3}$/', $datos['apoderado_rfc'])) {
                $errores[] = 'El RFC del apoderado debe tener formato válido de persona física';
            }
        }

        // Validar accionistas
        if (!empty($datos['accionistas'])) {
            $errores = array_merge($errores, $this->validarAccionistas($datos['accionistas']));
        }

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
     * Obtener datos constitutivos asociados a un trámite
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
}