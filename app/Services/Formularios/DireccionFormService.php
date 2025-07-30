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
     * Obtiene las reglas de validación para dirección
     */
    public function getValidationRules(): array
    {
        return [
            'codigo_postal' => 'required|string|size:5',
            'calle' => 'required|string|min:5|max:255',
            'numero_exterior' => 'required|string|max:20',
            'numero_interior' => 'nullable|string|max:20',
            'entre_calles' => 'required|string|min:3|max:200',
            'asentamiento' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'estado_id' => 'nullable|integer|min:1',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para dirección
     */
    public function getValidationMessages(): array
    {
        return [
            'codigo_postal.required' => 'El código postal es obligatorio.',
            'codigo_postal.size' => 'El código postal debe tener exactamente 5 dígitos.',
            
            'calle.required' => 'La calle es obligatoria.',
            'calle.min' => 'La calle debe tener al menos 5 caracteres.',
            'calle.max' => 'La calle no puede exceder 255 caracteres.',
            
            'numero_exterior.required' => 'El número exterior es obligatorio.',
            'numero_exterior.max' => 'El número exterior no puede exceder 20 caracteres.',
            
            'numero_interior.max' => 'El número interior no puede exceder 20 caracteres.',
            
            'entre_calles.required' => 'El campo entre calles es obligatorio.',
            'entre_calles.min' => 'El campo entre calles debe tener al menos 3 caracteres.',
            'entre_calles.max' => 'El campo entre calles no puede exceder 200 caracteres.',
            
            'asentamiento.max' => 'El asentamiento no puede exceder 255 caracteres.',
            'municipio.max' => 'El municipio no puede exceder 255 caracteres.',
            'estado_id.min' => 'El estado seleccionado no es válido.',
            
            'latitud.numeric' => 'La latitud debe ser un número válido.',
            'latitud.between' => 'La latitud debe estar entre -90 y 90 grados.',
            'longitud.numeric' => 'La longitud debe ser un número válido.',
            'longitud.between' => 'La longitud debe estar entre -180 y 180 grados.',
        ];
    }

    /**
     * Obtiene los nombres de atributos para dirección
     */
    public function getValidationAttributes(): array
    {
        return [
            'codigo_postal' => 'código postal',
            'calle' => 'calle',
            'numero_exterior' => 'número exterior',
            'numero_interior' => 'número interior',
            'entre_calles' => 'entre calles',
            'asentamiento' => 'asentamiento',
            'municipio' => 'municipio',
            'estado_id' => 'estado',
            'latitud' => 'latitud',
            'longitud' => 'longitud',
        ];
    }

    /**
     * Valida los datos de dirección (método legacy)
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