<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tramite;
use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactoService
{
    /**
     * Guarda la información de contacto del trámite
     */
    public function guardar(Tramite $tramite, Request $request): void
    {
        if (!$this->tieneDatosContacto($request)) {
            Log::info('No se enviaron datos de contacto', ['tramite_id' => $tramite->id]);
            return;
        }

        $datos = $this->extraerDatos($request);

        try {
            Contacto::create([
                'tramite_id' => $tramite->id,
                'nombre_contacto' => $datos['nombre_contacto'],
                'cargo' => $datos['cargo'],
                'correo_electronico' => $datos['correo_electronico'],
                'telefono' => $datos['telefono'],
            ]);

            Log::info('Contacto guardado exitosamente', [
                'tramite_id' => $tramite->id,
                'email' => $datos['correo_electronico']
            ]);

        } catch (\Exception $e) {
            Log::error('Error al guardar contacto', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Verifica si el request contiene datos de contacto
     */
    private function tieneDatosContacto(Request $request): bool
    {
        return $request->filled('email_contacto') || $request->filled('telefono');
    }

    /**
     * Extrae los datos de contacto del request
     */
    private function extraerDatos(Request $request): array
    {
        return [
            'nombre_contacto' => $request->input('razon_social', 'Contacto Default'),
            'cargo' => $request->input('cargo', 'Representante'),
            'correo_electronico' => $request->input('email_contacto', 'default@email.com'),
            'telefono' => $request->input('telefono', '000-000-0000'),
        ];
    }
} 