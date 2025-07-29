<?php declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\TramiteFormularioRequest;
use App\Models\Proveedor;
use App\Models\Tramite;
use App\Services\Formularios\ActividadesFormService;
use App\Services\Formularios\DatosGeneralesFormService;
use App\Services\Formularios\DireccionFormService;
use App\Services\Formularios\DocumentosFormService;
use App\Services\Formularios\PersonaMoralFormService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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

        // Validar que el RFC del usuario coincida con el RFC de la constancia
        $rfcUsuario = Auth::user()->rfc;
        $rfcConstancia = $datosSat['sat_rfc'] ?? null;

        if ($rfcUsuario && $rfcConstancia) {
            $rfcUsuarioNormalizado = strtoupper(trim($rfcUsuario));
            $rfcConstanciaNormalizado = strtoupper(trim($rfcConstancia));

            if ($rfcUsuarioNormalizado !== $rfcConstanciaNormalizado) {
                Log::warning('RFC del usuario no coincide con RFC de la constancia', [
                    'usuario_id' => Auth::id(),
                    'rfc_usuario' => $rfcUsuarioNormalizado,
                    'rfc_constancia' => $rfcConstanciaNormalizado
                ]);

                throw new \Exception('El RFC de la constancia fiscal no coincide con su RFC registrado. Verifique que esté cargando la constancia correcta.');
            }

            Log::info('RFC validado correctamente', [
                'usuario_id' => Auth::id(),
                'rfc' => $rfcUsuarioNormalizado
            ]);
        } else {
            Log::warning('No se pudo validar RFC - datos faltantes', [
                'usuario_id' => Auth::id(),
                'rfc_usuario' => $rfcUsuario,
                'rfc_constancia' => $rfcConstancia
            ]);

            if (!$rfcUsuario) {
                throw new \Exception('Su cuenta no tiene un RFC registrado. Contacte al administrador.');
            }

            if (!$rfcConstancia) {
                throw new \Exception('No se pudo extraer el RFC de la constancia. Verifique que el archivo sea válido.');
            }
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

    /**
     * Obtiene datos de un trámite existente para correcciones
     */
    public function getDatosFormularioCorreccion(Tramite $tramite): array
    {
        // Cargar todas las relaciones necesarias
        $tramite->load([
            'proveedor',
            'datosGenerales',
            'datosConstitutivos.instrumentoNotarial',
            'apoderadoLegal.instrumentoNotarial',
            'contactos',
            'accionistas',
            'direcciones.estado',
            'actividades.sector',
            'archivos.catalogoArchivo'
        ]);

        return [
            'tipo_tramite' => $tramite->tipo_tramite,
            'proveedor' => $tramite->proveedor,
            'tramite' => $tramite,
            'tramites' => $this->proveedorService->determinarTramitesDisponibles($tramite->proveedor),
            'titulo' => 'Corregir Trámite - ' . ucfirst($tramite->tipo_tramite),
            'descripcion' => 'Realice las correcciones solicitadas y reenvíe el trámite.',
            'datosSat' => $this->getDatosSatDeSesion(),
            'es_correccion' => true,
        ];
    }

    public function procesarEnvioFormulario(Request $request, string $tipo, ?Proveedor $proveedor): array
    {
        Log::info('=== PRUEBA: Servicio iniciando procesamiento ===', [
            'tipo' => $tipo,
            'usuario_id' => Auth::id(),
            'files_count' => count($request->allFiles()),
            'request_data' => $request->all()
        ]);

        try {
            Log::info('=== PRUEBA: Iniciando transacción ===');
            DB::beginTransaction();

            Log::info('=== PRUEBA: Asegurando proveedor ===');
            $proveedor = $this->asegurarProveedor($proveedor, $request);
            Log::info('=== PRUEBA: Proveedor asegurado ===', ['proveedor_id' => $proveedor->id]);

            Log::info('=== PRUEBA: Creando trámite ===');
            $tramite = $this->crearTramite($tipo, $proveedor);
            Log::info('=== PRUEBA: Trámite creado ===', ['tramite_id' => $tramite->id]);

            Log::info('=== PRUEBA: Procesando datos del trámite ===');
            $this->procesarDatosTramite($tramite, $request);

            Log::info('=== PRUEBA: Commit de transacción ===');
            DB::commit();

            Log::info('=== PRUEBA: Creando respuesta exitosa ===');
            return $this->crearRespuestaExitosa($tipo, $tramite);
        } catch (\Exception $e) {
            Log::error('=== PRUEBA: Error en servicio ===', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
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

    private function asegurarProveedor(?Proveedor $proveedor, Request $request): Proveedor
    {
        if ($proveedor) {
            Log::info('=== PRUEBA: Proveedor existente encontrado ===', ['proveedor_id' => $proveedor->id]);
            return $proveedor;
        }

        Log::info('=== PRUEBA: Creando nuevo proveedor ===');
        $rfc = $this->normalizarRfc($request->input('rfc', 'XAXX010101000'));

        $proveedorCreado = Proveedor::create([
            'usuario_id' => Auth::id(),
            'rfc' => $rfc,
            'tipo_persona' => $this->determinarTipoPersona($rfc),
            'estado_padron' => 'Pendiente',
            'fecha_alta_padron' => now()->toDateString(),
        ]);

        Log::info('=== PRUEBA: Proveedor creado ===', ['id' => $proveedorCreado->id, 'rfc' => $rfc]);

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

    private function procesarDatosTramite(Tramite $tramite, Request $request): void
    {
        Log::info('=== PRUEBA: Procesando datos del trámite ===', ['tramite_id' => $tramite->id]);

        // Datos principales usando servicios especializados
        $this->guardarDatosPrincipales($tramite, $request);

        // Datos específicos de persona moral si aplica
        if ($this->esPersonaMoral($request->input('rfc', 'XAXX010101000'))) {
            Log::info('=== PRUEBA: Procesando persona moral ===');
            $this->procesarPersonaMoral($tramite, $request);
        } else {
            Log::info('=== PRUEBA: Es persona física, saltando datos de persona moral ===');
        }
    }

    private function guardarDatosPrincipales(Tramite $tramite, Request $request): void
    {
        Log::info('=== PRUEBA: Guardando datos principales ===', ['tramite_id' => $tramite->id]);

        try {
            Log::info('=== PRUEBA: Guardando datos generales ===');
            app(DatosGeneralesService::class)->guardar($tramite, $request);

            Log::info('=== PRUEBA: Guardando dirección ===');
            app(DireccionService::class)->guardar($tramite, $request);

            Log::info('=== PRUEBA: Guardando contacto ===');
            app(ContactoService::class)->guardar($tramite, $request);

            // Procesar actividades temporales antes de guardar
            Log::info('=== PRUEBA: Procesando actividades ===');
            $this->procesarActividadesConTemporales($tramite, $request);

            Log::info('=== PRUEBA: Guardando documentos ===');
            app(DocumentosService::class)->guardar($tramite, $request);

            Log::info('=== PRUEBA: Datos principales guardados exitosamente ===', ['tramite_id' => $tramite->id]);
        } catch (\Exception $e) {
            Log::error('=== PRUEBA: Error guardando datos principales ===', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            throw $e;
        }
    }

    /**
     * Procesa actividades incluyendo las temporales
     */
    private function procesarActividadesConTemporales(Tramite $tramite, Request $request): void
    {
        Log::info('=== PRUEBA: Procesando actividades con temporales ===');
        $actividades = $request->input('actividades', []);

        Log::info('=== PRUEBA: Actividades encontradas ===', ['count' => count($actividades)]);

        // Solo procesar si hay actividades
        if (!empty($actividades)) {
            Log::info('=== PRUEBA: Guardando actividades ===');
            $actividadesService = new ActividadesService($request);
            $actividadesService->guardar($tramite, $request);
        } else {
            Log::info('=== PRUEBA: No hay actividades para procesar ===');
        }
    }

    // ============================================================================
    // MÉTODOS PRIVADOS - PERSONA MORAL
    // ============================================================================

    private function procesarPersonaMoral(Tramite $tramite, Request $request): void
    {
        Log::info('=== PRUEBA: Procesando datos de persona moral ===', ['tramite_id' => $tramite->id]);

        try {
            $this->datosConstitutivosService->procesar($tramite, $request);
            Log::info('=== PRUEBA: Datos de persona moral procesados exitosamente ===');
        } catch (\Exception $e) {
            Log::error('=== PRUEBA: Error procesando persona moral ===', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            throw $e;
        }
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
            'contactos',
            'datosConstitutivos',
            'direcciones',
            'actividades'
        ])->find($tramiteId);

        if (!$tramite) {
            return null;
        }

        return [
            'tramite' => $tramite,
            'datos_generales' => $this->datosGeneralesFormService->obtenerDatos($tramite),
            'datos_constitutivos' => $this->personaMoralFormService->obtenerDatos($tramite),
            'direcciones' => $this->direccionFormService->obtenerDatos($tramite),
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
            'folio' => str_pad((string) $tramite->id, 4, '0', STR_PAD_LEFT),
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
            'contactos' => $tramite->contactos->count() > 0,
            'datos_constitutivos' => $tramite->datosConstitutivos !== null,
            'direcciones' => $tramite->direcciones->count() > 0,
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

    /**
     * Procesa la actualización de un trámite con correcciones
     */
    public function procesarCorreccionTramite(Request $request, Tramite $tramite): array
    {
        Log::info('=== PROCESANDO CORRECCIÓN DE TRÁMITE ===', [
            'tramite_id' => $tramite->id,
            'usuario_id' => Auth::id(),
            'estado_actual' => $tramite->estado
        ]);

        try {
            Log::info('=== Iniciando transacción para corrección ===');
            DB::beginTransaction();

            // Actualizar el estado del trámite a En_Revision
            $tramite->update([
                'estado' => 'En_Revision',
                'observaciones' => 'Trámite reenviado con correcciones',
                'correcciones_count' => $tramite->correcciones_count + 1,
            ]);

            Log::info('=== Estado actualizado a En_Revision ===');

            // Procesar solo los datos que se envían en el formulario
            $this->procesarDatosTramiteCorreccion($tramite, $request);

            Log::info('=== Commit de transacción para corrección ===');
            DB::commit();

            Log::info('=== Corrección procesada exitosamente ===');
            return [
                'success' => true,
                'tramite_id' => $tramite->id,
                'message' => 'Trámite corregido y reenviado exitosamente.'
            ];
        } catch (\Exception $e) {
            Log::error('=== Error procesando corrección ===', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Error al procesar las correcciones: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Procesa solo los datos que se envían en el formulario de corrección
     * sin borrar los datos existentes de secciones aprobadas
     */
    private function procesarDatosTramiteCorreccion(Tramite $tramite, Request $request): void
    {
        Log::info('=== Procesando datos de corrección ===', ['tramite_id' => $tramite->id]);

        try {
            // Procesar datos generales solo si se envían
            if ($request->has('rfc') || $request->has('razon_social') || $request->has('pagina_web') || 
                $request->has('telefono') || $request->has('email_contacto') || $request->has('cargo')) {
                Log::info('=== Procesando datos generales ===');
                // Limpiar datos existentes y crear nuevos
                $tramite->datosGenerales()->delete();
                $tramite->contactos()->delete();
                $this->datosGeneralesFormService->procesar($tramite, $request->all());
            }

            // Procesar actividades solo si se envían
            if ($request->has('actividades') || $request->has('buscador_actividad')) {
                Log::info('=== Procesando actividades ===');
                // Limpiar actividades existentes y crear nuevas
                $tramite->actividades()->detach();
                $this->actividadesFormService->procesar($tramite, $request->all());
            }

            // Procesar domicilio solo si se envían
            if ($request->has('codigo_postal') || $request->has('estado_id') || $request->has('municipio') || 
                $request->has('asentamiento') || $request->has('calle') || $request->has('numero_exterior')) {
                Log::info('=== Procesando domicilio ===');
                // Limpiar direcciones existentes y crear nuevas
                $tramite->direcciones()->delete();
                $this->direccionFormService->procesar($tramite, $request->all());
            }

            // Procesar datos constitutivos solo si se envían
            if ($request->has('numero_escritura') || $request->has('fecha_constitucion') || 
                $request->has('notario_nombre') || $request->has('entidad_federativa')) {
                Log::info('=== Procesando datos constitutivos ===');
                // Limpiar datos constitutivos existentes y crear nuevos
                $tramite->datosConstitutivos()->delete();
                $this->personaMoralFormService->procesar($tramite, $request->all());
            }

            // Procesar apoderado legal solo si se envían
            if ($request->has('apoderado_nombre') || $request->has('apoderado_rfc') || 
                $request->has('poder_numero_escritura') || $request->has('poder_fecha_constitucion')) {
                Log::info('=== Procesando apoderado legal ===');
                // Limpiar apoderado existente y crear nuevo
                $tramite->apoderadoLegal()->delete();
                $this->datosConstitutivosService->procesar($tramite, $request);
            }

            // Procesar accionistas solo si se envían
            if ($request->has('accionistas')) {
                Log::info('=== Procesando accionistas ===');
                // Limpiar accionistas existentes y crear nuevos
                $tramite->accionistas()->delete();
                $this->datosConstitutivosService->procesar($tramite, $request);
            }

            // Procesar documentos solo si se envían
            if ($request->has('documentos')) {
                Log::info('=== Procesando documentos ===');
                $this->documentosFormService->procesar($tramite, $request->all());
            }

            Log::info('=== Datos de corrección procesados exitosamente ===');
        } catch (\Exception $e) {
            Log::error('=== Error procesando datos de corrección ===', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            throw $e;
        }
    }

    /**
     * Limpia los datos anteriores del trámite antes de procesar correcciones
     */
    private function limpiarDatosTramite(Tramite $tramite): void
    {
        Log::info('=== Limpiando datos anteriores del trámite ===', ['tramite_id' => $tramite->id]);

        // Eliminar datos relacionados
        $tramite->datosGenerales()->delete();
        $tramite->datosConstitutivos()->delete();
        $tramite->apoderadoLegal()->delete();
        $tramite->contactos()->delete();
        $tramite->accionistas()->delete();
        $tramite->direcciones()->delete();
        $tramite->actividades()->detach();

        Log::info('=== Datos anteriores eliminados ===');
    }
}
