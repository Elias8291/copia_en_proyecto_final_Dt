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
use Illuminate\Support\Facades\Log;
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
        // Validar datos del SAT primero
        $this->validateSATData($request);

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

            Log::info('Usuario registrado exitosamente:', [
                'user_id' => $user->id,
                'email' => $user->correo,
                'rfc' => $satData['rfc'] ?? 'No disponible',
                'proveedor_id' => $proveedor->id ?? 'No creado'
            ]);

            return $this->redirectWithSuccess($isResend, $user);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error en registro de usuario:', [
                'error' => $e->getMessage(),
                'request_data' => $request->only(['email', 'sat_rfc', 'sat_nombre'])
            ]);
            throw $e;
        }
    }

    /** Preparar datos de registro */
    protected function prepareRegistrationData(Request $request): array
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'sat_rfc' => $request->sat_rfc,
            'sat_nombre' => $request->sat_nombre,
        ];

        // Log para debug de datos del SAT recibidos
        if ($request->sat_rfc || $request->sat_nombre) {
            Log::info('Datos del SAT recibidos en registro:', [
                'rfc' => $request->sat_rfc,
                'nombre' => $request->sat_nombre,
                'tipo_persona' => $request->sat_tipo_persona,
                'curp' => $request->sat_curp
            ]);
        }

        return $data;
    }

    /** Preparar datos del SAT para el proveedor */
    protected function prepareSatData(Request $request): array
    {
        $satData = [
            'rfc' => $request->sat_rfc ?? '',
            'razon_social' => $request->sat_nombre ?? '',
            'tipo_persona' => $request->sat_tipo_persona ?? '',
            'curp' => $request->sat_curp ?? '',
            'cp' => $request->sat_cp ?? '',
            'colonia' => $request->sat_colonia ?? '',
            'nombre_vialidad' => $request->sat_nombre_vialidad ?? '',
            'numero_exterior' => $request->sat_numero_exterior ?? '',
            'numero_interior' => $request->sat_numero_interior ?? '',
            'regimen_fiscal' => $request->sat_regimen_fiscal ?? '',
            'situacion_contribuyente' => $request->sat_estatus ?? '',
            'entidad_federativa' => $request->sat_entidad_federativa ?? '',
            'municipio' => $request->sat_municipio ?? '',
            'email_fiscal' => $request->sat_email ?? '',
        ];

        // Filtrar campos vacíos
        $satData = array_filter($satData, function($value) {
            return !empty($value);
        });

        Log::info('Datos del SAT preparados para proveedor:', $satData);

        return $satData;
    }

    /** Buscar usuario no verificado */
    protected function findUnverifiedUser(string $email): ?User
    {
        return User::where('correo', $email)
        ->where('estado', 'Por_Iniciar')
                   ->first();
    }

    /** Validar datos del SAT */
    protected function validateSATData(Request $request): void
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'sat_rfc' => 'required|string|min:12|max:13|regex:/^[A-Z]{3,4}[0-9]{6}[A-Z0-9]{2,3}$/',
            'sat_nombre' => 'required|string|max:255',
        ], [
            'sat_rfc.required' => 'Es necesario cargar una constancia de situación fiscal válida.',
            'sat_rfc.regex' => 'El RFC extraído de la constancia no tiene un formato válido.',
            'sat_nombre.required' => 'No se pudo extraer la razón social de la constancia.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        // Validaciones adicionales del SAT
        if (empty($request->sat_rfc) || empty($request->sat_nombre)) {
            throw new \Exception('Los datos fiscales son incompletos. Por favor, suba una constancia de situación fiscal válida del SAT.');
        }

        // Verificar que el RFC no esté ya registrado
        $existingUser = User::where('rfc', $request->sat_rfc)
            ->where('fecha_verificacion_correo', '!=', null)
            ->first();

        if ($existingUser) {
            throw new \Exception('El RFC ' . $request->sat_rfc . ' ya está registrado en el sistema con el email ' . $existingUser->correo . '. Si es su RFC, por favor use la opción de recuperación de contraseña.');
        }

        Log::info('Validación de datos SAT completada exitosamente:', [
            'rfc' => $request->sat_rfc,
            'email' => $request->email
        ]);
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
