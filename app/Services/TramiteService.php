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
     * Procesa el envío del formulario de trámite - VERSIÓN REFACTORIZADA Y LIMPIA
     */
    public function procesarEnvioFormulario(TramiteFormularioRequest $request, string $tipo, ?Proveedor $proveedor): array
    {
        try {
            Log::info('Procesando envío de formulario', [
                'tipo' => $tipo,
                'usuario_id' => Auth::id(),
                'files_received' => count($request->allFiles())
            ]);

            DB::beginTransaction();

            // 1. Crear o usar proveedor existente
            if (!$proveedor) {
                $proveedor = $this->crearProveedorSimple($request->all());
                Log::info('Proveedor creado', ['id' => $proveedor->id]);
            }

            // 2. Crear trámite
            $tramite = $this->crearTramite($tipo, $proveedor);
            Log::info('Trámite creado', ['id' => $tramite->id]);

            // 3. Guardar datos usando servicios especializados
            app(DatosGeneralesService::class)->guardar($tramite, $request);
            app(DireccionService::class)->guardar($tramite, $request);
            app(ContactoService::class)->guardar($tramite, $request);
            app(ActividadesService::class)->guardar($tramite, $request);
            app(DocumentosService::class)->guardar($tramite, $request);

            // 4. Procesar datos específicos de Persona Moral
            if ($this->esPersonaMoral($request)) {
                $this->procesarPersonaMoral($tramite, $request);
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
     * Métodos simplificados ahora reemplazados por servicios especializados
     */

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

    /**
     * Procesa datos específicos de Persona Moral
     */
    private function procesarPersonaMoral(Tramite $tramite, TramiteFormularioRequest $request): void
    {
        Log::info('Procesando datos de PERSONA MORAL', ['tramite_id' => $tramite->id]);

        // Datos constitutivos e instrumento notarial
        if ($request->filled('numero_escritura') || $request->filled('notario_nombre')) {
            $instrumentoNotarial = $this->guardarInstrumentoNotarialSimple($request->all());
            if ($instrumentoNotarial) {
                $this->guardarDatosConstitutivosSimple($tramite, $instrumentoNotarial);
                
                // Apoderado legal
                if ($request->filled('apoderado_nombre') || $request->filled('apoderado_rfc')) {
                    $this->guardarApoderadoLegalSimple($tramite, $instrumentoNotarial, $request->all());
                }
            }
        }

        // Accionistas
        if ($request->filled('accionistas')) {
            $this->guardarAccionistasSimple($tramite, $request->input('accionistas', []));
        }
    }

    /**
     * Crea un proveedor simple basado en los datos del request
     */
    private function crearProveedorSimple(array $datos): Proveedor
    {
        $rfc = $this->procesarRfc($datos['rfc'] ?? null);
        $tipoPersona = strlen($rfc) === 12 ? 'Moral' : 'Física';
        
        Log::info('Creando proveedor', [
            'rfc' => $rfc,
            'tipo_persona' => $tipoPersona
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

    /**
     * Procesa y valida el RFC
     */
    private function procesarRfc(?string $rfc): string
    {
        if (!empty($rfc) && strlen($rfc) >= 10) {
            return strtoupper(trim($rfc));
        }
        
        // Generar RFC temporal si no se proporciona uno válido
        $timestamp = substr((string) time(), -6);
        return 'TEMP' . $timestamp;
    }
}
