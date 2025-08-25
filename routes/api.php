<?php

use App\Http\Controllers\Api\QRExtractorController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\CatalogoActividadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UbicacionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/validate/email', 'validateEmail');
        Route::get('/validate/rfc', 'validateRfc');
    });

    Route::post('/extract-qr-url', [QRExtractorController::class, 'extractQrFromPdf']);

    Route::get('/catalogo/actividades', [CatalogoActividadController::class, 'buscar']);

    Route::controller(ProveedorController::class)->group(function () {
        Route::get('/sectores', 'getSectores');
        Route::get('/actividades', 'getActividades');
    });

    Route::prefix('ubicacion')->controller(UbicacionController::class)->group(function () {
        Route::post('/buscar-codigo-postal', 'buscarPorCodigoPostal');
        Route::get('/estados', 'getEstados');
        Route::post('/municipios-por-estado', 'getMunicipiosPorEstado');
        Route::post('/localidades-por-municipio', 'getLocalidadesPorMunicipio');
    });
});