<?php
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\DashboardController;

//rutas publicas
Auth::routes();
Route::get('solicitud/buscar', [SolicitudController::class, 'buscarPorCodigo'])
        ->name('solicitud.buscar');
Route::get('/', [SolicitudController::class, 'create'])->name('solicitud.public.create');

Route::get('solicitud/create', [SolicitudController::class, 'create'])
    ->name('solicitud.create');
Route::post('solicitud', [SolicitudController::class, 'store'])
    ->name('solicitud.store');

//PUBLICAS PARA EL API GATEWAY
Route::post('/api/solicitud', [SolicitudController::class, 'store']);
Route::get('/api/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'logistica',
    ]);
});

Route::middleware(['auth', 'activo'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('home');
    Route::resource('solicitud', SolicitudController::class)
        ->except(['create', 'store']);
    Route::post('solicitud/{id}/aprobar', [SolicitudController::class, 'aprobar'])
        ->name('solicitud.aprobar');
    Route::post('solicitud/{id}/negar', [SolicitudController::class, 'negar'])
        ->name('solicitud.negar');
    Route::resource('solicitante', SolicitanteController::class)
        ->except(['destroy']);
    Route::resource('paquete', PaqueteController::class);
    Route::resource('destino', DestinoController::class)
        ->except(['destroy']);
    Route::resource('marca', MarcaController::class);
    Route::resource('vehiculo', VehiculoController::class);
    Route::resource('tipo-vehiculo', TipoVehiculoController::class);
    Route::resource('conductor', ConductorController::class);
    Route::get('/seguimiento/tracking/{id_paquete}', 
        [HistorialSeguimientoDonacioneController::class, 'tracking'])
        ->name('seguimiento.tracking');
    Route::resource('seguimiento', HistorialSeguimientoDonacioneController::class);

    Route::resource('ubicacion', UbicacionController::class)->except(['destroy']);

    Route::middleware(['auth'])->group(function () {
        Route::view('/perfil/pendiente', 'users.pendiente')
        ->name('perfil.pendiente');
});

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::resource('estado', EstadoController::class);
    Route::resource('reporte', ReporteController::class);
    Route::resource('tipo-licencia', TipoLicenciaController::class);
    Route::resource('tipo-emergencia', TipoEmergenciaController::class);
    Route::resource('rol', RolController::class);
    Route::get('/usuario', [UserAdminController::class, 'index'])
        ->name('usuarios.index');

    Route::post('/usuario/{id}/toggle-admin', [UserAdminController::class, 'toggleAdmin'])
        ->name('usuarios.toggleAdmin');

    Route::post('/usuario/{id}/toggle-activo', [UserAdminController::class, 'toggleActivo'])
        ->name('usuarios.toggleActivo');

    Route::post('/usuario/{id}/cambiar-rol', [UserAdminController::class, 'cambiarRol'])
        ->name('usuarios.cambiarRol');
    Route::delete('solicitante/{solicitante}', [SolicitanteController::class, 'destroy'])
        ->name('solicitante.destroy');
    Route::delete('destino/{destino}', [DestinoController::class, 'destroy'])
        ->name('destino.destroy');
    Route::delete('ubicacion/{ubicacion}', [UbicacionController::class, 'destroy'])
        ->name('ubicacion.destroy');
});



/** 
Route::get('solicitud/buscar', [SolicitudController::class, 'buscarPorCodigo'])->name('solicitud.buscar');
Route::resource('solicitud', SolicitudController::class);
Route::resource('paquete', paqueteController::class);
Route::resource('estado', EstadoController::class);
Route::resource('solicitante', SolicitanteController::class);
Route::resource('destino', controller: DestinoController::class);


Route::resource('reporte', ReporteController::class);

Route::resource('tipo-licencia', TipoLicenciaController::class);

Route::resource('conductor', ConductorController::class);

Route::resource('tipo-vehiculo', TipoVehiculoController::class);

Route::resource('vehiculo', VehiculoController::class);

Route::resource('tipo-emergencia', TipoEmergenciaController::class);

Route::resource('marca', MarcaController::class);

Route::resource('rol', RolController::class);

Route::get('/usuario', [UserAdminController::class, 'index'])->name('usuarios.index');
Route::post('/usuario/{id}/toggle-admin', [UserAdminController::class, 'toggleAdmin'])->name('usuarios.toggleAdmin');
Route::post('/usuario/{id}/toggle-activo', [UserAdminController::class, 'toggleActivo'])->name('usuarios.toggleActivo');
Route::post('/usuario/{id}/cambiar-rol', [UserAdminController::class, 'cambiarRol'])->name('usuarios.cambiarRol');
 */

Route::get('/galeria', [PaqueteController::class, 'galeria'])->name('galeria.index');


