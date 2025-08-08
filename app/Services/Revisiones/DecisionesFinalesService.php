<?php

namespace App\Services\Revisiones;

use App\Models\Tramite;
use App\Models\SeccionRevision;
use App\Models\Proveedor;
use App\Services\CitasService;
use App\Services\RevisionService;
use App\Services\RfcProveedorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DecisionesFinalesService
{
    public function __construct()
    {
        // Constructor vacío para evitar problemas de inyección
    }

    /** Aprobar y agendar cita */
    public function aprobarYAgendarCita(int $tramiteId, ?string $comentarioGeneral = null): array
    {
        return DB::transaction(function() use ($tramiteId, $comentarioGeneral) {
            $tramite = Tramite::findOrFail($tramiteId);
            
            // Validar que todas las secciones estén aprobadas
            $estadoGeneral = app(RevisionService::class)->obtenerEstadoGeneral($tramiteId);
            
            if (!$estadoGeneral['todas_aprobadas']) {
                return [
                    'success' => false,
                    'message' => 'No se puede aprobar el trámite. Todas las secciones deben estar aprobadas.'
                ];
            }

            // Agendar cita automáticamente
            $resultado = app(CitasService::class)->agendarCitaRevisionDigital($tramiteId);

            if (!$resultado['success']) {
                return [
                    'success' => false,
                    'message' => $resultado['message']
                ];
            }

            // Actualizar estado del trámite
            $tramite->update([
                'status' => 'Revision_Presencial',
                'observaciones' => $comentarioGeneral
            ]);

            // Guardar comentario general
            $this->guardarComentarioGeneral($tramiteId, $comentarioGeneral, 'Aprobado');

            return [
                'success' => true,
                'message' => 'Trámite aprobado y cita agendada exitosamente',
                'fecha_cita' => $resultado['fecha_formateada'],
                'cita_id' => $resultado['cita']->id
            ];
        });
    }

    /** Rechazar para corrección */
    public function rechazarParaCorreccion(int $tramiteId, ?string $comentarioGeneral = null): array
    {
        return DB::transaction(function() use ($tramiteId, $comentarioGeneral) {
            $tramite = Tramite::findOrFail($tramiteId);
            
            // Actualizar estado del trámite
            $tramite->update([
                'status' => 'Para_Correccion',
                'observaciones' => $comentarioGeneral,
                'correcciones_count' => $tramite->correcciones_count + 1
            ]);

            // Guardar comentario general
            $this->guardarComentarioGeneral($tramiteId, $comentarioGeneral, 'Rechazado');

            return [
                'success' => true,
                'message' => 'Trámite enviado para corrección exitosamente'
            ];
        });
    }

    /** Rechazar completamente */
    public function rechazarCompleto(int $tramiteId, ?string $comentarioGeneral = null): array
    {
        return DB::transaction(function() use ($tramiteId, $comentarioGeneral) {
            $tramite = Tramite::findOrFail($tramiteId);
            
            // Actualizar estado del trámite
            $tramite->update([
                'status' => 'Rechazado',
                'observaciones' => $comentarioGeneral,
                'fecha_finalizacion' => now()
            ]);

            // Guardar comentario general
            $this->guardarComentarioGeneral($tramiteId, $comentarioGeneral, 'Rechazado');

            return [
                'success' => true,
                'message' => 'Trámite rechazado exitosamente'
            ];
        });
    }

    /** Aprobar y asignar proveedor */
    public function aprobarYAsignarProveedor(int $tramiteId, ?string $comentarioGeneral = null): array
    {
        return DB::transaction(function() use ($tramiteId, $comentarioGeneral) {
            try {
                Log::info("Iniciando proceso de aprobación para trámite {$tramiteId}");
                
                $tramite = Tramite::findOrFail($tramiteId);
                Log::info("Trámite encontrado", ['tramite_id' => $tramiteId, 'status' => $tramite->status]);
                
                $rfcProveedorService = app(RfcProveedorService::class);
                
                // Obtener RFC del trámite
                if (!$tramite->proveedor) {
                    throw new \Exception("El trámite {$tramiteId} no tiene un proveedor asociado");
                }
                
                $rfc = $tramite->proveedor->rfc;
                $tipoTramite = $tramite->tipo_tramite;
                
                Log::info("Datos del trámite obtenidos", [
                    'rfc' => $rfc,
                    'tipo_tramite' => $tipoTramite,
                    'proveedor_id' => $tramite->proveedor->id
                ]);
                
                // Determinar la acción según el tipo de trámite (excluyendo el trámite actual)
                $accion = $rfcProveedorService->determinarAccionPorTipoTramite($rfc, $tipoTramite, $tramiteId);
                
                Log::info("Acción determinada", [
                    'accion' => $accion['accion'],
                    'motivo' => $accion['motivo'],
                    'proveedores_existentes' => $accion['proveedores_existentes']
                ]);
            
            $proveedorAsignado = null;
            $numeroProveedor = null;
            
            switch ($accion['accion']) {
                case 'crear_nuevo':
                    // Crear nuevo proveedor
                    $numeroProveedor = $rfcProveedorService->generarNumeroProveedor($rfc);
                    $proveedorAsignado = $this->crearNuevoProveedor($tramite, $numeroProveedor);
                    break;
                    
                case 'actualizar_existente':
                    // Mantener el proveedor existente (actualización)
                    $proveedorAsignado = $accion['proveedor_activo'];
                    $numeroProveedor = $proveedorAsignado->pv_numero;
                    break;
                    
                case 'renovar_vencido':
                    // Crear nuevo proveedor para renovación
                    $numeroProveedor = $rfcProveedorService->generarNumeroProveedor($rfc);
                    $proveedorAsignado = $this->crearNuevoProveedor($tramite, $numeroProveedor);
                    break;
                    
                default:
                    return [
                        'success' => false,
                        'message' => 'No se puede procesar este trámite: ' . $accion['motivo']
                    ];
            }
            
            // Actualizar el trámite con el proveedor asignado
            $tramite->update([
                'proveedor_id' => $proveedorAsignado->id,
                'status' => 'Aprobado',
                'observaciones' => $comentarioGeneral,
                'fecha_finalizacion' => now()
            ]);

            // Guardar comentario general
            $this->guardarComentarioGeneral($tramiteId, $comentarioGeneral, 'Aprobado');

            return [
                'success' => true,
                'message' => "Trámite aprobado exitosamente. Proveedor asignado: {$numeroProveedor}",
                'numero_proveedor' => $numeroProveedor,
                'proveedor_id' => $proveedorAsignado->id,
                'accion_realizada' => $accion['accion']
            ];
        });
    }

    /** Crear nuevo proveedor */
    private function crearNuevoProveedor(Tramite $tramite, string $numeroProveedor): Proveedor
    {
        // Obtener datos del trámite de forma segura
        $datosGenerales = $tramite->datosGenerales->first();
        $contacto = $tramite->contacto->first();
        $domicilio = $tramite->domicilio->first();
        
        // Obtener datos del proveedor actual del trámite
        $proveedorActual = $tramite->proveedor;
        
        // Validar que el proveedor actual existe
        if (!$proveedorActual) {
            throw new \Exception('No se encontró el proveedor asociado al trámite');
        }
        
        return Proveedor::create([
            'rfc' => $proveedorActual->rfc,
            'razon_social' => $datosGenerales ? ($datosGenerales->razon_social ?? null) : ($proveedorActual->razon_social ?? 'Sin razón social'),
            'curp' => $datosGenerales ? ($datosGenerales->curp ?? null) : ($proveedorActual->curp ?? null),
            'nombre_contacto' => $contacto ? ($contacto->nombre_contacto ?? $contacto->nombre ?? null) : ($proveedorActual->nombre_contacto ?? null),
            'email' => $contacto ? ($contacto->email ?? null) : ($proveedorActual->email ?? null),
            'telefono' => $contacto ? ($contacto->telefono ?? null) : ($proveedorActual->telefono ?? null),
            'calle' => $domicilio ? ($domicilio->calle ?? null) : ($proveedorActual->calle ?? null),
            'numero_exterior' => $domicilio ? ($domicilio->numero_exterior ?? null) : ($proveedorActual->numero_exterior ?? null),
            'numero_interior' => $domicilio ? ($domicilio->numero_interior ?? null) : ($proveedorActual->numero_interior ?? null),
            'colonia' => $domicilio ? ($domicilio->colonia ?? null) : ($proveedorActual->colonia ?? null),
            'codigo_postal' => $domicilio ? ($domicilio->codigo_postal ?? null) : ($proveedorActual->codigo_postal ?? null),
            'municipio' => $domicilio ? ($domicilio->municipio ?? null) : ($proveedorActual->municipio ?? null),
            'estado' => $domicilio ? ($domicilio->estado ?? null) : ($proveedorActual->estado ?? null),
            'pv_numero' => $numeroProveedor,
            'estado_padron' => 'activo',
            'fecha_vencimiento_padron' => now()->addYear(), // Vence en 1 año
            'tramite_id' => $tramite->id
        ]);
    }

    /** Guardar comentario general */
    private function guardarComentarioGeneral(int $tramiteId, ?string $comentario, string $estado): void
    {
        // Solo guardar si hay comentario y usuario autenticado
        if ($comentario && auth()->check()) {
            try {
                SeccionRevision::updateOrCreate(
                    [
                        'tramite_id' => $tramiteId,
                        'seccion' => 'comentario_general'
                    ],
                    [
                        'estado' => $estado,
                        'comentario' => $comentario,
                        'revisado_por' => auth()->id()
                    ]
                );
            } catch (\Exception $e) {
                \Log::error("Error guardando comentario general: " . $e->getMessage());
                // No lanzar excepción para no interrumpir el flujo principal
            }
        }
    }
} 