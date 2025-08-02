<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Proveedor;
use App\Models\Tramite;
use App\Services\Tramites\SesionSatService;
use App\Services\Tramites\ValidacionTramiteService;
use App\Services\Tramites\FormularioTramiteService;
use App\Services\Core\BaseDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Servicio principal para gestión de trámites
 * Responsabilidad: CRUD de trámites y coordinación de operaciones principales
 */
class TramiteService
{
    public function __construct(
        private ProveedorService $proveedorService,
        private SesionSatService $sesionSatService,
        private ValidacionTramiteService $validacionService,
        private FormularioTramiteService $formularioService,
        private BaseDataService $baseDataService
    ) {}

    public function getDatosTramitesIndex(?Proveedor $proveedor): array
    {
        return [
            'globalTramites' => $this->proveedorService->determinarTramitesDisponibles($proveedor),
            'proveedor' => $proveedor,
        ];
    }

    /**
     * Valida el acceso a un tipo de trámite específico
     */
    public function validarAccesoTramite(string $tipo, ?Proveedor $proveedor): bool
    {
        $tramitesDisponibles = $this->proveedorService->determinarTramitesDisponibles($proveedor);
        return $this->validacionService->validarAccesoTramite($tipo, $proveedor, $tramitesDisponibles);
    }

    public function getDatosConstancia(string $tipo, ?Proveedor $proveedor): array
    {
        return [
            'tipo' => $tipo,
            'proveedor' => $proveedor,
        ];
    }

    /**
     * Procesa los datos de la constancia SAT
     */
    public function procesarDatosConstancia(Request $request): void
    {
        $datosSat = $this->sesionSatService->extraerDatosDePeticion($request);
        $this->validacionService->validarRfcConstancia($datosSat);
        $this->sesionSatService->guardar($datosSat);
    }

    /**
     * Obtiene los datos necesarios para mostrar el formulario de trámite
     */
    public function getDatosFormulario(string $tipo, ?Proveedor $proveedor): array
    {
        return [
            'tipo_tramite' => $tipo,
            'proveedor' => $proveedor,
            'tramites' => $this->proveedorService->determinarTramitesDisponibles($proveedor),
            'titulo' => $this->baseDataService->obtenerTituloTramite($tipo),
            'descripcion' => $this->baseDataService->obtenerDescripcionTramite($tipo),
            'datosSat' => $this->sesionSatService->obtener(),
        ];
    }

    public function getDatosFormularioCorreccion(Tramite $tramite): array
    {
        $this->baseDataService->cargarRelacionesCorreccion($tramite);

        return [
            'tipo_tramite' => $tramite->tipo_tramite,
            'proveedor' => $tramite->proveedor,
            'tramite' => $tramite,
            'tramites' => $this->proveedorService->determinarTramitesDisponibles($tramite->proveedor),
            'titulo' => 'Corregir Trámite - ' . ucfirst($tramite->tipo_tramite),
            'descripcion' => 'Realice las correcciones solicitadas y reenvíe el trámite.',
            'datosSat' => $this->sesionSatService->obtener(),
            'es_correccion' => true,
        ];
    }

    /**
     * Procesa el envío completo de un formulario de trámite
     */
    public function procesarEnvioFormulario(Request $request, string $tipo, ?Proveedor $proveedor): array
    {
        try {
            DB::beginTransaction();

            Log::info('Iniciando procesamiento de envío de formulario', [
                'tipo' => $tipo,
                'proveedor_id' => $proveedor?->id
            ]);

            $proveedor = $this->asegurarProveedor($proveedor, $request);
            $tramite = $this->crearTramite($tipo, $proveedor);
            $this->formularioService->procesarDatosTramite($tramite, $request);

            DB::commit();

            Log::info('Formulario procesado exitosamente', [
                'tramite_id' => $tramite->id
            ]);

            return [
                'success' => true,
                'message' => 'Trámite procesado exitosamente.',
                'tramite_id' => $tramite->id,
                'redirect' => route('tramites.exito')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al procesar formulario', [
                'tipo' => $tipo,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al procesar el trámite: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Procesa las correcciones de un trámite
     */
    public function procesarCorreccionTramite(Request $request, Tramite $tramite): array
    {
        try {
            DB::beginTransaction();

            Log::info('Iniciando procesamiento de corrección', [
                'tramite_id' => $tramite->id
            ]);

            $tramite->update([
                'estado' => 'En_Revision',
                'observaciones' => 'Trámite reenviado con correcciones',
                'correcciones_count' => $tramite->correcciones_count + 1,
            ]);

            $this->formularioService->procesarDatosCorreccion($tramite, $request);

            DB::commit();

            Log::info('Corrección procesada exitosamente', [
                'tramite_id' => $tramite->id
            ]);

            return [
                'success' => true,
                'tramite_id' => $tramite->id,
                'message' => 'Trámite corregido y reenviado exitosamente.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al procesar corrección', [
                'tramite_id' => $tramite->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al procesar las correcciones: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Limpia los datos SAT de la sesión
     */
    public function limpiarDatosSesion(): void
    {
        $this->sesionSatService->limpiar();
    }



    /**
     * Crea un nuevo trámite
     */
    public function crearTramite(string $tipo, Proveedor $proveedor): Tramite
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

    /**
     * Asegura que existe un proveedor para el trámite
     */
    private function asegurarProveedor(?Proveedor $proveedor, Request $request): Proveedor
    {
        if ($proveedor) {
            return $proveedor;
        }

        $rfc = $this->validacionService->normalizarRfc($request->input('rfc', 'XAXX010101000'));
        $tipoPersona = $this->proveedorService->getTipoPersona(new Proveedor(['rfc' => $rfc]));

        return Proveedor::create([
            'usuario_id' => Auth::id(),
            'rfc' => $rfc,
            'tipo_persona' => $tipoPersona ?? 'Física',
            'estado_padron' => 'Pendiente',
            'fecha_alta_padron' => now()->toDateString(),
        ]);
    }


}
