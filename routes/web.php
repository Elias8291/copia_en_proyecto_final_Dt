<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\CitasController;
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
Route::post('/reenviar-verificacion', [VerificationController::class, 'resend'])
    ->name('verification.resend');

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

    // Primer paso: Cargar constancia del SAT
    Route::get('/constancia/{tipo}', [TramiteController::class, 'constancia'])->name('constancia');
    Route::post('/constancia/{tipo}', [TramiteController::class, 'procesarConstancia'])->name('procesarConstancia');

    // Segundo paso: Formulario del trámite (con datos precargados)
    Route::get('/formulario/{tipo}', [TramiteController::class, 'formulario'])->name('formulario');

    // ✅ CORREGIDO: Ruta de procesamiento independiente
    Route::post('/{tipo}', [TramiteController::class, 'store'])->name('store');

    // Página de éxito
    Route::get('/exito', function () {
        return view('tramites.exito');
    })->name('exito');
});

// RUTA PARA VERIFICAR DATOS EN BD
Route::get('/tramites/verificar', function () {
    $tramites = \App\Models\Tramite::latest()->take(5)->get();
    $datos = \App\Models\DatosGenerales::latest()->take(5)->get();

    return response()->json([
        'total_tramites' => \App\Models\Tramite::count(),
        'ultimos_tramites' => $tramites->map(function ($t) {
            return [
                'id' => $t->id,
                'tipo' => $t->tipo_tramite,
                'estado' => $t->estado,
                'fecha' => $t->created_at->format('Y-m-d H:i:s')
            ];
        }),
        'ultimos_datos_generales' => $datos->map(function ($d) {
            return [
                'tramite_id' => $d->tramite_id,
                'rfc' => $d->rfc,
                'razon_social' => $d->razon_social
            ];
        })
    ]);
})->name('tramites.verificar');

// RUTA PARA VERIFICAR ACTIVIDADES Y DOCUMENTOS
Route::get('/tramites/verificar-completo/{tramiteId?}', function ($tramiteId = null) {
    $tramiteId = $tramiteId ?? \App\Models\Tramite::latest()->first()?->id;

    if (!$tramiteId) {
        return response()->json(['error' => 'No hay trámites en la base de datos']);
    }

    $tramite = \App\Models\Tramite::find($tramiteId);
    if (!$tramite) {
        return response()->json(['error' => 'Trámite no encontrado']);
    }

    $actividades = \App\Models\Actividad::where('tramite_id', $tramiteId)->get();
    $archivos = \App\Models\Archivo::where('tramite_id', $tramiteId)->get();

    return response()->json([
        'tramite' => [
            'id' => $tramite->id,
            'tipo' => $tramite->tipo_tramite,
            'estado' => $tramite->estado,
            'proveedor_id' => $tramite->proveedor_id,
            'fecha_creacion' => $tramite->created_at->format('d/m/Y H:i'),
        ],
        'actividades' => $actividades->map(function ($a) {
            return [
                'id' => $a->id,
                'tramite_id' => $a->tramite_id,
                'actividad_id' => $a->actividad_id,
                'created_at' => $a->created_at->format('Y-m-d H:i:s')
            ];
        }),
        'archivos' => $archivos->map(function ($a) {
            return [
                'id' => $a->id,
                'tramite_id' => $a->tramite_id,
                'catalogo_id' => $a->idCatalogoArchivo,
                'nombre_original' => $a->nombre_original,
                'ruta_archivo' => $a->ruta_archivo,
                'created_at' => $a->created_at->format('Y-m-d H:i:s')
            ];
        }),
        'resumen' => [
            'total_actividades' => count($actividades),
            'total_archivos' => count($archivos),
            'mensaje' => count($actividades) > 0 && count($archivos) > 0 ?
                '✅ DATOS COMPLETOS' :
                '⚠️ FALTAN DATOS: Act=' . count($actividades) . ', Arc=' . count($archivos)
        ]
    ]);
})->name('tramites.verificar.completo');

// Actividades económicas (solo autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/actividades/buscar', [ActividadesController::class, 'buscador'])->name('actividades.buscar');
});

// Módulo de Catálogo de Archivos (solo autenticados)
Route::middleware(['auth'])->prefix('archivos')->name('archivos.')->group(function () {
    // Rutas principales CRUD
    Route::get('/', [CatalogoArchivoController::class, 'index'])->name('index');
    Route::get('/crear', [CatalogoArchivoController::class, 'create'])->name('create');
    Route::post('/', [CatalogoArchivoController::class, 'store'])->name('store');
    Route::get('/{archivo}/editar', [CatalogoArchivoController::class, 'edit'])->name('edit');
    Route::put('/{archivo}', [CatalogoArchivoController::class, 'update'])->name('update');
    Route::delete('/{archivo}', [CatalogoArchivoController::class, 'destroy'])->name('destroy');
});

// Módulo de Citas (solo autenticados)
Route::middleware(['auth'])->prefix('citas')->name('citas.')->group(function () {
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

// Módulo de Revisión de Trámites (solo autenticados)
Route::middleware(['auth'])->prefix('revision')->name('revision.')->group(function () {
    Route::get('/', [RevisionController::class, 'index'])->name('index');
    Route::get('/{tramite}', [RevisionController::class, 'show'])->name('show');
    Route::get('/pendientes/lista', [RevisionController::class, 'pendientes'])->name('pendientes');
    Route::get('/en-revision/lista', [RevisionController::class, 'enRevision'])->name('en-revision');
    Route::get('/mis-revisiones/lista', [RevisionController::class, 'misRevisiones'])->name('mis-revisiones');
    Route::get('/{tramite}/historial', [RevisionController::class, 'historial'])->name('historial');
    Route::patch('/{tramite}/asignar', [RevisionController::class, 'asignar'])->name('asignar');
    Route::patch('/{tramite}/estado', [RevisionController::class, 'cambiarEstado'])->name('cambiarEstado');
    Route::get('/api/estadisticas', [RevisionController::class, 'estadisticas'])->name('estadisticas');
    Route::get('/api/exportar', [RevisionController::class, 'exportar'])->name('exportar');
});

// API Routes para QR Extractor
Route::middleware(['web'])->prefix('api')->group(function () {
    Route::post('/extract-qr-url', [QRExtractorController::class, 'extractQrFromPdf']);
    Route::post('/scrape-sat-data', [QRExtractorController::class, 'scrapeFromUrl']);
});
