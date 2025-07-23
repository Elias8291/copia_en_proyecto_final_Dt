<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Proveedor;
use App\Models\Tramite;
use App\Models\DatosGenerales;
use App\Models\Direccion;
use App\Models\Contacto;
use App\Models\Accionista;
use App\Models\InstrumentoNotarial;
use App\Models\DatosConstitutivos;
use App\Models\ApoderadoLegal;
use App\Models\Archivo;
use App\Models\ActividadEconomica;
use App\Http\Requests\TramiteFormularioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TramiteService
{
    protected $proveedorService;

    public function __construct(ProveedorService $proveedorService)
    {
        $this->proveedorService = $proveedorService;
    }

    /**
     * Obtiene los datos necesarios para la vista de selección de trámites
     */
    public function getDatosTramitesIndex(?Proveedor $proveedor): array
    {
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);

        return [
            'globalTramites' => $tramitesDisponibles,
            'proveedor' => $proveedor,
        ];
    }

    /**
     * Valida el acceso a un trámite específico
     */
    public function validarAccesoTramite(string $tipo, ?Proveedor $proveedor): bool
    {
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);

        return $tramitesDisponibles[$tipo] ?? false;
    }

    /**
     * Obtiene los datos para la vista de constancia
     */
    public function getDatosConstancia(string $tipo, ?Proveedor $proveedor): array
    {
        return [
            'tipo' => $tipo,
            'proveedor' => $proveedor,
        ];
    }

    /**
     * Procesa los datos de la constancia SAT y los guarda en sesión
     */
    public function procesarDatosConstancia(Request $request): void
    {
        Session::put([
            'sat_rfc' => $request->sat_rfc,
            'sat_nombre' => $request->sat_nombre,
            'sat_tipo_persona' => $request->sat_tipo_persona,
            'sat_curp' => $request->sat_curp,
            'sat_cp' => $request->sat_cp,
            'sat_colonia' => $request->sat_colonia,
            'sat_nombre_vialidad' => $request->sat_nombre_vialidad,
            'sat_numero_exterior' => $request->sat_numero_exterior,
            'sat_numero_interior' => $request->sat_numero_interior,
        ]);
    }

    /**
     * Obtiene los datos del SAT desde la sesión
     */
    public function getDatosSatDeSesion(): array
    {
        return [
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

    /**
     * Obtiene los datos completos para el formulario de trámite
     */
    public function getDatosFormulario(string $tipo, ?Proveedor $proveedor): array
    {
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);
        $datosSat = $this->getDatosSatDeSesion();

        return [
            'tipo_tramite' => $tipo,
            'proveedor' => $proveedor,
            'tramites' => $tramitesDisponibles,
            'titulo' => $this->getTituloTramite($tipo),
            'descripcion' => $this->getDescripcionTramite($tipo),
            'datosSat' => $datosSat,
        ];
    }

    /**
     * Procesa el envío del formulario de trámite - VERSIÓN SIMPLIFICADA PARA TESTING
     */
    public function procesarEnvioFormulario(TramiteFormularioRequest $request, string $tipo, ?Proveedor $proveedor): array
    {
        try {
            Log::info('Procesando envío de formulario', [
                'tipo' => $tipo,
                'usuario_id' => Auth::id(),
                'datos_keys' => array_keys($request->all())
            ]);

            DB::beginTransaction();

            $datos = $request->all();
            
            // Debug: Log de todos los datos recibidos
            Log::info('Datos completos recibidos', [
                'datos_generales' => [
                    'razon_social' => $datos['razon_social'] ?? 'NO ENVIADO',
                    'rfc' => $datos['rfc'] ?? 'NO ENVIADO',
                    'tipo_persona' => $datos['tipo_persona'] ?? 'NO ENVIADO',
                    'curp' => $datos['curp'] ?? 'NO ENVIADO',
                    'pagina_web' => $datos['pagina_web'] ?? 'NO ENVIADO',
                    'cargo' => $datos['cargo'] ?? 'NO ENVIADO',
                    'email_contacto' => $datos['email_contacto'] ?? 'NO ENVIADO',
                    'telefono' => $datos['telefono'] ?? 'NO ENVIADO',
                ],
                'domicilio' => [
                    'codigo_postal' => $datos['codigo_postal'] ?? 'NO ENVIADO',
                    'estado_id' => $datos['estado_id'] ?? 'NO ENVIADO',
                    'municipio' => $datos['municipio'] ?? 'NO ENVIADO',
                    'municipio_id' => $datos['municipio_id'] ?? 'NO ENVIADO',
                    'asentamiento' => $datos['asentamiento'] ?? 'NO ENVIADO',
                    'tipo_asentamiento' => $datos['tipo_asentamiento'] ?? 'NO ENVIADO',
                    'calle' => $datos['calle'] ?? 'NO ENVIADO',
                    'numero_exterior' => $datos['numero_exterior'] ?? 'NO ENVIADO',
                    'numero_interior' => $datos['numero_interior'] ?? 'NO ENVIADO',
                ],
                'constitutivos' => [
                    'numero_escritura' => $datos['numero_escritura'] ?? 'NO ENVIADO',
                    'fecha_constitucion' => $datos['fecha_constitucion'] ?? 'NO ENVIADO',
                    'notario_nombre' => $datos['notario_nombre'] ?? 'NO ENVIADO',
                    'entidad_federativa' => $datos['entidad_federativa'] ?? 'NO ENVIADO',
                    'notario_numero' => $datos['notario_numero'] ?? 'NO ENVIADO',
                    'numero_registro' => $datos['numero_registro'] ?? 'NO ENVIADO',
                    'fecha_inscripcion' => $datos['fecha_inscripcion'] ?? 'NO ENVIADO',
                ],
                'accionistas' => $datos['accionistas'] ?? 'NO ENVIADOS',
                'actividades' => $datos['actividades'] ?? 'NO ENVIADAS',
                'confirma_datos' => $datos['confirma_datos'] ?? 'NO ENVIADO',
            ]);

            // Log adicional de debugging específico
            Log::info('Debug específico de campos problemáticos', [
                'total_keys' => count($datos),
                'all_keys' => array_keys($datos),
                'actividades_count' => is_array($datos['actividades'] ?? null) ? count($datos['actividades']) : 'NOT_ARRAY',
                'files_received' => count($request->allFiles()),
                'request_method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
            ]);

            if (!$proveedor) {
                $proveedor = $this->crearProveedorSimple($datos);
                Log::info('Proveedor creado', ['id' => $proveedor->id]);
            }

            $tramite = $this->crearTramite($tipo, $proveedor);
            Log::info('Trámite creado', ['id' => $tramite->id]);

            // Guardar datos generales (siempre se intenta guardar)
            $this->guardarDatosGeneralesSimple($tramite, $datos);
            Log::info('Datos generales guardados');

            // Guardar dirección si hay datos de domicilio
            if (!empty($datos['codigo_postal']) || !empty($datos['calle'])) {
                $this->guardarDireccionSimple($tramite, $datos);
                Log::info('Dirección guardada');
            }

            // Guardar contacto si hay datos de contacto
            if (!empty($datos['email_contacto']) || !empty($datos['telefono'])) {
                $this->guardarContactoSimple($tramite, $datos);
                Log::info('Contacto guardado');
            }

            // Guardar actividades si se enviaron
            if (!empty($datos['actividades']) && is_array($datos['actividades'])) {
                $this->guardarActividadesSimple($tramite, $datos['actividades']);
                Log::info('Actividades procesadas', ['count' => count($datos['actividades'])]);
            } else {
                Log::warning('No se encontraron actividades en los datos', [
                    'actividades_value' => $datos['actividades'] ?? 'NULL',
                    'actividades_type' => gettype($datos['actividades'] ?? null)
                ]);
            }

            // Determinar si es persona moral para procesar datos constitutivos
            $tipoPersona = 'Física';
            if (!empty($datos['tipo_persona']) && $datos['tipo_persona'] === 'Moral') {
                $tipoPersona = 'Moral';
            } elseif (!empty($datos['rfc']) && strlen(trim($datos['rfc'])) === 12) {
                $tipoPersona = 'Moral';
            }

            Log::info('Tipo de persona determinado', [
                'tipo_persona' => $tipoPersona,
                'tipo_persona_campo' => $datos['tipo_persona'] ?? 'NO ENVIADO',
                'rfc' => $datos['rfc'] ?? 'NO ENVIADO',
                'rfc_length' => !empty($datos['rfc']) ? strlen(trim($datos['rfc'])) : 0
            ]);

            // Procesar datos constitutivos solo para persona moral
            if ($tipoPersona === 'Moral') {
                Log::info('Procesando datos de PERSONA MORAL', [
                    'numero_escritura' => $datos['numero_escritura'] ?? 'NO ENVIADO',
                    'notario_nombre' => $datos['notario_nombre'] ?? 'NO ENVIADO',
                    'accionistas_count' => !empty($datos['accionistas']) ? count($datos['accionistas']) : 0
                ]);

                // Guardar instrumento notarial si se enviaron datos
                if (!empty($datos['numero_escritura']) || !empty($datos['notario_nombre'])) {
                    $instrumentoNotarial = $this->guardarInstrumentoNotarialSimple($datos);
                    if ($instrumentoNotarial) {
                        $this->guardarDatosConstitutivosSimple($tramite, $instrumentoNotarial);
                        Log::info('Instrumento notarial y datos constitutivos guardados');
                        
                        // 🎯 NUEVO: Guardar apoderado legal si se enviaron datos
                        if (!empty($datos['apoderado_nombre']) || !empty($datos['apoderado_rfc'])) {
                            $this->guardarApoderadoLegalSimple($tramite, $instrumentoNotarial, $datos);
                            Log::info('Apoderado legal guardado');
                        } else {
                            Log::warning('No se enviaron datos del apoderado legal', [
                                'apoderado_nombre' => $datos['apoderado_nombre'] ?? 'NULL',
                                'apoderado_rfc' => $datos['apoderado_rfc'] ?? 'NULL',
                                'poder_numero_escritura' => $datos['poder_numero_escritura'] ?? 'NULL',
                                'poder_notario_nombre' => $datos['poder_notario_nombre'] ?? 'NULL',
                            ]);
                        }
                    }
                } else {
                    Log::warning('No se enviaron datos constitutivos para persona moral', [
                        'campos_constitutivos' => [
                            'numero_escritura' => $datos['numero_escritura'] ?? 'NULL',
                            'notario_nombre' => $datos['notario_nombre'] ?? 'NULL',
                            'fecha_constitucion' => $datos['fecha_constitucion'] ?? 'NULL',
                            'entidad_federativa' => $datos['entidad_federativa'] ?? 'NULL',
                        ]
                    ]);
                }

                // Guardar accionistas si se enviaron datos
                if (!empty($datos['accionistas']) && is_array($datos['accionistas'])) {
                    $this->guardarAccionistasSimple($tramite, $datos['accionistas']);
                    Log::info('Accionistas procesados', ['count' => count($datos['accionistas'])]);
                } else {
                    Log::warning('No se enviaron accionistas para persona moral', [
                        'accionistas_value' => $datos['accionistas'] ?? 'NULL',
                        'accionistas_type' => gettype($datos['accionistas'] ?? null)
                    ]);
                }
            } else {
                Log::info('Procesando datos de PERSONA FÍSICA - Saltando datos constitutivos y accionistas');
            }

            // Guardar documentos si se subieron archivos
            if (!empty($request->allFiles())) {
                $this->guardarDocumentosSimple($tramite, $request->allFiles());
                Log::info('Documentos guardados', ['count' => count($request->allFiles())]);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => $this->getMensajeExito($tipo),
                'tramite_id' => $tramite->id,
                'redirect' => route('tramites.exito')
            ];

        } catch (\Exception $e) {
            DB::rollBack();
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
    }

    /**
     * Métodos simplificados para testing
     */
    private function guardarDatosGeneralesSimple(Tramite $tramite, array $datos): void
    {
        DatosGenerales::create([
            'tramite_id' => $tramite->id,
            'curp' => $datos['curp'] ?? null,
            'razon_social' => $datos['razon_social'] ?? 'Razón Social Default',
            'pagina_web' => $datos['pagina_web'] ?? null,
            'telefono' => $datos['telefono'] ?? null,
        ]);
    }

    private function guardarDireccionSimple(Tramite $tramite, array $datos): void
    {
        // Convertir estado_id a entero o usar valor por defecto
        $estadoId = 1; // Default: primer estado
        if (!empty($datos['estado_id']) && is_numeric($datos['estado_id'])) {
            $estadoId = (int) $datos['estado_id'];
        }
        
        // Si estado_id es texto (nombre del estado), buscar el ID
        if (!empty($datos['estado_id']) && !is_numeric($datos['estado_id'])) {
            // Mapeo básico de estados comunes
            $estadosMap = [
                'Puebla' => 21,
                'Ciudad de México' => 9,
                'CDMX' => 9,
                'Jalisco' => 14,
                'Nuevo León' => 19,
                'Oaxaca' => 20,
                'Veracruz' => 30,
                // Agregar más según necesidades
            ];
            $estadoId = $estadosMap[$datos['estado_id']] ?? 1;
        }
        
        Log::info('Guardando dirección completa', [
            'tramite_id' => $tramite->id,
            'datos_recibidos' => array_keys($datos)
        ]);
        
        Direccion::create([
            'id_tramite' => $tramite->id,
            'calle' => $datos['calle'] ?? 'Calle Default',
            'entre_calles' => $datos['entre_calles'] ?? null,
            'numero_exterior' => $datos['numero_exterior'] ?? 'S/N',
            'numero_interior' => $datos['numero_interior'] ?? null,
            'codigo_postal' => $datos['codigo_postal'] ?? '00000',
            'colonia_asentamiento' => $datos['asentamiento'] ?? $datos['asentamiento_select'] ?? 'Asentamiento Default',
            'municipio' => $datos['municipio'] ?? 'Municipio Default',
            'id_estado' => $estadoId,
            'tipo_asentamiento' => $datos['tipo_asentamiento'] ?? null,
        ]);
    }

    private function guardarContactoSimple(Tramite $tramite, array $datos): void
    {
        Contacto::create([
            'tramite_id' => $tramite->id,
            'nombre_contacto' => $datos['razon_social'] ?? 'Contacto Default',
            'cargo' => $datos['cargo'] ?? 'Representante',
            'correo_electronico' => $datos['email_contacto'] ?? 'default@email.com',
            'telefono' => $datos['telefono'] ?? '000-000-0000',
        ]);
    }

    private function crearProveedorSimple(array $datos): Proveedor
    {
        // Usar el RFC del formulario si está disponible, sino generar uno temporal corto
        $rfc = null;
        
        if (!empty($datos['rfc']) && strlen($datos['rfc']) >= 10) {
            // Usar RFC del formulario si es válido
            $rfc = strtoupper(trim($datos['rfc']));
        } else {
            // Generar RFC temporal corto (máximo 12 caracteres)
            $timestamp = substr((string) time(), -6); // Solo últimos 6 dígitos
            $rfc = 'TEMP' . $timestamp; // TEMP123456 = 10 caracteres máximo
        }
        
        // Determinar tipo de persona por RFC
        $tipoPersona = 'Física';
        if (!empty($rfc) && strlen($rfc) === 12) {
            $tipoPersona = 'Moral';
        }
        
        Log::info('Creando proveedor', [
            'rfc' => $rfc,
            'rfc_length' => strlen($rfc),
            'tipo_persona' => $tipoPersona,
            'rfc_origen' => !empty($datos['rfc']) ? 'formulario' : 'temporal'
        ]);
        
        return Proveedor::create([
            'usuario_id' => Auth::id(),
            'rfc' => $rfc,
            'razon_social' => $datos['razon_social'] ?? 'Razón Social Default',
            'tipo_persona' => $tipoPersona,
            'estado_padron' => 'Pendiente',
            'fecha_alta_padron' => now()->toDateString(),
        ]);
    }

    private function guardarActividadesSimple(Tramite $tramite, array $actividades): void
    {
        Log::info('Procesando actividades', [
            'tramite_id' => $tramite->id,
            'actividades_recibidas' => $actividades,
            'count' => count($actividades)
        ]);

        $actividadesIds = [];
        
        foreach ($actividades as $actividadId) {
            if (!empty($actividadId) && is_numeric($actividadId)) {
                $actividadesIds[] = (int) $actividadId;
            }
        }

        if (!empty($actividadesIds)) {
            // Usar la relación belongsToMany para insertar en la tabla pivot 'actividades'
            $tramite->actividades()->attach($actividadesIds);
            
            Log::info('Actividades guardadas exitosamente', [
                'tramite_id' => $tramite->id,
                'actividades_ids' => $actividadesIds,
                'total_guardadas' => count($actividadesIds)
            ]);
        } else {
            Log::warning('No se encontraron actividades válidas para guardar', [
                'tramite_id' => $tramite->id,
                'actividades_originales' => $actividades
            ]);
        }
    }

    private function guardarInstrumentoNotarialSimple(array $datos): ?InstrumentoNotarial
    {
        // Verificar si hay datos constitutivos
        if (empty($datos['numero_escritura']) && empty($datos['notario_nombre'])) {
            Log::info('No hay datos constitutivos para procesar');
            return null;
        }

        Log::info('Procesando instrumento notarial', [
            'numero_escritura' => $datos['numero_escritura'] ?? 'NO ENVIADO',
            'notario_nombre' => $datos['notario_nombre'] ?? 'NO ENVIADO',
            'fecha_constitucion' => $datos['fecha_constitucion'] ?? 'NO ENVIADO'
        ]);

        try {
            $instrumentoNotarial = InstrumentoNotarial::create([
                'numero_escritura' => $datos['numero_escritura'] ?? 'N/A',
                'numero_escritura_constitutiva' => $datos['numero_escritura'] ?? 'N/A', // Usar el mismo número
                'fecha_constitucion' => $datos['fecha_constitucion'] ?? now()->toDateString(),
                'nombre_notario' => $datos['notario_nombre'] ?? 'Notario Default',
                'entidad_federativa' => $datos['entidad_federativa'] ?? 'No especificado',
                'numero_notario' => !empty($datos['notario_numero']) ? (int) $datos['notario_numero'] : 1,
                'numero_registro_publico' => $datos['numero_registro'] ?? 'N/A',
                'fecha_inscripcion' => $datos['fecha_inscripcion'] ?? now()->toDateString(),
            ]);

            Log::info('Instrumento notarial creado exitosamente', [
                'id' => $instrumentoNotarial->id,
                'numero_escritura' => $instrumentoNotarial->numero_escritura
            ]);

            return $instrumentoNotarial;

        } catch (\Exception $e) {
            Log::error('Error al crear instrumento notarial', [
                'error' => $e->getMessage(),
                'datos' => $datos
            ]);
            return null;
        }
    }

    private function guardarDatosConstitutivosSimple(Tramite $tramite, InstrumentoNotarial $instrumentoNotarial): void
    {
        try {
            DatosConstitutivos::create([
                'tramite_id' => $tramite->id,
                'instrumento_notarial_id' => $instrumentoNotarial->id,
            ]);

            Log::info('Datos constitutivos guardados exitosamente', [
                'tramite_id' => $tramite->id,
                'instrumento_notarial_id' => $instrumentoNotarial->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al guardar datos constitutivos', [
                'error' => $e->getMessage(),
                'tramite_id' => $tramite->id,
                'instrumento_notarial_id' => $instrumentoNotarial->id
            ]);
        }
    }

    private function guardarAccionistasSimple(Tramite $tramite, array $accionistas): void
    {
        if (empty($accionistas) || !is_array($accionistas)) {
            Log::info('No hay datos de accionistas para procesar');
            return;
        }

        Log::info('Procesando accionistas', [
            'tramite_id' => $tramite->id,
            'accionistas_recibidos' => $accionistas,
            'count' => count($accionistas)
        ]);

        $accionistasCreados = 0;

        foreach ($accionistas as $index => $accionista) {
            if (empty($accionista['nombre']) && empty($accionista['rfc'])) {
                Log::info("Accionista #{$index} vacío, saltando");
                continue;
            }

            try {
                Accionista::create([
                    'tramite_id' => $tramite->id,
                    'nombre_completo' => $accionista['nombre'] ?? "Accionista #{$index}",
                    'rfc' => $accionista['rfc'] ?? null,
                    'porcentaje_participacion' => !empty($accionista['porcentaje']) ? (float) $accionista['porcentaje'] : 0.00,
                ]);

                $accionistasCreados++;

                Log::info("Accionista #{$index} guardado exitosamente", [
                    'nombre' => $accionista['nombre'] ?? "Accionista #{$index}",
                    'rfc' => $accionista['rfc'] ?? 'SIN RFC',
                    'porcentaje' => $accionista['porcentaje'] ?? '0.00'
                ]);

            } catch (\Exception $e) {
                Log::error("Error al guardar accionista #{$index}", [
                    'error' => $e->getMessage(),
                    'accionista_data' => $accionista
                ]);
            }
        }

        Log::info('Procesamiento de accionistas completado', [
            'tramite_id' => $tramite->id,
            'total_procesados' => count($accionistas),
            'total_guardados' => $accionistasCreados
        ]);
    }

    /**
     * 🎯 NUEVO: Guarda el apoderado legal simplificado para testing
     */
    private function guardarApoderadoLegalSimple(Tramite $tramite, InstrumentoNotarial $instrumentoNotarial, array $datos): void
    {
        // Verificar si hay datos del apoderado
        if (empty($datos['apoderado_nombre']) && empty($datos['apoderado_rfc'])) {
            Log::info('No hay datos del apoderado legal para procesar');
            return;
        }

        Log::info('Procesando apoderado legal', [
            'tramite_id' => $tramite->id,
            'instrumento_notarial_id' => $instrumentoNotarial->id,
            'apoderado_nombre' => $datos['apoderado_nombre'] ?? 'NO ENVIADO',
            'apoderado_rfc' => $datos['apoderado_rfc'] ?? 'NO ENVIADO',
            'poder_numero_escritura' => $datos['poder_numero_escritura'] ?? 'NO ENVIADO',
            'poder_notario_nombre' => $datos['poder_notario_nombre'] ?? 'NO ENVIADO'
        ]);

        try {
            // Determinar si usar el instrumento notarial existente o crear uno para el poder
            $instrumentoNotarialPoder = $instrumentoNotarial;
            
            // Si se enviaron datos específicos del poder notarial, crear un nuevo instrumento
            if (!empty($datos['poder_numero_escritura']) && $datos['poder_numero_escritura'] !== $datos['numero_escritura']) {
                Log::info('Creando instrumento notarial específico para el poder');
                
                $instrumentoNotarialPoder = InstrumentoNotarial::create([
                    'numero_escritura' => $datos['poder_numero_escritura'] ?? 'N/A',
                    'numero_escritura_constitutiva' => $datos['poder_numero_escritura'] ?? 'N/A',
                    'fecha_constitucion' => $datos['poder_fecha_constitucion'] ?? now()->toDateString(),
                    'nombre_notario' => $datos['poder_notario_nombre'] ?? 'Notario Default',
                    'entidad_federativa' => $datos['poder_entidad_federativa'] ?? 'No especificado',
                    'numero_notario' => !empty($datos['poder_notario_numero']) ? (int) $datos['poder_notario_numero'] : 1,
                    'numero_registro_publico' => $datos['poder_numero_registro'] ?? 'N/A',
                    'fecha_inscripcion' => $datos['poder_fecha_inscripcion'] ?? now()->toDateString(),
                ]);
                
                Log::info('Instrumento notarial del poder creado', [
                    'id' => $instrumentoNotarialPoder->id,
                    'numero_escritura' => $instrumentoNotarialPoder->numero_escritura
                ]);
            }

            // Crear el apoderado legal
            $apoderadoLegal = ApoderadoLegal::create([
                'tramite_id' => $tramite->id,
                'instrumento_notarial_id' => $instrumentoNotarialPoder->id,
                'nombre_apoderado' => $datos['apoderado_nombre'] ?? 'Apoderado Default',
                'rfc' => $datos['apoderado_rfc'] ?? null,
            ]);

            Log::info('Apoderado legal creado exitosamente', [
                'id' => $apoderadoLegal->id,
                'tramite_id' => $tramite->id,
                'nombre_apoderado' => $apoderadoLegal->nombre_apoderado,
                'rfc' => $apoderadoLegal->rfc,
                'instrumento_notarial_id' => $instrumentoNotarialPoder->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error al guardar apoderado legal', [
                'error' => $e->getMessage(),
                'tramite_id' => $tramite->id,
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'datos_apoderado' => [
                    'apoderado_nombre' => $datos['apoderado_nombre'] ?? 'NULL',
                    'apoderado_rfc' => $datos['apoderado_rfc'] ?? 'NULL',
                ]
            ]);
        }
    }

    private function guardarDocumentosSimple(Tramite $tramite, array $archivos): void
    {
        foreach ($archivos as $campo => $archivo) {
            if (is_array($archivo)) {
                foreach ($archivo as $index => $archivoIndividual) {
                    $this->procesarArchivoIndividual($tramite, $campo, $archivoIndividual, $index);
                }
            } else {
                $this->procesarArchivoIndividual($tramite, $campo, $archivo);
            }
        }
    }

    private function procesarArchivoIndividual(Tramite $tramite, string $campo, $archivo, int $index = null): void
    {
        if ($archivo && method_exists($archivo, 'isValid') && $archivo->isValid()) {
            try {
                preg_match('/documentos\[(\d+)\]/', $campo, $matches);
                $catalogoId = $matches[1] ?? null;
                
                $nombreCarpeta = 'documentos/' . $tramite->id;
                if ($index !== null) {
                    $nombreCarpeta .= '/' . $catalogoId . '_' . $index;
                }
                
                $rutaArchivo = $archivo->store($nombreCarpeta, 'public');
                
                \App\Models\Archivo::create([
                    'tramite_id' => $tramite->id,
                    'idCatalogoArchivo' => $catalogoId,
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'ruta_archivo' => $rutaArchivo,
                ]);
                
            } catch (\Exception $e) {
                Log::warning('Error al guardar documento', [
                    'tramite_id' => $tramite->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Crea el trámite principal
     */
    private function crearTramite(string $tipo, ?Proveedor $proveedor): Tramite
    {
        return Tramite::create([
            'proveedor_id' => $proveedor?->id,
            'tipo_tramite' => ucfirst($tipo),
            'estado' => 'Pendiente',
            'fecha_inicio' => now(),
            'paso_actual' => 1,
            'revisado_por' => 1, // Usuario administrador por defecto
        ]);
    }

    /**
     * Guarda los datos generales del trámite
     */
    private function guardarDatosGenerales(Tramite $tramite, array $datos): void
    {
        DatosGenerales::create([
            'tramite_id' => $tramite->id,
            'curp' => $datos['curp'],
            'razon_social' => $datos['razon_social'],
            'pagina_web' => $datos['pagina_web'],
            'telefono' => $datos['telefono'],
        ]);
    }

    /**
     * Guarda la dirección del trámite
     */
    private function guardarDireccion(Tramite $tramite, array $datos): void
    {
        Direccion::create([
            'id_tramite' => $tramite->id,
            'calle' => $datos['calle'],
            'entre_calles' => $datos['entre_calles'],
            'numero_exterior' => $datos['numero_exterior'],
            'numero_interior' => $datos['numero_interior'],
            'codigo_postal' => $datos['codigo_postal'],
            'colonia_asentamiento' => $datos['colonia_asentamiento'],
            'municipio' => $datos['municipio'],
            'id_estado' => $datos['id_estado'],
            'es_principal' => true,
            'activo' => true,
        ]);
    }

    /**
     * Guarda el contacto del trámite
     */
    private function guardarContacto(Tramite $tramite, array $datos): void
    {
        Contacto::create([
            'tramite_id' => $tramite->id,
            'nombre_contacto' => $datos['nombre_contacto'],
            'cargo' => $datos['cargo'],
            'correo_electronico' => $datos['correo_electronico'],
            'telefono' => $datos['telefono'],
        ]);
    }

    /**
     * Guarda las actividades económicas del trámite
     */
    private function guardarActividades(Tramite $tramite, array $actividadesIds): void
    {
        if (!empty($actividadesIds)) {
            $tramite->actividades()->attach($actividadesIds);
        }
    }

    /**
     * Guarda el instrumento notarial
     */
    private function guardarInstrumentoNotarial(array $datos): InstrumentoNotarial
    {
        return InstrumentoNotarial::create([
            'numero_escritura' => $datos['numero_escritura'],
            'numero_escritura_constitutiva' => $datos['numero_escritura_constitutiva'],
            'fecha_constitucion' => $datos['fecha_constitucion'],
            'nombre_notario' => $datos['nombre_notario'],
            'entidad_federativa' => $datos['entidad_federativa'],
            'numero_notario' => $datos['numero_notario'],
            'numero_registro_publico' => $datos['numero_registro_publico'],
            'fecha_inscripcion' => $datos['fecha_inscripcion'],
        ]);
    }

    /**
     * Guarda los datos constitutivos
     */
    private function guardarDatosConstitutivos(Tramite $tramite, InstrumentoNotarial $instrumentoNotarial): void
    {
        DatosConstitutivos::create([
            'tramite_id' => $tramite->id,
            'instrumento_notarial_id' => $instrumentoNotarial->id,
        ]);
    }

    /**
     * Guarda el apoderado legal
     */
    private function guardarApoderadoLegal(Tramite $tramite, InstrumentoNotarial $instrumentoNotarial, array $datos): void
    {
        ApoderadoLegal::create([
            'tramite_id' => $tramite->id,
            'instrumento_notarial_id' => $instrumentoNotarial->id,
            'nombre_apoderado' => $datos['nombre_apoderado'],
            'rfc' => $datos['rfc'],
        ]);
    }

    /**
     * Guarda los accionistas
     */
    private function guardarAccionistas(Tramite $tramite, array $accionistas): void
    {
        foreach ($accionistas as $accionista) {
            Accionista::create([
                'tramite_id' => $tramite->id,
                'nombre_completo' => $accionista['nombre'],
                'rfc' => $accionista['rfc'],
                'porcentaje_participacion' => $accionista['porcentaje'],
                'activo' => true,
            ]);
        }
    }

    /**
     * Guarda los archivos adjuntos
     */
    private function guardarArchivos(Tramite $tramite, array $archivos): void
    {
        foreach ($archivos as $catalogoId => $archivo) {
            if ($archivo && is_uploaded_file($archivo->getRealPath())) {
                $nombreOriginal = $archivo->getClientOriginalName();
                $rutaArchivo = $archivo->store("tramites/{$tramite->id}", 'public');

                Archivo::create([
                    'tramite_id' => $tramite->id,
                    'nombre_original' => $nombreOriginal,
                    'ruta_archivo' => $rutaArchivo,
                    'idCatalogoArchivo' => $catalogoId,
                    'aprobado' => false,
                ]);
            }
        }
    }

    /**
     * Actualiza o crea el proveedor si es necesario
     */
    private function actualizarProveedor(?Proveedor $proveedor, Tramite $tramite, array $datosGenerales): void
    {
        if (!$proveedor) {
            // Crear nuevo proveedor
            $nuevoProveedor = Proveedor::create([
                'usuario_id' => Auth::id(),
                'rfc' => $datosGenerales['rfc'],
                'tipo_persona' => $this->esPersonaMoral($datosGenerales['rfc']) ? 'Moral' : 'Física',
                'estado_padron' => 'Pendiente',
                'fecha_alta_padron' => now()->toDateString(),
            ]);

            // Actualizar el trámite con el proveedor
            $tramite->update(['proveedor_id' => $nuevoProveedor->id]);
        }
    }

    /**
     * Determina si es persona moral basado en la longitud del RFC
     */
    private function esPersonaMoral(string $rfc): bool
    {
        return strlen($rfc) === 12;
    }

    /**
     * Obtiene el mensaje de éxito según el tipo de trámite
     */
    private function getMensajeExito(string $tipo): string
    {
        $mensajes = [
            'inscripcion' => 'Su solicitud de inscripción ha sido enviada correctamente. Recibirá una notificación cuando sea revisada.',
            'renovacion' => 'Su solicitud de renovación ha sido procesada exitosamente.',
            'actualizacion' => 'Sus datos han sido actualizados correctamente.',
        ];

        return $mensajes[$tipo] ?? 'Trámite procesado exitosamente.';
    }

    /**
     * Obtiene el título según el tipo de trámite
     */
    public function getTituloTramite(string $tipo): string
    {
        $titulos = [
            'inscripcion' => 'Inscripción al Padrón de Proveedores',
            'renovacion' => 'Renovación de Registro',
            'actualizacion' => 'Actualización de Datos',
        ];

        return $titulos[$tipo] ?? 'Formulario de Trámite';
    }

    /**
     * Obtiene la descripción según el tipo de trámite
     */
    public function getDescripcionTramite(string $tipo): string
    {
        $descripciones = [
            'inscripcion' => 'Complete la información para registrarse como proveedor del gobierno.',
            'renovacion' => 'Actualice y renueve su registro en el padrón de proveedores.',
            'actualizacion' => 'Modifique los datos de su registro existente.',
        ];

        return $descripciones[$tipo] ?? 'Procese su trámite completando el formulario.';
    }

    /**
     * Limpia los datos de sesión después del procesamiento
     */
    public function limpiarDatosSesion(): void
    {
        $keys = [
            'sat_rfc', 'sat_nombre', 'sat_tipo_persona', 'sat_curp',
            'sat_cp', 'sat_colonia', 'sat_nombre_vialidad',
            'sat_numero_exterior', 'sat_numero_interior',
        ];

        foreach ($keys as $key) {
            Session::forget($key);
        }
    }
}
