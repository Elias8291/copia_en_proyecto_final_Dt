<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcesarConstanciaRequest;
use App\Services\Tramites\TramiteService;
use App\Services\Tramites\ConstanciaService;
use App\ViewModels\TramiteViewModel;
use Illuminate\Http\Request;

class TramiteController extends Controller
{
    private TramiteService $tramiteService;
    private ConstanciaService $constanciaService;

    public function __construct(TramiteService $tramiteService, ConstanciaService $constanciaService)
    {
        $this->tramiteService = $tramiteService;
        $this->constanciaService = $constanciaService;
    }

    public function index()
    {
        return view('tramites.index');
    }

    public function cargarConstancia()
    {
        return view('tramites.cargar_constancia');
    }

    public function procesarConstancia(ProcesarConstanciaRequest $request)
    {
        try {
            $datosConstancia = $this->constanciaService->procesar($request);
            
            if (!$this->constanciaService->validarRfcUsuario($request->sat_rfc)) {
                return back()->withErrors([
                    'sat_rfc' => 'La constancia que intentó cargar no le pertenece. Solo puede cargar constancias de su propia persona o empresa.'
                ]);
            }
            
            session($datosConstancia);

            return redirect()->route('tramites.create')
                ->with('success', 'Constancia cargada y datos extraídos exitosamente. Puede continuar con el trámite.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al procesar el archivo. Por favor, inténtelo de nuevo.']);
        }
    }

    public function create()
    {
        if (!$this->tramiteService->verificarConstanciaCargada()) {
            return redirect()->route('tramites.cargar-constancia')
                ->with('error', 'Debe cargar la constancia de situación fiscal antes de continuar.');
        }

        $datosConstancia = $this->tramiteService->obtenerDatosConstancia();
        $viewModel = new TramiteViewModel($datosConstancia);

        return view('tramites.create', compact('viewModel'));
    }

    public function store(Request $request)
    {
        try {
            $tramite = $this->tramiteService->crear($request->all());

            return redirect()->route('tramites.index')
                ->with('success', 'Trámite creado exitosamente.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear el trámite. Por favor, inténtelo de nuevo.']);
        }
    }
} 