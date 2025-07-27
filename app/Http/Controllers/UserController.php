<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Obtener usuarios con roles y aplicar filtros
        $query = User::with('roles');

        // Filtro de búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('correo', 'like', "%{$search}%")
                  ->orWhere('rfc', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->filled('rol')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->rol);
            });
        }

        // Filtro por estado
        if ($request->filled('estado')) {
            if ($request->estado === 'activo') {
                $query->whereNull('deleted_at');
            } elseif ($request->estado === 'inactivo') {
                $query->withTrashed()->whereNotNull('deleted_at');
            }
        } else {
            // Por defecto mostrar solo usuarios activos
            $query->whereNull('deleted_at');
        }

        // Ordenar y paginar
        $users = $query->orderBy('nombre', 'asc')
            ->paginate(10)
            ->withQueryString()
            ->through(function ($user) {
                // Agregar campos adicionales para el componente
                $user->email = $user->correo; // Mapear correo a email para el componente
                $user->rol = $user->roles->first() ? $user->roles->first()->name : 'user';
                $user->estado = $user->deleted_at ? 'inactivo' : 'activo';
                return $user;
            });

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            // Crear el usuario
            $user = User::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'rfc' => $request->rfc,
                'password' => Hash::make($request->password),
            ]);

            // Asignar roles si se proporcionan
            if ($request->has('roles') && !empty($request->roles)) {
                $user->assignRole($request->roles);
            } else {
                // Asignar rol por defecto si no se especifica
                $user->assignRole('user');
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuario creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        $user->load('roles');
        $user->email = $user->correo;
        $user->rol = $user->roles->first() ? $user->roles->first()->name : 'user';
        $user->estado = $user->deleted_at ? 'inactivo' : 'activo';
        
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            // Actualizar datos básicos
            $userData = [
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'rfc' => $request->rfc,
            ];

            // Actualizar contraseña solo si se proporciona
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Actualizar roles
            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'Usuario actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            // Verificar que no se elimine el usuario actual
            if ($user->id === auth()->id()) {
                return redirect()->route('users.index')
                    ->with('error', 'No puedes eliminar tu propia cuenta');
            }

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'Usuario eliminado exitosamente');

        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();

            return redirect()->route('users.index')
                ->with('success', 'Usuario restaurado exitosamente');

        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Error al restaurar el usuario: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            
            // Verificar que no se elimine el usuario actual
            if ($user->id === auth()->id()) {
                return redirect()->route('users.index')
                    ->with('error', 'No puedes eliminar permanentemente tu propia cuenta');
            }

            $user->forceDelete();

            return redirect()->route('users.index')
                ->with('success', 'Usuario eliminado permanentemente');

        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Error al eliminar permanentemente el usuario: ' . $e->getMessage());
        }
    }
}
