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

    /**
     * Actualiza los datos de contacto de un trámite existente durante corrección
     */
    public function actualizar(Tramite $tramite, Request $request): void
    {
        \Log::info('ContactoService: Iniciando actualización', [
            'tramite_id' => $tramite->id,
            'request_data' => $request->only(['nombre_contacto', 'cargo', 'correo_contacto', 'telefono_contacto'])
        ]);

        try {
            $contacto = $tramite->contactos()->latest()->first();

            if (!$contacto) {
                \Log::warning('ContactoService: No existe contacto previo para actualizar', [
                    'tramite_id' => $tramite->id
                ]);
                return;
            }

            $updates = [];
            if ($request->filled('nombre_contacto')) {
                $updates['nombre_contacto'] = $request->nombre_contacto;
            }
            if ($request->filled('cargo')) {
                $updates['cargo'] = $request->cargo;
            }
            if ($request->filled('correo_contacto')) {
                $updates['correo_electronico'] = $request->correo_contacto;
            }
            if ($request->filled('telefono_contacto')) {
                $updates['telefono'] = $request->telefono_contacto;
            }

            if (!empty($updates)) {
                $contacto->update($updates);
                \Log::info('ContactoService: Actualización completada', [
                    'tramite_id' => $tramite->id,
                    'contacto_id' => $contacto->id,
                    'updates' => $updates
                ]);
            } else {
                \Log::info('ContactoService: No hay datos de contacto para actualizar', [
                    'tramite_id' => $tramite->id
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('ContactoService: Error al actualizar contacto', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
} 