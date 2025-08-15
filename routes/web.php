<?php

use App\Http\Controllers\Api\QRExtractorController;
use App\Http\Controllers\Auth\{ForgotPasswordController, ResetPasswordController, RegisterController, LoginController};
use App\Http\Controllers\{
    VerificationController,
    UserController,
    RolesController,
    RoleController,
    ProfileController,
    TramiteController,
    RevisionController,
    NotificacionController,
    CitasController,
    ArchivoController,
    ProveedoresController,
    LogController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;


Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
});

// Rutas públicas para ver información de proveedores (sin autenticación)
Route::get('/proveedor/{proveedor}/publico', [ProveedoresController::class, 'publico'])->name('proveedores.publico');
Route::get('/proveedor/token/{token}', [ProveedoresController::class, 'publicoPorToken'])->name('proveedores.publico.token');

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

// Ruta de prueba para Tailwind CSS
Route::get('/test-tailwind', function () {
    return view('test-tailwind');
})->name('test-tailwind');


Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Perfil de usuario
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
    });

    // Mi Estado
    Route::get('/mi-estado', [\App\Http\Controllers\MiEstadoController::class, 'index'])->name('mi-estado');

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

   
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RolesController::class, 'index'])->name('index');
        Route::get('/crear', [RolesController::class, 'create'])->name('create');
        Route::post('/', [RolesController::class, 'store'])->name('store');
        Route::get('/{role}', [RolesController::class, 'show'])->name('show');
        Route::get('/{role}/editar', [RolesController::class, 'edit'])->name('edit');
        Route::put('/{role}', [RolesController::class, 'update'])->name('update');
        Route::delete('/{role}', [RolesController::class, 'destroy'])->name('destroy');
    });

    // Rutas para Citas
    Route::prefix('citas')->name('citas.')->group(function () {
        Route::get('/', [CitasController::class, 'index'])->name('index');
        Route::get('/crear', [CitasController::class, 'create'])->name('create');
        Route::post('/', [CitasController::class, 'store'])->name('store');
        Route::get('/{cita}', [CitasController::class, 'show'])->name('show');
        Route::get('/{cita}/editar', [CitasController::class, 'edit'])->name('edit');
        Route::put('/{cita}', [CitasController::class, 'update'])->name('update');
        Route::delete('/{cita}', [CitasController::class, 'destroy'])->name('destroy');
        
        // Acciones específicas
        Route::patch('/{cita}/asistida', [CitasController::class, 'marcarAsistida'])->name('marcar-asistida');
        Route::patch('/{cita}/no-asistio', [CitasController::class, 'marcarNoAsistio'])->name('marcar-no-asistio');
        Route::patch('/{cita}/cancelar', [CitasController::class, 'cancelar'])->name('cancelar');
    });

    Route::prefix('tramites')->name('tramites.')->group(function () {
        Route::get('/', [TramiteController::class, 'index'])->name('index');
        Route::get('/cargar-constancia/{tipo}', [TramiteController::class, 'cargarConstancia'])->name('cargar-constancia');
        Route::post('/procesar-constancia', [TramiteController::class, 'procesarConstancia'])->name('procesar-constancia');
        Route::get('/create', [TramiteController::class, 'create'])->name('create');
        Route::post('/', [TramiteController::class, 'store'])->name('store');
        Route::get('/estado', [TramiteController::class, 'estado'])->name('estado');
        Route::get('/{tramite}/edit', [TramiteController::class, 'edit'])->name('edit');
        Route::put('/{tramite}', [TramiteController::class, 'update'])->name('update');
        

    });

    // Rutas para revisiones
    Route::prefix('revisiones')->name('revisiones.')->group(function () {
        Route::get('/', [RevisionController::class, 'index'])->name('index');
        Route::get('/{tramite}/seleccionar-tipo', [RevisionController::class, 'seleccionarTipoRevision'])->name('seleccionar-tipo');
        Route::post('/{tramite}/iniciar', [RevisionController::class, 'iniciarRevision'])->name('iniciar');
        Route::get('/{tramite}/revisar', [RevisionController::class, 'revisarTramite'])->name('revisar');
        
        // Ruta para procesar revisión digital
        Route::post('/{tramite}/procesar-digital', [RevisionController::class, 'procesarRevisionDigital'])->name('procesar-digital');
        Route::post('/{tramite}/procesar-presencial', [RevisionController::class, 'procesarRevisionPresencial'])->name('procesar-presencial');
        
        // Ruta para procesar asignación de PV y fechas de vigencia
        Route::post('/{tramite}/procesar-asignacion-pv', [RevisionController::class, 'procesarAsignacionPv'])->name('procesar-asignacion-pv');
        
        // Rutas para gestión de citas
        Route::post('/{tramite}/agendar-cita', [RevisionController::class, 'agendarCita'])->name('agendar-cita');
        Route::post('/cita/{cita}/reagendar', [RevisionController::class, 'reagendarCita'])->name('reagendar-cita');
        Route::get('/horarios-disponibles', [RevisionController::class, 'obtenerHorariosDisponibles'])->name('horarios-disponibles');
        
        Route::get('/{tramite}/ver-historico', [RevisionController::class, 'verTramiteHistorico'])->name('ver-historico');
        Route::get('/archivo/{id}', [RevisionController::class, 'mostrarArchivo'])->name('mostrar-archivo')->where('id', '[0-9]+');
        
        // Rutas para evaluación de secciones
        Route::get('/{tramite}/seccion/estado', [RevisionController::class, 'obtenerEstadoSeccion'])->name('seccion.estado');
        Route::post('/{tramite}/seccion/evaluar', [RevisionController::class, 'evaluarSeccion'])->name('seccion.evaluar');
        Route::get('/{tramite}/estado-general', [RevisionController::class, 'obtenerEstadoGeneral'])->name('estado.general');
        
        // Ruta para obtener estados de revisión (AJAX)
        Route::get('/{tramite}/estados', [RevisionController::class, 'obtenerEstadosRevision'])->name('estados');
        
        // Rutas para decisiones finales
        Route::post('/{tramite}/aprobar-y-agendar', [RevisionController::class, 'aprobarYAgendarCita'])->name('aprobar-y-agendar');
        Route::post('/{tramite}/rechazar-correccion', [RevisionController::class, 'rechazarParaCorreccion'])->name('rechazar-correccion');
        Route::post('/{tramite}/rechazar-completo', [RevisionController::class, 'rechazarCompleto'])->name('rechazar-completo');
        
        // Rutas para revisión presencial
        Route::post('/{tramite}/aprobar', [RevisionController::class, 'aprobar'])->name('aprobar');
        Route::post('/{tramite}/rechazar', [RevisionController::class, 'rechazarTramite'])->name('rechazar');

// Limpiar sesión de éxito
Route::post('/limpiar-sesion-exito', [RevisionController::class, 'limpiarSesionExito'])->name('limpiar-sesion-exito');
        
        // Ruta para limpiar sesiones
        Route::post('/limpiar-sesiones', [RevisionController::class, 'limpiarSesiones'])->name('limpiar-sesiones');
    });

    // Rutas para oficios
    Route::prefix('oficios')->name('oficios.')->group(function () {
        Route::get('/descargar', [App\Http\Controllers\OficioController::class, 'descargar'])->name('descargar');
        Route::get('/validar/{tramite}', [App\Http\Controllers\OficioController::class, 'validar'])->name('validar');
        Route::get('/proveedor/{proveedor}', [App\Http\Controllers\OficioController::class, 'porProveedor'])->name('por-proveedor');
        Route::get('/tramite/{tramite}', [App\Http\Controllers\OficioController::class, 'porTramite'])->name('por-tramite');
        Route::post('/{oficio}/estado', [App\Http\Controllers\OficioController::class, 'actualizarEstado'])->name('actualizar-estado');
    });

    // Rutas para notificaciones
    Route::prefix('notificaciones')->name('notificaciones.')->group(function () {
        Route::get('/', [NotificacionController::class, 'index'])->name('index');
        Route::get('/{notificacion}', [NotificacionController::class, 'show'])->name('show');
        Route::post('/{notificacion}/marcar-leida', [NotificacionController::class, 'marcarLeida'])->name('marcar-leida');
        Route::delete('/{notificacion}', [NotificacionController::class, 'destroy'])->name('eliminar');
        Route::post('/marcar-todas-leidas', [NotificacionController::class, 'marcarTodasLeidas'])->name('marcar-todas-leidas');
        
        // Rutas AJAX
        Route::get('/api/conteo-no-leidas', [NotificacionController::class, 'conteoNoLeidas'])->name('conteo-no-leidas');
        Route::get('/api/recientes', [NotificacionController::class, 'recientes'])->name('recientes');
        Route::get('/api/no-leidas', [NotificacionController::class, 'noLeidas'])->name('no-leidas');
        Route::get('/api/recientes-dropdown', [NotificacionController::class, 'recientesParaDropdown'])->name('recientes-dropdown');
        Route::post('/api/marcar-vistas-leidas', [NotificacionController::class, 'marcarVistasComoLeidas'])->name('marcar-vistas-leidas');
        
        // Rutas administrativas
        Route::get('/crear', [NotificacionController::class, 'create'])->name('create');
        Route::post('/', [NotificacionController::class, 'store'])->name('store');
        Route::get('/{notificacion}/editar', [NotificacionController::class, 'edit'])->name('edit');
        Route::put('/{notificacion}', [NotificacionController::class, 'update'])->name('update');
        Route::post('/limpiar-antiguas', [NotificacionController::class, 'limpiarAntiguas'])->name('limpiar-antiguas');
    });

    // Rutas para logs del sistema
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [LogController::class, 'index'])->name('index');
        Route::get('/exportar', [LogController::class, 'exportar'])->name('exportar');
        Route::post('/limpiar', [LogController::class, 'limpiar'])->name('limpiar');
        Route::get('/{log}', [LogController::class, 'show'])->name('show');
    });

    // Rutas para archivos
    Route::prefix('archivos')->name('archivos.')->group(function () {
        Route::get('/', [ArchivoController::class, 'index'])->name('index');
        Route::get('/create', [ArchivoController::class, 'create'])->name('create');
        Route::post('/', [ArchivoController::class, 'store'])->name('store');
        Route::get('/{archivo}', [ArchivoController::class, 'show'])->name('show');
        Route::get('/{archivo}/edit', [ArchivoController::class, 'edit'])->name('edit');
        Route::put('/{archivo}', [ArchivoController::class, 'update'])->name('update');
        Route::delete('/{archivo}', [ArchivoController::class, 'destroy'])->name('destroy');
        Route::get('/{archivo}/download', [ArchivoController::class, 'download'])->name('download');
        Route::patch('/{archivo}/status', [ArchivoController::class, 'updateStatus'])->name('update-status');
        Route::get('/tramite/{tramite}', [ArchivoController::class, 'getArchivosFromTramite'])->name('by-tramite');
        Route::post('/guardar-individual', [ArchivoController::class, 'guardarIndividual'])->name('guardar-individual');
    });

    // Rutas para proveedores
    Route::prefix('proveedores')->name('proveedores.')->group(function () {
        Route::get('/', [ProveedoresController::class, 'index'])->name('index');
        Route::get('/crear', [ProveedoresController::class, 'create'])->name('create');
        Route::post('/', [ProveedoresController::class, 'store'])->name('store');
        Route::get('/{proveedor}', [ProveedoresController::class, 'show'])->name('show');
        Route::get('/{proveedor}/editar', [ProveedoresController::class, 'edit'])->name('edit');
        Route::put('/{proveedor}', [ProveedoresController::class, 'update'])->name('update');
        Route::delete('/{proveedor}', [ProveedoresController::class, 'destroy'])->name('destroy');
    });

});

Route::prefix('api')->group(function () {
    Route::post('/extract-qr-url', [QRExtractorController::class, 'extractQrFromPdf']);
    Route::post('/scrape-sat-data', [QRExtractorController::class, 'scrapeFromUrl']);
});

// Rutas para el extractor de QR (sin prefijo api)
Route::post('/scrape-sat-data', [QRExtractorController::class, 'scrapeFromUrl']);

Route::post('/extract-qr-url-web', [QRExtractorController::class, 'extractQrFromPdf'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('extract.qr.web');



