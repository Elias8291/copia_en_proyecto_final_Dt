<?php

use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\Api\QRExtractorController;
// use App\Http\Controllers\CatalogoArchivoController;
use App\Http\Controllers\CatalogoActividadController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\UbicacionController;
use Illuminate\Support\Facades\Route;


Route::controller(\App\Http\Controllers\UserController::class)->group(function () {
    Route::get('/validate/email', 'validateEmail');
    Route::get('/validate/rfc', 'validateRfc');
});

// Documentos por tipo de persona (sin autenticación para el modal)
// Route::get('/documentos/{tipoPersona}', [CatalogoArchivoController::class, 'porTipoPersona']);

// QR Extraction Route (sin middleware de autenticación)
Route::post('/extract-qr-url', [QRExtractorController::class, 'extractQrFromPdf']);

// Catálogo de actividades
Route::get('/catalogo/actividades', [CatalogoActividadController::class, 'buscar']);

// Ubicación API routes
Route::prefix('ubicacion')->group(function () {
    Route::post('/buscar-codigo-postal', [UbicacionController::class, 'buscarPorCodigoPostal']);
    Route::get('/estados', [UbicacionController::class, 'getEstados']);
    Route::post('/municipios-por-estado', [UbicacionController::class, 'getMunicipiosPorEstado']);
    Route::post('/localidades-por-municipio', [UbicacionController::class, 'getLocalidadesPorMunicipio']);
});

// Revisiones API routes
Route::prefix('revisiones')->group(function () {
    Route::get('/{tramite}/estados', [RevisionController::class, 'obtenerEstadosRevision']);
});

// Citas de Revisión API routes
Route::prefix('citas')->middleware('auth')->group(function () {
    Route::post('/agendar-revision', [\App\Http\Controllers\CitasController::class, 'agendarRevision']);
});

// Revisores API routes
Route::prefix('revisores')->middleware('auth')->group(function () {
    Route::post('/disponibles', [\App\Http\Controllers\Api\RevisoresController::class, 'obtenerDisponibles']);
});



