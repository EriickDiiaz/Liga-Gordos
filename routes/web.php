<?php

use App\Http\Controllers\EquipoController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\PartidoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('equipos', EquipoController::class);
Route::resource('jugador', JugadorController::class);
Route::resource('torneos', TorneoController::class);

Route::post('/torneos/{torneo}/grupos', [TorneoController::class, 'addGroup'])->name('torneos.addGroup');
Route::post('/torneos/{torneo}/equipos', [TorneoController::class, 'addEquipoToTorneo'])->name('torneos.addEquipo');
Route::delete('/torneos/{torneo}/equipos/{equipo}', [TorneoController::class, 'removeEquipoFromTorneo'])->name('torneos.removeEquipo');

Route::get('/partidos/grupos', [PartidoController::class, 'getGrupos'])->name('partidos.getGrupos');
Route::get('/partidos/equipos', [PartidoController::class, 'getEquipos'])->name('partidos.getEquipos');
Route::resource('partidos', PartidoController::class);
Route::post('/partidos/{partido}/registrar-accion', [PartidoController::class, 'registrarAccion'])->name('partidos.registrar-accion');
Route::delete('/partidos/{partido}/acciones/{accion}', [PartidoController::class, 'eliminarAccion'])->name('partidos.eliminar-accion');

