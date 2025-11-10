<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudController;

// Página de bienvenida o raíz
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación (login, registro, logout)
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('solicitud', SolicitudController::class);

//Donacion
use App\Http\Controllers\paqueteController;

Route::resource('paquete', paqueteController::class);

//estados
use App\Http\Controllers\EstadoController;

Route::resource('estado', EstadoController::class);

use App\Http\Controllers\SolicitanteController;

Route::resource('solicitante', SolicitanteController::class);

use App\Http\Controllers\UbicacionController;

Route::resource('ubicacion', UbicacionController::class);

use App\Http\Controllers\DestinoController;

Route::resource('destino', controller: DestinoController::class);


use App\Http\Controllers\ReporteController;

Route::resource('reporte', ReporteController::class);

use App\Http\Controllers\HistorialSeguimientoDonacioneController;

Route::resource('seguimiento', HistorialSeguimientoDonacioneController::class);
