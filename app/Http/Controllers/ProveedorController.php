<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\User;
use App\Services\ProveedorService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ProveedorController extends Controller
{
    protected $proveedorService;

    public function __construct(ProveedorService $proveedorService)
    {
        $this->proveedorService = $proveedorService;
    }
    /**
     * Mostrar lista de proveedores
     */
    public function index(Request $request): View
    {
        $query = Proveedor::with(['usuario'])
            ->orderBy('created_at', 'desc');

        // Filtros de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('rfc', 'like', "%{$search}%")
                  ->orWhere('razon_social', 'like', "%{$search}%")
                  ->orWhere('pv_numero', 'like', "%{$search}%")
                  ->orWhereHas('usuario', function($userQuery) use ($search) {
                      $userQuery->where('nombre', 'like', "%{$search}%")
                               ->orWhere('correo', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado_padron', $request->estado);
        }

        if ($request->filled('tipo_persona')) {
            $query->where('tipo_persona', $request->tipo_persona);
        }

        $proveedores = $query->paginate(15)->withQueryString();

        // Estadísticas
        $estadisticas = [
            'total' => Proveedor::count(),
            'activos' => Proveedor::where('estado_padron', 'Activo')->count(),
            'pendientes' => Proveedor::where('estado_padron', 'Pendiente')->count(),
            'vencidos' => Proveedor::where('estado_padron', 'Vencido')
                ->orWhere(function($q) {
                    $q->where('fecha_vencimiento_padron', '<', Carbon::now())
                      ->where('estado_padron', 'Activo');
                })->count(),
        ];

        return view('proveedores.index', compact('proveedores', 'estadisticas'));
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

        // Crear usuario
        $user = User::create([
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'rfc' => $validated['rfc'],
            'password' => bcrypt('temporal123'), // Password temporal
            'estado' => 'pendiente',
        ]);

        // Crear proveedor
        $proveedor = Proveedor::create([
            'usuario_id' => $user->id,
            'rfc' => $validated['rfc'],
            'tipo_persona' => $validated['tipo_persona'],
            'razon_social' => $validated['razon_social'],
            'estado_padron' => 'Pendiente',
        ]);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Mostrar detalles del proveedor
     */
    public function show(Proveedor $proveedor): View
    {
        $proveedor->load(['usuario', 'tramites', 'accionistas', 'contactos']);
        
        return view('proveedores.show', compact('proveedor'));
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
            'correo' => 'required|email|unique:users,correo,' . $proveedor->usuario_id,
            'rfc' => 'required|string|size:12|unique:proveedores,rfc,' . $proveedor->id,
            'tipo_persona' => 'required|in:Física,Moral',
            'razon_social' => 'nullable|string|max:255',
            'estado_padron' => 'required|in:Activo,Inactivo,Pendiente,Vencido,Rechazado',
            'fecha_vencimiento_padron' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        // Actualizar usuario
        $proveedor->usuario->update([
            'nombre' => $validated['nombre'],
            'correo' => $validated['correo'],
            'rfc' => $validated['rfc'],
        ]);

        // Actualizar proveedor
        $proveedor->update([
            'rfc' => $validated['rfc'],
            'tipo_persona' => $validated['tipo_persona'],
            'razon_social' => $validated['razon_social'],
            'estado_padron' => $validated['estado_padron'],
            'fecha_vencimiento_padron' => $validated['fecha_vencimiento_padron'],
            'observaciones' => $validated['observaciones'],
        ]);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Eliminar proveedor
     */
    public function destroy(Proveedor $proveedor): RedirectResponse
    {
        $proveedor->delete();
        
        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }

    /**
     * Crear un nuevo proveedor (método legacy)
     */
    public function createProveedor(User $user, array $data): Proveedor
    {
        $existingProveedor = Proveedor::where('usuario_id', $user->id)->first();

        if ($existingProveedor) {
            return $existingProveedor;
        }

        $proveedorData = [
            'usuario_id' => $user->id,
            'rfc' => $data['rfc'], 
            'tipo_persona' => $this->determineTipoPersona($data['rfc']),
            'estado_padron' => 'Pendiente',
        ];

        return Proveedor::create($proveedorData);
    }

    /**
     * Determinar tipo de persona basado en RFC
     */
    private function determineTipoPersona(string $rfc): string
    {
        return strlen($rfc) === 13 ? 'Física' : 'Moral';
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

        $proveedor->update($validated);

        return back()->with('success', 'Estado del proveedor actualizado exitosamente.');
    }

    /**
     * Exportar proveedores
     */
    public function export(Request $request)
    {
        // Implementar exportación a Excel/PDF
        // Por ahora retornamos un mensaje
        return back()->with('info', 'Funcionalidad de exportación en desarrollo.');
    }

    /**
     * Obtener proveedor por usuario (método usado por el servicio)
     */
    public function getProveedorByUser(User $user): ?Proveedor
    {
        return $this->proveedorService->getProveedorByUser($user);
    }

    /**
     * Crear o obtener proveedor existente para un usuario
     */
    public function createOrGetProveedor(User $user, array $data = []): Proveedor
    {
        return $this->proveedorService->createOrGetProveedor($user, $data);
    }

    /**
     * Verificar si un usuario puede realizar un tipo de trámite
     */
    public function puedeRealizarTramite(User $user, string $tipoTramite): array
    {
        return $this->proveedorService->puedeRealizarTramite($user, $tipoTramite);
    }

    /**
     * Obtener resumen de trámites disponibles para un usuario
     */
    public function getTramitesDisponibles(User $user): array
    {
        return $this->proveedorService->getTramitesDisponibles($user);
    }

    /**
     * Buscar proveedor por RFC
     */
    public function buscarPorRFC(string $rfc): ?Proveedor
    {
        return $this->proveedorService->buscarPorRFC($rfc);
    }

    /**
     * Buscar proveedor por correo
     */
    public function buscarPorCorreo(string $correo): ?Proveedor
    {
        return $this->proveedorService->buscarPorCorreo($correo);
    }

    /**
     * Activar proveedor (cambiar estado y asignar número PV)
     */
    public function activarProveedor(Proveedor $proveedor, ?string $fechaVencimiento = null): Proveedor
    {
        return $this->proveedorService->activarProveedor($proveedor, $fechaVencimiento);
    }

    /**
     * Obtener estadísticas detalladas de un proveedor
     */
    public function getEstadisticasProveedor(Proveedor $proveedor): array
    {
        return $this->proveedorService->getEstadisticasProveedor($proveedor);
    }

    /**
     * Obtener proveedores próximos a vencer
     */
    public function getProveedoresProximosAVencer(int $diasAnticipacion = 30)
    {
        return $this->proveedorService->getProveedoresProximosAVencer($diasAnticipacion);
    }

    /**
     * Verificar si un proveedor está próximo a vencer
     */
    public function estaProximoAVencer(Proveedor $proveedor, int $diasAnticipacion = 30): bool
    {
        return $this->proveedorService->estaProximoAVencer($proveedor, $diasAnticipacion);
    }

    /**
     * Generar número PV único
     */
    public function generarNumeroPV(): string
    {
        return $this->proveedorService->generarNumeroPV();
    }

    /**
     * Asignar número PV a proveedor
     */
    public function asignarNumeroPV(Proveedor $proveedor): Proveedor
    {
        return $this->proveedorService->asignarNumeroPV($proveedor);
    }
}
