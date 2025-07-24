<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tramite;
use App\Models\DatosGenerales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DatosGeneralesService
{
    /**
     * Guarda los datos generales del trámite
     */
    public function guardar(Tramite $tramite, Request $request): void
    {
        $datos = $this->extraerDatos($request);

        try {
            DatosGenerales::create([
                'tramite_id' => $tramite->id,
                'curp' => $datos['curp'],
                'razon_social' => $datos['razon_social'],
                'pagina_web' => $datos['pagina_web'],
                'telefono' => $datos['telefono'],
            ]);

            Log::info('Datos generales guardados exitosamente', [
                'tramite_id' => $tramite->id,
                'razon_social' => $datos['razon_social']
            ]);

        } catch (\Exception $e) {
            Log::error('Error al guardar datos generales', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Extrae y valida los datos del request
     */
    private function extraerDatos(Request $request): array
    {
        return [
            'curp' => $request->input('curp'),
            'razon_social' => $request->input('razon_social', 'Razón Social Default'),
            'pagina_web' => $request->input('pagina_web'),
            'telefono' => $request->input('telefono'),
        ];
    }
} 