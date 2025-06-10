<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TorneoController extends Controller
{
    public function index()
    {
        $torneos = Torneo::with(['grupos.equipos', 'equipos', 'partidos.equipoLocal', 'partidos.equipoVisitante'])->get();
        
        // Calcular tablas de posiciones para cada grupo de cada torneo
        $tablasPosiciones = [];
        foreach ($torneos as $torneo) {
            $tablasPosiciones[$torneo->id] = [];
            foreach ($torneo->grupos as $grupo) {
                $tablasPosiciones[$torneo->id][$grupo->id] = $this->calcularTablaPosiciones($grupo);
            }
        }
        
        return view('torneos.index', compact('torneos', 'tablasPosiciones'));
    }

    public function create()
    {
        $equipos = Equipo::all();
        return view('torneos.create', compact('equipos'));
    }

    public function store(Request $request)
    {
        $this->authorize('Crear Torneos');

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:eliminatoria,liga,mixto',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|in:planificado,en_curso,finalizado',
        ]);

        $torneo = Torneo::create($validatedData);

        return redirect()->route('torneos.index')->with('success', 'Torneo actualizado exitosamente.');
    }

    public function show(Torneo $torneo)
    {
        $torneo->load('grupos.equipos', 'equipos', 'partidos');
        
        $tablasPosiciones = [];
        foreach ($torneo->grupos as $grupo) {
            $tablasPosiciones[$grupo->id] = $this->calcularTablaPosiciones($grupo);
        }
        
        //return view('torneos.show', compact('torneo', 'tablasPosiciones'));
    }

    public function edit(Torneo $torneo)
    {
        $torneo->load('grupos.equipos');
        return view('torneos.edit', compact('torneo'));
    }

    public function update(Request $request, Torneo $torneo)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:eliminatoria,liga,mixto',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|in:planificado,en_curso,finalizado',
        ]);

        $torneo->update($validatedData);

        return redirect()->route('torneos.index')->with('success', 'Torneo actualizado exitosamente.');
    }

    public function destroy(Torneo $torneo)
    {
        $torneo->delete();
        return redirect()->route('torneos.index')->with('success', 'Torneo eliminado exitosamente.');
    }

    public function addGroup(Request $request, Torneo $torneo)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $grupo = $torneo->grupos()->create($validatedData);

        return redirect()->route('torneos.edit', $torneo)->with('success', 'Grupo agregado exitosamente.');
    }

    public function removeGroup(Torneo $torneo, Grupo $grupo)
    {
        $grupo->delete();

        return redirect()->route('torneos.edit', $torneo)->with('success', 'Grupo eliminado exitosamente.');
    }

    public function addEquipoToTorneo(Request $request, Torneo $torneo)
    {
        $validatedData = $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'grupo_id' => 'required|exists:grupos,id',
        ]);

        $torneo->equipos()->attach($validatedData['equipo_id'], [
            'grupo_id' => $validatedData['grupo_id'],
        ]);

        return redirect()->route('torneos.edit', $torneo)->with('success', 'Equipo agregado exitosamente al grupo.');
    }

    public function removeEquipoFromTorneo(Torneo $torneo, Equipo $equipo)
    {
        $torneo->equipos()->detach($equipo->id);

        return redirect()->route('torneos.edit', $torneo)->with('success', 'Equipo removido exitosamente del torneo.');
    }

// Método para calcular la tabla de posiciones
    private function calcularTablaPosiciones($grupo)
    {
        $tabla = [];
        foreach ($grupo->equipos as $equipo) {
            $estadisticas = [
                'equipo' => $equipo,
                'PJ' => 0,
                'PG' => 0,
                'PE' => 0,
                'PP' => 0,
                'GF' => 0,
                'GC' => 0,
                'DG' => 0,
                'PTS' => 0
            ];

            // Solo considerar partidos de liga (tipo='grupo')
            foreach ($grupo->torneo->partidos->where('tipo', 'grupo') as $partido) {
                if ($partido->equipo_local_id == $equipo->id || $partido->equipo_visitante_id == $equipo->id) {
                    // Solo contar partidos finalizados
                    if ($partido->estado == 'finalizado') {
                        $estadisticas['PJ']++;
                        
                        if ($partido->equipo_local_id == $equipo->id) {
                            $estadisticas['GF'] += $partido->goles_local;
                            $estadisticas['GC'] += $partido->goles_visitante;
                            
                            if ($partido->goles_local > $partido->goles_visitante) {
                                $estadisticas['PG']++;
                                $estadisticas['PTS'] += 3;
                            } elseif ($partido->goles_local < $partido->goles_visitante) {
                                $estadisticas['PP']++;
                            } else {
                                $estadisticas['PE']++;
                                $estadisticas['PTS'] += 1;
                            }
                        } else {
                            $estadisticas['GF'] += $partido->goles_visitante;
                            $estadisticas['GC'] += $partido->goles_local;
                            
                            if ($partido->goles_visitante > $partido->goles_local) {
                                $estadisticas['PG']++;
                                $estadisticas['PTS'] += 3;
                            } elseif ($partido->goles_visitante < $partido->goles_local) {
                                $estadisticas['PP']++;
                            } else {
                                $estadisticas['PE']++;
                                $estadisticas['PTS'] += 1;
                            }
                        }
                    }
                }
            }
            
            $estadisticas['DG'] = $estadisticas['GF'] - $estadisticas['GC'];
            $tabla[] = $estadisticas;
        }

        usort($tabla, function($a, $b) {
            if ($a['PTS'] == $b['PTS']) {
                return $b['DG'] - $a['DG'];
            }
            return $b['PTS'] - $a['PTS'];
        });

        return $tabla;
    }


}

