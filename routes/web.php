<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VerificationController;

// Página principal para usuarios no autenticados
Route::middleware(['web', 'guest'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
});

// Rutas públicas para API (sin autenticación)
Route::prefix('api')->group(function () {
    // Búsqueda por código postal (pública)
    Route::get('/codigo-postal/buscar', [App\Http\Controllers\Api\CodigoPostalController::class, 'buscarPorCodigoPostal'])
        ->name('api.codigo-postal.buscar.public');
    
    // Búsqueda de actividades económicas (pública)
    Route::get('/actividades/buscar', [App\Http\Controllers\Api\ActividadController::class, 'buscar'])
        ->name('api.actividades.buscar.public');
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
Route::post('/reenviar-verificacion', [VerificationController::class, 'resend'])
    ->name('verification.resend');

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
Route::middleware(['auth'])->group(function () {
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
});

// Notificaciones (solo autenticados, solo vista)
Route::middleware(['auth'])->get('/notificaciones', [App\Http\Controllers\NotificacionController::class, 'index'])->name('notificaciones.index');

// Perfil de usuario (solo autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
});

// Roles (solo autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/roles', [App\Http\Controllers\RolesController::class, 'index'])->name('roles.index');
});

// Tramites (solo autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/tramites', [App\Http\Controllers\TramiteController::class, 'index'])->name('tramites.index');
    Route::get('/tramites/inscripcion', [App\Http\Controllers\TramiteController::class, 'inscripcion'])->name('tramites.inscripcion');
    Route::get('/tramites/renovacion', [App\Http\Controllers\TramiteController::class, 'renovacion'])->name('tramites.renovacion');
    Route::get('/tramites/actualizacion', [App\Http\Controllers\TramiteController::class, 'actualizacion'])->name('tramites.actualizacion');
});

// API Routes (solo autenticados)
Route::middleware(['auth'])->prefix('api')->group(function () {
    // Rutas para código postal
    Route::get('/codigo-postal/{codigo}', [App\Http\Controllers\Api\CodigoPostalController::class, 'buscar'])
        ->name('api.codigo-postal.buscar');
    Route::get('/codigo-postal/municipios', [App\Http\Controllers\Api\CodigoPostalController::class, 'buscarMunicipios'])
        ->name('api.codigo-postal.municipios');
    Route::get('/codigo-postal/asentamientos', [App\Http\Controllers\Api\CodigoPostalController::class, 'buscarAsentamientos'])
        ->name('api.codigo-postal.asentamientos');
    
    // Rutas para actividades económicas
    Route::get('/actividades/buscar', [App\Http\Controllers\Api\ActividadesController::class, 'buscar'])
        ->name('api.actividades.buscar');
    Route::get('/actividades/por-ids', [App\Http\Controllers\Api\ActividadesController::class, 'porIds'])
        ->name('api.actividades.por-ids');
});