<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\RolesController;


use App\Http\Controllers\TramiteController;

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
    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
});
















// TRÁMITES - Flujo completo: selección → constancia → formulario → envío
Route::middleware(['auth'])->prefix('tramites')->name('tramites.')->group(function () {
    // Página principal de selección de trámites
    Route::get('/', [TramiteController::class, 'index'])->name('index');
    
    // Primer paso: Cargar constancia del SAT
    Route::get('/constancia/{tipo}', [TramiteController::class, 'constancia'])->name('constancia');
    Route::post('/constancia/{tipo}', [TramiteController::class, 'procesarConstancia'])->name('procesarConstancia');
    
    // Segundo paso: Formulario del trámite (con datos precargados)
    Route::get('/formulario/{tipo}', [TramiteController::class, 'formulario'])->name('formulario');
    Route::post('/formulario/{tipo}', [TramiteController::class, 'store'])->name('store');
});

// API Routes para QR Extractor (públicas para uso en welcome y otros)
Route::middleware(['web'])->prefix('api')->group(function () {
    Route::post('/extract-qr-url', [App\Http\Controllers\Api\QRExtractorController::class, 'extractQrFromPdf']);
    Route::post('/scrape-sat-data', [App\Http\Controllers\Api\QRExtractorController::class, 'scrapeFromUrl']);
});

