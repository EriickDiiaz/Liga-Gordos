<?php

use App\Http\Controllers\EquipoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\PatrocinadorController;
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

Route::get('/prueba', function () {
    return response()->json(['mensaje' => 'Ruta de prueba alcanzada']);
});

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
// Rutas para obtener datos relacionados
Route::get('/partidos/grupos', [PartidoController::class, 'getGrupos'])->name('partidos.getGrupos');

Route::get('/partidos/equipos', [PartidoController::class, 'getEquipos'])->name('partidos.getEquipos');
Route::get('/partidos/equipos-torneo', [PartidoController::class, 'getEquiposTorneo'])->name('partidos.getEquiposTorneo');

// Rutas para acciones de partidos
Route::post('/partidos/{partido}/acciones', [PartidoController::class, 'registrarAccion'])->name('partidos.registrar-accion');
Route::delete('/partidos/{partido}/acciones/{accion}', [PartidoController::class, 'eliminarAccion'])->name('partidos.eliminar-accion');

// Rutas para iniciar y finalizar partidos
Route::post('/partidos/{partido}/iniciar', [PartidoController::class, 'iniciarPartido'])->name('partidos.iniciar');
Route::post('/partidos/{partido}/finalizar', [PartidoController::class, 'finalizarPartido'])->name('partidos.finalizar');

// Añadir una ruta para depurar los equipos de un torneo
Route::get('/partidos/debug-equipos-torneo/{torneo}', [PartidoController::class, 'debugEquiposTorneo'])->name('partidos.debugEquiposTorneo');

// Rutas de recursos principales
Route::resource('partidos', PartidoController::class);
/*
|--------------------------------------------------------------------------
| Rutas de Administración de Usuarios.
|--------------------------------------------------------------------------
*/
Route::resource('usuarios', UserController::class);
Route::resource('usuarios', App\Http\Controllers\UserController::class);
Route::patch('/usuarios/{usuario}/toggle-active', [App\Http\Controllers\UserController::class, 'toggleActive'])->name('usuarios.toggle-active');
Route::patch('/usuarios/{usuario}/set-active-until', [App\Http\Controllers\UserController::class, 'setActiveUntil'])->name('usuarios.set-active-until');

/*
|--------------------------------------------------------------------------
| Rutas de Patrocinadores
|--------------------------------------------------------------------------
*/
Route::resource('patrocinador', PatrocinadorController::class);

/*
|--------------------------------------------------------------------------
| Rutas de Noticias
|--------------------------------------------------------------------------
*/
Route::resource('noticias', NoticiaController::class);

/*
|--------------------------------------------------------------------------
| Rutas de Administración de Usuarios, Permisos y Patrocinantes
|--------------------------------------------------------------------------
*/
Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class)->except(['index', 'show']);

