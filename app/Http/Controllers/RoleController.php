<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::with('permissions');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('guard_name')) {
            $query->where('guard_name', $request->guard_name);
        }

        $roles = $query->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString()
            ->through(function ($role) {
                $role->permissions_count = $role->permissions->count();
                return $role;
            });

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('roles.create', compact('permissions'));
    }

    public function store(RoleRequest $request)
    {
        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description,
                'guard_name' => $request->guard_name ?? 'web',
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Rol creado exitosamente')
                ->with('success_title', '¡Rol Creado!')
                ->with('success_message', 'El rol ha sido creado correctamente.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('roles.index'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el rol: ' . $e->getMessage());
        }
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        $role->permissions_count = $role->permissions->count();
        return view('roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $role->load('permissions');
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        try {
            DB::beginTransaction();

            $role->update([
                'name' => $request->name,
                'description' => $request->description,
                'guard_name' => $request->guard_name ?? 'web',
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Rol actualizado exitosamente')
                ->with('success_title', '¡Rol Actualizado!')
                ->with('success_message', 'El rol ha sido actualizado correctamente.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('roles.index'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el rol: ' . $e->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        try {
            // Verificar que no se elimine un rol del sistema
            if (in_array($role->name, ['admin', 'user', 'moderator'])) {
                return redirect()->route('roles.index')
                    ->with('error', 'No se puede eliminar un rol del sistema');
            }

            $role->delete();

            return redirect()->route('roles.index')
                ->with('success', 'Rol eliminado exitosamente')
                ->with('success_title', '¡Rol Eliminado!')
                ->with('success_message', 'El rol ha sido eliminado correctamente.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('roles.index'));

        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->with('error', 'Error al eliminar el rol: ' . $e->getMessage());
        }
    }
}
