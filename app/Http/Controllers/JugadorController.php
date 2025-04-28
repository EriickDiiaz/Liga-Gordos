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
        
        $this->validateJugador($request);

        $jugador = new Jugador($request->all());
        
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('fotos'), $fotoName);
            $jugador->foto = 'fotos/' . $fotoName;
        } else {
            $jugador->foto = 'img/default-player.png';
        }
        
        $jugador->save();

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
        $this->validateJugador($request, $jugador->id);

        $jugador->fill($request->except('foto'));

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('fotos'), $fotoName);
            $jugador->foto = 'fotos/' . $fotoName;
        }

        $jugador->save();
        return redirect()->route('jugador.index')->with('success', 'Jugador actualizado exitosamente.');
    }

    public function destroy(Jugador $jugador)
    {
        if ($jugador->foto && $jugador->foto != 'img/default-player.png') {
            $fotoPath = public_path($jugador->foto);
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
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
            'dorsal' => 'required|integer|min:0|max:999',
            'tipo' => 'required|in:habilidoso,brazalete,portero',
            'equipo_id' => 'required|exists:equipos,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }
}

