<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->load('roles');
        
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        $user->load('roles');
        
        return view('profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            
            $user->update([
                'nombre' => $request->nombre,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            DB::commit();

            return redirect()->route('profile.index')
                ->with('success', 'Perfil actualizado exitosamente')
                ->with('success_title', '¡Perfil Actualizado!')
                ->with('success_message', 'Tu información ha sido actualizada correctamente.')
                ->with('success_accept_text', 'Aceptar')
                ->with('success_redirect', route('profile.index'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }
}
