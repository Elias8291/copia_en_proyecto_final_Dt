<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(PermissionMiddleware::class . ':roles.ver')->only(['index', 'show']);
        $this->middleware(PermissionMiddleware::class . ':roles.crear')->only(['create', 'store']);
        $this->middleware(PermissionMiddleware::class . ':roles.editar')->only(['edit', 'update']);
        $this->middleware(PermissionMiddleware::class . ':roles.eliminar')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        
        $query = Role::withCount(['users', 'permissions']);
        
        // Búsqueda por nombre o descripción
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filtro por guard
        if ($request->filled('guard_name')) {
            $query->where('guard_name', $request->get('guard_name'));
        }
        
        $roles = $query->paginate($perPage);
        
        // Mantener los parámetros de búsqueda en la paginación
        $roles->appends($request->query());

        return view('roles.index', compact('roles', 'perPage'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'required|string|in:web,api',
            'description' => 'nullable|string|max:1000',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'description' => $request->description,
        ]);

        // Manejar permisos de forma más segura
        try {
            if ($request->has('permissions') && is_array($request->permissions)) {
                // Verificar que todos los permisos existan y sean del guard correcto
                $permissionIds = $request->permissions;
                $validPermissions = Permission::whereIn('id', $permissionIds)
                    ->where('guard_name', $request->guard_name)
                    ->pluck('id')
                    ->toArray();
                
                $role->syncPermissions($validPermissions);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al sincronizar permisos: ' . $e->getMessage());
        }

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        $role->permissions_count = $role->permissions->count();
        return view('roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'guard_name' => 'required|string|in:web,api',
            'description' => 'nullable|string|max:1000',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'description' => $request->description,
        ]);

        // Manejar permisos de forma más segura
        try {
            if ($request->has('permissions') && is_array($request->permissions)) {
                // Verificar que todos los permisos existan y sean del guard correcto
                $permissionIds = $request->permissions;
                $validPermissions = Permission::whereIn('id', $permissionIds)
                    ->where('guard_name', $request->guard_name)
                    ->pluck('id')
                    ->toArray();
                
                $role->syncPermissions($validPermissions);
            } else {
                $role->syncPermissions([]);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al sincronizar permisos: ' . $e->getMessage());
        }

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado exitosamente.');
    }
}
