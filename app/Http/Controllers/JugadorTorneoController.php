<?php

namespace App\Http\Controllers;

use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\JugadorTorneo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JugadorTorneoController extends Controller
{
    // Configuración del límite de jugadores por torneo
    protected $limiteJugadores = 15;
    
    // Mostrar todos los equipos de un torneo para seleccionar
    public function index($torneoId)
    {
        $torneo = Torneo::with('equipos')->findOrFail($torneoId);
        
        return view('plantillas.index', compact('torneo'));
    }
    
    // Mostrar la plantilla de un equipo específico en un torneo
    public function show($torneoId, $equipoId)
    {
        $torneo = Torneo::findOrFail($torneoId);
        $equipo = Equipo::findOrFail($equipoId);
        
        // Verificar que el equipo pertenece al torneo
        if (!$torneo->equipos->contains($equipoId)) {
            return redirect()->route('plantillas.index', $torneoId)
                ->with('error', 'El equipo seleccionado no participa en este torneo.');
        }
        
        // Jugadores ya inscritos en el torneo
        $jugadoresInscritos = $equipo->jugadoresEnTorneo($torneoId);
        
        // Jugadores disponibles para inscribir
        $jugadoresDisponibles = $equipo->jugadoresNoEnTorneo($torneoId);
        
        // Verificar si se alcanzó el límite
        $limiteAlcanzado = $torneo->equipoAlcanzoLimite($equipoId, $this->limiteJugadores);
        
        return view('plantillas.show', compact(
            'torneo', 
            'equipo', 
            'jugadoresInscritos', 
            'jugadoresDisponibles', 
            'limiteAlcanzado'
        ));
    }
    
    // Agregar un jugador a la plantilla del torneo
    public function agregarJugador(Request $request, $torneoId, $equipoId)
    {
        $request->validate([
            'jugador_id' => 'required|exists:jugadores,id'
        ]);
        
        $torneo = Torneo::findOrFail($torneoId);
        $equipo = Equipo::findOrFail($equipoId);
        $jugador = Jugador::findOrFail($request->jugador_id);
        
        // Verificar que el jugador pertenece al equipo
        if ($jugador->equipo_id != $equipoId) {
            return back()->with('error', 'El jugador no pertenece a este equipo.');
        }
        
        // Verificar límite de jugadores
        if ($torneo->equipoAlcanzoLimite($equipoId, $this->limiteJugadores)) {
            return back()->with('error', "No se pueden agregar más jugadores. El límite es {$this->limiteJugadores}.");
        }
        
        try {
            // Verificar si el jugador ya estaba en el torneo pero inactivo
            $existente = JugadorTorneo::where('jugador_id', $jugador->id)
                                      ->where('torneo_id', $torneo->id)
                                      ->first();
            
            if ($existente) {
                $existente->update(['activo' => true]);
                $mensaje = 'Jugador reactivado en la plantilla del torneo.';
            } else {
                // Crear nueva inscripción
                JugadorTorneo::create([
                    'jugador_id' => $jugador->id,
                    'torneo_id' => $torneo->id,
                    'equipo_id' => $equipo->id,
                    'activo' => true
                ]);
                $mensaje = 'Jugador agregado a la plantilla del torneo.';
            }
            
            return redirect()->route('plantillas.show', [$torneoId, $equipoId])
                ->with('success', $mensaje);
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error al agregar el jugador: ' . $e->getMessage());
        }
    }
    
    // Quitar un jugador de la plantilla del torneo
    public function quitarJugador(Request $request, $torneoId, $equipoId, $jugadorId)
    {
        $torneo = Torneo::findOrFail($torneoId);
        $jugador = Jugador::findOrFail($jugadorId);
        
        try {
            // En lugar de eliminar, marcamos como inactivo
            JugadorTorneo::where('jugador_id', $jugadorId)
                         ->where('torneo_id', $torneoId)
                         ->update(['activo' => false]);
            
            return redirect()->route('plantillas.show', [$torneoId, $equipoId])
                ->with('success', 'Jugador removido de la plantilla del torneo.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error al quitar el jugador: ' . $e->getMessage());
        }
    }
    
    // Actualizar estadísticas de un jugador en el torneo
    public function actualizarEstadisticas(Request $request, $torneoId, $equipoId, $jugadorId)
    {
        $request->validate([
            'goles' => 'nullable|integer|min:0',
            'tarjetas_amarillas' => 'nullable|integer|min:0',
            'tarjetas_rojas' => 'nullable|integer|min:0',
            'porterias_imbatidas' => 'nullable|integer|min:0',
        ]);
        
        try {
            $jugadorTorneo = JugadorTorneo::where('jugador_id', $jugadorId)
                                         ->where('torneo_id', $torneoId)
                                         ->firstOrFail();
            
            $jugadorTorneo->update([
                'goles' => $request->goles ?? $jugadorTorneo->goles,
                'tarjetas_amarillas' => $request->tarjetas_amarillas ?? $jugadorTorneo->tarjetas_amarillas,
                'tarjetas_rojas' => $request->tarjetas_rojas ?? $jugadorTorneo->tarjetas_rojas,
                'porterias_imbatidas' => $request->porterias_imbatidas ?? $jugadorTorneo->porterias_imbatidas,
            ]);
            
            return redirect()->route('plantillas.show', [$torneoId, $equipoId])
                ->with('success', 'Estadísticas actualizadas correctamente.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar estadísticas: ' . $e->getMessage());
        }
    }
}
