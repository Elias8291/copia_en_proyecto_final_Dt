<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar lista de roles
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        // Validar que sea un número válido y esté en las opciones permitidas
        $allowedPerPage = [5, 10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $roles = Role::with('permissions')
            ->orderBy('name', 'asc')
            ->paginate($perPage);
        
        // Mantener el parámetro per_page en los enlaces de paginación
        $roles->appends($request->query());

        return view('roles.index', compact('roles', 'perPage'));
    }

    /**
     * Mostrar formulario para crear nuevo rol
     */
    public function create()
    {
        $permissions = Permission::orderBy('name', 'asc')->get();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Almacenar nuevo rol
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Mostrar formulario para editar rol
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name', 'asc')->get();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Actualizar rol
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'name' => $request->name
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Eliminar rol
     */
    public function destroy(Role $role)
    {
        // Verificar que no sea un rol del sistema
        if (in_array($role->name, ['Super Admin', 'Admin', 'User'])) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar un rol del sistema.');
        }

        // Verificar que no tenga usuarios asignados
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar un rol que tiene usuarios asignados.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado exitosamente.');
    }
}