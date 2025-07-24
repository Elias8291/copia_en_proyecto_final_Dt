<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tramite;
use App\Models\Direccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DireccionService
{
    private const ESTADOS_MAP = [
        'Puebla' => 21,
        'Ciudad de México' => 9,
        'CDMX' => 9,
        'Jalisco' => 14,
        'Nuevo León' => 19,
        'Oaxaca' => 20,
        'Veracruz' => 30,
        // Agregar más según necesidades
    ];

    /**
     * Guarda la dirección del trámite
     */
    public function guardar(Tramite $tramite, Request $request): void
    {
        if (!$this->tieneDatosDireccion($request)) {
            Log::info('No se enviaron datos de dirección', ['tramite_id' => $tramite->id]);
            return;
        }

        $datos = $this->extraerDatos($request);

        try {
            Direccion::create([
                'id_tramite' => $tramite->id,
                'calle' => $datos['calle'],
                'entre_calles' => $datos['entre_calles'],
                'numero_exterior' => $datos['numero_exterior'],
                'numero_interior' => $datos['numero_interior'],
                'codigo_postal' => $datos['codigo_postal'],
                'colonia_asentamiento' => $datos['asentamiento'],
                'municipio' => $datos['municipio'],
                'id_estado' => $datos['estado_id'],
                'tipo_asentamiento' => $datos['tipo_asentamiento'],
            ]);

            Log::info('Dirección guardada exitosamente', [
                'tramite_id' => $tramite->id,
                'codigo_postal' => $datos['codigo_postal']
            ]);

        } catch (\Exception $e) {
            Log::error('Error al guardar dirección', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Verifica si el request contiene datos de dirección
     */
    private function tieneDatosDireccion(Request $request): bool
    {
        return $request->filled('codigo_postal') || $request->filled('calle');
    }

    /**
     * Extrae y procesa los datos de dirección del request
     */
    private function extraerDatos(Request $request): array
    {
        return [
            'calle' => $request->input('calle', 'Calle Default'),
            'entre_calles' => $request->input('entre_calles'),
            'numero_exterior' => $request->input('numero_exterior', 'S/N'),
            'numero_interior' => $request->input('numero_interior'),
            'codigo_postal' => $request->input('codigo_postal', '00000'),
            'asentamiento' => $request->input('asentamiento') 
                ?? $request->input('asentamiento_select') 
                ?? 'Asentamiento Default',
            'municipio' => $request->input('municipio', 'Municipio Default'),
            'estado_id' => $this->procesarEstadoId($request),
            'tipo_asentamiento' => $request->input('tipo_asentamiento'),
        ];
    }

    /**
     * Procesa el estado_id y lo convierte a entero válido
     */
    private function procesarEstadoId(Request $request): int
    {
        $estadoId = $request->input('estado_id');

        // Si es numérico válido, usarlo
        if (!empty($estadoId) && is_numeric($estadoId) && (int)$estadoId > 0) {
            return (int) $estadoId;
        }

        // Si es texto (nombre del estado), mapear al ID
        if (!empty($estadoId) && is_string($estadoId)) {
            return self::ESTADOS_MAP[$estadoId] ?? 1;
        }

        // Default: primer estado
        return 1;
    }
} 