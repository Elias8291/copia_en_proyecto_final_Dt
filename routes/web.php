<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{ForgotPasswordController, ResetPasswordController, RegisterController, LoginController};
use App\Http\Controllers\{
    VerificationController,
    TramiteController,
    UserController,
    ActividadesController,
    CatalogoArchivoController,
    CitasController,
    RevisionController,
    RolesController,
    NotificacionController,
    ProfileController
};
use App\Http\Controllers\Api\QRExtractorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================================================
// RUTAS PÚBLICAS
// ============================================================================

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
});

// ============================================================================
// AUTENTICACIÓN
// ============================================================================

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/iniciar-sesion', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/iniciar-sesion', [LoginController::class, 'login']);

    // Registro
    Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/registro', [RegisterController::class, 'register']);

    // Recuperación de contraseña
    Route::get('/recuperar-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/recuperar-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Logout
Route::middleware('auth')->post('/cerrar-sesion', [LoginController::class, 'logout'])->name('logout');

// Verificación de email
Route::get('/verificar-email/{id}/{token}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('/verify-email/{token}', [RegisterController::class, 'verifyEmail'])->name('verification.verify.token');
Route::post('/reenviar-verificacion', [VerificationController::class, 'resend'])->name('verification.resend');

// ============================================================================
// RUTAS AUTENTICADAS
// ============================================================================

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    // Notificaciones
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');

    // ============================================================================
    // MÓDULO DE TRÁMITES
    // ============================================================================

    Route::prefix('tramites')->name('tramites.')->group(function () {
        Route::get('/', [TramiteController::class, 'index'])->name('index');
        Route::get('/constancia/{tipo}', [TramiteController::class, 'constancia'])->name('constancia');
        Route::post('/constancia/{tipo}', [TramiteController::class, 'procesarConstancia'])->name('procesarConstancia');
        Route::get('/formulario/{tipo}', [TramiteController::class, 'formulario'])->name('formulario');
        Route::post('/{tipo}', [TramiteController::class, 'store'])->name('store');
        Route::get('/exito', [TramiteController::class, 'exito'])->name('exito');
        Route::get('/estado', function () {
            return view('tramites.estado');
        })->name('estado');
    });

    // ============================================================================
    // MÓDULO DE USUARIOS
    // ============================================================================

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // ============================================================================
    // MÓDULO DE ROLES
    // ============================================================================

    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RolesController::class, 'index'])->name('index');
        Route::get('/crear', [RolesController::class, 'create'])->name('create');
        Route::post('/', [RolesController::class, 'store'])->name('store');
        Route::get('/{role}/editar', [RolesController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RolesController::class, 'update'])->name('update');
        Route::delete('/{role}', [RolesController::class, 'destroy'])->name('destroy');
    });

    // ============================================================================
    // MÓDULO DE ACTIVIDADES ECONÓMICAS
    // ============================================================================

    Route::get('/actividades/buscar', [ActividadesController::class, 'buscador'])->name('actividades.buscar');

    // ============================================================================
    // MÓDULO DE CATÁLOGO DE ARCHIVOS
    // ============================================================================

    Route::prefix('archivos')->name('archivos.')->group(function () {
        Route::get('/', [CatalogoArchivoController::class, 'index'])->name('index');
        Route::get('/crear', [CatalogoArchivoController::class, 'create'])->name('create');
        Route::post('/', [CatalogoArchivoController::class, 'store'])->name('store');
        Route::get('/{archivo}/editar', [CatalogoArchivoController::class, 'edit'])->name('edit');
        Route::put('/{archivo}', [CatalogoArchivoController::class, 'update'])->name('update');
        Route::delete('/{archivo}', [CatalogoArchivoController::class, 'destroy'])->name('destroy');
    });

    // ============================================================================
    // MÓDULO DE CITAS
    // ============================================================================

    Route::prefix('citas')->name('citas.')->group(function () {
        Route::get('/', [CitasController::class, 'index'])->name('index');
        Route::get('/crear', [CitasController::class, 'create'])->name('create');
        Route::post('/', [CitasController::class, 'store'])->name('store');
        Route::get('/{cita}', [CitasController::class, 'show'])->name('show');
        Route::get('/{cita}/editar', [CitasController::class, 'edit'])->name('edit');
        Route::put('/{cita}', [CitasController::class, 'update'])->name('update');
        Route::delete('/{cita}', [CitasController::class, 'destroy'])->name('destroy');
        Route::patch('/{cita}/estado', [CitasController::class, 'cambiarEstado'])->name('cambiarEstado');
        Route::get('/api/estadisticas', [CitasController::class, 'estadisticas'])->name('estadisticas');
    });

    // ============================================================================
    // MÓDULO DE REVISIÓN DE TRÁMITES
    // ============================================================================

    Route::middleware(['auth'])->prefix('revision')->name('revision.')->group(function () {
        Route::get('/', [RevisionController::class, 'index'])->name('index');
        Route::get('/{tramite}/revisar-datos', [RevisionController::class, 'revisarDatos'])->name('revisar-datos');
        Route::middleware(['auth'])->get('/documentos/{tramite}/{archivo}/{filename}', [RevisionController::class, 'verDocumento'])->name('verDocumento');
        Route::get('/{tramite}', [RevisionController::class, 'show'])->name('show');
        // Nueva ruta para actualizar comentario de documento
        Route::post('/documento/{archivo}/comentario', [RevisionController::class, 'actualizarComentarioDocumento'])->name('documento.comentario');
        // Nueva ruta para actualizar estado de documento
        Route::post('/documento/{archivo}/estado', [RevisionController::class, 'actualizarEstadoDocumento'])->name('documento.estado');
    });
    
});

// ============================================================================
// API ROUTES
// ============================================================================

Route::prefix('api')->group(function () {
    Route::post('/extract-qr-url', [QRExtractorController::class, 'extractQrFromPdf']);
    Route::post('/scrape-sat-data', [QRExtractorController::class, 'scrapeFromUrl']);
});

// QR Extractor sin CSRF (ruta especial)
Route::post('/extract-qr-url-web', [QRExtractorController::class, 'extractQrFromPdf'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('extract.qr.web');
