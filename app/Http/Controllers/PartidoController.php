<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Grupo;
use App\Models\AccionPartido;
use App\Models\Jugador;
use App\Models\Patrocinador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PartidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        // Añade aquí cualquier middleware de permisos si es necesario
    }

    public function index(Request $request)
    {
        $now = Carbon::now();
        $filtro = $request->get('filtro', 'destacados'); // destacados, todos, proximos, recientes, fecha
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $fechaEspecifica = $request->get('fecha_especifica');
        $patrocinadores = Patrocinador::all();
        
        $query = Partido::with(['torneo', 'grupo', 'equipoLocal', 'equipoVisitante']);
        
        switch ($filtro) {
            case 'proximos':
                $partidos = $query->where('fecha', '>=', $now)
                    ->orderBy('fecha', 'asc')
                    ->get();
                break;
                
            case 'recientes':
                $partidos = $query->where('fecha', '<', $now)
                    ->orderBy('fecha', 'desc')
                    ->get();
                break;
                
            case 'fecha':
                if ($fechaEspecifica) {
                    $partidos = $query->whereDate('fecha', $fechaEspecifica)
                        ->orderBy('fecha', 'asc')
                        ->get();
                } elseif ($fechaInicio && $fechaFin) {
                    $partidos = $query->whereBetween('fecha', [$fechaInicio, $fechaFin])
                        ->orderBy('fecha', 'asc')
                        ->get();
                } else {
                    $partidos = collect();
                }
                break;
                
            case 'todos':
                $partidos = $query->orderBy('fecha', 'desc')->get();
                break;
                
            default: // destacados
                // Próximos 6 partidos y últimos 6 partidos
                $proximosPartidos = $query->where('fecha', '>=', $now)
                    ->orderBy('fecha', 'asc')
                    ->limit(6)
                    ->get();
                    
                $recientesPartidos = Partido::with(['torneo', 'grupo', 'equipoLocal', 'equipoVisitante'])
                    ->where('fecha', '<', $now)
                    ->orderBy('fecha', 'desc')
                    ->limit(6)
                    ->get();
                    
                $partidos = $proximosPartidos->concat($recientesPartidos);
                break;
        }
        
        // Estadísticas para mostrar en la vista
        $totalPartidos = Partido::count();
        $partidosHoy = Partido::whereDate('fecha', $now->toDateString())->count();
        $proximosPartidos = Partido::where('fecha', '>=', $now)->count();
        $partidosFinalizados = Partido::where('estado', 'finalizado')->count();
        
        return view('partidos.index', compact(
            'partidos', 
            'filtro', 
            'fechaInicio', 
            'fechaFin', 
            'fechaEspecifica',
            'totalPartidos',
            'partidosHoy',
            'proximosPartidos',
            'partidosFinalizados',
            'patrocinadores'
        ));
    }

    public function create()
    {
        $torneos = Torneo::all();
        $equipos = Equipo::all();
        return view('partidos.create', compact('torneos', 'equipos'));
    }

    /**
     * Obtiene los grupos de un torneo específico
     */
    public function getGrupos(Request $request)
    {
        try {
            // Registrar la solicitud para depuración
            Log::info('Solicitud getGrupos recibida', [
                'torneo_id' => $request->torneo_id,
                'request_url' => $request->fullUrl()
            ]);
            
            $torneo = Torneo::findOrFail($request->torneo_id);
            $grupos = $torneo->grupos;
            
            // Registrar la respuesta para depuración
            Log::info('Respuesta getGrupos', [
                'grupos_count' => $grupos->count(),
                'grupos' => $grupos->pluck('nombre', 'id')
            ]);
            
            return response()->json($grupos);
        } catch (\Exception $e) {
            Log::error('Error en getGrupos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene los equipos de un grupo específico
     */
    public function getEquipos(Request $request)
    {
        try {
            // Registrar la solicitud para depuración
            Log::info('Solicitud getEquipos recibida', [
                'grupo_id' => $request->grupo_id,
                'request_url' => $request->fullUrl()
            ]);
            
            $grupo = Grupo::findOrFail($request->grupo_id);
            $equipos = $grupo->equipos;
            
            // Registrar la respuesta para depuración
            Log::info('Respuesta getEquipos', [
                'equipos_count' => $equipos->count(),
                'equipos' => $equipos->pluck('nombre', 'id')
            ]);
            
            return response()->json($equipos);
        } catch (\Exception $e) {
            Log::error('Error en getEquipos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtiene todos los equipos de un torneo
     */
    public function getEquiposTorneo(Request $request)
    {
        try {
            // Registrar la solicitud para depuración
            Log::info('Solicitud getEquiposTorneo recibida', [
                'torneo_id' => $request->torneo_id,
                'request_url' => $request->fullUrl()
            ]);
            
            $torneo = Torneo::findOrFail($request->torneo_id);
            $equipos = $torneo->equipos;
            
            // Registrar la respuesta para depuración
            Log::info('Respuesta getEquiposTorneo', [
                'equipos_count' => $equipos->count(),
                'equipos' => $equipos->pluck('nombre', 'id')
            ]);
            
            return response()->json($equipos);
        } catch (\Exception $e) {
            Log::error('Error en getEquiposTorneo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Almacena un nuevo partido en la base de datos
     */
    public function store(Request $request)
    {
        // Registrar los datos recibidos para depuración
        Log::info('Datos recibidos en store', $request->all());
        
        // Validación base para todos los tipos de partidos
        $rules = [
            'equipo_local_id' => 'required|exists:equipos,id',
            'equipo_visitante_id' => 'required|exists:equipos,id|different:equipo_local_id',
            'fecha' => 'required|date',
            'tipo' => 'required|in:grupo,eliminatoria,amistoso',
            'estado' => 'required|in:programado,en_curso,finalizado',
        ];

        // Validaciones específicas según el tipo de partido
        if ($request->tipo == 'grupo') {
            $rules['torneo_id'] = 'required|exists:torneos,id';
            $rules['grupo_id'] = 'required|exists:grupos,id';
            $rules['fase'] = 'nullable|string';
        } elseif ($request->tipo == 'eliminatoria') {
            $rules['torneo_id'] = 'required|exists:torneos,id';
            $rules['fase'] = 'required|string';
            $rules['es_ida'] = 'required|boolean';
        } elseif ($request->tipo == 'amistoso') {
            $rules['descripcion'] = 'required|string';
        }

        $validatedData = $request->validate($rules);
        
        // Registrar los datos validados para depuración
        Log::info('Datos validados en store', $validatedData);

        // Crear el partido
        $partido = Partido::create($validatedData);

        // Si es partido de ida en una eliminatoria y se solicitó crear el partido de vuelta
        if ($request->tipo == 'eliminatoria' && $request->es_ida == 1 && isset($request->crear_vuelta)) {
            $partidoVuelta = new Partido();
            $partidoVuelta->torneo_id = $partido->torneo_id;
            $partidoVuelta->equipo_local_id = $partido->equipo_visitante_id;
            $partidoVuelta->equipo_visitante_id = $partido->equipo_local_id;
            $partidoVuelta->fecha = date('Y-m-d H:i:s', strtotime($partido->fecha . ' +1 week')); // Una semana después por defecto
            $partidoVuelta->tipo = 'eliminatoria';
            $partidoVuelta->fase = $partido->fase;
            $partidoVuelta->estado = 'programado';
            $partidoVuelta->es_ida = false;
            $partidoVuelta->partido_relacionado_id = $partido->id;
            $partidoVuelta->save();
            
            Log::info('Partido de vuelta creado', [
                'partido_ida_id' => $partido->id,
                'partido_vuelta_id' => $partidoVuelta->id
            ]);
        }

        return redirect()->route('partidos.show', $partido)->with('success', 'Partido creado exitosamente.');
    }

    public function show(Partido $partido)
    {
        $partido->load(['torneo', 'grupo', 'equipoLocal', 'equipoVisitante', 'acciones.jugador', 'partidoRelacionado']);
        $patrocinadores = Patrocinador::all();
        return view('partidos.show', compact('partido', 'patrocinadores'));
    }

    public function edit(Partido $partido)
    {
        $torneos = Torneo::all();
        $equipos = Equipo::all();
        $grupos = collect();
        
        // Si el partido pertenece a un torneo, cargar sus grupos
        if ($partido->torneo_id) {
            $grupos = Torneo::find($partido->torneo_id)->grupos;
        }
        
        return view('partidos.edit', compact('partido', 'torneos', 'equipos', 'grupos'));
    }

    public function update(Request $request, Partido $partido)
    {
        // Registrar los datos recibidos para depuración
        Log::info('Datos recibidos en update', $request->all());
        
        // Validación base para todos los tipos de partidos
        $rules = [
            'equipo_local_id' => 'required|exists:equipos,id',
            'equipo_visitante_id' => 'required|exists:equipos,id|different:equipo_local_id',
            'fecha' => 'required|date',
            'estado' => 'required|in:programado,en_curso,finalizado',
            'goles_local' => 'nullable|integer|min:0',
            'goles_visitante' => 'nullable|integer|min:0',
        ];

        // No permitimos cambiar el tipo de partido una vez creado
        // pero validamos campos específicos según el tipo actual
        if ($partido->tipo == 'grupo') {
            $rules['torneo_id'] = 'required|exists:torneos,id';
            $rules['grupo_id'] = 'required|exists:grupos,id';
            $rules['fase'] = 'nullable|string';
        } elseif ($partido->tipo == 'eliminatoria') {
            $rules['torneo_id'] = 'required|exists:torneos,id';
            $rules['fase'] = 'required|string';
        } elseif ($partido->tipo == 'amistoso') {
            $rules['descripcion'] = 'required|string';
        }

        $validatedData = $request->validate($rules);
        
        // Registrar los datos validados para depuración
        Log::info('Datos validados en update', $validatedData);
        
        // Guardar el estado anterior para verificar si ha cambiado
        $estadoAnterior = $partido->estado;
        
        $partido->update($validatedData);
        
        // Si el partido ha cambiado a finalizado, actualizar estadísticas
        if ($estadoAnterior != 'finalizado' && $partido->estado == 'finalizado' && $partido->tipo == 'grupo') {
            $this->actualizarEstadisticasEquipos($partido);
        }

        return redirect()->route('partidos.show', $partido)->with('success', 'Partido actualizado exitosamente.');
    }

    public function destroy(Partido $partido)
    {
        // Si es un partido de ida con partido de vuelta relacionado, eliminar también el de vuelta
        if ($partido->tipo == 'eliminatoria' && $partido->es_ida && $partido->partidosVuelta()->count() > 0) {
            foreach ($partido->partidosVuelta as $partidoVuelta) {
                $partidoVuelta->delete();
            }
        }
        
        $partido->delete();
        return redirect()->route('partidos.index')->with('success', 'Partido eliminado exitosamente.');
    }

    /**
     * Registra una acción en un partido (gol, tarjeta, etc.)
     */
    public function registrarAccion(Request $request, Partido $partido)
    {
        $validatedData = $request->validate([
            'jugador_id' => 'required|exists:jugadores,id',
            'tipo_accion' => 'required|in:gol,tarjeta_amarilla,tarjeta_roja',
        ]);

        $accion = new AccionPartido($validatedData);
        $partido->acciones()->save($accion);

        // Actualizar conteo de goles si la acción es un gol
        if ($validatedData['tipo_accion'] === 'gol') {
            $jugador = Jugador::findOrFail($validatedData['jugador_id']);
            if ($jugador->equipo_id === $partido->equipo_local_id) {
                $partido->goles_local = ($partido->goles_local ?? 0) + 1;
            } elseif ($jugador->equipo_id === $partido->equipo_visitante_id) {
                $partido->goles_visitante = ($partido->goles_visitante ?? 0) + 1;
            }
            $partido->save();
        }

        return redirect()->route('partidos.show', $partido)->with('success', 'Acción registrada exitosamente.');
    }

    /**
     * Elimina una acción de un partido
     */
    public function eliminarAccion(Partido $partido, AccionPartido $accion)
    {
        // Disminuir conteo de goles si la acción era un gol
        if ($accion->tipo_accion === 'gol') {
            $jugador = $accion->jugador;
            if ($jugador->equipo_id === $partido->equipo_local_id) {
                $partido->goles_local = max(0, ($partido->goles_local ?? 0) - 1);
            } elseif ($jugador->equipo_id === $partido->equipo_visitante_id) {
                $partido->goles_visitante = max(0, ($partido->goles_visitante ?? 0) - 1);
            }
            $partido->save();
        }

        $accion->delete();
        return redirect()->route('partidos.show', $partido)->with('success', 'Acción eliminada exitosamente.');
    }

    /**
     * Cambia el estado de un partido a 'en_curso'
     */
    public function iniciarPartido(Partido $partido)
    {
        if ($partido->estado !== 'programado') {
            return redirect()->route('partidos.show', $partido)->with('error', 'Solo se pueden iniciar partidos que estén programados.');
        }

        $partido->estado = 'en_curso';
        $partido->save();

        return redirect()->route('partidos.show', $partido)->with('success', 'Partido iniciado exitosamente.');
    }

    /**
     * Cambia el estado de un partido a 'finalizado'
     */
    public function finalizarPartido(Partido $partido)
    {
        if ($partido->estado !== 'en_curso') {
            return redirect()->route('partidos.show', $partido)->with('error', 'Solo se pueden finalizar partidos que estén en curso.');
        }

        $partido->estado = 'finalizado';
        $partido->save();

        // Si es un partido de liga, actualizar las estadísticas de los equipos en el torneo
        if ($partido->tipo == 'grupo' && $partido->torneo) {
            $this->actualizarEstadisticasEquipos($partido);
        }

        return redirect()->route('partidos.show', $partido)->with('success', 'Partido finalizado exitosamente.');
    }

    /**
     * Actualiza las estadísticas de los equipos en un torneo después de un partido
     */
    private function actualizarEstadisticasEquipos(Partido $partido)
    {
        // Obtener los equipos del partido
        $equipoLocal = $partido->equipoLocal;
        $equipoVisitante = $partido->equipoVisitante;
        $torneo = $partido->torneo;

        // Verificar que el partido tenga goles registrados
        if ($partido->goles_local === null || $partido->goles_visitante === null) {
            Log::warning('No se pueden actualizar estadísticas: partido sin goles registrados', [
                'partido_id' => $partido->id
            ]);
            return;
        }

        try {
            // Actualizar estadísticas del equipo local
            $estadisticasLocal = $torneo->equipos()->where('equipo_id', $equipoLocal->id)->first()->pivot;
            $estadisticasLocal->partidos_jugados += 1;
            $estadisticasLocal->goles_favor += $partido->goles_local;
            $estadisticasLocal->goles_contra += $partido->goles_visitante;

            // Actualizar estadísticas del equipo visitante
            $estadisticasVisitante = $torneo->equipos()->where('equipo_id', $equipoVisitante->id)->first()->pivot;
            $estadisticasVisitante->partidos_jugados += 1;
            $estadisticasVisitante->goles_favor += $partido->goles_visitante;
            $estadisticasVisitante->goles_contra += $partido->goles_local;

            // Determinar el resultado y actualizar victorias, empates y derrotas
            if ($partido->goles_local > $partido->goles_visitante) {
                // Victoria local
                $estadisticasLocal->victorias += 1;
                $estadisticasLocal->puntos += 3;
                $estadisticasVisitante->derrotas += 1;
            } elseif ($partido->goles_local < $partido->goles_visitante) {
                // Victoria visitante
                $estadisticasVisitante->victorias += 1;
                $estadisticasVisitante->puntos += 3;
                $estadisticasLocal->derrotas += 1;
            } else {
                // Empate
                $estadisticasLocal->empates += 1;
                $estadisticasLocal->puntos += 1;
                $estadisticasVisitante->empates += 1;
                $estadisticasVisitante->puntos += 1;
            }

            // Guardar los cambios
            $torneo->equipos()->updateExistingPivot($equipoLocal->id, [
                'partidos_jugados' => $estadisticasLocal->partidos_jugados,
                'victorias' => $estadisticasLocal->victorias,
                'empates' => $estadisticasLocal->empates,
                'derrotas' => $estadisticasLocal->derrotas,
                'goles_favor' => $estadisticasLocal->goles_favor,
                'goles_contra' => $estadisticasLocal->goles_contra,
                'puntos' => $estadisticasLocal->puntos
            ]);

            $torneo->equipos()->updateExistingPivot($equipoVisitante->id, [
                'partidos_jugados' => $estadisticasVisitante->partidos_jugados,
                'victorias' => $estadisticasVisitante->victorias,
                'empates' => $estadisticasVisitante->empates,
                'derrotas' => $estadisticasVisitante->derrotas,
                'goles_favor' => $estadisticasVisitante->goles_favor,
                'goles_contra' => $estadisticasVisitante->goles_contra,
                'puntos' => $estadisticasVisitante->puntos
            ]);
            
            Log::info('Estadísticas actualizadas correctamente', [
                'partido_id' => $partido->id,
                'equipo_local' => $equipoLocal->nombre,
                'equipo_visitante' => $equipoVisitante->nombre
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar estadísticas', [
                'partido_id' => $partido->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
