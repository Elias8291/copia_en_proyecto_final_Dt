<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{ForgotPasswordController, ResetPasswordController, RegisterController, LoginController};
use App\Http\Controllers\{
    VerificationController,
    TramiteController,
    UserController,
    ActividadesController,
    CatalogoArchivoController,
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
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/restore', [UserController::class, 'restore'])->name('restore');
        Route::delete('/{user}/force', [UserController::class, 'forceDelete'])->name('force-delete');
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

    // =========================================================================
    // MÓDULO DE CITAS
    // =========================================================================
    Route::resource('citas', \App\Http\Controllers\CitaController::class);

    // =========================================================================
    // MÓDULO DE DÍAS INHÁBILES
    // =========================================================================
    Route::resource('dias-inhabiles', \App\Http\Controllers\DiaInhabilController::class);
    Route::post('/dias-inhabiles/verificar-fecha', [\App\Http\Controllers\DiaInhabilController::class, 'verificarFechaHabil'])->name('dias-inhabiles.verificar-fecha');
    Route::get('/dias-inhabiles/proximos-dias', [\App\Http\Controllers\DiaInhabilController::class, 'proximosDiasHabiles'])->name('dias-inhabiles.proximos-dias');


    // ============================================================================
    // MÓDULO DE REVISIÓN DE TRÁMITES
    // ============================================================================

    Route::middleware(['auth'])->prefix('revision')->name('revision.')->group(function () {
        Route::get('/', [RevisionController::class, 'index'])->name('index');
        
        // Ruta principal que maneja todos los tipos de revisión
        Route::get('/{tramite}/{tipo?}', [RevisionController::class, 'revisarTramite'])
            ->where('tipo', 'seleccion-tipo|documentos-presencial|revision-digital')
            ->name('revisar');
        
        // Rutas de documentos y archivos
        Route::middleware(['auth'])->get('/documentos/{tramite}/{archivo}/{filename}', [RevisionController::class, 'verDocumento'])->name('verDocumento');
        Route::post('/documento/{archivo}/comentario', [RevisionController::class, 'actualizarComentarioDocumento'])->name('documento.comentario');
        Route::post('/documento/{archivo}/estado', [RevisionController::class, 'actualizarEstadoDocumento'])->name('documento.estado');
        Route::get('/documento/{archivo}/estado', [RevisionController::class, 'obtenerEstadoDocumento'])->name('documento.estado.get');
        
        // Rutas de secciones y comentarios
        Route::post('/seccion/comentario', [\App\Http\Controllers\RevisionSeccionController::class, 'store'])->name('seccion.comentario');
        Route::get('/seccion/{tramite}/{seccion}', [\App\Http\Controllers\RevisionSeccionController::class, 'show'])->name('seccion.show');
        
        // Ruta para comentario general
        Route::post('/comentario-general', [RevisionController::class, 'guardarComentarioGeneral'])->name('comentario-general');
        
        // Rutas de información y estado
        Route::get('/{tramite}/informacion-identidad', [RevisionController::class, 'obtenerInformacionIdentidad'])->name('informacion-identidad');
        Route::post('/{tramite}/cambiar-estado', [RevisionController::class, 'cambiarEstadoTramite'])->name('cambiar-estado');
        Route::get('/{tramite}/historial-estados', [RevisionController::class, 'historialEstados'])->name('historial-estados');
    });



    // ============================================================================
    // MÓDULO DE NOTIFICACIONES 
    // ============================================================================

    Route::middleware(['auth'])->prefix('notificaciones')->name('notificaciones.')->group(function () {
        Route::get('/', [NotificacionController::class, 'index'])->name('index');
        Route::get('/contador', [NotificacionController::class, 'contador'])->name('contador');
        Route::get('/header', [NotificacionController::class, 'header'])->name('header');
        Route::post('/marcar-todas-leidas', [NotificacionController::class, 'marcarTodasLeidas'])->name('marcar-todas-leidas');
        Route::post('/marcar-leida', [NotificacionController::class, 'marcarComoLeida'])->name('marcar-leida');
        Route::post('/eliminar-leidas', [NotificacionController::class, 'eliminarLeidas'])->name('eliminar-leidas');
        Route::post('/eliminar', [NotificacionController::class, 'eliminarNotificacion'])->name('eliminar');
        Route::get('/usuario', [NotificacionController::class, 'getUserNotifications'])->name('usuario');
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
