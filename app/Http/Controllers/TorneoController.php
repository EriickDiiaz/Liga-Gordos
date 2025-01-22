<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use Illuminate\Http\Request;

class TorneoController extends Controller
{
    public function index()
    {
        $torneos = Torneo::all();
        return view('torneo.index', compact('torneos'));
    }

    public function create()
    {
        return view('torneo.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:eliminatoria,liga,mixto',
            'fecha_inicio' => 'required|date',
            'estado' => 'required|in:planificado,en_curso,finalizado',
        ]);

        Torneo::create($validatedData);

        return redirect()->route('torneo.index')->with('success', 'Torneo creado exitosamente.');
    }

    public function show(Torneo $torneo)
    {
        return view('torneo.show', compact('torneo'));
    }

    public function edit(Torneo $torneo)
    {
        return view('torneo.edit', compact('torneo'));
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

        return redirect()->route('torneo.index')->with('success', 'Torneo actualizado exitosamente.');
    }

    public function destroy(Torneo $torneo)
    {
        $torneo->delete();
        return redirect()->route('torneo.index')->with('success', 'Torneo eliminado exitosamente.');
    }
}

