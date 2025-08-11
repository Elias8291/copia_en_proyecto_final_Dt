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
    // Lista usuarios con filtros (búsqueda, rol, estado)
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->string('search');
                $q->where(fn ($qq) =>
                    $qq->where('nombre', 'like', "%{$s}%")
                       ->orWhere('correo', 'like', "%{$s}%")
                       ->orWhere('rfc', 'like', "%{$s}%")
                );
            })
            ->when($request->filled('rol'), fn ($q) =>
                $q->whereHas('roles', fn ($rq) => $rq->where('name', $request->rol))
            )
            ->when($request->filled('estado'), function ($q) use ($request) {
                return $request->estado === 'activo'
                    ? $q->whereNull('deleted_at')
                    : $q->withTrashed()->whereNotNull('deleted_at');
            }, fn ($q) => $q->whereNull('deleted_at'))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString()
            ->through(fn ($user) => (object) [
                'id'                => $user->id,
                'name'              => $user->nombre,
                'email'             => $user->correo,
                'rfc'               => $user->rfc,
                'roles'             => $user->roles,
                'email_verified_at' => $user->email_verified_at,
                'created_at'        => $user->created_at,
                'deleted_at'        => $user->deleted_at,
            ]);

        return view('users.index', compact('users'));
    }

    // Formulario de creación
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // Crea usuario y asigna rol (usa transacción)
    public function store(UserStoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            $user = User::create([
                'nombre'   => $data['nombre'],
                'correo'   => $data['correo'],
                'rfc'      => $data['rfc'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole($request->input('roles', 'user'));
        });

        return $this->flashSuccess('users.index', 'Usuario creado exitosamente', '¡Usuario Creado!', 'El usuario ha sido creado correctamente.');
    }

    // Muestra detalle (sin mutar el modelo)
    public function show(User $user)
    {
        $user->load('roles');

        $presented = (object) [
            'id'     => $user->id,
            'name'   => $user->nombre,
            'email'  => $user->correo,
            'rfc'    => $user->rfc,
            'rol'    => optional($user->roles->first())->name ?? 'user',
            'estado' => $user->deleted_at ? 'inactivo' : 'activo',
        ];

        return view('users.show', ['user' => $presented]);
    }

    // Formulario de edición
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');

        return view('users.edit', compact('user', 'roles'));
    }

    // Actualiza datos y roles (solo cambia password si viene)
    public function update(UserUpdateRequest $request, User $user)
    {
        DB::transaction(function () use ($request, $user) {
            $data = $request->validated();

            $payload = [
                'nombre' => $data['nombre'],
                'correo' => $data['correo'],
                'rfc'    => $data['rfc'],
            ];

            if (!empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }

            $user->update($payload);

            if ($request->has('roles')) {
                $user->syncRoles($request->input('roles', []));
            }
        });

        return $this->flashSuccess('users.index', 'Usuario actualizado exitosamente', '¡Usuario Actualizado!', 'El usuario ha sido actualizado correctamente.');
    }

    // Soft delete (no permite auto-eliminarse)
    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes eliminar tu propia cuenta');
        }

        $user->delete();

        return $this->flashSuccess('users.index', 'Usuario eliminado exitosamente', '¡Usuario Eliminado!', 'El usuario ha sido eliminado correctamente.');
    }

    // Restaura un usuario eliminado
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return $this->flashSuccess('users.index', 'Usuario restaurado exitosamente', '¡Usuario Restaurado!', 'El usuario ha sido restaurado correctamente.');
    }

    // Elimina permanentemente (no permite auto-eliminarse)
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->is(auth()->user())) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes eliminar permanentemente tu propia cuenta');
        }

        $user->forceDelete();

        return $this->flashSuccess('users.index', 'Usuario eliminado permanentemente', '¡Usuario Eliminado Permanentemente!', 'El usuario ha sido eliminado permanentemente del sistema.');
    }

    // Helper: redirección + mensajes de éxito unificados
    private function flashSuccess(string $route, string $msg, string $title, string $detail)
    {
        return redirect()
            ->route($route)
            ->with([
                'success'             => $msg,
                'success_title'       => $title,
                'success_message'     => $detail,
                'success_accept_text' => 'Aceptar',
                'success_redirect'    => route($route),
            ]);
    }
}
