<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')->orderBy('nombre', 'asc')->paginate(12);
        $roles = Role::orderBy('name')->get();

        if ($request->ajax()) {
            // Si solicita todos los usuarios para filtrado del cliente
            if ($request->has('all')) {
                $allUsers = User::with('roles')->orderBy('nombre', 'asc')->get();

                return response()->json([
                    'users' => $allUsers,
                ]);
            }

            // Respuesta Ajax normal con paginación
            return response()->json([
                'html' => view('users.partials.table', compact('users'))->render(),
                'pagination' => view('users.partials.pagination', compact('users'))->render(),
                'count' => $users->total(),
            ]);
        }

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // TODO: Implementar lógica de creación
        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // TODO: Implementar lógica de actualización
        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(User $user)
    {
        // TODO: Implementar lógica de eliminación
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente');
    }
}
