<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\Api\QRExtractorController;
use App\Http\Controllers\TramiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\CatalogoArchivoController;
use App\Http\Controllers\RevisionController;
// Página principal para usuarios no autenticados
Route::middleware(['web', 'guest'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
});


// MÓDULO DE AUTENTICACIÓN Y REGISTRO
Route::middleware(['web', 'guest'])->group(function () {
    // INICIAR SESIÓN
    Route::get('/iniciar-sesion', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/iniciar-sesion', [LoginController::class, 'login']);
    // REGISTRO DE USUARIOS
    Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/registro', [RegisterController::class, 'register']);
    // RECUPERACIÓN DE CONTRASEÑA
    Route::get('/recuperar-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/recuperar-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    // RESET DE CONTRASEÑA
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// CERRAR SESIÓN (requiere autenticación)
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/cerrar-sesion', [LoginController::class, 'logout'])->name('logout');
});

// VERIFICACIÓN DE EMAIL
Route::get('/verificar-email/{id}/{token}', [VerificationController::class, 'verify'])
    ->name('verification.verify');
Route::get('/verify-email/{token}', [RegisterController::class, 'verifyEmail'])
    ->name('verification.verify.token');


// RUTA ESPECIAL PARA QR EXTRACTOR SIN CSRF (ALTERNATIVA)
Route::post('/extract-qr-url-web', [QRExtractorController::class, 'extractQrFromPdf'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('extract.qr.web');

// DASHBOARD (solo autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Ruta temporal para manejar POST requests incorrectos al dashboard
    Route::post('/dashboard', function () {
        return redirect()->route('dashboard');
    });
});

// USERS (solo autenticados)
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

// Notificaciones (solo autenticados, solo vista)
Route::middleware(['auth'])->get('/notificaciones', [App\Http\Controllers\NotificacionController::class, 'index'])->name('notificaciones.index');

// Perfil de usuario (solo autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
});

// Roles (solo autenticados)
Route::middleware(['auth'])->prefix('roles')->name('roles.')->group(function () {
    Route::get('/', [RolesController::class, 'index'])->name('index');
    Route::get('/crear', [RolesController::class, 'create'])->name('create');
    Route::post('/', [RolesController::class, 'store'])->name('store');
    Route::get('/{role}/editar', [RolesController::class, 'edit'])->name('edit');
    Route::put('/{role}', [RolesController::class, 'update'])->name('update');
    Route::delete('/{role}', [RolesController::class, 'destroy'])->name('destroy');
});


//ruta de index tramite
Route::middleware(['auth'])->prefix('tramites')->name('tramites.')->group(function () {
    // Página principal de selección de trámites
    Route::get('/', [TramiteController::class, 'index'])->name('index');
    Route::get('/constancia/{tipo}', [TramiteController::class, 'constancia'])->name('constancia');
    Route::post('/constancia/{tipo}', [TramiteController::class, 'procesarConstancia'])->name('procesarConstancia');
    Route::get('/formulario/{tipo}', [TramiteController::class, 'formulario'])->name('formulario');
    Route::post('/formulario/{tipo}', [TramiteController::class, 'store'])->name('store');

    Route::post('/datos-generales/{tipo}', [TramiteController::class, 'guardarDatosGenerales'])->name('datos-generales.store');
});

// Actividades económicas (solo autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/actividades/buscar', [ActividadesController::class, 'buscador'])->name('actividades.buscar');
});

// Módulo de Catálogo de Archivos (solo autenticados)
Route::middleware(['auth'])->prefix('archivos')->name('archivos.')->group(function () {
    Route::get('/', [CatalogoArchivoController::class, 'index'])->name('index');
    Route::get('/crear', [CatalogoArchivoController::class, 'create'])->name('create');
    Route::post('/', [CatalogoArchivoController::class, 'store'])->name('store');
    Route::get('/{archivo}/editar', [CatalogoArchivoController::class, 'edit'])->name('edit');
    Route::put('/{archivo}', [CatalogoArchivoController::class, 'update'])->name('update');
    Route::delete('/{archivo}', [CatalogoArchivoController::class, 'destroy'])->name('destroy');
});


// Módulo de Revisión (solo autenticados)
Route::middleware(['auth'])->prefix('revision')->name('revision.')->group(function () {
    Route::get('/documentos/{tramite}/{archivo}/{filename}', [RevisionController::class, 'servirDocumento'])->name('documento.completo');
    Route::get('/documento/{archivo}', [RevisionController::class, 'mostrarDocumento'])->name('documento');
    Route::get('/documento/{archivo}/download', [RevisionController::class, 'descargarDocumento'])->name('documento.download');
    Route::post('/documento/{archivo}/toggle-approval', [RevisionController::class, 'toggleApproval'])->name('documento.toggle-approval');
    Route::post('/documento/{archivo}/update-status', [RevisionController::class, 'updateDocumentStatus'])->name('documento.update-status');
    Route::post('/documento/{archivo}/add-comment', [RevisionController::class, 'addDocumentComment'])->name('documento.add-comment');
    Route::get('/', [RevisionController::class, 'index'])->name('index');
    Route::get('/pendientes/lista', [RevisionController::class, 'pendientes'])->name('pendientes');
    Route::get('/en-revision/lista', [RevisionController::class, 'enRevision'])->name('en-revision');
    Route::get('/mis-revisiones/lista', [RevisionController::class, 'misRevisiones'])->name('mis-revisiones');
    Route::get('/api/estadisticas', [RevisionController::class, 'estadisticas'])->name('estadisticas');
    Route::get('/api/exportar', [RevisionController::class, 'exportar'])->name('exportar');
    Route::get('/{tramite}/historial', [RevisionController::class, 'historial'])->name('historial');
    Route::get('/{tramite}/revisar-datos', [RevisionController::class, 'revisarDatos'])->name('revisar-datos');
    Route::patch('/{tramite}/asignar', [RevisionController::class, 'asignar'])->name('asignar');
    Route::patch('/{tramite}/estado', [RevisionController::class, 'cambiarEstado'])->name('cambiarEstado');
    Route::get('/{tramite}', [RevisionController::class, 'show'])->name('show');
});

// Ruta para servir documentos de revisión (solo autenticados)
Route::middleware(['auth'])->get('/documentos/{tramite}/{archivo}/{filename}', [RevisionController::class, 'servirDocumento'])->name('documento.ver');

// API Routes para QR Extractor
Route::middleware(['web'])->prefix('api')->group(function () {
    Route::post('/extract-qr-url', [QRExtractorController::class, 'extractQrFromPdf']);
    Route::post('/scrape-sat-data', [QRExtractorController::class, 'scrapeFromUrl']);
});
