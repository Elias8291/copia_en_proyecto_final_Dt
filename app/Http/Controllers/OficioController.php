<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use App\Models\Tramite;
use App\Services\OficioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OficioController extends Controller
{
    protected $oficioService;

    public function __construct(OficioService $oficioService)
    {
        $this->oficioService = $oficioService;
    }

    /**
     * Mostrar lista de oficios
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $oficios = Oficio::with(['tramite.proveedor.user'])
            ->orderBy('fecha_oficio', 'desc')
            ->paginate($perPage);

        return view('oficios.index', compact('oficios'));
    }

    /**
     * Mostrar oficio específico
     */
    public function show(Oficio $oficio)
    {
        $oficio->load(['tramite.proveedor.user']);
        return view('oficios.show', compact('oficio'));
    }

    /**
     * Eliminar oficio
     */
    public function destroy(Oficio $oficio)
    {
        try {
            $oficio->delete();
            return redirect()->route('oficios.index')
                ->with('success', 'Oficio eliminado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar oficio', [
                'oficio_id' => $oficio->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Error al eliminar el oficio.');
        }
    }
}
