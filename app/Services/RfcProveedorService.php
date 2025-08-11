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

    /**
     * Buscar proveedor que puede ser reutilizado (activo con fecha válida o sin fecha de vencimiento)
     */
    public function buscarProveedorReutilizable(string $rfc): ?Proveedor
    {
        \Log::info("Iniciando búsqueda de proveedor reutilizable", ['rfc' => $rfc]);
        
        // Primero buscar proveedor activo con fecha válida
        $proveedor = $this->buscarProveedorActivo($rfc);
        
        if ($proveedor) {
            \Log::info("Encontrado proveedor activo con fecha válida", [
                'rfc' => $rfc,
                'proveedor_id' => $proveedor->id,
                'pv_numero' => $proveedor->pv_numero
            ]);
            return $proveedor;
        }
        
        \Log::info("No se encontró proveedor activo con fecha válida, buscando sin fecha de vencimiento", ['rfc' => $rfc]);
        
        // Si no hay proveedor activo con fecha válida, buscar activo sin fecha de vencimiento
        $proveedorSinFecha = Proveedor::where('rfc', $rfc)
            ->where('estado_padron', 'Activo')
            ->whereNull('fecha_vencimiento_padron')
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($proveedorSinFecha) {
            \Log::info("Encontrado proveedor reutilizable sin fecha de vencimiento", [
                'rfc' => $rfc,
                'proveedor_id' => $proveedorSinFecha->id,
                'pv_numero' => $proveedorSinFecha->pv_numero,
                'estado_padron' => $proveedorSinFecha->estado_padron,
                'fecha_vencimiento' => $proveedorSinFecha->fecha_vencimiento_padron
            ]);
            return $proveedorSinFecha;
        }
        
        \Log::info("No se encontró proveedor activo sin fecha de vencimiento, buscando pendiente sin fecha de vencimiento", ['rfc' => $rfc]);
        
        // Si no hay proveedor activo sin fecha, buscar pendiente sin fecha de vencimiento
        $proveedorPendienteSinFecha = Proveedor::where('rfc', $rfc)
            ->where('estado_padron', 'Pendiente')
            ->whereNull('fecha_vencimiento_padron')
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($proveedorPendienteSinFecha) {
            \Log::info("Encontrado proveedor pendiente reutilizable sin fecha de vencimiento", [
                'rfc' => $rfc,
                'proveedor_id' => $proveedorPendienteSinFecha->id,
                'pv_numero' => $proveedorPendienteSinFecha->pv_numero,
                'estado_padron' => $proveedorPendienteSinFecha->estado_padron,
                'fecha_vencimiento' => $proveedorPendienteSinFecha->fecha_vencimiento_padron
            ]);
        } else {
            \Log::info("No se encontró proveedor reutilizable", ['rfc' => $rfc]);
        }
        
        return $proveedorPendienteSinFecha;
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

    /**
     * Verificar si un proveedor puede ser reutilizado (activo, pendiente o sin fecha de vencimiento)
     */
    public function proveedorPuedeReutilizarse(Proveedor $proveedor): bool
    {
        // Si está activo y tiene fecha de vencimiento válida
        if ($this->proveedorEstaActivo($proveedor)) {
            return true;
        }
        
        // Si está activo pero no tiene fecha de vencimiento (puede ser reutilizado)
        if ($proveedor->estado_padron === 'Activo' && !$proveedor->fecha_vencimiento_padron) {
            \Log::info("Proveedor puede reutilizarse (activo sin fecha de vencimiento)", [
                'proveedor_id' => $proveedor->id,
                'rfc' => $proveedor->rfc,
                'pv_numero' => $proveedor->pv_numero,
                'estado_padron' => $proveedor->estado_padron,
                'fecha_vencimiento' => $proveedor->fecha_vencimiento_padron
            ]);
            return true;
        }
        
        // Si está pendiente y no tiene fecha de vencimiento (puede ser reutilizado)
        if ($proveedor->estado_padron === 'Pendiente' && !$proveedor->fecha_vencimiento_padron) {
            \Log::info("Proveedor puede reutilizarse (pendiente sin fecha de vencimiento)", [
                'proveedor_id' => $proveedor->id,
                'rfc' => $proveedor->rfc,
                'pv_numero' => $proveedor->pv_numero,
                'estado_padron' => $proveedor->estado_padron,
                'fecha_vencimiento' => $proveedor->fecha_vencimiento_padron
            ]);
            return true;
        }
        
        return false;
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
        $proveedorReutilizable = $this->buscarProveedorReutilizable($rfc);
        $proveedorConTramitePendiente = $this->buscarProveedorConTramitePendiente($rfc, $tramiteIdExcluir);
        $tramitePendiente = $this->obtenerTramitePendiente($rfc, $tramiteIdExcluir);
        
        \Log::info("Determinando acción por tipo de trámite", [
            'rfc' => $rfc,
            'tipo_tramite' => $tipoTramite,
            'total_proveedores' => $proveedores->count(),
            'proveedor_activo_encontrado' => $proveedorActivo ? 'Sí' : 'No',
            'proveedor_reutilizable_encontrado' => $proveedorReutilizable ? 'Sí' : 'No',
            'proveedor_activo_id' => $proveedorActivo ? $proveedorActivo->id : null,
            'proveedor_reutilizable_id' => $proveedorReutilizable ? $proveedorReutilizable->id : null,
            'proveedor_activo_pv_numero' => $proveedorActivo ? $proveedorActivo->pv_numero : null,
            'proveedor_reutilizable_pv_numero' => $proveedorReutilizable ? $proveedorReutilizable->pv_numero : null,
            'proveedor_activo_fecha_vencimiento' => $proveedorActivo ? $proveedorActivo->fecha_vencimiento_padron : null,
            'proveedor_reutilizable_fecha_vencimiento' => $proveedorReutilizable ? $proveedorReutilizable->fecha_vencimiento_padron : null
        ]);
        
        // Convertir el tipo de trámite a minúsculas para la comparación
        $tipoTramiteLower = strtolower($tipoTramite);
        
        // Verificar si hay trámite pendiente (cualquier estado activo)
        if ($proveedorConTramitePendiente && $tramitePendiente) {
            return $this->crearRespuestaAccion('tramite_pendiente', 'Tiene un trámite en proceso', $proveedorConTramitePendiente, $proveedores->count());
        }
        
        switch ($tipoTramiteLower) {
            case 'inscripcion':
                // INSCRIPCIÓN: 
                // - Si no hay proveedor reutilizable: crear nuevo
                // - Si hay proveedor reutilizable: reutilizar (actualizar fechas)
                if (!$proveedorReutilizable) {
                    \Log::info("Inscripción: No hay proveedor reutilizable, crear nuevo", [
                        'rfc' => $rfc,
                        'total_proveedores' => $proveedores->count()
                    ]);
                    return $this->crearRespuestaAccion('crear_nuevo', 'No tiene proveedor registrado', null, $proveedores->count());
                }
                // Si hay proveedor reutilizable, reutilizarlo
                \Log::info("Inscripción: Reutilizando proveedor existente", [
                    'rfc' => $rfc,
                    'proveedor_id' => $proveedorReutilizable->id,
                    'pv_numero' => $proveedorReutilizable->pv_numero,
                    'fecha_vencimiento' => $proveedorReutilizable->fecha_vencimiento_padron
                ]);
                return $this->crearRespuestaAccion('reutilizar_vigente', 'Reutilizar proveedor vigente', $proveedorReutilizable, $proveedores->count());
                
            case 'renovacion':
                // RENOVACIÓN: 
                // - Solo si hay proveedor (vigente o no)
                // - Reutilizar el proveedor existente y actualizar fecha de vencimiento
                if (!$proveedorReutilizable) {
                    return $this->crearRespuestaAccion('no_renovar', 'No tiene proveedor para renovar', null, $proveedores->count());
                }
                // Siempre reutilizar el proveedor existente para renovación
                return $this->crearRespuestaAccion('renovar_existente', 'Renovar proveedor existente', $proveedorReutilizable, $proveedores->count());
                
            case 'actualizacion':
                // ACTUALIZACIÓN: 
                // - Solo si hay proveedor reutilizable
                // - Reutilizar sin cambiar fechas
                if (!$proveedorReutilizable) {
                    return $this->crearRespuestaAccion('no_actualizar', 'No tiene proveedor para actualizar', null, $proveedores->count());
                }
                // Si hay proveedor reutilizable, actualizarlo
                return $this->crearRespuestaAccion('actualizar_existente', 'Actualizar proveedor vigente', $proveedorReutilizable, $proveedores->count());
                
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

    /**
     * Gestionar proveedor según tipo de trámite y vigencia
     */
    public function gestionarProveedorPorTramite(string $rfc, string $tipoTramite, array $datosProveedor): array
    {
        \Log::info("Gestionando proveedor por trámite", [
            'rfc' => $rfc,
            'tipo_tramite' => $tipoTramite,
            'datos_proveedor' => $datosProveedor
        ]);

        $tipoTramiteLower = strtolower($tipoTramite);
        $proveedorReutilizable = $this->buscarProveedorReutilizable($rfc);
        $fechaActual = Carbon::now();

        switch ($tipoTramiteLower) {
            case 'inscripcion':
                return $this->gestionarInscripcion($rfc, $proveedorReutilizable, $datosProveedor, $fechaActual);
                
            case 'renovacion':
                return $this->gestionarRenovacion($rfc, $proveedorReutilizable, $datosProveedor, $fechaActual);
                
            case 'actualizacion':
                return $this->gestionarActualizacion($rfc, $proveedorReutilizable, $datosProveedor, $fechaActual);
                
            default:
                throw new \Exception("Tipo de trámite no válido: {$tipoTramite}");
        }
    }

    /**
     * Gestionar inscripción
     */
    private function gestionarInscripcion(string $rfc, ?Proveedor $proveedorReutilizable, array $datosProveedor, Carbon $fechaActual): array
    {
        // Si no hay proveedor reutilizable, crear nuevo con estado pendiente
        if (!$proveedorReutilizable) {
            $numeroProveedor = $this->generarNumeroProveedor($rfc);
            
            $nuevoProveedor = Proveedor::create([
                'rfc' => $rfc,
                'pv_numero' => $numeroProveedor,
                'tipo_persona' => $datosProveedor['tipo_persona'] ?? 'Física',
                'estado_padron' => 'Pendiente', // Estado pendiente hasta aprobación
                'fecha_registro' => null, // Se asignará cuando se apruebe
                'fecha_vencimiento_padron' => null, // Se asignará cuando se apruebe
                'usuario_id' => $datosProveedor['usuario_id'] ?? auth()->id(),
                'fecha_alta_padron' => null, // Se asignará cuando se apruebe
                'razon_social' => $datosProveedor['razon_social'] ?? null,
            ]);

            \Log::info("Nuevo proveedor creado para inscripción (pendiente)", [
                'rfc' => $rfc,
                'pv_numero' => $numeroProveedor,
                'estado_padron' => 'Pendiente',
                'fecha_registro' => null,
                'fecha_vencimiento' => null
            ]);

            return [
                'accion' => 'creado_pendiente',
                'proveedor' => $nuevoProveedor,
                'numero_proveedor' => $numeroProveedor,
                'fecha_registro' => null,
                'fecha_vencimiento' => null
            ];
        }

        // Si hay proveedor reutilizable, reutilizarlo pero mantener estado pendiente
        // Si el proveedor no tiene número PV, generarlo
        if (!$proveedorReutilizable->pv_numero) {
            $numeroProveedor = $this->generarNumeroProveedor($rfc);
            $proveedorReutilizable->update([
                'pv_numero' => $numeroProveedor,
                'estado_padron' => 'Pendiente', // Mantener pendiente hasta aprobación
                'fecha_registro' => null, // Se asignará cuando se apruebe
                'fecha_vencimiento_padron' => null, // Se asignará cuando se apruebe
                'fecha_alta_padron' => null // Se asignará cuando se apruebe
            ]);
            \Log::info("Número PV asignado a proveedor existente (pendiente)", [
                'rfc' => $rfc,
                'proveedor_id' => $proveedorReutilizable->id,
                'pv_numero' => $numeroProveedor,
                'estado_padron' => 'Pendiente'
            ]);
        } else {
            $numeroProveedor = $proveedorReutilizable->pv_numero;
            // Actualizar estado a pendiente si no lo está
            if ($proveedorReutilizable->estado_padron !== 'Pendiente') {
                $proveedorReutilizable->update([
                    'estado_padron' => 'Pendiente',
                    'fecha_registro' => null,
                    'fecha_vencimiento_padron' => null,
                    'fecha_alta_padron' => null
                ]);
            }
        }
        
        // Sincronizar datos con DatosGenerales después de asignar PV
        // Buscar el trámite actual que está siendo procesado
        $tramiteActual = Tramite::where('proveedor_id', $proveedorReutilizable->id)
            ->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria', 'Para_Correccion'])
            ->latest()
            ->first();
            
        if ($tramiteActual) {
            $datosGenerales = $tramiteActual->datosGenerales()->latest()->first();
            if ($datosGenerales) {
                $proveedorReutilizable->sincronizarDatosGenerales($datosGenerales);
                
                \Log::info("Datos sincronizados después de asignar PV en inscripción (pendiente)", [
                    'proveedor_id' => $proveedorReutilizable->id,
                    'tramite_actual_id' => $tramiteActual->id,
                    'pv_numero' => $numeroProveedor,
                    'razon_social' => $datosGenerales->razon_social,
                    'datos_generales_id' => $datosGenerales->id,
                    'estado_padron' => 'Pendiente'
                ]);
            } else {
                \Log::warning("No se encontraron DatosGenerales para el trámite actual en inscripción", [
                    'proveedor_id' => $proveedorReutilizable->id,
                    'tramite_actual_id' => $tramiteActual->id
                ]);
            }
        } else {
            \Log::warning("No se encontró trámite actual para sincronizar datos en inscripción", [
                'proveedor_id' => $proveedorReutilizable->id
            ]);
        }

        return [
            'accion' => 'reutilizado_pendiente',
            'proveedor' => $proveedorReutilizable->fresh(),
            'numero_proveedor' => $numeroProveedor,
            'fecha_registro' => null,
            'fecha_vencimiento' => null
        ];
    }

    /**
     * Gestionar renovación
     */
    private function gestionarRenovacion(string $rfc, ?Proveedor $proveedorReutilizable, array $datosProveedor, Carbon $fechaActual): array
    {
        if (!$proveedorReutilizable) {
            throw new \Exception("No existe proveedor para renovar con RFC: {$rfc}");
        }

        // Reutilizar el proveedor existente pero mantener estado pendiente
        // Si el proveedor no tiene número PV, generarlo
        if (!$proveedorReutilizable->pv_numero) {
            $numeroProveedor = $this->generarNumeroProveedor($rfc);
            $proveedorReutilizable->update([
                'pv_numero' => $numeroProveedor,
                'estado_padron' => 'Pendiente', // Mantener pendiente hasta aprobación
                'fecha_registro' => null, // Se asignará cuando se apruebe
                'fecha_vencimiento_padron' => null, // Se asignará cuando se apruebe
                'fecha_alta_padron' => null // Se asignará cuando se apruebe
            ]);
            \Log::info("Número PV asignado a proveedor existente para renovación (pendiente)", [
                'rfc' => $rfc,
                'proveedor_id' => $proveedorReutilizable->id,
                'pv_numero' => $numeroProveedor,
                'estado_padron' => 'Pendiente'
            ]);
        } else {
            $numeroProveedor = $proveedorReutilizable->pv_numero;
            // Actualizar estado a pendiente si no lo está
            if ($proveedorReutilizable->estado_padron !== 'Pendiente') {
                $proveedorReutilizable->update([
                    'estado_padron' => 'Pendiente',
                    'fecha_registro' => null,
                    'fecha_vencimiento_padron' => null,
                    'fecha_alta_padron' => null
                ]);
            }
        }

        // Sincronizar datos con DatosGenerales después de asignar PV
        // Buscar el trámite actual que está siendo procesado
        $tramiteActual = Tramite::where('proveedor_id', $proveedorReutilizable->id)
            ->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria', 'Para_Correccion'])
            ->latest()
            ->first();
            
        if ($tramiteActual) {
            $datosGenerales = $tramiteActual->datosGenerales()->latest()->first();
            if ($datosGenerales) {
                $proveedorReutilizable->sincronizarDatosGenerales($datosGenerales);
                
                \Log::info("Datos sincronizados después de asignar PV en renovación (pendiente)", [
                    'proveedor_id' => $proveedorReutilizable->id,
                    'tramite_actual_id' => $tramiteActual->id,
                    'pv_numero' => $numeroProveedor,
                    'razon_social' => $datosGenerales->razon_social,
                    'datos_generales_id' => $datosGenerales->id,
                    'estado_padron' => 'Pendiente'
                ]);
            } else {
                \Log::warning("No se encontraron DatosGenerales para el trámite actual en renovación", [
                    'proveedor_id' => $proveedorReutilizable->id,
                    'tramite_actual_id' => $tramiteActual->id
                ]);
            }
        } else {
            \Log::warning("No se encontró trámite actual para sincronizar datos en renovación", [
                'proveedor_id' => $proveedorReutilizable->id
            ]);
        }

        \Log::info("Proveedor reutilizable actualizado para renovación (pendiente)", [
            'rfc' => $rfc,
            'proveedor_id' => $proveedorReutilizable->id,
            'pv_numero' => $numeroProveedor,
            'estado_padron' => 'Pendiente',
            'fecha_registro' => null,
            'fecha_vencimiento' => null
        ]);

        return [
            'accion' => 'renovado_pendiente',
            'proveedor' => $proveedorReutilizable->fresh(),
            'numero_proveedor' => $numeroProveedor,
            'fecha_registro' => null,
            'fecha_vencimiento' => null
        ];
    }

    /**
     * Gestionar actualización
     */
    private function gestionarActualizacion(string $rfc, ?Proveedor $proveedorReutilizable, array $datosProveedor, Carbon $fechaActual): array
    {
        if (!$proveedorReutilizable) {
            throw new \Exception("No existe proveedor para actualizar con RFC: {$rfc}");
        }

        // Reutilizar el proveedor existente pero mantener estado pendiente
        // Si el proveedor no tiene número PV, generarlo
        if (!$proveedorReutilizable->pv_numero) {
            $numeroProveedor = $this->generarNumeroProveedor($rfc);
            $proveedorReutilizable->update([
                'pv_numero' => $numeroProveedor,
                'estado_padron' => 'Pendiente', // Mantener pendiente hasta aprobación
                'fecha_registro' => null, // Se asignará cuando se apruebe
                'fecha_vencimiento_padron' => null, // Se asignará cuando se apruebe
                'fecha_alta_padron' => null // Se asignará cuando se apruebe
            ]);
            \Log::info("Número PV asignado a proveedor existente para actualización (pendiente)", [
                'rfc' => $rfc,
                'proveedor_id' => $proveedorReutilizable->id,
                'pv_numero' => $numeroProveedor,
                'estado_padron' => 'Pendiente'
            ]);
        } else {
            $numeroProveedor = $proveedorReutilizable->pv_numero;
            // Actualizar estado a pendiente si no lo está
            if ($proveedorReutilizable->estado_padron !== 'Pendiente') {
                $proveedorReutilizable->update([
                    'estado_padron' => 'Pendiente',
                    'fecha_registro' => null,
                    'fecha_vencimiento_padron' => null,
                    'fecha_alta_padron' => null
                ]);
            }
        }

        // Sincronizar datos con DatosGenerales después de asignar PV
        // Buscar el trámite actual que está siendo procesado
        $tramiteActual = Tramite::where('proveedor_id', $proveedorReutilizable->id)
            ->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria', 'Para_Correccion'])
            ->latest()
            ->first();
            
        if ($tramiteActual) {
            $datosGenerales = $tramiteActual->datosGenerales()->latest()->first();
            if ($datosGenerales) {
                $proveedorReutilizable->sincronizarDatosGenerales($datosGenerales);
                
                \Log::info("Datos sincronizados después de asignar PV en actualización (pendiente)", [
                    'proveedor_id' => $proveedorReutilizable->id,
                    'tramite_actual_id' => $tramiteActual->id,
                    'pv_numero' => $numeroProveedor,
                    'razon_social' => $datosGenerales->razon_social,
                    'datos_generales_id' => $datosGenerales->id,
                    'estado_padron' => 'Pendiente'
                ]);
            } else {
                \Log::warning("No se encontraron DatosGenerales para el trámite actual en actualización", [
                    'proveedor_id' => $proveedorReutilizable->id,
                    'tramite_actual_id' => $tramiteActual->id
                ]);
            }
        } else {
            \Log::warning("No se encontró trámite actual para sincronizar datos en actualización", [
                'proveedor_id' => $proveedorReutilizable->id
            ]);
        }

        \Log::info("Proveedor reutilizable actualizado para actualización (pendiente)", [
            'rfc' => $rfc,
            'proveedor_id' => $proveedorReutilizable->id,
            'pv_numero' => $numeroProveedor,
            'estado_padron' => 'Pendiente',
            'fecha_registro' => null,
            'fecha_vencimiento' => null
        ]);

        return [
            'accion' => 'actualizado_pendiente',
            'proveedor' => $proveedorReutilizable->fresh(),
            'numero_proveedor' => $numeroProveedor,
            'fecha_registro' => null,
            'fecha_vencimiento' => null
        ];
    }

    /**
     * Sincronizar datos del proveedor con DatosGenerales del trámite específico
     */
    public function sincronizarDatosProveedorConTramite(Proveedor $proveedor, int $tramiteId): bool
    {
        try {
            $tramite = Tramite::find($tramiteId);
            if (!$tramite) {
                \Log::warning("No se encontró el trámite para sincronizar datos", [
                    'proveedor_id' => $proveedor->id,
                    'tramite_id' => $tramiteId
                ]);
                return false;
            }

            $datosGenerales = $tramite->datosGenerales()->latest()->first();
            if (!$datosGenerales) {
                \Log::warning("No se encontraron DatosGenerales para el trámite", [
                    'proveedor_id' => $proveedor->id,
                    'tramite_id' => $tramiteId
                ]);
                return false;
            }

            $proveedor->sincronizarDatosGenerales($datosGenerales);
            
            \Log::info("Datos sincronizados exitosamente con trámite específico", [
                'proveedor_id' => $proveedor->id,
                'tramite_id' => $tramiteId,
                'razon_social' => $datosGenerales->razon_social,
                'datos_generales_id' => $datosGenerales->id
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error("Error al sincronizar datos del proveedor con trámite", [
                'proveedor_id' => $proveedor->id,
                'tramite_id' => $tramiteId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Obtener información de gestión de proveedor para mostrar en la vista
     */
    public function obtenerInfoGestionProveedor(string $rfc, string $tipoTramite): array
    {
        $accion = $this->determinarAccionPorTipoTramite($rfc, $tipoTramite);
        $proveedorReutilizable = $this->buscarProveedorReutilizable($rfc);
        
        $info = [
            'accion' => $accion['accion'],
            'motivo' => $accion['motivo'],
            'tipo_tramite' => $tipoTramite,
            'rfc' => $rfc,
            'proveedor_existente' => $proveedorReutilizable ? [
                'id' => $proveedorReutilizable->id,
                'pv_numero' => $proveedorReutilizable->pv_numero,
                'estado_padron' => $proveedorReutilizable->estado_padron,
                'fecha_registro' => $proveedorReutilizable->fecha_registro,
                'fecha_vencimiento' => $proveedorReutilizable->fecha_vencimiento_padron,
                'esta_vigente' => $this->proveedorPuedeReutilizarse($proveedorReutilizable)
            ] : null
        ];

        // Determinar mensaje informativo para el usuario
        switch ($accion['accion']) {
            case 'crear_nuevo':
                $info['mensaje_usuario'] = 'Se creará un nuevo proveedor con número PV único.';
                break;
            case 'reutilizar_vigente':
                $info['mensaje_usuario'] = 'Se reutilizará el proveedor vigente existente y se actualizarán las fechas.';
                break;
            case 'renovar_existente':
                $info['mensaje_usuario'] = 'Se renovará el proveedor existente actualizando la fecha de vencimiento.';
                break;
            case 'actualizar_existente':
                $info['mensaje_usuario'] = 'Se actualizará la información del proveedor vigente sin cambiar fechas.';
                break;
            default:
                $info['mensaje_usuario'] = 'Información del proveedor disponible.';
        }

        return $info;
    }

    /**
     * Activar proveedor cuando el trámite sea aprobado
     */
    public function activarProveedor(Proveedor $proveedor, string $tipoTramite): bool
    {
        try {
            $fechaActual = Carbon::now();
            $fechaVencimiento = $fechaActual->copy()->addYears(3); // 3 años de vigencia
            
            $datosActualizacion = [
                'estado_padron' => 'Activo',
                'fecha_registro' => $fechaActual,
                'fecha_vencimiento_padron' => $fechaVencimiento,
                'fecha_alta_padron' => $fechaActual,
            ];
            
            // Para renovación, mantener la fecha de registro original si existe
            if ($tipoTramite === 'renovacion' && $proveedor->fecha_registro) {
                $datosActualizacion['fecha_registro'] = $proveedor->fecha_registro;
            }
            
            $proveedor->update($datosActualizacion);
            
            \Log::info("Proveedor activado exitosamente", [
                'proveedor_id' => $proveedor->id,
                'rfc' => $proveedor->rfc,
                'pv_numero' => $proveedor->pv_numero,
                'tipo_tramite' => $tipoTramite,
                'estado_padron' => 'Activo',
                'fecha_registro' => $fechaActual,
                'fecha_vencimiento' => $fechaVencimiento
            ]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Error al activar proveedor", [
                'proveedor_id' => $proveedor->id,
                'rfc' => $proveedor->rfc,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
} 