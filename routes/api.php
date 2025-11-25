<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\PaqueteController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\SolicitanteController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\DestinoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\HistorialSeguimientoDonacioneController;
use App\Http\Controllers\TipoLicenciaController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\TipoVehiculoController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\TipoEmergenciaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'logistica',
    ]);
});

Route::apiResource('solicitud', SolicitudController::class);
Route::apiResource('paquete', PaqueteController::class);
Route::apiResource('estado', EstadoController::class);
Route::apiResource('solicitante', SolicitanteController::class);
Route::apiResource('ubicacion', UbicacionController::class);
Route::apiResource('destino', DestinoController::class);
Route::apiResource('reporte', ReporteController::class);
Route::apiResource('seguimiento', HistorialSeguimientoDonacioneController::class);
Route::apiResource('tipo-licencia', TipoLicenciaController::class);
Route::apiResource('conductor', ConductorController::class);
Route::apiResource('tipo-vehiculo', TipoVehiculoController::class);
Route::apiResource('vehiculo', VehiculoController::class);
Route::apiResource('tipo-emergencia', TipoEmergenciaController::class);
Route::apiResource('marca', MarcaController::class);
Route::apiResource('rol', RolController::class);

Route::post('solicitud/{id}/aprobar', [SolicitudController::class, 'aprobar']);
Route::post('solicitud/{id}/negar', [SolicitudController::class, 'negar']);

Route::get('usuario', [UserAdminController::class, 'index']);
Route::post('usuario/{id}/toggle-admin', [UserAdminController::class, 'toggleAdmin']);
Route::post('usuario/{id}/toggle-activo', [UserAdminController::class, 'toggleActivo']);
Route::post('usuario/{id}/cambiar-rol', [UserAdminController::class, 'cambiarRol']);
Route::post('login', [LoginController::class, 'apiLogin']);
