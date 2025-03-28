<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Grupo;
use App\Models\AccionPartido;
use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartidoController extends Controller
{
    public function index()
    {
        $partidos = Partido::with(['torneo', 'grupo', 'equipoLocal', 'equipoVisitante'])
            ->orderBy('fecha', 'desc')
            ->get();
        return view('partidos.index', compact('partidos'));
    }

    public function create()
    {
        $torneos = Torneo::all();
        $equipos = Equipo::all();
        return view('partidos.create', compact('torneos', 'equipos'));
    }

    public function getGrupos(Request $request)
    {
        $torneo = Torneo::findOrFail($request->torneo_id);
        $grupos = $torneo->grupos;
        return response()->json($grupos);
    }

    public function getEquipos(Request $request)
    {
        $grupo = Grupo::findOrFail($request->grupo_id);
        $equipos = $grupo->equipos;
        return response()->json($equipos);
    }

    public function getEquiposTorneo(Request $request)
    {
        $torneo = Torneo::with('equipos')->findOrFail($request->torneo_id);
        
        // Log para depuración
        Log::info('Obteniendo equipos del torneo:', [
            'torneo_id' => $request->torneo_id,
            'nombre_torneo' => $torneo->nombre,
            'cantidad_equipos' => $torneo->equipos->count()
        ]);
        
        return response()->json($torneo->equipos);
    }

    public function getPartidosIda(Request $request)
    {
        $torneoId = $request->torneo_id;
        $fase = $request->fase;
        
        // Log para depuración
        Log::info('Buscando partidos de ida:', [
            'torneo_id' => $torneoId,
            'fase' => $fase
        ]);
        
        $partidosIda = Partido::where('torneo_id', $torneoId)
            ->where('fase', $fase)
            ->where('tipo', 'eliminatoria')
            ->where('es_ida', true)
            ->with(['equipoLocal', 'equipoVisitante'])
            ->get();
        
        // Filtrar solo los partidos que no tienen partido de vuelta asociado
        $partidosIdaDisponibles = $partidosIda->filter(function($partido) {
            $tieneVuelta = Partido::where('partido_relacionado_id', $partido->id)->exists();
            return !$tieneVuelta;
        });
        
        // Log para depuración
        Log::info('Partidos de ida encontrados:', [
            'total' => $partidosIda->count(),
            'disponibles' => $partidosIdaDisponibles->count()
        ]);
        
        return response()->json($partidosIdaDisponibles->values());
    }

    public function store(Request $request)
    {
        // Log para depuración
        Log::info('Datos recibidos en store:', $request->all());
        
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
            
            // Si es partido de vuelta, debe tener un partido de ida relacionado
            if ($request->es_ida == 0) {
                $rules['partido_relacionado_id'] = 'required|exists:partidos,id';
            }
        } elseif ($request->tipo == 'amistoso') {
            $rules['descripcion'] = 'required|string';
        }

        $validatedData = $request->validate($rules);
        
        // Log para depuración
        Log::info('Datos validados:', $validatedData);

        // Crear el partido
        $partido = Partido::create($validatedData);

        // Si es partido de ida en una eliminatoria, podemos crear automáticamente el de vuelta
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
        }

        return redirect()->route('partidos.show', $partido)->with('success', 'Partido creado exitosamente.');
    }

    public function show(Partido $partido)
    {
        $partido->load(['torneo', 'grupo', 'equipoLocal', 'equipoVisitante', 'acciones.jugador', 'partidoRelacionado']);
        return view('partidos.show', compact('partido'));
    }

    public function edit(Partido $partido)
    {
        $torneos = Torneo::all();
        $equipos = Equipo::all();
        
        // Cargar datos específicos según el tipo de partido
        if ($partido->esLiga()) {
            $torneo = $partido->torneo;
            $grupos = $torneo ? $torneo->grupos : collect();
        } elseif ($partido->esEliminatoria()) {
            $torneo = $partido->torneo;
            $partidoRelacionado = $partido->partidoRelacionado;
        }
        
        return view('partidos.edit', compact('partido', 'torneos', 'equipos'));
    }

    public function update(Request $request, Partido $partido)
    {
        // Log para depuración
        Log::info('Datos recibidos en update:', $request->all());
        
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
        if ($partido->esLiga()) {
            $rules['torneo_id'] = 'required|exists:torneos,id';
            $rules['grupo_id'] = 'required|exists:grupos,id';
            $rules['fase'] = 'nullable|string';
        } elseif ($partido->esEliminatoria()) {
            $rules['torneo_id'] = 'required|exists:torneos,id';
            $rules['fase'] = 'required|string';
        } elseif ($partido->esAmistoso()) {
            $rules['descripcion'] = 'required|string';
        }

        $validatedData = $request->validate($rules);
        
        // Log para depuración
        Log::info('Datos validados en update:', $validatedData);
        
        $partido->update($validatedData);

        return redirect()->route('partidos.show', $partido)->with('success', 'Partido actualizado exitosamente.');
    }

    public function destroy(Partido $partido)
    {
        // Si es un partido de ida con partido de vuelta relacionado, eliminar también el de vuelta
        if ($partido->esEliminatoria() && $partido->esIda() && $partido->partidosVuelta->count() > 0) {
            foreach ($partido->partidosVuelta as $partidoVuelta) {
                $partidoVuelta->delete();
            }
        }
        
        $partido->delete();
        return redirect()->route('partidos.index')->with('success', 'Partido eliminado exitosamente.');
    }

    public function registrarAccion(Request $request, Partido $partido)
    {
        $validatedData = $request->validate([
            'jugador_id' => 'required|exists:jugadores,id',
            'tipo_accion' => 'required|in:gol,tarjeta_amarilla,tarjeta_roja',
        ]);

        $accion = new AccionPartido($validatedData);
        $partido->acciones()->save($accion);

        // Update goal count if the action is a goal
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

    public function eliminarAccion(Partido $partido, AccionPartido $accion)
    {
        // Decrease goal count if the action was a goal
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

    // Método para iniciar un partido (cambiar estado a 'en_curso')
    public function iniciarPartido(Partido $partido)
    {
        if ($partido->estado !== 'programado') {
            return redirect()->route('partidos.show', $partido)->with('error', 'Solo se pueden iniciar partidos que estén programados.');
        }

        $partido->estado = 'en_curso';
        $partido->save();

        return redirect()->route('partidos.show', $partido)->with('success', 'Partido iniciado exitosamente.');
    }

    // Método para finalizar un partido (cambiar estado a 'finalizado')
    public function finalizarPartido(Partido $partido)
    {
        if ($partido->estado !== 'en_curso') {
            return redirect()->route('partidos.show', $partido)->with('error', 'Solo se pueden finalizar partidos que estén en curso.');
        }

        $partido->estado = 'finalizado';
        $partido->save();

        // Si es un partido de liga, actualizar las estadísticas de los equipos en el torneo
        if ($partido->esLiga() && $partido->torneo) {
            $this->actualizarEstadisticasEquipos($partido);
        }

        return redirect()->route('partidos.show', $partido)->with('success', 'Partido finalizado exitosamente.');
    }

    // Método para actualizar las estadísticas de los equipos en un torneo
    private function actualizarEstadisticasEquipos(Partido $partido)
    {
        // Obtener los equipos del partido
        $equipoLocal = $partido->equipoLocal;
        $equipoVisitante = $partido->equipoVisitante;
        $torneo = $partido->torneo;

        // Actualizar estadísticas del equipo local
        $estadisticasLocal = $torneo->equipos()->where('equipo_id', $equipoLocal->id)->first()->pivot;
        $estadisticasLocal->partidos_jugados += 1;
        $estadisticasLocal->goles_favor += $partido->goles_local ?? 0;
        $estadisticasLocal->goles_contra += $partido->goles_visitante ?? 0;

        // Actualizar estadísticas del equipo visitante
        $estadisticasVisitante = $torneo->equipos()->where('equipo_id', $equipoVisitante->id)->first()->pivot;
        $estadisticasVisitante->partidos_jugados += 1;
        $estadisticasVisitante->goles_favor += $partido->goles_visitante ?? 0;
        $estadisticasVisitante->goles_contra += $partido->goles_local ?? 0;

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
    }
}

