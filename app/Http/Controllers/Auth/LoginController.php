<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SystemLogService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function username()
    {
        return 'rfc';
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $user = User::where('rfc', $request->rfc)->first();

        if (!$user) {
            return $this->loginError($request, 'No se encontró una cuenta con este RFC.');
        }

        if (!Hash::check($request->password, $user->password)) {
            SystemLogService::loginFailed($user->correo);
            return $this->loginError($request, 'La contraseña es incorrecta.');
        }

        if (!$this->isUserVerified($user)) {
            return $this->loginError($request, $this->getVerificationMessage($user));
        }

        return $this->performLogin($request, $user);
    }

    public function logout(Request $request)
    {
        if ($user = Auth::user()) {
            SystemLogService::userLogout($user->correo);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'rfc' => 'required|string',
            'password' => 'required|string',
        ], [
            'rfc.required' => 'El RFC es obligatorio',
            'password.required' => 'La contraseña es obligatoria',
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        $user->update(['ultimo_acceso' => now()]);
        session(['last_activity' => now()]);
        SystemLogService::userLogin($user->correo);

        return redirect()->intended(route('dashboard'));
    }

    private function loginError(Request $request, string $message)
    {
        return redirect()->back()
            ->withInput($request->only('rfc', 'remember'))
            ->with('error', $message);
    }

    private function isUserVerified(User $user): bool
    {
        // Si el estado es 'Activo', permitir acceso sin verificar correo
        if ($user->estado === 'activo') {
            return true;
        }
        
        // Para otros estados, verificar que no sea 'pendiente' y que el correo esté verificado
        return $user->estado !== 'pendiente' && $user->fecha_verificacion_correo !== null;
    }

    private function getVerificationMessage(User $user): string
    {
        if ($user->estado === 'pendiente') {
            return 'Tu cuenta está pendiente de verificación. Debes confirmar tu correo electrónico antes de iniciar sesión. Revisa tu bandeja de entrada o spam.';
        }

        if ($user->fecha_verificacion_correo === null) {
            return 'Debes confirmar tu correo electrónico antes de iniciar sesión. Revisa tu bandeja de entrada o spam.';
        }

        return 'Tu cuenta no está activa. Contacta al administrador para más información.';
    }

    private function performLogin(Request $request, User $user)
    {
        $credentials = $request->only('rfc', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return $this->authenticated($request, Auth::user());
        }

        return $this->loginError($request, 'Error en el proceso de autenticación.');
    }
}
