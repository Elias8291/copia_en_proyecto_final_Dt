<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\User;
use App\Services\ProveedorService;
use App\Services\Proveedores\BusquedaProveedorService;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProveedorController extends Controller
{
    public function __construct(
        private ProveedorService $proveedorService,
        private BusquedaProveedorService $busquedaService
    ) {}

    /**
     * Mostrar lista de proveedores
     */
    public function index(Request $request): View
    {
        $filtros = $request->only(['search', 'estado', 'tipo_persona', 'vencimiento', 'año']);
        
        // Si se solicita filtrado en tiempo real, cargar todos los datos
        $cargarTodos = $request->get('filter_realtime', false);
        
        if ($cargarTodos) {
            // Cargar todos los proveedores para filtrado en tiempo real
            $query = $this->proveedorService->obtenerConFiltros([]);
            $todosProveedores = $query->orderBy('created_at', 'desc')->get();
            
            // Crear una colección paginada falsa para mantener compatibilidad con la vista
            $todosProveedores = new \Illuminate\Pagination\LengthAwarePaginator(
                $todosProveedores,
                $todosProveedores->count(),
                $todosProveedores->count(),
                1,
                ['path' => request()->url(), 'pageName' => 'page']
            );
        } else {
            // Funcionalidad normal con paginación
            $query = $this->proveedorService->obtenerConFiltros($filtros);
            $todosProveedores = $query->orderBy('created_at', 'desc')->paginate(10);
        }
    
        $todosProveedores->getCollection()->transform(function ($proveedor) {
            try {
                $datosGenerales = $this->proveedorService->obtenerDatosGeneralesUltimoTramite($proveedor);
                
                // Usar razón social del último trámite si existe y no está vacía
                if (!empty($datosGenerales['razon_social'] ?? null)) {
                    $proveedor->razon_social_ultimo_tramite = $datosGenerales['razon_social'];
                }
                // Si no hay datos generales del último trámite, mantener la razón social original del proveedor
                // No asignar nada para que la vista use el fallback automáticamente
                
            } catch (\Exception $e) {
                Log::error('Error al obtener datos generales del proveedor', [
                    'proveedor_id' => $proveedor->id,
                    'error' => $e->getMessage()
                ]);
                // En caso de error, no asignar nada para usar el fallback en la vista
            }
            return $proveedor;
        });
    
        $estadisticas = $this->proveedorService->obtenerEstadisticas();
    
        return view('proveedores.index', compact('estadisticas', 'todosProveedores', 'cargarTodos'));
    }
    

    /**
     * Mostrar formulario de creación
     */
    public function create(): View
    {
        return view('proveedores.create');
    }

    /**
     * Almacenar nuevo proveedor
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:users,correo',
            'rfc' => 'required|string|size:12|unique:proveedores,rfc',
            'tipo_persona' => 'required|in:Física,Moral',
            'razon_social' => 'nullable|string|max:255',
        ]);

        try {
            $this->proveedorService->crearProveedor($validated);
            
            return redirect()->route('proveedores.index')
                ->with('success', 'Proveedor creado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles del proveedor
     */
    public function show(Proveedor $proveedor): View
    {
        // Cargar relaciones existentes
        $proveedor->load([
            'user',
            'tramites' => function ($query) {
                $query->where('estado', 'Aprobado')->orderBy('updated_at', 'desc');
            }
        ]);

        try {
            // Obtener el último trámite aprobado
            $tramite = $proveedor->tramites()->where('estado', 'Aprobado')->orderBy('updated_at', 'desc')->first();
            
            if (!$tramite) {
                // Si no hay trámite aprobado, mostrar página básica del proveedor
                return view('proveedores.tramite-details', [
                    'proveedor' => $proveedor,
                    'tramite' => null,
                    'datosCompletos' => null
                ])->with('warning', 'Este proveedor no tiene trámites aprobados.');
            }

            // Obtener datos completos del último trámite aprobado
            $datosCompletos = $this->proveedorService->obtenerInformacionCompletaUltimoTramite($proveedor);

            return view('proveedores.tramite-details', compact(
                'proveedor',
                'tramite', 
                'datosCompletos'
            ));

        } catch (\Exception $e) {
            Log::error('Error al mostrar detalles del proveedor', [
                'proveedor_id' => $proveedor->id,
                'error' => $e->getMessage()
            ]);

            return view('proveedores.tramite-details', [
                'proveedor' => $proveedor,
                'tramite' => null,
                'datosCompletos' => null
            ])->with('error', 'Error al cargar los datos del proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Proveedor $proveedor): View
    {
        $proveedor->load('usuario');

        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Actualizar proveedor
     */
    public function update(Request $request, Proveedor $proveedor): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:users,correo,'.$proveedor->usuario_id,
            'rfc' => 'required|string|size:12|unique:proveedores,rfc,'.$proveedor->id,
            'tipo_persona' => 'required|in:Física,Moral',
            'razon_social' => 'nullable|string|max:255',
            'estado_padron' => 'required|in:Activo,Inactivo,Pendiente,Vencido,Rechazado',
            'fecha_vencimiento_padron' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $this->proveedorService->actualizarProveedor($proveedor, $validated);
            
            return redirect()->route('proveedores.index')
                ->with('success', 'Proveedor actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al actualizar el proveedor: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar proveedor
     */
    public function destroy(Proveedor $proveedor): RedirectResponse
    {
        try {
            $this->proveedorService->eliminarProveedor($proveedor);
            
            return redirect()->route('proveedores.index')
                ->with('success', 'Proveedor eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el proveedor: ' . $e->getMessage());
        }
    }



    /**
     * Cambiar estado del proveedor
     */
    public function cambiarEstado(Request $request, Proveedor $proveedor): RedirectResponse
    {
        $validated = $request->validate([
            'estado_padron' => 'required|in:Activo,Inactivo,Pendiente,Vencido,Rechazado',
            'observaciones' => 'nullable|string',
        ]);

        try {
            $this->proveedorService->cambiarEstado(
                $proveedor, 
                $validated['estado_padron'], 
                $validated['observaciones'] ?? null
            );
            
            return back()->with('success', 'Estado del proveedor actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }



    /**
     * Obtener datos generales del último trámite aprobado de un proveedor
     */
    public function obtenerDatosGenerales(Proveedor $proveedor)
    {
        try {
            $datosGenerales = $this->proveedorService->obtenerDatosGeneralesUltimoTramite($proveedor);
            
            if (!$datosGenerales) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron datos generales del último trámite aprobado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $datosGenerales,
                'message' => 'Datos generales obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos generales: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información completa del último trámite aprobado
     */
    public function obtenerInformacionCompleta(Proveedor $proveedor)
    {
        try {
            $informacionCompleta = $this->proveedorService->obtenerInformacionCompletaUltimoTramite($proveedor);
            
            if (!$informacionCompleta) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró información del último trámite aprobado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $informacionCompleta,
                'message' => 'Información completa obtenida exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información completa: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showTramiteDetails(Proveedor $proveedor)
    {
        try {
            // Obtener el último trámite aprobado con todos sus datos relacionados
            $ultimoTramite = $this->proveedorService->obtenerUltimoTramiteAprobado($proveedor);
            
            if (!$ultimoTramite) {
                return redirect()->route('proveedores.index')
                    ->with('error', 'El proveedor no tiene trámites aprobados.');
            }

            // Obtener todos los datos relacionados del trámite
            $datosCompletos = $this->proveedorService->obtenerDatosCompletosUltimoTramite($proveedor);
            
            return view('proveedores.tramite-details', [
                'proveedor' => $proveedor,
                'tramite' => $ultimoTramite,
                'datosCompletos' => $datosCompletos
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al mostrar detalles del trámite: ' . $e->getMessage(), [
                'proveedor_id' => $proveedor->id,
                'rfc' => $proveedor->rfc
            ]);
            
            return redirect()->route('proveedores.index')
                ->with('error', 'Error al cargar los detalles del trámite.');
        }
    }
}
