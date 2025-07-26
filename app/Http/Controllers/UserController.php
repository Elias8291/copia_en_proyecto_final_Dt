<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Obtener usuarios reales de la base de datos
        $users = User::with('roles')
            ->orderBy('nombre', 'asc')
            ->get()
            ->map(function ($user) {
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
        return view('users.create');
    }

    public function store(Request $request)
    {
        // TODO: Implementar lógica de creación
        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente');
    }

    public function edit($id)
    {
        // TODO: Implementar lógica de edición
        return view('users.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // TODO: Implementar lógica de actualización
        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy($id)
    {
        // TODO: Implementar lógica de eliminación
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente');
    }
}
