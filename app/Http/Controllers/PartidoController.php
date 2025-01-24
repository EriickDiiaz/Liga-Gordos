<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Grupo;
use App\Models\AccionPartido;
use Illuminate\Http\Request;

class PartidoController extends Controller
{
    public function index()
    {
        $partidos = Partido::with(['torneo', 'equipoLocal', 'equipoVisitante'])->get();
        return view('partidos.index', compact('partidos'));
    }

    public function create()
    {
        $torneos = Torneo::all();
        return view('partidos.create', compact('torneos'));
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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
            'grupo_id' => 'required|exists:grupos,id',
            'equipo_local_id' => 'required|exists:equipos,id',
            'equipo_visitante_id' => 'required|exists:equipos,id|different:equipo_local_id',
            'fecha' => 'required|date',
            'tipo' => 'required|in:grupo,eliminatoria',
            'fase' => 'nullable|string',
        ]);

        $partido = Partido::create($validatedData);

        return redirect()->route('partidos.show', $partido)->with('success', 'Partido creado exitosamente.');
    }

    public function show(Partido $partido)
    {
        $partido->load(['torneo', 'grupo', 'equipoLocal', 'equipoVisitante', 'acciones.jugador']);
        return view('partidos.show', compact('partido'));
    }

    public function edit(Partido $partido)
    {
        $torneos = Torneo::all();
        $torneo = $partido->torneo;
        $grupos = $torneo->grupos;
        $equipos = $partido->grupo ? $partido->grupo->equipos : collect();
        return view('partidos.edit', compact('partido', 'torneos', 'torneo', 'grupos', 'equipos'));
    }

    public function update(Request $request, Partido $partido)
    {
        $validatedData = $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
            'grupo_id' => 'required|exists:grupos,id',
            'equipo_local_id' => 'required|exists:equipos,id',
            'equipo_visitante_id' => 'required|exists:equipos,id|different:equipo_local_id',
            'fecha' => 'required|date',
            'tipo' => 'required|in:grupo,eliminatoria',
            'fase' => 'nullable|string',
            'estado' => 'required|in:programado,en_curso,finalizado',
        ]);

        $partido->update($validatedData);

        return redirect()->route('partidos.show', $partido)->with('success', 'Partido actualizado exitosamente.');
    }

    public function destroy(Partido $partido)
    {
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

        return redirect()->route('partidos.show', $partido)->with('success', 'Acción registrada exitosamente.');
    }
}

