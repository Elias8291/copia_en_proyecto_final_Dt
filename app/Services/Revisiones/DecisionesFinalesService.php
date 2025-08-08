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
                
                // Verificar si el trámite tiene proveedor_id
                Log::info("Datos del trámite", [
                    'proveedor_id' => $tramite->proveedor_id,
                    'tipo_tramite' => $tramite->tipo_tramite,
                    'status' => $tramite->status
                ]);
                
                $rfcProveedorService = app(RfcProveedorService::class);
                
                // Obtener RFC del trámite
                if (!$tramite->proveedor) {
                    Log::error("El trámite {$tramiteId} no tiene proveedor asociado", [
                        'proveedor_id' => $tramite->proveedor_id,
                        'tramite_exists' => $tramite ? 'Sí' : 'No'
                    ]);
                    throw new \Exception("El trámite {$tramiteId} no tiene un proveedor asociado");
                }
                
                $rfc = $tramite->proveedor->rfc;
                $tipoTramite = $tramite->tipo_tramite;
                
                Log::info("Datos del trámite obtenidos", [
                    'rfc' => $rfc,
                    'tipo_tramite' => $tipoTramite,
                    'proveedor_id' => $tramite->proveedor->id
                ]);
                
                // Debug: Verificar estado de proveedores para este RFC
                $rfcProveedorService->debugProveedoresRfc($rfc);
                
                // Determinar la acción según el tipo de trámite (excluyendo el trámite actual)
                $accion = $rfcProveedorService->determinarAccionPorTipoTramite($rfc, $tipoTramite, $tramiteId);
                
                Log::info("Acción determinada", [
                    'accion' => $accion['accion'],
                    'motivo' => $accion['motivo'],
                    'proveedores_existentes' => $accion['proveedores_existentes'],
                    'proveedor_activo_id' => $accion['proveedor_activo'] ? $accion['proveedor_activo']->id : null,
                    'proveedor_activo_pv_numero' => $accion['proveedor_activo'] ? $accion['proveedor_activo']->pv_numero : null,
                    'proveedor_activo_estado' => $accion['proveedor_activo'] ? $accion['proveedor_activo']->estado_padron : null,
                    'proveedor_activo_vencimiento' => $accion['proveedor_activo'] ? $accion['proveedor_activo']->fecha_vencimiento_padron : null
                ]);
                
                $proveedorAsignado = null;
                $numeroProveedor = null;
                
                switch ($accion['accion']) {
                    case 'crear_nuevo':
                        // Crear nuevo proveedor
                        $numeroProveedor = $rfcProveedorService->generarNumeroProveedor($rfc);
                        $proveedorAsignado = $this->crearNuevoProveedor($tramite, $numeroProveedor);
                        Log::info("Nuevo proveedor creado", [
                            'numero_proveedor' => $numeroProveedor,
                            'proveedor_id' => $proveedorAsignado->id
                        ]);
                        break;
                        
                    case 'actualizar_existente':
                        // Mantener el proveedor existente (actualización)
                        $proveedorAsignado = $accion['proveedor_activo'];
                        $numeroProveedor = $proveedorAsignado->pv_numero;
                        Log::info("Proveedor existente reutilizado", [
                            'numero_proveedor' => $numeroProveedor,
                            'proveedor_id' => $proveedorAsignado->id,
                            'estado_padron' => $proveedorAsignado->estado_padron,
                            'fecha_vencimiento' => $proveedorAsignado->fecha_vencimiento_padron
                        ]);
                        break;
                        
                    case 'renovar_vencido':
                        // Crear nuevo proveedor para renovación
                        $numeroProveedor = $rfcProveedorService->generarNumeroProveedor($rfc);
                        $proveedorAsignado = $this->crearNuevoProveedor($tramite, $numeroProveedor);
                        Log::info("Nuevo proveedor creado para renovación", [
                            'numero_proveedor' => $numeroProveedor,
                            'proveedor_id' => $proveedorAsignado->id
                        ]);
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
            } catch (\Exception $e) {
                Log::error("Error en proceso de aprobación para trámite {$tramiteId}", [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                throw $e; // Re-lanzar la excepción para que sea manejada por el controlador
            }
        });
    }

    /** Crear nuevo proveedor */
    private function crearNuevoProveedor(Tramite $tramite, string $numeroProveedor): Proveedor
    {
        try {
            Log::info("Creando nuevo proveedor para trámite {$tramite->id} con número PV: {$numeroProveedor}");
            
            // Obtener datos del trámite de forma segura
            $datosGenerales = $tramite->datosGenerales->first();
            $contacto = $tramite->contactos->first();
            $domicilio = $tramite->direcciones->first();
            
            Log::info("Datos obtenidos del trámite", [
                'datos_generales_existe' => $datosGenerales ? 'Sí' : 'No',
                'contacto_existe' => $contacto ? 'Sí' : 'No',
                'domicilio_existe' => $domicilio ? 'Sí' : 'No'
            ]);
            
            // Obtener datos del proveedor actual del trámite
            $proveedorActual = $tramite->proveedor;
            
            // Validar que el proveedor actual existe
            if (!$proveedorActual) {
                throw new \Exception('No se encontró el proveedor asociado al trámite');
            }
            
            // Validar que el proveedor tiene RFC
            if (!$proveedorActual->rfc) {
                throw new \Exception('El proveedor actual no tiene RFC asignado');
            }
            
            Log::info("Proveedor actual encontrado", [
                'proveedor_id' => $proveedorActual->id,
                'rfc' => $proveedorActual->rfc,
                'usuario_id' => $proveedorActual->usuario_id,
                'tipo_persona' => $proveedorActual->tipo_persona
            ]);
            
            // Obtener el usuario_id del proveedor actual o del usuario autenticado
            $usuarioId = $proveedorActual->usuario_id ?? auth()->id();
            if (!$usuarioId) {
                throw new \Exception("No se puede crear un proveedor sin usuario_id. El proveedor actual no tiene usuario_id asignado y no hay usuario autenticado.");
            }
            
            // Verificar que el proveedor actual tiene tipo_persona
            $tipoPersona = $proveedorActual->tipo_persona ?? 'Moral';
            
            // Verificar que el RFC existe
            if (!$proveedorActual->rfc) {
                throw new \Exception("No se puede crear un proveedor sin RFC. El proveedor actual no tiene RFC asignado.");
            }
            
            Log::info("Creando proveedor con datos", [
                'usuario_id' => $usuarioId,
                'rfc' => $proveedorActual->rfc,
                'tipo_persona' => $tipoPersona,
                'pv_numero' => $numeroProveedor
            ]);
            
            try {
                $proveedor = Proveedor::create([
                    'usuario_id' => $usuarioId,
                    'rfc' => $proveedorActual->rfc,
                    'tipo_persona' => $tipoPersona,
                    'pv_numero' => $numeroProveedor,
                    'estado_padron' => 'Activo',
                    'fecha_alta_padron' => now(),
                    'fecha_vencimiento_padron' => now()->addYear(), // Vence en 1 año
                ]);
                
                Log::info("Proveedor creado exitosamente", [
                    'proveedor_id' => $proveedor->id,
                    'numero_pv' => $numeroProveedor,
                    'rfc' => $proveedor->rfc
                ]);
            } catch (\Exception $e) {
                Log::error("Error al crear proveedor", [
                    'error' => $e->getMessage(),
                    'data' => [
                        'usuario_id' => $usuarioId,
                        'rfc' => $proveedorActual->rfc,
                        'tipo_persona' => $tipoPersona,
                        'pv_numero' => $numeroProveedor
                    ]
                ]);
                throw $e;
            }
            
            return $proveedor;
        } catch (\Exception $e) {
            Log::error("Error creando nuevo proveedor para trámite {$tramite->id}", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            throw $e;
        }
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