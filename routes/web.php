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

Route::get('/torneos/{torneo}/partidos/create', [PartidoController::class, 'create'])->name('partidos.create');
Route::post('/torneos/{torneo}/partidos', [PartidoController::class, 'store'])->name('partidos.store');
Route::get('/partidos/{partido}/edit', [PartidoController::class, 'edit'])->name('partidos.edit');
Route::put('/partidos/{partido}', [PartidoController::class, 'update'])->name('partidos.update');
Route::delete('/partidos/{partido}', [PartidoController::class, 'destroy'])->name('partidos.destroy');

Route::get('/partidos/grupos', [PartidoController::class, 'getGrupos'])->name('partidos.getGrupos');
Route::get('/partidos/equipos', [PartidoController::class, 'getEquipos'])->name('partidos.getEquipos');
Route::resource('partidos', PartidoController::class);

Route::post('/partidos/{partido}/registrar-accion', [PartidoController::class, 'registrarAccion'])->name('partidos.registrar-accion');

