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

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Rutas Principales
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Rutas de Equipos
|--------------------------------------------------------------------------
*/
Route::resource('equipos', EquipoController::class);

/*
|--------------------------------------------------------------------------
| Rutas de Jugadores
|--------------------------------------------------------------------------
*/
Route::resource('jugador', JugadorController::class);

/*
|--------------------------------------------------------------------------
| Rutas de Torneos
|--------------------------------------------------------------------------
*/
Route::resource('torneos', TorneoController::class);
Route::post('/torneos/{torneo}/grupos', [TorneoController::class, 'addGroup'])->name('torneos.addGroup');
Route::delete('/torneos/{torneo}/grupos/{grupo}', [TorneoController::class, 'removeGroup'])->name('torneos.removeGroup');
Route::post('/torneos/{torneo}/equipos', [TorneoController::class, 'addEquipoToTorneo'])->name('torneos.addEquipo');
Route::delete('/torneos/{torneo}/equipos/{equipo}', [TorneoController::class, 'removeEquipoFromTorneo'])->name('torneos.removeEquipo');

/*
|--------------------------------------------------------------------------
| Rutas de Partidos
|--------------------------------------------------------------------------
*/
// Rutas de recursos principales
Route::resource('partidos', PartidoController::class);

// Rutas para obtener datos relacionados
Route::get('/partidos/grupos', [PartidoController::class, 'getGrupos'])->name('partidos.getGrupos');
Route::get('/partidos/equipos', [PartidoController::class, 'getEquipos'])->name('partidos.getEquipos');
Route::get('/partidos/equipos-torneo', [PartidoController::class, 'getEquiposTorneo'])->name('partidos.getEquiposTorneo');

// Rutas para acciones de partidos
Route::post('/partidos/{partido}/acciones', [PartidoController::class, 'registrarAccion'])->name('partidos.registrar-accion');
Route::delete('/partidos/{partido}/acciones/{accion}', [PartidoController::class, 'eliminarAccion'])->name('partidos.eliminar-accion');

/*
|--------------------------------------------------------------------------
| Rutas de Administración de Usuarios y Permisos
|--------------------------------------------------------------------------
*/
Route::resource('usuarios', UserController::class);
Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class)->except(['index', 'show']);

