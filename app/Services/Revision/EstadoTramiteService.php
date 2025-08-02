<?php

namespace App\Services\Revision;

use App\Models\Tramite;
use App\Services\NotificacionService;
use App\Services\Tramites\CitaTramiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Servicio especializado para la gestión de estados de trámites
 * Responsabilidad: Manejar cambios de estado y sus efectos secundarios
 */
class EstadoTramiteService
{
    public function __construct(
        private NotificacionService $notificacionService,
        private CitaTramiteService $citaTramiteService
    ) {}

    /**
     * Estados válidos para trámites
     */
    private const ESTADOS_VALIDOS = [
        'Pendiente',
        'En_Revision', 
        'Por_Cotejar',
        'Aprobado',
        'Rechazado',
        'Para_Correccion',
        'Cancelado'
    ];

    /**
     * Mensajes de éxito por estado
     */
    private const MENSAJES_ESTADO = [
        'Aprobado' => 'Tu trámite fue aprobado exitosamente.',
        'Por_Cotejar' => 'Tu trámite fue enviado a cotejo presencial exitosamente.',
        'Rechazado' => 'Tu trámite fue rechazado.',
        'Para_Correccion' => 'Tu trámite fue enviado para corrección.',
        'Cancelado' => 'Tu trámite fue cancelado.',
        'En_Revision' => 'Tu trámite fue enviado a revisión.',
        'Pendiente' => 'Tu trámite fue marcado como pendiente.'
    ];

    /**
     * Títulos por estado
     */
    private const TITULOS_ESTADO = [
        'Aprobado' => '¡Trámite Aprobado!',
        'Por_Cotejar' => '¡Enviado a Cotejo!',
        'Rechazado' => 'Trámite Rechazado',
        'Para_Correccion' => 'Enviado para Corrección',
        'Cancelado' => 'Trámite Cancelado',
        'En_Revision' => 'Enviado a Revisión',
        'Pendiente' => 'Trámite Pendiente'
    ];

    /**
     * Cambia el estado de un trámite y ejecuta las acciones correspondientes
     */
    public function cambiarEstado(Tramite $tramite, string $nuevoEstado, ?string $observaciones = null): array
    {
        try {
            // Validar estado
            if (!$this->esEstadoValido($nuevoEstado)) {
                throw new \InvalidArgumentException("Estado no válido: {$nuevoEstado}");
            }

            $estadoAnterior = $tramite->estado;
            
            // Actualizar el trámite
            $this->actualizarTramite($tramite, $nuevoEstado, $observaciones);
            
            // Ejecutar acciones específicas del estado
            $this->ejecutarAccionesEstado($tramite, $nuevoEstado);
            
            // Notificar cambio de estado
            $this->notificacionService->notificarCambioEstado($tramite, $estadoAnterior, $nuevoEstado);
            
            // Registrar log
            $this->registrarCambioEstado($tramite, $estadoAnterior, $nuevoEstado);

            return [
                'success' => true,
                'mensaje' => $this->obtenerMensajeEstado($nuevoEstado),
                'titulo' => $this->obtenerTituloEstado($nuevoEstado),
                'estado_anterior' => $estadoAnterior,
                'nuevo_estado' => $nuevoEstado
            ];

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado del trámite', [
                'tramite_id' => $tramite->id,
                'estado_anterior' => $tramite->estado,
                'nuevo_estado' => $nuevoEstado,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'mensaje' => 'Error al cambiar el estado del trámite: ' . $e->getMessage(),
                'titulo' => 'Error al procesar trámite'
            ];
        }
    }

    /**
     * Actualiza los datos básicos del trámite
     */
    private function actualizarTramite(Tramite $tramite, string $nuevoEstado, ?string $observaciones): void
    {
        $tramite->update([
            'estado' => $nuevoEstado,
            'observaciones' => $observaciones,
            'revisado_por' => Auth::id()
        ]);
    }

    /**
     * Ejecuta acciones específicas según el nuevo estado
     */
    private function ejecutarAccionesEstado(Tramite $tramite, string $nuevoEstado): void
    {
        switch ($nuevoEstado) {
            case 'Por_Cotejar':
                $this->procesarEstadoPorCotejar($tramite);
                break;
                
            case 'Aprobado':
                $this->procesarEstadoAprobado($tramite);
                break;
                
            case 'Rechazado':
                $this->procesarEstadoRechazado($tramite);
                break;
                
            case 'Para_Correccion':
                $this->procesarEstadoParaCorreccion($tramite);
                break;
        }
    }

    /**
     * Procesa el estado "Por_Cotejar" - agenda cita automáticamente
     */
    private function procesarEstadoPorCotejar(Tramite $tramite): void
    {
        try {
            $cita = $this->citaTramiteService->agendarCotejo($tramite);
            
            if ($cita) {
                $this->notificacionService->notificarCitaAgendada($tramite, $cita);
                
                Log::info('Cita agendada automáticamente para cotejo', [
                    'tramite_id' => $tramite->id,
                    'cita_id' => $cita->id,
                    'fecha_cita' => $cita->fecha_cita
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al agendar cita para cotejo', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Procesa el estado "Aprobado" - crea oficio automáticamente
     */
    private function procesarEstadoAprobado(Tramite $tramite): void
    {
        try {
            // Verificar si ya existe un oficio
            $oficioExistente = \App\Models\Oficio::where('tramite_id', $tramite->id)->first();
            
            if (!$oficioExistente) {
                $oficioService = app(\App\Services\OficioService::class);
                $oficio = $oficioService->crearOficioAprobacion($tramite);

                Log::info('Oficio de aprobación creado automáticamente', [
                    'tramite_id' => $tramite->id,
                    'oficio_id' => $oficio->id,
                    'numero_oficio' => $oficio->numero_oficio
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al crear oficio de aprobación', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Procesa el estado "Rechazado"
     */
    private function procesarEstadoRechazado(Tramite $tramite): void
    {
        // Cancelar cita si existe
        if ($tramite->cita && $tramite->cita->estado !== 'Cancelada') {
            $tramite->cita->update(['estado' => 'Cancelada']);
            
            Log::info('Cita cancelada por rechazo de trámite', [
                'tramite_id' => $tramite->id,
                'cita_id' => $tramite->cita->id
            ]);
        }
    }

    /**
     * Procesa el estado "Para_Correccion"
     */
    private function procesarEstadoParaCorreccion(Tramite $tramite): void
    {
        // Registrar que el trámite necesita correcciones
        Log::info('Trámite enviado para corrección', [
            'tramite_id' => $tramite->id,
            'observaciones' => $tramite->observaciones
        ]);
    }

    /**
     * Valida si el estado es válido
     */
    private function esEstadoValido(string $estado): bool
    {
        return in_array($estado, self::ESTADOS_VALIDOS);
    }

    /**
     * Obtiene el mensaje correspondiente al estado
     */
    private function obtenerMensajeEstado(string $estado): string
    {
        return self::MENSAJES_ESTADO[$estado] ?? 'Tu trámite fue actualizado exitosamente.';
    }

    /**
     * Obtiene el título correspondiente al estado
     */
    private function obtenerTituloEstado(string $estado): string
    {
        return self::TITULOS_ESTADO[$estado] ?? '¡Trámite Procesado!';
    }

    /**
     * Registra el cambio de estado en los logs
     */
    private function registrarCambioEstado(Tramite $tramite, string $estadoAnterior, string $nuevoEstado): void
    {
        Log::info('Estado de trámite cambiado exitosamente', [
            'tramite_id' => $tramite->id,
            'estado_anterior' => $estadoAnterior,
            'nuevo_estado' => $nuevoEstado,
            'revisado_por' => Auth::id(),
            'timestamp' => now()
        ]);
    }

    /**
     * Obtiene los estados válidos
     */
    public function obtenerEstadosValidos(): array
    {
        return self::ESTADOS_VALIDOS;
    }

    /**
     * Verifica si un trámite puede cambiar a un estado específico
     */
    public function puedeTransicionarA(Tramite $tramite, string $nuevoEstado): bool
    {
        $estadoActual = $tramite->estado;
        
        // Definir transiciones válidas
        $transicionesValidas = [
            'Pendiente' => ['En_Revision', 'Cancelado'],
            'En_Revision' => ['Por_Cotejar', 'Aprobado', 'Rechazado', 'Para_Correccion'],
            'Por_Cotejar' => ['Aprobado', 'Rechazado', 'Para_Correccion'],
            'Para_Correccion' => ['En_Revision'],
            'Aprobado' => [], // No se puede cambiar desde aprobado
            'Rechazado' => [], // No se puede cambiar desde rechazado
            'Cancelado' => [] // No se puede cambiar desde cancelado
        ];

        return in_array($nuevoEstado, $transicionesValidas[$estadoActual] ?? []);
    }
}