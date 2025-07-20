<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProveedorController;
use App\Mail\VerifyEmail;
use App\Jobs\DeleteUnverifiedUsers;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /** Mostrar formulario de registro */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /** Procesar registro de usuario */
    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
            $registrationData = $this->prepareRegistrationData($request);
            $existingUser = $this->findUnverifiedUser($request->email);
            $userController = new UserController();
            $proveedorController = new ProveedorController();
            
            if ($existingUser) {
                $user = $userController->updateUser($existingUser, $registrationData);
                $isResend = true;
            } else {
                $user = $userController->createUser($registrationData);
                $isResend = false;
            }

            // Crear proveedor con datos del SAT
            $satData = $this->prepareSatData($request);
            $proveedor = $proveedorController->createProveedor($user, $satData);

            Mail::to($user->correo)->send(new VerifyEmail($user));
            DeleteUnverifiedUsers::dispatch($user->id)->delay(now()->addHours(72));

            DB::commit();

            return $this->redirectWithSuccess($isResend, $user);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /** Preparar datos de registro */
    protected function prepareRegistrationData(Request $request): array
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'sat_rfc' => $request->sat_rfc,
            'sat_nombre' => $request->sat_nombre,
        ];
    }

    /** Preparar datos del SAT para el proveedor */
    protected function prepareSatData(Request $request): array
    {
        return [
            'rfc' => $request->sat_rfc ?? '',
            'razon_social' => $request->sat_nombre ?? '',
            'tipo_persona' => $request->sat_tipo_persona ?? '',
            'curp' => $request->sat_curp ?? '',
            'cp' => $request->sat_cp ?? '',
            'colonia' => $request->sat_colonia ?? '',
            'nombre_vialidad' => $request->sat_nombre_vialidad ?? '',
            'numero_exterior' => $request->sat_numero_exterior ?? '',
            'numero_interior' => $request->sat_numero_interior ?? '',
        ];
    }

    /** Buscar usuario no verificado */
    protected function findUnverifiedUser(string $email): ?User
    {
        return User::where('correo', $email)
        ->where('estado', 'Por_Iniciar')
                   ->first();
    }
    /** Redirigir con mensaje de éxito */
    protected function redirectWithSuccess(bool $isResend, User $user)
    {
        $title = $isResend ? '¡Actualización Exitosa!' : '¡Registro Exitoso!';
            
        $message = $isResend 
            ? 'Se ha actualizado tu información y reenviado el correo de verificación. Revisa tu bandeja de entrada.'
            : 'Se ha enviado un correo de verificación a ' . $user->correo . '. Tu cuenta de proveedor ha sido creada y está en revisión. Se te asignará un número de proveedor una vez que tu cuenta sea aprobada. Revisa tu bandeja de entrada y sigue las instrucciones para activar tu cuenta.';

        return back()->with([
            'show_success_modal' => true,
            'modal_title' => $title,
            'modal_message' => $message,
            'modal_button_text' => 'Ir al Login',
            'modal_redirect_to' => route('login')
        ]);
    }
}
