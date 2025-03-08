<?php

use App\Http\Controllers\EquipoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

Route::resource('equipos', EquipoController::class);
Route::resource('jugador', JugadorController::class);
Route::resource('torneos', TorneoController::class);
route::resource('usuarios', UserController::class);

Route::post('/torneos/{torneo}/grupos', [TorneoController::class, 'addGroup'])->name('torneos.addGroup');
Route::delete('/torneos/{torneo}/grupos/{grupo}', [TorneoController::class, 'removeGroup'])->name('torneos.removeGroup');
Route::post('/torneos/{torneo}/equipos', [TorneoController::class, 'addEquipoToTorneo'])->name('torneos.addEquipo');
Route::delete('/torneos/{torneo}/equipos/{equipo}', [TorneoController::class, 'removeEquipoFromTorneo'])->name('torneos.removeEquipo');

Route::get('/partidos/grupos', [PartidoController::class, 'getGrupos'])->name('partidos.getGrupos');
Route::get('/partidos/equipos', [PartidoController::class, 'getEquipos'])->name('partidos.getEquipos');
Route::resource('partidos', PartidoController::class);
Route::post('/partidos/{partido}/registrar-accion', [PartidoController::class, 'registrarAccion'])->name('partidos.registrar-accion');
Route::delete('/partidos/{partido}/acciones/{accion}', [PartidoController::class, 'eliminarAccion'])->name('partidos.eliminar-accion');

Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class)->except(['index', 'show']);

