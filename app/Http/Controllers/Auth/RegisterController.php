<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{

    protected function create(array $data)
    {
        DB::beginTransaction();

        try {
            $emailPos = strpos($data['email'], '@');
            $nombre = $data['sat_nombre'] ?? 'Usuario ' . ($emailPos !== false ? substr($data['email'], 0, $emailPos) : $data['email']);
            $correo = $data['sat_email'] ?? $data['email'];
            $rfc = $data['sat_rfc'] ?? null;

            $user = User::create([
                'nombre' => $nombre,
                'correo' => $correo,
                'password' => Hash::make($data['password']),
                'rfc' => !empty($rfc) ? strtoupper($rfc) : null,
                'verification' => false,
                'verification_token' => Str::random(60),
            ]);

            $verificationUrl = url('/verify-email/' . $user->verification_token);

            Mail::send('emails.verify-email', [
                'user' => $user,
                'verificationUrl' => $verificationUrl,
                'expirationHours' => 24
            ], function ($message) use ($user) {
                $message->to($user->correo, $user->nombre)
                        ->subject('Verifica tu cuenta - Padrón de Proveedores');
            });

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear usuario: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    public function register(RegisterRequest $request)
    {
        try {
            Log::info('Registro iniciado', [
                'email' => $request->email,
                'tiene_datos_sat' => !empty($request->sat_rfc),
                'sat_rfc' => $request->sat_rfc,
                'sat_nombre' => $request->sat_nombre
            ]);

            $data = [
                'email' => $request->email,
                'password' => $request->password,
                'sat_nombre' => $request->sat_nombre,
                'sat_email' => $request->sat_email,
                'sat_rfc' => $request->sat_rfc,
            ];

            $this->create($data);

            // Redirigir con session flash para mostrar el modal
            return redirect()->route('register')->with([
                'showSuccessModal' => true,
                'userEmail' => $request->email
            ]);

        } catch (\Exception $e) {
            Log::error('Error al registrar usuario: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Error al registrar usuario. Por favor, inténtalo de nuevo.');
        }
    }

    public function determinarTipoPersona(string $rfc): string
    {
        $rfc = strtoupper(trim($rfc));

        if (!preg_match('/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/', $rfc)) {
            Log::warning('RFC inválido, se asume Persona Física', ['rfc' => $rfc]);
            return 'Física';
        }

        return match (strlen($rfc)) {
            12 => tap('Moral', fn() => Log::info('RFC detectado como Persona Moral', ['rfc' => $rfc])),
            13 => tap('Física', fn() => Log::info('RFC detectado como Persona Física', ['rfc' => $rfc])),
            default => tap('Física', fn() => Log::warning('RFC con longitud no estándar', [
                'rfc' => $rfc,
                'length' => strlen($rfc)
            ])),
        };
    }
    public function verify($token)
    {
        Log::info('Intento de verificación', ['token' => substr($token, 0, 10) . '...']);

        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            Log::warning('Token de verificación no válido', ['token' => substr($token, 0, 10) . '...']);
            return redirect()->route('register')->with('error', 'Token de verificación inválido. Regístrate nuevamente.');
        }

        $user->verification = true;
        $user->verification_token = null;
        $user->save();

        Log::info('Usuario verificado', [
            'user_id' => $user->id,
            'email' => $user->correo
        ]);

        return redirect()->route('login')->with([
            'showEmailVerifiedModal' => true,
            'success' => '¡Correo verificado! Ya puedes iniciar sesión.'
        ]);
    }

    public function verifyEmail($token)
    {
        return $this->verify($token);
    }
}
