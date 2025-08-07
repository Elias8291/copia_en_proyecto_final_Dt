<?php

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Models\Proveedor;
use App\Models\Direccion;
use App\Models\Coordenada;
use Illuminate\Http\Request;

class DomicilioService
{
    public function guardar(Tramite $tramite, Proveedor $proveedor, Request $request): void
    {
        $coordenadaId = $this->crearCoordenada($request);
        
        Direccion::create([
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
    }

    /**
     * Obtiene los datos de domicilio de un trámite
     */
    public function obtener(Tramite $tramite): ?array
    {
        $domicilio = $tramite->direcciones()->with(['coordenada', 'estado'])->latest()->first();
        
        if (!$domicilio) {
            return null;
        }
        
        return $domicilio->toArray();
    }
    
    private function crearCoordenada(Request $request): int
    {
        $latitud = $request->latitud;
        $longitud = $request->longitud;
        
        // Si hay coordenadas válidas, usarlas; si no, usar coordenadas por defecto
        if ($latitud && $longitud && is_numeric($latitud) && is_numeric($longitud)) {
            $descripcion = 'Coordenada del domicilio - ' . $request->calle . ' ' . $request->numero_exterior;
        } else {
            $latitud = 19.4326;
            $longitud = -99.1332;
            $descripcion = 'Coordenada por defecto - Domicilio sin coordenadas específicas';
        }
        
        $coordenada = Coordenada::create([
            'latitud' => $latitud,
            'longitud' => $longitud,
            'descripcion' => $descripcion
        ]);
        
        return $coordenada->id;
    }

    /**
     * Actualizar domicilio de un trámite existente
     */
    public function actualizar(Tramite $tramite, Request $request): void
    {
        if ($request->filled('domicilio')) {
            $domicilioData = $request->domicilio;
            $domicilio = $tramite->direcciones->first();
            
            if ($domicilio) {
                $domicilio->update([
                    'estado_id' => $domicilioData['estado_id'] ?? null,
                    'municipio' => $domicilioData['municipio'] ?? null,
                    'asentamiento' => $domicilioData['asentamiento'] ?? null,
                    'codigo_postal' => $domicilioData['codigo_postal'] ?? null,
                    'calle' => $domicilioData['calle'] ?? null,
                    'numero_exterior' => $domicilioData['numero_exterior'] ?? null,
                    'numero_interior' => $domicilioData['numero_interior'] ?? null,
                    'entre_calle' => $domicilioData['entre_calle'] ?? null,
                    'y_calle' => $domicilioData['y_calle'] ?? null,
                    'colonia' => $domicilioData['colonia'] ?? null,
                ]);
            }
        }
    }
} 