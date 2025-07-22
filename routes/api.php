<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QRExtractorController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\Api\ErrorController;
use App\Http\Controllers\CatalogoArchivoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Validation Routes
Route::controller(\App\Http\Controllers\UserController::class)->group(function () {
    Route::get('/validate/email', 'validateEmail');
    Route::get('/validate/rfc', 'validateRfc');
});

// Actividades Routes
Route::controller(ActividadesController::class)->group(function () {
    Route::get('/actividades/buscar', 'buscar');
    Route::get('/actividades/por-ids', 'porIds');
});

// Ubicación Routes
Route::controller(UbicacionController::class)->group(function () {
    Route::post('/ubicacion/codigo-postal', 'buscarPorCodigoPostal');
    Route::get('/ubicacion/estados', 'getEstados');
    Route::post('/ubicacion/municipios', 'getMunicipiosPorEstado');
    Route::post('/ubicacion/localidades', 'getLocalidadesPorMunicipio');
});

Route::middleware(['auth:sanctum'])->prefix('archivos')->name('archivos.')->group(function () {
    Route::get('/buscar', [CatalogoArchivoController::class, 'buscar'])->name('buscar');
    Route::get('/estadisticas', [CatalogoArchivoController::class, 'estadisticas'])->name('estadisticas');
});



Route::post('/extract-qr-url', [QRExtractorController::class, 'extractQrFromPdf']);

// Error Testing Routes (only in development)
if (app()->environment(['local', 'development'])) {
    Route::get('/test-error', [ErrorController::class, 'apiTest']);
    Route::post('/test-error', [ErrorController::class, 'apiTest']);
}

// API Error handling routes (fallback)
Route::fallback([ErrorController::class, 'notFound']);