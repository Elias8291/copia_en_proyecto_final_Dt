<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QRExtractorController;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\UserController;

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

Route::post('/extract-qr-url', [QRExtractorController::class, 'extractQrFromPdf']);