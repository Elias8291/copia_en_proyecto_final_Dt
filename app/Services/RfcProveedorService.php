<?php

namespace App\Services;

use App\Models\User;
use App\Models\Proveedor;
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
        return Proveedor::where('rfc', $rfc)
            ->where('estado_padron', 'activo')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function proveedorEstaActivo(Proveedor $proveedor): bool
    {
        return $proveedor->estado_padron === 'activo' && 
               $proveedor->fecha_vencimiento_padron > Carbon::now();
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

    public function determinarAccionPorTipoTramite(string $rfc, string $tipoTramite): array
    {
        $proveedores = $this->buscarProveedoresPorRfc($rfc);
        $proveedorActivo = $this->buscarProveedorActivo($rfc);
        $proveedorConTramitePendiente = $this->buscarProveedorConTramitePendiente($rfc);
        
        // Convertir el tipo de trámite a minúsculas para la comparación
        $tipoTramiteLower = strtolower($tipoTramite);
        
        switch ($tipoTramiteLower) {
            case 'inscripcion':
                if ($proveedorConTramitePendiente) {
                    return $this->crearRespuestaAccion('tramite_pendiente', 'Tiene un trámite pendiente', $proveedorConTramitePendiente, $proveedores->count());
                }
                if ($proveedorActivo && $this->proveedorEstaActivo($proveedorActivo)) {
                    return $this->crearRespuestaAccion('proveedor_activo', 'Ya tiene un proveedor activo', $proveedorActivo, $proveedores->count());
                }
                return $this->crearRespuestaAccion('crear_nuevo', 'Puede crear nueva inscripción', $proveedorActivo, $proveedores->count());
                
            case 'renovacion':
                if ($proveedorConTramitePendiente) {
                    return $this->crearRespuestaAccion('tramite_pendiente', 'Tiene un trámite pendiente', $proveedorConTramitePendiente, $proveedores->count());
                }
                if ($proveedorActivo && $this->proveedorEstaActivo($proveedorActivo)) {
                    return $this->crearRespuestaAccion('renovar_activo', 'Puede renovar proveedor activo', $proveedorActivo, $proveedores->count());
                }
                if ($proveedorActivo && !$this->proveedorEstaActivo($proveedorActivo)) {
                    return $this->crearRespuestaAccion('renovar_vencido', 'Puede renovar proveedor vencido', $proveedorActivo, $proveedores->count());
                }
                return $this->crearRespuestaAccion('no_renovar', 'No tiene proveedor para renovar', null, $proveedores->count());
                
            case 'actualizacion':
                if ($proveedorConTramitePendiente) {
                    return $this->crearRespuestaAccion('tramite_pendiente', 'Tiene un trámite pendiente', $proveedorConTramitePendiente, $proveedores->count());
                }
                if ($proveedorActivo) {
                    return $this->crearRespuestaAccion('actualizar_existente', 'Puede actualizar proveedor existente', $proveedorActivo, $proveedores->count());
                }
                return $this->crearRespuestaAccion('no_actualizar', 'No tiene proveedor para actualizar', null, $proveedores->count());
                
            default:
                return $this->crearRespuestaAccion('error', 'Tipo de trámite no válido', null, $proveedores->count());
        }
    }

    public function buscarProveedorConTramitePendiente(string $rfc): ?Proveedor
    {
        return Proveedor::where('rfc', $rfc)
            ->whereHas('tramites', function($query) {
                $query->where('status', 'Pendiente');
            })
            ->first();
    }

    public function tieneTramitePendiente(string $rfc): bool
    {
        return $this->buscarProveedorConTramitePendiente($rfc) !== null;
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
        $ultimoProveedor = Proveedor::where('rfc', $rfc)
            ->orderBy('pv_numero', 'desc')
            ->first();
        
        if (!$ultimoProveedor) {
            return '001';
        }
        
        $ultimoNumero = (int) $ultimoProveedor->pv_numero;
        return str_pad($ultimoNumero + 1, 3, '0', STR_PAD_LEFT);
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
} 