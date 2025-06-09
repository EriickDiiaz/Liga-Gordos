<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'tipo', 'fecha_inicio', 'fecha_fin', 'estado'];

    protected $casts = [
        'fecha_inicio' => 'date',
    ];

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipos_torneo')
                    ->withPivot('grupo_id', 'puntos', 'partidos_jugados', 'victorias', 'empates', 'derrotas', 'goles_favor', 'goles_contra');
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class);
    }

    // Nueva relación para jugadores
    public function jugadores()
    {
        return $this->belongsToMany(Jugador::class, 'jugadores_torneo')
                    ->withPivot('equipo_id', 'goles', 'tarjetas_amarillas', 'tarjetas_rojas', 'porterias_imbatidas', 'activo')
                    ->withTimestamps();
    }
    
    // Método para obtener jugadores de un equipo específico en este torneo
    public function jugadoresDeEquipo($equipoId)
    {
        return $this->jugadores()
                    ->wherePivot('equipo_id', $equipoId)
                    ->wherePivot('activo', true)
                    ->get();
    }
    
    // Método para verificar si un equipo ha alcanzado el límite de jugadores
    public function equipoAlcanzoLimite($equipoId, $limite = 15)
    {
        return $this->jugadoresDeEquipo($equipoId)->count() >= $limite;
    }
}

