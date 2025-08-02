<?php

declare(strict_types=1);

namespace App\Services\Tramites;

use App\Models\Tramite;
use App\Services\DatosGeneralesService;
use App\Services\DireccionService;
use App\Services\ContactoService;
use App\Services\ActividadesService;
use App\Services\DocumentosService;
use App\Services\DatosConstitutivosService;
use App\Services\Formularios\DatosGeneralesFormService;
use App\Services\Formularios\DocumentosFormService;
use App\Services\Formularios\PersonaMoralFormService;
use App\Services\Formularios\DireccionFormService;
use App\Services\Formularios\ActividadesFormService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para procesamiento de formularios de trámites
 * Responsabilidad: Coordinar el procesamiento de datos de formularios de trámites
 */
class FormularioTramiteService
{
    public function __construct(
        private ValidacionTramiteService $validacionService,
        private DatosGeneralesFormService $datosGeneralesFormService,
        private DocumentosFormService $documentosFormService,
        private PersonaMoralFormService $personaMoralFormService,
        private DireccionFormService $direccionFormService,
        private ActividadesFormService $actividadesFormService,
        private DatosConstitutivosService $datosConstitutivosService,
    ) {}

    /**
     * Procesa todos los datos del formulario de trámite
     */
    public function procesarDatosTramite(Tramite $tramite, Request $request): void
    {
        Log::info('Iniciando procesamiento de datos del trámite', [
            'tramite_id' => $tramite->id
        ]);

        $this->guardarDatosPrincipales($tramite, $request);
        
        if ($this->validacionService->esPersonaMoral($request->input('rfc', 'XAXX010101000'))) {
            $this->datosConstitutivosService->procesar($tramite, $request);
        }

        Log::info('Procesamiento de datos completado', [
            'tramite_id' => $tramite->id
        ]);
    }

    /**
     * Procesa los datos para corrección de trámite
     */
    public function procesarDatosCorreccion(Tramite $tramite, Request $request): void
    {
        Log::info('Iniciando procesamiento de corrección', [
            'tramite_id' => $tramite->id
        ]);

        $this->procesarCorreccionDatosGenerales($tramite, $request);
        $this->procesarCorreccionActividades($tramite, $request);
        $this->procesarCorreccionDomicilio($tramite, $request);
        $this->procesarCorreccionDatosConstitutivos($tramite, $request);
        $this->procesarCorreccionApoderado($tramite, $request);
        $this->procesarCorreccionAccionistas($tramite, $request);
        $this->procesarCorreccionDocumentos($tramite, $request);

        Log::info('Procesamiento de corrección completado', [
            'tramite_id' => $tramite->id
        ]);
    }

    /**
     * Guarda los datos principales del trámite
     */
    private function guardarDatosPrincipales(Tramite $tramite, Request $request): void
    {
        app(DatosGeneralesService::class)->guardar($tramite, $request);
        app(DireccionService::class)->guardar($tramite, $request);
        app(ContactoService::class)->guardar($tramite, $request);
        $this->procesarActividades($tramite, $request);
        app(DocumentosService::class)->guardar($tramite, $request);
    }

    /**
     * Procesa las actividades del trámite
     */
    private function procesarActividades(Tramite $tramite, Request $request): void
    {
        $actividades = $request->input('actividades', []);

        if (!empty($actividades)) {
            $actividadesService = new ActividadesService($request);
            $actividadesService->guardar($tramite, $request);
        }
    }

    /**
     * Procesa corrección de datos generales
     */
    private function procesarCorreccionDatosGenerales(Tramite $tramite, Request $request): void
    {
        if ($this->tieneDatosGenerales($request)) {
            $tramite->datosGenerales()->delete();
            $tramite->contactos()->delete();
            $this->datosGeneralesFormService->procesar($tramite, $request->all());
        }
    }

    /**
     * Procesa corrección de actividades
     */
    private function procesarCorreccionActividades(Tramite $tramite, Request $request): void
    {
        if ($request->has('actividades') || $request->has('buscador_actividad')) {
            $tramite->actividades()->detach();
            $this->actividadesFormService->procesar($tramite, $request->all());
        }
    }

    /**
     * Procesa corrección de domicilio
     */
    private function procesarCorreccionDomicilio(Tramite $tramite, Request $request): void
    {
        if ($this->tieneDatosDomicilio($request)) {
            $tramite->direcciones()->delete();
            $this->direccionFormService->procesar($tramite, $request->all());
        }
    }

    /**
     * Procesa corrección de datos constitutivos
     */
    private function procesarCorreccionDatosConstitutivos(Tramite $tramite, Request $request): void
    {
        if ($this->tieneDatosConstitutivos($request)) {
            $tramite->datosConstitutivos()->delete();
            $this->personaMoralFormService->procesar($tramite, $request->all());
        }
    }

    /**
     * Procesa corrección de apoderado
     */
    private function procesarCorreccionApoderado(Tramite $tramite, Request $request): void
    {
        if ($this->tieneDatosApoderado($request)) {
            $tramite->apoderadoLegal()->delete();
            $this->datosConstitutivosService->procesar($tramite, $request);
        }
    }

    /**
     * Procesa corrección de accionistas
     */
    private function procesarCorreccionAccionistas(Tramite $tramite, Request $request): void
    {
        if ($request->has('accionistas')) {
            $tramite->accionistas()->delete();
            $this->datosConstitutivosService->procesar($tramite, $request);
        }
    }

    /**
     * Procesa corrección de documentos
     */
    private function procesarCorreccionDocumentos(Tramite $tramite, Request $request): void
    {
        if ($request->has('documentos')) {
            $this->documentosFormService->procesar($tramite, $request->all());
        }
    }

    /**
     * Verifica si la petición tiene datos generales
     */
    private function tieneDatosGenerales(Request $request): bool
    {
        return $request->has('rfc') || $request->has('razon_social') || $request->has('pagina_web') || 
               $request->has('telefono') || $request->has('email_contacto') || $request->has('cargo');
    }

    /**
     * Verifica si la petición tiene datos de domicilio
     */
    private function tieneDatosDomicilio(Request $request): bool
    {
        return $request->has('codigo_postal') || $request->has('estado_id') || $request->has('municipio') || 
               $request->has('asentamiento') || $request->has('calle') || $request->has('numero_exterior');
    }

    /**
     * Verifica si la petición tiene datos constitutivos
     */
    private function tieneDatosConstitutivos(Request $request): bool
    {
        return $request->has('numero_escritura') || $request->has('fecha_constitucion') || 
               $request->has('notario_nombre') || $request->has('entidad_federativa');
    }

    /**
     * Verifica si la petición tiene datos de apoderado
     */
    private function tieneDatosApoderado(Request $request): bool
    {
        return $request->has('apoderado_nombre') || $request->has('apoderado_rfc') || 
               $request->has('poder_numero_escritura') || $request->has('poder_fecha_constitucion');
    }
}