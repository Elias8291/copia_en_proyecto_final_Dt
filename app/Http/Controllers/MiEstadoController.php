<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proveedor;
use App\Models\Tramite;
use App\Services\RfcProveedorService;
use App\Services\Tramites\DataRetrievalService;
use App\Services\HistorialTramitesService;
use App\ViewModels\FormDataViewModel;
use App\Enums\TramiteStatus;

class MiEstadoController extends Controller
{
    private RfcProveedorService $rfcProveedorService;
    private DataRetrievalService $dataRetrievalService;
    private HistorialTramitesService $historialTramitesService;

    public function __construct(
        RfcProveedorService $rfcProveedorService,
        DataRetrievalService $dataRetrievalService,
        HistorialTramitesService $historialTramitesService
    ) {
        $this->rfcProveedorService = $rfcProveedorService;
        $this->dataRetrievalService = $dataRetrievalService;
        $this->historialTramitesService = $historialTramitesService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $rfc = $this->rfcProveedorService->obtenerRfcUsuario();
        
        $proveedor = $this->obtenerProveedorPorRfc($rfc);

        $ultimoTramite = null;
        $datosCompletos = null;
        $archivosCargados = null;
        $historialTramites = collect();
        
        $ordenHistorial = $request->get('orden_historial', 'reciente');

        if ($rfc) {
            $historialTramites = $this->obtenerHistorialTramitesOrdenado($rfc, $ordenHistorial);
            $ultimoTramiteActivo = $this->obtenerUltimoTramiteActivo($rfc);
            $ultimoTramite = $ultimoTramiteActivo ?: $historialTramites->first();
            
            if ($ultimoTramite) {
                $datosCompletos = $this->obtenerDatosCompletosTramite($ultimoTramite);
                $archivosCargados = $ultimoTramite->archivos;
            }
        }

        return view('mi_estado', compact('proveedor', 'ultimoTramite', 'datosCompletos', 'archivosCargados', 'ordenHistorial', 'historialTramites', 'rfc'));
    }

    private function obtenerProveedorPorRfc(?string $rfc): ?Proveedor
    {
        if (!$rfc) {
            return null;
        }

        $proveedor = $this->rfcProveedorService->buscarProveedorActivo($rfc);
        
        if (!$proveedor) {
            $proveedor = Proveedor::where('rfc', $rfc)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return $proveedor;
    }

    private function obtenerHistorialTramitesOrdenado(string $rfc, string $orden): \Illuminate\Support\Collection
    {
        $query = Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })->with(['proveedor', 'datosGenerales', 'direcciones', 'contactos', 'actividades', 'archivos', 'oficios']);

        if ($orden === 'pasados') {
            $query->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) ASC');
        } else {
            $query->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) DESC');
        }

        return $query->get();
    }

    private function obtenerUltimoTramiteActivo(string $rfc): ?Tramite
    {
        return Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })
        ->where('status', TramiteStatus::APROBADO->value)
        ->with(['proveedor', 'datosGenerales', 'direcciones', 'contactos', 'actividades', 'archivos'])
        ->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) DESC')
        ->first();
    }

    private function obtenerUltimoTramiteActivoFlexible(string $rfc, bool $incluirEnRevision = false): ?Tramite
    {
        $estadosActivos = [TramiteStatus::APROBADO->value];
        
        if ($incluirEnRevision) {
            $estadosActivos = array_merge($estadosActivos, [
                TramiteStatus::REVISION_DIGITAL->value,
                TramiteStatus::REVISION_PRESENCIAL->value,
                TramiteStatus::REVISION_DOMICILIARIA->value
            ]);
        }

        return Tramite::whereHas('proveedor', function($query) use ($rfc) {
            $query->where('rfc', $rfc);
        })
        ->whereIn('status', $estadosActivos)
        ->with(['proveedor', 'datosGenerales', 'direcciones', 'contactos', 'actividades', 'archivos'])
        ->orderByRaw('COALESCE(fecha_finalizacion, fecha_inicio, created_at) DESC')
        ->first();
    }

    private function obtenerDatosCompletosTramite(Tramite $tramite): array
    {
        $datosServicio = $this->dataRetrievalService->obtenerDatosTramiteHistorico($tramite->id);
        
        if (isset($datosServicio['datos_generales'])) {
            $datosServicio['datos_generales']['tipo_persona'] = $tramite->proveedor->tipo_persona;
        }
        
        $viewModel = new FormDataViewModel($datosServicio);
        
        return $viewModel->getAllFormData();
    }

    private function obtenerDatosProveedorVigenteConServicio(string $rfc): array
    {
        return $this->dataRetrievalService->obtenerDatosProveedorVigente($rfc);
    }

    private function obtenerEstadisticasHistorial(string $rfc): array
    {
        return $this->historialTramitesService->obtenerEstadisticasHistorial($rfc);
    }

    private function formatApoderadoData($apoderado)
    {
        if (!$apoderado) {
            return [];
        }

        $instrumentoNotarial = $apoderado->instrumentoNotarial;

        return [
            'nombre_apoderado' => $apoderado->nombre_apoderado,
            'rfc' => $apoderado->rfc,
            'numero_escritura_poder' => $instrumentoNotarial->numero_escritura ?? '',
            'fecha_poder' => $instrumentoNotarial->fecha_constitucion ?? '',
            'nombre_notario_poder' => $instrumentoNotarial->nombre_notario ?? '',
            'numero_notario_poder' => $instrumentoNotarial->numero_notario ?? '',
            'numero_escritura_constitutiva_poder' => $apoderado->numero_escritura_constitutiva_poder ?? '',
            'numero_registro_publico_poder' => $apoderado->numero_registro_publico_poder ?? '',
            'fecha_inscripcion_poder' => $apoderado->fecha_inscripcion_poder ?? '',
            'estado_id' => $instrumentoNotarial->estado_id ?? '',
            'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
        ];
    }

    private function formatConstitucionData($constitutivo)
    {
        if (!$constitutivo) {
            return [];
        }

        $instrumentoNotarial = $constitutivo->instrumentoNotarial;

        return [
            'estado_id' => $instrumentoNotarial->estado_id ?? '',
            'estado_nombre' => $instrumentoNotarial->estado->nombre ?? '',
            'numero_escritura' => $instrumentoNotarial->numero_escritura ?? '',
            'numero_escritura_constitutiva' => $instrumentoNotarial->numero_escritura_constitutiva ?? '',
            'fecha_constitucion' => $instrumentoNotarial->fecha_constitucion ?? '',
            'nombre_notario' => $instrumentoNotarial->nombre_notario ?? '',
            'numero_notario' => $instrumentoNotarial->numero_notario ?? '',
            'numero_registro_publico' => $instrumentoNotarial->numero_registro_publico ?? '',
            'fecha_inscripcion' => $instrumentoNotarial->fecha_inscripcion ?? '',
        ];
    }
}
