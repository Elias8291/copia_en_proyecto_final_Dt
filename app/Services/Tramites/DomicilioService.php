<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\Direccion;
use Illuminate\Http\Request;

class DomicilioService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        \Log::info('DomicilioService: Iniciando guardado', [
            'tramite_id' => $tramite->id,
            'proveedor_id' => $proveedor->id,
            'request_data' => $request->all()
        ]);
        
        try {
            // Manejar coordenadas
            $coordenadaId = $this->manejarCoordenadas($request);
            
            $direccion = Direccion::create([
                'tramite_id' => $tramite->id,
                'proveedor_id' => $proveedor->id,
                'calle' => $request->calle,
                'entre_calle' => $request->entre_calle,
                'y_calle' => $request->y_calle,
                'numero_exterior' => $request->numero_exterior,
                'numero_interior' => $request->numero_interior,
                'colonia' => $request->colonia,
                'codigo_postal' => $request->codigo_postal,
                'municipio' => $request->municipio,
                'asentamiento' => $request->asentamiento,
                'estado_id' => $request->estado_id,
                'coordenada_id' => $coordenadaId,
                'status' => 'pendiente',
            ]);
            
            \Log::info('DomicilioService: Dirección creada exitosamente', [
                'direccion_id' => $direccion->id,
                'coordenada_id' => $coordenadaId
            ]);
        } catch (\Exception $e) {
            \Log::error('DomicilioService: Error al crear dirección', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    private function manejarCoordenadas(Request $request): ?int
    {
        $latitud = $request->latitud;
        $longitud = $request->longitud;
        
        \Log::info('DomicilioService: Manejando coordenadas', [
            'latitud' => $latitud,
            'longitud' => $longitud
        ]);
        
        // Si hay latitud y longitud válidas, crear una nueva coordenada
        if ($latitud && $longitud && is_numeric($latitud) && is_numeric($longitud)) {
            $coordenada = \App\Models\Coordenada::create([
                'latitud' => $latitud,
                'longitud' => $longitud,
                'descripcion' => 'Coordenada del domicilio - ' . $request->calle . ' ' . $request->numero_exterior
            ]);
            
            \Log::info('DomicilioService: Nueva coordenada creada para el domicilio', [
                'coordenada_id' => $coordenada->id,
                'latitud' => $latitud,
                'longitud' => $longitud,
                'domicilio' => $request->calle . ' ' . $request->numero_exterior
            ]);
            
            return $coordenada->id;
        }
        
        // Si no hay coordenadas válidas, crear una coordenada por defecto
        $coordenadaDefault = \App\Models\Coordenada::create([
            'latitud' => 19.4326,
            'longitud' => -99.1332,
            'descripcion' => 'Coordenada por defecto - Domicilio sin coordenadas específicas'
        ]);
        
        \Log::info('DomicilioService: Coordenada por defecto creada', [
            'coordenada_id' => $coordenadaDefault->id,
            'domicilio' => $request->calle . ' ' . $request->numero_exterior
        ]);
        
        return $coordenadaDefault->id;
    }
} 