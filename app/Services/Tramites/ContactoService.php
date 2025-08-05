<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\Contacto;
use Illuminate\Http\Request;

class ContactoService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        \Log::info('ContactoService: Iniciando guardado', [
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'request_data' => $request->all()
        ]);
        
        try {
            $contacto = Contacto::create([
                'tramite_id' => $tramite->id,
                'proveedor_id' => $proveedor->id,
                'nombre_contacto' => $request->nombre_contacto,
                'cargo' => $request->cargo,
                'correo_electronico' => $request->correo_contacto,
                'telefono' => $request->telefono_contacto,
                'status' => 'pendiente',
            ]);
            
            \Log::info('ContactoService: Contacto creado exitosamente', [
                'contacto_id' => $contacto->id
            ]);
        } catch (\Exception $e) {
            \Log::error('ContactoService: Error al crear contacto', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Obtiene los datos de contacto de un trámite
     */
    public function obtener(Tramite $tramite): ?array
    {
        $contacto = $tramite->contactos()->latest()->first();
        
        if (!$contacto) {
            return null;
        }
        
        return [
            'nombre_contacto' => $contacto->nombre_contacto,
            'cargo' => $contacto->cargo,
            'correo_electronico' => $contacto->correo_electronico,
            'telefono' => $contacto->telefono,
        ];
    }
} 