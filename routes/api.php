<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RfcSearchController;
use App\Http\Controllers\HistorialProveedorController;
use App\Http\Controllers\Api\SectorController;
use App\Http\Controllers\LocationDataController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\Formularios\DocumentosController;
use App\Http\Controllers\Api\GoogleMapsProxyController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\Api\ActividadController;
use App\Http\Controllers\Api\QrPdfController;
use App\Http\Controllers\Api\QRExtractorController;

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


Route::post('/extract-qr-url', [QRExtractorController::class, 'extractQrFromPdf']);