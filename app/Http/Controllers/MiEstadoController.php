<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proveedor;
use App\Models\Tramite;
use App\Services\Tramites\DataRetrievalService;
use App\ViewModels\TramiteViewModel;

class MiEstadoController extends Controller
{
    private DataRetrievalService $dataRetrievalService;

    public function __construct(DataRetrievalService $dataRetrievalService)
    {
        $this->dataRetrievalService = $dataRetrievalService;
    }

    public function index()
    {
        $user = Auth::user();
        $proveedor = Proveedor::where('usuario_id', $user->id)->first();
        
        $datosProveedor = [];
        $ultimoTramite = null;
        $viewModel = null;
        $historialTramites = collect();
        
        if ($proveedor) {
            // Obtener datos del proveedor vigente
            $datosProveedor = $this->dataRetrievalService->obtenerDatosProveedorVigente($proveedor->rfc);
            
            // Obtener el historial completo de trámites
            $historialTramites = $this->dataRetrievalService->obtenerHistorialTramites($proveedor->rfc);
            
            // Obtener el último trámite del proveedor
            $ultimoTramite = Tramite::where('proveedor_id', $proveedor->id)
                ->orderBy('created_at', 'desc')
                ->first();
                
            // Si hay último trámite, obtener sus datos
            if ($ultimoTramite) {
                $datosTramite = $this->dataRetrievalService->obtenerDatosTramiteHistorico($ultimoTramite->id);
                $viewModel = new TramiteViewModel($datosTramite);
            }
        }
        
        return view('mi_estado', compact('proveedor', 'datosProveedor', 'ultimoTramite', 'viewModel', 'historialTramites'));
    }
} 