<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\InstrumentoNotarial;
use App\Http\Requests\TramiteFormularioRequest;
use Illuminate\Support\Facades\Log;

class InstrumentoNotarialService
{
    /**
     * Crea un instrumento notarial desde el request
     */
    public function crear(TramiteFormularioRequest $request): ?InstrumentoNotarial
    {
        if (!$this->tieneInformacionNotarial($request)) {
            Log::info('No hay información notarial para procesar');
            return null;
        }

        try {
            $instrumentoNotarial = InstrumentoNotarial::create([
                'numero_escritura' => $request->input('numero_escritura'),
                'numero_escritura_constitutiva' => $request->input('numero_escritura'),
                'fecha_constitucion' => $request->input('fecha_constitucion', now()->toDateString()),
                'nombre_notario' => $request->input('notario_nombre'),
                'entidad_federativa' => $request->input('entidad_federativa', 'No especificado'),
                'numero_notario' => (int) $request->input('notario_numero', 1),
                'numero_registro_publico' => $request->input('numero_registro', 'N/A'),
                'fecha_inscripcion' => $request->input('fecha_inscripcion', now()->toDateString()),
            ]);

            Log::info('Instrumento notarial creado', [
                'id' => $instrumentoNotarial->id,
                'numero_escritura' => $instrumentoNotarial->numero_escritura
            ]);

            return $instrumentoNotarial;

        } catch (\Exception $e) {
            Log::error('Error al crear instrumento notarial', [
                'error' => $e->getMessage(),
                'datos' => $this->extraerDatos($request)
            ]);
            return null;
        }
    }

    /**
     * Verifica si el request contiene información notarial
     */
    private function tieneInformacionNotarial(TramiteFormularioRequest $request): bool
    {
        return $request->filled(['numero_escritura', 'notario_nombre']);
    }

    /**
     * Extrae los datos del request para logging
     */
    private function extraerDatos(TramiteFormularioRequest $request): array
    {
        return [
            'numero_escritura' => $request->input('numero_escritura'),
            'notario_nombre' => $request->input('notario_nombre'),
            'fecha_constitucion' => $request->input('fecha_constitucion'),
            'entidad_federativa' => $request->input('entidad_federativa'),
            'notario_numero' => $request->input('notario_numero'),
        ];
    }
} 