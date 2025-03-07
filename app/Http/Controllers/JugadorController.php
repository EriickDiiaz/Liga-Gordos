<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class JugadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Corregimos la forma de usar los middlewares de permisos
        $this->middleware('can:crear jugadores', ['only' => ['create', 'store']]);
        $this->middleware('can:editar jugadores', ['only' => ['edit', 'update']]);
        $this->middleware('can:borrar jugadores', ['only' => ['destroy']]);
    }

    public function index()
    {
        if (Auth::user()->hasRole('Capitán')) {
            // Si es capitán, solo puede ver jugadores de su equipo
            if (!Auth::user()->equipo_id) {
                return redirect()->route('equipos.index')
                    ->with('error', 'No tienes un equipo asignado como capitán.');
            }
            $jugadores = Jugador::where('equipo_id', Auth::user()->equipo_id)->get();
        } else {
            // Otros roles con permisos pueden ver todos los jugadores
            $jugadores = Jugador::all();
        }
        return view('jugador.index', compact('jugadores'));
    }

    public function create()
    {
        if (Auth::user()->hasRole('Capitán')) {
            // Si es capitán, solo puede crear jugadores para su equipo
            if (!Auth::user()->equipo_id) {
                return redirect()->route('equipos.index')
                    ->with('error', 'No tienes un equipo asignado como capitán.');
            }
            $equipos = Equipo::where('id', Auth::user()->equipo_id)->get();
            $equipo_id = Auth::user()->equipo_id;
            return view('jugador.create', compact('equipos', 'equipo_id'));
        } else {
            // Otros roles con permisos pueden crear jugadores para cualquier equipo
            $equipos = Equipo::all();
            return view('jugador.create', compact('equipos'));
        }
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateJugador($request);
        
        // Verificar si el usuario es capitán y está intentando crear un jugador para otro equipo
        if (Auth::user()->hasRole('Capitán') && Auth::user()->equipo_id != $validatedData['equipo_id']) {
            return redirect()->route('jugador.index')
                ->with('error', 'No puedes crear jugadores para otros equipos.');
        }
        
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('jugadores', 'public');
            $validatedData['foto'] = $path;
        } else {
            $validatedData['foto'] = 'img/default-player.png';
        }

        Jugador::create($validatedData);
        return redirect()->route('jugador.index')->with('success', 'Jugador creado exitosamente.');
    }

    public function show(Jugador $jugador)
    {
        // Verificar si el usuario es capitán y está intentando ver un jugador de otro equipo
        if (Auth::user()->hasRole('Capitán') && Auth::user()->equipo_id != $jugador->equipo_id) {
            return redirect()->route('jugador.index')
                ->with('error', 'No puedes ver jugadores de otros equipos.');
        }
        
        $jugador->load('equipo');
        return view('jugador.show', compact('jugador'));
    }

    public function edit(Jugador $jugador)
    {
        // Verificar si el usuario es capitán y está intentando editar un jugador de otro equipo
        if (Auth::user()->hasRole('Capitán') && Auth::user()->equipo_id != $jugador->equipo_id) {
            return redirect()->route('jugador.index')
                ->with('error', 'No puedes editar jugadores de otros equipos.');
        }
        
        if (Auth::user()->hasRole('Capitán')) {
            $equipos = Equipo::where('id', Auth::user()->equipo_id)->get();
        } else {
            $equipos = Equipo::all();
        }
        
        return view('jugador.edit', compact('jugador', 'equipos'));
    }

    public function update(Request $request, Jugador $jugador)
    {
        // Verificar si el usuario es capitán y está intentando actualizar un jugador de otro equipo
        if (Auth::user()->hasRole('Capitán') && Auth::user()->equipo_id != $jugador->equipo_id) {
            return redirect()->route('jugador.index')
                ->with('error', 'No puedes actualizar jugadores de otros equipos.');
        }
        
        $validatedData = $this->validateJugador($request, $jugador->id);
        
        // Verificar si el capitán está intentando cambiar el equipo del jugador
        if (Auth::user()->hasRole('Capitán') && $validatedData['equipo_id'] != Auth::user()->equipo_id) {
            return redirect()->route('jugador.edit', $jugador)
                ->with('error', 'No puedes cambiar el jugador a otro equipo.');
        }
        
        if ($request->hasFile('foto')) {
            if ($jugador->foto && $jugador->foto != 'img/default-player.png') {
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
        // Verificar si el usuario es capitán y está intentando eliminar un jugador de otro equipo
        if (Auth::user()->hasRole('Capitán') && Auth::user()->equipo_id != $jugador->equipo_id) {
            return redirect()->route('jugador.index')
                ->with('error', 'No puedes eliminar jugadores de otros equipos.');
        }
        
        if ($jugador->foto && $jugador->foto != 'img/default-player.png') {
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

