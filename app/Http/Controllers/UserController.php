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
        $query = User::with('roles');
    
        if ($request->filled('search')) {
            $query->where(fn ($q) => $q->where('nombre', 'like', "%{$request->search}%")
                ->orWhere('correo', 'like', "%{$request->search}%")
                ->orWhere('rfc', 'like', "%{$request->search}%"));
        }
    
        if ($request->filled('rol')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $request->rol));
        }
    
        $query->when($request->filled('estado'), fn ($q) => $request->estado === 'activo' 
            ? $q->whereNull('deleted_at')
            : $q->withTrashed()->whereNotNull('deleted_at'), 
            fn ($q) => $q->whereNull('deleted_at'));
    
        $users = $query->orderBy('nombre')->paginate(10)->withQueryString()->through(
            fn ($user) => (object) [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'email' => $user->correo,
                'rfc' => $user->rfc,
                'rol' => $user->roles->first()->name ?? 'user',
                'estado' => $user->deleted_at ? 'inactivo' : 'activo',
            ]
        );
    
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
                ->with('success', 'Usuario creado exitosamente')
                ->with('success_title', '¡Usuario Creado!')
                ->with('success_message', 'El usuario ha sido creado correctamente.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('users.index'));

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
                ->with('success', 'Usuario actualizado exitosamente')
                ->with('success_title', '¡Usuario Actualizado!')
                ->with('success_message', 'El usuario ha sido actualizado correctamente.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('users.index'));

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
                ->with('success', 'Usuario eliminado exitosamente')
                ->with('success_title', '¡Usuario Eliminado!')
                ->with('success_message', 'El usuario ha sido eliminado correctamente.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('users.index'));

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
                ->with('success', 'Usuario restaurado exitosamente')
                ->with('success_title', '¡Usuario Restaurado!')
                ->with('success_message', 'El usuario ha sido restaurado correctamente.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('users.index'));

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
                ->with('success', 'Usuario eliminado permanentemente')
                ->with('success_title', '¡Usuario Eliminado Permanentemente!')
                ->with('success_message', 'El usuario ha sido eliminado permanentemente del sistema.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('users.index'));

        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Error al eliminar permanentemente el usuario: ' . $e->getMessage());
        }
    }
}
