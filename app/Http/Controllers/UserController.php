<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por rol (usando Spatie)
        if ($request->filled('rol')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->rol);
            });
        }

        // Filtro por fecha de registro
        if ($request->filled('fecha')) {
            if ($request->fecha === 'hoy') {
                $query->whereDate('created_at', today());
            } elseif ($request->fecha === 'semana') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($request->fecha === 'mes') {
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            }
        }

        // Paginación
        $perPage = $request->input('perPage', 10);
        if ($perPage === 'all') {
            $usuarios = $query->get();
        } else {
            $usuarios = $query->paginate((int) $perPage)->appends($request->all());
        }

        $totalUsuarios = User::count();
        return view('users.index', compact('totalUsuarios', 'usuarios'));
    }

    public function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $exists = User::where('correo', $request->email)->exists();
        if ($exists) {
            return ['valid' => false, 'message' => 'Este email ya está registrado'];
        }
        return ['valid' => true, 'message' => 'Email disponible'];
    }

    public function validateRfc(Request $request)
    {
        $request->validate([
            'rfc' => [
                'required',
                'regex:/^[A-ZÑ&]{3,4}[0-9]{6}[A-V1-9][A-Z0-9][0-9]$/i',
            ],
        ], [
            'rfc.regex' => 'Formato de RFC inválido',
        ]);

        $exists = User::where('rfc', $request->rfc)->exists();
        if ($exists) {
            return ['valid' => false, 'message' => 'Este RFC ya está registrado'];
        }
        return ['valid' => true, 'message' => 'RFC disponible'];
    }

    /**
     * Validar datos de registro de usuario
     */
    public function validateRegistrationData(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|email|unique:users,correo',
            'password' => 'required|string|min:8|confirmed',
            'sat_rfc' => 'required|string',
            'sat_nombre' => 'required|string|max:255',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo electrónico no es válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'sat_rfc.required' => 'El RFC del SAT es obligatorio',
            'sat_nombre.required' => 'El nombre del SAT es obligatorio',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
    }

    /**
     * Validar datos de actualización de usuario
     */
    public function validateUpdateData(array $data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'sat_rfc' => 'required|string',
            'sat_nombre' => 'required|string|max:255',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo electrónico no es válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'sat_rfc.required' => 'El RFC del SAT es obligatorio',
            'sat_nombre.required' => 'El nombre del SAT es obligatorio',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
    }

    /**
     * Crear usuario con datos del SAT
     */
    public function createUser(array $data): User
    {
        try {
            // Validar datos de entrada
            $this->validateRegistrationData($data);

            // Crear el usuario
            $user = User::create([
                'nombre' => $data['sat_nombre'],
                'correo' => $data['email'],
                'rfc' => $data['sat_rfc'],
                'password' => Hash::make($data['password']),
                'estado' => 'pendiente',
                'verification_token' => Str::random(64),
            ]);

            // Asignar rol de solicitante
            $this->assignSolicitanteRole($user);

            Log::info('Usuario creado exitosamente', [
                'user_id' => $user->id,
                'email' => $user->correo,
                'rfc' => $user->rfc
            ]);

            return $user;

        } catch (\Exception $e) {
            Log::error('Error al crear usuario', [
                'error' => $e->getMessage(),
                'data' => collect($data)->except(['password', 'password_confirmation'])->toArray()
            ]);
            throw $e;
        }
    }

    /**
     * Actualizar usuario existente con nuevos datos del SAT
     */
    public function updateUser(User $user, array $data): User
    {
        try {
            // Validar datos de entrada (sin validar email único ya que es el mismo usuario)
            $this->validateUpdateData($data);

            // Actualizar el usuario
            $user->update([
                'nombre' => $data['sat_nombre'],
                'rfc' => $data['sat_rfc'],
                'password' => Hash::make($data['password']),
                'verification_token' => Str::random(64),
            ]);

            // Asignar rol de solicitante si no lo tiene
            $this->assignSolicitanteRole($user);

            Log::info('Usuario actualizado exitosamente', [
                'user_id' => $user->id,
                'email' => $user->correo,
                'rfc' => $user->rfc
            ]);

            return $user;

        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'data' => collect($data)->except(['password', 'password_confirmation'])->toArray()
            ]);
            throw $e;
        }
    }

    /**
     * Asignar rol de solicitante al usuario
     */
    protected function assignSolicitanteRole(User $user): void
    {
        try {
            // Verificar si el rol 'solicitante' existe, si no, crearlo
            $solicitanteRole = Role::firstOrCreate(['name' => 'solicitante']);
            
            // Asignar el rol si el usuario no lo tiene
            if (!$user->hasRole('solicitante')) {
                $user->assignRole($solicitanteRole);
                Log::info('Rol solicitante asignado', ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {
            Log::warning('Error al asignar rol solicitante', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
} 