<?php

namespace App\Services;

use App\Models\User;
use App\Models\Proveedor;
use App\Models\Tramite;
use App\Models\CatalogoArchivo;
use Carbon\Carbon;

// Servicio para gestión de RFC y proveedores
class RfcProveedorService
{
    public function esPersonaMoral(?string $tipoPersona, ?string $rfc): bool
    {
        if ($tipoPersona === 'Moral') {
            return true;
        }
        if ($rfc) {
            return $this->determinarTipoPersona($rfc) === 'Moral';
        }
        return false;
    }

    public function obtenerTipoPersonaDesdeInputs(?string $tipoPersona, ?string $rfc): string
    {
        return $this->esPersonaMoral($tipoPersona, $rfc) ? 'Moral' : 'Física';
    }

    // Determinar tipo de persona por RFC
    public function determinarTipoPersona(string $rfc): string
    {
        $rfcLimpio = trim(strtoupper($rfc));
        
        if (!preg_match('/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/', $rfcLimpio)) {
            return 'Física';
        }
        
        return strlen($rfcLimpio) === 13 ? 'Física' : 'Moral';
    }

    // Obtener RFC del usuario autenticado
    public function obtenerRfcUsuario(): ?string
    {
        $user = auth()->user();
        return $user ? $user->rfc : null;
    }

    // Buscar proveedores por RFC
    public function buscarProveedoresPorRfc(string $rfc): \Illuminate\Database\Eloquent\Collection
    {
        return Proveedor::where('rfc', $rfc)->get();
    }

    // Buscar proveedor activo y vigente
    public function buscarProveedorActivo(string $rfc): ?Proveedor
    {
        return Proveedor::where('rfc', $rfc)
            ->where('estado_padron', 'Activo')
            ->where('fecha_vencimiento_padron', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->first();
    }

    // Buscar proveedor que puede ser reutilizado
    public function buscarProveedorReutilizable(string $rfc): ?Proveedor
    {
        // Buscar proveedor activo con fecha válida
        $proveedor = $this->buscarProveedorActivo($rfc);
        if ($proveedor) {
            return $proveedor;
        }
        
        // Buscar activo sin fecha de vencimiento
        $proveedorSinFecha = Proveedor::where('rfc', $rfc)
            ->where('estado_padron', 'Activo')
            ->whereNull('fecha_vencimiento_padron')
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($proveedorSinFecha) {
            return $proveedorSinFecha;
        }
        
        // Buscar pendiente sin fecha de vencimiento
        return Proveedor::where('rfc', $rfc)
            ->where('estado_padron', 'Pendiente')
            ->whereNull('fecha_vencimiento_padron')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    // Verificar si proveedor está activo
    public function proveedorEstaActivo(Proveedor $proveedor): bool
    {
        return $proveedor->estado_padron === 'Activo' && 
               $proveedor->fecha_vencimiento_padron && 
               $proveedor->fecha_vencimiento_padron > Carbon::now();
    }

    // Verificar si proveedor puede ser reutilizado
    public function proveedorPuedeReutilizarse(Proveedor $proveedor): bool
    {
        if ($this->proveedorEstaActivo($proveedor)) {
            return true;
        }
        
        if ($proveedor->estado_padron === 'Activo' && !$proveedor->fecha_vencimiento_padron) {
            return true;
        }
        
        if ($proveedor->estado_padron === 'Pendiente' && !$proveedor->fecha_vencimiento_padron) {
            return true;
        }
        
        return false;
    }

    // Determinar acción básica
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

    // Determinar acción por tipo de trámite
    public function determinarAccionPorTipoTramite(string $rfc, string $tipoTramite, ?int $tramiteIdExcluir = null): array
    {
        $proveedores = $this->buscarProveedoresPorRfc($rfc);
        $proveedorReutilizable = $this->buscarProveedorReutilizable($rfc);
        $proveedorConTramitePendiente = $this->buscarProveedorConTramitePendiente($rfc, $tramiteIdExcluir);
        $tramitePendiente = $this->obtenerTramitePendiente($rfc, $tramiteIdExcluir);
        
        $tipoTramiteLower = strtolower($tipoTramite);
        
        if ($proveedorConTramitePendiente && $tramitePendiente) {
            return $this->crearRespuestaAccion('tramite_pendiente', 'Tiene un trámite en proceso', $proveedorConTramitePendiente, $proveedores->count());
        }
        
        switch ($tipoTramiteLower) {
            case 'inscripcion':
                if (!$proveedorReutilizable) {
                    return $this->crearRespuestaAccion('crear_nuevo', 'No tiene proveedor registrado', null, $proveedores->count());
                }
                return $this->crearRespuestaAccion('reutilizar_vigente', 'Reutilizar proveedor vigente', $proveedorReutilizable, $proveedores->count());
                
            case 'renovacion':
                if (!$proveedorReutilizable) {
                    return $this->crearRespuestaAccion('no_renovar', 'No tiene proveedor para renovar', null, $proveedores->count());
                }
                return $this->crearRespuestaAccion('renovar_existente', 'Renovar proveedor existente', $proveedorReutilizable, $proveedores->count());
                
            case 'actualizacion':
                if (!$proveedorReutilizable) {
                    return $this->crearRespuestaAccion('no_actualizar', 'No tiene proveedor para actualizar', null, $proveedores->count());
                }
                return $this->crearRespuestaAccion('actualizar_existente', 'Actualizar proveedor vigente', $proveedorReutilizable, $proveedores->count());
                
            default:
                return $this->crearRespuestaAccion('error', 'Tipo de trámite no válido', null, $proveedores->count());
        }
    }

    // Buscar proveedor con trámite pendiente
    public function buscarProveedorConTramitePendiente(string $rfc, ?int $tramiteIdExcluir = null): ?Proveedor
    {
        return Proveedor::where('rfc', $rfc)
            ->whereHas('tramites', function($query) use ($tramiteIdExcluir) {
                $query->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria', 'Para_Correccion']);
                
                if ($tramiteIdExcluir) {
                    $query->where('id', '!=', $tramiteIdExcluir);
                }
            })
            ->first();
    }

    // Verificar si tiene trámite pendiente
    public function tieneTramitePendiente(string $rfc, ?int $tramiteIdExcluir = null): bool
    {
        return $this->buscarProveedorConTramitePendiente($rfc, $tramiteIdExcluir) !== null;
    }

    // Crear respuesta de acción
    private function crearRespuestaAccion(string $accion, string $motivo, ?Proveedor $proveedorActivo, int $totalProveedores): array
    {
        return [
            'accion' => $accion,
            'motivo' => $motivo,
            'proveedor_activo' => $proveedorActivo,
            'proveedores_existentes' => $totalProveedores
        ];
    }

    // Generar número PV con formato PV + dígitos
    public function generarNumeroPV(): string
    {
        try {
            $ultimoPV = Proveedor::whereNotNull('pv_numero')
                ->where('pv_numero', 'LIKE', 'PV%')
                ->orderByRaw('CAST(SUBSTRING(pv_numero, 3) AS UNSIGNED) DESC')
                ->first();
            
            if (!$ultimoPV || !$ultimoPV->pv_numero) {
                return 'PV901323';
            }
            
            $ultimoComponenteNumerico = substr($ultimoPV->pv_numero, 2);
            $siguienteNumero = $this->obtenerSiguienteNumeroProveedor();
            $nuevoPV = 'PV' . $ultimoComponenteNumerico . $siguienteNumero;
            
            while (Proveedor::where('pv_numero', $nuevoPV)->exists()) {
                $siguienteNumero = str_pad((int)$siguienteNumero + 1, 3, '0', STR_PAD_LEFT);
                $nuevoPV = 'PV' . $ultimoComponenteNumerico . $siguienteNumero;
            }
            
            if (!preg_match('/^PV\d+$/', $nuevoPV)) {
                throw new \Exception("El PV generado no cumple el formato requerido: {$nuevoPV}");
            }
            
            return $nuevoPV;
        } catch (\Exception $e) {
            \Log::error("Error generando número PV: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Obtener siguiente número secuencial
    private function obtenerSiguienteNumeroProveedor(): string
    {
        $ultimoProveedor = Proveedor::whereNotNull('pv_numero')
            ->where('pv_numero', 'LIKE', 'PV%')
            ->get()
            ->map(function($p) {
                $pv = $p->pv_numero;
                if (strlen($pv) >= 6) {
                    return (int)substr($pv, -3);
                }
                return 0;
            })
            ->max();
            
        $siguienteNumero = $ultimoProveedor ? $ultimoProveedor + 1 : 1;
        
        return str_pad($siguienteNumero, 3, '0', STR_PAD_LEFT);
    }

    // Generar número secuencial simple
    public function generarNumeroProveedor(): string
    {
        $ultimoNumero = Proveedor::whereNotNull('pv_numero')
            ->where('pv_numero', 'NOT LIKE', 'PV%')
            ->max('pv_numero');
            
        $siguienteNumero = $ultimoNumero ? (int)$ultimoNumero + 1 : 1;
        return str_pad($siguienteNumero, 3, '0', STR_PAD_LEFT);
    }

    // Obtener archivos por tipo de persona
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

    // Obtener archivos por tipo directo
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

    // Debug información de proveedores
    public function debugProveedoresRfc(string $rfc): array
    {
        $proveedores = $this->buscarProveedoresPorRfc($rfc);
        $proveedorActivo = $this->buscarProveedorActivo($rfc);
        
        $debugInfo = [
            'rfc' => $rfc,
            'total_proveedores' => $proveedores->count(),
            'proveedor_activo_encontrado' => $proveedorActivo ? 'Sí' : 'No',
            'proveedores_detalle' => []
        ];
        
        foreach ($proveedores as $proveedor) {
            $debugInfo['proveedores_detalle'][] = [
                'id' => $proveedor->id,
                'pv_numero' => $proveedor->pv_numero,
                'estado_padron' => $proveedor->estado_padron,
                'fecha_vencimiento' => $proveedor->fecha_vencimiento_padron,
                'esta_activo' => $this->proveedorEstaActivo($proveedor),
                'created_at' => $proveedor->created_at
            ];
        }
        
        return $debugInfo;
    }

    // Obtener trámite pendiente
    public function obtenerTramitePendiente(string $rfc, ?int $tramiteIdExcluir = null): ?\App\Models\Tramite
    {
        $query = Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria', 'Para_Correccion']);
        
        if ($tramiteIdExcluir) {
            $query->where('id', '!=', $tramiteIdExcluir);
        }
        
        return $query->orderBy('created_at', 'desc')->first();
    }

    // Gestionar proveedor según tipo de trámite
    public function gestionarProveedorPorTramite(string $rfc, string $tipoTramite, array $datosProveedor): array
    {
        $tipoTramiteLower = strtolower($tipoTramite);
        $proveedorReutilizable = $this->buscarProveedorReutilizable($rfc);

        // Crear nuevo proveedor si no existe
        if (!$proveedorReutilizable) {
            if ($tipoTramiteLower !== 'inscripcion') {
                throw new \Exception("No existe proveedor para {$tipoTramite} con RFC: {$rfc}");
            }
            
            $nuevoProveedor = Proveedor::create([
                'rfc' => $rfc,
                'pv_numero' => null,
                'tipo_persona' => $datosProveedor['tipo_persona'] ?? 'Física',
                'estado_padron' => 'Pendiente',
                'usuario_id' => $datosProveedor['usuario_id'] ?? auth()->id(),
                'razon_social' => $datosProveedor['razon_social'] ?? null,
            ]);

            return ['accion' => 'creado_pendiente', 'proveedor' => $nuevoProveedor, 'numero_proveedor' => null];
        }

        // Reutilizar proveedor existente
        $actualizacion = ['estado_padron' => 'Pendiente'];
        

        if (!$proveedorReutilizable->pv_numero && $tipoTramiteLower !== 'inscripcion') {
            $actualizacion['pv_numero'] = $this->generarNumeroProveedor();
        }
        
        // Para inscripción, remover PV existente
        if ($tipoTramiteLower === 'inscripcion') {
            $actualizacion['pv_numero'] = null;
        }

        $proveedorReutilizable->update($actualizacion);
        $this->sincronizarDatosConTramiteActual($proveedorReutilizable);

        return [
            'accion' => $tipoTramiteLower . '_pendiente',
            'proveedor' => $proveedorReutilizable->fresh(),
            'numero_proveedor' => $proveedorReutilizable->fresh()->pv_numero
        ];
    }

    // Sincronizar datos con trámite actual
    private function sincronizarDatosConTramiteActual(Proveedor $proveedor): void
    {
        $tramiteActual = Tramite::where('proveedor_id', $proveedor->id)
            ->whereIn('status', ['Pendiente', 'Revision_Digital', 'Revision_Presencial', 'Revision_Domiciliaria', 'Para_Correccion'])
            ->latest()
            ->first();
            
        if ($tramiteActual) {
            $datosGenerales = $tramiteActual->datosGenerales()->latest()->first();
            if ($datosGenerales) {
                $proveedor->sincronizarDatosGenerales($datosGenerales);
            }
        }
    }

    // Sincronizar datos con trámite específico
    public function sincronizarDatosProveedorConTramite(Proveedor $proveedor, int $tramiteId): bool
    {
        try {
            $tramite = Tramite::find($tramiteId);
            if (!$tramite) {
                return false;
            }

            $datosGenerales = $tramite->datosGenerales()->latest()->first();
            if (!$datosGenerales) {
                return false;
            }

            $proveedor->sincronizarDatosGenerales($datosGenerales);
            return true;
        } catch (\Exception $e) {
            \Log::error("Error al sincronizar datos del proveedor: " . $e->getMessage());
            return false;
        }
    }

    // Obtener información de gestión para vista
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

    // Activar proveedor cuando se aprueba trámite
    public function activarProveedor(Proveedor $proveedor, string $tipoTramite): bool
    {
        try {
            $fechaActual = Carbon::now();
            $tipoTramiteLower = strtolower($tipoTramite);
            
            $actualizacion = [
                'estado_padron' => 'Activo',
                'fecha_registro' => $proveedor->fecha_registro ?: $fechaActual
            ];
            
            // Asignar PV solo para inscripción
            if ($tipoTramiteLower === 'inscripcion' && !$proveedor->pv_numero) {
                $actualizacion['pv_numero'] = $this->generarNumeroPV();
            }
            
            // Calcular vigencia según tipo de trámite
            if ($tipoTramiteLower === 'inscripcion') {
                // Inscripción: 1 año desde la fecha actual
                $actualizacion['fecha_vencimiento_padron'] = $this->calcularFechaVencimiento($fechaActual, 1);
            } elseif ($tipoTramiteLower === 'renovacion') {
                // Renovación: 1 año desde la fecha de vencimiento actual del proveedor
                $fechaVencimientoActual = $proveedor->fecha_vencimiento_padron ? 
                    Carbon::parse($proveedor->fecha_vencimiento_padron) : 
                    $fechaActual;
                $actualizacion['fecha_vencimiento_padron'] = $this->calcularFechaVencimiento($fechaVencimientoActual, 1);
            } else {
                // Actualización: mantener la fecha de vencimiento existente o calcular nueva si no existe
                $actualizacion['fecha_vencimiento_padron'] = $proveedor->fecha_vencimiento_padron ?: $this->calcularFechaVencimiento($fechaActual, 1);
            }
            
            // Fecha de alta solo la primera vez
            if (!$proveedor->fecha_alta_padron) {
                $actualizacion['fecha_alta_padron'] = $fechaActual;
            }
            
            $proveedor->update($actualizacion);
            return true;
        } catch (\Exception $e) {
            \Log::error("Error al activar proveedor: " . $e->getMessage());
            return false;
        }
    }
    
    // Calcular fecha de vencimiento respetando años bisiestos
    private function calcularFechaVencimiento(Carbon $fechaInicio, int $años): Carbon
    {
        $fechaVencimiento = $fechaInicio->copy()->addYears($años);
        
        if ($fechaInicio->month === 2 && $fechaInicio->day === 29) {
            if (!$fechaVencimiento->isLeapYear()) {
                $fechaVencimiento = $fechaVencimiento->setDay(28);
            }
        }
        
        return $fechaVencimiento;
    }
}