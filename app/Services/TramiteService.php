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
        
        if (app()->environment('local', 'development')) {
            return in_array($tipo, ['inscripcion', 'renovacion', 'actualizacion']);
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
        $datosSat = $this->extraerDatosSat($request);
        $this->validarRfcConstancia($datosSat);
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

    public function limpiarDatosSesion(): void
    {
        foreach (self::CLAVES_SESION_SAT as $clave) {
            Session::forget($clave);
        }
    }

    public function getTituloTramite(string $tipo): string
    {
        $titulos = [
            'inscripcion' => 'Inscripción al Padrón de Proveedores',
            'renovacion' => 'Renovación de Registro',
            'actualizacion' => 'Actualización de Datos'
        ];

        return $titulos[$tipo] ?? 'Formulario de Trámite';
    }

    public function getDescripcionTramite(string $tipo): string
    {
        $descripciones = [
            'inscripcion' => 'Complete la información para registrarse como proveedor del gobierno.',
            'renovacion' => 'Actualice y renueve su registro en el padrón de proveedores.',
            'actualizacion' => 'Modifique los datos de su registro existente.'
        ];

        return $descripciones[$tipo] ?? 'Procese su trámite completando el formulario.';
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

    private function extraerDatosSat(Request $request): array
    {
        $datosSat = [];
        foreach (self::CLAVES_SESION_SAT as $clave) {
            $datosSat[$clave] = $request->input($clave);
        }
        return $datosSat;
    }

    private function validarRfcConstancia(array $datosSat): void
    {
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
    }

    private function asegurarProveedor(?Proveedor $proveedor, Request $request): Proveedor
    {
        if ($proveedor) {
            return $proveedor;
        }

        $rfc = $this->normalizarRfc($request->input('rfc', 'XAXX010101000'));
        $tipoPersona = $this->proveedorService->getTipoPersona(new Proveedor(['rfc' => $rfc]));

        return Proveedor::create([
            'usuario_id' => Auth::id(),
            'rfc' => $rfc,
            'tipo_persona' => $tipoPersona ?? 'Física',
            'estado_padron' => 'Pendiente',
            'fecha_alta_padron' => now()->toDateString(),
        ]);
    }

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

    private function procesarDatosTramite(Tramite $tramite, Request $request): void
    {
        $this->guardarDatosPrincipales($tramite, $request);
        
        if ($this->esPersonaMoral($request->input('rfc', 'XAXX010101000'))) {
            $this->datosConstitutivosService->procesar($tramite, $request);
        }
    }

    private function guardarDatosPrincipales(Tramite $tramite, Request $request): void
    {
        app(DatosGeneralesService::class)->guardar($tramite, $request);
        app(DireccionService::class)->guardar($tramite, $request);
        app(ContactoService::class)->guardar($tramite, $request);
        $this->procesarActividades($tramite, $request);
        app(DocumentosService::class)->guardar($tramite, $request);
    }
    
    private function procesarActividades(Tramite $tramite, Request $request): void
    {
        $actividades = $request->input('actividades', []);

        if (!empty($actividades)) {
            $actividadesService = new ActividadesService($request);
            $actividadesService->guardar($tramite, $request);
        }
    }

    private function procesarDatosTramiteCorreccion(Tramite $tramite, Request $request): void
    {
        $this->procesarCorreccionDatosGenerales($tramite, $request);
        $this->procesarCorreccionActividades($tramite, $request);
        $this->procesarCorreccionDomicilio($tramite, $request);
        $this->procesarCorreccionDatosConstitutivos($tramite, $request);
        $this->procesarCorreccionApoderado($tramite, $request);
        $this->procesarCorreccionAccionistas($tramite, $request);
        $this->procesarCorreccionDocumentos($tramite, $request);
    }

    private function procesarCorreccionDatosGenerales(Tramite $tramite, Request $request): void
    {
        if ($this->tieneDatosGenerales($request)) {
            $tramite->datosGenerales()->delete();
            $tramite->contactos()->delete();
            $this->datosGeneralesFormService->procesar($tramite, $request->all());
        }
    }

    private function procesarCorreccionActividades(Tramite $tramite, Request $request): void
    {
        if ($request->has('actividades') || $request->has('buscador_actividad')) {
            $tramite->actividades()->detach();
            $this->actividadesFormService->procesar($tramite, $request->all());
        }
    }

    private function procesarCorreccionDomicilio(Tramite $tramite, Request $request): void
    {
        if ($this->tieneDatosDomicilio($request)) {
            $tramite->direcciones()->delete();
            $this->direccionFormService->procesar($tramite, $request->all());
        }
    }

    private function procesarCorreccionDatosConstitutivos(Tramite $tramite, Request $request): void
    {
        if ($this->tieneDatosConstitutivos($request)) {
            $tramite->datosConstitutivos()->delete();
            $this->personaMoralFormService->procesar($tramite, $request->all());
        }
    }

    private function procesarCorreccionApoderado(Tramite $tramite, Request $request): void
    {
        if ($this->tieneDatosApoderado($request)) {
            $tramite->apoderadoLegal()->delete();
            $this->datosConstitutivosService->procesar($tramite, $request);
        }
    }

    private function procesarCorreccionAccionistas(Tramite $tramite, Request $request): void
    {
        if ($request->has('accionistas')) {
            $tramite->accionistas()->delete();
            $this->datosConstitutivosService->procesar($tramite, $request);
        }
    }

    private function procesarCorreccionDocumentos(Tramite $tramite, Request $request): void
    {
        if ($request->has('documentos')) {
            $this->documentosFormService->procesar($tramite, $request->all());
        }
    }

    private function tieneDatosGenerales(Request $request): bool
    {
        return $request->has('rfc') || $request->has('razon_social') || $request->has('pagina_web') || 
               $request->has('telefono') || $request->has('email_contacto') || $request->has('cargo');
    }

    private function tieneDatosDomicilio(Request $request): bool
    {
        return $request->has('codigo_postal') || $request->has('estado_id') || $request->has('municipio') || 
               $request->has('asentamiento') || $request->has('calle') || $request->has('numero_exterior');
    }

    private function tieneDatosConstitutivos(Request $request): bool
    {
        return $request->has('numero_escritura') || $request->has('fecha_constitucion') || 
               $request->has('notario_nombre') || $request->has('entidad_federativa');
    }

    private function tieneDatosApoderado(Request $request): bool
    {
        return $request->has('apoderado_nombre') || $request->has('apoderado_rfc') || 
               $request->has('poder_numero_escritura') || $request->has('poder_fecha_constitucion');
    }

    private function normalizarRfc(?string $rfc): string
    {
        if (!empty($rfc) && strlen($rfc) >= 10) {
            return strtoupper(trim($rfc));
        }

        return 'TEMP' . substr((string) time(), -6);
    }

    private function esPersonaMoral(string $rfc): bool
    {
        $tipoPersona = $this->proveedorService->getTipoPersona(new Proveedor(['rfc' => $rfc]));
        return $tipoPersona === 'Moral';
    }
}
