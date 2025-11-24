<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\paqueteController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('solicitud/buscar', [SolicitudController::class, 'buscarPorCodigo'])->name('solicitud.buscar');
Route::resource('solicitud', SolicitudController::class);
Route::resource('paquete', paqueteController::class);
Route::resource('estado', EstadoController::class);
Route::resource('solicitante', SolicitanteController::class);
Route::resource('ubicacion', UbicacionController::class);
Route::resource('destino', controller: DestinoController::class);


Route::resource('reporte', ReporteController::class);


Route::resource('seguimiento', HistorialSeguimientoDonacioneController::class);

Route::resource('tipo-licencia', TipoLicenciaController::class);

Route::resource('conductor', ConductorController::class);

Route::resource('tipo-vehiculo', TipoVehiculoController::class);

Route::resource('vehiculo', VehiculoController::class);

Route::resource('tipo-emergencia', TipoEmergenciaController::class);

Route::resource('marca', MarcaController::class);

Route::resource('rol', RolController::class);


Route::post('/api/solicitud', [SolicitudController::class, 'store']);

Route::get('/api/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'logistica',
    ]);
});

Route::get('/usuario', [UserAdminController::class, 'index'])->name('usuarios.index');
Route::post('/usuario/{id}/toggle-admin', [UserAdminController::class, 'toggleAdmin'])->name('usuarios.toggleAdmin');
Route::post('/usuario/{id}/toggle-activo', [UserAdminController::class, 'toggleActivo'])->name('usuarios.toggleActivo');
Route::post('/usuario/{id}/cambiar-rol', [UserAdminController::class, 'cambiarRol'])->name('usuarios.cambiarRol');

//MANEJO DE SOLICITUDES

Route::post('solicitud/{id}/aprobar', [SolicitudController::class, 'aprobar'])->name('solicitud.aprobar');

Route::post('solicitud/{id}/negar', [SolicitudController::class, 'negar'])->name('solicitud.negar');


