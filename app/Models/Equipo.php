<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'logo', 'color_primario', 'color_secundario', 'instagram', 'tiktok', 'estado'];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function jugadores()
    {
        return $this->hasMany(Jugador::class);
    }

    public function partidos()
    {
        return $this->belongsToMany(Partido::class, 'partido_equipo')->withPivot('goles');
    }

    public function torneos()
    {
        return $this->belongsToMany(Torneo::class, 'equipos_torneo')
                    ->withPivot('grupo_id', 'puntos', 'partidos_jugados', 'victorias', 'empates', 'derrotas', 'goles_favor', 'goles_contra');
    }

    // Método para obtener jugadores inscritos en un torneo específico
    public function jugadoresEnTorneo($torneoId)
    {
        return $this->jugadores()
                    ->whereHas('torneos', function($query) use ($torneoId) {
                        $query->where('torneo_id', $torneoId)
                              ->where('activo', true);
                    })
                    ->get();
    }
    
    // Método para obtener jugadores NO inscritos en un torneo específico
    public function jugadoresNoEnTorneo($torneoId)
    {
        return $this->jugadores()
                    ->whereDoesntHave('torneos', function($query) use ($torneoId) {
                        $query->where('torneo_id', $torneoId);
                    })
                    ->orWhereHas('torneos', function($query) use ($torneoId) {
                        $query->where('torneo_id', $torneoId)
                              ->where('activo', false);
                    })
                    ->get();
    }
}