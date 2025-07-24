<?php

declare(strict_types=1);

namespace App\Services\Formularios;

use App\Models\Tramite;
use App\Models\Direccion;

class DireccionFormService
{
    /**
     * Procesa y guarda la dirección del formulario
     */
    public function procesar(Tramite $tramite, array $datos): void
    {
        if ($this->tieneDireccion($datos)) {
            $this->guardarDireccion($tramite, $datos);
        }
    }

    /**
     * Verifica si tiene datos de dirección
     */
    private function tieneDireccion(array $datos): bool
    {
        return !empty($datos['calle']) || !empty($datos['codigo_postal']);
    }

    /**
     * Guarda la dirección
     */
    private function guardarDireccion(Tramite $tramite, array $datos): void
    {
        Direccion::create([
            'id_tramite' => $tramite->id,
            'calle' => $datos['calle'] ?? 'Sin especificar',
            'numero_exterior' => $datos['numero_exterior'] ?? 'S/N',
            'numero_interior' => $datos['numero_interior'] ?? null,
            'codigo_postal' => $datos['codigo_postal'] ?? '00000',
            'colonia_asentamiento' => $datos['asentamiento'] ?? $datos['colonia_asentamiento'] ?? 'Sin especificar',
            'municipio' => $datos['municipio'] ?? 'Sin especificar',
            'id_estado' => $this->obtenerEstadoId($datos['estado_id'] ?? null),
            'entre_calles' => $datos['entre_calles'] ?? null,
            'es_principal' => true,
            'activo' => true,
        ]);
    }

    /**
     * Obtiene el ID del estado
     */
    private function obtenerEstadoId($estadoInput): int
    {
        if (empty($estadoInput)) {
            return 1;
        }

        if (is_numeric($estadoInput)) {
            return (int) $estadoInput;
        }

        $estadosMap = [
            'Puebla' => 21,
            'Ciudad de México' => 9,
            'CDMX' => 9,
            'Jalisco' => 14,
            'Nuevo León' => 19,
            'Oaxaca' => 20,
            'Veracruz' => 30,
        ];

        return $estadosMap[$estadoInput] ?? 1;
    }

    /**
     * Valida los datos de dirección
     */
    public function validar(array $datos): array
    {
        $errores = [];

        if (empty($datos['calle'])) {
            $errores[] = 'La calle es requerida';
        }

        if (empty($datos['numero_exterior'])) {
            $errores[] = 'El número exterior es requerido';
        }

        if (empty($datos['codigo_postal']) || !preg_match('/^\d{5}$/', $datos['codigo_postal'])) {
            $errores[] = 'El código postal debe tener 5 dígitos';
        }

        if (empty($datos['municipio'])) {
            $errores[] = 'El municipio es requerido';
        }

        return $errores;
    }

    /**
     * Obtener direcciones asociadas a un trámite
     */
    public function obtenerDatos(Tramite $tramite): ?array
    {
        $direcciones = $tramite->direcciones;
        if ($direcciones->isEmpty()) {
            return null;
        }

        return $direcciones->map(function ($direccion) {
            return [
                'calle' => $direccion->calle,
                'numero_exterior' => $direccion->numero_exterior,
                'numero_interior' => $direccion->numero_interior,
                'colonia' => $direccion->colonia ?? $direccion->colonia_asentamiento ?? null,
                'municipio' => $direccion->municipio,
                'estado' => $direccion->estado ?? $direccion->id_estado ?? null,
                'codigo_postal' => $direccion->codigo_postal,
                'pais' => $direccion->pais ?? 'México',
                'direccion_completa' => collect([
                    $direccion->calle,
                    $direccion->numero_exterior,
                    $direccion->numero_interior ? "Int. {$direccion->numero_interior}" : null,
                    $direccion->colonia ?? $direccion->colonia_asentamiento ?? null,
                    $direccion->municipio,
                    $direccion->estado ?? $direccion->id_estado ?? null,
                    $direccion->codigo_postal,
                    $direccion->pais ?? 'México',
                ])->filter()->implode(', '),
            ];
        })->toArray();
    }
}