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

        // Validación adicional para asegurar que el cargo no sea null
        if (empty($datos['cargo']) || is_null($datos['cargo'])) {
            $datos['cargo'] = 'Representante Legal';
        }

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
                'email' => $datos['correo_electronico'],
                'cargo' => $datos['cargo']
            ]);

        } catch (\Exception $e) {
            Log::error('Error al guardar contacto', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage(),
                'datos_intentados' => $datos
            ]);
            throw $e;
        }
    }

    /**
     * Verifica si el request contiene datos de contacto
     */
    private function tieneDatosContacto(Request $request): bool
    {
        // Verificar si hay datos básicos de contacto
        $tieneEmail = $request->filled('email_contacto');
        $tieneTelefono = $request->filled('telefono');
        $tieneRazonSocial = $request->filled('razon_social');
        
        // Si tiene al menos email o teléfono, o razón social, considerar que tiene datos
        return $tieneEmail || $tieneTelefono || $tieneRazonSocial;
    }

    /**
     * Extrae los datos de contacto del request
     */
    private function extraerDatos(Request $request): array
    {
        // Obtener el cargo del request
        $cargo = $request->input('cargo');
        
        // Asegurar que el cargo nunca sea null o vacío
        if (empty($cargo) || is_null($cargo) || trim($cargo) === '') {
            $cargo = 'Representante Legal';
        }
        
        // Obtener el nombre del contacto
        $nombreContacto = $request->input('razon_social');
        if (empty($nombreContacto)) {
            $nombreContacto = 'Contacto Principal';
        }
        
        // Obtener el email
        $email = $request->input('email_contacto');
        if (empty($email)) {
            $email = 'contacto@empresa.com';
        }
        
        // Obtener el teléfono
        $telefono = $request->input('telefono');
        if (empty($telefono)) {
            $telefono = '000-000-0000';
        }
        
        $datos = [
            'nombre_contacto' => $nombreContacto,
            'cargo' => $cargo,
            'correo_electronico' => $email,
            'telefono' => $telefono,
        ];
        
        // Log para debug
        Log::info('Datos de contacto procesados', [
            'tramite_id' => $request->input('tramite_id'),
            'datos_finales' => $datos
        ]);
        
        return $datos;
    }
} 