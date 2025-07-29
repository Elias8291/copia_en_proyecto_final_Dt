<?php declare(strict_types=1);

namespace App\Services;

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
use Illuminate\Support\Facades\Session;

class TramiteService
{
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

    // Datos para página de índice
    public function getDatosTramitesIndex(?Proveedor $proveedor): array
    {
        return [
            'globalTramites' => $this->proveedorService->determinarTramitesDisponibles($proveedor),
            'proveedor' => $proveedor,
        ];
    }

    // Valida acceso a tipo de trámite
    public function validarAccesoTramite(string $tipo, ?Proveedor $proveedor): bool
    {
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);
        
        // Más permisivo en development
        if (app()->environment('local', 'development')) {
            if (in_array($tipo, ['inscripcion', 'renovacion', 'actualizacion'])) {
                return true;
            }
        }
        
        return $tramitesDisponibles[$tipo] ?? false;
    }

    // Datos para página de constancia
    public function getDatosConstancia(string $tipo, ?Proveedor $proveedor): array
    {
        return [
            'tipo' => $tipo,
            'proveedor' => $proveedor,
        ];
    }

    // Procesa datos de constancia SAT
    public function procesarDatosConstancia(Request $request): void
    {
        $datosSat = [];
        foreach (self::CLAVES_SESION_SAT as $clave) {
            $datosSat[$clave] = $request->input($clave);
        }

        // Validar RFC del usuario vs constancia
        $rfcUsuario = Auth::user()->rfc;
        $rfcConstancia = $datosSat['sat_rfc'] ?? null;

        if ($rfcUsuario && $rfcConstancia) {
            $rfcUsuarioNormalizado = strtoupper(trim($rfcUsuario));
            $rfcConstanciaNormalizado = strtoupper(trim($rfcConstancia));

            if ($rfcUsuarioNormalizado !== $rfcConstanciaNormalizado) {
                throw new \Exception('El RFC de la constancia fiscal no coincide con su RFC registrado. Verifique que esté cargando la constancia correcta.');
            }
        } else {
            if (!$rfcUsuario) {
                throw new \Exception('Su cuenta no tiene un RFC registrado. Contacte al administrador.');
            }
            if (!$rfcConstancia) {
                throw new \Exception('No se pudo extraer el RFC de la constancia. Verifique que el archivo sea válido.');
            }
        }

        Session::put($datosSat);
    }

    // Datos para formulario de trámite
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

    // Datos para corrección de trámite
    public function getDatosFormularioCorreccion(Tramite $tramite): array
    {
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

    // Procesa envío de formulario
    public function procesarEnvioFormulario(Request $request, string $tipo, ?Proveedor $proveedor): array
    {
        try {
            DB::beginTransaction();

            $proveedor = $this->asegurarProveedor($proveedor, $request);
            $tramite = $this->crearTramite($tipo, $proveedor);
            $this->procesarDatosTramite($tramite, $request);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Trámite procesado exitosamente.',
                'tramite_id' => $tramite->id,
                'redirect' => route('tramites.exito')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Error al procesar el trámite: ' . $e->getMessage()
            ];
        }
    }

    // Procesa corrección de trámite
    public function procesarCorreccionTramite(Request $request, Tramite $tramite): array
    {
        try {
            DB::beginTransaction();

            $tramite->update([
                'estado' => 'En_Revision',
                'observaciones' => 'Trámite reenviado con correcciones',
                'correcciones_count' => $tramite->correcciones_count + 1,
            ]);

            $this->procesarDatosTramiteCorreccion($tramite, $request);

            DB::commit();

            return [
                'success' => true,
                'tramite_id' => $tramite->id,
                'message' => 'Trámite corregido y reenviado exitosamente.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Error al procesar las correcciones: ' . $e->getMessage()
            ];
        }
    }

    // Limpia datos de sesión SAT
    public function limpiarDatosSesion(): void
    {
        foreach (self::CLAVES_SESION_SAT as $clave) {
            Session::forget($clave);
        }
    }

    // Obtiene título del trámite
    public function getTituloTramite(string $tipo): string
    {
        $titulos = [
            'inscripcion' => 'Inscripción al Padrón de Proveedores',
            'renovacion' => 'Renovación de Registro',
            'actualizacion' => 'Actualización de Datos'
        ];

        return $titulos[$tipo] ?? 'Formulario de Trámite';
    }

    // Obtiene descripción del trámite
    public function getDescripcionTramite(string $tipo): string
    {
        $descripciones = [
            'inscripcion' => 'Complete la información para registrarse como proveedor del gobierno.',
            'renovacion' => 'Actualice y renueve su registro en el padrón de proveedores.',
            'actualizacion' => 'Modifique los datos de su registro existente.'
        ];

        return $descripciones[$tipo] ?? 'Procese su trámite completando el formulario.';
    }

    // Obtiene datos SAT de sesión
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

    // Asegura que existe proveedor
    private function asegurarProveedor(?Proveedor $proveedor, Request $request): Proveedor
    {
        if ($proveedor) {
            return $proveedor;
        }

        $rfc = $this->normalizarRfc($request->input('rfc', 'XAXX010101000'));
        
        $proveedorTemporal = new Proveedor(['rfc' => $rfc]);
        $tipoPersona = $this->proveedorService->getTipoPersona($proveedorTemporal);

        return Proveedor::create([
            'usuario_id' => Auth::id(),
            'rfc' => $rfc,
            'tipo_persona' => $tipoPersona ?? 'Física',
            'estado_padron' => 'Pendiente',
            'fecha_alta_padron' => now()->toDateString(),
        ]);
    }

    // Crea nuevo trámite
    private function crearTramite(string $tipo, Proveedor $proveedor): Tramite
    {
        return Tramite::create([
            'proveedor_id' => $proveedor->id,
            'tipo_tramite' => ucfirst($tipo),
            'estado' => 'Pendiente',
            'fecha_inicio' => now(),
            'paso_actual' => 1,
            'revisado_por' => 1,
        ]);
    }

    // Procesa todos los datos del trámite
    private function procesarDatosTramite(Tramite $tramite, Request $request): void
    {
        $this->guardarDatosPrincipales($tramite, $request);
        
        if ($this->esPersonaMoral($request->input('rfc', 'XAXX010101000'))) {
            $this->procesarPersonaMoral($tramite, $request);
        }
    }

    // Guarda datos principales
    private function guardarDatosPrincipales(Tramite $tramite, Request $request): void
    {
        app(DatosGeneralesService::class)->guardar($tramite, $request);
        app(DireccionService::class)->guardar($tramite, $request);
        app(ContactoService::class)->guardar($tramite, $request);
        $this->procesarActividadesConTemporales($tramite, $request);
        app(DocumentosService::class)->guardar($tramite, $request);
    }
    
    // Procesa actividades con temporales
    private function procesarActividadesConTemporales(Tramite $tramite, Request $request): void
    {
        $actividades = $request->input('actividades', []);

        if (!empty($actividades)) {
            $actividadesService = new ActividadesService($request);
            $actividadesService->guardar($tramite, $request);
        }
    }

    // Procesa datos de persona moral
    private function procesarPersonaMoral(Tramite $tramite, Request $request): void
    {
        $this->datosConstitutivosService->procesar($tramite, $request);
    }

    // Procesa datos de corrección
    private function procesarDatosTramiteCorreccion(Tramite $tramite, Request $request): void
    {
        // Datos generales
        if ($request->has('rfc') || $request->has('razon_social') || $request->has('pagina_web') || 
            $request->has('telefono') || $request->has('email_contacto') || $request->has('cargo')) {
            $tramite->datosGenerales()->delete();
            $tramite->contactos()->delete();
            $this->datosGeneralesFormService->procesar($tramite, $request->all());
        }

        // Actividades
        if ($request->has('actividades') || $request->has('buscador_actividad')) {
            $tramite->actividades()->detach();
            $this->actividadesFormService->procesar($tramite, $request->all());
        }

        // Domicilio
        if ($request->has('codigo_postal') || $request->has('estado_id') || $request->has('municipio') || 
            $request->has('asentamiento') || $request->has('calle') || $request->has('numero_exterior')) {
            $tramite->direcciones()->delete();
            $this->direccionFormService->procesar($tramite, $request->all());
        }

        // Datos constitutivos
        if ($request->has('numero_escritura') || $request->has('fecha_constitucion') || 
            $request->has('notario_nombre') || $request->has('entidad_federativa')) {
            $tramite->datosConstitutivos()->delete();
            $this->personaMoralFormService->procesar($tramite, $request->all());
        }

        // Apoderado legal
        if ($request->has('apoderado_nombre') || $request->has('apoderado_rfc') || 
            $request->has('poder_numero_escritura') || $request->has('poder_fecha_constitucion')) {
            $tramite->apoderadoLegal()->delete();
            $this->datosConstitutivosService->procesar($tramite, $request);
        }

        // Accionistas
        if ($request->has('accionistas')) {
            $tramite->accionistas()->delete();
            $this->datosConstitutivosService->procesar($tramite, $request);
    }

        // Documentos
        if ($request->has('documentos')) {
            $this->documentosFormService->procesar($tramite, $request->all());
        }
    }

    // Normaliza RFC
    private function normalizarRfc(?string $rfc): string
    {
        if (!empty($rfc) && strlen($rfc) >= 10) {
            return strtoupper(trim($rfc));
        }

        return 'TEMP' . substr((string) time(), -6);
    }

    // Verifica si es persona moral
    private function esPersonaMoral(string $rfc): bool
    {
        $proveedorTemporal = new Proveedor(['rfc' => $rfc]);
        $tipoPersona = $this->proveedorService->getTipoPersona($proveedorTemporal);

        return $tipoPersona === 'Moral';
    }
}
