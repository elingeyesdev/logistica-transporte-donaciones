<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\paqueteController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\SolicitanteController;
use App\Http\Controllers\UbicacionController;

// Página de bienvenida o raíz
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación (login, registro, logout)
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('solicitud', SolicitudController::class);

//Donacion


Route::resource('paquete', paqueteController::class);
//estados
Route::resource('estado', EstadoController::class);



Route::resource('solicitante', SolicitanteController::class);



Route::resource('ubicacion', UbicacionController::class);

use App\Http\Controllers\DestinoController;

Route::resource('destino', controller: DestinoController::class);


use App\Http\Controllers\ReporteController;
Route::resource('reporte', ReporteController::class);

use App\Http\Controllers\HistorialSeguimientoDonacioneController;

Route::resource('seguimiento', HistorialSeguimientoDonacioneController::class);

use App\Http\Controllers\TipoLicenciaController;
Route::resource('tipo-licencia', TipoLicenciaController::class);

use App\Http\Controllers\ConductorController;
Route::resource('conductor', ConductorController::class);

use App\Http\Controllers\TipoVehiculoController;
Route::resource('tipo-vehiculo', TipoVehiculoController::class);

use App\Http\Controllers\VehiculoController;
Route::resource('vehiculo', VehiculoController::class);

use App\Http\Controllers\TipoEmergenciaController;
Route::resource('tipo-emergencia', TipoEmergenciaController::class);

use App\Http\Controllers\MarcaController;
Route::resource('marca', MarcaController::class);

use App\Http\Controllers\RolController;
Route::resource('rol', RolController::class);

use App\Http\Controllers\UserAdminController;

Route::get('/usuario', [UserAdminController::class, 'index'])->name('usuarios.index');
Route::post('/usuario/{id}/toggle-admin', [UserAdminController::class, 'toggleAdmin'])->name('usuarios.toggleAdmin');
Route::post('/usuario/{id}/toggle-activo', [UserAdminController::class, 'toggleActivo'])->name('usuarios.toggleActivo');

