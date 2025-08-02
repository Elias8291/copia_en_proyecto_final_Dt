<?php

namespace App\Services;

use App\Models\Proveedor;
use App\Models\User;
use App\Models\Tramite;
use App\Helpers\TiempoHelper;
use App\Services\Proveedores\EstadosProveedorService;
use App\Services\Proveedores\TramitesDisponiblesService;
use App\Services\Proveedores\AprobacionTramiteService;
use App\Services\Proveedores\BusquedaProveedorService;
use App\Services\Proveedores\GestionProveedorService;
use App\Services\Proveedores\DatosTramiteAprobadoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProveedorService
{
    public function __construct(
        private EstadosProveedorService $estadosService,
        private TramitesDisponiblesService $tramitesDisponiblesService,
        private AprobacionTramiteService $aprobacionService,
        private BusquedaProveedorService $busquedaService,
        private GestionProveedorService $gestionService,
        private DatosTramiteAprobadoService $datosTramiteAprobadoService
    ) {}

    /**
     * Get the proveedor associated with the authenticated user
     */
    public function getProveedorByUser(): ?Proveedor
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return null;
        }

        try {
            return $user->proveedor()->first();
        } catch (\Exception $e) {
            Log::error('Error al obtener proveedor: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Verificar si el proveedor tiene trámites pendientes
     */
    public function tieneTramitesPendientes(?Proveedor $proveedor): bool
    {
        if (!$proveedor) {
            return false;
        }

        return $proveedor->tramites()
            ->whereIn('estado', ['Para_Correccion', 'Por_Cotejar', 'Cancelado', 'En_Revision', 'Pendiente', 'Enviado', 'Rechazado'])
            ->exists();
    }

    /**
     * Obtener el trámite pendiente más reciente del proveedor
     */
    public function getTramitePendiente(?Proveedor $proveedor): ?Tramite
    {
        if (!$proveedor) {
            return null;
        }

        return $proveedor->tramites()
            ->whereIn('estado', ['Para_Correccion', 'Por_Cotejar', 'Cancelado', 'En_Revision', 'Pendiente', 'Enviado', 'Rechazado'])
            ->with(['proveedor', 'proveedor.user', 'datosGenerales', 'apoderadoLegal', 'oficios', 'cita'])
            ->latest()
            ->first();
    }

    /**
     * Obtener información detallada del trámite pendiente
     */
    public function getDetallesTramitePendiente(?Proveedor $proveedor): ?array
    {
        $tramite = $this->getTramitePendiente($proveedor);
        
        if (!$tramite) {
            return null;
        }

        $informacionEstado = $this->estadosService->obtenerInformacionCompleta($tramite->estado);

        $detalles = [
            'tramite' => $tramite,
            'dias_transcurridos' => $tramite->created_at->diffInDays(now()),
            'estado_color' => $informacionEstado['color'],
            'estado_descripcion' => $informacionEstado['descripcion'],
            'siguiente_paso' => $informacionEstado['siguiente_paso'],
            'puede_editar' => in_array($tramite->estado, ['Para_Correccion', 'Pendiente'])
        ];

        // Si el estado es Por_Cotejar, incluir información de la cita
        if ($tramite->estado === 'Por_Cotejar' && $tramite->cita) {
            $cita = $tramite->cita;
            $detalles['cita'] = [
                'id' => $cita->id,
                'fecha_cita' => $cita->fecha_cita,
                'tipo_cita' => $cita->tipo_cita,
                'estado' => $cita->estado,
                'motivo' => $cita->motivo,
                'observaciones' => $cita->observaciones
            ];
        }

        return $detalles;
    }



    /**
     * Obtener quién debe presentarse según el tipo de persona del trámite
     */
    public function getQuienDebePresentarse(Tramite $tramite): string
    {
        $rfc = $tramite->proveedor->rfc ?? '';
        $tipoPersona = (strlen($rfc) === 12) ? 'Moral' : 'Física';
        
        if ($tipoPersona === 'Moral') {
            $apoderadoLegal = $tramite->apoderadoLegal;
            $representanteLegal = null;
            
            if ($apoderadoLegal) {
                $representanteLegal = $apoderadoLegal->nombre_apoderado ?? null;
            }
            
            return $representanteLegal ?: 'su representante legal';
        } else {
            // Para persona física, mostrar el nombre del usuario que realizó el trámite
            $usuario = $tramite->proveedor->user ?? null;
            if ($usuario) {
                return $usuario->name ?? 'quien realizó el trámite';
            }
            
            return 'quien realizó el trámite';
        }
    }

    public function determinarTramitesDisponibles($proveedor): array
    {
        return $this->tramitesDisponiblesService->determinar($proveedor);
    }

    /**
     * Verifica si el usuario autenticado tiene un proveedor asociado en estado Activo
     */
    public function hasActiveProveedor(): bool
    {
        $proveedor = $this->getProveedorByUser();

        return $proveedor?->estado_padron === 'Activo';
    }



    /**
     * Calcula el tipo de persona basado en el RFC del proveedor
     * RFC de 13 caracteres = Persona Física
     * RFC de 12 caracteres = Persona Moral
     */
    public function calcularTipoPersonaPorRfc(?Proveedor $proveedor): ?string
    {
        if (!$proveedor || !$proveedor->rfc) {
            return null;
        }

        return TiempoHelper::calcularTipoPersonaPorRfc($proveedor->rfc);
    }

    /**
     * Obtiene el tipo de persona del proveedor, calculándolo si es necesario
     */
    public function getTipoPersona(?Proveedor $proveedor): ?string
    {
        return TiempoHelper::getTipoPersona($proveedor);
    }

    /**
     * Aprobar trámite y crear oficio
     */
    public function aprobarTramite(Tramite $tramite): array
    {
        return $this->aprobacionService->aprobar($tramite);
    }

    // ========== MÉTODOS DE BÚSQUEDA ==========

    /**
     * Buscar proveedor por RFC
     */
    public function buscarPorRFC(string $rfc): ?Proveedor
    {
        return $this->busquedaService->buscarPorRFC($rfc);
    }

    /**
     * Buscar proveedor por correo
     */
    public function buscarPorCorreo(string $correo): ?Proveedor
    {
        return $this->busquedaService->buscarPorCorreo($correo);
    }

    /**
     * Obtener proveedores con filtros
     */
    public function obtenerConFiltros(array $filtros = [])
    {
        return $this->busquedaService->obtenerConFiltros($filtros);
    }

    /**
     * Obtener estadísticas de proveedores
     */
    public function obtenerEstadisticas(): array
    {
        return $this->busquedaService->obtenerEstadisticas();
    }

    /**
     * Obtener proveedores próximos a vencer
     */
    public function getProveedoresProximosAVencer(int $diasAnticipacion = 30)
    {
        return $this->busquedaService->obtenerProximosAVencer($diasAnticipacion);
    }

    /**
     * Verificar si un proveedor está próximo a vencer
     */
    public function estaProximoAVencer(Proveedor $proveedor, int $diasAnticipacion = 30): bool
    {
        return $this->busquedaService->estaProximoAVencer($proveedor, $diasAnticipacion);
    }

    // ========== MÉTODOS DE GESTIÓN ==========

    /**
     * Crear o obtener proveedor existente para un usuario
     */
    public function createOrGetProveedor(User $user, array $data = []): Proveedor
    {
        return $this->gestionService->crearObtenerProveedor($user, $data);
    }

    /**
     * Crear un nuevo proveedor completo
     */
    public function crearProveedor(array $data): Proveedor
    {
        return $this->gestionService->crearProveedor($data);
    }

    /**
     * Actualizar proveedor
     */
    public function actualizarProveedor(Proveedor $proveedor, array $data): Proveedor
    {
        return $this->gestionService->actualizarProveedor($proveedor, $data);
    }

    /**
     * Activar proveedor (cambiar estado y asignar número PV)
     */
    public function activarProveedor(Proveedor $proveedor, ?string $fechaVencimiento = null): Proveedor
    {
        return $this->gestionService->activarProveedor($proveedor, $fechaVencimiento);
    }

    /**
     * Cambiar estado del proveedor
     */
    public function cambiarEstado(Proveedor $proveedor, string $nuevoEstado, ?string $observaciones = null): Proveedor
    {
        return $this->gestionService->cambiarEstado($proveedor, $nuevoEstado, $observaciones);
    }

    /**
     * Eliminar proveedor
     */
    public function eliminarProveedor(Proveedor $proveedor): bool
    {
        return $this->gestionService->eliminarProveedor($proveedor);
    }

    /**
     * Obtener estadísticas detalladas de un proveedor
     */
    public function getEstadisticasProveedor(Proveedor $proveedor): array
    {
        return $this->gestionService->obtenerEstadisticasProveedor($proveedor);
    }

    // ========== MÉTODOS DE TRÁMITES ==========

    /**
     * Verificar si un usuario puede realizar un tipo de trámite
     */
    public function puedeRealizarTramite(User $user, string $tipoTramite): array
    {
        $proveedor = $this->getProveedorByUser();
        
        if (!$proveedor) {
            return [
                'puede' => false,
                'mensaje' => 'No tiene un proveedor registrado'
            ];
        }

        $tramitesDisponibles = $this->determinarTramitesDisponibles($proveedor);
        
        return [
            'puede' => in_array($tipoTramite, $tramitesDisponibles['disponibles'] ?? []),
            'mensaje' => $tramitesDisponibles['message'] ?? 'No puede realizar este trámite'
        ];
    }

    /**
     * Obtener resumen de trámites disponibles para un usuario
     */
    public function getTramitesDisponibles(User $user): array
    {
        $proveedor = $this->getProveedorByUser();
        return $this->determinarTramitesDisponibles($proveedor);
    }

    /**
     * Generar número PV único
     */
    public function generarNumeroPV(): string
    {
        return app(\App\Services\Proveedores\NumerosPvService::class)->generarNuevoPv();
    }

    /**
     * Asignar número PV a proveedor
     */
    public function asignarNumeroPV(Proveedor $proveedor): Proveedor
    {
        $numeroPV = $this->generarNumeroPV();
        $proveedor->update(['pv_numero' => $numeroPV]);
        return $proveedor;
    }

    // ========== MÉTODOS DE DATOS DEL ÚLTIMO TRÁMITE APROBADO ==========
    
    /**
     * Obtiene el último trámite aprobado del proveedor con todos sus datos relacionados
     */
    public function obtenerUltimoTramiteAprobado(Proveedor $proveedor): ?Tramite
    {
        return $this->datosTramiteAprobadoService->obtenerUltimoTramiteAprobado($proveedor);
    }

    /**
     * Obtiene los datos generales del último trámite aprobado
     */
    public function obtenerDatosGeneralesUltimoTramite(Proveedor $proveedor): ?array
    {
        return $this->datosTramiteAprobadoService->obtenerSoloDatosGenerales($proveedor);
    }

    /**
     * Obtiene información completa del último trámite aprobado incluyendo datos generales
     */
    public function obtenerInformacionCompletaUltimoTramite(Proveedor $proveedor): ?array
    {
        return $this->datosTramiteAprobadoService->obtenerInformacionCompletaUltimoTramite($proveedor);
    }

    /**
     * Verifica si el proveedor tiene algún trámite aprobado
     */
    public function tieneTramiteAprobado(Proveedor $proveedor): bool
    {
        return $this->datosTramiteAprobadoService->tieneTramiteAprobado($proveedor);
    }

    /**
     * Obtiene la fecha del último trámite aprobado
     */
    public function obtenerFechaUltimoTramiteAprobado(Proveedor $proveedor): ?string
    {
        return $this->datosTramiteAprobadoService->obtenerFechaUltimoTramiteAprobado($proveedor);
    }

    /**
     * Obtiene el tipo del último trámite aprobado
     */
    public function obtenerTipoUltimoTramiteAprobado(Proveedor $proveedor): ?string
    {
        return $this->datosTramiteAprobadoService->obtenerTipoUltimoTramiteAprobado($proveedor);
    }

    /**
     * Obtener todos los datos completos del último trámite aprobado
     */
    public function obtenerDatosCompletosUltimoTramite(Proveedor $proveedor): ?array
    {
        try {
            $ultimoTramite = $this->obtenerUltimoTramiteAprobado($proveedor);
            
            if (!$ultimoTramite) {
                return null;
            }

            // Cargar las relaciones que realmente existen en el modelo Tramite
            $tramiteCompleto = $ultimoTramite->load([
                'datosGenerales',
                'apoderadoLegal',
                'direccion.coordenadas', // Cargar también las coordenadas
                'accionistas',
                'actividades',
                'datosConstitutivos',
                'archivos',
                'revisionSecciones'
            ]);

            return [
                'tramite' => $tramiteCompleto,
                'datosGenerales' => $tramiteCompleto->datosGenerales,
                'apoderadoLegal' => $tramiteCompleto->apoderadoLegal,
                'direccion' => $tramiteCompleto->direccion,
                'accionistas' => $tramiteCompleto->accionistas,
                'actividadesEconomicas' => $tramiteCompleto->actividades, // Cambiado de actividadesEconomicas a actividades
                'constitucion' => $tramiteCompleto->datosConstitutivos, // Cambiado de constitucion a datosConstitutivos
                'documentos' => $tramiteCompleto->archivos, // Cambiado de documentos a archivos
                'estadoSeccion' => $tramiteCompleto->revisionSecciones // Cambiado de estadoSeccion a revisionSecciones
            ];
            
        } catch (\Exception $e) {
            Log::error('Error al obtener datos completos del último trámite: ' . $e->getMessage(), [
                'proveedor_id' => $proveedor->id,
                'rfc' => $proveedor->rfc
            ]);
            return null;
        }
    }
}