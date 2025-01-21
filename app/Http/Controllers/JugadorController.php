<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JugadorController extends Controller
{
    public function index()
    {
        $jugadores = Jugador::with('equipo')->get();
        return view('jugador.index', compact('jugadores'));
    }

    public function create()
    {
        $equipos = Equipo::all();
        return view('jugador.create', compact('equipos'));
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateJugador($request);
        
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('jugadores', 'public');
            $validatedData['foto'] = $path;
        }

        Jugador::create($validatedData);
        return redirect()->route('jugador.index')->with('success', 'Jugador creado exitosamente.');
    }

    public function show(Jugador $jugador)
    {
        $jugador->load('equipo');
        return view('jugador.show', compact('jugador'));
    }


    public function edit(Jugador $jugador)
    {
        $equipos = Equipo::all();
        return view('jugador.edit', compact('jugador', 'equipos'));
    }

    public function update(Request $request, Jugador $jugador)
    {
        $validatedData = $this->validateJugador($request, $jugador->id);
        
        if ($request->hasFile('foto')) {
            if ($jugador->foto) {
                Storage::disk('public')->delete($jugador->foto);
            }
            $path = $request->file('foto')->store('jugadores', 'public');
            $validatedData['foto'] = $path;
        }

        $jugador->update($validatedData);
        return redirect()->route('jugador.index')->with('success', 'Jugador actualizado exitosamente.');
    }

    public function destroy(Jugador $jugador)
    {
        if ($jugador->foto) {
            Storage::disk('public')->delete($jugador->foto);
        }
        $jugador->delete();
        return redirect()->route('jugador.index')->with('success', 'Jugador eliminado exitosamente.');
    }

    private function validateJugador(Request $request, $id = null)
    {
        return $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|unique:jugadores,cedula,' . $id,
            'fecha_nacimiento' => 'required|date',
            'dorsal' => 'required|integer|min:1|max:99',
            'tipo' => 'required|in:habilidoso,brazalete,portero',
            'equipo_id' => 'required|exists:equipos,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }
}

