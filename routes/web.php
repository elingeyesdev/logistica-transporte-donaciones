<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudController; // 👈 importa el controlador

// Página de bienvenida o raíz
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación (login, registro, logout)
Auth::routes();

// Ruta de inicio después de login
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ✅ CRUD de Solicitud
Route::resource('solicitud', SolicitudController::class);

//Donacion
use App\Http\Controllers\DonacionController;

Route::resource('donacion', DonacionController::class);

//estados
use App\Http\Controllers\EstadoController;

Route::resource('estado', EstadoController::class);