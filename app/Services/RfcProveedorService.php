<?php

namespace App\Services;

use App\Models\User;
use App\Models\Proveedor;
use App\Models\Tramite;
use App\Models\CatalogoArchivo;
use Carbon\Carbon;

class RfcProveedorService
{
    public function determinarTipoPersona(string $rfc): string
    {
        $rfcLimpio = trim(strtoupper($rfc));
        
        if (!preg_match('/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/', $rfcLimpio)) {
            return 'Física';
        }
        
        if (strlen($rfcLimpio) === 13) {
            return 'Física';
        }
        
        if (strlen($rfcLimpio) === 12) {
            return 'Moral';
        }
        
        return 'Física';
    }

    public function obtenerRfcUsuario(): ?string
    {
        $user = auth()->user();
        return $user ? $user->rfc : null;
    }

    public function buscarProveedoresPorRfc(string $rfc): \Illuminate\Database\Eloquent\Collection
    {
        return Proveedor::where('rfc', $rfc)->get();
    }

    public function buscarProveedorActivo(string $rfc): ?Proveedor
    {
        $proveedor = Proveedor::where('rfc', $rfc)
            ->where('estado_padron', 'Activo')
            ->where('fecha_vencimiento_padron', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();
            
        \Log::info("Búsqueda de proveedor activo", [
            'rfc' => $rfc,
            'encontrado' => $proveedor ? 'Sí' : 'No',
            'proveedor_id' => $proveedor ? $proveedor->id : null,
            'pv_numero' => $proveedor ? $proveedor->pv_numero : null,
            'estado_padron' => $proveedor ? $proveedor->estado_padron : null,
            'fecha_vencimiento' => $proveedor ? $proveedor->fecha_vencimiento_padron : null,
            'fecha_actual' => Carbon::now()
        ]);
        
        return $proveedor;
    }

    public function proveedorEstaActivo(Proveedor $proveedor): bool
    {
        $estaActivo = $proveedor->estado_padron === 'Activo' && 
               $proveedor->fecha_vencimiento_padron && 
               $proveedor->fecha_vencimiento_padron > Carbon::now();
               
        \Log::info("Verificación de proveedor activo", [
            'proveedor_id' => $proveedor->id,
            'rfc' => $proveedor->rfc,
            'pv_numero' => $proveedor->pv_numero,
            'estado_padron' => $proveedor->estado_padron,
            'fecha_vencimiento' => $proveedor->fecha_vencimiento_padron,
            'fecha_actual' => Carbon::now(),
            'esta_activo' => $estaActivo,
            'condicion_estado' => $proveedor->estado_padron === 'Activo',
            'condicion_fecha_existe' => $proveedor->fecha_vencimiento_padron ? 'Sí' : 'No',
            'condicion_fecha_valida' => $proveedor->fecha_vencimiento_padron ? ($proveedor->fecha_vencimiento_padron > Carbon::now()) : false
        ]);
        
        return $estaActivo;
    }

    public function determinarAccion(string $rfc): array
    {
        $proveedores = $this->buscarProveedoresPorRfc($rfc);
        $proveedorActivo = $this->buscarProveedorActivo($rfc);
        
        if ($proveedores->isEmpty()) {
            return $this->crearRespuestaAccion('crear_nuevo', 'No existe proveedor para este RFC', null, 0);
        }
        
        if ($proveedorActivo && $this->proveedorEstaActivo($proveedorActivo)) {
            return $this->crearRespuestaAccion('actualizar_existente', 'Existe proveedor activo', $proveedorActivo, $proveedores->count());
        }
        
        return $this->crearRespuestaAccion('crear_nuevo', 'Proveedor existente no está activo o vencido', $proveedorActivo, $proveedores->count());
    }

    public function determinarAccionPorTipoTramite(string $rfc, string $tipoTramite, ?int $tramiteIdExcluir = null): array
    {
        $proveedores = $this->buscarProveedoresPorRfc($rfc);
        $proveedorActivo = $this->buscarProveedorActivo($rfc);
        $proveedorConTramitePendiente = $this->buscarProveedorConTramitePendiente($rfc, $tramiteIdExcluir);
        $tramitePendiente = $this->obtenerTramitePendiente($rfc, $tramiteIdExcluir);
        
        // Convertir el tipo de trámite a minúsculas para la comparación
        $tipoTramiteLower = strtolower($tipoTramite);
        
        // Verificar si hay trámite pendiente (cualquier estado activo)
        if ($proveedorConTramitePendiente && $tramitePendiente) {
            return $this->crearRespuestaAccion('tramite_pendiente', 'Tiene un trámite en proceso', $proveedorConTramitePendiente, $proveedores->count());
        }
        
        switch ($tipoTramiteLower) {
            case 'inscripcion':
                // Inscripción: Solo si no hay proveedor o si hay proveedor pero ya venció
                if (!$proveedorActivo) {
                    return $this->crearRespuestaAccion('crear_nuevo', 'No tiene proveedor registrado', null, $proveedores->count());
                }
                if ($proveedorActivo && !$this->proveedorEstaActivo($proveedorActivo)) {
                    return $this->crearRespuestaAccion('crear_nuevo', 'Proveedor vencido - puede reinscribirse', $proveedorActivo, $proveedores->count());
                }
                return $this->crearRespuestaAccion('no_inscribir', 'Ya tiene un proveedor activo', $proveedorActivo, $proveedores->count());
                
            case 'renovacion':
                // Renovación: Solo si hay proveedor y ya venció
                if (!$proveedorActivo) {
                    return $this->crearRespuestaAccion('no_renovar', 'No tiene proveedor para renovar', null, $proveedores->count());
                }
                if ($proveedorActivo && !$this->proveedorEstaActivo($proveedorActivo)) {
                    return $this->crearRespuestaAccion('renovar_vencido', 'Puede renovar proveedor vencido', $proveedorActivo, $proveedores->count());
                }
                return $this->crearRespuestaAccion('no_renovar', 'Proveedor aún no ha vencido', $proveedorActivo, $proveedores->count());
                
            case 'actualizacion':
                // Actualización: Solo si hay proveedor y NO ha vencido
                if (!$proveedorActivo) {
                    return $this->crearRespuestaAccion('no_actualizar', 'No tiene proveedor para actualizar', null, $proveedores->count());
                }
                if ($proveedorActivo && $this->proveedorEstaActivo($proveedorActivo)) {
                    return $this->crearRespuestaAccion('actualizar_existente', 'Puede actualizar proveedor activo', $proveedorActivo, $proveedores->count());
                }
                return $this->crearRespuestaAccion('no_actualizar', 'Proveedor vencido - debe renovar', $proveedorActivo, $proveedores->count());
                
            default:
                return $this->crearRespuestaAccion('error', 'Tipo de trámite no válido', null, $proveedores->count());
        }
    }

    public function buscarProveedorConTramitePendiente(string $rfc, ?int $tramiteIdExcluir = null): ?Proveedor
    {
        return Proveedor::where('rfc', $rfc)
            ->whereHas('tramites', function($query) use ($tramiteIdExcluir) {
                $query->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria', 'Para_Correccion']);
                
                // Excluir el trámite actual si se especifica
                if ($tramiteIdExcluir) {
                    $query->where('id', '!=', $tramiteIdExcluir);
                }
            })
            ->first();
    }

    public function tieneTramitePendiente(string $rfc, ?int $tramiteIdExcluir = null): bool
    {
        return $this->buscarProveedorConTramitePendiente($rfc, $tramiteIdExcluir) !== null;
    }

    private function crearRespuestaAccion(string $accion, string $motivo, ?Proveedor $proveedorActivo, int $totalProveedores): array
    {
        return [
            'accion' => $accion,
            'motivo' => $motivo,
            'proveedor_activo' => $proveedorActivo,
            'proveedores_existentes' => $totalProveedores
        ];
    }

    public function generarNumeroProveedor(string $rfc): string
    {
        try {
            \Log::info("Generando número de proveedor para RFC: {$rfc}");
            
            $ultimoProveedor = Proveedor::where('rfc', $rfc)
                ->whereNotNull('pv_numero')
                ->orderBy('pv_numero', 'desc')
                ->first();
            
            \Log::info("Búsqueda de último proveedor", [
                'rfc' => $rfc,
                'encontrado' => $ultimoProveedor ? 'Sí' : 'No',
                'ultimo_pv_numero' => $ultimoProveedor ? $ultimoProveedor->pv_numero : null,
                'ultimo_proveedor_id' => $ultimoProveedor ? $ultimoProveedor->id : null
            ]);
            
            if (!$ultimoProveedor || !$ultimoProveedor->pv_numero) {
                \Log::info("No se encontró proveedor previo para RFC {$rfc}, asignando 001");
                return '001';
            }
            
            $ultimoNumero = (int) $ultimoProveedor->pv_numero;
            $nuevoNumero = str_pad($ultimoNumero + 1, 3, '0', STR_PAD_LEFT);
            
            \Log::info("Número de proveedor generado", [
                'rfc' => $rfc,
                'ultimo_numero' => $ultimoProveedor->pv_numero,
                'nuevo_numero' => $nuevoNumero,
                'ultimo_proveedor_id' => $ultimoProveedor->id
            ]);
            
            return $nuevoNumero;
        } catch (\Exception $e) {
            \Log::error("Error generando número de proveedor para RFC {$rfc}", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            throw $e;
        }
    }

    public function obtenerArchivosPorTipoPersona(string $rfc): \Illuminate\Database\Eloquent\Collection
    {
        $tipoPersona = $this->determinarTipoPersona($rfc);
        
        return CatalogoArchivo::where('es_visible', true)
            ->where(function($query) use ($tipoPersona) {
                $query->where('tipo_persona', 'Ambas')
                      ->orWhere('tipo_persona', $tipoPersona);
            })
            ->orderBy('nombre')
            ->get();
    }

    public function obtenerArchivosPorTipoPersonaDirecto(string $tipoPersona): \Illuminate\Database\Eloquent\Collection
    {
        return CatalogoArchivo::where('es_visible', true)
            ->where(function($query) use ($tipoPersona) {
                $query->where('tipo_persona', 'Ambas')
                      ->orWhere('tipo_persona', $tipoPersona);
            })
            ->orderBy('nombre')
            ->get();
    }

    public function debugProveedoresRfc(string $rfc): array
    {
        \Log::info("=== DEBUG PROVEEDORES RFC: {$rfc} ===");
        
        $proveedores = $this->buscarProveedoresPorRfc($rfc);
        $proveedorActivo = $this->buscarProveedorActivo($rfc);
        
        $debugInfo = [
            'rfc' => $rfc,
            'total_proveedores' => $proveedores->count(),
            'proveedor_activo_encontrado' => $proveedorActivo ? 'Sí' : 'No',
            'proveedores_detalle' => []
        ];
        
        foreach ($proveedores as $proveedor) {
            $estaActivo = $this->proveedorEstaActivo($proveedor);
            $debugInfo['proveedores_detalle'][] = [
                'id' => $proveedor->id,
                'pv_numero' => $proveedor->pv_numero,
                'estado_padron' => $proveedor->estado_padron,
                'fecha_vencimiento' => $proveedor->fecha_vencimiento_padron,
                'esta_activo' => $estaActivo,
                'created_at' => $proveedor->created_at
            ];
        }
        
        \Log::info("Debug info", $debugInfo);
        
        return $debugInfo;
    }

    public function obtenerTramitePendiente(string $rfc, ?int $tramiteIdExcluir = null): ?\App\Models\Tramite
    {
        $query = Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria', 'Para_Correccion']);
        
        // Excluir el trámite actual si se especifica
        if ($tramiteIdExcluir) {
            $query->where('id', '!=', $tramiteIdExcluir);
        }
        
        return $query->orderBy('created_at', 'desc')->first();
    }
} 