<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Proveedor;
use App\Models\Tramite;
use App\Http\Requests\TramiteFormularioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Services\Formularios\DatosGeneralesFormService;
use App\Services\Formularios\DocumentosFormService;
use App\Services\Formularios\PersonaMoralFormService;
use App\Services\Formularios\DireccionFormService;
use App\Services\Formularios\ActividadesFormService;

class TramiteService
{
    private const RFC_PERSONA_MORAL_LENGTH = 12;
    private const RFC_PERSONA_FISICA_LENGTH = 13;
    
    private const TIPOS_TRAMITE = [
        'inscripcion' => [
            'titulo' => 'Inscripción al Padrón de Proveedores',
            'descripcion' => 'Complete la información para registrarse como proveedor del gobierno.',
            'mensaje_exito' => 'Su solicitud de inscripción ha sido enviada correctamente. Recibirá una notificación cuando sea revisada.'
        ],
        'renovacion' => [
            'titulo' => 'Renovación de Registro',
            'descripcion' => 'Actualice y renueve su registro en el padrón de proveedores.',
            'mensaje_exito' => 'Su solicitud de renovación ha sido procesada exitosamente.'
        ],
        'actualizacion' => [
            'titulo' => 'Actualización de Datos',
            'descripcion' => 'Modifique los datos de su registro existente.',
            'mensaje_exito' => 'Sus datos han sido actualizados correctamente.'
        ]
    ];

    private const CLAVES_SESION_SAT = [
        'sat_rfc', 'sat_nombre', 'sat_tipo_persona', 'sat_curp',
        'sat_cp', 'sat_colonia', 'sat_nombre_vialidad',
        'sat_numero_exterior', 'sat_numero_interior'
    ];

    public function __construct(
        private ProveedorService $proveedorService,
        private DatosConstitutivosService $datosConstitutivosService,
        private DatosGeneralesFormService $datosGeneralesFormService,
        private DocumentosFormService $documentosFormService,
        private PersonaMoralFormService $personaMoralFormService,
        private DireccionFormService $direccionFormService,
        private ActividadesFormService $actividadesFormService,
    ) {}

    // ============================================================================
    // MÉTODOS PÚBLICOS PRINCIPALES
    // ============================================================================

    public function getDatosTramitesIndex(?Proveedor $proveedor): array
    {
        return [
            'globalTramites' => $this->proveedorService->determinarTramitesDisponibles($proveedor),
            'proveedor' => $proveedor,
        ];
    }

    public function validarAccesoTramite(string $tipo, ?Proveedor $proveedor): bool
    {
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);
        
        // Log para debug
        Log::info('Validando acceso a trámite', [
            'tipo' => $tipo,
            'proveedor_id' => $proveedor?->id,
            'tramites_disponibles' => $tramitesDisponibles,
            'resultado' => $tramitesDisponibles[$tipo] ?? false
        ]);
        
        // Para development, ser más permisivo
        if (app()->environment('local', 'development')) {
            if (in_array($tipo, ['inscripcion', 'renovacion', 'actualizacion'])) {
                return true;
            }
        }
        
        return $tramitesDisponibles[$tipo] ?? false;
    }

    public function getDatosConstancia(string $tipo, ?Proveedor $proveedor): array
    {
        return [
            'tipo' => $tipo,
            'proveedor' => $proveedor,
        ];
    }

    public function procesarDatosConstancia(Request $request): void
    {
        $datosSat = [];
        foreach (self::CLAVES_SESION_SAT as $clave) {
            $datosSat[$clave] = $request->input($clave);
        }

        Session::put($datosSat);
    }

    public function getDatosFormulario(string $tipo, ?Proveedor $proveedor): array
    {
        return [
            'tipo_tramite' => $tipo,
            'proveedor' => $proveedor,
            'tramites' => $this->proveedorService->determinarTramitesDisponibles($proveedor),
            'titulo' => $this->getTituloTramite($tipo),
            'descripcion' => $this->getDescripcionTramite($tipo),
            'datosSat' => $this->getDatosSatDeSesion(),
        ];
    }

    public function procesarEnvioFormulario(TramiteFormularioRequest $request, string $tipo, ?Proveedor $proveedor): array
    {
        Log::info('Iniciando procesamiento de formulario', [
            'tipo' => $tipo,
            'usuario_id' => Auth::id(),
            'files_count' => count($request->allFiles())
        ]);

        try {
            DB::beginTransaction();

            $proveedor = $this->asegurarProveedor($proveedor, $request);
            $tramite = $this->crearTramite($tipo, $proveedor);
            
            $this->procesarDatosTramite($tramite, $request);

            DB::commit();

            return $this->crearRespuestaExitosa($tipo, $tramite);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->crearRespuestaError($e, $tipo);
        }
    }

    public function limpiarDatosSesion(): void
    {
        foreach (self::CLAVES_SESION_SAT as $clave) {
            Session::forget($clave);
        }
    }

    // ============================================================================
    // MÉTODOS DE UTILIDAD PÚBLICOS
    // ============================================================================

    public function getTituloTramite(string $tipo): string
    {
        return self::TIPOS_TRAMITE[$tipo]['titulo'] ?? 'Formulario de Trámite';
    }

    public function getDescripcionTramite(string $tipo): string
    {
        return self::TIPOS_TRAMITE[$tipo]['descripcion'] ?? 'Procese su trámite completando el formulario.';
    }

    public function getDatosSatDeSesion(): array
    {
        return [
            'rfc' => Session::get('sat_rfc'),
            'razon_social' => Session::get('sat_nombre'),
            'tipo_persona' => Session::get('sat_tipo_persona'),
            'curp' => Session::get('sat_curp'),
            'cp' => Session::get('sat_cp'),
            'colonia' => Session::get('sat_colonia'),
            'nombre_vialidad' => Session::get('sat_nombre_vialidad'),
            'numero_exterior' => Session::get('sat_numero_exterior'),
            'numero_interior' => Session::get('sat_numero_interior'),
        ];
    }

    // ============================================================================
    // MÉTODOS PRIVADOS - GESTIÓN DE PROVEEDORES
    // ============================================================================

    private function asegurarProveedor(?Proveedor $proveedor, TramiteFormularioRequest $request): Proveedor
    {
        if ($proveedor) {
            return $proveedor;
        }

        $rfc = $this->normalizarRfc($request->input('rfc'));
        
        $proveedorCreado = Proveedor::create([
            'usuario_id' => Auth::id(),
            'rfc' => $rfc,
            'razon_social' => $request->input('razon_social', 'Razón Social Default'),
            'tipo_persona' => $this->determinarTipoPersona($rfc),
            'estado_padron' => 'Pendiente',
            'fecha_alta_padron' => now()->toDateString(),
        ]);

        Log::info('Proveedor creado', ['id' => $proveedorCreado->id, 'rfc' => $rfc]);

        return $proveedorCreado;
    }

    // ============================================================================
    // MÉTODOS PRIVADOS - GESTIÓN DE TRÁMITES
    // ============================================================================

    private function crearTramite(string $tipo, Proveedor $proveedor): Tramite
    {
        $tramite = Tramite::create([
            'proveedor_id' => $proveedor->id,
            'tipo_tramite' => ucfirst($tipo),
            'estado' => 'Pendiente',
            'fecha_inicio' => now(),
            'paso_actual' => 1,
            'revisado_por' => 1,
        ]);

        Log::info('Trámite creado', ['id' => $tramite->id, 'tipo' => $tipo]);

        return $tramite;
    }

    private function procesarDatosTramite(Tramite $tramite, TramiteFormularioRequest $request): void
    {
        // Datos principales usando servicios especializados
        $this->guardarDatosPrincipales($tramite, $request);
        
        // Datos específicos de persona moral si aplica
        if ($this->esPersonaMoral($request->input('rfc'))) {
            $this->procesarPersonaMoral($tramite, $request);
        }
    }

    private function guardarDatosPrincipales(Tramite $tramite, TramiteFormularioRequest $request): void
    {
        app(DatosGeneralesService::class)->guardar($tramite, $request);
        app(DireccionService::class)->guardar($tramite, $request);
        app(ContactoService::class)->guardar($tramite, $request);
        app(ActividadesService::class)->guardar($tramite, $request);
        app(DocumentosService::class)->guardar($tramite, $request);

        Log::info('Datos principales guardados', ['tramite_id' => $tramite->id]);
    }

    // ============================================================================
    // MÉTODOS PRIVADOS - PERSONA MORAL
    // ============================================================================

    private function procesarPersonaMoral(Tramite $tramite, TramiteFormularioRequest $request): void
    {
        Log::info('Procesando datos de persona moral', ['tramite_id' => $tramite->id]);
        
        $this->datosConstitutivosService->procesar($tramite, $request);
    }

    // ============================================================================
    // MÉTODOS PRIVADOS - UTILIDADES
    // ============================================================================

    private function normalizarRfc(?string $rfc): string
    {
        if (!empty($rfc) && strlen($rfc) >= 10) {
            return strtoupper(trim($rfc));
        }

        return 'TEMP' . substr((string) time(), -6);
    }

    private function determinarTipoPersona(string $rfc): string
    {
        return strlen($rfc) === self::RFC_PERSONA_MORAL_LENGTH ? 'Moral' : 'Física';
    }

    private function esPersonaMoral(string $rfc): bool
    {
        return strlen($rfc) === self::RFC_PERSONA_MORAL_LENGTH;
    }

    private function crearRespuestaExitosa(string $tipo, Tramite $tramite): array
    {
        return [
            'success' => true,
            'message' => self::TIPOS_TRAMITE[$tipo]['mensaje_exito'] ?? 'Trámite procesado exitosamente.',
            'tramite_id' => $tramite->id,
            'redirect' => route('tramites.exito')
        ];
    }

    private function crearRespuestaError(\Exception $e, string $tipo): array
    {
        Log::error('Error en procesamiento de formulario', [
            'error' => $e->getMessage(),
            'tipo' => $tipo,
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);

        return [
            'success' => false,
            'message' => 'Error al procesar el trámite: ' . $e->getMessage()
        ];
    }

    /**
     * Obtener todos los datos completos de un trámite
     */
    public function obtenerDatosCompletosTramite(int $tramiteId): ?array
    {
        $tramite = Tramite::with([
            'proveedor',
            'archivos',
            'datosGenerales',
            'contacto',
            'personaMoral',
            'direccion',
            'actividades'
        ])->find($tramiteId);

        if (!$tramite) {
            return null;
        }

        return [
            'tramite' => $tramite,
            'datos_generales' => $this->datosGeneralesFormService->obtenerDatos($tramite),
            'persona_moral' => $this->personaMoralFormService->obtenerDatos($tramite),
            'direccion' => $this->direccionFormService->obtenerDatos($tramite),
            'actividades' => $this->actividadesFormService->obtenerDatos($tramite),
            'documentos' => $this->documentosFormService->obtenerDocumentos($tramite),
            'resumen' => $this->generarResumenTramite($tramite)
        ];
    }

    /**
     * Generar resumen del trámite
     */
    private function generarResumenTramite(Tramite $tramite): array
    {
        return [
            'folio' => str_pad($tramite->id, 4, '0', STR_PAD_LEFT),
            'tipo_tramite' => $tramite->tipo_tramite,
            'estado' => $tramite->estado,
            'fecha_creacion' => $tramite->created_at->format('d/m/Y H:i'),
            'fecha_actualizacion' => $tramite->updated_at->format('d/m/Y H:i'),
            'tiempo_transcurrido' => $tramite->tiempo_transcurrido,
            'proveedor_id' => $tramite->proveedor_id,
            'total_documentos' => $tramite->archivos->count(),
            'completitud' => $this->calcularCompletitud($tramite),
        ];
    }

    /**
     * Formatear dirección completa
     */
    private function formatearDireccionCompleta($direccion): string
    {
        $partes = array_filter([
            $direccion->calle,
            $direccion->numero_exterior,
            $direccion->numero_interior ? "Int. {$direccion->numero_interior}" : null,
            $direccion->colonia,
            $direccion->municipio,
            $direccion->estado,
            $direccion->codigo_postal,
            $direccion->pais
        ]);

        return implode(', ', $partes);
    }

    /**
     * Calcular completitud del trámite
     */
    private function calcularCompletitud(Tramite $tramite): array
    {
        $secciones = [
            'datos_generales' => $tramite->datosGenerales !== null,
            'contacto' => $tramite->contacto !== null,
            'persona_moral' => $tramite->personaMoral !== null,
            'direccion' => $tramite->direccion !== null,
            'actividades' => $tramite->actividades->count() > 0,
            'documentos' => $tramite->archivos->count() > 0,
        ];

        $completadas = array_sum($secciones);
        $total = count($secciones);
        $porcentaje = $total > 0 ? round(($completadas / $total) * 100) : 0;

        return [
            'secciones' => $secciones,
            'completadas' => $completadas,
            'total' => $total,
            'porcentaje' => $porcentaje,
        ];
    }
}
