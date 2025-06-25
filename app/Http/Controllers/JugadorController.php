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

        return redirect()->route('jugador.show', $jugador->id)->with('success', 'Jugador creado exitosamente.');

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
            // Elimina la foto anterior si no es la predeterminada
            if ($jugador->foto && $jugador->foto != 'img/default-player.png' && file_exists(public_path($jugador->foto))) {
                unlink(public_path($jugador->foto));
            }
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('fotos'), $fotoName);
            $jugador->foto = 'fotos/' . $fotoName;
        }

        $jugador->save();

        return redirect()->route('jugador.show', $jugador->id)->with('success', 'Jugador actualizado exitosamente.');
    }

    public function destroy(Jugador $jugador)
    {
        if ($jugador->foto && $jugador->foto != 'img/default-player.png' && file_exists(public_path($jugador->foto))) {
            unlink(public_path($jugador->foto));
        }
        $jugador->delete();
        return redirect()->route('jugador.index')->with('success', 'Jugador eliminado exitosamente.');
    }

    private function validateJugador(Request $request, $id = null)
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|integer|unique:jugadores,cedula,' . $id,
            'fecha_nacimiento' => 'required|date',
            'dorsal' => 'required|integer|min:0|max:999',
            'tipo' => 'required|in:habilidoso,brazalete,portero',
            'equipo_id' => 'required|exists:equipos,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ];

        $messages = [
            'nombre.required' => 'El nombre del jugador es obligatorio.',
            'nombre.string' => 'El nombre del jugador debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del jugador no puede tener más de 255 caracteres.',
            'cedula.required' => 'La cédula del jugador es obligatoria.',
            'cedula.integer' => 'La cédula solo puede contener números.',
            'cedula.unique' => 'La cédula ya está registrada para otro jugador.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'dorsal.required' => 'El dorsal del jugador es obligatorio.',
            'dorsal.integer' => 'El dorsal debe ser un número entero.',
            'dorsal.min' => 'El dorsal no puede ser menor que 0.',
            'dorsal.max' => 'El dorsal no puede ser mayor que 999.',
            'tipo.required' => 'El tipo de jugador es obligatorio.',
            'tipo.in' => 'El tipo de jugador debe ser uno de los siguientes: habilidoso, brazalete, portero.',
            'equipo_id.required' => 'El equipo del jugador es obligatorio.',
            'equipo_id.exists' => 'El equipo seleccionado no existe.',
            'foto.image' => 'La foto debe ser una imagen.',
            'foto.mimes' => 'La foto debe ser un archivo de tipo: jpeg, png, jpg, gif.',
            'foto.max' => 'La foto no puede exceder los 10 MB.',
        ];

        return $request->validate($rules, $messages);
    }
}

